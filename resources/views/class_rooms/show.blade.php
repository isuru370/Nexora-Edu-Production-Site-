@extends('layouts.app')

@section('title', 'Class Details')
@section('page-title', 'Class Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('class_rooms.index') }}">Class Rooms</a></li>
    <li class="breadcrumb-item active">Class Details</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Class Details
                            </h5>
                            <p class="text-muted mb-0">Complete information about this class</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="loadClassDetails()" title="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <a href="{{ route('class_rooms.edit', $id) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Loading State -->
                    <div id="loadingState" class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading class information...</p>
                    </div>

                    <!-- Error State -->
                    <div id="errorState" class="alert alert-danger d-none">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="errorMessage"></span>
                    </div>

                    <!-- Class Details Content -->
                    <div id="classDetailsContent" class="d-none">
                        <div class="row">
                            <!-- Left Column - Main Information -->
                            <div class="col-lg-8">
                                <!-- Class Header Card -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div id="classTypeIcon" class="class-type-icon me-3"></div>
                                                    <div>
                                                        <h2 class="mb-1 fw-bold" id="className">Loading...</h2>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span id="classCode" class="badge bg-primary bg-opacity-10 text-primary border border-primary">#ID</span>
                                                            <span id="classTypeBadge" class="badge">Type</span>
                                                            <span id="teacherPercentageBadge" class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                                                <i class="fas fa-percentage me-1"></i> -
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="info-item">
                                                            <div class="info-label">
                                                                <i class="fas fa-calendar-plus me-2 text-muted"></i>
                                                                Created Date
                                                            </div>
                                                            <div class="info-value" id="createdDate">-</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info-item">
                                                            <div class="info-label">
                                                                <i class="fas fa-calendar-check me-2 text-muted"></i>
                                                                Last Updated
                                                            </div>
                                                            <div class="info-value" id="updatedDate">-</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Details Cards Grid -->
                                <div class="row g-3">
                                    <!-- Teacher Card -->
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon-container bg-success bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-user-tie fa-lg text-success"></i>
                                                    </div>
                                                    <h6 class="card-title mb-0 ms-3">Teacher Information</h6>
                                                </div>
                                                <div class="teacher-info">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-user me-2 text-muted"></i>
                                                        <div>
                                                            <div class="fw-bold" id="teacherName">-</div>
                                                            <small class="text-muted" id="teacherId">ID: -</small>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-envelope me-2 text-muted"></i>
                                                        <div class="text-truncate" id="teacherEmail">-</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subject Card -->
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon-container bg-info bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-book fa-lg text-info"></i>
                                                    </div>
                                                    <h6 class="card-title mb-0 ms-3">Subject Information</h6>
                                                </div>
                                                <div class="subject-info">
                                                    <div class="mb-3">
                                                        <div class="fw-bold" id="subjectName">-</div>
                                                    </div>
                                                    <div class="subject-code">
                                                        <i class="fas fa-hashtag me-2 text-muted"></i>
                                                        <span id="subjectId">ID: -</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grade Card -->
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon-container bg-warning bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-graduation-cap fa-lg text-warning"></i>
                                                    </div>
                                                    <h6 class="card-title mb-0 ms-3">Grade Information</h6>
                                                </div>
                                                <div class="grade-info">
                                                    <div class="mb-3">
                                                        <div class="fw-bold" id="gradeName">-</div>
                                                    </div>
                                                    <div class="grade-code">
                                                        <i class="fas fa-hashtag me-2 text-muted"></i>
                                                        <span id="gradeId">ID: -</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Teacher Percentage Card -->
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon-container bg-danger bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-percentage fa-lg text-danger"></i>
                                                    </div>
                                                    <h6 class="card-title mb-0 ms-3">Teacher Percentage</h6>
                                                </div>
                                                <div class="percentage-info">
                                                    <div class="mb-3 text-center">
                                                        <div class="percentage-display">
                                                            <div class="percentage-value fw-bold display-4" id="teacherPercentage">-</div>
                                                            <div class="percentage-label text-muted">of class fees</div>
                                                        </div>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div id="percentageProgressBar" class="progress-bar bg-danger" role="progressbar" 
                                                             style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <div class="text-center mt-2">
                                                        <small class="text-muted" id="percentageDescription">Loading...</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status Card -->
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="icon-container bg-purple bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-chart-line fa-lg text-purple"></i>
                                                    </div>
                                                    <h6 class="card-title mb-0 ms-3">Status Information</h6>
                                                </div>
                                                <div class="status-info">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <div class="status-item text-center p-2 bg-light rounded">
                                                                <div class="status-label text-muted small mb-1">Active Status</div>
                                                                <div id="activeStatus" class="status-badge">-</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="status-item text-center p-2 bg-light rounded">
                                                                <div class="status-label text-muted small mb-1">Ongoing Status</div>
                                                                <div id="ongoingStatus" class="status-badge">-</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Quick Actions & Statistics -->
                            <div class="col-lg-4">
                                <!-- Quick Actions -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                                        </h6>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-primary btn-hover" onclick="loadClassDetails()">
                                                <i class="fas fa-sync-alt me-2"></i>Refresh Data
                                            </button>
                                            <a href="{{ route('class_rooms.index') }}" class="btn btn-outline-secondary btn-hover">
                                                <i class="fas fa-arrow-left me-2"></i>Back to Classes
                                            </a>
                                            <a href="{{ route('class_rooms.edit', $id) }}" class="btn btn-warning btn-hover">
                                                <i class="fas fa-edit me-2"></i>Edit Class
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Class Statistics -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h6 class="card-title mb-3">
                                            <i class="fas fa-chart-pie me-2 text-info"></i>Class Statistics
                                        </h6>
                                        <div class="stats-grid">
                                            <div class="stat-card text-center p-3 bg-primary bg-opacity-10 rounded">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-chalkboard-teacher fa-2x text-primary"></i>
                                                </div>
                                                <div class="stat-label text-muted small">Class Type</div>
                                                <div class="stat-value fw-bold" id="statClassType">-</div>
                                            </div>
                                            <div class="stat-card text-center p-3 bg-success bg-opacity-10 rounded">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-percentage fa-2x text-success"></i>
                                                </div>
                                                <div class="stat-label text-muted small">Teacher %</div>
                                                <div class="stat-value fw-bold" id="statPercentage">-</div>
                                            </div>
                                            <div class="stat-card text-center p-3 bg-warning bg-opacity-10 rounded">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                                                </div>
                                                <div class="stat-label text-muted small">Created</div>
                                                <div class="stat-value fw-bold" id="statCreated">-</div>
                                            </div>
                                            <div class="stat-card text-center p-3 bg-purple bg-opacity-10 rounded">
                                                <div class="stat-icon mb-2">
                                                    <i class="fas fa-history fa-2x text-purple"></i>
                                                </div>
                                                <div class="stat-label text-muted small">Updated</div>
                                                <div class="stat-value fw-bold" id="statUpdated">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --purple-color: #6f42c1;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.25);
        }

        .class-type-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .class-type-online {
            background: linear-gradient(135deg, #36b9cc, #258ea6);
            color: white;
        }

        .class-type-offline {
            background: linear-gradient(135deg, #f6c23e, #dda20a);
            color: white;
        }

        .class-type-default {
            background: linear-gradient(135deg, #858796, #5a5c69);
            color: white;
        }

        .info-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e3e6f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.875rem;
            color: #858796;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #5a5c69;
        }

        .icon-container {
            transition: all 0.3s ease;
        }

        .hover-card:hover .icon-container {
            transform: scale(1.1);
        }

        .btn-hover {
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }

        .badge-active {
            background-color: rgba(28, 200, 138, 0.1);
            color: #1cc88a;
            border: 1px solid rgba(28, 200, 138, 0.2);
        }

        .badge-inactive {
            background-color: rgba(231, 74, 59, 0.1);
            color: #e74a3b;
            border: 1px solid rgba(231, 74, 59, 0.2);
        }

        .badge-ongoing {
            background-color: rgba(54, 185, 204, 0.1);
            color: #36b9cc;
            border: 1px solid rgba(54, 185, 204, 0.2);
        }

        .badge-not-ongoing {
            background-color: rgba(133, 135, 150, 0.1);
            color: #858796;
            border: 1px solid rgba(133, 135, 150, 0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: scale(1.05);
            background: white !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .stat-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 1.25rem;
            color: #5a5c69;
        }

        .bg-purple {
            background-color: var(--purple-color) !important;
        }

        .text-purple {
            color: var(--purple-color) !important;
        }

        .bg-opacity-10 {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .border-bottom {
            border-bottom-color: #e3e6f0 !important;
        }

        /* Percentage Display Styles */
        .percentage-display {
            padding: 1rem;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(231, 74, 59, 0.1), rgba(231, 74, 59, 0.05));
            margin-bottom: 1rem;
        }

        .percentage-value {
            color: #e74a3b;
            font-weight: 700;
            line-height: 1;
            font-size: 3rem;
        }

        .percentage-label {
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .progress {
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 4px;
            transition: width 1s ease-in-out;
        }

        /* Progress bar color variants */
        .bg-danger {
            background: linear-gradient(135deg, #e74a3b, #d62c1a) !important;
        }

        .bg-success {
            background: linear-gradient(135deg, #1cc88a, #169b6b) !important;
        }

        .bg-info {
            background: linear-gradient(135deg, #36b9cc, #258ea6) !important;
        }

        .bg-warning {
            background: linear-gradient(135deg, #f6c23e, #dda20a) !important;
        }

        .bg-primary {
            background: linear-gradient(135deg, #4e73df, #2e59d9) !important;
        }

        /* Teacher percentage badge */
        #teacherPercentageBadge {
            transition: all 0.3s ease;
        }

        /* Text colors for description */
        .text-success {
            color: #1cc88a !important;
        }

        .text-info {
            color: #36b9cc !important;
        }

        .text-warning {
            color: #f6c23e !important;
        }

        .text-danger {
            color: #e74a3b !important;
        }

        /* Display utilities */
        .display-4 {
            font-size: 3rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const classId = {{ $id }};

        document.addEventListener('DOMContentLoaded', function() {
            loadClassDetails();
        });

        // Load Class Details
        function loadClassDetails() {
            showLoadingState();

            fetch(`/api/class-rooms/${classId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: Failed to load class details`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success' && data.data) {
                        renderClassDetails(data.data);
                        showContentState();
                    } else {
                        throw new Error(data.message || 'Invalid response format');
                    }
                })
                .catch(error => {
                    console.error('Error loading class details:', error);
                    showErrorState(`Unable to load class details: ${error.message}`);
                });
        }

        // Render Class Details
        function renderClassDetails(classData) {
            // Class Type Configuration
            const classTypeConfig = {
                'online': {
                    icon: 'fa-laptop',
                    badgeClass: 'class-type-online',
                    badgeText: 'Online',
                    badgeColor: 'bg-info',
                    textColor: 'text-white'
                },
                'offline': {
                    icon: 'fa-school',
                    badgeClass: 'class-type-offline',
                    badgeText: 'Offline',
                    badgeColor: 'bg-warning',
                    textColor: 'text-white'
                }
            };

            const classType = classData.class_type?.toLowerCase() || 'default';
            const config = classTypeConfig[classType] || {
                icon: 'fa-chalkboard',
                badgeClass: 'class-type-default',
                badgeText: classType.charAt(0).toUpperCase() + classType.slice(1),
                badgeColor: 'bg-secondary',
                textColor: 'text-secondary'
            };

            // Format dates
            const createdDate = formatDate(classData.created_at);
            const updatedDate = formatDate(classData.updated_at);
            const createdDateShort = formatDateShort(classData.created_at);
            const updatedDateShort = formatDateShort(classData.updated_at);

            // Update Class Header
            const classTypeIcon = document.getElementById('classTypeIcon');
            if (classTypeIcon) {
                classTypeIcon.className = `class-type-icon ${config.badgeClass}`;
                classTypeIcon.innerHTML = `<i class="fas ${config.icon}"></i>`;
            }

            document.getElementById('className').textContent = classData.class_name || 'Unnamed Class';
            document.getElementById('classCode').textContent = `#${classData.id}`;
            
            const classTypeBadge = document.getElementById('classTypeBadge');
            if (classTypeBadge) {
                classTypeBadge.className = `badge ${config.badgeColor} ${config.textColor}`;
                classTypeBadge.textContent = config.badgeText;
            }

            // Update Dates
            document.getElementById('createdDate').textContent = createdDate;
            document.getElementById('updatedDate').textContent = updatedDate;
            document.getElementById('statCreated').textContent = createdDateShort;
            document.getElementById('statUpdated').textContent = updatedDateShort;

            // Update Teacher Info
            if (classData.teacher) {
                document.getElementById('teacherName').textContent = 
                    `${classData.teacher.fname} ${classData.teacher.lname}`;
                document.getElementById('teacherId').textContent = 
                    `ID: ${classData.teacher.custom_id || classData.teacher.id}`;
                document.getElementById('teacherEmail').textContent = 
                    classData.teacher.email || 'No email';
            }

            // Update Subject Info
            if (classData.subject) {
                document.getElementById('subjectName').textContent = classData.subject.subject_name;
                document.getElementById('subjectId').textContent = `ID: ${classData.subject.id}`;
            }

            // Update Grade Info
            if (classData.grade) {
                document.getElementById('gradeName').textContent = `Grade ${classData.grade.grade_name}`;
                document.getElementById('gradeId').textContent = `ID: ${classData.grade.id}`;
            }

            // Update Teacher Percentage
            updateTeacherPercentage(classData);

            // Update Status Badges
            const activeStatus = document.getElementById('activeStatus');
            if (activeStatus) {
                activeStatus.className = classData.is_active === 1 ? 
                    'status-badge badge-active' : 'status-badge badge-inactive';
                activeStatus.textContent = classData.is_active === 1 ? 'Active' : 'Inactive';
            }

            const ongoingStatus = document.getElementById('ongoingStatus');
            if (ongoingStatus) {
                ongoingStatus.className = classData.is_ongoing === 1 ? 
                    'status-badge badge-ongoing' : 'status-badge badge-not-ongoing';
                ongoingStatus.textContent = classData.is_ongoing === 1 ? 'Ongoing' : 'Not Ongoing';
            }

            // Update Statistics
            document.getElementById('statClassType').textContent = config.badgeText;
        }

        // Update Teacher Percentage
        function updateTeacherPercentage(classData) {
            const percentage = classData.teacher_percentage;
            
            if (percentage === undefined || percentage === null) {
                console.warn('Teacher percentage not available');
                return;
            }
            
            const percentageValue = parseFloat(percentage);
            
            // Update percentage display
            const percentageElement = document.getElementById('teacherPercentage');
            if (percentageElement) {
                percentageElement.textContent = `${percentageValue}%`;
            }

            // Update statistics card
            const statPercentage = document.getElementById('statPercentage');
            if (statPercentage) {
                statPercentage.textContent = `${percentageValue}%`;
            }

            // Update header badge
            const percentageBadge = document.getElementById('teacherPercentageBadge');
            if (percentageBadge) {
                percentageBadge.innerHTML = `<i class="fas fa-percentage me-1"></i> ${percentageValue}%`;
                
                // Update badge color based on percentage
                if (percentageValue >= 80) {
                    percentageBadge.className = 'badge bg-success bg-opacity-10 text-success border border-success';
                } else if (percentageValue >= 60) {
                    percentageBadge.className = 'badge bg-info bg-opacity-10 text-info border border-info';
                } else if (percentageValue >= 40) {
                    percentageBadge.className = 'badge bg-warning bg-opacity-10 text-warning border border-warning';
                } else {
                    percentageBadge.className = 'badge bg-danger bg-opacity-10 text-danger border border-danger';
                }
            }
            
            // Update progress bar
            const progressBar = document.getElementById('percentageProgressBar');
            if (progressBar) {
                // Animate the progress bar
                setTimeout(() => {
                    progressBar.style.width = `${percentageValue}%`;
                    progressBar.setAttribute('aria-valuenow', percentageValue);
                    
                    // Color coding based on percentage
                    if (percentageValue >= 80) {
                        progressBar.className = 'progress-bar bg-success';
                    } else if (percentageValue >= 60) {
                        progressBar.className = 'progress-bar bg-info';
                    } else if (percentageValue >= 40) {
                        progressBar.className = 'progress-bar bg-warning';
                    } else {
                        progressBar.className = 'progress-bar bg-danger';
                    }
                }, 300);
            }
            
            // Update description
            updatePercentageDescription(percentageValue);
        }

        // Helper function for percentage description
        function updatePercentageDescription(percentage) {
            const descriptionElement = document.getElementById('percentageDescription');
            if (!descriptionElement) return;
            
            if (percentage >= 80) {
                descriptionElement.textContent = 'Premium commission rate';
                descriptionElement.className = 'text-success small';
            } else if (percentage >= 60) {
                descriptionElement.textContent = 'High commission rate';
                descriptionElement.className = 'text-info small';
            } else if (percentage >= 40) {
                descriptionElement.textContent = 'Standard commission rate';
                descriptionElement.className = 'text-warning small';
            } else if (percentage >= 20) {
                descriptionElement.textContent = 'Basic commission rate';
                descriptionElement.className = 'text-danger small';
            } else {
                descriptionElement.textContent = 'Entry commission rate';
                descriptionElement.className = 'text-muted small';
            }
        }

        // Format date to readable format
        function formatDate(dateString) {
            if (!dateString) return 'Not available';
            
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return dateString;
                
                return date.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (error) {
                console.error('Error formatting date:', error);
                return dateString;
            }
        }

        // Format date to short format
        function formatDateShort(dateString) {
            if (!dateString) return '-';
            
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return dateString;
                
                return date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
            } catch (error) {
                console.error('Error formatting date:', error);
                return dateString;
            }
        }

        // State Management Functions
        function showLoadingState() {
            document.getElementById('loadingState').classList.remove('d-none');
            document.getElementById('errorState').classList.add('d-none');
            document.getElementById('classDetailsContent').classList.add('d-none');
        }

        function showContentState() {
            document.getElementById('loadingState').classList.add('d-none');
            document.getElementById('errorState').classList.add('d-none');
            document.getElementById('classDetailsContent').classList.remove('d-none');
        }

        function showErrorState(message) {
            document.getElementById('loadingState').classList.add('d-none');
            document.getElementById('errorState').classList.remove('d-none');
            document.getElementById('classDetailsContent').classList.add('d-none');
            document.getElementById('errorMessage').textContent = message;
        }

        // Alert function
        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 
                    type === 'danger' ? 'exclamation-triangle' : 
                    type === 'warning' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            const container = document.querySelector('.card-body');
            if (container) {
                container.insertBefore(alertDiv, container.firstChild);
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        }
    </script>
@endpush