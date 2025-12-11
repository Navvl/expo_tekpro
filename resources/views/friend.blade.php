<style>
    .friend-card {
        background: #1e293b;
        border: 1px solid #374151;
        border-radius: 14px;
        padding: 10px;
        transition: .25s;
        display: flex;
        align-items: center;
        gap: 14px;
        cursor: pointer;
        justify-content: center;
    }

    .friend-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0 15px rgba(59,130,246,.25);
    }

    .friend-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #334155;
    }

    .friend-username {
        font-size: 15px;
        font-weight: 600;
        color: #E2E8F0;
        margin: 0;
    }

    #notifBadge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ef4444;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 11px;
        font-weight: 600;
        padding: 2px;
    }

    /* BUTTON ACCEPT - biru soft */
    .btn-accept {
        background: #3b82f6 !important;   /* Tailwind Blue-500 */
        border: 1px solid #2563eb !important;
        color: #fff !important;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 8px;
        transition: .2s;
    }
    .btn-accept:hover {
        background: #2563eb !important;
    }

    /* BUTTON REJECT - merah dark (bukan putih) */
    .btn-reject {
        background: #b91c1c !important;   /* Red-700 */
        border: 1px solid #991b1b !important;
        color: #fff !important;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 8px;
        transition: .2s;
    }
    .btn-reject:hover {
        background: #991b1b !important;
    }
    .swal2-confirm-gradient {
        background: linear-gradient(135deg, #4facfe, #00f2fe) !important;
        border: none !important;
        color: #fff !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
    }

    /* tombol cancel outline */
    .swal2-cancel-outline {
        background: transparent !important;
        border: 1px solid #64748b !important;
        color: #fff !important;
        border-radius: 8px !important;
    }

</style>


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 d-flex align-items-center gap-3">
                    Friend

                    <div class="position-relative" style="cursor:pointer;" id="friendBell">
                        <i class="fa-solid fa-bell fa-lg"></i>

                        @php
                            $pendingCount = \App\Models\Friend::where('id_user_friended', Session::get('id'))
                                            ->where('status', 0)
                                            ->count();
                        @endphp

                        @if($pendingCount > 0)
                            <span id="notifBadge">{{ $pendingCount }}</span>
                        @endif
                    </div>
                </h4>
            </div>

            <div class="card-body">

                <input type="text" 
                       id="searchFriend" 
                       placeholder="Search name..."
                       class="form-control mb-3">

                <div id="friendCardWrapper" class="row g-3"></div>


                <table id="datatable" class="d-none">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Foto</th>
                            <th>ID User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($friends as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->username }} <button class="btn btn-danger btn-sm ms-2" onclick="unfriend({{ $data->id_user }})">Unfriend</button></td>
                            <td>
                                <img src="{{ $data && $data->foto ? asset('storage/profile/' . $data->foto) : asset('images/avatars/01.png') }}" 
                                     class="friend-avatar">
                            </td>
                            <td>{{ $data->id_user }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="friendRequestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#1e293b; border:1px solid #334155; color:#E2E8F0;">

            <div class="modal-header border-0">
                <h5 class="modal-title">Friend Requests</h5>
                <button type="button" class="btn-close" style="filter: invert(1);" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                @php
                    $requests = \App\Models\Friend::where('id_user_friended', Session::get('id'))
                                    ->where('status', 0)
                                    ->get();
                @endphp

                @if($requests->isEmpty())
                    <p class="text-center text-muted py-2">No pending requests.</p>
                @else
                    @foreach($requests as $req)
                        @php 
                            $user = \App\Models\User::find($req->id_user); 
                        @endphp

                        <div class="request-row d-flex align-items-center justify-content-between p-2 mb-2"
                             style="background:#0f172a;border:1px solid #334155;border-radius:10px;"
                             data-id="{{ $req->id_friend }}">

                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $user && $user->foto ? asset('storage/profile/' . $user->foto) : asset('images/avatars/01.png')}}"
                                     style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                <strong>{{ $user->username }}</strong>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-accept accept-btn">Accept</button>
                                <button class="btn btn-sm btn-reject reject-btn">Reject</button>
                            </div>

                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    /* DATATABLE + CARD TRANSFORM */
    let table = new DataTable("#datatable", {
        paging: false,
        info: false,
        searching: true,
        ordering: false,
        dom: "t"
    });

    const wrapper = document.getElementById("friendCardWrapper");

    function renderCards() {
    wrapper.innerHTML = "";
    const data = table.rows({ search: "applied" }).data();
    const totalRows = table.rows().data().length;
    const searchValue = document.getElementById("searchFriend").value.trim();

    // --- Tidak punya friend sama sekali ---
    if (totalRows === 0) {
        wrapper.innerHTML = `
            <div class="col-12 text-center text-muted py-3">No friend yet.</div>
        `;
        return;
    }

    // --- Ada friend tapi hasil search kosong ---
    if (searchValue !== "" && data.length === 0) {
        wrapper.innerHTML = `
            <div class="col-12 text-center text-muted py-3">No results found.</div>
        `;
        return;
    }

    // --- Tampilkan card normal ---
    data.each(row => {
        wrapper.innerHTML += `
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="friend-card">
                ${row[2]}
                <p class="friend-username">${row[1]}</p>
            </div>
        </div>`;
    });
}


    renderCards();

    document.getElementById("searchFriend").addEventListener("keyup", function () {
        table.search(this.value).draw();
        renderCards();
    });

    table.on("draw", renderCards);

    document.getElementById("friendBell")?.addEventListener("click", () => {
        new bootstrap.Modal(document.getElementById("friendRequestModal")).show();
    });

    async function processRequest(action, id, row) {
        let response = await fetch(`/friend-request/${action}/${id}`, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        });

        if (!response.ok) return;


        let data = await response.json();


        if (action === "accept" && data.status === "success") {
            addFriendCard(data.user); 
            table.row.add([
            table.rows().count() + 1,
            data.user.username,
            `<img src="${data.user.foto ? `/storage/profile/${data.user.foto}` : '/images/avatars/01.png'}" class="friend-avatar">`
        ]).draw();
        }

        row.style.opacity = "0";
        setTimeout(() => {
            row.remove();
            updateBadge();
            updateMenuBadge();
        }, 200);
    }


    document.addEventListener("click", (e) => {
        if (e.target.classList.contains("accept-btn") || e.target.classList.contains("reject-btn")) {
            let btn = e.target;

            if (btn.disabled) return;
            btn.disabled = true;

            let row = e.target.closest(".request-row");
            let id = row.dataset.id;
            let action = e.target.classList.contains("accept-btn") ? "accept" : "reject";

            processRequest(action, id, row);
        }
    });

    /* Update Notification Badge */
    function updateBadge() {
        const badge = document.getElementById("notifBadge");
        const remaining = document.querySelectorAll(".request-row").length;

        if (badge) {
            if (remaining > 0) badge.textContent = remaining;
            else badge.remove();
        }
    }

    function addFriendCard(user) {
        const wrapper = document.getElementById("friendCardWrapper");

        let newCard = document.createElement("div");
        newCard.className = "col-md-4 col-lg-3 mb-3";
        newCard.innerHTML = `
            <div class="friend-card">
                <img src="${user.foto ? `/storage/profile/${user.foto}` : '/images/avatars/01.png'}"
                    class="friend-avatar">
                <p class="friend-username">${user.username}</p>
            </div>
        `;

        wrapper.prepend(newCard); // prepend biar muncul paling atas

        // Optional animasi
        newCard.style.opacity = "0";
        setTimeout(() => newCard.style.opacity = "1", 50);
    }

});

function unfriend(id_user) {
    Swal.fire({
        title: 'Delete this friend?',
        text: "Once removed, you must add them again to restore.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove',
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
        }
    }).then(result => {

        if (!result.isConfirmed) return;

        fetch(`/unfriend/${id_user}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === "success") {

                let table = $("#datatable").DataTable();

                // hapus row dari datatable
                table.rows().every(function () {
                    let rowData = this.data();
                    if (rowData && rowData[3] == id_user) {
                        this.remove();
                    }
                });
                table.draw();

                // hapus card UI
                document.querySelectorAll(".friend-card").forEach(card => {
                    if (card.dataset.id == id_user) {
                        card.parentElement.remove();
                    }
                });

                Swal.fire({
                    title: "Removed",
                    text: "Friend has been deleted.",
                    icon: "success",
                    background: 'rgba(20, 30, 48, 0.95)',
                    color: '#fff',
                    confirmButtonColor: '#4facfe',
                });

                updateMenuBadge();
            }
        });
    });
}

</script>
