<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border">
            <div class="card-header">
                <h4 class="mb-0">My Profile</h4>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="text-center mb-3">
                        <img 
                            id="previewPhoto" 
                            src="{{ $user->foto ? asset('storage/profile/' . $user->foto) : asset('default-avatar.png') }}"
                            class="img-thumbnail rounded-circle"
                            style="width: 120px; height: 120px; object-fit: cover;"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Change Photo</label>
                        <input type="file" class="form-control" name="foto" onchange="previewImage(event)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                    </div>

                    <hr>

                    <h6>Change Password (optional)</h6>
                    <p class="text-muted small">Leave blank if you don't want to change your password.</p>

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const img = document.getElementById("previewPhoto");
    img.src = URL.createObjectURL(event.target.files[0]);
}
</script>
