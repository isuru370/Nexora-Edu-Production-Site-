@extends('layouts.app')

@section('title', 'Create Exam Schedule')
@section('page-title', 'Create New Exam Schedule')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('student_exam.index') }}">Exam Schedule</a></li>
    <li class="breadcrumb-item active">Create New Exam</li>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                {{-- Card Header with Gradient --}}
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white bg-opacity-25 p-2 me-3">
                            <i class="fas fa-calendar-plus fa-2x text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Create New Exam Schedule</h5>
                            <small class="opacity-75">Fill in the details to schedule a new exam</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Alert Container with Auto-dismiss --}}
                    <div id="alert-box"></div>

                    <form id="examForm" class="needs-validation" novalidate>
                        @csrf

                        {{-- Exam Title with Icon --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-heading me-2 text-primary"></i>
                                Exam Title
                            </label>
                            <input type="text" 
                                   id="title" 
                                   class="form-control form-control-lg border-2" 
                                   placeholder="e.g., Final Term Examination 2024"
                                   required 
                                   maxlength="255">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Enter a descriptive title for the exam
                            </div>
                            <div class="invalid-feedback">Please provide an exam title.</div>
                        </div>

                        {{-- Exam Date with Calendar Icon --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                Exam Date
                            </label>
                            <input type="date" 
                                   id="date" 
                                   class="form-control form-control-lg border-2" 
                                   required
                                   min="{{ date('Y-m-d') }}">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Select the date of the exam (future dates only)
                            </div>
                        </div>

                        {{-- Time Range with Duration Preview --}}
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-play me-2 text-success"></i>
                                    Start Time
                                </label>
                                <input type="time" 
                                       id="start_time" 
                                       class="form-control form-control-lg border-2" 
                                       required>
                            </div>

                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                <div class="text-center mt-4">
                                    <span class="badge bg-light text-dark p-3">
                                        <i class="fas fa-long-arrow-alt-right me-1"></i>
                                        to
                                        <i class="fas fa-long-arrow-alt-left ms-1"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-stop me-2 text-danger"></i>
                                    End Time
                                </label>
                                <input type="time" 
                                       id="end_time" 
                                       class="form-control form-control-lg border-2" 
                                       required>
                            </div>

                            {{-- Duration Preview --}}
                            <div class="col-12 mt-2">
                                <div class="alert alert-info py-2 d-none" id="durationPreview">
                                    <i class="fas fa-clock me-2"></i>
                                    <span>Exam duration: </span>
                                    <strong id="durationDisplay">0</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Class Selection with Search --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-users me-2 text-primary"></i>
                                Select Class
                            </label>
                            <div class="position-relative">
                                <select id="class_category_has_student_class_id" 
                                        class="form-select form-select-lg border-2 select2-class" 
                                        required>
                                    <option value="">Loading classes...</option>
                                </select>
                                <div class="spinner-border spinner-border-sm position-absolute end-0 top-50 translate-middle-y me-3 d-none" 
                                     id="classSpinner" 
                                     role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Select the class that will take this exam
                            </div>
                        </div>

                        {{-- Hall Selection with Capacity Display --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-building me-2 text-primary"></i>
                                Select Hall
                            </label>
                            <div class="position-relative">
                                <select id="class_hall_id" 
                                        class="form-select form-select-lg border-2 select2-hall" 
                                        required>
                                    <option value="">Loading halls...</option>
                                </select>
                                <div class="spinner-border spinner-border-sm position-absolute end-0 top-50 translate-middle-y me-3 d-none" 
                                     id="hallSpinner" 
                                     role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Select the hall where the exam will be conducted
                            </div>
                        </div>

                        {{-- Hall Capacity Info --}}
                        <div class="alert alert-warning d-none" id="capacityWarning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="capacityMessage"></span>
                        </div>

                        {{-- Confirmation Checkbox --}}
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmCheck" required>
                                <label class="form-check-label" for="confirmCheck">
                                    I confirm that all the information provided is correct and I have the authority to schedule this exam.
                                </label>
                                <div class="invalid-feedback">
                                    You must confirm before creating the exam.
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                            <a href="{{ route('student_exam.index') }}" class="btn btn-lg btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-lg btn-success px-5" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Create Exam
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Help Card --}}
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body bg-light">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-question-circle fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold">Need Help?</h6>
                            <p class="mb-0 text-muted">
                                Make sure to select the correct class and hall. The exam time should be within the institution's working hours. 
                                You can create multiple exams for different classes on the same date.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
    }
    
    .form-control-lg, .form-select-lg {
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        transform: translateY(-2px);
    }
    
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    .select2-container--default .select2-selection--single {
        height: 48px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 44px;
        padding-left: 15px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
    }
    
    .btn {
        border-radius: 10px;
        font-weight: 500;
    }
    
    .btn-success {
        background: linear-gradient(45deg, #1cc88a 0%, #13855c 100%);
        border: none;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(28, 200, 138, 0.3);
    }
    
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize variables
    const classSelect = document.getElementById("class_category_has_student_class_id");
    const hallSelect = document.getElementById("class_hall_id");
    const alertBox = document.getElementById("alert-box");
    const submitBtn = document.getElementById("submitBtn");
    const form = document.getElementById("examForm");
    
    let classData = [];
    let hallData = [];

    // Initialize Select2
    $(classSelect).select2({
        placeholder: "Search for a class...",
        allowClear: true,
        width: '100%'
    });

    $(hallSelect).select2({
        placeholder: "Search for a hall...",
        allowClear: true,
        width: '100%'
    });

    // Load Class Options with details
    showLoading('class');
    fetch("/api/class-has-category-classes/details")
        .then(res => res.json())
        .then(response => {
            hideLoading('class');
            if (response.status === "success" && response.data) {
                classData = response.data;
                renderClassOptions(response.data);
            } else {
                showError("No classes available");
            }
        })
        .catch(() => {
            hideLoading('class');
            showError("Failed to load classes");
        });

    // Load Hall Options with details
    showLoading('hall');
    fetch("/api/halls/dropdown")
        .then(res => res.json())
        .then(response => {
            hideLoading('hall');
            if (response.status === "success" && response.data) {
                hallData = response.data;
                renderHallOptions(response.data);
            } else {
                showError("No halls available");
            }
        })
        .catch(() => {
            hideLoading('hall');
            showError("Failed to load halls");
        });

    function renderClassOptions(data) {
        $(classSelect).empty().append('<option value="">Select a class</option>');
        
        // Group by grade for better organization
        const groupedData = data.reduce((acc, item) => {
            const grade = item.grade_name || 'Other';
            if (!acc[grade]) acc[grade] = [];
            acc[grade].push(item);
            return acc;
        }, {});

        Object.keys(groupedData).sort().forEach(grade => {
            const group = $(document.createElement('optgroup')).attr('label', `Grade ${grade}`);
            groupedData[grade].forEach(item => {
                const text = `${item.class_name} - ${item.subject_name} (${item.category_name})`;
                const option = new Option(text, item.id, false, false);
                $(option).data('student-count', item.student_count || 0);
                group.append(option);
            });
            $(classSelect).append(group);
        });

        $(classSelect).trigger('change');
    }

    function renderHallOptions(data) {
        $(hallSelect).empty().append('<option value="">Select a hall</option>');
        
        // Group by hall type
        const groupedData = data.reduce((acc, item) => {
            const type = item.hall_type || 'General';
            if (!acc[type]) acc[type] = [];
            acc[type].push(item);
            return acc;
        }, {});

        Object.keys(groupedData).sort().forEach(type => {
            const group = $(document.createElement('optgroup')).attr('label', type);
            groupedData[type].forEach(item => {
                const text = `${item.hall_name} (Capacity: ${item.capacity || 'N/A'})`;
                const option = new Option(text, item.id, false, false);
                $(option).data('capacity', item.capacity || 0);
                group.append(option);
            });
            $(hallSelect).append(group);
        });

        $(hallSelect).trigger('change');
    }

    // Time validation with duration calculation
    function validateTime() {
        const startTime = document.getElementById("start_time").value;
        const endTime = document.getElementById("end_time").value;
        
        if (startTime && endTime) {
            const start = new Date(`2000-01-01T${startTime}`);
            const end = new Date(`2000-01-01T${endTime}`);
            
            if (end <= start) {
                showError("End time must be greater than start time.");
                return false;
            }
            
            // Calculate duration
            const diffMs = end - start;
            const diffMins = Math.floor(diffMs / 60000);
            const hours = Math.floor(diffMins / 60);
            const mins = diffMins % 60;
            
            const durationDisplay = document.getElementById("durationDisplay");
            durationDisplay.textContent = hours > 0 
                ? `${hours} hour${hours > 1 ? 's' : ''} ${mins > 0 ? `and ${mins} minute${mins > 1 ? 's' : ''}` : ''}`
                : `${mins} minute${mins > 1 ? 's' : ''}`;
            
            document.getElementById("durationPreview").classList.remove('d-none');
            return true;
        }
        return false;
    }

    document.getElementById("start_time").addEventListener("change", validateTime);
    document.getElementById("end_time").addEventListener("change", validateTime);

    // Check capacity when both selections are made
    function checkCapacity() {
        const classId = classSelect.value;
        const hallId = hallSelect.value;
        
        if (classId && hallId) {
            const selectedClass = classData.find(c => c.id == classId);
            const selectedHall = hallData.find(h => h.id == hallId);
            
            if (selectedClass && selectedHall) {
                const studentCount = selectedClass.student_count || 0;
                const hallCapacity = selectedHall.capacity || 0;
                
                if (studentCount > hallCapacity) {
                    document.getElementById("capacityMessage").innerHTML = 
                        `⚠️ Warning: The selected hall capacity (${hallCapacity}) is less than the class size (${studentCount}). 
                         Please consider selecting a larger hall.`;
                    document.getElementById("capacityWarning").classList.remove('d-none');
                } else {
                    document.getElementById("capacityWarning").classList.add('d-none');
                }
            }
        }
    }

    classSelect.addEventListener('change', checkCapacity);
    hallSelect.addEventListener('change', checkCapacity);

    // Form Submission with enhanced validation
    form.addEventListener("submit", async function(e) {
        e.preventDefault();
        
        // Basic validation
        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        const title = document.getElementById("title").value.trim();
        const date = document.getElementById("date").value;
        const startTime = document.getElementById("start_time").value;
        const endTime = document.getElementById("end_time").value;

        // Time validation
        if (!validateTime()) {
            return;
        }

        // Date validation
        const selectedDate = new Date(date);
        const today = new Date();
        today.setHours(0,0,0,0);
        
        if (selectedDate < today) {
            showError("Exam date cannot be in the past.");
            return;
        }

        const formData = {
            title,
            date,
            start_time: startTime,
            end_time: endTime,
            class_category_has_student_class_id: classSelect.value,
            class_hall_id: hallSelect.value,
        };

        // Disable submit button with loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating Exam...';

        try {
            const response = await fetch("/api/exams", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok) {
                showSuccess(result.message || "Exam created successfully!");
                
                // Reset form
                form.reset();
                $(classSelect).val(null).trigger('change');
                $(hallSelect).val(null).trigger('change');
                document.getElementById("durationPreview").classList.add('d-none');
                
                // Redirect after delay
                setTimeout(() => {
                    window.location.href = "{{ route('student_exam.index') }}";
                }, 2000);
            } else {
                // Handle validation errors
                if (result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join('<br>');
                    showError(errorMessages);
                } else {
                    showError(result.message || "Failed to create exam.");
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Create Exam';
            }

        } catch (error) {
            showError("Server error. Please try again.");
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Create Exam';
        }
    });

    // Loading indicators
    function showLoading(type) {
        document.getElementById(`${type}Spinner`).classList.remove('d-none');
    }

    function hideLoading(type) {
        document.getElementById(`${type}Spinner`).classList.add('d-none');
    }

    // Enhanced alert functions
    function showSuccess(message) {
        alertBox.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading mb-1">Success!</h6>
                        <p class="mb-0">${message}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
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

    function showError(message) {
        alertBox.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading mb-1">Error!</h6>
                        <p class="mb-0">${message}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        `;
    }

    // Set minimum date for date input
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').setAttribute('min', today);
});
</script>
@endpush