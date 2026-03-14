@extends('layouts.app')

@section('title', 'Manage Teachers')
@section('page-title', 'Teachers Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Teachers</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-primary bg-gradient">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="card-title text-white">Total Teachers</h4>
                                    <h2 class="text-white" id="totalTeachers">0</h2>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-success bg-gradient">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="card-title text-white">Active Teachers</h4>
                                    <h2 class="text-white" id="activeTeachers">0</h2>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-check fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-info bg-gradient">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="card-title text-white">Male Teachers</h4>
                                    <h2 class="text-white" id="maleTeachers">0</h2>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-male fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-warning bg-gradient">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="card-title text-white">Female Teachers</h4>
                                    <h2 class="text-white" id="femaleTeachers">0</h2>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-female fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card custom-card">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Teachers Management</h5>
                            <p class="text-muted mb-0">Manage all teachers and their information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" onclick="loadTeachers()" title="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add New Teacher
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body position-relative">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading teachers...</p>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="alert alert-danger d-none" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="errorText"></span>
                    </div>

                    <!-- Filters and Controls -->
                    <div class="d-flex justify-content-between align-items-center mb-3 d-none" id="controlsBar">
                        <div class="d-flex align-items-center gap-2">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary active" id="filterAll"
                                    data-status="">All</button>
                                <button type="button" class="btn btn-outline-success" id="filterActive"
                                    data-status="active">Active</button>
                                <button type="button" class="btn btn-outline-secondary" id="filterInactive"
                                    data-status="inactive">Inactive</button>
                            </div>

                            <!-- Rows Per Page -->
                            <div class="d-flex align-items-center ms-3">
                                <label for="rowsPerPage" class="form-label text-muted mb-0 me-2">Show:</label>
                                <select class="form-select form-select-sm" id="rowsPerPage" style="width: 80px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search Box -->
                        <div class="d-flex align-items-center gap-2">
                            <div class="input-group input-group-sm" style="width: 280px;">
                                <span class="input-group-text bg-transparent">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Search teachers..." id="searchInput"
                                    autocomplete="off">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn" title="Clear">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Teachers Table -->
                    <div class="table-responsive d-none" id="teachersTableContainer">
                        <table class="table table-hover" id="teachersTable">
                            <thead class="table-primary">
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th>Teacher</th>
                                    <th>Contact</th>
                                    <th class="text-center">Gender</th>
                                    <th class="text-center">Status</th>
                                    <th width="140" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teachersTableBody">
                                <!-- Teachers will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3 d-none" id="paginationSection">
                        <div class="col-md-6">
                            <div class="text-muted" id="paginationInfo">
                                Showing <span id="startRecord">0</span> to <span id="endRecord">0</span> of <span
                                    id="totalRecords">0</span> entries
                            </div>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Teachers pagination">
                                <ul class="pagination justify-content-end mb-0" id="paginationLinks">
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="text-center py-5 d-none">
                        <div class="empty-state-icon">
                            <i class="fas fa-users fa-4x text-muted mb-4"></i>
                        </div>
                        <h4 class="text-muted">No Teachers Found</h4>
                        <p class="text-muted mb-4">There are no teachers in the database yet.</p>
                        <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Add First Teacher
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activate Teacher Modal -->
    <div class="modal fade" id="activateTeacherModal" tabindex="-1" aria-labelledby="activateTeacherModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-check me-2"></i>Activate Teacher
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to activate this teacher?</p>
                    <div class="teacher-info bg-light p-3 rounded">
                        <strong id="activateTeacherName"></strong><br>
                        <small class="text-muted" id="activateTeacherEmail"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="confirmActivateBtn">
                        <i class="fas fa-user-check me-2"></i>Activate Teacher
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Deactivate Teacher Modal -->
    <div class="modal fade" id="deactivateTeacherModal" tabindex="-1" aria-labelledby="deactivateTeacherModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-slash me-2"></i>Deactivate Teacher
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to deactivate this teacher?</p>
                    <div class="teacher-info bg-light p-3 rounded">
                        <strong id="deactivateTeacherName"></strong><br>
                        <small class="text-muted" id="deactivateTeacherEmail"></small>
                    </div>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This teacher will no longer be able to access the system.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-warning" id="confirmDeactivateBtn">
                        <i class="fas fa-user-slash me-2"></i>Deactivate Teacher
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .custom-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem 0.75rem;
            font-size: 0.9rem;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
            border-color: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        .badge.rounded-pill {
            padding: 0.5em 0.8em;
            font-size: 0.75rem;
        }

        .avatar-sm {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .text-pink {
            color: #e83e8c !important;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5em 0.75em;
            border-radius: 10px;
        }

        .pagination .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            border-color: #2c3e50;
        }

        .btn-group .btn.active {
            background-color: #2c3e50;
            color: white;
            border-color: #2c3e50;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Global variables for pagination
        let currentPage = 1;
        let totalPages = 1;
        let rowsPerPage = 10;
        let totalRecords = 0;
        let currentStatusFilter = '';
        let currentSearch = '';
        let allTeachers = [];
        let isSearching = false;
        let searchTimeout = null;

        // Wait for the DOM to be loaded
        document.addEventListener('DOMContentLoaded', function () {
            initializeTeachersPage();
        });

        function initializeTeachersPage() {
            // Load teachers on page load
            loadTeachers();

            // Rows per page change
            document.getElementById('rowsPerPage').addEventListener('change', function () {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                loadTeachers();
            });

            // Search functionality with real-time search and enter key support
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function (e) {
                // Clear any existing timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
                
                // If user presses escape, clear search immediately
                if (e.data === null && searchInput.value === '') {
                    currentSearch = '';
                    currentPage = 1;
                    loadTeachers();
                    return;
                }
                
                currentSearch = e.target.value;
                
                // If search is empty, clear immediately
                if (currentSearch.trim() === '') {
                    currentSearch = '';
                    currentPage = 1;
                    loadTeachers();
                    return;
                }
                
                // Set a timeout for debouncing (400ms)
                searchTimeout = setTimeout(() => {
                    isSearching = true;
                    currentPage = 1;
                    loadTeachers();
                    searchTimeout = null;
                }, 400);
            });

            // Handle Enter key to stop searching (load immediately)
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    // Clear any pending timeout
                    if (searchTimeout) {
                        clearTimeout(searchTimeout);
                        searchTimeout = null;
                    }
                    
                    // Load teachers immediately
                    isSearching = true;
                    currentPage = 1;
                    loadTeachers();
                } else if (e.key === 'Escape') {
                    // Clear search on escape
                    this.value = '';
                    currentSearch = '';
                    currentPage = 1;
                    loadTeachers();
                }
            });

            document.getElementById('clearSearchBtn').addEventListener('click', function () {
                document.getElementById('searchInput').value = '';
                currentSearch = '';
                currentPage = 1;
                loadTeachers();
            });

            // Filter functionality
            document.getElementById('filterAll').addEventListener('click', function () {
                setActiveFilter(this, '');
            });

            document.getElementById('filterActive').addEventListener('click', function () {
                setActiveFilter(this, 'active');
            });

            document.getElementById('filterInactive').addEventListener('click', function () {
                setActiveFilter(this, 'inactive');
            });

            // Activate/Deactivate modal events
            document.getElementById('confirmActivateBtn').addEventListener('click', confirmActivateTeacher);
            document.getElementById('confirmDeactivateBtn').addEventListener('click', confirmDeactivateTeacher);
        }

        function setActiveFilter(button, status) {
            // Remove active class from all filter buttons
            document.querySelectorAll('#controlsBar .btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Add active class to clicked button
            button.classList.add('active');

            currentStatusFilter = status;
            currentPage = 1;
            loadTeachers();
        }

        function loadTeachers() {
            showLoadingState();

            // Build query parameters for backend filtering
            const params = new URLSearchParams({
                page: currentPage,
                per_page: rowsPerPage,
            });

            // Add search parameter if exists
            if (currentSearch) {
                params.append('search', currentSearch);
            }

            // Add status parameter if exists
            if (currentStatusFilter) {
                params.append('status', currentStatusFilter);
            }

            // Show loading indicator in search field if searching
            const searchInput = document.getElementById('searchInput');
            if (currentSearch) {
                const searchIcon = searchInput.parentElement.querySelector('.input-group-text i');
                if (searchIcon) {
                    searchIcon.className = 'fas fa-spinner fa-spin';
                }
            }

            // Fetch from API with parameters
            fetch(`/api/teachers?${params.toString()}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Reset search icon
                    const searchIcon = searchInput.parentElement.querySelector('.input-group-text i');
                    if (searchIcon) {
                        searchIcon.className = 'fas fa-search';
                    }
                    
                    if (data.status === 'success') {
                        // Check the new response structure
                        if (data.data && data.data.teachers) {
                            // Backend returns paginated data
                            const teachers = data.data.teachers;
                            const pagination = data.data.pagination;
                            const statistics = data.data.statistics;
                            
                            renderTeachersTable(teachers);
                            updatePagination(pagination);
                            updateStatistics(statistics);
                        } else if (Array.isArray(data.data)) {
                            // Old structure: direct array
                            allTeachers = data.data;
                            renderFilteredTeachers();
                            updateStatistics(allTeachers);
                        } else {
                            throw new Error('Invalid data structure received from server');
                        }
                        showContentState();
                    } else {
                        throw new Error(data.message || 'Failed to load teachers');
                    }
                })
                .catch(error => {
                    console.error('Error loading teachers:', error);
                    // Reset search icon on error too
                    const searchIcon = searchInput.parentElement.querySelector('.input-group-text i');
                    if (searchIcon) {
                        searchIcon.className = 'fas fa-search';
                    }
                    showErrorState('Error loading teachers: ' + error.message);
                })
                .finally(() => {
                    isSearching = false;
                });
        }

        // Fallback function for frontend filtering (if backend doesn't support it)
        function renderFilteredTeachers() {
            if (!Array.isArray(allTeachers)) {
                allTeachers = [];
            }

            let filteredTeachers = allTeachers;

            // Apply status filter
            if (currentStatusFilter === 'active') {
                filteredTeachers = filteredTeachers.filter(teacher => teacher.is_active);
            } else if (currentStatusFilter === 'inactive') {
                filteredTeachers = filteredTeachers.filter(teacher => !teacher.is_active);
            }

            // Apply search filter
            if (currentSearch) {
                const searchTerm = currentSearch.toLowerCase();
                filteredTeachers = filteredTeachers.filter(teacher =>
                    (teacher.custom_id && teacher.custom_id.toLowerCase().includes(searchTerm)) ||
                    (teacher.fname && teacher.fname.toLowerCase().includes(searchTerm)) ||
                    (teacher.lname && teacher.lname.toLowerCase().includes(searchTerm)) ||
                    (teacher.email && teacher.email.toLowerCase().includes(searchTerm)) ||
                    (teacher.nic && teacher.nic.toLowerCase().includes(searchTerm)) ||
                    (teacher.mobile && teacher.mobile.includes(searchTerm))
                );
            }

            totalRecords = filteredTeachers.length;
            totalPages = Math.ceil(totalRecords / rowsPerPage);

            // Ensure current page is valid
            if (currentPage > totalPages && totalPages > 0) {
                currentPage = totalPages;
            } else if (currentPage < 1) {
                currentPage = 1;
            }

            // Get teachers for current page
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const paginatedTeachers = filteredTeachers.slice(startIndex, endIndex);

            renderTeachersTable(paginatedTeachers);
            updatePaginationForFrontend();
        }

        function renderTeachersTable(teachers) {
            const tbody = document.getElementById('teachersTableBody');
            const tableContainer = document.getElementById('teachersTableContainer');
            const emptyState = document.getElementById('emptyState');
            const paginationSection = document.getElementById('paginationSection');
            const controlsBar = document.getElementById('controlsBar');

            if (!tbody) return;

            tbody.innerHTML = '';

            if (!teachers || teachers.length === 0) {
                tableContainer.classList.add('d-none');
                paginationSection.classList.add('d-none');
                // DO NOT hide controlsBar - keep it visible
                emptyState.classList.remove('d-none');
                
                // Update empty state message based on search
                const emptyStateTitle = document.querySelector('#emptyState h4');
                const emptyStateMessage = document.querySelector('#emptyState p');
                
                if (currentSearch) {
                    emptyStateTitle.textContent = 'No Teachers Found';
                    emptyStateMessage.textContent = `No teachers match your search for "${currentSearch}". Try a different search term.`;
                } else if (currentStatusFilter) {
                    const statusText = currentStatusFilter === 'active' ? 'Active' : 'Inactive';
                    emptyStateTitle.textContent = `No ${statusText} Teachers`;
                    emptyStateMessage.textContent = `There are no ${statusText.toLowerCase()} teachers in the database.`;
                } else {
                    emptyStateTitle.textContent = 'No Teachers Found';
                    emptyStateMessage.textContent = 'There are no teachers in the database yet.';
                }
                
                return;
            }

            tableContainer.classList.remove('d-none');
            paginationSection.classList.remove('d-none');
            // Controls bar should already be visible, but ensure it is
            controlsBar.classList.remove('d-none');
            emptyState.classList.add('d-none');

            teachers.forEach((teacher, index) => {
                const startRecord = (currentPage - 1) * rowsPerPage;
                const statusBadge = teacher.is_active ?
                    '<span class="badge bg-success rounded-pill"><i class="fas fa-circle me-1"></i>Active</span>' :
                    '<span class="badge bg-secondary rounded-pill"><i class="fas fa-circle me-1"></i>Inactive</span>';

                const genderIcon = teacher.gender === 'male' ?
                    '<i class="fas fa-mars text-primary"></i>' :
                    '<i class="fas fa-venus text-pink"></i>';

                // Escape HTML to prevent XSS
                const firstName = escapeHtml(teacher.fname || '');
                const lastName = escapeHtml(teacher.lname || '');
                const email = escapeHtml(teacher.email || 'No email');
                const nic = escapeHtml(teacher.nic || 'No NIC');
                const mobile = escapeHtml(teacher.mobile || 'No phone');

                const row = `
                        <tr class="align-middle">
                            <td class="text-center fw-bold text-muted">${startRecord + index + 1}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-gradient rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <span class="fw-bold">${firstName.charAt(0)}${lastName.charAt(0)}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">${firstName} ${lastName}</h6>
                                        <small class="text-muted">${nic}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="mb-1">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        <small>${email}</small>
                                    </div>
                                    <div>
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        <small>${mobile}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fs-5">${genderIcon}</span>
                            </td>
                            <td class="text-center">
                                ${statusBadge}
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary rounded-start" title="View" onclick="viewTeacher(${teacher.id})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" title="Edit" onclick="editTeacher(${teacher.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    ${teacher.is_active ?
                        `<button class="btn btn-outline-danger rounded-end" title="Deactivate" onclick="showDeactivateModal(${teacher.id}, '${escapeHtml(teacher.fname + ' ' + teacher.lname)}', '${escapeHtml(teacher.email)}')">
                                            <i class="fas fa-user-slash"></i>
                                        </button>` :
                        `<button class="btn btn-outline-success rounded-end" title="Activate" onclick="showActivateModal(${teacher.id}, '${escapeHtml(teacher.fname + ' ' + teacher.lname)}', '${escapeHtml(teacher.email)}')">
                                            <i class="fas fa-user-check"></i>
                                        </button>`
                    }
                                </div>
                            </td>
                        </tr>
                    `;
                tbody.innerHTML += row;
            });
        }

        // Update pagination for backend response
        function updatePagination(pagination) {
            if (pagination) {
                document.getElementById('startRecord').textContent = pagination.from || 0;
                document.getElementById('endRecord').textContent = pagination.to || 0;
                document.getElementById('totalRecords').textContent = pagination.total || 0;

                currentPage = pagination.current_page;
                totalPages = pagination.last_page;
                totalRecords = pagination.total;
            } else {
                updatePaginationForFrontend();
            }
            renderPaginationLinks();
        }

        // Update pagination for frontend filtering
        function updatePaginationForFrontend() {
            const startRecord = totalRecords > 0 ? ((currentPage - 1) * rowsPerPage) + 1 : 0;
            const endRecord = Math.min(currentPage * rowsPerPage, totalRecords);

            document.getElementById('startRecord').textContent = startRecord;
            document.getElementById('endRecord').textContent = endRecord;
            document.getElementById('totalRecords').textContent = totalRecords;
        }

        function renderPaginationLinks() {
            const paginationLinks = document.getElementById('paginationLinks');
            paginationLinks.innerHTML = '';

            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `
                        <a class="page-link" href="#" onclick="changePage(${currentPage - 1})" aria-label="Previous">
                            <span aria-hidden="true">Previous</span>
                        </a>
                    `;
            paginationLinks.appendChild(prevLi);

            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // First page ellipsis
            if (startPage > 1) {
                const firstLi = document.createElement('li');
                firstLi.className = 'page-item';
                firstLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(1)">1</a>`;
                paginationLinks.appendChild(firstLi);
                
                if (startPage > 2) {
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    ellipsisLi.innerHTML = '<span class="page-link">...</span>';
                    paginationLinks.appendChild(ellipsisLi);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${currentPage === i ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
                paginationLinks.appendChild(li);
            }

            // Last page ellipsis
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsisLi = document.createElement('li');
                    ellipsisLi.className = 'page-item disabled';
                    ellipsisLi.innerHTML = '<span class="page-link">...</span>';
                    paginationLinks.appendChild(ellipsisLi);
                }
                
                const lastLi = document.createElement('li');
                lastLi.className = 'page-item';
                lastLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${totalPages})">${totalPages}</a>`;
                paginationLinks.appendChild(lastLi);
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `
                        <a class="page-link" href="#" onclick="changePage(${currentPage + 1})" aria-label="Next">
                            <span aria-hidden="true">Next</span>
                        </a>
                    `;
            paginationLinks.appendChild(nextLi);
        }

        function changePage(page) {
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            loadTeachers();
        }

        // Update statistics for backend response
        function updateStatistics(statistics) {
            if (statistics) {
                document.getElementById('totalTeachers').textContent = statistics.total || 0;
                document.getElementById('activeTeachers').textContent = statistics.active || 0;
                document.getElementById('maleTeachers').textContent = statistics.male || 0;
                document.getElementById('femaleTeachers').textContent = statistics.female || 0;
            } else {
                updateStatisticsFromArray(allTeachers);
            }
        }

        // Fallback for frontend statistics
        function updateStatisticsFromArray(teachers) {
            if (!Array.isArray(teachers)) {
                teachers = [];
            }
            const totalTeachers = teachers.length;
            const activeTeachers = teachers.filter(t => t.is_active).length;
            const maleTeachers = teachers.filter(t => t.gender === 'Male' || t.gender === 'male').length;
            const femaleTeachers = teachers.filter(t => t.gender === 'Female' || t.gender === 'female').length;

            document.getElementById('totalTeachers').textContent = totalTeachers;
            document.getElementById('activeTeachers').textContent = activeTeachers;
            document.getElementById('maleTeachers').textContent = maleTeachers;
            document.getElementById('femaleTeachers').textContent = femaleTeachers;
        }

        function showActivateModal(teacherId, teacherName, teacherEmail) {
            document.getElementById('activateTeacherName').textContent = teacherName;
            document.getElementById('activateTeacherEmail').textContent = teacherEmail;

            const modal = new bootstrap.Modal(document.getElementById('activateTeacherModal'));
            modal.show();

            // Store teacher ID for the confirm action
            document.getElementById('confirmActivateBtn').setAttribute('data-teacher-id', teacherId);
        }

        function showDeactivateModal(teacherId, teacherName, teacherEmail) {
            document.getElementById('deactivateTeacherName').textContent = teacherName;
            document.getElementById('deactivateTeacherEmail').textContent = teacherEmail;

            const modal = new bootstrap.Modal(document.getElementById('deactivateTeacherModal'));
            modal.show();

            // Store teacher ID for the confirm action
            document.getElementById('confirmDeactivateBtn').setAttribute('data-teacher-id', teacherId);
        }

        function confirmActivateTeacher() {
            const teacherId = document.getElementById('confirmActivateBtn').getAttribute('data-teacher-id');

            fetch(`/api/teachers/${teacherId}/reactivate`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('activateTeacherModal'));
                        modal.hide();

                        // Show success message and reload
                        showAlert('Teacher activated successfully!', 'success');
                        loadTeachers();
                    } else {
                        throw new Error(data.message || 'Failed to activate teacher');
                    }
                })
                .catch(error => {
                    console.error('Error activating teacher:', error);
                    showAlert('Error activating teacher: ' + error.message, 'danger');
                });
        }

        function confirmDeactivateTeacher() {
            const teacherId = document.getElementById('confirmDeactivateBtn').getAttribute('data-teacher-id');

            fetch(`/api/teachers/${teacherId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deactivateTeacherModal'));
                        modal.hide();

                        // Show success message and reload
                        showAlert('Teacher deactivated successfully!', 'success');
                        loadTeachers();
                    } else {
                        throw new Error(data.message || 'Failed to deactivate teacher');
                    }
                })
                .catch(error => {
                    console.error('Error deactivating teacher:', error);
                    showAlert('Error deactivating teacher: ' + error.message, 'danger');
                });
        }

        function viewTeacher(id) {
            window.location.href = `/teachers/${id}`;
        }

        function editTeacher(id) {
            window.location.href = `/teachers/${id}/edit`;
        }

        // Helper functions
        function showLoadingState() {
            document.getElementById('loadingSpinner').classList.remove('d-none');
            // DO NOT hide controlsBar during loading
            document.getElementById('teachersTableContainer').classList.add('d-none');
            document.getElementById('paginationSection').classList.add('d-none');
            document.getElementById('emptyState').classList.add('d-none');
            document.getElementById('errorMessage').classList.add('d-none');
        }

        function showContentState() {
            document.getElementById('loadingSpinner').classList.add('d-none');
            // Ensure controls bar is visible
            document.getElementById('controlsBar').classList.remove('d-none');
        }

        function showErrorState(message) {
            document.getElementById('loadingSpinner').classList.add('d-none');
            // Keep controls bar visible on error
            document.getElementById('controlsBar').classList.remove('d-none');
            document.getElementById('errorMessage').classList.remove('d-none');
            document.getElementById('errorText').textContent = message;
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

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;

            const container = document.querySelector('.container') || document.querySelector('.card-body');
            if (container) {
                container.insertBefore(alertDiv, container.firstChild);

                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.toString().replace(/[&<>"']/g, m => map[m]);
        }
    </script>
@endpush