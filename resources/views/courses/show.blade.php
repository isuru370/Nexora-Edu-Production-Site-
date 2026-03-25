@extends('layouts.app')

@section('title', 'Course Details')
@section('page-title', 'Course Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
    <li class="breadcrumb-item active">View Course</li>
@endsection

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        if ($course->status == 'active') {
            $badgeClass = 'success';
            $statusIcon = 'fa-circle-check';
        } elseif ($course->status == 'inactive') {
            $badgeClass = 'warning';
            $statusIcon = 'fa-clock';
        } elseif ($course->status == 'archived') {
            $badgeClass = 'secondary';
            $statusIcon = 'fa-box-archive';
        } else {
            $badgeClass = 'dark';
            $statusIcon = 'fa-circle';
        }
    @endphp

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-0 py-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                        <h3 class="mb-0 fw-bold text-dark">{{ $course->course_name }}</h3>
                        <span class="badge bg-{{ $badgeClass }} px-3 py-2">
                            <i class="fas {{ $statusIcon }} me-1"></i>{{ ucfirst($course->status) }}
                        </span>
                    </div>

                    <div class="text-muted">
                        <i class="fas fa-hashtag me-1"></i>
                        Course Code: <strong>{{ $course->course_code ?? '-' }}</strong>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning shadow-sm">
                        <i class="fas fa-pen me-1"></i> Edit
                    </a>

                    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body bg-light">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 48px; height: 48px;">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Teacher</h6>
                                    <small class="text-muted">Assigned instructor</small>
                                </div>
                            </div>

                            <div class="fs-5 fw-semibold text-dark">
                                @if($course->teacher)
                                    {{ trim(($course->teacher->fname ?? '') . ' ' . ($course->teacher->lname ?? '')) }}
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 48px; height: 48px;">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Total Fee</h6>
                                    <small class="text-muted">Course full amount</small>
                                </div>
                            </div>

                            <div class="fs-4 fw-bold text-dark">
                                {{ number_format((float) $course->total_fee, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 48px; height: 48px;">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Duration</h6>
                                    <small class="text-muted">Learning period</small>
                                </div>
                            </div>

                            <div class="fs-4 fw-bold text-dark">
                                {{ (int) $course->duration_months }} Month(s)
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-circle-info me-2 text-primary"></i>
                                Course Information
                            </h5>
                        </div>

                        <div class="card-body px-4 pb-4">
                            <div class="row g-4">

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 bg-white h-100">
                                        <small class="text-muted d-block mb-1">Department</small>
                                        <div class="fw-semibold fs-6">{{ $course->department ?: '-' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 bg-white h-100">
                                        <small class="text-muted d-block mb-1">Max Students</small>
                                        <div class="fw-semibold fs-6">{{ $course->max_students ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 bg-white h-100">
                                        <small class="text-muted d-block mb-1">Compulsory Payment</small>
                                        <div class="fw-semibold fs-6">
                                            {{ number_format((float) $course->compulsory_payment, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 bg-white h-100">
                                        <small class="text-muted d-block mb-1">Teacher Percentage</small>
                                        <div class="fw-semibold fs-6">
                                            {{ number_format((float) $course->teacher_percentage, 2) }}%
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 bg-white h-100">
                                        <small class="text-muted d-block mb-1">Institute Percentage</small>
                                        <div class="fw-semibold fs-6">
                                            {{ number_format((float) $course->institute_percentage, 2) }}%
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 bg-white h-100">
                                        <small class="text-muted d-block mb-1">Course Status</small>
                                        <div>
                                            <span class="badge bg-{{ $badgeClass }} px-3 py-2">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 bg-white h-100">
                                        <small class="text-muted d-block mb-1">Start Date</small>
                                        <div class="fw-semibold fs-6">
                                            {{ $course->start_date ? $course->start_date->format('Y-m-d') : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 bg-white h-100">
                                        <small class="text-muted d-block mb-1">End Date</small>
                                        <div class="fw-semibold fs-6">
                                            {{ $course->end_date ? $course->end_date->format('Y-m-d') : '-' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-align-left me-2 text-success"></i>
                                Description
                            </h5>
                        </div>

                        <div class="card-body px-4 pb-4">
                            <div class="bg-light border rounded-4 p-4 text-dark" style="min-height: 220px;">
                                {{ $course->description ?: 'No description available.' }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection