@extends('layouts.app')

@section('title', 'Student Exam Management')
@section('page-title', 'Exam Schedule Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Exam Management</li>
@endsection

@section('content')
<div class="container-fluid px-4">
    {{-- Header Section with Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Exams</h6>
                            <h2 class="mb-0" id="totalExams">0</h2>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Active Exams</h6>
                            <h2 class="mb-0" id="activeExams">0</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Upcoming</h6>
                            <h2 class="mb-0" id="upcomingExams">0</h2>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Completed</h6>
                            <h2 class="mb-0" id="completedExams">0</h2>
                        </div>
                        <i class="fas fa-flag-checkered fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Exam Schedule List
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('student_exam.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>
                        Create New Exam
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            {{-- Alert Container --}}
            <div id="alert-box"></div>

            {{-- Advanced Search and Filter Bar --}}
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               id="examSearch" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Search by exam title or hall name..."
                               autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="canceled">Canceled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" id="dateFilter" class="form-control" placeholder="Filter by date">
                </div>
                <div class="col-md-1">
                    <button id="clearFilters" class="btn btn-outline-secondary w-100" title="Clear filters">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            {{-- Table with modern styling --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="examTable">
                    <thead class="table-light">
                        <tr>
                            <th>Exam Details</th>
                            <th>Schedule</th>
                            <th>Hall</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="examBody">
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading exams...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination Info --}}
            <div class="row mt-3 align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0" id="showingInfo"></p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end gap-2">
                        <select id="itemsPerPage" class="form-select w-auto">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cancel Confirmation Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Cancellation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this exam? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Students will be notified about the cancellation.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-danger" id="confirmCancel">
                    <i class="fas fa-ban me-1"></i>Cancel Exam
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .opacity-50 {
        opacity: 0.5;
    }
    
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .input-group-text {
        background: transparent;
    }
    
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom-width: 1px;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
    
    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }
    
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
let allExams = [];
let currentPage = 1;
let itemsPerPage = 10;
let cancelExamId = null;

// Initialize Bootstrap modal
const cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));

async function loadExams() {
    try {
        const response = await fetch('/api/exams');
        const result = await response.json();

        if (response.ok) {
            allExams = result.data;
            updateStats();
            applyFiltersAndRender();
        } else {
            showAlert('danger', 'Failed to load exams');
        }
    } catch (error) {
        showAlert('danger', `Error loading exams: ${error.message}`);
    }
}

function updateStats() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const stats = {
        total: allExams.length,
        active: allExams.filter(exam => exam.status !== 'Canceled').length,
        upcoming: allExams.filter(exam => new Date(exam.date) > today && exam.status !== 'Canceled').length,
        completed: allExams.filter(exam => new Date(exam.date) < today && exam.status !== 'Canceled').length
    };
    
    document.getElementById('totalExams').textContent = stats.total;
    document.getElementById('activeExams').textContent = stats.active;
    document.getElementById('upcomingExams').textContent = stats.upcoming;
    document.getElementById('completedExams').textContent = stats.completed;
}

function applyFiltersAndRender() {
    const searchQuery = document.getElementById('examSearch').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    
    let filtered = allExams.filter(exam => {
        const matchesSearch = exam.title.toLowerCase().includes(searchQuery) || 
                             (exam.hall_name?.toLowerCase() || '').includes(searchQuery);
        
        const matchesStatus = !statusFilter || 
                             (statusFilter === 'active' && exam.status !== 'Canceled') ||
                             (statusFilter === 'canceled' && exam.status === 'Canceled');
        
        const matchesDate = !dateFilter || exam.date === dateFilter;
        
        return matchesSearch && matchesStatus && matchesDate;
    });
    
    renderExams(filtered);
    updatePaginationInfo(filtered.length);
}

