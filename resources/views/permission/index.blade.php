@extends('layouts.app')

@section('title', 'User Permission')
@section('page-title', 'User Permission')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">User Permission</li>
@endsection

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manage User Permissions</h4>
            <button type="button" id="selectAllGlobal" class="btn btn-sm btn-secondary">
                Select / Unselect All
            </button>
        </div>

        <div class="card-body">

            <form id="permissionForm">
                <input type="hidden" id="userId" value="{{ $userId }}">

                <div id="permissionContainer">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        Save Permissions
                    </button>
                </div>
            </form>

            <div id="alertBox" class="mt-3"></div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>

        document.addEventListener('DOMContentLoaded', async function () {

            const container = document.getElementById('permissionContainer');
            const userId = document.getElementById('userId').value;
            const alertBox = document.getElementById('alertBox');

            let assignedPermissions = [];

            try {
                // 1️⃣ Fetch all pages
                const pageResponse = await fetch('/api/permission');

                const pageResult = await pageResponse.json();

                if (!pageResult.success) {
                    container.innerHTML = '<div class="alert alert-danger">Failed to load pages</div>';
                    return;
                }

                // ✅ Correctly define pages variable
                const pages = pageResult.data;

                // 2️⃣ Fetch assigned permissions for this user
                try {
                    const assignedResponse = await fetch(`/api/permission/${userId}`);

                    const assignedResult = await assignedResponse.json();

                    if (assignedResult.success) {
                        assignedPermissions = assignedResult.data;
                    }
                } catch (e) {
                    console.error('Failed to load assigned permissions:', e);
                }

                // 3️⃣ Group pages by section
                const grouped = {};
                pages.forEach(page => {
                    const parts = page.page_name.split(' - ');
                    const section = parts[0];

                    if (!grouped[section]) grouped[section] = [];
                    grouped[section].push(page);
                });

                // 4️⃣ Render sections
                container.innerHTML = '';
                Object.keys(grouped).forEach(section => {
                    const sectionCard = document.createElement('div');
                    sectionCard.className = 'card mb-3';

                    sectionCard.innerHTML = `
                        <div class="card-header d-flex justify-content-between align-items-center bg-light">
                            <strong>${section}</strong>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary select-section"
                                    data-section="${section}">
                                Select All
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                ${grouped[section].map(page => `
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox section-${section.replace(/\s/g, '_')}"
                                                   type="checkbox"
                                                   value="${page.page_id}"
                                                   id="page_${page.page_id}"
                                                   ${assignedPermissions.includes(page.page_id) ? 'checked' : ''}>
                                            <label class="form-check-label" for="page_${page.page_id}">
                                                ${page.page_name}
                                            </label>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                    container.appendChild(sectionCard);
                });

                // 5️⃣ Section "Select All" buttons
                document.querySelectorAll('.select-section').forEach(button => {
                    button.addEventListener('click', function () {
                        const section = this.dataset.section.replace(/\s/g, '_');
                        const checkboxes = document.querySelectorAll(`.section-${section}`);
                        const allChecked = [...checkboxes].every(cb => cb.checked);
                        checkboxes.forEach(cb => cb.checked = !allChecked);
                    });
                });

                // 6️⃣ Global "Select / Unselect All" button
                document.getElementById('selectAllGlobal').addEventListener('click', function () {
                    const allCheckboxes = document.querySelectorAll('.permission-checkbox');
                    const allChecked = [...allCheckboxes].every(cb => cb.checked);
                    allCheckboxes.forEach(cb => cb.checked = !allChecked);
                });

                // 7️⃣ Save button - commented for now

                document.getElementById('permissionForm').addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const selected = [];
                    document.querySelectorAll('.permission-checkbox:checked').forEach(cb => {
                        selected.push(cb.value);
                    });

                    try {
                        const response = await fetch('/api/permission/store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                user_type_id: userId, // changed from user_id
                                page_ids: selected     // changed from pages
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            alertBox.innerHTML = `<div class="alert alert-success">Permissions saved successfully!</div>`;
                        } else {
                            alertBox.innerHTML = `<div class="alert alert-danger">Failed to save permissions</div>`;
                        }
                    } catch (error) {
                        alertBox.innerHTML = `<div class="alert alert-danger">Server error</div>`;
                    }
                });


            } catch (error) {
                container.innerHTML = '<div class="alert alert-danger">Error loading permissions</div>';
                console.error(error);
            }

        });
    </script>
@endpush