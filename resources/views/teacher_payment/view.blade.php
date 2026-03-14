@extends('layouts.app')

@section('title', 'Teacher Payment Matrix')
@section('page-title', 'Teacher Payment Matrix')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('teacher_payment.index') }}">Teacher Payments</a></li>
    <li class="breadcrumb-item active">Payment Matrix</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" id="teacherId" value="{{ $teacherId }}">

    <!-- Teacher Summary -->
    <div class="row mb-4" id="teacherSummary" style="display: none;">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-primary border-3 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Period
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="periodDisplay">-</div>
                            <div class="text-muted small mt-1" id="classesDisplay">0 classes</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-info border-3 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Students
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="totalStudentsDisplay">0</div>
                            <div class="text-muted small mt-1">
                                <span class="text-success" id="paidStudentsDisplay">0 paid</span> •
                                <span class="text-danger" id="unpaidStudentsDisplay">0 unpaid</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-success border-3 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Payment Rate
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="paymentRateDisplay">0%</div>
                            <div class="progress progress-sm mt-2">
                                <div id="paymentProgressBar" class="progress-bar bg-success" role="progressbar"
                                    style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-warning border-3 shadow-sm h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Collection
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="totalCollectionDisplay">Rs 0</div>
                            <div class="text-muted small mt-1" id="freeCardDisplay">
                                0 free cards
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Filter -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="fas fa-filter me-2 text-primary"></i>Filter Data
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">Year</label>
                        <select class="form-select form-select-sm" id="yearFilter">
                            @php
                                $currentYear = date('Y');
                                $years = range($currentYear, $currentYear - 5);
                            @endphp
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">Month</label>
                        <select class="form-select form-select-sm" id="monthFilter">
                            @php
                                $months = [
                                    '01' => 'Jan',
                                    '02' => 'Feb',
                                    '03' => 'Mar',
                                    '04' => 'Apr',
                                    '05' => 'May',
                                    '06' => 'Jun',
                                    '07' => 'Jul',
                                    '08' => 'Aug',
                                    '09' => 'Sep',
                                    '10' => 'Oct',
                                    '11' => 'Nov',
                                    '12' => 'Dec'
                                ];
                            @endphp
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}" {{ $key == date('m') ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">Per Page</label>
                        <select class="form-select form-select-sm" id="perPageFilter">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="mb-2 w-100">
                        <button class="btn btn-primary btn-sm w-100" onclick="loadData()" id="loadBtn">
                            <i class="fas fa-sync-alt me-1"></i>Load Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div class="text-center py-5" id="loadingState">
        <div class="spinner-border text-primary" style="width: 2rem; height: 2rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h6 class="mt-3 text-primary">Loading Payment Data...</h6>
    </div>

    <!-- Error State -->
    <div class="text-center py-5" id="errorState" style="display: none;">
        <div class="text-danger mb-3">
            <i class="fas fa-exclamation-triangle fa-2x"></i>
        </div>
        <h6 class="mb-2" id="errorMessage">Error loading data</h6>
        <button class="btn btn-primary btn-sm" onclick="loadData()">
            <i class="fas fa-redo me-1"></i>Try Again
        </button>
    </div>

    <!-- No Data Message -->
    <div class="text-center py-5" id="noDataMessage" style="display: none;">
        <div class="mb-3">
            <i class="fas fa-database fa-3x text-muted opacity-50"></i>
        </div>
        <h6 class="text-muted mb-2">No Data Available</h6>
        <p class="text-muted small">No payment data found for the selected period</p>
    </div>

    <!-- Class Cards Grid -->
    <div id="classCardsContainer" class="row mb-4" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-chalkboard me-2 text-primary"></i>Classes
                        <small class="text-muted ms-1" id="selectedMonthInfo"></small>
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3" id="classCardsGrid">
                        <!-- Class cards will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table with Pagination -->
    <div id="studentsContainer" class="row mb-4" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-users me-2 text-primary"></i>Students
                                <small class="text-muted ms-1" id="selectedClassInfo"></small>
                            </h6>
                            <small class="text-muted small" id="paginationInfo">Showing 0 students</small>
                        </div>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm" onclick="goBackToClasses()">
                                <i class="fas fa-arrow-left me-1"></i>Back to Classes
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <!-- Students Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th width="40">#</th>
                                    <th width="100">Student ID</th>
                                    <th>Student Name</th>
                                    <th width="80">Grade</th>
                                    <th width="100">Status</th>
                                    <th width="100">Amount</th>
                                    <th width="80">Payments</th>
                                    <th width="80">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentsTableBody">
                                <!-- Students will be loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <div class="text-muted small">
                            <span id="totalItems">0</span> students
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0" id="paginationControls">
                                <!-- Pagination buttons will be generated here -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-3">
                    <h6 class="modal-title mb-0 fw-bold">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Payment Details
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="paymentDetailsContent">
                        <!-- Payment details will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .class-card {
            transition: all 0.2s ease;
            border: 1px solid #dee2e6;
            border-left: 3px solid #4e73df;
            cursor: pointer;
            height: 100%;
        }

        .class-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            border-color: #4e73df;
        }

        .class-card.selected {
            background-color: #f8f9fc;
            border-color: #28a745;
            border-left-color: #28a745;
        }

        .stats-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }

        .student-row.paid {
            background-color: rgba(40, 167, 69, 0.03);
        }

        .student-row.unpaid {
            background-color: rgba(220, 53, 69, 0.03);
        }

        .student-row.free {
            background-color: rgba(23, 162, 184, 0.03);
        }

        .progress-thin {
            height: 4px;
            border-radius: 2px;
        }

        .avatar-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 11px;
        }

        .pagination-sm .page-link {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }

        .table th {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            font-size: 0.8rem;
            vertical-align: middle;
            padding: 0.75rem 0.5rem;
        }

        .table tfoot th {
            background-color: #f8f9fa;
            font-weight: 700;
        }

        .badge-sm {
            font-size: 0.65rem;
            padding: 0.15rem 0.35rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Global variables
        let matrixData = null;
        let selectedClassId = null;
        let selectedClassName = '';
        let currentClassStudents = [];
        let currentPage = 1;
        let perPage = 25;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            perPage = parseInt(document.getElementById('perPageFilter').value);
            // Auto-load data
            setTimeout(() => loadData(), 100);
        });

        // Load data
        async function loadData() {
            const teacherId = document.getElementById('teacherId').value;
            const year = document.getElementById('yearFilter').value;
            const month = document.getElementById('monthFilter').value;
            const yearMonth = `${year}-${month}`;
            perPage = parseInt(document.getElementById('perPageFilter').value);

            // Show loading state
            showLoading();

            try {
                // Build API URL - Note: No pagination in URL since we get all data at once
                const apiUrl = `/api/teacher-payments/class-wise/${teacherId}/${yearMonth}`;

                // Make API call
                const response = await fetch(apiUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (!data) {
                    throw new Error('No data received from server');
                }

                if (data.status === 'success') {
                    matrixData = data;
                    renderTeacherSummary(data);
                    renderClassCards(data.classes);
                    showClassCards();
                    hideErrorState();
                } else {
                    throw new Error(data.message || 'Failed to load data');
                }

            } catch (error) {
                console.error('Error in loadData:', error);
                showErrorState('Failed to load payment data: ' + error.message);
                hideAllContainers();
            } finally {
                hideLoading();
            }
        }

        // Render teacher summary
        function renderTeacherSummary(data) {
            const teacherSummary = document.getElementById('teacherSummary');
            if (!teacherSummary) return;

            teacherSummary.style.display = 'flex';

            // Update period
            document.getElementById('periodDisplay').textContent = data.year_month || '-';

            // Update summary from summary object
            if (data.summary) {
                const summary = data.summary;
                document.getElementById('classesDisplay').textContent = `${summary.total_classes || 0} classes`;
                document.getElementById('totalStudentsDisplay').textContent = summary.total_students || 0;
                document.getElementById('paidStudentsDisplay').textContent = `${summary.paid_students || 0} paid`;
                document.getElementById('unpaidStudentsDisplay').textContent = `${summary.unpaid_students || 0} unpaid`;
                document.getElementById('freeCardDisplay').textContent = `${summary.free_card_students || 0} free cards`;

                // Calculate payment rate
                const totalStudents = summary.total_students || 0;
                const paidStudents = summary.paid_students || 0;
                const paymentRate = totalStudents > 0 ? Math.round((paidStudents / totalStudents) * 100) : 0;

                document.getElementById('paymentRateDisplay').textContent = `${paymentRate}%`;
                document.getElementById('paymentProgressBar').style.width = `${paymentRate}%`;

                // Calculate total collection
                let totalCollection = 0;
                if (data.classes && Array.isArray(data.classes)) {
                    data.classes.forEach(cls => {
                        if (cls.students && Array.isArray(cls.students)) {
                            cls.students.forEach(student => {
                                totalCollection += parseFloat(student.total_paid) || 0;
                            });
                        }
                    });
                }
                document.getElementById('totalCollectionDisplay').textContent = `Rs ${formatNumber(totalCollection)}`;
            }

            // Update selected month info
            const selectedMonthInfo = document.getElementById('selectedMonthInfo');
            if (selectedMonthInfo) {
                selectedMonthInfo.textContent = `for ${data.year_month || 'selected month'}`;
            }
        }

        // Render class cards
        function renderClassCards(classes) {
            const container = document.getElementById('classCardsGrid');
            if (!container) return;

            if (!classes || !Array.isArray(classes) || classes.length === 0) {
                container.innerHTML = `
                                <div class="col-12">
                                    <div class="alert alert-info py-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No classes found for this teacher in the selected month.
                                    </div>
                                </div>
                            `;
                return;
            }

            let html = '';

            classes.forEach(cls => {
                const className = cls.class_name || 'Unnamed Class';
                const grade = cls.grade || 'N/A';
                const subject = cls.subject || '';
                const totalStudents = cls.total_students || 0;
                const paidStudents = cls.paid_students || 0;
                const unpaidStudents = cls.unpaid_students || 0;
                const freeCardStudents = cls.free_card_students || 0;

                // Calculate payment rate
                const paidPercentage = totalStudents > 0
                    ? Math.round((paidStudents / totalStudents) * 100)
                    : 0;

                // Determine progress bar color
                let progressColor = 'bg-danger';
                if (paidPercentage >= 70) progressColor = 'bg-success';
                else if (paidPercentage >= 40) progressColor = 'bg-warning';

                // Calculate total collection for this class
                let classCollection = 0;
                if (cls.students && Array.isArray(cls.students)) {
                    cls.students.forEach(student => {
                        classCollection += parseFloat(student.total_paid) || 0;
                    });
                }

                html += `
                                <div class="col-lg-4 col-md-6">
                                    <div class="card class-card" onclick="selectClass(${cls.class_id}, '${escapeString(className)}')">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="card-title fw-bold text-dark mb-1 small">${className}</h6>
                                                    <p class="card-text text-muted mb-0 small">
                                                        <i class="fas fa-graduation-cap me-1"></i>Grade ${grade}
                                                        ${subject ? `• ${subject}` : ''}
                                                    </p>
                                                </div>
                                                <span class="badge bg-primary stats-badge">${totalStudents} students</span>
                                            </div>

                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="text-muted small">Payment Rate</span>
                                                    <span class="fw-bold small ${paidPercentage >= 70 ? 'text-success' : paidPercentage >= 40 ? 'text-warning' : 'text-danger'}">
                                                        ${paidPercentage}%
                                                    </span>
                                                </div>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar ${progressColor}" style="width: ${paidPercentage}%"></div>
                                                </div>
                                            </div>

                                            <div class="row g-1 mb-2">
                                                <div class="col-4">
                                                    <div class="text-center p-1 bg-success bg-opacity-10 rounded">
                                                        <div class="fw-bold text-success small">${paidStudents}</div>
                                                        <small class="text-muted">Paid</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-center p-1 bg-danger bg-opacity-10 rounded">
                                                        <div class="fw-bold text-danger small">${unpaidStudents}</div>
                                                        <small class="text-muted">Unpaid</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-center p-1 bg-info bg-opacity-10 rounded">
                                                        <div class="fw-bold text-info small">${freeCardStudents}</div>
                                                        <small class="text-muted">Free</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-2 pt-2 border-top">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted small">Collection</small>
                                                    <span class="fw-bold text-success small">Rs ${formatNumber(classCollection)}</span>
                                                </div>
                                                <div class="mt-1">
                                                    <small class="text-muted small">
                                                        <i class="fas fa-money-bill-wave me-1"></i>Average: Rs ${formatNumber(totalStudents > 0 ? classCollection / totalStudents : 0)}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
            });

            container.innerHTML = html;
        }

        // Select a class
        function selectClass(classId, className) {
            selectedClassId = classId;
            selectedClassName = unescapeString(className);

            // Remove selected class from all cards
            document.querySelectorAll('.class-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');

            // Show students for selected class
            showStudentsForClass(classId, selectedClassName);
        }

        // Show students for selected class
        function showStudentsForClass(classId, className) {
            if (!matrixData || !matrixData.classes) return;

            // Find the selected class
            const selectedClass = matrixData.classes.find(cls => cls.class_id == classId);
            if (!selectedClass) return;

            // Store all students for this class
            currentClassStudents = selectedClass.students || [];

            // Update selected class info
            const selectedClassInfo = document.getElementById('selectedClassInfo');
            if (selectedClassInfo) {
                selectedClassInfo.textContent = `- ${className} (Grade ${selectedClass.grade || 'N/A'})`;
            }

            // Reset to first page
            currentPage = 1;

            // Update pagination info
            updatePaginationInfo();

            // Render students table
            renderStudentsTable();

            // Show students container
            document.getElementById('classCardsContainer').style.display = 'none';
            document.getElementById('studentsContainer').style.display = 'block';
        }

        // Render students table with pagination
        function renderStudentsTable() {
            const tableBody = document.getElementById('studentsTableBody');
            if (!tableBody) return;

            // Calculate pagination
            const startIndex = (currentPage - 1) * perPage;
            const endIndex = Math.min(startIndex + perPage, currentClassStudents.length);
            const pageStudents = currentClassStudents.slice(startIndex, endIndex);

            let html = '';

            if (pageStudents.length === 0) {
                html = `
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-users fa-lg mb-2 opacity-50"></i>
                                        <p class="mb-0 small">No students found in this class</p>
                                    </td>
                                </tr>
                            `;
            } else {
                pageStudents.forEach((student, index) => {
                    const studentName = student.name || 'Unknown';
                    const studentId = student.custom_id || student.student_id || 'N/A';
                    const grade = student.grade || '';
                    const totalPaid = student.total_paid || 0;
                    const paymentCount = student.payments ? student.payments.length : 0;
                    const status = student.status || 'Unpaid';

                    // Determine status class
                    let statusClass = 'secondary';
                    let rowClass = '';
                    if (status === 'Paid') {
                        statusClass = 'success';
                        rowClass = 'paid';
                    } else if (status === 'Unpaid') {
                        statusClass = 'danger';
                        rowClass = 'unpaid';
                    } else if (status === 'Free') {
                        statusClass = 'info';
                        rowClass = 'free';
                    }

                    // Get initials for avatar
                    const initials = getInitials(studentName);

                    html += `
                                    <tr class="student-row ${rowClass}">
                                        <td class="text-muted">${startIndex + index + 1}</td>
                                        <td>
                                            <span class="fw-medium small">${studentId}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                                                    ${initials}
                                                </div>
                                                <div>
                                                    <div class="fw-medium small">${studentName}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small">${grade || '-'}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-${statusClass} stats-badge">${status}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold small ${totalPaid > 0 ? 'text-success' : 'text-muted'}">
                                                Rs ${formatNumber(totalPaid)}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary stats-badge">${paymentCount}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary ${paymentCount === 0 ? 'disabled' : ''}" 
                                                    onclick="showPaymentDetails(${JSON.stringify(student).replace(/"/g, '&quot;')})"
                                                    title="View payment details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                });
            }

            tableBody.innerHTML = html;
        }

        // Update pagination information
        function updatePaginationInfo() {
            const totalItems = currentClassStudents.length;
            const totalPages = Math.ceil(totalItems / perPage);

            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('paginationInfo').textContent = `Showing ${Math.min(perPage, totalItems)} of ${totalItems} students`;

            const paginationControls = document.getElementById('paginationControls');
            if (!paginationControls) return;

            let html = '';

            // Previous button
            html += `
                            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                                <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        `;

            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // First page
            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1); return false;">1</a></li>`;
                if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                html += `
                                <li class="page-item ${i === currentPage ? 'active' : ''}">
                                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                                </li>
                            `;
            }

            // Last page
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${totalPages}); return false;">${totalPages}</a></li>`;
            }

            // Next button
            html += `
                            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                                <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        `;

            paginationControls.innerHTML = html;
        }

        // Change page
        function changePage(page) {
            const totalPages = Math.ceil(currentClassStudents.length / perPage);
            if (page < 1 || page > totalPages || page === currentPage) return;

            currentPage = page;
            renderStudentsTable();
            updatePaginationInfo();
            // Scroll to top of table
            const table = document.querySelector('#studentsTableBody');
            if (table) table.parentElement.parentElement.scrollIntoView({ behavior: 'smooth' });
        }

        // Show payment details modal (Updated version with table)
        function showPaymentDetails(student) {
            if (!student) return;

            // Parse student data if it's a string
            if (typeof student === 'string') {
                try {
                    student = JSON.parse(student.replace(/&quot;/g, '"'));
                } catch (e) {
                    console.error('Error parsing student data:', e);
                    return;
                }
            }

            const modalContent = document.getElementById('paymentDetailsContent');
            if (!modalContent) return;

            const studentName = student.name || 'Unknown';
            const studentId = student.custom_id || student.student_id || 'N/A';
            const totalPaid = student.total_paid || 0;
            const paymentCount = student.payments ? student.payments.length : 0;
            const status = student.status || 'Unpaid';
            const payments = student.payments || [];

            let html = `
                            <div class="p-4">
                                <!-- Student Info Header -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <h6 class="fw-bold text-dark mb-2">${studentName}</h6>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            <span class="badge bg-primary small">ID: ${studentId}</span>
                                            <span class="badge bg-${status === 'Paid' ? 'success' : status === 'Unpaid' ? 'danger' : 'info'} small">${status}</span>
                                            <span class="badge bg-secondary small">${paymentCount} payment${paymentCount !== 1 ? 's' : ''}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="bg-light p-3 rounded">
                                            <h4 class="fw-bold text-success mb-0">Rs ${formatNumber(totalPaid)}</h4>
                                            <small class="text-muted">Total Paid Amount</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment History Section -->
                                <div class="mb-3">
                                    <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                        <i class="fas fa-history me-2"></i>Payment History
                                    </h6>
                        `;

            if (paymentCount > 0) {
                // Calculate summary stats
                let totalAmount = 0;
                let earliestDate = null;
                let latestDate = null;

                payments.forEach(payment => {
                    const amount = parseFloat(payment.amount) || 0;
                    totalAmount += amount;

                    const paymentDate = new Date(payment.date);
                    if (!earliestDate || paymentDate < earliestDate) earliestDate = paymentDate;
                    if (!latestDate || paymentDate > latestDate) latestDate = paymentDate;
                });

                // Summary stats row
                html += `
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <div class="text-center">
                                                    <div class="h5 fw-bold text-primary">${paymentCount}</div>
                                                    <small class="text-muted">Total Payments</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <div class="text-center">
                                                    <div class="h5 fw-bold text-success">Rs ${formatNumber(totalAmount)}</div>
                                                    <small class="text-muted">Total Amount</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <div class="text-center">
                                                    <div class="h5 fw-bold text-info">${earliestDate ? formatDate(earliestDate) : 'N/A'}</div>
                                                    <small class="text-muted">First Payment</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <div class="text-center">
                                                    <div class="h5 fw-bold text-warning">${latestDate ? formatDate(latestDate) : 'N/A'}</div>
                                                    <small class="text-muted">Last Payment</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                // Payments Table with all requested columns
                html += `
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">#</th>
                                                <th width="100">Date</th>
                                                <th width="120">Payment For</th>
                                                <th width="150">Payment Date</th>
                                                <th width="100" class="text-end">Amount</th>
                                                <th width="80">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            `;

                // Sort payments by date (newest first)
                const sortedPayments = [...payments].sort((a, b) => {
                    return new Date(b.date) - new Date(a.date);
                });

                sortedPayments.forEach((payment, index) => {
                    const amount = payment.amount || 0;
                    const date = formatDate(payment.date) || 'N/A';
                    const paymentFor = payment.payment_for || 'N/A';
                    const paymentDateTime = formatDateTime(payment.date) || 'N/A';
                    const paymentDate = new Date(payment.date);
                    const paymentStatus = 'Paid';

                    html += `
                                    <tr>
                                        <td class="text-muted">${index + 1}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium small">${date}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small">${paymentFor}</span>
                                        </td>
                                        <td>
                                            <span class="small">${paymentDateTime}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold text-success">Rs ${formatNumber(amount)}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success small">${paymentStatus}</span>
                                        </td>
                                    </tr>
                                `;
                });

                html += `
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="4" class="text-end">Total Amount:</th>
                                                <th class="text-end fw-bold text-success">Rs ${formatNumber(totalAmount)}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            `;

                // Additional statistics
                const averagePayment = paymentCount > 0 ? totalAmount / paymentCount : 0;
                const formattedAverage = formatNumber(averagePayment);

                html += `
                                <div class="mt-3 pt-3 border-top">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="small text-muted">
                                                <i class="fas fa-calculator me-1"></i>
                                                Average Payment: <span class="fw-bold text-dark">Rs ${formattedAverage}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="small text-muted">
                                                <i class="fas fa-calendar-check me-1"></i>
                                                Payments This Year: <span class="fw-bold text-dark">${getPaymentsThisYear(payments)}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <div class="small text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                Last Updated: <span class="fw-bold text-dark">${formatDateTime(new Date())}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
            } else {
                html += `
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-receipt fa-3x text-muted opacity-50"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">No Payment History</h6>
                                    <p class="text-muted small mb-0">This student has no payment records yet</p>
                                </div>
                            `;
            }

            html += `
                                </div>
                            </div>
                        `;

            modalContent.innerHTML = html;

            // Show modal using Bootstrap 5
            const modalElement = document.getElementById('paymentDetailsModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }

        // Go back to class cards
        function goBackToClasses() {
            document.getElementById('studentsContainer').style.display = 'none';
            document.getElementById('classCardsContainer').style.display = 'block';
            selectedClassId = null;
            selectedClassName = '';
            currentClassStudents = [];
            currentPage = 1;
        }

        // UI State Management Functions
        function showLoading() {
            document.getElementById('loadingState').style.display = 'block';
            hideAllContainers();

            const loadBtn = document.getElementById('loadBtn');
            if (loadBtn) {
                loadBtn.disabled = true;
                loadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
            }
        }

        function hideLoading() {
            document.getElementById('loadingState').style.display = 'none';

            const loadBtn = document.getElementById('loadBtn');
            if (loadBtn) {
                loadBtn.disabled = false;
                loadBtn.innerHTML = '<i class="fas fa-sync-alt me-1"></i>Load Data';
            }
        }

        function showClassCards() {
            document.getElementById('classCardsContainer').style.display = 'block';
            document.getElementById('studentsContainer').style.display = 'none';
            document.getElementById('noDataMessage').style.display = 'none';
            document.getElementById('errorState').style.display = 'none';
            document.getElementById('teacherSummary').style.display = 'flex';
        }

        function hideAllContainers() {
            ['classCardsContainer', 'studentsContainer', 'noDataMessage', 'errorState', 'teacherSummary']
                .forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.style.display = 'none';
                });
        }

        function showErrorState(message) {
            const errorState = document.getElementById('errorState');
            const errorMessage = document.getElementById('errorMessage');

            if (errorState) errorState.style.display = 'block';
            if (errorMessage && message) errorMessage.textContent = message;

            hideAllContainers();
        }

        function hideErrorState() {
            const errorState = document.getElementById('errorState');
            if (errorState) errorState.style.display = 'none';
        }

        // Helper Functions
        function getInitials(name) {
            if (!name || typeof name !== 'string') return '??';
            return name.split(' ')
                .map(word => word.charAt(0))
                .join('')
                .toUpperCase()
                .substring(0, 2);
        }

        function formatNumber(num) {
            const number = parseFloat(num) || 0;
            return number.toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        function formatDate(dateString) {
            if (!dateString || dateString === 'N/A') return 'N/A';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } catch (e) {
                console.error('Error formatting date:', dateString, e);
                return dateString;
            }
        }

        function formatDateTime(dateString) {
            if (!dateString || dateString === 'N/A') return 'N/A';
            try {
                const date = new Date(dateString);
                // Format: "Feb 5, 2026, 6:30 PM"
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } catch (e) {
                console.error('Error formatting date-time:', dateString, e);
                return dateString;
            }
        }

        function getPaymentsThisYear(payments) {
            if (!payments || !Array.isArray(payments)) return 0;
            const currentYear = new Date().getFullYear();
            return payments.filter(payment => {
                try {
                    const paymentDate = new Date(payment.date);
                    return paymentDate.getFullYear() === currentYear;
                } catch (e) {
                    return false;
                }
            }).length;
        }

        function escapeString(str) {
            if (!str) return '';
            return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        function unescapeString(str) {
            if (!str) return '';
            return str.replace(/\\'/g, "'").replace(/&quot;/g, '"');
        }

        // Event listeners for filter changes
        document.getElementById('yearFilter').addEventListener('change', function () {
            loadData();
        });

        document.getElementById('monthFilter').addEventListener('change', function () {
            loadData();
        });

        document.getElementById('perPageFilter').addEventListener('change', function () {
            perPage = parseInt(this.value);
            if (selectedClassId) {
                currentPage = 1;
                renderStudentsTable();
                updatePaginationInfo();
            }
        });
    </script>
@endpush