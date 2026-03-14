@extends('layouts.app')

@section('title', 'Teacher Ledger')
@section('page-title', 'Teacher Ledger Summary')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Teacher Ledger</li>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Month Selector -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-1 text-primary">
                                <i class="fas fa-book me-2"></i>Monthly Ledger Summary
                            </h5>
                            <p class="text-muted small mb-0">Generate detailed ledger for selected month</p>
                        </div>
                        <div class="col-md-4">
                            <form id="ledgerForm" method="GET" action="{{ route('teacher_ledger_summary.index') }}">
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 py-2">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="month" 
                                           class="form-control border-start-0 py-2" 
                                           id="monthSelect" 
                                           name="month" 
                                           value="{{ request('month') ?? date('Y-m') }}"
                                           required>
                                    <button type="submit" class="btn btn-primary px-3 py-2">
                                        <i class="fas fa-chart-line"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($data) && $data['status'] === 'success')
        @php $ledgerData = $data['data']; @endphp
        
        <!-- Summary Cards -->
        <div class="row mb-3">
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-start-2 border-start-primary shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="row g-0 align-items-center">
                            <div class="col-8">
                                <div class="text-xs fw-bold text-muted text-uppercase">Opening</div>
                                <div class="h6 mb-0 fw-bold">
                                    Rs. {{ number_format($ledgerData['opening_balance'], 2) }}
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <i class="fas fa-wallet text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-start-2 border-start-success shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="row g-0 align-items-center">
                            <div class="col-8">
                                <div class="text-xs fw-bold text-muted text-uppercase">Receipts</div>
                                <div class="h6 mb-0 fw-bold text-success">
                                    Rs. {{ number_format($ledgerData['summary']['total_receipts'], 2) }}
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <i class="fas fa-download text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-start-2 border-start-danger shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="row g-0 align-items-center">
                            <div class="col-8">
                                <div class="text-xs fw-bold text-muted text-uppercase">Payments</div>
                                <div class="h6 mb-0 fw-bold text-danger">
                                    Rs. {{ number_format($ledgerData['summary']['total_payments'], 2) }}
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <i class="fas fa-upload text-danger fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-start-2 border-start-warning shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="row g-0 align-items-center">
                            <div class="col-8">
                                <div class="text-xs fw-bold text-muted text-uppercase">Closing</div>
                                <div class="h6 mb-0 fw-bold text-warning">
                                    Rs. {{ $ledgerData['summary']['closing_balance'] }}
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <i class="fas fa-balance-scale text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Period Banner -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-primary bg-opacity-10 border-primary py-2 mb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="far fa-calendar-alt me-2"></i>
                            <strong>{{ $ledgerData['period']['month'] }}</strong>
                            <span class="text-muted ms-2">
                                {{ $ledgerData['period']['start_date'] }} - {{ $ledgerData['period']['end_date'] }}
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-primary">Monthly Ledger</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger Table -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-table me-2 text-primary"></i>Ledger Details
                            </h6>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#filterSection" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filter Section -->
                    <div class="collapse" id="filterSection">
                        <div class="card-body border-bottom bg-light py-2">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Transaction Type</label>
                                    <select class="form-select form-select-sm" id="filterType">
                                        <option value="all">All</option>
                                        <option value="receipt">Receipts</option>
                                        <option value="payment">Payments</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small text-muted mb-1">Search</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" 
                                               placeholder="Search description..." id="searchDescription">
                                        <button class="btn btn-outline-secondary" onclick="clearFilters()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0" id="ledgerTable">
                                <thead class="table-light">
                                    <tr class="small">
                                        <th class="ps-3" style="width: 15%">
                                            <i class="far fa-calendar me-1"></i>Date
                                        </th>
                                        <th style="width: 45%">
                                            <i class="far fa-file-alt me-1"></i>Description
                                        </th>
                                        <th class="text-end" style="width: 10%">
                                            <i class="fas fa-plus-circle me-1 text-success"></i>Receipt
                                        </th>
                                        <th class="text-end" style="width: 10%">
                                            <i class="fas fa-minus-circle me-1 text-danger"></i>Payment
                                        </th>
                                        <th class="text-end pe-3" style="width: 15%">
                                            <i class="fas fa-balance-scale me-1"></i>Balance
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Opening Balance -->
                                    <tr class="table-info">
                                        <td class="ps-3 fw-bold small">
                                            {{ date('d M Y', strtotime($ledgerData['period']['start_date'])) }}
                                        </td>
                                        <td class="fw-bold small">
                                            Opening Balance
                                        </td>
                                        <td class="text-end">-</td>
                                        <td class="text-end">-</td>
                                        <td class="text-end pe-3 fw-bold">
                                            {{ number_format($ledgerData['opening_balance'], 2) }}
                                        </td>
                                    </tr>
                                    
                                    <!-- Ledger Entries -->
                                    @forelse($ledgerData['ledger'] as $entry)
                                        <tr class="ledger-entry small">
                                            <td class="ps-3">
                                                {{ $entry['date'] }}
                                            </td>
                                            <td>
                                                {{ $entry['description'] }}
                                            </td>
                                            <td class="text-end">
                                                @if($entry['receipt'])
                                                    <span class="text-success">
                                                        {{ $entry['receipt'] }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($entry['payment'])
                                                    <span class="text-danger">
                                                        {{ $entry['payment'] }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3 fw-medium">
                                                {{ $entry['balance'] }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">No transactions found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                    
                                    <!-- Closing Summary -->
                                    <tr class="table-warning fw-bold">
                                        <td colspan="2" class="ps-3">
                                            Period Summary
                                        </td>
                                        <td class="text-end text-success">
                                            {{ number_format($ledgerData['summary']['total_receipts'], 2) }}
                                        </td>
                                        <td class="text-end text-danger">
                                            {{ number_format($ledgerData['summary']['total_payments'], 2) }}
                                        </td>
                                        <td class="text-end pe-3">
                                            {{ $ledgerData['summary']['closing_balance'] }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="fas fa-clock me-1"></i>
                                {{ date('Y-m-d H:i') }}
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i>Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <div class="row text-center">
                            <div class="col-md-2 col-4 mb-2">
                                <div class="p-2 border rounded">
                                    <div class="h5 mb-1">{{ count($ledgerData['ledger']) }}</div>
                                    <small class="text-muted">Transactions</small>
                                </div>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <div class="p-2 border rounded">
                                    <div class="h5 mb-1 text-success">
                                        {{ $ledgerData['ledger']->where('receipt', '!=', '')->count() }}
                                    </div>
                                    <small class="text-muted">Receipts</small>
                                </div>
                            </div>
                            <div class="col-md-2 col-4 mb-2">
                                <div class="p-2 border rounded">
                                    <div class="h5 mb-1 text-danger">
                                        {{ $ledgerData['ledger']->where('payment', '!=', '')->count() }}
                                    </div>
                                    <small class="text-muted">Payments</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <div class="p-2 border rounded">
                                    <div class="h5 mb-1">
                                        @php
                                            $netChange = $ledgerData['summary']['net_change'];
                                            $textClass = $netChange >= 0 ? 'text-success' : 'text-danger';
                                        @endphp
                                        <span class="{{ $textClass }}">
                                            {{ $netChange >= 0 ? '+' : '' }}{{ number_format($netChange, 2) }}
                                        </span>
                                    </div>
                                    <small class="text-muted">Net Change</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-2">
                                <div class="p-2 border rounded">
                                    <div class="h5 mb-1">
                                        <span class="badge {{ $ledgerData['summary']['net_change'] >= 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $ledgerData['summary']['net_change'] >= 0 ? 'Profit' : 'Loss' }}
                                        </span>
                                    </div>
                                    <small class="text-muted">Status</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-2 border-bottom">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie me-2 text-primary"></i>Visual Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="text-center mb-3">
                                    <h4 class="text-success mb-1">
                                        Rs. {{ number_format($ledgerData['summary']['total_receipts'], 2) }}
                                    </h4>
                                    <small class="text-muted">Total Receipts</small>
                                </div>
                                <div class="text-center mb-3">
                                    <h4 class="text-danger mb-1">
                                        Rs. {{ number_format($ledgerData['summary']['total_payments'], 2) }}
                                    </h4>
                                    <small class="text-muted">Total Payments</small>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <canvas id="ledgerChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif(isset($data) && $data['status'] === 'error')
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $data['message'] }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto submit form on month change
            document.getElementById('monthSelect').addEventListener('change', function() {
                if(this.value) {
                    document.getElementById('ledgerForm').submit();
                }
            });
            
            // Filter functionality
            document.getElementById('filterType').addEventListener('change', filterTable);
            document.getElementById('searchDescription').addEventListener('keyup', filterTable);
            
            @if(isset($data) && $data['status'] === 'success')
                initializeChart();
            @endif
        });
        
        function filterTable() {
            const typeFilter = document.getElementById('filterType').value;
            const searchFilter = document.getElementById('searchDescription').value.toLowerCase();
            const rows = document.querySelectorAll('.ledger-entry');
            
            rows.forEach(row => {
                const receipt = row.querySelector('.text-success') !== null;
                const description = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                let showRow = true;
                
                // Apply type filter
                if (typeFilter === 'receipt' && !receipt) showRow = false;
                if (typeFilter === 'payment' && receipt) showRow = false;
                
                // Apply search filter
                if (searchFilter && !description.includes(searchFilter)) showRow = false;
                
                row.style.display = showRow ? '' : 'none';
            });
        }
        
        function clearFilters() {
            document.getElementById('filterType').value = 'all';
            document.getElementById('searchDescription').value = '';
            filterTable();
        }
        
        function initializeChart() {
            const ctx = document.getElementById('ledgerChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Receipts', 'Payments'],
                    datasets: [{
                        data: [
                            {{ $ledgerData['summary']['total_receipts'] ?? 0 }},
                            {{ $ledgerData['summary']['total_payments'] ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(220, 53, 69, 0.8)'
                        ],
                        borderColor: [
                            'rgb(40, 167, 69)',
                            'rgb(220, 53, 69)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 8px;
    }
    
    .border-start-2 {
        border-left-width: 3px !important;
    }
    
    .table-sm td, .table-sm th {
        padding: 0.5rem;
    }
    
    .table > :not(:first-child) {
        border-top: none;
    }
    
    .table-info {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .table-warning {
        background-color: rgba(255, 193, 7, 0.05);
    }
    
    .small {
        font-size: 0.875rem;
    }
    
    .h6 {
        font-size: 1rem;
    }
    
    .form-select-sm, .form-control-sm {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
    
    .input-group-sm > .form-control,
    .input-group-sm > .input-group-text {
        padding: 0.25rem 0.5rem;
    }
    
    @media print {
        .card-header, .card-footer, .btn, .alert {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        table {
            font-size: 12px !important;
        }
    }
</style>
@endsection