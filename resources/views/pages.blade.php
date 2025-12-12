<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body {
    background-color: #f5f6fa;
    font-family: "Poppins", sans-serif;
}

.note-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.note-card {
    background: #343A37;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 300px;
    display: flex;
    flex-direction: column;
    position: relative;
}

.note-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

.note-header {
    padding: 12px 16px;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.note-header h5 {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: white;
}

.note-header h5[contenteditable="true"]:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    padding: 2px 6px;
}

.note-actions {
    display: flex;
    gap: 8px;
}

.note-actions button {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    padding: 4px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background 0.2s;
}

.note-actions button:hover {
    background: rgba(255, 255, 255, 0.3);
}

.note-body {
    padding: 16px;
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.note-content {
    flex: 1;
    overflow-y: auto;
    line-height: 1.6;
    color: #fff;
}

.note-content .p {
    border-bottom: 1px solid;
    border-color:white;
}

.note-content.empty {
    color: #999;
    font-style: italic;
}

.editor-wrapper {
    display: none;
    flex: 1;
    flex-direction: column;
}

.editor-wrapper.active {
    display: flex;
}

.note-content.hidden {
    display: none;
}

.editor-box {
    flex: 1;
    background: #fff;
    border-radius: 8px;
}

.editor-box .ql-container {
    font-family: "Poppins", sans-serif;
    font-size: 0.95rem;
}

.editor-box .ql-editor {
    min-height: 150px;
    background-color: #343A37;
}

.ql-toolbar{
    background-color: #fff;
}

.add-note-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    border: none;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 12px;
    height: 300px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(79, 172, 254, 0.4);
}

.add-note-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(79, 172, 254, 0.5);
}

.add-icon {
    font-size: 2.5rem;
    margin-bottom: 8px;
}

.delete-btn {
    background: rgba(255, 59, 48, 0.2) !important;
}

.delete-btn:hover {
    background: rgba(255, 59, 48, 0.4) !important;
}

.fullscreen-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    padding: 4px 10px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background 0.2s;
}

.fullscreen-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Efek fullscreen */
/* Overlay latar belakang saat fullscreen aktif */
.fullscreen-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(4px);
    z-index: 9998;
    opacity: 0;
    transition: opacity 0.5s ease;
    pointer-events: none; /* biar gak ngeblok saat belum aktif */
}

/* Saat aktif, biar kelihatan dan nonaktifin klik di luar */
.fullscreen-overlay.active {
    opacity: 1;
    pointer-events: all;
}

/* Efek fullscreen pada note */
.note-card.fullscreen {
    position: fixed !important;
    top: 50%;
    left: 50%;
    width: 90vw;
    height: 90vh;
    transform: translate(-50%, -50%) scale(1);
    z-index: 9999;
    transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    border-radius: 14px;
}

/* Smooth zoom-in/out */
.note-card.fullscreen-enter {
    transform: translate(-50%, -50%) scale(0.85);
    opacity: 0.7;
}
.note-card.fullscreen-exit {
    transform: translate(-50%, -50%) scale(1.08);
    opacity: 0.8;
}

.swal2-rounded {
    border-radius: 15px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    font-family: "Poppins", sans-serif;
}

/* pastikan semua teks di swal putih */
.swal2-white-text,
.swal2-white-text * {
    color: #fff !important;
}

/* progress bar juga ubah warna */
.swal2-progress-white .swal2-timer-progress-bar {
    background: rgba(255, 255, 255, 0.8) !important;
}

/* biar teks lebih kontras */
.swal2-title,
.swal2-html-container {
    color: #fff !important;
}

@media (max-width: 800px) {
    .note-container {
        grid-template-columns: 1fr;
    }
}

/* AI STYLEE >>>>>>>>>>>>> */
.ai-typing {
    opacity: 0;
    transition: opacity 0.25s ease-out;
}

.ai-typing.visible {
    opacity: 1;
}

/* bubble AI hover effect */
.ai-chat-bubble:not(.ai-chat-user) {
    position: relative;
    transition: background 0.15s ease, box-shadow 0.15s ease;
    cursor: pointer;
}

.ai-chat-bubble:not(.ai-chat-user):hover {
    background: #383838;
    box-shadow: 0 0 10px rgba(255,255,255,0.15);
}

