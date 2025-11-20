<style>
.friend-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    border-radius: 12px;
    background: #f8f9fb;
    margin-bottom: 10px;
    border: 1px solid #e0e6ed;
    transition: 0.2s ease;
}

.friend-item:hover {
    background: #eef7ff;
    border-color: #bcdcff;
}

.friend-avatar {
    width: 48px; 
    height: 48px; 
    border-radius: 50%; 
    margin-right: 14px;
    object-fit: cover;
}

.friend-info {
    flex: 1;
}

.friend-name {
    font-size: 16px;
    font-weight: 600;
}

.friend-email {
    font-size: 13px;
    color: #777;
}

.friend-add-btn {
    background: #4facfe;
    border: none;
    padding: 8px 18px;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    transition: 0.15s ease;
}

.friend-add-btn:hover {
    background: #3796e8;
}

</style>

<div class="row">
    <div class="col-sm-12">

        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-header d-flex justify-content-between align-items-center" 
                 style="background: linear-gradient(135deg, #4facfe, #00f2fe); color:white; border-radius: 16px 16px 0 0;">

                <div class="header-title">
                    <h4 class="card-title mb-0" style="font-weight:600; color: white;">Add Friend</h4>
                    <small style="opacity: .9;">Search people and connect with them</small>
                </div>

                <i class="icon">
                    <svg class="icon-24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24">
                        <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2z" opacity=".4"></path>
                        <path d="M12 6a3 3 0 1 1-.001 6.001A3 3 0 0 1 12 6zm0 8c3.314 0 6 1.791 6 4v1H6v-1c0-2.209 2.686-4 6-4z"></path>
                    </svg>
                </i>
            </div>

            <div class="card-body p-4">

                <!-- SEARCH BAR -->
                <div class="mb-4">
                    <label for="searchFriend" class="form-label fw-semibold">Search Friend</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchFriend" placeholder="Search by name or email...">
                        <button class="btn btn-primary" id="btnSearchFriend" style="background:#4facfe;border:none;">Search</button>
                    </div>
                </div>

                <!-- RESULT CONTAINER -->
                <div id="friendResult" class="mt-4" style="display:none;">
                    <h5 class="fw-semibold mb-3">Search Results</h5>

                    <div id="friendList" class="list-group">
                        <!-- Dynamically added friend items -->
                    </div>
                </div>

                <!-- EMPTY STATE -->
                <div id="friendEmptyState" class="text-center text-muted py-5" style="display:block;">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" width="90" class="mb-3" style="opacity:0.7;">
                    <p style="font-size:15px;">Search for your friends to add them here.</p>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
let searchTimeout = null;

document.getElementById("searchFriend").addEventListener("keyup", function() {
    const keyword = this.value.trim();

    clearTimeout(searchTimeout);

    if (keyword.length === 0) {
        document.getElementById("friendResult").style.display = "none";
        document.getElementById("friendEmptyState").style.display = "block";
        return;
    }

    // debounce 300ms seperti google
    searchTimeout = setTimeout(() => {
        fetch("/friend/search?keyword=" + encodeURIComponent(keyword))
            .then(res => res.json())
            .then(data => displayFriendResults(data));
    }, 300);
});


function displayFriendResults(data) {
    const list = document.getElementById("friendList");
    const resultBox = document.getElementById("friendResult");
    const empty = document.getElementById("friendEmptyState");

    list.innerHTML = "";

    if (data.length === 0) {
        resultBox.style.display = "block";
        empty.style.display = "none";
        list.innerHTML = `<div class="text-muted text-center py-3">No users found</div>`;
        return;
    }

    data.forEach(user => {
        const item = document.createElement("div");
        item.className = "friend-item";

        item.innerHTML = `
            <img src="/storage/profile/${user.foto}" class="friend-avatar">

            <div class="friend-info">
                <div class="friend-name">${user.username}</div>
                <div class="friend-email">${user.email}</div>
            </div>

            <button class="friend-add-btn" onclick="addFriend(${user.id_user})">Add</button>
        `;

        list.appendChild(item);
    });

    empty.style.display = "none";
    resultBox.style.display = "block";
}


function addFriend(id_user) {
    alert("TODO: Add friend logic for user " + id_user);
}
</script>
