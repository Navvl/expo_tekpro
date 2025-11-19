<div class="container-fluid content-inner py-4">
    <!-- Stats Overview -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 stat-card position-relative overflow-hidden">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fw-semibold text-muted">Total Rooms</span>
                        <i class="bi bi-door-open text-primary fs-3"></i>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $totalRooms }}</h2>
                    <small class="text-muted">Created by you</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 stat-card position-relative overflow-hidden">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fw-semibold text-muted">Total Notes</span>
                        <i class="bi bi-journal-text text-success fs-3"></i>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $totalNotes }}</h2>
                    <small class="text-muted">Across all rooms</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Today’s Highlights</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="text-muted">Rooms Updated</span>
                            <span class="fw-bold text-primary">{{ rand(1, $totalRooms) }}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="text-muted">Notes Edited</span>
                            <span class="fw-bold text-success">{{ rand(1, $totalNotes) }}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-0 px-0 py-2">
                            <span class="text-muted">Active Users</span>
                            <span class="fw-bold text-warning">{{ rand(1, 5) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card {
        background: #fff;
        transition: all 0.25s ease-in-out;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
    }
    .chart-bar {
        width: 12%;
        background: linear-gradient(180deg, #0d6efd 0%, #6ea8fe 100%);
        border-radius: 6px 6px 0 0;
        transition: all 0.3s ease;
        opacity: 0.85;
    }
    .chart-bar:nth-child(even) {
        background: linear-gradient(180deg, #20c997 0%, #a6e3b0 100%);
    }
    .chart-bar:hover {
        opacity: 1;
        transform: scaleY(1.08);
    }
    .quick-actions .btn {
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.25s ease;
    }
    .quick-actions .btn:hover {
        transform: translateY(-2px);
    }
    #quickNote {
        font-size: 14px;
        line-height: 1.5;
        box-shadow: inset 0 0 0 1px #e3e3e3;
    }
    #quickNote:focus {
        background: #ffffff;
        box-shadow: inset 0 0 0 2px #0d6efd20;
    }
</style>
