<style>
/* ==== DARK THEME FOR FRIEND LIST ==== */

.friend-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 14px 18px;
    border-radius: 14px;
    background: #1E293B;                      /* DARK SLATE */
    border: 1px solid #0F172A;                /* DARK NAVY BORDER */
    margin-bottom: 12px;
    transition: all .25s ease;
}

.friend-item:hover {
    border-color: #2563EB;                    /* Deep Blue Hover */
    background: #162234;                      /* Slightly lighter */
}

.friend-avatar {
    width: 48px;
    height: 48px;
    border-radius: 10px;                      /* Rounded modern */
    object-fit: cover;
    background: #0F172A;
    border: 2px solid #2563EB;                /* Blue border */
}

.friend-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.friend-name {
    font-size: 15px;
    font-weight: 600;
    color: #E2E8F0;                           /* light gray text */
    margin: 0;
}

.friend-email {
    font-size: 13px;
    color: #94A3B8;                           /* soft slate text */
    margin-top: 3px;
}

/* PRIMARY BUTTON — BLUE */
.friend-add-btn {
    background: #3B82F6;                      /* Soft Blue */
    border: none;
    padding: 7px 18px;
    border-radius: 10px;
    color: #F1F5F9;
    font-weight: 600;
    font-size: 14px;
    transition: .18s ease;
}

.friend-add-btn:hover:not(.disabled) {
    background: #2563EB;                      /* Deep Blue */
}

/* DISABLED */
.friend-add-btn.disabled {
    background: #475569;
    color: #CBD5E1;
    cursor: not-allowed;
}

/* ACCEPT BUTTON */
.btn-success {
    background: #22c55e !important;
    border-color: #22c55e !important;
    padding: 7px 18px;
    border-radius: 10px;
    font-weight: 600;
    color: #0F172A !important;                /* dark text */
}

.btn-success:hover {
    background: #16a34a !important;
}

/* EMPTY STATE */
#friendEmptyState p {
    color: #64748b;
}

</style>

<div class="row">
    <div class="col-sm-12">

        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header d-flex justify-content-between align-items-center"
                 style="background: #427bff; color:white; border-radius: 15px 15px 0 0;">

                <div>
                    <h5 class="mb-4" style="font-weight:600; color:white;">Add Friend</h5>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Search User</label>
                    <div class="input-group">
                        <input type="text" id="searchFriend" class="form-control" placeholder="Search name or email...">
                        <button id="btnSearchFriend" class="btn btn-primary">Search</button>
                    </div>
                </div>

                <div id="friendResult" style="display:none;">
                    <h6 class="fw-semibold mt-3 mb-3">Search Results</h6>
                    <div id="friendList"></div>
                </div>

                <div id="friendEmptyState" class="text-center text-muted py-5">
                    <p style="font-size:14px;">Start typing to find someone.</p>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
let searchTimeout = null;

document.getElementById("searchFriend").addEventListener("keyup", function () {
    const keyword = this.value.trim();
    clearTimeout(searchTimeout);

    if (keyword === "") {
        document.getElementById("friendResult").style.display = "none";
        document.getElementById("friendEmptyState").style.display = "block";
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch(`/friend/search?keyword=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(data => displayFriendResults(data))
            .catch(err => console.error(err));
    }, 250);
});

function displayFriendResults(data) {
    const list = document.getElementById("friendList");
    const resultBox = document.getElementById("friendResult");
    const empty = document.getElementById("friendEmptyState");

    list.innerHTML = "";

    if (data.length === 0) {
        list.innerHTML = `<div class="text-center text-muted py-3">No users found</div>`;
        resultBox.style.display = "block";
        empty.style.display = "none";
        return;
    }

    data.forEach(user => {
        const div = document.createElement("div");
        div.className = "friend-item";

        let btnLabel = "Add";
        let btnClass = "friend-add-btn";
        let disabled = false;
        let onClick = "";

        switch(user.relation) {
            case "added":
                btnLabel = "Added";
                btnClass += " disabled";
                disabled = true;
                break;
            case "pending":
                btnLabel = "Waiting";
                btnClass += " disabled";
                disabled = true;
                break;
            case "incoming":
                btnLabel = "Accept";
                btnClass = "btn btn-sm btn-success";
                onClick = `onclick="acceptFriend(${user.id_user}, this)"`;
                break;
            default:
                btnLabel = "Add";
                btnClass = "friend-add-btn";
                onClick = `onclick="addFriend(${user.id_user}, this)"`;
                break;
        }

        div.innerHTML = `
            <img src="/storage/profile/${user.foto ?? 'default.png'}" class="friend-avatar">

            <div class="friend-info">
                <p class="friend-name">${user.username}</p>
                <span class="friend-email">${user.email}</span>
            </div>

            <button class="${btnClass}" ${disabled ? "disabled" : ""} ${onClick}>${btnLabel}</button>
        `;

        list.appendChild(div);
    });

    empty.style.display = "none";
    resultBox.style.display = "block";
}

function addFriend(id_user, btn) {
    btn.disabled = true;
    btn.innerText = "Adding...";

    fetch("/friend/add", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id_user: id_user })
    })
    .then(res => res.json())
    .then(resp => {
        if(resp.status === "success") {
            btn.innerText = "Waiting";
            btn.classList.add("disabled");
        } else {
            btn.innerText = "Already";
            btn.classList.add("disabled");
        }
    })
    .catch(() => {
        btn.innerText = "Retry";
        btn.disabled = false;
    });
}

function acceptFriend(id_user, btn) {
    btn.disabled = true;
    btn.innerText = "Accepting...";
    
    fetch("/friend/search_friend_accept", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id_user: id_user })
    })
    .then(res => res.json())
    .then(resp => {
        if(resp.status === "success") {
            btn.innerText = "Added";
            btn.classList.add("disabled");
            btn.classList.remove("btn-success");
            updateMenuBadge();
        } else {
            btn.innerText = "Failed";
            btn.disabled = false;
        }
    })
    .catch(() => {
        btn.innerText = "Retry";
        btn.disabled = false;
    });
}
</script>