/* action menu floating */
/* menu inserted under input row (flow mode) */
.ai-bubble-action-menu {
    background: #2d2d2d;
    padding: 8px 12px;
    border-radius: 8px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.45);
    z-index: 999999;
    display: flex;
    gap: 8px;
    align-items: center;
}

/* style button inside menu */
.ai-bubble-action-menu button {
    background: #00b894;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    color: white;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
}
.ai-bubble-action-menu button:hover { filter: brightness(1.06); }


@keyframes fadeMenu {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}

#aiPopup {
    position: fixed !important;
    background: white;
    padding: 10px 14px;
    border-radius: 10px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.25);
    font-size: 14px;
    font-weight: 600;
    background-color: rgba(0,0,0,0.85);
    cursor: pointer;
    opacity: 0;
    transform: translateY(-8px);
    pointer-events: none;
    z-index: 9999999; /* above everything */
    transition: opacity .15s, transform .15s;
}

#aiPopup.visible {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

#aiPopup.hidden {
    opacity: 0;
    pointer-events: none;
}

/* command box main (tetap fixed but positioned by JS openAiCommandPopup) */
#aiCommandBox {
    position: fixed;
    transform: none;
    width: 50%;
    min-width: 320px;
    max-width: 900px;
    background: #1E1F1E;
    border: 1px solid #333;
    border-radius: 14px;
    padding: 12px 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    z-index: 20000;
    display: none;
    font-family: "Poppins", sans-serif;
    overflow: visible; /* penting supaya result panel bisa muncul */
}

/* chat messages container (above the input row) */
#aiChatMessages {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 10px;
    padding-right: 6px;
}

/* bubble styles */
.ai-chat-bubble {
    background: #2b2b2b;
    color: white;
    padding: 10px 14px;
    border-radius: 10px;
    margin-bottom: 8px;
    font-size: 14px;
    line-height: 1.4;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    word-break: break-word;
}

.ai-chat-user {
    background: #3d3d3d;
    text-align: right;
}

/* input row buttons */
#aiRunBtn {
    margin-left: 8px;
    background: #0984e3;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
}

#aiCancelBtn {
    margin-left: 8px;
    background: #d63031;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
}

/* row tetap compact */
.ai-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: -10px;
}

/* wrapper around input so spinner sits inside right side */
.ai-input-wrap {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

/* input stays the same but ensure padding-right so spinner doesn't overlap text */
#aiInput {
  flex: 1;
  padding: 10px 44px 10px 12px; /* right padding to leave room for spinner/text */
  border-radius: 8px;
  border: none;
  background: #2A2A2A;
  color: white;
  font-size: 14px;
  outline: none;
}

/* spinner circle (CSS only) — hidden by default */
.ai-spinner {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  border: 2px solid rgba(255,255,255,0.12);
  border-top-color: white;
  position: absolute;
  right: 12px;
  display: none;
  box-sizing: border-box;
  animation: aiSpin 0.9s linear infinite;
}

/* thinking text sits left of spinner, hidden by default */
.ai-thinking-text {
  position: absolute;
  right: 40px;
  font-size: 13px;
  color: #d0d0d0;
  display: none;
  white-space: nowrap;
}

/* show states when thinking */
.ai-input-wrap.thinking .ai-spinner,
.ai-input-wrap.thinking .ai-thinking-text {
  display: inline-block;
}

/* optionally dim input text while thinking */
.ai-input-wrap.thinking #aiInput {
  color: #9b9b9b;
}

/* spinner animation */
@keyframes aiSpin {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}

/* result panel (kept for compatibility; hidden in chat-mode) */
#aiResultBox {
    width: 100%;
    background: #232424;
    border-radius: 10px;
    margin-top: 12px;
    padding: 12px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.3);
    color: #e8e8e8;
    display: none; /* kept hidden by default */
    max-height: 320px;
    overflow: auto;
}

#aiResultContent {
    background: #f7f8fa;
    padding: 12px;
    border-radius: 8px;
    color: #222;
    font-size: 14px;
    margin-bottom: 12px;
}

/* result actions */
.ai-result-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

.ai-result-btn {
    padding: 8px 12px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    color: white;
}

.ai-replace-btn { background: #00b894; }
.ai-discard-btn { background: #d63031; }

#aiResultBox.fade-in {
    animation: aiFadeIn .18s ease;
}
@keyframes aiFadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

/* error box + overlay (kept but overlay won't be shown by code) */
#aiErrorBox {
    position: fixed;
    top: 30px;
    right: 30px;
    background: #ff5252;
    color: white;
    padding: 14px 18px;
    border-radius: 12px;
    font-size: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    z-index: 99999;
    display: none;
}

#aiBlockingOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 999999;
    backdrop-filter: blur(2px);
    display: none;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* COLOR VARIABLES (kept) */
:root {
    --hue: 223;
    --primary100: hsl(var(--hue) 90% 95%);
    --primary300: hsl(var(--hue) 90% 75%);
    --primary500: hsl(var(--hue) 90% 55%);
    --primary900: hsl(var(--hue) 90% 15%);
}

/* keep overlay loader styles (unused visually unless you decide later) */
#aiBlockingLoader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
/* STACK ANIMATION */
.stack {
    width: 8em;
    height: 26em;
    position: relative;
    overflow: hidden;
}

.stack__card {
    position: absolute;
    inset: 0;
    width: 70%;
    aspect-ratio: 1;
    margin: auto;
    transform: rotateX(45deg) rotateZ(-45deg);
    transform-style: preserve-3d;
}

/* CARD 1 */
.stack__card:nth-child(1)::before,
.stack__card:nth-child(2)::before,
.stack__card:nth-child(3)::before {
    content: "";
    position: absolute;
    inset: 0;
    display: block;
    border-radius: 7.5%;
    animation: card 2s infinite;
    box-shadow: -0.5em 0.5em 1.5em hsl(var(--hue) 90% 15% / 0.1);
}

/* CARD COLORS + delays */
.stack__card:nth-child(1)::before {
    background: var(--primary500);
}

.stack__card:nth-child(2) {
    top: 0;
}
.stack__card:nth-child(2)::before {
    background: var(--primary300);
    animation-delay: calc(2s * -0.95);
}

.stack__card:nth-child(3) {
    top: -15%;
}
.stack__card:nth-child(3)::before {
    background: var(--primary100);
    animation-delay: calc(2s * -0.9);
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M8 0L1.03553 6.96447C0.372492 7.62751 0 8.52678 0 9.46447V9.54584C0 11.4535 1.54648 13 3.45416 13C4.1361 13 4.80278 12.7981 5.37019 12.4199L7.125 11.25L6 15V16H10V15L8.875 11.25L10.6298 12.4199C11.1972 12.7981 11.8639 13 12.5458 13C14.4535 13 16 11.4535 16 9.54584V9.46447C16 8.52678 15.6275 7.62751 14.9645 6.96447L8 0Z' fill='rgba(0,0,0,0.9)' /%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-size: 45% 45%;
    background-position: center;
}

/* ANIMATION */
@keyframes card {
    0%, 100% {
        transform: translateZ(0);
    }
    11% {
        transform: translateZ(0.125em);
        opacity: 1;
    }
    34% {
        transform: translateZ(-12em);
        opacity: 0;
    }
    48% {
        transform: translateZ(12em);
        opacity: 0;
    }
    57% {
        transform: translateZ(0);
        opacity: 1;
    }
    61% {
        transform: translateZ(-1.8em);
    }
    74% {
        transform: translateZ(0.6em);
    }
    87% {
        transform: translateZ(-0.2em);
    }
}
</style>

<div class="note-container" id="noteContainer">
    <button class="add-note-btn" id="addNoteBtn">
        <div class="add-icon">＋</div>
        Add New Note
    </button>
</div>
<div class="fullscreen-overlay" id="fullscreenOverlay"></div>

<div id="aiPopup" class="hidden">Ask AI ✨</div>

<div id="aiBlockingOverlay">
    <div id="aiBlockingLoader">
        <div class="stack">
            <div class="stack__card"></div>
            <div class="stack__card"></div>
            <div class="stack__card"></div>
        </div>
    </div>
</div>

<div id="aiCommandBox">
    <div id="aiChatMessages"></div>

    <div class="ai-row">

        <div class="ai-icon">✨</div>

        <div class="ai-input-wrap">
            <input type="text" id="aiInput" placeholder="Ask AI anything...">
            <span class="ai-spinner" aria-hidden="true"></span>
            <span class="ai-thinking-text" aria-hidden="true">Thinking…</span>
        </div>

        <button id="aiRunBtn" onclick="submitAiCommand()">↗</button>
        <button id="aiCancelBtn" onclick="closeAiBox()">✕</button>

    </div>

    <!-- kept for compatibility; hidden in chat flow -->
    <div id="aiResultBox" class="collapsed" aria-hidden="true">
        <div style="font-size:16px;font-weight:600;margin-bottom:8px;color:#fff;">AI Result</div>
        <div id="aiResultContent"></div>

        <div class="ai-result-actions">
            <button class="ai-result-btn ai-replace-btn" onclick="applyAiReplace()">Replace Selection</button>
            <button class="ai-result-btn ai-discard-btn" onclick="closeAiResult()">Discard</button>
        </div>
    </div>

