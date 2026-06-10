@extends('layouts.master')
@section('title', 'Fund Request')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <h4 class="mb-0 fs-20">
                            <i class="las la-hand-holding-usd text-primary me-2"></i>FUND REQUEST
                        </h4>
                    </div>
                </div>
            </div>

            <!-- ✅ Toast Notification Container -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                <div id="mainToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="toastMessage">
                            <!-- Message will be inserted here -->
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>

            <!-- Fund Request Form -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form id="fundRequestForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Payment Mode -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Payment Mode</label>
                                    <select class="form-select" id="paymentMode" name="payment_mode" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>

                                <!-- Bank Details Section (Hidden initially) -->
                                <div id="bankDetailsSection" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-medium">QR Code</label>
                                        <div id="qrCodeDisplay" class="text-center p-3 bg-light rounded">
                                            <img id="qrCodeImg" src="" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-medium">Bank Name</label>
                                        <input type="text" class="form-control bg-light" id="bankName" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-medium">IFSC Code</label>
                                        <input type="text" class="form-control bg-light" id="ifscCode" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-medium">Account NO.</label>
                                        <input type="text" class="form-control bg-light" id="accountNo" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted fw-medium">UPI Address</label>
                                        <input type="text" class="form-control bg-light" id="upiAddress" readonly>
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Enter Amount</label>
                                    <input type="number" class="form-control" name="amount" placeholder="Enter Amount" required min="1">
                                </div>

                                <!-- Remark -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Enter Remark</label>
                                    <input type="text" class="form-control" name="remark" placeholder="Enter Remark">
                                </div>

                                <!-- Mode of Payment -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Mode Of Payment</label>
                                    <select class="form-select" name="mode_of_payment" required>
                                        <option value="">Select</option>
                                        <option value="IMPS">IMPS</option>
                                        <option value="NEFT">NEFT</option>
                                        <option value="RTGS">RTGS</option>
                                        <option value="UPI">UPI</option>
                                    </select>
                                </div>

                                <!-- Deposit Bank -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Deposit Bank</label>
                                    <input type="text" class="form-control" name="deposit_bank" placeholder="Deposit Bank" required>
                                </div>

                                <!-- Transaction Number -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Enter Transaction No</label>
                                    <input type="text" class="form-control" name="transaction_no" placeholder="Enter Transaction No" required>
                                </div>

                                <!-- Deposit Date -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Deposite Date</label>
                                    <input type="date" class="form-control" name="deposit_date" required>
                                </div>

                                <!-- Hash Code Image -->
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-medium">Hash Code</label>
                                    <input type="file" class="form-control" name="hash_code" accept="image/*" id="hashCodeInput">
                                    <small class="text-danger" id="fileSizeError" style="display: none;">You Can Not Upload More Than 2 MB Image</small>
                                    <small class="text-muted">Max file size: 2MB (JPEG, PNG, JPG, GIF)</small>
                                </div>

                                <!-- Submit Button -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4" style="background: #1e3a5f; border: none;">
                                        <i class="las la-paper-plane me-2"></i>Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let bankDetails = [];

// ✅ Toast Notification Function
function showToast(message, type = 'success') {
    const toast = document.getElementById('mainToast');
    const toastMessage = document.getElementById('toastMessage');
    
    // Remove previous classes
    toast.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-primary');
    
    // Add new class based on type
    switch(type) {
        case 'success':
            toast.classList.add('bg-success');
            break;
        case 'error':
            toast.classList.add('bg-danger');
            break;
        case 'warning':
            toast.classList.add('bg-warning');
            break;
        case 'info':
            toast.classList.add('bg-info');
            break;
        default:
            toast.classList.add('bg-primary');
    }
    
    // Set message
    toastMessage.textContent = message;
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast, {
        delay: 4000
    });
    bsToast.show();
}

// ✅ Load bank details on page load (Vanilla JS - No jQuery)
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Page loaded, fetching bank details...');
    loadBankDetails();
});

