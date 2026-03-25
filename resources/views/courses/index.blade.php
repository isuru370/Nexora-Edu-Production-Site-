@extends('layouts.app')

@section('title', 'Courses')
@section('page-title', 'Courses')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Courses</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white">
            <h5 class="mb-0">Course List</h5>

            <a href="{{ route('courses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Course
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="GET" action="{{ route('courses.index') }}" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Code, course name, department, teacher..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="department" class="form-label">Department</label>
                    <input
                        type="text"
                        name="department"
                        id="department"
                        class="form-control"
                        placeholder="Department"
                        value="{{ request('department') }}"
                    >
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>

                    <a href="{{ route('courses.index') }}" class="btn btn-secondary w-100">
                        Reset
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Teacher</th>
                            <th>Department</th>
                            <th>Total Fee</th>
                            <th>Compulsory Payment</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Students</th>
                            <th width="250">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $index => $course)
                            @php
                                if ($course->status == 'active') {
                                    $badgeClass = 'success';
                                } elseif ($course->status == 'inactive') {
                                    $badgeClass = 'warning';
                                } elseif ($course->status == 'archived') {
                                    $badgeClass = 'secondary';
                                } else {
                                    $badgeClass = 'dark';
                                }

                                $rowNumber = method_exists($courses, 'firstItem') && $courses->firstItem()
                                    ? $courses->firstItem() + $index
                                    : $index + 1;
                            @endphp

                            <tr>
                                <td>{{ $rowNumber }}</td>
                                <td>{{ $course->course_code ?? '-' }}</td>
                                <td>
                                    <strong>{{ $course->course_name }}</strong>
                                    @if(!empty($course->description))
                                        <br>
                                        <small class="text-muted">
                                            {{ \Illuminate\Support\Str::limit($course->description, 60) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($course->teacher)
                                        {{ trim(($course->teacher->fname ?? '') . ' ' . ($course->teacher->lname ?? '')) }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>{{ $course->department ?: '-' }}</td>
                                <td>{{ number_format((float) $course->total_fee, 2) }}</td>
                                <td>{{ number_format((float) $course->compulsory_payment, 2) }}</td>
                                <td>{{ (int) $course->duration_months }} month(s)</td>
                                <td>
                                    <span class="badge bg-{{ $badgeClass }}">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </td>
                                <td>{{ $course->registrations_count ?? 0 }}</td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-info">
                                            View
                                        </a>

                                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        @if($course->status !== 'inactive')
                                            <form
                                                action="{{ route('courses.changeStatus', $course->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Do you want to deactivate this course?')"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="inactive">

                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Deactivate
                                                </button>
                                            </form>
                                        @endif

                                        @if($course->status === 'inactive')
                                            <form
                                                action="{{ route('courses.changeStatus', $course->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Do you want to activate this course?')"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="active">

                                                <button type="submit" class="btn btn-sm btn-success">
                                                    Activate
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">No courses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($courses, 'links'))
                <div class="mt-3">
                    {{ $courses->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection