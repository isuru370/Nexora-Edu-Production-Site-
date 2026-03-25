@extends('layouts.app')

@section('title', 'Create Course')
@section('page-title', 'Create New Course')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
    <li class="breadcrumb-item active">Create Course</li>
@endsection

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>There were some problems with your input.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Course Details</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Course Name *</label>
                        <input type="text" name="course_name"
                            class="form-control @error('course_name') is-invalid @enderror"
                            value="{{ old('course_name') }}" required>

                        @error('course_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Teacher Percentage *</label>
                        <input type="number" step="0.01" min="0" max="100" name="teacher_percentage"
                            class="form-control @error('teacher_percentage') is-invalid @enderror"
                            value="{{ old('teacher_percentage', 100) }}" required>

                        @error('teacher_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Total Fee *</label>
                        <input type="number" step="0.01" name="total_fee"
                            class="form-control @error('total_fee') is-invalid @enderror"
                            value="{{ old('total_fee') }}" required>

                        @error('total_fee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Compulsory Payment</label>
                        <input type="number" step="0.01" name="compulsory_payment"
                            class="form-control @error('compulsory_payment') is-invalid @enderror"
                            value="{{ old('compulsory_payment') }}">

                        @error('compulsory_payment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Duration (Months) *</label>
                        <input type="number" name="duration_months"
                            class="form-control @error('duration_months') is-invalid @enderror"
                            value="{{ old('duration_months') }}" required>

                        @error('duration_months')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teacher</label>
                        <select name="teacher_id" id="teacher_id"
                            class="form-select @error('teacher_id') is-invalid @enderror">
                            <option value="">Loading teachers...</option>
                        </select>

                        @error('teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted" id="teacher-load-message"></small>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" name="department"
                            class="form-control @error('department') is-invalid @enderror"
                            value="{{ old('department') }}">

                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Max Students</label>
                        <input type="number" name="max_students"
                            class="form-control @error('max_students') is-invalid @enderror"
                            value="{{ old('max_students') }}">

                        @error('max_students')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date') }}">

                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date"
                            class="form-control @error('end_date') is-invalid @enderror"
                            value="{{ old('end_date') }}">

                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary me-2">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Save Course
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
    const selectedTeacherId = @json(old('teacher_id'));

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
                option.textContent = `${teacher.custom_id} - ${teacher.fname} ${teacher.lname ?? ''}`.trim();

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