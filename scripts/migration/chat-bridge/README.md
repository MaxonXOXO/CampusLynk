# CampusLynk Migration Chat Bridge Proof of Concept

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Proof-of-Concept & Channel Investigation  
> **Target Bridge:** Local Windows Environment $\longleftrightarrow$ ChatGPT (Architect/Supervisor)  
> **Active Branch:** `migration-alpha`  

---

## 1. Objective & Architectural Role

The goal of this communication bridge is to eliminate the manual:
$$\text{Antigravity} \longrightarrow \text{copy} \longrightarrow \text{ChatGPT} \longrightarrow \text{copy} \longrightarrow \text{Antigravity}$$
workflow, and replace it with an automated, reliable bidirectional channel:
$$\text{Antigravity (Orchestrator)} \longleftrightarrow \text{Chat Bridge} \longleftrightarrow \text{ChatGPT (Architect / Supervisor)}$$

This document details the technical investigation of the three available approaches on Windows 11, empirical findings, the selected mechanism, and the operational protocol.

---

## 2. Technical Investigation of Approaches

### Approach A: ChatGPT Desktop App (`OpenAI.Codex_26.915.4065.0_x64__2p2nqsd0c76g0\app\ChatGPT.exe`)
- **Discovery:** Window was successfully detected (`PID: 5188`, `HWND: 5506438`, Class: `Chrome_WidgetWin_1`, Rect: `1280x695`).
- **UI Automation (UIA) Findings:** The ChatGPT Desktop app is built on WebView2 / Electron. Out-of-process UIA traversal reveals only 12 root caption buttons (`RootView`, `Minimize`, `Maximize`, `Close`). The inner web contents and conversation DOM are not exposed to Windows UIAutomation because Chromium accessibility is disabled by default.
- **Input & Output:** While `SetForegroundWindow` and `SendInput` can type text, response completion cannot be determined programmatically, and assistant responses cannot be isolated without DOM access.
- **Verdict:** ❌ **REJECTED.**

---

### Approach C: Clipboard + Window Automation (Fallback)
- **Discovery & Activation:** Window discovery and activation via Win32 `OpenDesktop("default")`, `AttachThreadInput`, and `SetForegroundWindow` were proven functional.
- **Keystroke Injection:** 64-bit `SendInput` with 40-byte explicit struct union was implemented and successfully sent keystrokes.
- **Failure Modes Discovered:**
  1. **Clipboard Session Isolation:** Accessing the Windows clipboard from background tasks or services frequently throws `ExternalException: Requested Clipboard operation did not succeed` due to Win32 clipboard locking.
  2. **Hotkey Collisions:** `Ctrl+Shift+C` is intercepted by Google Chrome as the "Inspect Element / DevTools" hotkey and is unmapped in the Desktop App.
  3. **Response Pollution:** `Ctrl+A` + `Ctrl+C` captures the entire viewport including navigation sidebar, user prompt, and UI chrome, making clean extraction of the assistant's directive impossible.
  4. **No Completion Hook:** There is no programmatic indicator of when ChatGPT has finished generating without OCR or pixel checking.
- **Verdict:** ❌ **REJECTED** (Fragile, prone to race conditions, unable to guarantee clean extraction).

---

### Approach B: ChatGPT Browser via Chrome DevTools Protocol (CDP) — [SELECTED]
- **Mechanism:** Direct WebSocket connection to Chrome via Chrome DevTools Protocol (`--remote-debugging-port=9222`).
- **Why It Is Selected:**
  1. **Direct DOM Access:** Interacts directly with `#prompt-textarea` and `button[data-testid="send-button"]` without focus stealing or mouse movement.
  2. **100% Deterministic Completion Detection:** Monitors the lifecycle of `button[data-testid="stop-button"]`. When the stop button disappears and `button[data-testid="send-button"]` is re-enabled, generation is guaranteed complete.
  3. **Clean Response Isolation:** `document.querySelectorAll('.markdown')[last].innerText` or `document.querySelectorAll('[data-message-author-role="assistant"]')[last].innerText` extracts *only* the assistant's response, completely ignoring user prompts, UI chrome, and previous conversation history.
  4. **Zero Credentials Required:** Does not scrape cookies, tokens, or use OpenAI API keys. Operates within the user's existing, authenticated browser session.
  5. **Background Execution:** Operates silently in the background without stealing mouse or keyboard focus from the user.

---

## 3. Operational Specification (Approach B)

### 3.1 Assumptions & Requirements
- **Operating System:** Windows 10/11 x64.
- **Browser:** Google Chrome (or Microsoft Edge).
- **Target Conversation:** Active tab open to `chatgpt.com` (e.g. `ChatGPT - CampusSynk`).
- **Port:** Port `9222` (configurable via `-Port`).

### 3.2 Setup (One-Time)
To enable the CDP bridge, Chrome must be launched with remote debugging enabled:
```cmd
chrome.exe --remote-debugging-port=9222
```
*Alternatively, add `--remote-debugging-port=9222` to your Chrome desktop shortcut target.*

### 3.3 Protocol Execution Lifecycle

```text
[LOCAL ORCHESTRATOR]
        │
        ├─► 1. Query http://localhost:9222/json/list
        │      Find tab matching 'chatgpt.com' -> webSocketDebuggerUrl
        │
        ├─► 2. Connect ClientWebSocket to ws://localhost:9222/devtools/page/...
        │
        ├─► 3. Send Message:
        │      Runtime.evaluate:
        │      textarea.value = message;
        │      sendButton.click();
        │
        ├─► 4. Wait for Completion:
        │      Poll every 500ms:
        │      is_generating = !!document.querySelector('button[data-testid="stop-button"]');
        │      until (is_generating == false && last_response.length > 0)
        │
        ├─► 5. Extract Response:
        │      Runtime.evaluate:
        │      return document.querySelectorAll('div[data-message-author-role="assistant"]')[last].innerText;
        │
        ▼
[SAVE LOCAL RESPONSE] -> scripts/migration/chat-bridge/test-output.txt
```

### 3.4 Failure & Timeout Handling
- **Timeout:** Default 45 seconds (configurable). If ChatGPT does not finish within the window, the bridge fails closed with `BRIDGE TEST: FAIL (Timeout)`.
- **Target Not Found:** If no ChatGPT tab is open, the bridge reports `CDP is active, but no tab matching 'chatgpt.com' was found.` and exits with code 1.

---

## 4. Test Script Usage

```powershell
# Run the test bridge probe (default test message: 'CampusLynk Migration Bridge Test. Reply with exactly: BRIDGE_OK')
powershell -ExecutionPolicy Bypass -File scripts/migration/chat-bridge/test-bridge.ps1

# Run with custom message and timeout
powershell -ExecutionPolicy Bypass -File scripts/migration/chat-bridge/test-bridge.ps1 -Message "Hello from Antigravity" -TimeoutSeconds 60
```