</div>

<div id="aiErrorBox">AI is not available to generate your answer.</div>



<script>
  /* ============================================
   AI SECTION (With Deep Debug)
============================================ */

let currentSelection = "";
let currentQuill = null;
let selectionIndex = null;
let selectionLength = null;

/* --------------------------------------------
   1. Detect Text Selection
-------------------------------------------- */
document.addEventListener("mouseup", function (event) {

    // IGNORE MOUSEUP KALAU KLIK DI UI AI
    const target = event.target;
    if (
        target.closest("#aiPopup") ||
        target.closest("#aiCommandBox") ||
        target.closest("#aiResultBox")
    ) {
        // interaction inside AI UI — ignore
        // console.log("Mouseup ignored (AI UI interaction)");
        return;
    }

    const selection = window.getSelection();
    const text = selection.toString().trim();
    const popup = document.getElementById("aiPopup");

    // No text → reset and stop
    if (!text) {
        popup.classList.remove("visible");
        resetAiState();
        return;
    }

    // Rect for popup position
    let range, rect;
    try {
        range = selection.getRangeAt(0);
        rect = range.getBoundingClientRect();
    } catch (err) {
        console.error("Range error:", err);
        return;
    }

    popup.style.left = `${rect.left + rect.width / 2 + window.scrollX}px`;
    popup.style.top = `${rect.top - 45 + window.scrollY}px`;

    popup.classList.remove("hidden", "visible");
    void popup.offsetWidth;
    popup.classList.add("visible");

    // Ensure selection is inside Quill editor
    const editorEl = range.startContainer.parentElement.closest(".ql-editor");

    if (!editorEl) {
        popup.classList.remove("visible");
        return;
    }

    // Ensure editor is in edit mode
    const wrapper = editorEl.closest(".editor-wrapper");
    const isEditing = wrapper?.classList.contains("active");

    if (!isEditing) {
        popup.classList.remove("visible");
        return;
    }

    // Get Quill instance
    const editorId = editorEl.parentElement.id;
    const found = notes.find(n => n.quill.root.parentElement.id === editorId);

    currentQuill = found?.quill || null;

    currentSelection = text;

    if (currentQuill) {
        const q = currentQuill.getSelection(true);
        if (q) {
            selectionIndex = q.index;
            selectionLength = q.length;
            // store snapshot so clicks on UI won't lose the selection
            window._aiStoredSelection = {
                quillId: currentQuill.root.parentElement.id || null,
                quill: currentQuill,
                index: selectionIndex,
                length: selectionLength,
                text: currentSelection
            };
        }
    }

});

/* --------------------------------------------
   2. Ask AI Popup Click
-------------------------------------------- */
document.getElementById("aiPopup").addEventListener("click", function () {
    this.classList.remove("visible");
    this.classList.add("hidden");
    openAiCommandPopup();   
});

/* --------------------------------------------
   3. Open/Close Command Box
-------------------------------------------- */
function openAiCommandPopup() {
    const popup = document.getElementById("aiPopup");
    const box = document.getElementById("aiCommandBox");

    // Ambil posisi popup, tampilkan box di dekat popup
    const rect = popup.getBoundingClientRect();

    box.style.position = "fixed";
    // adjust so box doesn't overflow right edge
    const left = Math.max(8, rect.left + window.scrollX);
    box.style.left = left + "px";

    // place below popup, but ensure visible inside viewport
    const top = rect.bottom + 10 + window.scrollY;
    box.style.top = Math.min(top, window.innerHeight - 120) + "px";

    // reset message area (optional) - keep history? we keep history so not clearing
    // hide resultBox (compat)
    document.getElementById("aiResultBox").style.display = "none";

    box.style.display = "block";

    // focus input for quick typing
    setTimeout(() => {
        const input = document.getElementById("aiInput");
        input.focus();
        input.select?.();
    }, 40);
}

