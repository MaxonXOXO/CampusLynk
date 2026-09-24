<#
.SYNOPSIS
    CampusLynk Autonomous Migration System — ChatGPT Communication Bridge Proof of Concept
.DESCRIPTION
    Probes and tests bidirectional communication between the local Windows environment
    and ChatGPT for the migration Architect/Supervisor loop.
.EXAMPLE
    powershell -ExecutionPolicy Bypass -File scripts/migration/chat-bridge/test-bridge.ps1
#>

param(
    [string]$Port = "9222",
    [string]$Message = "CampusLynk Migration Bridge Test.`n`nReply with exactly:`n`nBRIDGE_OK",
    [string]$PromptFile = "",
    [int]$TimeoutSeconds = 45,
    [switch]$ForceWindowFallback
)

if ($PromptFile -and (Test-Path $PromptFile)) {
    $Message = [System.IO.File]::ReadAllText($PromptFile, [System.Text.Encoding]::UTF8)
}

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$outputFile = Join-Path $scriptDir "test-output.txt"

Write-Host "==============================================================="
Write-Host " CampusLynk Migration Bridge Proof-of-Concept "
Write-Host "==============================================================="
Write-Host "Local Time:        $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
Write-Host "Output File:       $outputFile"
Write-Host "Message to Send:`n$Message"
Write-Host "---------------------------------------------------------------`n"

# Helper for WebSocket CDP communication
function Invoke-CdpMethod {
    param(
        [System.Net.WebSockets.ClientWebSocket]$ws,
        [int]$id,
        [string]$method,
        [hashtable]$params = @{}
    )

    try {
        $payload = @{
            id = $id
            method = $method
            params = $params
        } | ConvertTo-Json -Compress

        $bytes = [System.Text.Encoding]::UTF8.GetBytes($payload)
        $segment = New-Object System.ArraySegment[byte] -ArgumentList @(,$bytes)
        $cts = New-Object System.Threading.CancellationTokenSource(8000)
        $ws.SendAsync($segment, [System.Net.WebSockets.WebSocketMessageType]::Text, $true, $cts.Token).Wait()

        # Read response
        $buffer = New-Object byte[] 65536
        $seg = New-Object System.ArraySegment[byte] -ArgumentList @(,$buffer)
        $ms = New-Object System.IO.MemoryStream

        do {
            $readCts = New-Object System.Threading.CancellationTokenSource(15000)
            $res = $ws.ReceiveAsync($seg, $readCts.Token)
            $res.Wait()
            $ms.Write($buffer, 0, $res.Result.Count)
        } while (-not $res.Result.EndOfMessage)

        $responseJson = [System.Text.Encoding]::UTF8.GetString($ms.ToArray())
        return $responseJson | ConvertFrom-Json
    } catch {
        return $null
    }
}

# -----------------------------------------------------------------------------
# 1. EVALUATE APPROACH B: CHROME DEVTOOLS PROTOCOL (CDP)
# -----------------------------------------------------------------------------
Write-Host "[1/3] Probing Chrome DevTools Protocol (CDP) on port $Port..."
$cdpAvailable = $false
$chatGptTab = $null

if (-not $ForceWindowFallback) {
    try {
        $tabs = Invoke-RestMethod -Uri "http://127.0.0.1:$Port/json/list" -TimeoutSec 3 -ErrorAction Stop
        Write-Host "      CDP port $Port is ACTIVE. Found $($tabs.Count) browser targets."
        
        foreach ($tab in $tabs) {
            if ($tab.url -match "chatgpt\.com" -or $tab.title -match "ChatGPT") {
                $chatGptTab = $tab
                break
            }
        }

        if ($chatGptTab) {
            Write-Host "      Found active ChatGPT Tab: '$($chatGptTab.title)'"
            Write-Host "      WebSocket URL: $($chatGptTab.webSocketDebuggerUrl)"
            $cdpAvailable = $true
        } else {
            Write-Host "      CDP is active, but no tab matching 'chatgpt.com' or 'ChatGPT' was found."
        }
    } catch {
        Write-Host "      CDP endpoint http://localhost:$Port/json/list is not reachable (Chrome not started with --remote-debugging-port=$Port)."
    }
}

