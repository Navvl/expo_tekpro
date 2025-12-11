<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">My Room</h4>
                    <br>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        Add New Room
                    </button>
                </div>
            </div>

            <div class="card-body">

                @if ($room->isEmpty())
                    <p>You have no rooms.</p>
                @endif

                <div class="row">
                    @foreach ($room as $value)
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm border">

                                <div class="card-body position-relative">

                                <!-- 3 dots menu -->
                                <div class="position-absolute top-0 end-0 p-2">
                                    <div class="dropdown">
                                        <button class="btn btn-sm text-light" style="background: transparent;" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical fs-5"></i>
                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#inviteUserModal"
                                                    data-room-id="{{ $value->id_room }}"
                                                    data-user-list="{{ $value->access->id_user ?? '' }}"
                                                >
                                                    Invite User
                                                </button>
                                            </li>

                                            <li>
                                                <button 
                                                    class="dropdown-item text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal"
                                                    data-room-id="{{ $value->id_room }}"
                                                >
                                                    Delete
                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                </div>

                                <h5 class="card-title">{{ $value->room_title }}</h5>

                                <p class="mb-1">
                                    <strong>Creator:</strong> {{ $value->user->username ?? 'Unknown' }}
                                </p>

                                <p class="text-muted">
                                    <small>Created at: {{ $value->created_at }}</small>
                                </p>

                                <a href="{{ route('note', ['id_room' => $value->id_room]) }}" class="btn btn-primary btn-sm">Open</a>


                            </div>


                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoomModalLabel">Add Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('t_room') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Room Title</label>
                        <input type="text" class="form-control" name="room_title" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="inviteUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-0 rounded-3 shadow-lg">

      <div class="modal-header border-0">
        <h5 class="modal-title">Invite User To Room</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form id="inviteUserForm" method="POST">
        @csrf

        <div class="modal-body">
          <label class="form-label">Users</label>

          <!-- Chips / selected users -->
          <div id="invitedChips"
               class="d-flex flex-wrap gap-2 p-2 rounded-3 border"
               style="min-height:44px;">
            <!-- chips injected by JS -->
          </div>

          <!-- Search/add area -->
          <div class="mt-3">
            <input id="userSearch" type="text" class="form-control" placeholder="Search users…">
            <div id="userResults" class="list-group mt-2" style="max-height:220px; overflow:auto;">
              <!-- search results injected by JS -->
            </div>
          </div>

          <!-- Hidden inputs for submission (user_id[]) injected by JS -->
          <div id="hiddenUserInputs"></div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Invite</button>
        </div>
      </form>

    </div>
  </div>
</div>



<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-0 shadow-lg">

            <div class="modal-header border-0">
                <h5 class="modal-title">Delete Room</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-1">Are you sure you want to delete this room?</p>
                <p class="fw-bold text-warning">This action cannot be undone.</p>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>

                <form id="deleteRoomForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>

        </div>
    </div>
</div>


@php
    $USERS_JSON = $friends->map(function($u) {
        return [
            'id' => (string)$u->id_user,
            'name' => $u->username,
        ];
    });
@endphp

<script>
    window.ALL_USERS = JSON.parse('{!! addslashes($USERS_JSON->toJson()) !!}');
</script>

<script> 
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('confirmDeleteModal');
    const deleteForm  = document.getElementById('deleteRoomForm');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const roomId = button.getAttribute('data-room-id');

        // Set the DELETE form action dynamically
        deleteForm.action = "{{ url('/room-destroy') }}/" + roomId;
    });


    const inviteModal  = document.getElementById('inviteUserModal');
    const inviteForm   = document.getElementById('inviteUserForm');
    const chipsWrap    = document.getElementById('invitedChips');
    const hiddenWrap   = document.getElementById('hiddenUserInputs');
    const searchInput  = document.getElementById('userSearch');
    const resultsBox   = document.getElementById('userResults');

    // All users map/dataset
    const USERS = (window.ALL_USERS || []); // [{id:'1', name:'Admin'}, ...]
    const byId  = Object.fromEntries(USERS.map(u => [String(u.id), u]));

    let selected = new Set(); // of string user IDs

    function renderChips() {
        chipsWrap.innerHTML = '';
        hiddenWrap.innerHTML = '';
        if (selected.size === 0) {
        const hint = document.createElement('span');
        hint.className = 'text-muted';
        hint.textContent = 'No users selected';
        chipsWrap.appendChild(hint);
        } else {
        [...selected].forEach(id => {
            const u = byId[id];
            const chip = document.createElement('span');
            chip.className = 'badge rounded-pill bg-primary d-inline-flex align-items-center';
            chip.style.gap = '8px';
            chip.textContent = u ? u.name : ('User ' + id);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-link text-light p-0 ms-2';
            btn.innerHTML = '&times;';
            btn.addEventListener('click', () => {
            selected.delete(id);
            renderChips();
            renderResults();
            });

            chip.appendChild(btn);
            chipsWrap.appendChild(chip);

            // hidden input for submission
            const hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'user_id[]';
            hidden.value = id;
            hiddenWrap.appendChild(hidden);
        });
        }
    }

    function renderResults() {
        const q = searchInput.value.trim().toLowerCase();
        resultsBox.innerHTML = '';

        const candidates = USERS.filter(u => !selected.has(String(u.id)));
        const filtered   = q ? candidates.filter(u =>
        String(u.id).includes(q) || (u.name || '').toLowerCase().includes(q)
        ) : candidates;

        if (filtered.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'list-group-item bg-transparent text-muted';
        empty.textContent = 'No users found';
        resultsBox.appendChild(empty);
        return;
        }

        filtered.forEach(u => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'list-group-item list-group-item-action bg-transparent text-light';
        item.textContent = u.name + " (ID: " + u.id + ")";
        item.addEventListener('click', () => {
            selected.add(String(u.id));
            renderChips();
            renderResults();
        });
        resultsBox.appendChild(item);
        });
    }

    // When opening modal, preload selections from trigger button
        inviteModal.addEventListener('show.bs.modal', function (event) {
            const button  = event.relatedTarget;
            const roomId  = button.getAttribute('data-room-id');
            const csv     = (button.getAttribute('data-user-list') || '').trim();

            // Set form action
            inviteForm.action = "{{ url('/invite_user') }}/" + roomId;

            // Reset UI
            searchInput.value = '';
            selected = new Set();

            // Preselect invited users (robust)
            if (csv && csv.length > 0) {
                csv.split(',').forEach(id => {
                    id = id.trim();
                    if (id !== "" && byId[id]) {
                        selected.add(id);
                    }
                });
            }

            renderChips();
            renderResults();
        });


    // As you type, filter
    searchInput.addEventListener('input', renderResults);
});
</script>