function closeAiBox() {
    document.getElementById("aiCommandBox").style.display = "none";
}

/* --------------------------------------------
   4. Submit AI Command
-------------------------------------------- */
function submitAiCommand() {
    const instruction = document.getElementById("aiInput").value.trim();
    if (!instruction) return;

    // tampilkan sebagai bubble user
    addChatMessage(instruction, true);

    // call AI
    runInlineAI(instruction);
}

/* listen Enter key on input to submit */
document.getElementById("aiInput").addEventListener("keydown", function(e){
    if (e.key === "Enter") {
        e.preventDefault();
        submitAiCommand();
    }
});

/* --------------------------------------------
   5. Run AI (Core Function)
-------------------------------------------- */
function runInlineAI(instruction) {
    if (!currentQuill) {
        console.error("STOP → currentQuill NULL");
        // still show thinking UI to indicate action if selection not required
        // but return
        return;
    }
    if (!currentSelection) {
        console.error("STOP → currentSelection EMPTY");
        return;
    }

    const prompt = `${instruction}:\n\n"${currentSelection}"`;
    console.log("Prompt to API:", prompt);

    // show in-bar thinking UI
    showAiBlocking();

    fetch("/api/ai", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ prompt })
    })
    .then(res => res.json())
    .then(data => {
        // hide thinking UI
        hideAiBlocking();

        if (!data?.answer) {
            console.error("AI returned empty answer");
            return showAiError();
        }

        const aiText = data.answer.trim();

        // hide small floating popup (so it won't overlap)
        document.getElementById("aiPopup").style.display = "none";

        // append result as chat bubble above input
        addChatMessage(aiText, false);

        // restore input
        const input = document.getElementById("aiInput");
        input.value = "";
        input.disabled = false;
        input.focus();

        // keep compatibility: store last text and show aiResultBox (hidden by default)
        window.lastAiText = aiText;
        // Fill aiResultContent (kept for compatibility)
        const resultContent = document.getElementById("aiResultContent");
        if (resultContent) resultContent.innerText = aiText;
        // show hidden resultBox only if you still want it visible (optional)
        // document.getElementById("aiResultBox").style.display = "block";
    })
    .catch(err => {
        hideAiBlocking();
        console.error("AI API ERROR:", err);
        showAiError();
    });
}

/* helper to append chat message */
let activeBubbleMenu = null;
let activeBubbleText = "";

/* Add chat bubble + attach click handler */
function addChatMessage(text, isUser = false) {
    const msgBox = document.getElementById("aiChatMessages");
    const bubble = document.createElement("div");

    bubble.classList.add("ai-chat-bubble");
    if (isUser) bubble.classList.add("ai-chat-user");

    bubble.innerText = text;

    // hanya AI bubble yang bisa di-replace
    if (!isUser) {
        bubble.addEventListener("click", function (e) {
            e.stopPropagation();
            openBubbleActionMenu(bubble, text);
        });
    }

    msgBox.appendChild(bubble);
    msgBox.scrollTop = msgBox.scrollHeight;
}

/* Create floating action menu below bubble */
function openBubbleActionMenu(bubble, text) {
    // close existing
    closeBubbleActionMenu();
    activeBubbleText = text;

    // create menu
    const menu = document.createElement("div");
    menu.classList.add("ai-bubble-action-menu");
    // make it a simple block that will be inserted under the input row
    menu.style.position = "relative";
    menu.style.marginTop = "10px";
    menu.style.display = "flex";
    menu.style.justifyContent = "flex-end";
    menu.innerHTML = `
        <button id="__ai_replace_btn">Replace</button>
    `;

    // insert menu just below the input row, inside aiCommandBox
    const commandBox = document.getElementById("aiCommandBox");
    const row = commandBox.querySelector(".ai-row");
    // insert after the row so menu is visually under the input
    row.insertAdjacentElement('afterend', menu);

    // wire button
    menu.querySelector("#__ai_replace_btn").addEventListener("click", function(e){
        e.stopPropagation();
        replaceSelectedTextFromBubble();
    });

    activeBubbleMenu = menu;
}

function replaceSelectedTextFromBubble() {
    const snap = window._aiStoredSelection;

    if (!snap || !snap.quill) {
        console.error("No stored selection found");
        showAiError();
        return;
    }

    const quill = snap.quill;
    const idx = snap.index;
    const len = snap.length;
    const newText = activeBubbleText || window.lastAiText || "";

    try {
        quill.deleteText(idx, len);
        quill.insertText(idx, newText);
    } catch (err) {
        console.error("Replace error:", err);
        showAiError();
        return;
    }

    closeBubbleActionMenu();
    closeAiBox();
    resetAiState();
}

