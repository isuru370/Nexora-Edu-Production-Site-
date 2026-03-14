@extends('layouts.app')

@section('title', 'Class Attendance')
@section('page-title', 'Class Attendance Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('class_rooms.index') }}">Class Rooms</a></li>
    <li class="breadcrumb-item active">Class Attendance</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Class Attendance
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Loading Overlay -->
                    <div id="globalLoadingOverlay" class="loading-overlay d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Processing your request...</p>
                    </div>

                    <!-- Error Container -->
                    <div id="errorContainer" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="errorMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <!-- Success Container -->
                    <div id="successContainer" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="successMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <!-- Class Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="class-info p-3 bg-light rounded">
                                <h6 class="fw-bold">Class & Hall Details:</h6>
                                <div id="classDetails" class="class-details-container">
                                    <div class="text-center py-2">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="ms-2">Loading class information...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Date Search Filter -->
                            <div class="filters-section">
                                <div class="card">
                                    <div class="card-header bg-transparent">
                                        <h6 class="card-title mb-0">Search Attendance</h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="dateSearchForm">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <label for="searchDate" class="form-label">Search by Date</label>
                                                        <input type="date" class="form-control" id="searchDate"
                                                            name="searchDate">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">&nbsp;</label>
                                                        <button type="submit" class="btn btn-primary w-100" id="searchBtn">
                                                            <i class="fas fa-search me-2"></i>Search
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <button type="button" class="btn btn-outline-secondary w-100"
                                            onclick="clearSearch()" id="clearSearchBtn">
                                            <i class="fas fa-sync-alt me-2"></i>Show All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Actions Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-tasks me-2"></i>Bulk Actions
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted" id="pendingCount">Pending records: 0</span>
                                            <span class="text-muted ms-3" id="selectedCount">Selected: 0</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-danger" onclick="deleteSelectedAttendance()"
                                                id="bulkDeleteBtn" disabled>
                                                <i class="fas fa-trash me-2"></i>Delete Selected Pending Attendance
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Summary -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Attendance Summary</h6>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" onclick="generatePDF('all')">All Records</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="#" onclick="generatePDF('marked')">Marked
                                                Only</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="generatePDF('not_marked')">Not Marked
                                                Only</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="generatePDF('pending')">Pending
                                                Only</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="generatePDF('deleted')">Deleted
                                                Only</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row mt-3" id="attendanceSummary">
                                <!-- Summary cards will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="card-title mb-0">Attendance Records</h6>
                                        <button class="btn btn-outline-primary btn-sm" onclick="loadAttendanceData()"
                                            title="Refresh" id="refreshBtn">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <button class="btn btn-secondary mb-3" onclick="openAddAttendanceModal()"
                                        id="addAttendanceBtn">
                                        <i class="fas fa-plus-circle me-2"></i>Add New Day
                                    </button>

                                    <!-- Table Controls -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <label class="me-2">Show:</label>
                                                <select class="form-select form-select-sm" style="width: 80px;"
                                                    onchange="changeRecordsPerPage(this.value)" id="recordsPerPage">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                                <span class="ms-2">entries</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-end">
                                                <input type="text" class="form-control form-control-sm"
                                                    style="width: 250px;" placeholder="Search in table..." id="tableSearch">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="attendanceTable">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th width="50">
                                                        <input type="checkbox" id="selectAllPending"
                                                            onchange="toggleSelectAll(this)" class="form-check-input">
                                                    </th>
                                                    <th width="50">#</th>
                                                    <th>Date</th>
                                                    <th>Day</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Hall</th>
                                                    <th>Status</th>
                                                    <th width="150" class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="attendanceTableBody">
                                                <!-- Attendance data will be loaded here -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination Section -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div id="paginationContainer"
                                                class="d-flex justify-content-between align-items-center">
                                                <!-- Pagination controls will be loaded here -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Loading State -->
                                    <div id="attendanceLoading" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading attendance records...</p>
                                    </div>

                                    <!-- Empty State -->
                                    <div id="attendanceEmpty" class="text-center py-5 d-none">
                                        <div class="empty-state-icon mb-4">
                                            <i class="fas fa-calendar-times fa-4x text-muted"></i>
                                        </div>
                                        <h4 class="text-muted">No Attendance Records Found</h4>
                                        <p class="text-muted mb-4">Click the "Add New Day" button to create attendance
                                            records.</p>
                                        <button class="btn btn-primary" onclick="openAddAttendanceModal()">
                                            <i class="fas fa-plus-circle me-2"></i>Add New Day
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editAttendanceModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Attendance
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAttendanceForm" class="needs-validation" novalidate>
                        <input type="hidden" id="edit_attendance_id">
                        <input type="hidden" id="edit_class_category_has_student_class_id">

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Date Selection -->
                                <div class="mb-3">
                                    <label for="edit_date" class="form-label">Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="edit_date" name="date" required>
                                    <div class="invalid-feedback">Please select a date.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Day of Week Selection -->
                                <div class="mb-3">
                                    <label for="edit_day_of_week" class="form-label">Day of Week <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_day_of_week" name="day_of_week" required>
                                        <option value="">Select Day</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a day.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Start Time -->
                                <div class="mb-3">
                                    <label for="edit_start_time" class="form-label">Start Time <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_start_time" name="start_time"
                                        placeholder="e.g., 9:00 AM" required>
                                    <div class="invalid-feedback">Please enter start time.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- End Time -->
                                <div class="mb-3">
                                    <label for="edit_end_time" class="form-label">End Time <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_end_time" name="end_time"
                                        placeholder="e.g., 12:00 PM" required>
                                    <div class="invalid-feedback">Please enter end time.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Hall Selection -->
                        <div class="mb-3">
                            <label for="edit_class_hall_id" class="form-label">Hall <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="edit_class_hall_id" name="class_hall_id" required>
                                <option value="">Select Hall</option>
                            </select>
                            <div class="invalid-feedback">Please select a hall.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-warning" id="updateAttendanceBtn">
                        <i class="fas fa-save me-2"></i>Update Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Attendance Modal -->
    <div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addAttendanceModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Attendance Day
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addAttendanceForm" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Date Selection -->
                                <div class="mb-3">
                                    <label for="add_date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="add_date" name="date" required>
                                    <div class="invalid-feedback">Please select a date.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Day of Week Selection -->
                                <div class="mb-3">
                                    <label for="add_day_of_week" class="form-label">Day of Week <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="add_day_of_week" name="day_of_week" required>
                                        <option value="">Select Day</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a day.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Start Time -->
                                <div class="mb-3">
                                    <label for="add_start_time" class="form-label">Start Time <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="add_start_time" name="start_time"
                                        placeholder="e.g., 9:00 AM" required>
                                    <div class="invalid-feedback">Please enter start time.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- End Time -->
                                <div class="mb-3">
                                    <label for="add_end_time" class="form-label">End Time <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="add_end_time" name="end_time"
                                        placeholder="e.g., 12:00 PM" required>
                                    <div class="invalid-feedback">Please enter end time.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Hall Selection -->
                        <div class="mb-3">
                            <label for="add_class_hall_id" class="form-label">Hall <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="add_class_hall_id" name="class_hall_id" required>
                                <option value="">Select Hall</option>
                            </select>
                            <div class="invalid-feedback">Please select a hall.</div>
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" id="add_status" name="status" value="0">
                        <input type="hidden" id="add_is_ongoing" name="is_ongoing" value="1">
                        <input type="hidden" id="add_class_category_has_student_class_id"
                            name="class_category_has_student_class_id" value="{{ $id }}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="saveAttendanceBtn">
                        <i class="fas fa-save me-2"></i>Save Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteConfirmMessage">Are you sure you want to delete the selected attendance records?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewDetailsModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Attendance Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewDetailsContent">
                    <!-- Details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        /* Table Row Styles */
        .attendance-marked {
            background-color: #d4edda !important;
        }

        .attendance-not-marked {
            background-color: #f8d7da !important;
        }

        .attendance-pending {
            background-color: #fff3cd !important;
        }

        .attendance-deleted {
            background-color: #f8f9fa !important;
            text-decoration: line-through;
            color: #6c757d !important;
        }

        .attendance-deleted td {
            opacity: 0.6;
        }

        /* Summary Card Styles */
        .summary-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            margin-bottom: 1.5rem;
            cursor: pointer;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .summary-card .card-body {
            padding: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .summary-card h2 {
            font-size: 2.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        /* Card Color Themes */
        .total-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .marked-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .not-marked-card {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .pending-card {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: #2c3e50;
        }

        .deleted-card {
            background: linear-gradient(135deg, #868f96 0%, #596164 100%);
            color: white;
        }

        .active-card {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #2c3e50;
        }

        /* Summary Icon Styles */
        .summary-icon {
            opacity: 0.8;
            transition: transform 0.3s ease;
        }

        .summary-card:hover .summary-icon {
            transform: scale(1.1);
            opacity: 1;
        }

        /* Progress Bar Styles */
        .summary-card .progress {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            height: 8px;
            backdrop-filter: blur(10px);
        }

        .pending-card .progress {
            background: rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            border-radius: 10px;
        }

        /* Table Styles */
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
        }

        /* Pagination Styles */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-link {
            color: #007bff;
            padding: 0.5rem 0.75rem;
            margin: 0 2px;
            border-radius: 5px;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        /* Checkbox Styles */
        .form-check-input {
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Main Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .summary-card h2 {
                font-size: 1.8rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }

        /* Class Details Container */
        .class-details-container {
            min-height: 120px;
        }

        /* Badge Styles */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
        }

        /* Button Styles */
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Configuration Object
        const APP_CONFIG = {
            classCategoryHasStudentClassId: {{ $id }},
            apiBaseUrl: '{{ url('/') }}',
            apiEndpoints: {
                attendance: `/api/class-attendances/{{ $id }}`,
                halls: '/api/halls/dropdown',
                classRooms: '/api/class-rooms',
                categories: '/api/categories',
                bulkDelete: '/api/class-attendances/bulk-delete',
                singleAttendance: '/api/class-attendances/single'
            },
            pagination: {
                currentPage: 1,
                recordsPerPage: 10,
                totalPages: 1,
                totalRecords: 0
            },
            debounceDelay: 300,
            maxRetries: 3
        };

        // State Management
        let appState = {
            attendanceData: {
                current_page: 1,
                data: [],
                first_page_url: '',
                from: 1,
                last_page: 1,
                last_page_url: '',
                links: [],
                next_page_url: null,
                path: '',
                per_page: 10,
                prev_page_url: null,
                to: 10,
                total: 0
            },
            allAttendanceData: [], // For summary calculations
            selectedAttendanceIds: [],
            pendingAttendanceIds: [],
            isLoading: false,
            searchTerm: '',
            dateFilter: '',
            currentFilterType: 'all'
        };

        // DOM Elements
        const elements = {
            loadingOverlay: document.getElementById('globalLoadingOverlay'),
            errorContainer: document.getElementById('errorContainer'),
            errorMessage: document.getElementById('errorMessage'),
            successContainer: document.getElementById('successContainer'),
            successMessage: document.getElementById('successMessage'),
            attendanceTableBody: document.getElementById('attendanceTableBody'),
            attendanceLoading: document.getElementById('attendanceLoading'),
            attendanceEmpty: document.getElementById('attendanceEmpty'),
            paginationContainer: document.getElementById('paginationContainer'),
            selectAllPending: document.getElementById('selectAllPending'),
            pendingCount: document.getElementById('pendingCount'),
            selectedCount: document.getElementById('selectedCount'),
            bulkDeleteBtn: document.getElementById('bulkDeleteBtn'),
            tableSearch: document.getElementById('tableSearch'),
            searchDate: document.getElementById('searchDate'),
            recordsPerPage: document.getElementById('recordsPerPage')
        };

        // Initialize Application
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM loaded, initializing app...');
            initializeApp();
        });

        function initializeApp() {
            setupEventListeners();
            loadClassDetails();
            loadAttendanceData();
            initializeFormValidation();
            setupKeyboardShortcuts();
        }

        function setupEventListeners() {
            // Form submissions
            document.getElementById('dateSearchForm').addEventListener('submit', handleDateSearch);

            // Button clicks
            document.getElementById('updateAttendanceBtn').addEventListener('click', updateAttendance);
            document.getElementById('saveAttendanceBtn').addEventListener('click', saveNewAttendance);
            document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDeleteAttendance);

            // Input events with debounce
            if (elements.tableSearch) {
                elements.tableSearch.addEventListener('input', debounce(handleTableSearch, APP_CONFIG.debounceDelay));
            }

            // Date change events
            document.getElementById('add_date').addEventListener('change', handleDateChange);
            document.getElementById('edit_date').addEventListener('change', handleEditDateChange);

            // Modal events
            document.getElementById('addAttendanceModal').addEventListener('show.bs.modal', handleAddModalShow);
            document.getElementById('editAttendanceModal').addEventListener('show.bs.modal', handleEditModalShow);

            // Records per page change
            if (elements.recordsPerPage) {
                elements.recordsPerPage.addEventListener('change', function (e) {
                    APP_CONFIG.pagination.recordsPerPage = parseInt(e.target.value);
                    loadAttendanceData(1);
                });
            }
        }

        // API Calls
        async function apiCall(url, options = {}, retries = APP_CONFIG.maxRetries) {
            for (let i = 0; i < retries; i++) {
                try {
                    const fullUrl = url.startsWith('http') ? url : `${APP_CONFIG.apiBaseUrl}${url}`;

                    const response = await fetch(fullUrl, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        ...options
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error(`API call failed (attempt ${i + 1}/${retries}):`, error);
                    if (i === retries - 1) throw error;
                    await new Promise(resolve => setTimeout(resolve, 1000 * (i + 1)));
                }
            }
        }

        // Load Class Details
        async function loadClassDetails() {
            try {
                const response = await apiCall(APP_CONFIG.apiEndpoints.attendance);
                const classDetailsDiv = document.getElementById('classDetails');

                if (response.status === 'success' && response.data && response.data.data && response.data.data.length > 0) {
                    const firstRecord = response.data.data[0];
                    const classData = firstRecord.class_category_student_class;
                    const hallData = firstRecord.hall;

                    let classRoomDetails = 'N/A';
                    if (classData && classData.student_classes_id) {
                        try {
                            const classResult = await apiCall(`/api/class-rooms/${classData.student_classes_id}`);
                            if (classResult.status === 'success' && classResult.data) {
                                const classInfo = classResult.data;
                                classRoomDetails = `${classInfo.class_name || ''} - Grade ${classInfo.grade?.grade_name || ''} - ${classInfo.subject?.subject_name || ''}`;
                            }
                        } catch (error) {
                            console.error('Error loading class room details:', error);
                        }
                    }

                    let categoryDetails = 'N/A';
                    if (classData && classData.class_category_id) {
                        try {
                            const categoryResult = await apiCall(`/api/categories/${classData.class_category_id}`);
                            if (categoryResult.status === 'success' && categoryResult.data) {
                                categoryDetails = categoryResult.data.category_name || 'N/A';
                            }
                        } catch (error) {
                            console.error('Error loading category details:', error);
                        }
                    }

                    classDetailsDiv.innerHTML = `
                            <p class="mb-1"><strong>Class Category Student Class ID:</strong> ${APP_CONFIG.classCategoryHasStudentClassId}</p>
                            <p class="mb-1"><strong>Fees:</strong> Rs. ${classData?.fees || '0'}</p>
                            <p class="mb-1"><strong>Student Class:</strong> ${classRoomDetails}</p>
                            <p class="mb-1"><strong>Class Category:</strong> ${categoryDetails}</p>
                            ${hallData ? `<p class="mb-0"><strong>Default Hall:</strong> ${hallData.hall_name} (${hallData.hall_id})</p>` : ''}
                        `;
                } else {
                    classDetailsDiv.innerHTML = `
                            <p class="mb-0"><strong>Class Category Student Class ID:</strong> ${APP_CONFIG.classCategoryHasStudentClassId}</p>
                            <p class="mb-0 text-muted">No detailed information available</p>
                        `;
                }
            } catch (error) {
                console.error('Error loading class details:', error);
                showError('Failed to load class details. Please refresh the page.');
            }
        }

        // Load Attendance Data
        async function loadAttendanceData(page = 1) {
            showAttendanceLoading();

            try {
                const perPage = APP_CONFIG.pagination.recordsPerPage;
                const url = `${APP_CONFIG.apiEndpoints.attendance}?page=${page}&per_page=${perPage}`;

                console.log('Loading attendance data from:', url);
                const response = await apiCall(url);
                console.log('API Response:', response);

                if (response.status === 'success' && response.data) {
                    // Update state with paginated data
                    appState.attendanceData = response.data;

                    // Also load all data for summary (first page only for now)
                    appState.allAttendanceData = response.data.data || [];

                    // Render the table
                    renderAttendanceTable();
                    updatePaginationControls();
                    updatePendingCount();
                    updateAttendanceSummary();

                    hideAttendanceLoading();
                } else {
                    throw new Error('Invalid response format');
                }
            } catch (error) {
                console.error('Error loading attendance data:', error);
                showError('Error loading attendance records. Please try again.');
                hideAttendanceLoading();
                elements.attendanceEmpty.classList.remove('d-none');
            }
        }

        // Render Attendance Table
        function renderAttendanceTable() {
            const tbody = elements.attendanceTableBody;
            if (!tbody) {
                console.error('Table body not found');
                return;
            }

            tbody.innerHTML = '';
            if (elements.selectAllPending) {
                elements.selectAllPending.checked = false;
            }

            const records = appState.attendanceData.data || [];
            console.log('Rendering records:', records.length);

            if (records.length === 0) {
                elements.attendanceEmpty.classList.remove('d-none');
                return;
            }

            elements.attendanceEmpty.classList.add('d-none');

            // Current date for comparison
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // Render table rows
            records.forEach((record, index) => {
                const actualIndex = (appState.attendanceData.from || 1) + index;
                const attendanceId = record.id;

                const formattedDate = formatDate(record.date);
                const recordDate = new Date(record.date);
                recordDate.setHours(0, 0, 0, 0);

                const isPastDate = recordDate < today;
                const isFutureDate = recordDate > today;
                const isToday = recordDate.getTime() === today.getTime();
                const isDeleted = record.is_ongoing == 0 || record.is_ongoing === false;
                const isMarked = record.status == 1;

                let statusText, statusClass, canEdit, showCheckbox;

                if (isDeleted) {
                    statusText = "Deleted";
                    statusClass = "attendance-deleted";
                    canEdit = false;
                    showCheckbox = false;
                } else if (isMarked) {
                    statusText = "Marked";
                    statusClass = "attendance-marked";
                    canEdit = false;
                    showCheckbox = false;
                } else {
                    if (isPastDate) {
                        statusText = "Not Marked";
                        statusClass = "attendance-not-marked";
                        canEdit = false;
                        showCheckbox = false;
                    } else if (isFutureDate) {
                        statusText = "Pending";
                        statusClass = "attendance-pending";
                        canEdit = true;
                        showCheckbox = true;
                    } else if (isToday) {
                        const currentTime = new Date();
                        const recordEndTime = new Date(record.date);

                        if (record.end_time) {
                            try {
                                const timeStr = record.end_time;
                                const [time, modifier] = timeStr.split(' ');
                                let [hours, minutes] = time.split(':');

                                if (modifier === 'PM' && hours !== '12') {
                                    hours = parseInt(hours) + 12;
                                }
                                if (modifier === 'AM' && hours === '12') {
                                    hours = 0;
                                }

                                recordEndTime.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                            } catch (e) {
                                console.error('Error parsing time:', e);
                            }
                        }

                        if (currentTime > recordEndTime) {
                            statusText = "Not Marked";
                            statusClass = "attendance-not-marked";
                            canEdit = false;
                            showCheckbox = false;
                        } else {
                            statusText = "Pending";
                            statusClass = "attendance-pending";
                            canEdit = true;
                            showCheckbox = true;
                        }
                    }
                }

                const isSelected = appState.selectedAttendanceIds.includes(attendanceId);
                const checkbox = showCheckbox ?
                    `<input type="checkbox" class="form-check-input attendance-checkbox" 
                            onchange="toggleSelection(${attendanceId}, this)" 
                            ${isSelected ? 'checked' : ''}>` : '';

                const row = `
                        <tr class="${statusClass} fade-in">
                            <td>${checkbox}</td>
                            <td class="fw-bold text-muted">${actualIndex}</td>
                            <td>${formattedDate}</td>
                            <td>${record.day_of_week || 'N/A'}</td>
                            <td>${record.start_time || 'N/A'}</td>
                            <td>${record.end_time || 'N/A'}</td>
                            <td>${record.hall ? record.hall.hall_name : 'N/A'}</td>
                            <td>
                                <span class="badge ${getStatusBadgeClass(statusClass)}">
                                    ${statusText}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-info" title="View Details" 
                                        onclick="viewDetails(${attendanceId})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    ${canEdit ?
                        `<button class="btn btn-outline-warning" title="Edit Attendance" 
                                            onclick="editAttendance(${attendanceId})">
                                            <i class="fas fa-edit"></i>
                                        </button>` :
                        `<button class="btn btn-outline-secondary" disabled title="Cannot Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>`
                    }
                                </div>
                            </td>
                        </tr>
                    `;
                tbody.innerHTML += row;
            });

            updateSelectAllCheckbox();
        }

        // Helper Functions
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            } catch (error) {
                console.error('Error formatting date:', error);
                return 'Invalid Date';
            }
        }

        function getStatusBadgeClass(statusClass) {
            if (statusClass.includes('marked')) return 'bg-success';
            if (statusClass.includes('pending')) return 'bg-warning text-dark';
            if (statusClass.includes('deleted')) return 'bg-secondary';
            return 'bg-danger';
        }

        // Update Pending Count
        function updatePendingCount() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const pendingRecords = appState.allAttendanceData.filter(record =>
                record.is_ongoing == 1 &&
                record.status == 0 &&
                new Date(record.date) > today
            );

            appState.pendingAttendanceIds = pendingRecords.map(record => record.id);
            if (elements.pendingCount) {
                elements.pendingCount.textContent = `Pending records: ${pendingRecords.length}`;
            }
            updateBulkDeleteButton();
        }

        // Update Selected Count
        function updateSelectedCount() {
            if (elements.selectedCount) {
                elements.selectedCount.textContent = `Selected: ${appState.selectedAttendanceIds.length}`;
            }
            updateBulkDeleteButton();
        }

        // Update Bulk Delete Button State
        function updateBulkDeleteButton() {
            if (elements.bulkDeleteBtn) {
                elements.bulkDeleteBtn.disabled = appState.selectedAttendanceIds.length === 0;
            }
        }

        // Toggle Select All
        function toggleSelectAll(checkbox) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const currentPageRecords = appState.attendanceData.data || [];
            const pendingRecords = currentPageRecords.filter(record =>
                record.is_ongoing == 1 &&
                record.status == 0 &&
                new Date(record.date) > today
            );

            if (checkbox.checked) {
                pendingRecords.forEach(record => {
                    if (!appState.selectedAttendanceIds.includes(record.id)) {
                        appState.selectedAttendanceIds.push(record.id);
                    }
                });
            } else {
                pendingRecords.forEach(record => {
                    appState.selectedAttendanceIds = appState.selectedAttendanceIds.filter(id => id !== record.id);
                });
            }

            document.querySelectorAll('.attendance-checkbox').forEach(cb => {
                cb.checked = checkbox.checked;
            });

            updateSelectedCount();
        }

        // Toggle Single Selection
        function toggleSelection(attendanceId, checkbox) {
            if (checkbox.checked) {
                if (!appState.selectedAttendanceIds.includes(attendanceId)) {
                    appState.selectedAttendanceIds.push(attendanceId);
                }
            } else {
                appState.selectedAttendanceIds = appState.selectedAttendanceIds.filter(id => id !== attendanceId);
            }

            updateSelectAllCheckbox();
            updateSelectedCount();
        }

        // Update Select All Checkbox
        function updateSelectAllCheckbox() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const currentPageRecords = appState.attendanceData.data || [];
            const pendingRecords = currentPageRecords.filter(record =>
                record.is_ongoing == 1 &&
                record.status == 0 &&
                new Date(record.date) > today
            );

            const allPendingSelected = pendingRecords.length > 0 &&
                pendingRecords.every(record => appState.selectedAttendanceIds.includes(record.id));

            if (elements.selectAllPending) {
                elements.selectAllPending.checked = allPendingSelected;
            }
        }

        // Delete Selected Attendance
        function deleteSelectedAttendance() {
            if (appState.selectedAttendanceIds.length === 0) {
                showError('Please select pending attendance records to delete', 'warning');
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            document.getElementById('deleteConfirmMessage').textContent =
                `Are you sure you want to delete ${appState.selectedAttendanceIds.length} selected pending attendance records?`;
            modal.show();
        }

        // Confirm Delete
        async function confirmDeleteAttendance() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
            modal.hide();

            showGlobalLoading('Deleting attendance records...');

            try {
                const response = await apiCall(APP_CONFIG.apiEndpoints.bulkDelete, {
                    method: 'POST',
                    body: JSON.stringify({ ids: appState.selectedAttendanceIds })
                });

                if (response.status === 'success') {
                    showSuccess('Selected pending attendance records deleted successfully!');
                    appState.selectedAttendanceIds = [];
                    await loadAttendanceData(APP_CONFIG.pagination.currentPage);
                } else {
                    throw new Error(response.message || 'Failed to delete selected attendance');
                }
            } catch (error) {
                console.error('Error deleting selected attendance:', error);
                showError('Error deleting selected attendance: ' + error.message);
            } finally {
                hideGlobalLoading();
            }
        }

        // View Details
        function viewDetails(attendanceId) {
            const record = appState.allAttendanceData.find(r => r.id === attendanceId);
            if (!record) return;

            const detailsHtml = `
                    <div class="container-fluid">
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>ID:</strong>
                                <p class="text-muted">${record.id}</p>
                            </div>
                            <div class="col-6">
                                <strong>Date:</strong>
                                <p class="text-muted">${formatDate(record.date)}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Day:</strong>
                                <p class="text-muted">${record.day_of_week || 'N/A'}</p>
                            </div>
                            <div class="col-6">
                                <strong>Time:</strong>
                                <p class="text-muted">${record.start_time || 'N/A'} - ${record.end_time || 'N/A'}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Hall:</strong>
                                <p class="text-muted">${record.hall ? record.hall.hall_name : 'N/A'}</p>
                            </div>
                            <div class="col-6">
                                <strong>Status:</strong>
                                <p class="text-muted">${record.status == 1 ? 'Marked' : (record.is_ongoing == 0 ? 'Deleted' : 'Pending')}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <strong>Created At:</strong>
                                <p class="text-muted">${new Date(record.created_at).toLocaleString()}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <strong>Last Updated:</strong>
                                <p class="text-muted">${new Date(record.updated_at).toLocaleString()}</p>
                            </div>
                        </div>
                    </div>
                `;

            document.getElementById('viewDetailsContent').innerHTML = detailsHtml;
            const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
            modal.show();
        }

        // Handle Table Search
        function handleTableSearch(e) {
            appState.searchTerm = e.target.value.toLowerCase();
            filterLocalData();
        }

        // Handle Date Search
        function handleDateSearch(e) {
            e.preventDefault();
            const searchDate = elements.searchDate.value;

            if (!searchDate) {
                showError('Please select a date to search', 'warning');
                return;
            }

            appState.dateFilter = searchDate;
            filterLocalData();
        }

        // Filter local data
        function filterLocalData() {
            let filtered = [...appState.allAttendanceData];

            if (appState.dateFilter) {
                filtered = filtered.filter(record => formatDate(record.date) === appState.dateFilter);
            }

            if (appState.searchTerm) {
                filtered = filtered.filter(record =>
                    formatDate(record.date).toLowerCase().includes(appState.searchTerm) ||
                    (record.day_of_week || '').toLowerCase().includes(appState.searchTerm) ||
                    (record.start_time || '').toLowerCase().includes(appState.searchTerm) ||
                    (record.end_time || '').toLowerCase().includes(appState.searchTerm) ||
                    (record.hall ? record.hall.hall_name.toLowerCase() : '').includes(appState.searchTerm)
                );
            }

            appState.attendanceData.data = filtered.slice(0, APP_CONFIG.pagination.recordsPerPage);
            appState.attendanceData.total = filtered.length;
            appState.attendanceData.from = 1;
            appState.attendanceData.to = Math.min(APP_CONFIG.pagination.recordsPerPage, filtered.length);
            appState.attendanceData.current_page = 1;
            appState.attendanceData.last_page = Math.ceil(filtered.length / APP_CONFIG.pagination.recordsPerPage);

            renderAttendanceTable();
            updatePaginationControls();
        }

        // Clear Search
        function clearSearch() {
            if (elements.searchDate) elements.searchDate.value = '';
            if (elements.tableSearch) elements.tableSearch.value = '';
            appState.dateFilter = '';
            appState.searchTerm = '';
            loadAttendanceData(1);
            showSuccess('Search filters cleared');
        }

        // Open Add Attendance Modal
        function openAddAttendanceModal() {
            const form = document.getElementById('addAttendanceForm');
            form.reset();
            form.classList.remove('was-validated');

            const today = new Date().toISOString().split('T')[0];
            document.getElementById('add_date').value = today;

            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const todayDayName = dayNames[new Date().getDay()];
            document.getElementById('add_day_of_week').value = todayDayName;

            const modal = new bootstrap.Modal(document.getElementById('addAttendanceModal'));
            modal.show();
        }

        // Handle Add Modal Show
        async function handleAddModalShow() {
            await loadAddHallsDropdown();
        }

        // Handle Edit Modal Show
        async function handleEditModalShow() {
            await loadEditHallsDropdown();
        }

        // Load Halls Dropdown for Add Modal
        async function loadAddHallsDropdown() {
            try {
                const response = await apiCall(APP_CONFIG.apiEndpoints.halls);
                const hallSelect = document.getElementById('add_class_hall_id');

                if (response.status === 'success') {
                    hallSelect.innerHTML = '<option value="">Select Hall</option>';
                    response.data.forEach(hall => {
                        const option = document.createElement('option');
                        option.value = hall.id;
                        option.textContent = `${hall.hall_name} (${hall.hall_id}) - ${hall.hall_type || 'No Type'} - Rs.${hall.hall_price || '0'}`;
                        hallSelect.appendChild(option);
                    });
                } else {
                    hallSelect.innerHTML = '<option value="">Error loading halls</option>';
                }
            } catch (error) {
                console.error('Error loading halls:', error);
                document.getElementById('add_class_hall_id').innerHTML = '<option value="">Error loading halls</option>';
            }
        }

        // Load Halls Dropdown for Edit Modal
        async function loadEditHallsDropdown() {
            try {
                const response = await apiCall(APP_CONFIG.apiEndpoints.halls);
                const hallSelect = document.getElementById('edit_class_hall_id');

                if (response.status === 'success') {
                    hallSelect.innerHTML = '<option value="">Select Hall</option>';
                    response.data.forEach(hall => {
                        const option = document.createElement('option');
                        option.value = hall.id;
                        option.textContent = `${hall.hall_name} (${hall.hall_id}) - ${hall.hall_type || 'No Type'} - Rs.${hall.hall_price || '0'}`;
                        hallSelect.appendChild(option);
                    });
                } else {
                    hallSelect.innerHTML = '<option value="">Error loading halls</option>';
                }
            } catch (error) {
                console.error('Error loading halls:', error);
                document.getElementById('edit_class_hall_id').innerHTML = '<option value="">Error loading halls</option>';
            }
        }

        // Handle Date Change
        function handleDateChange(e) {
            if (e.target.value) {
                const date = new Date(e.target.value);
                const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const dayName = dayNames[date.getDay()];
                document.getElementById('add_day_of_week').value = dayName;
            }
        }

        // Handle Edit Date Change
        function handleEditDateChange(e) {
            if (e.target.value) {
                const date = new Date(e.target.value);
                const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const dayName = dayNames[date.getDay()];
                document.getElementById('edit_day_of_week').value = dayName;
            }
        }

        // Save New Attendance
        async function saveNewAttendance() {
            const form = document.getElementById('addAttendanceForm');

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const formData = {
                date: document.getElementById('add_date').value,
                day_of_week: document.getElementById('add_day_of_week').value,
                start_time: document.getElementById('add_start_time').value,
                end_time: document.getElementById('add_end_time').value,
                class_hall_id: document.getElementById('add_class_hall_id').value,
                status: document.getElementById('add_status').value,
                is_ongoing: document.getElementById('add_is_ongoing').value,
                class_category_has_student_class_id: document.getElementById('add_class_category_has_student_class_id').value
            };

            if (!formData.start_time || !formData.end_time) {
                showError('Please enter both start and end times', 'warning');
                return;
            }

            showGlobalLoading('Saving attendance record...');

            try {
                const response = await apiCall(APP_CONFIG.apiEndpoints.singleAttendance, {
                    method: 'POST',
                    body: JSON.stringify(formData)
                });

                if (response.status === 'success') {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addAttendanceModal'));
                    modal.hide();

                    await loadAttendanceData(1);
                    showSuccess('Attendance record added successfully!');
                } else {
                    throw new Error(response.message || 'Failed to add attendance record');
                }
            } catch (error) {
                console.error('Error adding attendance:', error);
                showError('Error adding attendance: ' + error.message);
            } finally {
                hideGlobalLoading();
            }
        }

        // Edit Attendance
        async function editAttendance(attendanceId) {
            const record = appState.allAttendanceData.find(r => r.id === attendanceId);
            if (!record) return;

            document.getElementById('edit_attendance_id').value = record.id;
            document.getElementById('edit_class_category_has_student_class_id').value = record.class_category_has_student_class_id;
            document.getElementById('edit_date').value = formatDate(record.date);
            document.getElementById('edit_day_of_week').value = record.day_of_week || '';
            document.getElementById('edit_start_time').value = record.start_time || '';
            document.getElementById('edit_end_time').value = record.end_time || '';

            await loadEditHallsDropdown();
            if (record.class_hall_id) {
                document.getElementById('edit_class_hall_id').value = record.class_hall_id;
            }

            const modal = new bootstrap.Modal(document.getElementById('editAttendanceModal'));
            modal.show();
        }

        // Update Attendance
        async function updateAttendance() {
            const form = document.getElementById('editAttendanceForm');

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const attendanceId = document.getElementById('edit_attendance_id').value;

            const formData = {
                status: "0",
                date: document.getElementById('edit_date').value,
                day_of_week: document.getElementById('edit_day_of_week').value,
                start_time: document.getElementById('edit_start_time').value,
                end_time: document.getElementById('edit_end_time').value,
                class_hall_id: document.getElementById('edit_class_hall_id').value,
                class_category_has_student_class_id: document.getElementById('edit_class_category_has_student_class_id').value
            };

            showGlobalLoading('Updating attendance record...');

            try {
                const response = await apiCall(`/api/class-attendances/${attendanceId}`, {
                    method: 'PUT',
                    body: JSON.stringify(formData)
                });

                if (response.status === 'success') {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editAttendanceModal'));
                    modal.hide();

                    await loadAttendanceData(APP_CONFIG.pagination.currentPage);
                    showSuccess('Attendance updated successfully!');
                } else {
                    throw new Error(response.message || 'Failed to update attendance');
                }
            } catch (error) {
                console.error('Error updating attendance:', error);
                showError('Error updating attendance: ' + error.message);
            } finally {
                hideGlobalLoading();
            }
        }

        // Update Attendance Summary
        function updateAttendanceSummary() {
            const summaryDiv = document.getElementById('attendanceSummary');
            if (!summaryDiv) return;

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const totalRecords = appState.attendanceData.total || 0;
            const records = appState.allAttendanceData || [];

            const deletedRecords = records.filter(record => record.is_ongoing == 0).length;
            const activeRecords = records.filter(record => record.is_ongoing == 1);

            let markedRecords = 0;
            let notMarkedRecords = 0;
            let pendingRecords = 0;

            activeRecords.forEach(record => {
                const recordDate = new Date(record.date);
                recordDate.setHours(0, 0, 0, 0);
                const isMarked = record.status == 1;

                if (isMarked) {
                    markedRecords++;
                } else {
                    if (recordDate < today) {
                        notMarkedRecords++;
                    } else if (recordDate > today) {
                        pendingRecords++;
                    } else {
                        const currentTime = new Date();
                        if (record.end_time) {
                            try {
                                const timeStr = record.end_time;
                                const [time, modifier] = timeStr.split(' ');
                                let [hours, minutes] = time.split(':');

                                if (modifier === 'PM' && hours !== '12') {
                                    hours = parseInt(hours) + 12;
                                }
                                if (modifier === 'AM' && hours === '12') {
                                    hours = 0;
                                }

                                const endTime = new Date(record.date);
                                endTime.setHours(parseInt(hours), parseInt(minutes), 0, 0);

                                if (currentTime > endTime) {
                                    notMarkedRecords++;
                                } else {
                                    pendingRecords++;
                                }
                            } catch (e) {
                                pendingRecords++;
                            }
                        } else {
                            pendingRecords++;
                        }
                    }
                }
            });

            const activeTotal = activeRecords.length;
            const markedPercentage = activeTotal > 0 ? ((markedRecords / activeTotal) * 100).toFixed(1) : 0;
            const notMarkedPercentage = activeTotal > 0 ? ((notMarkedRecords / activeTotal) * 100).toFixed(1) : 0;
            const pendingPercentage = activeTotal > 0 ? ((pendingRecords / activeTotal) * 100).toFixed(1) : 0;
            const deletedPercentage = totalRecords > 0 ? ((deletedRecords / totalRecords) * 100).toFixed(1) : 0;

            summaryDiv.innerHTML = `
                    <div class="col-xl-2 col-md-4 mb-4">
                                    <div class="summary-card total-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <div class="card-body position-relative">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h2 class="mb-1 fw-bold">${totalRecords}</h2>
                                                    <p class="mb-0 opacity-75">Total Records</p>
                                                </div>
                                                <div class="summary-icon">
                                                    <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                                                <small class="opacity-75">All attendance records</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 mb-4">
                                    <div class="summary-card marked-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                        <div class="card-body position-relative">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h2 class="mb-1 fw-bold">${markedRecords}</h2>
                                                    <p class="mb-0 opacity-75">Marked</p>
                                                </div>
                                                <div class="summary-icon">
                                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                                                <div class="progress bg-white bg-opacity-25" style="height: 8px; border-radius: 10px;">
                                                    <div class="progress-bar bg-white" style="width: ${markedPercentage}%"></div>
                                                </div>
                                                <small class="opacity-75 d-block mt-1">${markedPercentage}% of active</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 mb-4">
                                    <div class="summary-card not-marked-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                        <div class="card-body position-relative">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h2 class="mb-1 fw-bold">${notMarkedRecords}</h2>
                                                    <p class="mb-0 opacity-75">Not Marked</p>
                                                </div>
                                                <div class="summary-icon">
                                                    <i class="fas fa-times-circle fa-2x opacity-75"></i>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                                                <div class="progress bg-white bg-opacity-25" style="height: 8px; border-radius: 10px;">
                                                    <div class="progress-bar bg-white" style="width: ${notMarkedPercentage}%"></div>
                                                </div>
                                                <small class="opacity-75 d-block mt-1">${notMarkedPercentage}% of active</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 mb-4">
                                    <div class="summary-card pending-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2c3e50;">
                                        <div class="card-body position-relative">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h2 class="mb-1 fw-bold">${pendingRecords}</h2>
                                                    <p class="mb-0 opacity-75">Pending</p>
                                                </div>
                                                <div class="summary-icon">
                                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 border-top border-dark border-opacity-25">
                                                <div class="progress bg-dark bg-opacity-10" style="height: 8px; border-radius: 10px;">
                                                    <div class="progress-bar bg-warning" style="width: ${pendingPercentage}%"></div>
                                                </div>
                                                <small class="opacity-75 d-block mt-1">${pendingPercentage}% of active</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 mb-4">
                                    <div class="summary-card deleted-card" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%);">
                                        <div class="card-body position-relative">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h2 class="mb-1 fw-bold">${deletedRecords}</h2>
                                                    <p class="mb-0 opacity-75">Deleted</p>
                                                </div>
                                                <div class="summary-icon">
                                                    <i class="fas fa-trash fa-2x opacity-75"></i>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 border-top border-white border-opacity-25">
                                                <div class="progress bg-white bg-opacity-25" style="height: 8px; border-radius: 10px;">
                                                    <div class="progress-bar bg-white" style="width: ${deletedPercentage}%"></div>
                                                </div>
                                                <small class="opacity-75 d-block mt-1">${deletedPercentage}% of total</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-4 mb-4">
                                    <div class="summary-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2c3e50;">
                                        <div class="card-body position-relative">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h2 class="mb-1 fw-bold">${activeRecords.length}</h2>
                                                    <p class="mb-0 opacity-75">Active</p>
                                                </div>
                                                <div class="summary-icon">
                                                    <i class="fas fa-play-circle fa-2x opacity-75"></i>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 border-top border-dark border-opacity-25">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="opacity-75">Active records</small>
                                                    <span class="badge bg-success bg-opacity-25 text-success">Live</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
        }

        // Filter by Status
        function filterByStatus(status) {
            appState.currentFilterType = status;

            let filtered = [];

            switch (status) {
                case 'marked':
                    filtered = appState.allAttendanceData.filter(r => r.is_ongoing == 1 && r.status == 1);
                    break;
                case 'not_marked':
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    filtered = appState.allAttendanceData.filter(r =>
                        r.is_ongoing == 1 &&
                        r.status == 0 &&
                        new Date(r.date) < today
                    );
                    break;
                case 'pending':
                    const futureDate = new Date();
                    futureDate.setHours(0, 0, 0, 0);
                    filtered = appState.allAttendanceData.filter(r =>
                        r.is_ongoing == 1 &&
                        r.status == 0 &&
                        new Date(r.date) > futureDate
                    );
                    break;
                case 'deleted':
                    filtered = appState.allAttendanceData.filter(r => r.is_ongoing == 0);
                    break;
                case 'active':
                    filtered = appState.allAttendanceData.filter(r => r.is_ongoing == 1);
                    break;
                default:
                    filtered = [...appState.allAttendanceData];
            }

            appState.attendanceData.data = filtered.slice(0, APP_CONFIG.pagination.recordsPerPage);
            appState.attendanceData.total = filtered.length;
            appState.attendanceData.from = 1;
            appState.attendanceData.to = Math.min(APP_CONFIG.pagination.recordsPerPage, filtered.length);
            appState.attendanceData.current_page = 1;
            appState.attendanceData.last_page = Math.ceil(filtered.length / APP_CONFIG.pagination.recordsPerPage);
            appState.attendanceData.next_page_url = appState.attendanceData.current_page < appState.attendanceData.last_page ? 'has' : null;
            appState.attendanceData.prev_page_url = appState.attendanceData.current_page > 1 ? 'has' : null;

            renderAttendanceTable();
            updatePaginationControls();
        }

        // Pagination Controls
        function updatePaginationControls() {
            const paginationDiv = elements.paginationContainer;
            const data = appState.attendanceData;

            if (data.total === 0) {
                paginationDiv.innerHTML = '';
                return;
            }

            APP_CONFIG.pagination.currentPage = data.current_page;
            APP_CONFIG.pagination.totalPages = data.last_page;
            APP_CONFIG.pagination.totalRecords = data.total;

            let paginationHtml = `
                    <div>
                        <span class="text-muted">
                            Showing ${data.from || 1} to ${data.to || data.data.length} of ${data.total} entries
                        </span>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item ${data.current_page <= 1 ? 'disabled' : ''}">
                                <a class="page-link" href="#" onclick="changePage(1)">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            </li>
                            <li class="page-item ${data.current_page <= 1 ? 'disabled' : ''}">
                                <a class="page-link" href="#" onclick="changePage(${data.current_page - 1})">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            </li>

                            ${generatePageNumbers()}

                            <li class="page-item ${data.current_page >= data.last_page ? 'disabled' : ''}">
                                <a class="page-link" href="#" onclick="changePage(${data.current_page + 1})">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            </li>
                            <li class="page-item ${data.current_page >= data.last_page ? 'disabled' : ''}">
                                <a class="page-link" href="#" onclick="changePage(${data.last_page})">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                `;

            paginationDiv.innerHTML = paginationHtml;
        }

        function generatePageNumbers() {
            let pageNumbers = '';
            const current = appState.attendanceData.current_page || 1;
            const total = appState.attendanceData.last_page || 1;
            const maxPagesToShow = 5;

            let startPage = Math.max(1, current - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(total, startPage + maxPagesToShow - 1);

            if (endPage - startPage + 1 < maxPagesToShow) {
                startPage = Math.max(1, endPage - maxPagesToShow + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                pageNumbers += `
                        <li class="page-item ${current === i ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                        </li>
                    `;
            }

            return pageNumbers;
        }

        function changePage(page) {
            if (page < 1 || page > appState.attendanceData.last_page) return;

            if (appState.currentFilterType !== 'all' || appState.searchTerm || appState.dateFilter) {
                const filtered = getFilteredData();
                const start = (page - 1) * APP_CONFIG.pagination.recordsPerPage;
                const end = start + APP_CONFIG.pagination.recordsPerPage;

                appState.attendanceData.data = filtered.slice(start, end);
                appState.attendanceData.current_page = page;
                appState.attendanceData.from = start + 1;
                appState.attendanceData.to = Math.min(end, filtered.length);
                appState.attendanceData.prev_page_url = page > 1 ? 'has' : null;
                appState.attendanceData.next_page_url = page < appState.attendanceData.last_page ? 'has' : null;

                renderAttendanceTable();
                updatePaginationControls();
            } else {
                loadAttendanceData(page);
            }
        }

        function getFilteredData() {
            let filtered = [...appState.allAttendanceData];

            if (appState.dateFilter) {
                filtered = filtered.filter(record => formatDate(record.date) === appState.dateFilter);
            }

            if (appState.searchTerm) {
                filtered = filtered.filter(record =>
                    formatDate(record.date).toLowerCase().includes(appState.searchTerm) ||
                    (record.day_of_week || '').toLowerCase().includes(appState.searchTerm) ||
                    (record.start_time || '').toLowerCase().includes(appState.searchTerm) ||
                    (record.end_time || '').toLowerCase().includes(appState.searchTerm) ||
                    (record.hall ? record.hall.hall_name.toLowerCase() : '').includes(appState.searchTerm)
                );
            }

            if (appState.currentFilterType !== 'all') {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                switch (appState.currentFilterType) {
                    case 'marked':
                        filtered = filtered.filter(r => r.is_ongoing == 1 && r.status == 1);
                        break;
                    case 'not_marked':
                        filtered = filtered.filter(r =>
                            r.is_ongoing == 1 &&
                            r.status == 0 &&
                            new Date(r.date) < today
                        );
                        break;
                    case 'pending':
                        filtered = filtered.filter(r =>
                            r.is_ongoing == 1 &&
                            r.status == 0 &&
                            new Date(r.date) > today
                        );
                        break;
                    case 'deleted':
                        filtered = filtered.filter(r => r.is_ongoing == 0);
                        break;
                    case 'active':
                        filtered = filtered.filter(r => r.is_ongoing == 1);
                        break;
                }
            }

            return filtered;
        }

        // PDF Generation
        function generatePDF(filterType = 'all') {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape');

            const filteredData = filterAttendanceDataForPDF(filterType);
            const filterLabel = getFilterLabel(filterType);

            doc.setFontSize(20);
            doc.setTextColor(0, 0, 128);
            doc.text('ATTENDANCE DETAILED REPORT', 150, 15, { align: 'center' });

            doc.setFontSize(10);
            doc.setTextColor(100);
            doc.text(`Filter: ${filterLabel} | Generated on: ${new Date().toLocaleString()}`, 150, 22, { align: 'center' });

            doc.setFontSize(12);
            doc.setTextColor(0, 0, 0);
            doc.text(`Class Category Student Class ID: ${APP_CONFIG.classCategoryHasStudentClassId}`, 14, 35);
            doc.text(`Total Records: ${filteredData.length}`, 14, 42);

            const tableData = filteredData.map((record, index) => [
                index + 1,
                formatDate(record.date),
                record.day_of_week || 'N/A',
                record.start_time || 'N/A',
                record.end_time || 'N/A',
                record.hall ? record.hall.hall_name : 'N/A',
                record.status == 1 ? 'Marked' : (record.is_ongoing == 0 ? 'Deleted' : 'Pending')
            ]);

            doc.autoTable({
                startY: 50,
                head: [['#', 'Date', 'Day', 'Start Time', 'End Time', 'Hall', 'Status']],
                body: tableData,
                theme: 'striped',
                headStyles: { fillColor: [0, 123, 255], textColor: 255 },
                styles: { fontSize: 8, cellPadding: 3 },
                columnStyles: {
                    0: { cellWidth: 10 },
                    1: { cellWidth: 25 },
                    2: { cellWidth: 20 },
                    3: { cellWidth: 20 },
                    4: { cellWidth: 20 },
                    5: { cellWidth: 30 },
                    6: { cellWidth: 20 }
                }
            });

            const finalY = doc.lastAutoTable.finalY + 10;
            doc.setFontSize(10);
            doc.text(`Summary:`, 14, finalY);
            doc.text(`- Marked: ${filteredData.filter(r => r.status == 1).length}`, 20, finalY + 7);
            doc.text(`- Pending: ${filteredData.filter(r => r.status == 0 && new Date(r.date) > new Date()).length}`, 20, finalY + 14);
            doc.text(`- Not Marked: ${filteredData.filter(r => r.status == 0 && new Date(r.date) < new Date()).length}`, 20, finalY + 21);
            doc.text(`- Deleted: ${filteredData.filter(r => r.is_ongoing == 0).length}`, 20, finalY + 28);

            const fileName = `attendance-${filterType}-${APP_CONFIG.classCategoryHasStudentClassId}-${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(fileName);
        }

        function filterAttendanceDataForPDF(filterType) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            switch (filterType) {
                case 'marked':
                    return appState.allAttendanceData.filter(record => record.is_ongoing == 1 && record.status == 1);
                case 'not_marked':
                    return appState.allAttendanceData.filter(record =>
                        record.is_ongoing == 1 && record.status == 0 && new Date(record.date) < today
                    );
                case 'pending':
                    return appState.allAttendanceData.filter(record =>
                        record.is_ongoing == 1 && record.status == 0 && new Date(record.date) > today
                    );
                case 'deleted':
                    return appState.allAttendanceData.filter(record => record.is_ongoing == 0);
                default:
                    return appState.allAttendanceData;
            }
        }

        function getFilterLabel(filterType) {
            const labels = {
                'all': 'All Records',
                'marked': 'Marked Only',
                'not_marked': 'Not Marked Only',
                'pending': 'Pending Only',
                'deleted': 'Deleted Only',
                'active': 'Active Only'
            };
            return labels[filterType] || 'All Records';
        }

        // Loading State Functions
        function showGlobalLoading(message = 'Processing your request...') {
            if (elements.loadingOverlay) {
                elements.loadingOverlay.querySelector('p').textContent = message;
                elements.loadingOverlay.classList.remove('d-none');
            }
        }

        function hideGlobalLoading() {
            if (elements.loadingOverlay) {
                elements.loadingOverlay.classList.add('d-none');
            }
        }

        function showAttendanceLoading() {
            if (elements.attendanceLoading) {
                elements.attendanceLoading.classList.remove('d-none');
            }
            if (elements.attendanceTable) {
                elements.attendanceTable.classList.add('d-none');
            }
        }

        function hideAttendanceLoading() {
            if (elements.attendanceLoading) {
                elements.attendanceLoading.classList.add('d-none');
            }
            if (elements.attendanceTable) {
                elements.attendanceTable.classList.remove('d-none');
            }
        }

        function showError(message, type = 'danger', duration = 5000) {
            if (elements.errorMessage) elements.errorMessage.textContent = message;
            if (elements.errorContainer) {
                elements.errorContainer.classList.remove('d-none');
                elements.errorContainer.classList.add(`alert-${type}`);

                setTimeout(() => {
                    elements.errorContainer.classList.add('d-none');
                }, duration);
            }
        }

        function showSuccess(message, duration = 3000) {
            if (elements.successMessage) elements.successMessage.textContent = message;
            if (elements.successContainer) {
                elements.successContainer.classList.remove('d-none');

                setTimeout(() => {
                    elements.successContainer.classList.add('d-none');
                }, duration);
            }
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>
@endpush