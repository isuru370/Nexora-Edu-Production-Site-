@extends('layouts.app')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
    <li class="breadcrumb-item active">Edit Course</li>
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
                    <h4 class="mb-1 fw-bold text-dark">Edit Course</h4>
                    <p class="text-muted mb-0">
                        Update the course details below.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-1"></i> View
                    </a>
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body bg-light">
            <form action="{{ route('courses.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Course Name <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="course_name"
                            class="form-control @error('course_name') is-invalid @enderror"
                            value="{{ old('course_name', $course->course_name) }}"
                            required
                        >
                        @error('course_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Teacher Percentage <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            name="teacher_percentage"
                            class="form-control @error('teacher_percentage') is-invalid @enderror"
                            value="{{ old('teacher_percentage', $course->teacher_percentage) }}"
                            required
                        >
                        @error('teacher_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Total Fee <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="total_fee"
                            class="form-control @error('total_fee') is-invalid @enderror"
                            value="{{ old('total_fee', $course->total_fee) }}"
                            required
                        >
                        @error('total_fee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Compulsory Payment</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="compulsory_payment"
                            class="form-control @error('compulsory_payment') is-invalid @enderror"
                            value="{{ old('compulsory_payment', $course->compulsory_payment) }}"
                        >
                        @error('compulsory_payment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Duration (Months) <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            min="1"
                            name="duration_months"
                            class="form-control @error('duration_months') is-invalid @enderror"
                            value="{{ old('duration_months', $course->duration_months) }}"
                            required
                        >
                        @error('duration_months')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teacher</label>
                        <select
                            name="teacher_id"
                            id="teacher_id"
                            class="form-select @error('teacher_id') is-invalid @enderror"
                        >
                            <option value="">Loading teachers...</option>
                        </select>

                        @error('teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted" id="teacher-load-message"></small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Department</label>
                        <input
                            type="text"
                            name="department"
                            class="form-control @error('department') is-invalid @enderror"
                            value="{{ old('department', $course->department) }}"
                        >
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Max Students</label>
                        <input
                            type="number"
                            min="1"
                            name="max_students"
                            class="form-control @error('max_students') is-invalid @enderror"
                            value="{{ old('max_students', $course->max_students) }}"
                        >
                        @error('max_students')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input
                            type="date"
                            name="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date', $course->start_date ? $course->start_date->format('Y-m-d') : '') }}"
                        >
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">End Date</label>
                        <input
                            type="date"
                            name="end_date"
                            class="form-control @error('end_date') is-invalid @enderror"
                            value="{{ old('end_date', $course->end_date ? $course->end_date->format('Y-m-d') : '') }}"
                        >
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', $course->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $course->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea
                            name="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                        >{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Course
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
    const teacherSelect = document.getElementById('teacher_id');
    const teacherMessage = document.getElementById('teacher-load-message');
    const selectedTeacherId = @json(old('teacher_id', $course->teacher_id));

    async function loadTeachers() {
        teacherSelect.innerHTML = '<option value="">Loading teachers...</option>';
        teacherMessage.textContent = '';

        try {
            const response = await fetch('/api/teachers/dropdown', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load teachers.');
            }

            const result = await response.json();

            teacherSelect.innerHTML = '<option value="">Select Teacher</option>';

            if (!result.data || !Array.isArray(result.data) || result.data.length === 0) {
                teacherMessage.textContent = 'No teachers found.';
                return;
            }

            result.data.forEach(function (teacher) {
                const option = document.createElement('option');
                option.value = teacher.id;
                option.textContent = `${teacher.custom_id ?? ''} - ${teacher.fname ?? ''} ${teacher.lname ?? ''}`.trim();

                if (selectedTeacherId && String(selectedTeacherId) === String(teacher.id)) {
                    option.selected = true;
                }

                teacherSelect.appendChild(option);
            });
        } catch (error) {
            teacherSelect.innerHTML = '<option value="">Unable to load teachers</option>';
            teacherMessage.textContent = error.message;
            console.error('Teacher dropdown error:', error);
        }
    }

    loadTeachers();
});
</script>
@endpush