/* Close menu if clicking somewhere else */
document.addEventListener("click", function () {
    closeBubbleActionMenu();
});

function closeBubbleActionMenu() {
    if (activeBubbleMenu) {
        activeBubbleMenu.remove();
        activeBubbleMenu = null;
        activeBubbleText = "";
    }
}

/* --------------------------------------------
   6. Replace Text in Editor
-------------------------------------------- */
function applyAiReplace() {
    if (!currentQuill) {
        console.error("currentQuill missing");
        return closeAiResult();
    }
    if (selectionIndex === null) {
        console.error("selectionIndex missing");
        return closeAiResult();
    }

    currentQuill.deleteText(selectionIndex, selectionLength);
    currentQuill.insertText(selectionIndex, window.lastAiText || '');

    // close result and box
    closeAiResult();
    closeAiBox();
    resetAiState();
}

function replaceSelectedText(newText) {
    if (!currentQuill || selectionIndex == null) {
        console.error("Cannot replace → no quill or index");
        return;
    }

    currentQuill.deleteText(selectionIndex, currentSelection.length);
    currentQuill.insertText(selectionIndex, newText);

    resetAiState();
}

/* --------------------------------------------
   7. Helpers & UI blocking (in-bar)
-------------------------------------------- */
function resetAiState() {
    currentSelection = "";
    currentQuill = null;
    selectionIndex = null;
    selectionLength = null;

    const popup = document.getElementById("aiPopup");
    popup.classList.remove("hidden");
    popup.classList.remove("visible");
    popup.style.display = ""; // jaga-jaga kalau ada inline style
}

function closeAiResult() {
    document.getElementById("aiResultBox").style.display = "none";
    resetAiState();
}

function showAiError() {
    const box = document.getElementById("aiErrorBox");
    box.style.display = "block";
    setTimeout(() => box.style.display = "none", 3000);
}

/* replace full-page overlay with in-bar thinking UI */
function showAiBlocking() {
    // Enable in-bar thinking UI (spinner + text)
    const wrap = document.querySelector('.ai-input-wrap');
    const input = document.getElementById('aiInput');
    if (wrap && input) {
        wrap.classList.add('thinking');
        input.disabled = true;
        // optionally clear input text while thinking for clearer UX
        // input.dataset._prev = input.value;
        // input.value = '';
    }
    // Do NOT show the full-page overlay; keep it hidden
    // document.getElementById("aiBlockingOverlay").style.display = "block";
}

function hideAiBlocking() {
    const wrap = document.querySelector('.ai-input-wrap');
    const input = document.getElementById('aiInput');
    if (wrap && input) {
        wrap.classList.remove('thinking');
        input.disabled = false;
        // if we cleared value earlier, restore it
        // if (input.dataset._prev !== undefined) { input.value = input.dataset._prev; delete input.dataset._prev; }
    }
    // keep overlay hidden
    // document.getElementById("aiBlockingOverlay").style.display = "none";
}


// NOTES SECTION >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


const notes = [];
let noteCounter = 0;
const id_user = "{{ Auth::id() }}";
const pagesCode = "{{ $note->pages_code }}";
let titleUpdateTimers = {};

