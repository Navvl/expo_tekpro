<style>
    .btn-note {
        font-size: 13px;
        padding: 6px 16px;
        border-radius: 10px;
        font-weight: 600;
        align-items: center;
        gap: 6px;
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
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Note</h4>
                    <br>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                        Add New Note
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped" data-toggle="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Note Title</th>
                                <th>Total Page</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($note as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ url('pages/' . $data->id_note) }}" class="text-decoration-none text-primary fw-semibold">
                                        {{ $data->note_title }}
                                    </a>
                                </td>
                                <td>{{ $data->pages_count }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button 
                                            class="btn-note btn-edit-note editNoteBtn"
                                            data-id="{{ $data->id_note }}" 
                                            data-title="{{ $data->note_title }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editNoteModal">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        <form action="/delete_note/{{ $data->id_note }}" method="POST" class="deleteForm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-note btn-delete-note">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>

                                    </div>

                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Note Title</th>
                                <th>Total Page</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                
                <form action="{{ route('t_note') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_room" value="{{ $room->id_room }}">

                    <div class="mb-3">
                        <label class="form-label">Note Title</label>
                        <input type="text" class="form-control" name="note_title" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>

            </div>

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