// ✅ Load bank details using Fetch API
function loadBankDetails() {
    const apiUrl = '{{ route("user.fund-request.bank-details") }}';
    console.log('📡 Fetching from:', apiUrl);
    
    fetch(apiUrl, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('📥 Response status:', response.status);
        return response.json();
    })
    .then(response => {
        console.log('✅ Response received:', response);
        
        if (response.success && response.data && response.data.length > 0) {
            bankDetails = response.data;
            console.log('✅ Bank details loaded:', bankDetails);
            populatePaymentModeDropdown(bankDetails);
            showToast('Bank details loaded successfully', 'success');
        } else {
            console.warn('⚠️ No bank details found');
            showToast('No bank details available', 'warning');
        }
    })
    .catch(error => {
        console.error('❌ Fetch Error:', error);
        showToast('Failed to load bank details', 'error');
    });
}

// ✅ Populate payment mode dropdown
function populatePaymentModeDropdown(banks) {
    const select = document.getElementById('paymentMode');
    
    // Clear existing options except "Select"
    select.innerHTML = '<option value="">Select</option>';
    
    // Add bank options
    banks.forEach(bank => {
        const option = document.createElement('option');
        option.value = bank.id;
        option.textContent = bank.mode_name;
        select.appendChild(option);
    });
    
    console.log('✅ Dropdown populated with', banks.length, 'banks');
}

// ✅ Show bank details when payment mode is selected
document.getElementById('paymentMode').addEventListener('change', function() {
    const bankId = this.value;
    const bankDetailsSection = document.getElementById('bankDetailsSection');
    
    console.log('🔔 Payment mode changed, bank ID:', bankId);
    
    if (bankId) {
        const selectedBank = bankDetails.find(bank => bank.id == bankId);
        
        if (selectedBank) {
            console.log('✅ Selected bank:', selectedBank);
            
            // Show bank details section
            bankDetailsSection.style.display = 'block';
            
            // Populate bank details
            document.getElementById('bankName').value = selectedBank.bank_name || 'N/A';
            document.getElementById('ifscCode').value = selectedBank.ifsc_code || 'N/A';
            document.getElementById('accountNo').value = selectedBank.account_no || 'N/A';
            document.getElementById('upiAddress').value = selectedBank.address || 'N/A';
            
            // Store bank ID in hidden field for form submission
            let hiddenInput = document.querySelector('input[name="bank_detail_id"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'bank_detail_id';
                document.getElementById('fundRequestForm').appendChild(hiddenInput);
            }
            hiddenInput.value = bankId;
            
            // Show QR code if available
            if (selectedBank.image) {
                document.getElementById('qrCodeImg').src = '/storage/' + selectedBank.image;
                document.getElementById('qrCodeDisplay').style.display = 'block';
            } else {
                document.getElementById('qrCodeDisplay').style.display = 'none';
            }
        }
    } else {
        bankDetailsSection.style.display = 'none';
    }
});

// ✅ File size validation
document.getElementById('hashCodeInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileSizeError = document.getElementById('fileSizeError');
    const maxSize = 2 * 1024 * 1024; // 2MB
    
    if (file && file.size > maxSize) {
        fileSizeError.style.display = 'block';
        this.value = '';
        showToast('File size must be less than 2MB', 'error');
    } else {
        fileSizeError.style.display = 'none';
    }
});

// ✅ Form submission using Fetch API
document.getElementById('fundRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Validation
    if (!formData.get('payment_mode')) {
        showToast('Please select a payment mode', 'warning');
        return;
    }
    
    if (!formData.get('bank_detail_id')) {
        showToast('Please select a valid bank', 'warning');
        return;
    }
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Submitting...';
    
    // Submit form
    fetch('{{ route("user.fund-request.submit") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Submit response:', data);
        
        if (data.success) {
            showToast('Fund request submitted successfully!', 'success');
            document.getElementById('fundRequestForm').reset();
            document.getElementById('bankDetailsSection').style.display = 'none';
        } else {
            showToast(data.message || 'Failed to submit fund request', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while submitting the request', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="las la-paper-plane me-2"></i>Send';
    });
});
</script>
@endpush