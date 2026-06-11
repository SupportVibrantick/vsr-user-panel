@extends('layouts.master')
@section('title', 'Matching Income')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Toast Notification -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                <div id="mainToast" class="toast align-items-center text-white border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>

            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-handshake text-primary me-2"></i>MATCHING INCOME
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Date Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form id="matchingIncomeForm">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Date From</label>
                                <input type="date" name="date_from" id="dateFrom" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-medium">Date To</label>
                                <input type="date" name="date_to" id="dateTo" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn w-100 text-white" style="background: #1e3a5f; border: none;">
                                    <i class="las la-search me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Matching Income Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0" style="background: #b9f6ca;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0" id="matchingIncomeTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 50px;">SrNo</th>
                                    <th>UserName</th>
                                    <th>Date</th>
                                    <th>Matching Income</th>
                                    <th class="text-center">Credit</th>
                                    <th>Matching CC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total</td>
                                    <td class="text-center fw-bold text-success" id="totalCredit">₹0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
let dataTable;

// Toast function
function showToast(message, type = 'success') {
    const toast = document.getElementById('mainToast');
    const toastMessage = document.getElementById('toastMessage');
    
    toast.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info');
    
    switch(type) {
        case 'success': toast.classList.add('bg-success'); break;
        case 'error': toast.classList.add('bg-danger'); break;
        case 'warning': toast.classList.add('bg-warning'); break;
        default: toast.classList.add('bg-info');
    }
    
    toastMessage.textContent = message;
    new bootstrap.Toast(toast, { delay: 4000 }).show();
}

// Initialize DataTable
function initializeTable() {
    dataTable = $('#matchingIncomeTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[2, 'desc']], // Sort by Date
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries available",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No data available in table",
            emptyTable: "No data available in table"
        },
        columnDefs: [
            { orderable: false, targets: [0] }
        ]
    });
}

// Load Matching Income data
function loadMatchingIncomeData(dateFrom = '', dateTo = '') {
    // Show loading state
    dataTable.clear().draw();
    
    const url = `{{ route('user.matching-income.data') }}?date_from=${dateFrom}&date_to=${dateTo}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTableData(data.data);
                updateTotals(data.totals);
            } else {
                showToast(data.message || 'Failed to load data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while loading data', 'error');
        });
}

// Update table with data
function updateTableData(transactions) {
    dataTable.clear();
    
    if (transactions.length === 0) {
        dataTable.draw();
        return;
    }
    
    transactions.forEach((transaction, index) => {
        const creditAmount = transaction.type === 'credit' ? 
            `<span class="text-success fw-bold">₹${parseFloat(transaction.amount).toFixed(2)}</span>` : 
            '<span class="text-muted">-</span>';
        
        // Calculate matching CC (assuming it's stored in remark or a separate field)
        const matchingCC = transaction.remark || '-';
        
        dataTable.row.add([
            index + 1,
            '{{ session("user_name") }}',
            new Date(transaction.created_at).toLocaleDateString('en-GB'),
            transaction.particular || 'Matching Income',
            creditAmount,
            matchingCC
        ]);
    });
    
    dataTable.draw();
}

// Update totals
function updateTotals(totals) {
    document.getElementById('totalCredit').textContent = '₹' + parseFloat(totals.credit || 0).toFixed(2);
}

// Form submission
document.getElementById('matchingIncomeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    loadMatchingIncomeData(dateFrom, dateTo);
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeTable();
    loadMatchingIncomeData(); // Load all data by default
});
</script>
@endpush