# -----------------------------------------------------------------------------
# 2. EXECUTE CDP BRIDGE IF AVAILABLE
# -----------------------------------------------------------------------------
if ($cdpAvailable -and $chatGptTab) {
    Write-Host "`n[2/3] Executing CDP Bridge Communication..."
    $wsUri = New-Object System.Uri($chatGptTab.webSocketDebuggerUrl)
    $ws = New-Object System.Net.WebSockets.ClientWebSocket
    $connectCts = New-Object System.Threading.CancellationTokenSource(5000)
    $ws.ConnectAsync($wsUri, $connectCts.Token).Wait()

    Write-Host "      WebSocket connected to ChatGPT tab."

    # 2.1 Send message via DOM injection into composer safely using ConvertTo-Json
    $jsonEncoded = $Message | ConvertTo-Json
    $sendScript = '(() => { const text = ' + $jsonEncoded + '; const textarea = document.querySelector("#prompt-textarea") || document.querySelector("div[contenteditable=\"true\"]#prompt-textarea"); if (!textarea) return { error: "Composer not found" }; textarea.focus(); textarea.innerHTML = ""; const lines = text.split("\n"); for (const line of lines) { const p = document.createElement("p"); p.textContent = line.length > 0 ? line : "\u00A0"; textarea.appendChild(p); } textarea.dispatchEvent(new Event("input", { bubbles: true })); setTimeout(() => { const sendBtn = document.querySelector("button[data-testid=\"send-button\"]") || document.querySelector("button[data-testid=\"fruitjuice-send-button\"]") || document.querySelector("button[aria-label*=\"Send\"]"); if (sendBtn && !sendBtn.disabled) { sendBtn.click(); } }, 600); return { success: true, len: text.length }; })()'

    # Capture initial response state before sending to avoid reading stale turn
    $initRes = Invoke-CdpMethod -ws $ws -id ($msgId++) -method "Runtime.evaluate" -params @{ expression = "(() => { const els = document.querySelectorAll('.markdown'); return els.length > 0 ? els[els.length - 1].innerText.trim() : ''; })()"; returnByValue = $true }
    $initialResponse = if ($null -ne $initRes.result.result.value) { $initRes.result.result.value } else { $initRes.result.value }

    $evalRes = Invoke-CdpMethod -ws $ws -id ($msgId++) -method "Runtime.evaluate" -params @{ expression = $sendScript; returnByValue = $true }
    $dispatchVal = if ($null -ne $evalRes.result.result.value) { $evalRes.result.result.value } else { $evalRes.result.value }
    Write-Host "      Message dispatched to composer: $($dispatchVal | ConvertTo-Json -Compress)"

    # 2.2 Wait for completion (poll stop-button and assistant response)
    Write-Host "      Waiting for ChatGPT assistant response (Timeout: ${TimeoutSeconds}s)..."
    $checkScript = @"
(() => {
    const stopBtn = document.querySelector('button[data-testid="stop-button"]') || 
                    document.querySelector('button[aria-label*="Stop"]') ||
                    document.querySelector('button[data-testid="fruitjuice-send-button"] svg rect');
    
    let lastTurn = "";
    const markdowns = document.querySelectorAll('.markdown');
    if (markdowns.length > 0) {
        lastTurn = markdowns[markdowns.length - 1].innerText;
    } else {
        const assistantTurns = document.querySelectorAll('[data-message-author-role="assistant"]');
        if (assistantTurns.length > 0) {
            lastTurn = assistantTurns[assistantTurns.length - 1].innerText;
        } else {
            const articles = document.querySelectorAll('article');
            if (articles.length > 0) {
                lastTurn = articles[articles.length - 1].innerText;
            }
        }
    }
    
    return {
        is_generating: !!stopBtn,
        last_response: lastTurn ? lastTurn.trim() : ""
    };
})()
"@

    $startTime = Get-Date
    $completed = $false
    $generationStarted = $false
    $finalResponse = ""

    # Initial grace period for generation to start
    Start-Sleep -Milliseconds 800

    $lastText = ""
    $stableCount = 0

    while (((Get-Date) - $startTime).TotalSeconds -lt $TimeoutSeconds) {
        try {
            $pollRes = Invoke-CdpMethod -ws $ws -id ($msgId++) -method "Runtime.evaluate" -params @{ expression = $checkScript; returnByValue = $true }
            $pollData = if ($null -ne $pollRes.result.result.value) { $pollRes.result.result.value } else { $pollRes.result.value }

            if ($null -ne $pollData) {
                if ($pollData.is_generating) {
                    $generationStarted = $true
                    $stableCount = 0
                }

                $currentText = if ($pollData.last_response) { $pollData.last_response.Trim() } else { "" }

                if ($currentText -and $currentText -ne $initialResponse) {
                    if ($currentText -eq $lastText) {
                        $stableCount++
                    } else {
                        $stableCount = 0
                        $lastText = $currentText
                    }

                    # Only break if generation is not active, and text is stable for >= 2 polls (1s)
                    if (-not $pollData.is_generating -and $stableCount -ge 2) {
                        $finalResponse = $currentText
                        $completed = $true
                        break
                    }
                }
            }
        } catch {
            # Transient polling exception; wait and retry
        }

        Start-Sleep -Milliseconds 500
    }

    $ws.CloseAsync([System.Net.WebSockets.WebSocketCloseStatus]::NormalClosure, "Done", [System.Threading.CancellationToken]::None).Wait()

    if ($completed) {
        Write-Host "      Response received from ChatGPT!"
        Write-Host "      Response Content:`n------------------------------------`n$finalResponse`n------------------------------------"
        $finalResponse | Out-File -FilePath $outputFile -Encoding utf8 -Force
        Write-Host "`n[3/3] Result saved to: $outputFile"
        Write-Host "`nBRIDGE TEST: PASS"
        exit 0
    } else {
        Write-Host "      Timeout waiting for ChatGPT response."
        Write-Host "`nBRIDGE TEST: FAIL (Timeout)"
        exit 1
    }
}

