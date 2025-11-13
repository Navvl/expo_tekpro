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
                                <td>{{ $data->pages_code }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Note Title</th>
                                <th>Total Page</th>
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