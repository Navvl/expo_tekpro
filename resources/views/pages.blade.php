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
    background: #fff;
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
    color: #333;
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

.swal2-container {
    z-index: 999999 !important;
}

@media (max-width: 800px) {
    .note-container {
        grid-template-columns: 1fr;
    }
}

/* AI STYLEE >>>>>>>>>>>>> */
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

#aiCommandBox {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 360px;
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.25);
    z-index: 20000;
    display: none;
    font-family: "Poppins", sans-serif;
}

.ai-title {
    font-size: 18px;
    margin-bottom: 10px;
    font-weight: 600;
}

#aiInput {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 14px;
    margin-bottom: 12px;
}

.ai-shortcuts {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.ai-shortcuts button {
    flex: 1;
    padding: 8px 0;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 13px;
    color: white;
}

.ai-btn-explain { background: #4facfe; }
.ai-btn-rewrite { background: #6c5ce7; }
.ai-btn-extend  { background: #00b894; }

#aiRunBtn {
    width: 100%;
    background: #0984e3;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 15px;
}

#aiCancelBtn {
    margin-top: 8px;
    width: 100%;
    background: #d63031;
    color: white;
    padding: 8px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 500;
}

#aiResultBox {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 380px;
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.25);
    display: none;
    z-index: 20000;
    font-family: "Poppins", sans-serif;
}

#aiResultContent {
    background: #f7f8fa;
    padding: 12px;
    border-radius: 10px;
    max-height: 200px;
    overflow-y: auto;
    margin-bottom: 14px;
    font-size: 14px;
    color: #333;
}

.ai-result-btn {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    margin-top: 10px;
    font-weight: 600;
    font-size: 15px;
    color: white;
}

.ai-replace-btn { background: #00b894; }
.ai-discard-btn { background: #d63031; }

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

/* COLOR VARIABLES */
:root {
    --hue: 223;
    --primary100: hsl(var(--hue) 90% 95%);
    --primary300: hsl(var(--hue) 90% 75%);
    --primary500: hsl(var(--hue) 90% 55%);
    --primary900: hsl(var(--hue) 90% 15%);
}

/* OVERLAY COVERING WHOLE PAGE */
#aiBlockingOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 999999;
    backdrop-filter: blur(2px);
    display: none; /* hidden by default */
}

/* Center container */
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
    <div class="ai-title">Ask AI</div>

    <input type="text" id="aiInput" placeholder="Type your command...">

    <div class="ai-shortcuts">
        <button class="ai-btn-explain" onclick="setAiShortcut('Explain this')">Explain</button>
        <button class="ai-btn-rewrite" onclick="setAiShortcut('Rewrite clearly')">Rewrite</button>
        <button class="ai-btn-extend" onclick="setAiShortcut('Extend this')">Extend</button>
    </div>

    <button id="aiRunBtn" onclick="submitAiCommand()">Run AI</button>
    <button id="aiCancelBtn" onclick="closeAiBox()">Cancel</button>
</div>

<div id="aiResultBox">
    <div style="font-size:18px;font-weight:600;margin-bottom:10px;">AI Result</div>
    <div id="aiResultContent"></div>

    <button class="ai-result-btn ai-replace-btn" onclick="applyAiReplace()">Replace Selection</button>
    <button class="ai-result-btn ai-discard-btn" onclick="closeAiResult()">Discard</button>
</div>

<div id="aiErrorBox">AI is not available to generate your answer.</div>


<script>
    // AI SECTION >>>>>>>>>>>>>>>>>>>>>>
let currentSelection = "";
let currentQuill = null;
let selectionIndex = null;

document.addEventListener("mouseup", function () {
    const selection = window.getSelection();
    const text = selection.toString().trim();
    const popup = document.getElementById("aiPopup");

    if (!text) {
        popup.classList.remove("visible");
        return;
    }

    const range = selection.getRangeAt(0);
    const rect = range.getBoundingClientRect();

    const scrollX = window.scrollX || window.pageXOffset;
    const scrollY = window.scrollY || window.pageYOffset;

    popup.style.left = `${rect.left + rect.width / 2 + scrollX}px`;
    popup.style.top  = `${rect.top - 45 + scrollY}px`;

    popup.classList.remove("hidden");
    popup.classList.add("visible");

    // find editor
    const editorEl = range.startContainer.parentElement.closest(".ql-editor");
    if (!editorEl) return;

    const editorId = editorEl.parentElement.id;
    const found = notes.find(n => n.quill.root.parentElement.id === editorId);

    currentQuill = found?.quill || null;
    currentSelection = text;

    // QUILL selection index
    if (currentQuill) {
        const q = currentQuill.getSelection();
        if (q && q.length > 0) {
            selectionIndex = q.index;
            selectionLength = q.length;
        }
    }
});


document.getElementById("aiPopup").addEventListener("click", function () {
    this.classList.remove("visible");
    this.classList.add("hidden");

    openAiCommandPopup();
});

function openAiCommandPopup() {
    document.getElementById("aiCommandBox").style.display = "block";
}

function setAiShortcut(text) {
    document.getElementById('aiInput').value = text;
}

function closeAiBox() {
    document.getElementById("aiCommandBox").style.display = "none";
}

function submitAiCommand() {
    const instruction = document.getElementById("aiInput").value.trim();
    if (!instruction) return;

    document.getElementById("aiCommandBox").style.display = "none";
    runInlineAI(instruction);
}

function runInlineAI(instruction) {
    if (!currentQuill || !currentSelection) return;

    const prompt = `${instruction}:\n\n"${currentSelection}"`;

    showAiBlocking(); // BLOCK EVERYTHING

    fetch("/api/ai", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ prompt })
    })
    .then(res => res.json())
    .then(data => {

        hideAiBlocking(); // UNBLOCK

        if (!data || !data.answer) {
            showAiError();
            return;
        }

        const aiText = data.answer.trim();

        document.getElementById("aiPopup").style.display = "none";

        document.getElementById("aiResultContent").innerText = aiText;
        document.getElementById("aiResultBox").style.display = "block";

        window.lastAiText = aiText;
    })
    .catch(err => {
        hideAiBlocking();
        showAiError();
    });
}

