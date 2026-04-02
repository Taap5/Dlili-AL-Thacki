@extends('layouts.app')

@section('title', 'إدارة التقييمات')

@section('content')
<style>
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --danger: #dc3545;
        --warning: #ffc107;
        --text-dark: #1a2c3e;
        --text-muted: #6c757d;
        --border-light: #eef2f6;
    }

    main {
        padding-top: 100px !important;
    }

    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* رأس الصفحة */
    .page-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-dark);
        position: relative;
        padding-right: 20px;
    }

    .page-title::before {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 30px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 4px;
    }

    .stats-badge {
        background: #f0f4ff;
        padding: 8px 20px;
        border-radius: 40px;
        font-weight: 500;
        color: var(--primary);
    }

    /* شريط البحث والفلتر */
    .filters-bar {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid var(--border-light);
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }

    .search-input {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-input i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .search-input input {
        width: 100%;
        padding: 12px 42px 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 40px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .search-input input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
    }

    .filter-select {
        min-width: 150px;
    }

    .filter-select select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 40px;
        background: white;
        font-size: 14px;
        cursor: pointer;
    }

    .reset-btn {
        background: #f0f4ff;
        border: none;
        border-radius: 40px;
        padding: 12px 24px;
        color: var(--primary);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .reset-btn:hover {
        background: var(--primary);
        color: white;
    }

    /* جدول التقييمات */
    .data-table-wrapper {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-light);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 16px 20px;
        text-align: center;
        background: #fafbfc;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
        border-bottom: 1px solid var(--border-light);
    }

    .data-table td {
        padding: 16px 20px;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.85rem;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover td {
        background: #fafbfc;
    }

    .rating-stars {
        color: var(--warning);
        font-size: 12px;
        white-space: nowrap;
    }

    .review-comment {
        max-width: 250px;
        text-align: right;
        line-height: 1.5;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        background: #f0f4ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    .government-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .government-link:hover {
        text-decoration: underline;
    }

    .btn-delete {
        background: transparent;
        border: 1px solid var(--danger);
        border-radius: 30px;
        padding: 6px 14px;
        color: var(--danger);
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-delete:hover {
        background: var(--danger);
        color: white;
    }

    /* تنبيه النجاح */
    .alert-success-custom {
        background: #e8f5e9;
        color: #2e7d32;
        border-right: 4px solid #2e7d32;
        border-radius: 16px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success-custom i {
        font-size: 1.2rem;
    }

    /* الترقيم */
    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .page-link {
        padding: 8px 14px;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        color: var(--text-muted);
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-link:hover {
        background: #f0f4ff;
        border-color: var(--primary);
        color: var(--primary);
    }

    .page-link.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    /* حالة عدم وجود بيانات */
    .empty-row td {
        padding: 60px;
        text-align: center;
        color: var(--text-muted);
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .admin-container {
            padding: 0 15px;
        }

        .page-title {
            font-size: 1.4rem;
        }

        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input,
        .filter-select {
            width: 100%;
        }

        .data-table th,
        .data-table td {
            padding: 10px 12px;
            font-size: 0.75rem;
        }

        .review-comment {
            max-width: 150px;
        }

        .user-info {
            flex-direction: column;
            gap: 4px;
        }
    }
    /* ===== تنسيقات المودال ===== */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 2000;
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 28px;
    width: 90%;
    max-width: 450px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0.2);
}

.modal-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    border-radius: 28px 28px 0 0;
}

.modal-header h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-header button {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #9ca3af;
    transition: color 0.2s;
}

.modal-header button:hover {
    color: var(--danger);
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px 24px;
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    background: #fff;
    border-radius: 0 0 28px 28px;
}

.btn-secondary {
    background: #f0f4ff;
    border: none;
    border-radius: 40px;
    padding: 8px 20px;
    color: var(--primary);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background: #e5e9f5;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
    border-radius: 40px;
    padding: 8px 20px;
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}
</style>

<div class="admin-container py-5">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-star text-warning me-2"></i>
            إدارة التقييمات
        </h1>
        <div class="stats-badge">
            <i class="fas fa-chart-line me-1"></i>
            إجمالي التقييمات: {{ $reviews->total() }}
        </div>
    </div>

    <!-- شريط البحث والفلتر -->
    <div class="filters-bar">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="ابحث عن مستخدم أو جهة..." value="{{ request('search') }}">
        </div>
        <div class="filter-select">
            <select id="ratingFilter">
                <option value="">كل التقييمات</option>
                <option value="5">5 نجوم</option>
                <option value="4">4 نجوم</option>
                <option value="3">3 نجوم</option>
                <option value="2">2 نجوم</option>
                <option value="1">1 نجمة</option>
            </select>
        </div>
        <button class="reset-btn" id="resetBtn">
            <i class="fas fa-undo-alt me-1"></i>
            إعادة تعيين
        </button>
    </div>

    <!-- جدول التقييمات -->
    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
                 <tr>
                    <th>المستخدم</th>
                    <th>الجهة</th>
                    <th>التقييم</th>
                    <th>التعليق</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="reviewsTableBody">
                @if(session('success'))
                    <tr class="alert-row">
                        <td colspan="6">
                            <div class="alert-success-custom">
                                <i class="fas fa-check-circle fa-lg"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        </td>
                    </tr>
                @endif

                @forelse($reviews as $review)
                    <tr data-review-id="{{ $review->id }}">
                         <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span>{{ $review->user->user_name }}</span>
                            </div>
                         </td>
                         <td>
                            <a href="{{ route('governments.show', $review->government->id) }}" class="government-link">
                                {{ $review->government->name }}
                            </a>
                         </td>
                         <td>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span class="text-muted ms-1">({{ $review->rating }})</span>
                            </div>
                         </td>
                        <td class="review-comment">
                            {{ $review->comment ?: '<span class="text-muted fst-italic">لا يوجد تعليق</span>' }}
                        </td>
                         <td>
                            <span title="{{ $review->created_at->format('Y-m-d H:i') }}">
                                {{ $review->created_at->diffForHumans() }}
                            </span>
                        </td>
                         <td>
                            <button class="btn-delete" onclick="deleteReview({{ $review->id }})">
                                <i class="fas fa-trash-alt me-1"></i>
                                حذف
                            </button>
                            <form id="delete-form-{{ $review->id }}" action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="6">
                            <div class="empty-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <p>لا توجد تقييمات حتى الآن</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- الترقيم -->
    <div class="pagination-wrapper">
        {{ $reviews->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
    <!-- Modal تأكيد الحذف -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="color: var(--danger);">
                <i class="fas fa-exclamation-triangle me-2"></i>
                تأكيد الحذف
            </h3>
            <button type="button" onclick="closeDeleteModal()" style="background:none; border:none; font-size:24px;">&times;</button>
        </div>
        <div class="modal-body">
            <p class="text-center">هل أنت متأكد من حذف هذا التقييم؟</p>
            <p class="text-muted text-center small">لا يمكن التراجع عن هذا الإجراء.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeDeleteModal()">إلغاء</button>
            <button type="button" class="btn-danger" id="confirmDeleteBtn">حذف</button>
        </div>
    </div>
</div>
</div>
<script>
    {
    let reviewIdToDelete = null;

// حذف التقييم - يفتح المودال
function deleteReview(id) {
    reviewIdToDelete = id;
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
    reviewIdToDelete = null;
}

function confirmDelete() {
    if (reviewIdToDelete) {
        document.getElementById('delete-form-' + reviewIdToDelete).submit();
    }
}

// البحث والفلترة
const searchInput = document.getElementById('searchInput');
const ratingFilter = document.getElementById('ratingFilter');
const resetBtn = document.getElementById('resetBtn');

function applyFilters() {
    const search = searchInput.value;
    const rating = ratingFilter.value;
    let url = new URL(window.location.href);

    if (search) {
        url.searchParams.set('search', search);
    } else {
        url.searchParams.delete('search');
    }

    if (rating) {
        url.searchParams.set('rating', rating);
    } else {
        url.searchParams.delete('rating');
    }

    window.location.href = url.toString();
}

searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

ratingFilter.addEventListener('change', applyFilters);
resetBtn.addEventListener('click', function() {
    searchInput.value = '';
    ratingFilter.value = '';
    applyFilters();
});

// ربط زر التأكيد
document.getElementById('confirmDeleteBtn')?.addEventListener('click', confirmDelete);

// إغلاق المودال عند الضغط على Esc
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

// إغلاق المودال عند الضغط خارج المحتوى
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
}
    };
    </script>

@endsection