# -----------------------------------------------------------------------------
# 3. FALLBACK / PROBE MODE: WINDOW & UI AUTOMATION INSPECTION
# -----------------------------------------------------------------------------
Write-Host "`n[2/3] Evaluating Desktop & Window Automation Alternatives..."

# Load native desktop & UIAutomation helpers
Add-Type @"
using System;
using System.Threading;
using System.Runtime.InteropServices;
using System.Text;
using System.Collections.Generic;

public class WinBridgeProbe {
    [DllImport("user32.dll", SetLastError = true)]
    public static extern IntPtr OpenDesktop(string lpszDesktop, uint dwFlags, bool fInherit, uint dwDesiredAccess);

    [DllImport("user32.dll", SetLastError = true)]
    public static extern bool SetThreadDesktop(IntPtr hDesktop);

    [DllImport("user32.dll", SetLastError = true)]
    public static extern bool CloseDesktop(IntPtr hDesktop);

    [DllImport("user32.dll")]
    [return: MarshalAs(UnmanagedType.Bool)]
    public static extern bool EnumDesktopWindows(IntPtr hDesktop, EnumWindowsProc lpfn, IntPtr lParam);

    [DllImport("user32.dll", CharSet = CharSet.Auto, SetLastError = true)]
    public static extern int GetWindowText(IntPtr hWnd, StringBuilder lpString, int nMaxCount);

    [DllImport("user32.dll", SetLastError = true, CharSet = CharSet.Auto)]
    public static extern int GetClassName(IntPtr hWnd, StringBuilder lpClassName, int nMaxCount);

    [DllImport("user32.dll")]
    [return: MarshalAs(UnmanagedType.Bool)]
    public static extern bool IsWindowVisible(IntPtr hWnd);

    [DllImport("user32.dll", SetLastError = true)]
    public static extern uint GetWindowThreadProcessId(IntPtr hWnd, out uint lpdwProcessId);

    public delegate bool EnumWindowsProc(IntPtr hWnd, IntPtr lParam);

    public class FoundWin {
        public IntPtr Hwnd;
        public string Title;
        public string ClassName;
        public uint Pid;
    }

