@extends('layouts.master')
@section('title', 'Genealogy')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-title-box shadow-sm border-0">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0 fs-20"><i class="las la-sitemap text-primary me-2"></i>GENEALOGY TREE</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tree Structure -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="tree-wrapper">
                        @if($treeData)
                            @include('pages.user.partials.tree-node', ['node' => $treeData, 'isRoot' => true, 'depth' => 0])
                        @elseif($rootUser && isset($rootUser->id))
                            @php
                                $rootNode = (object)[
                                    'id' => $rootUser->id ?? 0,
                                    'user_id' => $rootUser->id ?? 0,
                                    'user_name' => $rootUser->user_name ?? '',
                                    'first_name' => $rootUser->first_name ?? '',
                                    'last_name' => $rootUser->last_name ?? '',
                                    'email' => $rootUser->email ?? '',
                                    'position' => 'none',
                                    'level' => 0,
                                    'is_active' => $rootUser->is_active ?? false,
                                    'is_root' => true,
                                    'left' => null,
                                    'right' => null,
                                ];
                            @endphp
                            @include('pages.user.partials.tree-node', ['node' => $rootNode, 'isRoot' => true, 'depth' => 0])
                        @else
                            <div class="alert alert-warning text-center">
                                <i class="las la-exclamation-triangle me-2"></i>
                                No tree data found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Profile Modal -->
<div class="modal fade" id="userProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 pb-4 px-4">
                <div id="profileContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading profile...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Tree Structure */
.tree-wrapper {
    text-align: center;
    padding: 20px;
    overflow-x: auto;
}

.tree-level {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 30px;
    margin: 30px 0;
    position: relative;
}

.tree-node-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

/* User Card */
.user-card {
    background: white;
    border-radius: 16px;
    padding: 18px 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    min-width: 220px;
    max-width: 240px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    position: relative;
}

.user-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    border-color: #eef2f7;
}

.user-card.root-user {
    border: 2px solid #eef2f7;
}

.crown-icon {
    position: absolute;
    top: -12px;
    right: 15px;
    color: #fbbf24;
    font-size: 18px;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    color: white;
    margin: 0 auto 12px;
}

.avatar-blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.avatar-orange {
    background: linear-gradient(135deg, #f5a623 0%, #ff8c00 100%);
}

.user-name {
    font-weight: 700;
    font-size: 14px;
    color: #1a1d2e;
    margin-bottom: 3px;
}

.user-handle {
    font-size: 12px;
    color: #8a91a8;
    margin-bottom: 8px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 600;
    color: #27ae60;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #27ae60;
}

/* Connector Lines */
.connector-vertical {
    width: 2px;
    height: 40px;
    background: #e2e8f0;
    margin: 0 auto;
}

/* Expand Button */
.expand-btn {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: white;
    border: 2px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    color: #8a91a8;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    margin-top: 10px;
}

.expand-btn:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.expand-btn.expanded {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.expand-btn.expanded i {
    transform: rotate(180deg);
}

.expand-btn i {
    transition: transform 0.3s ease;
}

/* Empty Slot */
.empty-slot {
    background: #f8f9fa;
    border: 2px dashed #e2e8f0;
    border-radius: 16px;
    padding: 30px 20px;
    min-width: 220px;
    color: #a0aec0;
    font-size: 13px;
}

.empty-slot i {
    font-size: 32px;
    margin-bottom: 8px;
    opacity: 0.4;
}

/* Modal Styles */
.modal-profile-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 700;
    margin: 0 auto 20px;
    position: relative;
}

.modal-status-dot {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 18px;
    height: 18px;
    background: #27ae60;
    border-radius: 50%;
    border: 3px solid white;
}

.modal-title {
    font-size: 22px;
    font-weight: 700;
    color: #1a1d2e;
    margin-bottom: 5px;
}

.modal-subtitle {
    font-size: 14px;
    color: #8a91a8;
    margin-bottom: 30px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 25px;
}

.stat-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 18px 15px;
    text-align: center;
}

.stat-card.personal-bv {
    background: #f0fdf4;
}

.stat-card.left-bv {
    background: #eff6ff;
}

.stat-card.right-bv {
    background: #faf5ff;
}

.stat-card.network-bv {
    background: #f5f3ff;
}

.stat-label {
    font-size: 10px;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #8a91a8;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #1a1d2e;
}

.stat-card.personal-bv .stat-value { color: #27ae60; }
.stat-card.left-bv .stat-value { color: #3b82f6; }
.stat-card.right-bv .stat-value { color: #8b5cf6; }
.stat-card.network-bv .stat-value { color: #7c3aed; }

/* Subtree Toggle */
.subtree {
    display: none;
    animation: fadeIn 0.4s ease;
}

.subtree.show {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Tree script loaded');
    
    const treeWrapper = document.querySelector('.tree-wrapper');
    if (!treeWrapper) return;

    // Event delegation for tree clicks
    treeWrapper.addEventListener('click', function(e) {
        // 1️ Profile card click
        const profileCard = e.target.closest('.profile-clickable');
        if (profileCard) {
            e.preventDefault();
            e.stopPropagation();
            const userId = profileCard.dataset.userId;
            if (userId) openProfileModal(userId);
            return;
        }

        // 2️⃣ Expand button click
        const toggleBtn = e.target.closest('.subtree-toggle');
        if (toggleBtn) {
            e.preventDefault();
            e.stopPropagation();
            const targetId = toggleBtn.dataset.target;
            const subtree = document.getElementById(targetId);
            if (subtree) {
                subtree.classList.toggle('show');
                toggleBtn.classList.toggle('expanded');
            }
        }
    });

    // Modal function
    function openProfileModal(userId) {
        const modalEl = document.getElementById('userProfileModal');
        const modal = new bootstrap.Modal(modalEl);
        const contentEl = document.getElementById('profileContent');
        
        contentEl.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Loading profile...</p>
            </div>`;
        
        modal.show();

        const url = `{{ url('/user-profile') }}/${userId}/modal`;

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.text();
            })
            .then(html => {
                contentEl.innerHTML = html;
            })
            .catch(err => {
                contentEl.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="las la-exclamation-circle me-2"></i>
                        Failed to load profile.
                    </div>`;
            });
    }
});

// ✅ GLOBAL FUNCTION: Yeh function bahar hai taaki HTML button isse access kar sake
window.loadUserTree = function(userId) {
    console.log('Loading tree for user:', userId);
    
    // 1. Modal band karein
    const modalEl = document.getElementById('userProfileModal');
    if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }

    const treeWrapper = document.querySelector('.tree-wrapper');
    
    // 2. Loading dikhayein
    treeWrapper.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-3 text-muted">Loading tree for User ID: ${userId}...</p>
        </div>
    `;

    // 3. Fetch HTML from our new route
    const url = `{{ url('/user-tree') }}/${userId}/html`;
    
    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.text();
        })
        .then(html => {
            // 4. Update the DOM with new tree HTML
            treeWrapper.innerHTML = html;
            
            // Scroll to top to see the new tree
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(err => {
            console.error('Error loading tree:', err);
            treeWrapper.innerHTML = `
                <div class="alert alert-danger text-center">
                    <i class="las la-exclamation-circle me-2"></i>
                    Failed to load tree. Please try again.
                </div>
            `;
        });
}
</script>
@endpush