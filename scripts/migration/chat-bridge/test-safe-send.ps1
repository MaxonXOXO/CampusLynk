param(
    [string]$Port = "9222",
    [string]$PromptFile = "docs/migration/runtime/active_prompt.txt"
)

$tabs = Invoke-RestMethod -Uri "http://127.0.0.1:$Port/json/list"
$chatGptTab = $tabs | Where-Object { $_.url -match "chatgpt\.com" -or $_.title -match "ChatGPT" } | Select-Object -First 1
if (-not $chatGptTab) {
    Write-Error "No ChatGPT tab found"
    exit 1
}

$message = [System.IO.File]::ReadAllText($PromptFile, [System.Text.Encoding]::UTF8)
$jsonEncoded = $message | ConvertTo-Json

$ws = New-Object System.Net.WebSockets.ClientWebSocket
$ws.ConnectAsync([Uri]$chatGptTab.webSocketDebuggerUrl, [System.Threading.CancellationToken]::None).Wait()

$sendScript = '(() => { const text = ' + $jsonEncoded + '; const textarea = document.querySelector("#prompt-textarea"); if (!textarea) return { error: "Composer not found" }; textarea.focus(); document.execCommand("selectAll", false, null); document.execCommand("insertText", false, text); textarea.dispatchEvent(new Event("input", { bubbles: true })); setTimeout(() => { const sendBtn = document.querySelector("button[data-testid=\"send-button\"]") || document.querySelector("button[data-testid=\"fruitjuice-send-button\"]") || document.querySelector("button[aria-label=\"Send prompt\"]"); if (sendBtn && !sendBtn.disabled) { sendBtn.click(); } }, 300); return { success: true, len: text.length }; })()'

$evalPayload = @{
    id = 1
    method = "Runtime.evaluate"
    params = @{
        expression = $sendScript
        returnByValue = $true
    }
} | ConvertTo-Json -Compress

$bytes = [System.Text.Encoding]::UTF8.GetBytes($evalPayload)
$seg = New-Object System.ArraySegment[byte] @(,$bytes)
$ws.SendAsync($seg, [System.Net.WebSockets.WebSocketMessageType]::Text, $true, [System.Threading.CancellationToken]::None).Wait()

$buf = New-Object byte[] 65536
$ms = New-Object System.IO.MemoryStream
do {
    $res = $ws.ReceiveAsync((New-Object System.ArraySegment[byte] @(,$buf)), [System.Threading.CancellationToken]::None)
    $res.Wait()
    $ms.Write($buf, 0, $res.Result.Count)
} while (-not $res.Result.EndOfMessage)

$rawJson = [System.Text.Encoding]::UTF8.GetString($ms.ToArray())
$obj = $rawJson | ConvertFrom-Json
$ws.CloseAsync([System.Net.WebSockets.WebSocketCloseStatus]::NormalClosure, "Done", [System.Threading.CancellationToken]::None).Wait()

Write-Host ($obj.result.result.value | ConvertTo-Json -Depth 5)
