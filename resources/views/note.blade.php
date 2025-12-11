<style>
    .card-wrapper {
        background: #0F172A;
        padding: 22px;
        border-radius: 16px;
        border: 1px solid #334155;
    }

    .note-card {
        background: #1e293b;
        border: 1px solid #374151;
        border-radius: 14px;
        padding: 20px;
        transition: .25s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .note-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 15px rgba(59,130,246,0.25);
    }

    .btn-note {
        font-size: 13px;
        padding: 8px 0;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: all .25s ease;
        text-align: center;
        border: 1px solid transparent;
    }

    .btn-edit-note {
        background: #1e293b;
        border: 1px solid #3b82f6;
        color: #60a5fa;
    }

    .btn-edit-note:hover {
        background: #3b82f6;
        color: #fff;
        box-shadow: 0 0 10px rgba(59,130,246,0.4);
    }

    .btn-delete-note {
        background: #1e293b;
        border: 1px solid #f87171;
        color: #fca5a5;
    }

    .btn-delete-note:hover {
        background: #ef4444;
        color: #fff;
        box-shadow: 0 0 10px rgba(239,68,68,0.4);
    }

</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .btn-note {
        font-size: 13px;
        padding: 6px 0;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        height: 38px;
        cursor: pointer;
        transition: all .25s ease;
        border: 1px solid transparent;
    }

    .btn-edit-note {
        background: #1e293b;
        border: 1px solid #3b82f6;
        color: #60a5fa;
    }
    .btn-edit-note:hover {
        background: #3b82f6;
        color: white;
        box-shadow: 0 0 10px rgba(59,130,246,0.25);
    }

    .btn-delete-note {
        background: #1e293b;
        border: 1px solid #ef4444;
        color: #fca5a5;
    }
    .btn-delete-note:hover {
        background: #ef4444;
        color: white;
        box-shadow: 0 0 10px rgba(239,68,68,0.25);
    }

    .note-card {
        background: #1e293b;
        border: 1px solid #374151;
        border-radius: 14px;
        padding: 18px;
        transition: .25s;
        cursor: pointer;
    }

    .note-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 15px rgba(59,130,246,0.2);
    }
</style>


<div class="card" style="background:#0F172A; border:1px solid #1e293b; border-radius:14px; padding:22px;">
    
    <div class="d-flex align-items-center gap-2 mb-3">
        <h4 class="mb-0" style="color:#e2e8f0;">Note</h4>

        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal" style="padding:5px 10px;">
            Add New Note
        </button>
    </div>

    <div class="row g-3">
        @foreach($note as $data)
        <div class="col-md-4 col-lg-3">
            <div class="note-card">
                <a href="{{ url('pages/' . $data->id_note) }}" 
                   class="fw-semibold"
                   style="color:#93c5fd; text-decoration:none; font-size:17px;">
                    {{ $data->note_title }}
                </a>

                <div class="mt-2 text-muted small">
                    Total Pages: <span class="fw-semibold">{{ $data->pages_count }}</span>
                </div>

                <div class="d-flex gap-2 mt-3">

                    <button 
                        class="btn-note btn-edit-note editNoteBtn"
                        data-id="{{ $data->id_note }}" 
                        data-title="{{ $data->note_title }}"
                        data-bs-toggle="modal" 
                        data-bs-target="#editNoteModal">
                        Edit
                    </button>

                    <form action="/delete_note/{{ $data->id_note }}" method="POST" class="deleteForm w-100">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-note btn-delete-note">
                            Delete
                        </button>
                    </form>

                </div>
            </div>
        </div>
        @endforeach

        @if(count($note) == 0)
            <div class="col-12 text-center text-muted py-5">
                <p class="mb-0">No notes yet — click Add New Note</p>
            </div>
        @endif

    </div>
</div>


<div class="modal fade" id="addNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#0F172A; color:white; border-radius:12px; border:1px solid #334155;">
            
            <div class="modal-header" style="border-bottom:1px solid #334155;">
                <h5 class="modal-title">Add Note</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('t_note') }}" method="POST">
                @csrf

                <input type="hidden" name="id_room" value="{{ $room->id_room }}">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="color:#cbd5e1;">Note Title</label>
                        <input type="text" class="form-control" name="note_title" required
                        style="background:#1E293B; border:1px solid #334155; color:white;">
                    </div>
                </div>

                <div class="modal-footer" style="border-top:1px solid #334155;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>


<div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:#0F172A; color:white; border-radius:12px; border:1px solid #334155;">
            
            <div class="modal-header" style="border-bottom:1px solid #334155;">
                <h5 class="modal-title">Edit Note</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="updateNoteForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-gray-300">Note Title</label>
                        <input type="text" class="form-control" id="editNoteTitle" name="note_title" required 
                        style="background:#1E293B; border:1px solid #334155; color:white;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #334155;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.editNoteBtn').forEach(button => {
    button.addEventListener('click', function () {

        const noteId = this.getAttribute('data-id');
        const noteTitle = this.getAttribute('data-title');

        document.getElementById('editNoteTitle').value = noteTitle;

        // Set form action dynamically
        document.getElementById('updateNoteForm').action = `/e_note/${noteId}`;
    });
});

document.querySelectorAll('.deleteForm').forEach(form => {

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: "Delete Note?",
            text: "This action cannot be undone.",
            icon: "warning",
            background: "#0F172A",
            color: "#e2e8f0",
            iconColor: "#ef4444",
            customClass: {
                popup: 'dark-alert',
            },
            showCancelButton: true,
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#ef4444",
            cancelButtonColor: "#334155",
        }).then(result => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

});

</script>