async function createNote() {
    try {
        const res = await fetch("/page/store", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                page_title: "New Page",
                page_field: "<p></p>",
                pages_code: pagesCode,
                id_user: id_user
            })
        });

        const data = await res.json();
        const id_page = data.page_id ?? data.id_page ?? data.id ?? null;

        if (!id_page) {
            console.error("Gagal dapat id_page dari server:", data);
            alert("Gagal membuat note. Cek response backend.");
            return;
        }

        const noteId = `page-${id_page}`;
        const editorId = `editor-${id_page}`;

        const noteCard = document.createElement('div');
        noteCard.className = 'note-card';
        noteCard.dataset.noteId = noteId;
        noteCard.dataset.pageId = id_page;

        noteCard.innerHTML = `
            <div class="note-header">
                <button class="fullscreen-btn" onclick="toggleFullscreen('${noteId}')">
                    <i class="fas fa-expand"></i>
                </button>
                <h5 contenteditable="true" class="note-title"
                    oninput="updateTitle('${noteId}', this.innerText)">New Page</h5>
                <div class="note-actions">
                    <button class="edit-btn" onclick="toggleEdit('${noteId}')">Edit</button>
                    <button class="delete-btn" onclick="deleteNote('${id_page}','${noteId}')">Delete</button>
                </div>
            </div>
            <div class="note-body">
                <div class="note-content empty">Click Edit to add your note...</div>
                <div class="editor-wrapper">
                    <div id="${editorId}" class="editor-box"></div>
                </div>
            </div>
        `;

        const container = document.getElementById('noteContainer');
        const addBtn = document.getElementById('addNoteBtn');
        container.insertBefore(noteCard, addBtn);

        const quill = new Quill(`#${editorId}`, {
            theme: 'snow',
            placeholder: 'Write your note here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });

        notes.push({
            id: noteId,
            quill: quill,
            title: "New Page",
            page_code: pagesCode,
            id_user: id_user,
            page_id: id_page,
            isEditing: false
        });

        quill.on('text-change', () => updateNoteContent(noteId));

    } catch (err) {
        console.error("❌ Error createNote:", err);
        alert("Gagal menambah note. Lihat console log untuk detailnya.");
    }
}


function savePage(noteId) {
    const note = notes.find(n => n.id === noteId);
    const html = note.quill.root.innerHTML;
    const url = note.page_id ? `/page/update/${note.page_id}` : '/page/store';

    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            id_page: note.page_id,
            page_title: note.title,
            page_field: html,
            pages_code: pagesCode
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            note.page_id = data.page_id; 
        } else {
            console.warn("⚠️ Gagal menyimpan:", data.message);
        }
    })
    .catch(err => console.error("❌ Error savePage:", err));
}

function toggleEdit(noteId) {
    const note = notes.find(n => n.id === noteId);
    const card = document.querySelector(`[data-note-id="${noteId}"]`);
    const content = card.querySelector('.note-content');
    const editorWrapper = card.querySelector('.editor-wrapper');
    const editBtn = card.querySelector('.edit-btn');

    if (note.isEditing) {
        updateNoteContent(noteId);
        content.classList.remove('hidden');
        editorWrapper.classList.remove('active');
        editBtn.textContent = 'Edit';
        note.isEditing = false;
        savePage(noteId);
    } else {
        content.classList.add('hidden');
        editorWrapper.classList.add('active');
        editBtn.textContent = 'Done';
        note.isEditing = true;
    }
}

function updateNoteContent(noteId) {
    const note = notes.find(n => n.id === noteId);
    const card = document.querySelector(`[data-note-id="${noteId}"]`);
    const content = card.querySelector('.note-content');
    const html = note.quill.root.innerHTML;
    const text = note.quill.getText().trim();

    note.page_field = html;
    if (text.length === 0) {
        content.innerHTML = 'Click Edit to add your note...';
        content.classList.add('empty');
    } else {
        content.innerHTML = html;
        content.classList.remove('empty');
    }
}

function updateTitle(noteId, newTitle) {
    const note = notes.find(n => n.id === noteId);
    if (!note) return;

    note.title = newTitle;


    if (titleUpdateTimers[noteId]) {
        clearTimeout(titleUpdateTimers[noteId]);
    }


    titleUpdateTimers[noteId] = setTimeout(() => {
        fetch('/page/updateTitle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                id_page: note.page_id,
                page_title: note.title
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification('success', 'Updated', 'Page title updated successfully.');
            } else {
                showNotification('warning', 'No Change', data.message || 'Nothing to update.');
            }
        })
        .catch(err => {
            console.error('❌ Error updating title:', err);
            showNotification('error', 'Error', 'Failed to update page title.');
        });
    }, 1200);
}