function renderExams(exams) {
    const tbody = document.getElementById('examBody');
    
    if (exams.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No exams found</h5>
                    <p class="text-muted">Try adjusting your search filters</p>
                </td>
            </tr>
        `;
        return;
    }
    
    // Pagination
    const start = (currentPage - 1) * itemsPerPage;
    const paginatedExams = exams.slice(start, start + itemsPerPage);
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    tbody.innerHTML = paginatedExams.map(exam => {
        const examDate = new Date(exam.date);
        examDate.setHours(0, 0, 0, 0);
        
        const isUpcoming = examDate > today;
        const isOngoing = examDate.getTime() === today.getTime();
        const isPast = examDate < today;
        
        // Determine status badge
        let statusBadge = '';
        if (exam.status === 'Canceled') {
            statusBadge = '<span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Canceled</span>';
        } else if (isOngoing) {
            statusBadge = '<span class="badge bg-success"><i class="fas fa-play me-1"></i>Ongoing</span>';
        } else if (isUpcoming) {
            statusBadge = '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Upcoming</span>';
        } else {
            statusBadge = '<span class="badge bg-secondary"><i class="fas fa-check me-1"></i>Completed</span>';
        }
        
        // Format time range
        const timeRange = `${exam.start} - ${exam.end}`;
        
        // Determine available actions
        const actions = getActionButtons(exam, today, examDate);
        
        return `
            <tr data-id="${exam.id}" class="hover-bg-light">
                <td>
                    <div class="fw-bold">${escapeHtml(exam.title)}</div>
                    <small class="text-muted">ID: ${exam.id}</small>
                </td>
                <td>
                    <div><i class="far fa-calendar-alt me-1 text-muted"></i>${formatDate(exam.date)}</div>
                    <div><i class="far fa-clock me-1 text-muted"></i>${timeRange}</div>
                </td>
                <td>
                    <span class="badge bg-info bg-opacity-10 text-info">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        ${escapeHtml(exam.hall_name || 'Not assigned')}
                    </span>
                </td>
                <td>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                        <i class="fas fa-hourglass-half me-1"></i>
                        ${exam.duration || '-'}
                    </span>
                </td>
                <td>${statusBadge}</td>
                <td>
                    <div class="btn-group" role="group">
                        ${actions}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    
    attachEventListeners();
}

function getActionButtons(exam, today, examDate) {
    if (exam.status === 'Canceled') {
        return `
            <button class="btn btn-sm btn-outline-secondary" disabled>
                <i class="fas fa-ban me-1"></i>Canceled
            </button>
        `;
    }
    
    const cancelWindowDays = 7;
    const cancelStart = new Date(examDate);
    cancelStart.setDate(examDate.getDate() - cancelWindowDays);
    const cancelEnd = new Date(examDate);
    cancelEnd.setDate(examDate.getDate() + cancelWindowDays);
    
    const canAddMarks = today >= examDate;
    const canCancel = today >= cancelStart && today <= cancelEnd;
    
    let buttons = '';
    
    if (canAddMarks) {
        buttons += `
            <button class="btn btn-sm btn-success add-marks-btn" data-id="${exam.id}">
                <i class="fas fa-plus-circle me-1"></i>Add Marks
            </button>
        `;
    }
    
    if (canCancel) {
        buttons += `
            <button class="btn btn-sm btn-danger cancel-btn" data-id="${exam.id}">
                <i class="fas fa-ban me-1"></i>Cancel
            </button>
        `;
    }
    
    if (!canAddMarks && !canCancel) {
        buttons = `
            <button class="btn btn-sm btn-outline-secondary" disabled>
                <i class="fas fa-lock me-1"></i>Locked
            </button>
        `;
    }
    
    return buttons;
}

function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

function showAlert(type, message) {
    const alertBox = document.getElementById('alert-box');
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    
    alertBox.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    setTimeout(() => {
        const alert = alertBox.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 5000);
}

function updatePaginationInfo(totalItems) {
    const start = (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(start + itemsPerPage - 1, totalItems);
    
    document.getElementById('showingInfo').innerHTML = 
        totalItems > 0 
            ? `Showing ${start} to ${end} of ${totalItems} entries`
            : 'No entries found';
}

// Event Listeners
function attachEventListeners() {
    // Cancel buttons
    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.addEventListener('click', function() {
            cancelExamId = this.dataset.id;
            cancelModal.show();
        });
    });
    
    // Add marks buttons
    document.querySelectorAll('.add-marks-btn').forEach(button => {
        button.addEventListener('click', function() {
            const examId = this.dataset.id;
            window.location.href = `/student-exam/${examId}/marks/create`;
        });
    });
}

// Confirm cancel button in modal
document.getElementById('confirmCancel').addEventListener('click', async function() {
    if (!cancelExamId) return;
    
    try {
        const csrfToken = '{{ csrf_token() }}';
        const response = await fetch(`/api/exams/${cancelExamId}/cancel`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({})
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showAlert('success', result.message);
            cancelModal.hide();
            loadExams();
        } else {
            showAlert('danger', result.message);
        }
    } catch (error) {
        showAlert('danger', `Error: ${error.message}`);
    } finally {
        cancelExamId = null;
    }
});

// Search input with debounce
let searchTimeout;
document.getElementById('examSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        currentPage = 1;
        applyFiltersAndRender();
    }, 300);
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function() {
    currentPage = 1;
    applyFiltersAndRender();
});

// Date filter
document.getElementById('dateFilter').addEventListener('change', function() {
    currentPage = 1;
    applyFiltersAndRender();
});

// Clear filters
document.getElementById('clearFilters').addEventListener('click', function() {
    document.getElementById('examSearch').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    currentPage = 1;
    applyFiltersAndRender();
});

// Items per page
document.getElementById('itemsPerPage').addEventListener('change', function() {
    itemsPerPage = parseInt(this.value);
    currentPage = 1;
    applyFiltersAndRender();
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadExams();
});
</script>
@endpush