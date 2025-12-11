<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">All Room</h4>
                    <br>
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
                                                    class="dropdown-item text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmQuitModal"
                                                    data-room-id="{{ $value->id_room }}"
                                                >
                                                    Quit Room
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

<div class="modal fade" id="confirmQuitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-0 shadow-lg">

      <div class="modal-header border-0">
        <h5 class="modal-title">Quit Room</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="mb-1">Are you sure you want to quit this room?</p>
        <p class="fw-bold text-warning">You will lose access to this room.</p>
      </div>

      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>

        <form id="quitRoomForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Quit</button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const quitModal = document.getElementById('confirmQuitModal');
    const quitForm  = document.getElementById('quitRoomForm');

    quitModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const roomId = button.getAttribute('data-room-id');
        quitForm.action = "{{ url('/room-quit') }}/" + roomId;
    });

});
</script>