function loadPages() {
    fetch("/page/getByCode", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ pages_code: pagesCode })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {

            document.querySelectorAll('.note-card').forEach(el => el.remove());

            const container = document.getElementById("noteContainer");

            data.data.forEach((page, index) => {
                const noteId = "note-" + (index + 1);
                const editorId = "editor-" + (index + 1);

                const noteCard = document.createElement("div");
                noteCard.className = "note-card";
                noteCard.dataset.noteId = noteId;
                noteCard.dataset.pageCode = page.pages_code;
                noteCard.dataset.userId = id_user;

                noteCard.innerHTML = `
                    <div class="note-header">
                        <button class="fullscreen-btn" onclick="toggleFullscreen('${noteId}')">
                            <i class="fas fa-expand"></i>
                        </button>
                        <h5 contenteditable="true" class="note-title" 
                            oninput="updateTitle('${noteId}', this.innerText)">
                            ${page.page_title}
                        </h5>
                        <div class="note-actions">
                            <button class="edit-btn" onclick="toggleEdit('${noteId}')">Edit</button>
                            <button class="delete-btn" onclick="deleteNote('${page.id_page}','${noteId}')">Delete</button>
                        </div>
                    </div>
                    <div class="note-body">
                        <div class="note-content">${page.page_field || 'Click Edit to add your note...'}</div>
                        <div class="editor-wrapper">
                            <div id="${editorId}" class="editor-box"></div>
                        </div>
                    </div>
                `;

                const addBtn = document.getElementById("addNoteBtn");
                container.insertBefore(noteCard, addBtn);

                const quill = new Quill('#' + editorId, {
                    theme: 'snow',
                    placeholder: 'Write your note here...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            ['link', 'blockquote', 'code-block'],
                            ['clean']
                        ]
                    }
                });

                quill.root.innerHTML = page.page_field || "";
                notes.push({
                    id: noteId,
                    quill: quill,
                    isEditing: false,
                    title: page.page_title,
                    page_code: page.pages_code,
                    id_user: id_user,
                    page_id: page.id_page 
                });

                quill.on('text-change', function() {
                    updateNoteContent(noteId);
                });
            });

        } else {
            console.warn("Tidak ada data:", data.message);
        }
    })
    .catch(err => console.error("Error loadPages:", err));
}

function toggleFullscreen(noteId) {
    const card = document.querySelector(`[data-note-id="${noteId}"]`);
    const btn = card.querySelector('.fullscreen-btn i');
    const overlay = document.getElementById('fullscreenOverlay');
    
    if (!card.classList.contains('fullscreen')) {

        overlay.classList.add('active');
        card.classList.add('fullscreen-enter');
        requestAnimationFrame(() => {
            card.classList.add('fullscreen');
            card.classList.remove('fullscreen-enter');
        });
        btn.classList.remove('fa-expand');
        btn.classList.add('fa-compress');
    } else {

        card.classList.add('fullscreen-exit');
        overlay.classList.remove('active');
        setTimeout(() => {
            card.classList.remove('fullscreen', 'fullscreen-exit');
        }, 500);
        btn.classList.remove('fa-compress');
        btn.classList.add('fa-expand');
    }
}

function deleteNote(noteId, note_ids) {
    if (!noteId) {
        showNotification('error', 'Note ID not found. Please refresh the page.');
        return;
    }

    Swal.fire({
        title: 'Delete this note?',
        text: "Once deleted, it cannot be restored.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        background: 'rgba(20, 30, 48, 0.95)', 
        color: '#fff',
        confirmButtonColor: '#4facfe',
        cancelButtonColor: '#00f2fe',
        buttonsStyling: true,
        customClass: {
            popup: 'swal2-rounded swal2-white-text',
            title: 'swal2-white-text',
            confirmButton: 'swal2-confirm-gradient',
            cancelButton: 'swal2-cancel-outline'
        },
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/pages/${noteId}/delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    notes.splice(notes.findIndex(n => n.id_page === noteId), 1);

                    const card = document.querySelector(`[data-note-id="${note_ids}"]`);
                    if (card) {
                        card.style.transition = 'opacity 0.4s ease, transform 0.3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        setTimeout(() => card.remove(), 400);
                    }

                    showNotification('success', 'Note deleted successfully');
                } else {
                    showNotification('error', result.message || 'Failed to delete note');
                }
            })
            .catch(() => {
                showNotification('error', 'Error while deleting note');
            });
        }
    });
}



function showNotification(type, message) {
    const bgGradient = 'linear-gradient(135deg, #4facfe, #00f2fe)';
    const iconColor = '#ffffff';

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: bgGradient,
        color: '#fff',
        iconColor: iconColor,
        customClass: {
            popup: 'swal2-rounded swal2-white-text',
            title: 'swal2-white-text',
            htmlContainer: 'swal2-white-text',
            timerProgressBar: 'swal2-progress-white'
        },
    });
}

document.getElementById('addNoteBtn').addEventListener('click', createNote);
loadPages();
</script>


