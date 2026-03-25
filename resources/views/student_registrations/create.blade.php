@extends('layouts.app')

@section('title', 'Student Registration')
@section('page-title', 'Register Student to Course')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Student Registration</li>
@endsection

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>There were some problems with your input.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-0 py-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">Single Student Registration</h4>
                    <p class="text-muted mb-0">
                        Enroll one student into a selected course.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Courses
                    </a>
                    <a href="{{ route('student-registrations.bulk-create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-1"></i> Bulk Registration
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body bg-light">
            <form action="{{ route('student-registrations.store') }}" method="POST">
                @csrf

                <div class="row g-4">

                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h5 class="fw-bold mb-0">
                                    <i class="fas fa-user-plus me-2 text-primary"></i>
                                    Registration Details
                                </h5>
                            </div>

                            <div class="card-body px-4 pb-4">
                                <div class="row g-4">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Student Code / QR Code <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            name="student_code"
                                            class="form-control @error('student_code') is-invalid @enderror"
                                            value="{{ old('student_code') }}"
                                            placeholder="Enter custom ID or temporary QR code"
                                            required
                                        >
                                        @error('student_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            Enter the student's custom ID or temporary QR code.
                                        </small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Course <span class="text-danger">*</span>
                                        </label>
                                        <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                            <option value="">Select Course</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}"
                                                    {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                    {{ ($course->course_code ?? 'N/A') . ' - ' . $course->course_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('course_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Registration Date <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            name="registration_date"
                                            class="form-control @error('registration_date') is-invalid @enderror"
                                            value="{{ old('registration_date', now()->format('Y-m-d')) }}"
                                            required
                                        >
                                        @error('registration_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Next Payment Date</label>
                                        <input
                                            type="date"
                                            name="next_payment_date"
                                            class="form-control @error('next_payment_date') is-invalid @enderror"
                                            value="{{ old('next_payment_date') }}"
                                        >
                                        @error('next_payment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold d-block">
                                            Compulsory Payment Paid?
                                        </label>

                                        <div class="form-check form-switch mt-2">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                role="switch"
                                                name="compulsory_paid"
                                                id="compulsory_paid"
                                                value="1"
                                                {{ old('compulsory_paid') ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="compulsory_paid">
                                                Yes, compulsory payment has been paid
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="compulsory_paid_date_wrapper" style="display: none;">
                                        <label class="form-label fw-semibold">Compulsory Paid Date</label>
                                        <input
                                            type="date"
                                            name="compulsory_paid_date"
                                            class="form-control @error('compulsory_paid_date') is-invalid @enderror"
                                            value="{{ old('compulsory_paid_date', now()->format('Y-m-d')) }}"
                                        >
                                        @error('compulsory_paid_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Notes</label>
                                        <input
                                            type="text"
                                            name="notes"
                                            class="form-control @error('notes') is-invalid @enderror"
                                            value="{{ old('notes') }}"
                                            placeholder="Optional note..."
                                        >
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h5 class="fw-bold mb-0">
                                    <i class="fas fa-circle-info me-2 text-success"></i>
                                    Important Info
                                </h5>
                            </div>

                            <div class="card-body px-4 pb-4">
                                <div class="bg-light border rounded-4 p-3 mb-3">
                                    <small class="text-muted d-block mb-1">Registration Type</small>
                                    <div class="fw-semibold">Single Student Enrollment</div>
                                </div>

                                <div class="bg-light border rounded-4 p-3 mb-3">
                                    <small class="text-muted d-block mb-1">Student Lookup</small>
                                    <div class="fw-semibold">Student is found using custom ID or temporary QR code</div>
                                </div>

                                <div class="bg-light border rounded-4 p-3 mb-3">
                                    <small class="text-muted d-block mb-1">Course Fee Snapshot</small>
                                    <div class="fw-semibold">Automatically taken from selected course</div>
                                </div>

                                <div class="bg-light border rounded-4 p-3 mb-3">
                                    <small class="text-muted d-block mb-1">Course Dates</small>
                                    <div class="fw-semibold">Start and end dates are automatically loaded from the selected course</div>
                                </div>

                                <div class="bg-light border rounded-4 p-3 mb-3">
                                    <small class="text-muted d-block mb-1">Compulsory Payment</small>
                                    <div class="fw-semibold">You can mark whether the compulsory payment has already been paid</div>
                                </div>

                                <div class="bg-light border rounded-4 p-3 mb-3">
                                    <small class="text-muted d-block mb-1">Duplicate Protection</small>
                                    <div class="fw-semibold">Same student cannot register twice for the same course</div>
                                </div>

                                <div class="bg-light border rounded-4 p-3">
                                    <small class="text-muted d-block mb-1">Payment Status</small>
                                    <div class="fw-semibold">
                                        Will start as <span class="badge bg-warning">Pending</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Register Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const compulsoryPaidToggle = document.getElementById('compulsory_paid');
    const compulsoryPaidDateWrapper = document.getElementById('compulsory_paid_date_wrapper');

    function toggleCompulsoryPaidDate() {
        if (compulsoryPaidToggle.checked) {
            compulsoryPaidDateWrapper.style.display = 'block';
        } else {
            compulsoryPaidDateWrapper.style.display = 'none';
        }
    }

    compulsoryPaidToggle.addEventListener('change', toggleCompulsoryPaidDate);

    toggleCompulsoryPaidDate();
});
</script>
@endpush