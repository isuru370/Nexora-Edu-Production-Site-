@extends('layouts.app')

@section('title', 'Student Marks Management')
@section('page-title', 'Student Marks Entry')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('student_exam.index') }}">Exam Schedule</a></li>
    <li class="breadcrumb-item active">Student Marks Entry</li>
@endsection

@section('content')
<div class="container-fluid px-4">
    {{-- Exam Info Card --}}
    <div class="card shadow-sm mb-4" id="examInfoCard">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-3">
                        <i class="fas fa-clipboard-list me-2 text-primary"></i>
                        Exam Information
                    </h5>
                    <div class="row" id="examDetails">
                        <div class="col-sm-6 col-md-3 mb-2">
                            <small class="text-muted d-block">Exam Title</small>
                            <span class="fw-bold" id="examTitle">Loading...</span>
                        </div>
                        <div class="col-sm-6 col-md-2 mb-2">
                            <small class="text-muted d-block">Exam Date</small>
                            <span class="fw-bold" id="examDate">Loading...</span>
                        </div>
                        <div class="col-sm-6 col-md-2 mb-2">
                            <small class="text-muted d-block">Duration</small>
                            <span class="fw-bold" id="examDuration">Loading...</span>
                        </div>
                        <div class="col-sm-6 col-md-2 mb-2">
                            <small class="text-muted d-block">Hall</small>
                            <span class="fw-bold" id="examHall">Loading...</span>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-2">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-success" id="examStatus">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Students:</span>
                            <span class="fw-bold" id="totalStudents">0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Marks Entered:</span>
                            <span class="fw-bold" id="marksEntered">0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Completion:</span>
                            <span class="fw-bold" id="completionPercentage">0%</span>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-success" id="completionBar" style="width: 0%"></div>
                        </div>
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
                        <i class="fas fa-users me-2 text-primary"></i>
                        Student Marks Entry
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2 justify-content-end">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   id="student-search" 
                                   class="form-control border-start-0" 
                                   placeholder="Search by ID, name, or mobile..."
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <button class="btn btn-outline-secondary" id="showStats">
                            <i class="fas fa-chart-bar"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            {{-- Alert Container --}}
            <div id="alert-box"></div>

            {{-- Bulk Actions Bar --}}
            <div class="bg-light p-2 mb-3 rounded d-flex gap-2 align-items-center">
                <span class="text-muted me-2">Bulk Actions:</span>
                <button type="button" class="btn btn-sm btn-outline-primary" id="setAllPass">
                    <i class="fas fa-check-circle me-1"></i>Set All Pass (50)
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning" id="setAllZero">
                    <i class="fas fa-undo me-1"></i>Set All Zero
                </button>
                <button type="button" class="btn btn-sm btn-outline-success" id="setAllFull">
                    <i class="fas fa-star me-1"></i>Set All Full Marks
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" id="clearAll">
                    <i class="fas fa-eraser me-1"></i>Clear All
                </button>
            </div>

            {{-- Validation Summary --}}
            <div id="validationSummary" class="d-none mb-3"></div>

            {{-- Students Table --}}
            <form id="bulk-marks-form">
                <input type="hidden" id="exam_id" value="{{ $exam_id }}">
                <input type="hidden" id="user_id" value="{{ auth()->id() }}">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="students-table">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Marks <small class="text-muted">(0-100)</small></th>
                                <th width="80">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Loading students...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Form Actions --}}
                <div class="row mt-4">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            <span id="selectedCount">0</span> student(s) selected
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('student_exam.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>Back to Exams
                        </a>
                        <button type="button" class="btn btn-primary" id="validateSubmit">
                            <i class="fas fa-check-circle me-1"></i>Validate Marks
                        </button>
                        <button type="submit" class="btn btn-success" id="submitMarks">
                            <i class="fas fa-save me-1"></i>Submit Marks
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Statistics Modal --}}
<div class="modal fade" id="statsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-bar me-2"></i>
                    Marks Statistics
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <canvas id="statsChart" style="height: 300px;"></canvas>
                
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <span class="text-muted d-block">Average</span>
                            <span class="h3" id="avgMarks">0</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3 text-center">
                            <span class="text-muted d-block">Highest</span>
                            <span class="h3" id="highestMarks">0</span>
                        </div>
                    </div>
                    <div class="col-6 mt-3">
                        <div class="border rounded p-3 text-center">
                            <span class="text-muted d-block">Lowest</span>
                            <span class="h3" id="lowestMarks">0</span>
                        </div>
                    </div>
                    <div class="col-6 mt-3">
                        <div class="border rounded p-3 text-center">
                            <span class="text-muted d-block">Pass Rate</span>
                            <span class="h3" id="passRate">0%</span>
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
    .table td {
        vertical-align: middle;
    }
    
    .marks-input {
        max-width: 100px;
    }
    
    .marks-input.is-valid {
        border-color: #198754;
        background-image: none;
    }
    
    .marks-input.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.3rem 0.5rem;
    }
    
    .progress {
        background-color: #e9ecef;
    }
    
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const examId = document.getElementById('exam_id').value;
    const userId = document.getElementById('user_id').value;
    const tableBody = document.querySelector('#students-table tbody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    let studentsData = [];
    let filteredStudents = [];
    let examData = null;
    let chart = null;
    
    // Initialize Bootstrap modal
    const statsModal = new bootstrap.Modal(document.getElementById('statsModal'));

    // 1️⃣ Fetch exam details and students
    try {
        const res = await fetch(`/api/exams/${examId}`);
        const data = await res.json();

        if (data.status === 'success') {
            examData = data.exam;
            studentsData = data.students;
            filteredStudents = [...studentsData];
            
            updateExamInfo();
            renderStudents(studentsData);
            updateStats();
        } else {
            showAlert('danger', 'Failed to load exam data');
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No students found</h5>
                    </td>
                </tr>
            `;
        }
    } catch (error) {
        showAlert('danger', `Error loading data: ${error.message}`);
    }

    // Update exam info
    function updateExamInfo() {
        if (!examData) return;
        
        document.getElementById('examTitle').textContent = examData.title || '-';
        document.getElementById('examDate').textContent = formatDate(examData.date);
        document.getElementById('examDuration').textContent = examData.duration || '-';
        document.getElementById('examHall').textContent = examData.hall_name || '-';
        document.getElementById('totalStudents').textContent = studentsData.length;
        
        const marksEntered = studentsData.filter(s => s.marks !== null && s.marks !== undefined).length;
        const percentage = Math.round((marksEntered / studentsData.length) * 100) || 0;
        
        document.getElementById('marksEntered').textContent = marksEntered;
        document.getElementById('completionPercentage').textContent = percentage + '%';
        document.getElementById('completionBar').style.width = percentage + '%';
    }

    // 2️⃣ Render students into table
    function renderStudents(students) {
        tableBody.innerHTML = '';
        
        if (students.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No matching students found</h5>
                    </td>
                </tr>
            `;
            return;
        }
        
        students.forEach(student => {
            const row = document.createElement('tr');
            row.dataset.studentId = student.id;
            
            const marksValue = student.marks !== null && student.marks !== undefined ? student.marks : '';
            const statusClass = getMarksStatusClass(student.marks);
            
            row.innerHTML = `
                <td>
                    <div class="form-check">
                        <input class="form-check-input student-checkbox" type="checkbox" value="${student.id}">
                    </div>
                </td>
                <td><span class="fw-bold">${escapeHtml(student.custom_id)}</span></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                            ${(student.fname?.[0] || 'S') + (student.lname?.[0] || '')}
                        </div>
                        <div>
                            <span class="fw-bold">${escapeHtml(student.fname)} ${escapeHtml(student.lname)}</span>
                            ${student.has_previous ? '<small class="text-warning ms-1"><i class="fas fa-history"></i></small>' : ''}
                        </div>
                    </div>
                </td>
                <td>${escapeHtml(student.mobile || '-')}</td>
                <td>
                    <input type="number" 
                           class="form-control marks-input ${statusClass}" 
                           name="marks_${student.id}" 
                           value="${marksValue}" 
                           min="0" 
                           max="100" 
                           step="0.01"
                           data-student-id="${student.id}"
                           data-student-name="${escapeHtml(student.fname)} ${escapeHtml(student.lname)}"
                           ${examData?.status === 'Canceled' ? 'disabled' : ''}>
                </td>
                <td>
                    <span class="badge ${getMarksStatusBadge(student.marks)} status-badge">
                        ${getMarksStatus(student.marks)}
                    </span>
                </td>
            `;
            tableBody.appendChild(row);
        });
        
        attachMarksInputEvents();
        updateSelectedCount();
    }

    function getMarksStatusClass(marks) {
        if (marks === null || marks === undefined || marks === '') return '';
        marks = parseFloat(marks);
        if (marks >= 40) return 'is-valid';
        if (marks < 40) return 'is-invalid';
        return '';
    }

    function getMarksStatusBadge(marks) {
        if (marks === null || marks === undefined || marks === '') return 'bg-secondary';
        marks = parseFloat(marks);
        if (marks >= 75) return 'bg-success';
        if (marks >= 40) return 'bg-info';
        return 'bg-danger';
    }

    function getMarksStatus(marks) {
        if (marks === null || marks === undefined || marks === '') return 'Not Set';
        marks = parseFloat(marks);
        if (marks >= 75) return 'Excellent';
        if (marks >= 60) return 'Good';
        if (marks >= 40) return 'Pass';
        return 'Fail';
    }

    function attachMarksInputEvents() {
        document.querySelectorAll('.marks-input').forEach(input => {
            input.addEventListener('input', function() {
                const studentId = this.dataset.studentId;
                const value = this.value;
                
                // Update status badge
                const row = this.closest('tr');
                const badge = row.querySelector('.status-badge');
                const marks = value ? parseFloat(value) : null;
                
                badge.className = `badge ${getMarksStatusBadge(marks)} status-badge`;
                badge.textContent = getMarksStatus(marks);
                
                // Update input class
                this.className = `form-control marks-input ${getMarksStatusClass(marks)}`;
                
                // Update stats
                updateStats();
            });
        });
    }

    // 3️⃣ Handle search
    document.getElementById('student-search').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        
        filteredStudents = studentsData.filter(student => {
            const customId = (student.custom_id || '').toLowerCase();
            const name = (student.fname + ' ' + student.lname).toLowerCase();
            const mobile = (student.mobile || '').toLowerCase();
            
            return customId.includes(filter) || name.includes(filter) || mobile.includes(filter);
        });
        
        renderStudents(filteredStudents);
    });

    document.getElementById('clearSearch').addEventListener('click', function() {
        document.getElementById('student-search').value = '';
        filteredStudents = [...studentsData];
        renderStudents(studentsData);
    });

    // 4️⃣ Select All functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedCount();
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('student-checkbox')) {
            updateSelectedCount();
        }
    });

    function updateSelectedCount() {
        const selected = document.querySelectorAll('.student-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = selected;
        document.getElementById('selectAll').checked = 
            selected === document.querySelectorAll('.student-checkbox').length;
    }

    // 5️⃣ Bulk actions
    document.getElementById('setAllPass').addEventListener('click', function() {
        setAllMarks(50);
    });

    document.getElementById('setAllZero').addEventListener('click', function() {
        setAllMarks(0);
    });

    document.getElementById('setAllFull').addEventListener('click', function() {
        setAllMarks(100);
    });

    document.getElementById('clearAll').addEventListener('click', function() {
        setAllMarks('');
    });

    function setAllMarks(value) {
        document.querySelectorAll('.marks-input').forEach(input => {
            input.value = value;
            // Trigger input event to update UI
            const event = new Event('input', { bubbles: true });
            input.dispatchEvent(event);
        });
    }

    // 6️⃣ Validation before submit
    document.getElementById('validateSubmit').addEventListener('click', function() {
        const validation = validateMarks();
        
        if (validation.valid) {
            showAlert('success', 'All marks are valid! Ready to submit.');
        } else {
            showValidationSummary(validation.invalid);
        }
    });

    function validateMarks() {
        const invalid = [];
        document.querySelectorAll('.marks-input').forEach(input => {
            if (input.value === '') return;
            
            const value = parseFloat(input.value);
            const studentName = input.dataset.studentName;
            
            if (isNaN(value) || value < 0 || value > 100) {
                invalid.push(`${studentName}: Marks must be between 0 and 100`);
            }
        });
        
        return {
            valid: invalid.length === 0,
            invalid: invalid
        };
    }

    function showValidationSummary(errors) {
        const summary = document.getElementById('validationSummary');
        summary.classList.remove('d-none');
        summary.innerHTML = `
            <div class="alert alert-danger">
                <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Validation Errors:</h6>
                <ul class="mb-0">
                    ${errors.map(error => `<li>${error}</li>`).join('')}
                </ul>
            </div>
        `;
    }

    // 7️⃣ Handle bulk marks submit
    document.getElementById('bulk-marks-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const validation = validateMarks();
        if (!validation.valid) {
            showValidationSummary(validation.invalid);
            return;
        }

        const results = studentsData.map(student => {
            const marksInput = document.querySelector(`input[name="marks_${student.id}"]`);
            const marksValue = marksInput ? marksInput.value : '';
            return {
                student_id: student.id,
                marks: marksValue !== '' ? parseFloat(marksValue) : null
            };
        });

        try {
            const response = await fetch('/api/exams/results', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    exam_id: examId,
                    user_id: userId,
                    results: results
                })
            });

            const respData = await response.json();

            if (respData.status === 'success') {
                showAlert('success', respData.message);
                
                // Update student data with new marks
                respData.updated_students?.forEach(updated => {
                    const student = studentsData.find(s => s.id === updated.id);
                    if (student) {
                        student.marks = updated.marks;
                    }
                });
                
                updateExamInfo();
                showAlert('success', 'Marks saved successfully!');
            } else {
                showAlert('danger', `Error: ${respData.message}`);
            }
        } catch (error) {
            showAlert('danger', `Error: ${error.message}`);
        }
    });

    // 8️⃣ Statistics
    document.getElementById('showStats').addEventListener('click', function() {
        updateStatisticsModal();
        statsModal.show();
    });

    function updateStats() {
        const marksEntered = studentsData.filter(s => s.marks !== null && s.marks !== undefined).length;
        const percentage = Math.round((marksEntered / studentsData.length) * 100) || 0;
        
        document.getElementById('marksEntered').textContent = marksEntered;
        document.getElementById('completionPercentage').textContent = percentage + '%';
        document.getElementById('completionBar').style.width = percentage + '%';
    }

    function updateStatisticsModal() {
        const marks = studentsData
            .map(s => parseFloat(s.marks))
            .filter(m => !isNaN(m) && m !== null);
        
        if (marks.length === 0) {
            document.getElementById('avgMarks').textContent = 'N/A';
            document.getElementById('highestMarks').textContent = 'N/A';
            document.getElementById('lowestMarks').textContent = 'N/A';
            document.getElementById('passRate').textContent = 'N/A';
            return;
        }
        
        const avg = marks.reduce((a, b) => a + b, 0) / marks.length;
        const highest = Math.max(...marks);
        const lowest = Math.min(...marks);
        const passCount = marks.filter(m => m >= 40).length;
        const passRate = (passCount / marks.length) * 100;
        
        document.getElementById('avgMarks').textContent = avg.toFixed(1);
        document.getElementById('highestMarks').textContent = highest;
        document.getElementById('lowestMarks').textContent = lowest;
        document.getElementById('passRate').textContent = passRate.toFixed(1) + '%';
        
        // Create or update chart
        const ctx = document.getElementById('statsChart').getContext('2d');
        
        if (chart) {
            chart.destroy();
        }
        
        // Create distribution
        const distribution = [0, 0, 0, 0, 0];
        marks.forEach(m => {
            if (m < 20) distribution[0]++;
            else if (m < 40) distribution[1]++;
            else if (m < 60) distribution[2]++;
            else if (m < 80) distribution[3]++;
            else distribution[4]++;
        });
        
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['0-20', '21-40', '41-60', '61-80', '81-100'],
                datasets: [{
                    label: 'Student Distribution',
                    data: distribution,
                    backgroundColor: [
                        '#dc3545',
                        '#ffc107',
                        '#0dcaf0',
                        '#0d6efd',
                        '#198754'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Helper functions
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return String(unsafe)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
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
});
</script>
@endpush