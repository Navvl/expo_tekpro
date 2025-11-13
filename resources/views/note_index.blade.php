<!-- Modal Create Note -->
<div class="modal fade" id="createNoteModal" tabindex="-1" aria-labelledby="createNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #1b1e29; border: 1px solid #2b2f3c; border-radius: 16px; box-shadow: 0 0 20px rgba(13,110,253,0.3);">
      <div class="modal-header border-0">
        <h5 class="modal-title text-white fw-semibold" id="createNoteModalLabel">
          <i class="bi bi-journal-plus text-primary"></i>Create New Note
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="createNoteForm" action="{{ route('note.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-light">Note Title</label>
            <input type="text" name="note_title" class="form-control" placeholder="Enter your note title..."
              style="background-color: #2b2f3c; color: #fff; border: none; border-radius: 10px;">
          </div>
        </div>
        <div class="modal-footer border-0 d-flex justify-content-end">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
            style="background-color: #2b2f3c; border: none; border-radius: 10px;">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary"
            style="background: linear-gradient(90deg, #0d6efd, #2a9dff); border: none; border-radius: 10px; box-shadow: 0 0 12px rgba(13,110,253,0.5);">
            + Create Note
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
