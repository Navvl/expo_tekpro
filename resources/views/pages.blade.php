<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

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

@media (max-width: 800px) {
    .note-container {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="note-container" id="noteContainer">
    <button class="add-note-btn" id="addNoteBtn">
        <div class="add-icon">＋</div>
        Add New Note
        
    </button>
</div>

<script>
const notes = [];
let noteCounter = 0;
const id_user = "{{ Auth::id() }}";
const pagesCode = "{{ $note->pages_code }}";

function createNote() {
    const noteId = 'note-' + (++noteCounter);
    const editorId = 'editor-' + noteCounter;

    const noteCard = document.createElement('div');
    noteCard.className = 'note-card';
    noteCard.dataset.noteId = noteId;

    noteCard.innerHTML = `
        <div class="note-header">
            <h5 contenteditable="true" class="note-title" 
                oninput="updateTitle('${noteId}', this.innerText)">New Page</h5>
            <div class="note-actions">
                <button class="edit-btn" onclick="toggleEdit('${noteId}')">Edit</button>
                <button class="delete-btn" onclick="deleteNote('${noteId}')">Delete</button>
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

    notes.push({
        id: noteId,
        quill: quill,
        title: "New Page",
        page_code: pagesCode,
        id_user: id_user,
        page_id: null // <— awalnya null, nanti diisi saat sukses create
    });

    quill.on('text-change', () => updateNoteContent(noteId));

    savePage(noteId);
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
            note.page_id = data.page_id; // simpan id_page buat update berikutnya
            console.log("✅ Page disimpan:", note.title, "(ID:", note.page_id, ")");
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
        if (!data.success) return console.warn("Tidak ada data page.");

        const container = document.getElementById("noteContainer");
        container.innerHTML = "";

        data.data.forEach((page, i) => {
            const noteId = "note-" + (i + 1);
            const editorId = "editor-" + (i + 1);

            const noteCard = document.createElement("div");
            noteCard.className = "note-card";
            noteCard.dataset.noteId = noteId;

            noteCard.innerHTML = `
                <div class="note-header">
                    <h5 contenteditable="true" class="note-title" 
                        oninput="updateTitle('${noteId}', this.innerText)">
                        ${page.page_title}
                    </h5>
                    <div class="note-actions">
                        <button class="edit-btn" onclick="toggleEdit('${noteId}')">Edit</button>
                        <button class="delete-btn" onclick="deleteNote('${noteId}')">Delete</button>
                    </div>
                </div>
                <div class="note-body">
                    <div class="note-content">${page.page_field || 'Click Edit...'}</div>
                    <div class="editor-wrapper">
                        <div id="${editorId}" class="editor-box"></div>
                    </div>
                </div>
            `;

            container.appendChild(noteCard);

            const quill = new Quill('#' + editorId, {
                theme: 'snow',
                modules: { toolbar: [['bold', 'italic'], ['link']] }
            });

            quill.root.innerHTML = page.page_field || "";

            notes.push({
                id: noteId,
                quill,
                title: page.page_title,
                page_code: page.pages_code,
                id_user: id_user,
                page_id: page.id_page, // penting! simpan id_page
                isEditing: false
            });

            quill.on('text-change', () => updateNoteContent(noteId));
        });

        // tambah tombol Add di akhir
        container.appendChild(document.getElementById('addNoteBtn'));
    })
    .catch(err => console.error("Error loadPages:", err));
}

function deleteNote(noteId) {
    const note = notes.find(n => n.id === noteId);
    if (!note.page_id) return;

    if (confirm('Delete this note?')) {
        fetch(`/page/delete/${note.page_id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-note-id="${noteId}"]`).remove();
                console.log("Note deleted:", note.page_id);
            }
        });
    }
}

function updateTitle(noteId, newTitle) {
    const note = notes.find(n => n.id === noteId);
    if (note) note.title = newTitle;
}

document.getElementById('addNoteBtn').addEventListener('click', createNote);
loadPages();
</script>