function applyAiReplace() {
    if (!currentQuill || selectionIndex === null || selectionLength === null) {
        closeAiResult();
        return;
    }

    currentQuill.deleteText(selectionIndex, selectionLength);
    currentQuill.insertText(selectionIndex, window.lastAiText);

    closeAiResult();

    // Reset
    currentSelection = "";
    selectionIndex = null;
    selectionLength = null;
}

function showAiError() {
    const box = document.getElementById("aiErrorBox");
    box.style.display = "block";

    setTimeout(() => {
        box.style.display = "none";
    }, 3000); // auto-hide after 3 sec
}

function closeAiError() {
    document.getElementById("aiErrorBox").style.display = "none";
}

function closeAiResult() {
    document.getElementById("aiResultBox").style.display = "none";
}

function replaceSelectedText(newText) {
    if (selectionIndex == null || !currentQuill) return;

    const oldLen = currentSelection.length;

    // Remove old text
    currentQuill.deleteText(selectionIndex, oldLen);

    // Insert AI-generated text
    currentQuill.insertText(selectionIndex, newText);

    // Reset temp variables
    currentSelection = "";
    selectionIndex = null;
}

function showAiBlocking() {
    document.getElementById("aiBlockingOverlay").style.display = "block";
}

function hideAiBlocking() {
    document.getElementById("aiBlockingOverlay").style.display = "none";
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
    const overlay = document.getElementById('fullscreenOverlay');
    
    Swal.fire({
        title: 'Delete this page?',
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

                    // Hapus dari notes[] berdasarkan page_id
                    const index = notes.findIndex(n => Number(n.page_id) === Number(noteId));
                    if (index !== -1) {
                        notes.splice(index, 1);
                    } else {
                        console.warn("deleteNote: page_id not found in notes[]", noteId);
                    }

                    const card = document.querySelector(`[data-note-id="${note_ids}"]`);
                    if (card) {
                        card.style.transition = 'opacity 0.4s ease, transform 0.3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        if (card.classList.contains("fullscreen")) {
                            card.classList.remove("fullscreen", "fullscreen-enter", "fullscreen-exit");
                            document.getElementById("fullscreenOverlay").classList.remove("active");
                        }

                        setTimeout(() => {
                            card.remove();
                            rebuildCardState();
                        }, 400);
                    }

                    showNotification('success', 'Note deleted successfully');
                } else {
                    showNotification('error', result.message || 'Failed to delete pages');
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

function rebuildCardState() {
    cardStates = {};  

    document.querySelectorAll(".note-card").forEach(card => {
        const id = card.dataset.id;
        cardStates[id] = { isEditing: false };
    });
}


document.getElementById('addNoteBtn').addEventListener('click', createNote);

loadPages();
</script>