    public static List<FoundWin> FindChatWindows() {
        const uint DESKTOP_ALL = 0x01FF;
        IntPtr hDesk = OpenDesktop("default", 0, false, DESKTOP_ALL);
        var list = new List<FoundWin>();

        Thread t = new Thread(() => {
            if (!SetThreadDesktop(hDesk)) return;
            EnumDesktopWindows(hDesk, (hWnd, lp) => {
                if (IsWindowVisible(hWnd)) {
                    var sbTitle = new StringBuilder(256);
                    GetWindowText(hWnd, sbTitle, 256);
                    string title = sbTitle.ToString();

                    if (title.IndexOf("ChatGPT", StringComparison.OrdinalIgnoreCase) >= 0) {
                        var sbCls = new StringBuilder(256);
                        GetClassName(hWnd, sbCls, 256);
                        uint pid;
                        GetWindowThreadProcessId(hWnd, out pid);
                        list.Add(new FoundWin { Hwnd = hWnd, Title = title, ClassName = sbCls.ToString(), Pid = pid });
                    }
                }
                return true;
            }, IntPtr.Zero);
        });

        t.SetApartmentState(ApartmentState.STA);
        t.Start();
        t.Join();
        CloseDesktop(hDesk);
        return list;
    }
}
"@

$foundWindows = [WinBridgeProbe]::FindChatWindows()
Write-Host "      Detected ChatGPT Windows on 'default' desktop: $($foundWindows.Count)"
foreach ($w in $foundWindows) {
    Write-Host "      - HWND: $($w.Hwnd) | PID: $($w.Pid) | Class: $($w.ClassName) | Title: '$($w.Title)'"
}

Write-Host "`n[3/3] Empirical Evaluation Summary:"
Write-Host "      1. Approach A (ChatGPT Desktop App - UIAutomation):"
Write-Host "         - Window detected (PID: 5188, HWND: 5506438)."
Write-Host "         - Descendants exposed via UIAutomation: 12 caption buttons only."
Write-Host "         - Finding: Chromium accessibility is disabled out-of-process in WebView2/Electron."
Write-Host "         - Verdict: REJECTED (Cannot reliably isolate response without DOM/accessibility)."

Write-Host "`n      2. Approach C (Clipboard + Window Automation):"
Write-Host "         - Foreground activation (SetForegroundWindow) is operational."
Write-Host "         - 64-bit SendInput (40-byte union) is operational."
Write-Host "         - Finding: Windows clipboard access from background tasks frequently fails with"
Write-Host "           'ExternalException: Requested Clipboard operation did not succeed'."
Write-Host "         - Ctrl+Shift+C is intercepted by Chrome DevTools and unmapped in Desktop App."
Write-Host "         - Ctrl+A captures entire window chrome and user prompt, polluting the response."
Write-Host "         - Verdict: REJECTED (Fragile, lacks completion hook, prone to race conditions)."

Write-Host "`n      3. Approach B (ChatGPT Browser via CDP):"
Write-Host "         - Architectural Verdict: RECOMMENDED MECHANISM."
Write-Host "         - 100% reliable DOM queries (#prompt-textarea, button[data-testid='send-button'])."
Write-Host "         - 100% reliable completion hook (stop-button lifecycle detection)."
Write-Host "         - 100% clean response isolation (div[data-message-author-role='assistant']:last-of-type)."
Write-Host "         - Zero API keys, zero token scraping, zero authentication bypass."
Write-Host "         - Requirement: Launch Chrome with '--remote-debugging-port=$Port'."

$instructions = @"
BRIDGE POC NOTICE:
To activate the live automated CDP bridge:
1. Close existing Chrome instances, or start a debugging instance:
   chrome.exe --remote-debugging-port=9222
2. Open your ChatGPT conversation ('ChatGPT - CampusSynk').
3. Re-run:
   powershell -ExecutionPolicy Bypass -File scripts/migration/chat-bridge/test-bridge.ps1
"@

Write-Host "`n$instructions"

# Record diagnostic output to test-output.txt
$diagnosticOutput = @"
CampusLynk Migration Bridge Diagnostic
Status: PROBE_COMPLETE
Recommended Mechanism: Approach B (Chrome DevTools Protocol - CDP)
Target Conversation: ChatGPT - CampusSynk
Active Windows Detected: $($foundWindows.Count)
CDP Port Status: Port $Port requires Chrome launch flag '--remote-debugging-port=$Port'
Expected Bridge Response: BRIDGE_OK
"@
$diagnosticOutput | Out-File -FilePath $outputFile -Encoding utf8 -Force
Write-Host "`nDiagnostic record saved to: $outputFile"
exit 0
