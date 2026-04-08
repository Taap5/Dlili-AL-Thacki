
@extends('layouts.app')

@section('title', 'إدارة الجهات')

@section('content')
<style>
    /* منع انعكاس الأرقام في أرقام الاتصال */
.service-modal-details .info-box .info-value a,
.contact-value a,
[dir="ltr"],
.phone-number {
    direction: ltr !important;
    display: inline-block !important;
    unicode-bidi: embed !important;
}
    :root {
        --primary: #2f3e9e;
        --primary-light: #5a6fc9;
        --primary-dark: #1e2a6e;
        --danger: #dc3545;
        --success: #28a745;
        --warning: #ffc107;
        --text-dark: #1a2c3e;
        --text-muted: #6c757d;
        --bg-gray: #f8f9fa;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        position: relative;
        padding-right: 18px;
        margin: 0;
    }

    .page-title::before {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 24px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 4px;
    }

    .stats-badge {
        background: #f0f4ff;
        padding: 6px 16px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.8rem;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* شريط البحث والفلترة */
    .filters-bar {
        background: white;
        border-radius: 20px;
        padding: 16px 20px;
        margin-bottom: 24px;
        border: 1px solid var(--border-light);
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .search-input {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-input i {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }

    .search-input input {
        width: 100%;
        padding: 10px 38px 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 40px;
        font-size: 0.85rem;
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
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 40px;
        background: white;
        font-size: 0.85rem;
        cursor: pointer;
    }

    .reset-btn {
        background: #f0f4ff;
        border: none;
        border-radius: 40px;
        padding: 10px 20px;
        color: var(--primary);
        font-weight: 500;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .reset-btn:hover {
        background: var(--primary);
        color: white;
    }

    /* زر الإضافة */
    .btn-add {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 30px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s;
        margin-bottom: 20px;
    }

    .btn-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(47, 62, 158, 0.3);
    }

    /* تنبيهات */
    .alert-custom {
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
    }

    .alert-success-custom {
        background: #e8f5e9;
        color: #2e7d32;
        border-right: 4px solid #2e7d32;
    }

    /* جدول الجهات */
    .governments-table-wrapper {
        background: white;
        border-radius: 20px;
        overflow-x: auto;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-light);
    }

    .governments-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    .governments-table th {
        padding: 12px 16px;
        text-align: center;
        background: #fafbfc;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-light);
    }

    .governments-table td {
        padding: 12px 16px;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }

    .governments-table tr:last-child td {
        border-bottom: none;
    }

    .governments-table tr:hover td {
        background: #fafbfc;
    }

    .gov-name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
    }

    .category-badge {
        background: #f0f4ff;
        color: var(--primary);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }

    .images-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f0f4ff;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        color: var(--primary);
    }

    /* أزرار الإجراءات */
    .action-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-edit {
        background: transparent;
        border: 1px solid var(--primary);
        color: var(--primary);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-edit:hover {
        background: var(--primary);
        color: white;
    }

    .btn-delete {
        background: transparent;
        border: 1px solid var(--danger);
        color: var(--danger);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-delete:hover {
        background: var(--danger);
        color: white;
    }

    /* حالة عدم وجود بيانات */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 16px;
        background: #f0f2f5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon i {
        font-size: 28px;
        color: #9ca3af;
    }

    .empty-state p {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    /* الترقيم */
    .pagination-wrapper {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .page-link {
        padding: 6px 12px;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.8rem;
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

    /* تنسيقات المودالات والمحتوى الإضافي */
    .service-card {
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .service-card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-remove-service {
        background: #dc3545;
        color: white;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-remove-service:hover {
        background: #c82333;
        transform: scale(1.05);
    }

    .service-details-fields {
        padding-top: 12px;
        border-top: 1px dashed #dee2e6;
        margin-top: 10px;
    }

    .add-service-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        width: 100%;
        justify-content: center;
    }

    .add-service-btn:hover {
        background: #218838;
    }

    .services-dropdown {
        position: absolute;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        max-height: 250px;
        overflow-y: auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10001;
        margin-top: 5px;
    }

    .services-dropdown-item {
        padding: 10px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
        font-size: 14px;
    }

    .services-dropdown-item:hover {
        background-color: #f0f0f0;
    }

    #addServiceModal .modal-body {
        position: relative;
    }

    .img-preview {
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }

    .location-loading {
        color: var(--primary);
        font-size: 0.7rem;
        margin-top: 5px;
    }

    .location-success {
        color: var(--success);
        font-size: 0.7rem;
        margin-top: 5px;
    }

    .location-error {
        color: var(--danger);
        font-size: 0.7rem;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .admin-container {
            padding: 0 15px;
        }

        .page-title {
            font-size: 1.3rem;
        }

        .governments-table th,
        .governments-table td {
            padding: 8px 10px;
            font-size: 0.7rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 4px;
        }

        .btn-edit,
        .btn-delete {
            padding: 3px 8px;
            font-size: 0.65rem;
        }

        .col-3 {
            width: 50%;
        }
    }
    /* ===== تنسيقات المودالات ===== */
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
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0.2);
}

.modal-content-small {
    max-width: 500px;
}

.modal-header {
    padding: 18px 24px;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    border-radius: 28px 28px 0 0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.modal-header h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-dark);
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
    position: sticky;
    bottom: 0;
}

/* تنسيقات الحقول داخل المودال */
.modal .form-group {
    margin-bottom: 20px;
}

.modal .form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.85rem;
}

.modal .form-control,
.modal .form-select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: all 0.2s;
    background: white;
}

.modal .form-control:focus,
.modal .form-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
}

.modal .btn-secondary {
    background: #f0f4ff;
    border: none;
    border-radius: 40px;
    padding: 8px 20px;
    color: var(--primary);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.modal .btn-secondary:hover {
    background: #e5e9f5;
}

.modal .btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    border: none;
    border-radius: 40px;
    padding: 8px 20px;
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.modal .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(47, 62, 158, 0.3);
}

.modal .btn-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
    border-radius: 40px;
    padding: 8px 20px;
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.modal .btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.modal .btn-add {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 40px;
    cursor: pointer;
    font-weight: 500;
}

.modal .row {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 0;
}

.modal .col-md-6 {
    flex: 1;
    min-width: 200px;
}

.modal .col-3 {
    width: 25%;
    padding: 0 8px;
}

.modal textarea {
    resize: vertical;
    min-height: 80px;
}

.modal .text-muted {
    font-size: 0.7rem;
    color: #9ca3af;
}

/* تحسين للهواتف */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        max-width: 95%;
    }

    .modal .col-md-6 {
        min-width: 100%;
    }

    .modal .col-3 {
        width: 50%;
    }
}
</style>

<div class="admin-container py-5">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-building me-2"></i>
            إدارة الجهات
        </h1>
        <div class="stats-badge">
            <i class="fas fa-chart-line"></i>
            <span>إجمالي الجهات: {{ $governments->total() }}</span>
        </div>
    </div>

    <!-- شريط البحث والفلترة -->
    <div class="filters-bar">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="ابحث عن جهة..." value="{{ request('search') }}">
        </div>
        <div class="filter-select">
            <select id="categoryFilter">
                <option value="">كل التصنيفات</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button class="reset-btn" id="resetBtn">
            <i class="fas fa-undo-alt me-1"></i>
            إعادة تعيين
        </button>
    </div>

    <!-- زر الإضافة -->
    <button class="btn-add" onclick="openAddModal()">
        <i class="fas fa-plus"></i>
        إضافة جهة جديدة
    </button>

    <!-- تنبيهات -->
    @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- جدول الجهات -->
    <div class="governments-table-wrapper">
        <table class="governments-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>التصنيف</th>
                    <th>رقم الهاتف</th>
                    <th>ساعات العمل</th>
                    <th>الصور</th>
                    <th>الإجراءات</th>
                </tr>
                </thead>
            <tbody>
                @forelse($governments as $gov)
                     <tr>
                        <td>{{ $gov->id }}</td>
                        <td class="gov-name">{{ $gov->name }}</td>
                        <td>
                            @if($gov->category)
                                <span class="category-badge">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $gov->category->name }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $gov->contact_number ?? '-' }}</td>
                        <td>{{ $gov->work_hours ?? '-' }}</td>
                        <td>
                            @if($gov->images && count($gov->images) > 0)
                                <div class="images-badge">
                                    <i class="fas fa-image"></i>
                                    {{ count($gov->images) }} صورة
                                </div>
                            @else
                                <span class="text-muted">لا توجد</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick='editGovernment({{ json_encode($gov->load('services')) }})'>
                                    <i class="fas fa-edit"></i>
                                    <span>تعديل</span>
                                </button>
                                <button class="btn-delete" onclick='deleteGovernment({{ $gov->id }}, "{{ $gov->name }}")'>
                                    <i class="fas fa-trash-alt"></i>
                                    <span>حذف</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <p>لا توجد جهات مسجلة</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- الترقيم -->
    <div class="pagination-wrapper">
        {{ $governments->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Modal إضافة جهة -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <form id="addGovernmentForm" method="POST" action="{{ route('admin.governments.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h3>إضافة جهة جديدة</h3>
                <button type="button" onclick="closeModal('addModal')" style="background:none; border:none; font-size:24px;">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">اسم الجهة *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">البحث عن الموقع (اختياري)</label>
                    <input type="text" name="search_address" id="add_search_address" class="form-control" placeholder="مثال: مستشفى الكويت، صنعاء، اليمن">
                    <small class="text-muted">سيتم جلب الإحداثيات والعنوان التفصيلي تلقائياً</small>
                    <div id="add_location_hint" class="mt-1"></div>
                </div>

                <!-- حقول الموقع المخفية -->
                <input type="hidden" name="location_lat" id="add_location_lat" value="">
                <input type="hidden" name="location_long" id="add_location_long" value="">
                <input type="hidden" name="formatted_address" id="add_formatted_address" value="">

                <div class="form-group">
                    <label class="form-label">التصنيف *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">اختر التصنيف</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">ساعات العمل</label>
                            <input type="text" name="work_hours" class="form-control" placeholder="مثال: 8ص - 2م">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">صور الجهة</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" id="addImagesInput">
                    <small class="text-muted">يمكنك اختيار أكثر من صورة (jpg, png, gif)</small>
                    <div id="addImagePreview" class="row mt-2 g-2"></div>
                </div>

                <div class="form-group mt-4">
                    <label class="form-label fw-bold">الخدمات المقدمة</label>
                    <hr class="mt-1 mb-3">
                    <div id="addedServicesList" class="mb-3"></div>
                    <button type="button" class="add-service-btn" onclick="openAddServiceModal('add')">
                        <i class="fas fa-plus-circle"></i> إضافة خدمة جديدة
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('addModal')">إلغاء</button>
                <button type="submit" class="btn-add">حفظ الجهة</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal تعديل جهة -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3>تعديل الجهة</h3>
                <button type="button" onclick="closeModal('editModal')" style="background:none; border:none; font-size:24px;">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">اسم الجهة *</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">البحث عن الموقع (اختياري - لتحديث الموقع)</label>
                    <input type="text" name="search_address" id="edit_search_address" class="form-control" placeholder="مثال: مستشفى الكويت، صنعاء، اليمن">
                    <small class="text-muted">اتركه فارغاً للاحتفاظ بالموقع الحالي</small>
                    <div id="edit_location_hint" class="mt-1"></div>
                </div>

                <!-- حقول الموقع المخفية -->
                <input type="hidden" name="location_lat" id="edit_location_lat" value="">
                <input type="hidden" name="location_long" id="edit_location_long" value="">
                <input type="hidden" name="formatted_address" id="edit_formatted_address" value="">

                <div class="form-group">
                    <label class="form-label">التصنيف *</label>
                    <select name="category_id" id="edit_category" class="form-select" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">ساعات العمل</label>
                            <input type="text" name="work_hours" id="edit_hours" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group" id="currentImagesContainer">
                    <label class="form-label">الصور الحالية</label>
                    <div class="row g-2" id="currentImagesList"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">إضافة صور جديدة</label>
                    <input type="file" name="new_images[]" class="form-control" multiple accept="image/*" id="newImagesInput">
                    <small class="text-muted">يمكنك إضافة صور جديدة دون حذف الصور الحالية</small>
                    <div id="newImagePreview" class="row mt-2 g-2"></div>
                </div>

                <div class="form-group mt-4">
                    <label class="form-label fw-bold">الخدمات المقدمة</label>
                    <hr class="mt-1 mb-3">
                    <div id="editServicesList" class="mb-3"></div>
                    <button type="button" class="add-service-btn" onclick="openAddServiceModal('edit')">
                        <i class="fas fa-plus-circle"></i> إضافة خدمة جديدة
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('editModal')">إلغاء</button>
                <button type="submit" class="btn-add">تحديث</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal منبثق لإضافة خدمة جديدة -->
<div id="addServiceModal" class="modal">
    <div class="modal-content modal-content-small">
        <div class="modal-header">
            <h3>إضافة خدمة جديدة</h3>
            <button type="button" onclick="closeModal('addServiceModal')" style="background:none; border:none; font-size:24px;">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">البحث عن الخدمة *</label>
                <input type="text" id="serviceSearchInput" class="form-control"
                       placeholder="اكتب اسم الخدمة للبحث..."
                       autocomplete="off"
                       onkeyup="filterServices()"
                       onfocus="checkAndShowDropdown()">
                <div id="servicesDropdown" class="services-dropdown" style="display: none;"></div>
                <small class="text-muted">اكتب اسم الخدمة واختر من القائمة</small>
            </div>

            <input type="hidden" id="selectedServiceId" value="">
            <input type="hidden" id="selectedServiceName" value="">

            <div class="form-group">
                <label class="form-label">وصف الخدمة (لهذه الجهة)</label>
                <textarea id="serviceDescription" class="form-control" rows="2"></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">رقم الاتصال الخاص</label>
                        <input type="text" id="serviceContactNumber" class="form-control" placeholder="مثال: 01 234 567">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">ساعات العمل</label>
                        <input type="text" id="serviceWorkHours" class="form-control" placeholder="مثال: 8ص - 2م">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">الرسوم</label>
                <input type="text" id="servicePrice" class="form-control" placeholder="مثال: 5000 ريال">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeModal('addServiceModal')">إلغاء</button>
            <button type="button" class="btn-success" onclick="confirmAddService()">إضافة الخدمة</button>
        </div>
    </div>
</div>

<!-- Modal حذف جهة -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h3 style="color: #dc3545;">تأكيد الحذف</h3>
                <button type="button" onclick="closeModal('deleteModal')" style="background:none; border:none; font-size:24px;">&times;</button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف الجهة <strong id="deleteName"></strong>؟</p>
                <p class="text-muted">لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">إلغاء</button>
                <button type="submit" class="btn-danger">حذف</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentImagesData = [];
    let tempServices = [];
    let currentModalType = '';
    let allServicesList = @json($services);
    let locationFetchTimeout = null;

    // البحث والفلترة
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const resetBtn = document.getElementById('resetBtn');

    function applyFilters() {
        const search = searchInput.value;
        const category = categoryFilter.value;
        let url = new URL(window.location.href);

        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }

        if (category) {
            url.searchParams.set('category_id', category);
        } else {
            url.searchParams.delete('category_id');
        }

        window.location.href = url.toString();
    }

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });

    categoryFilter.addEventListener('change', applyFilters);

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilter.value = '';
        applyFilters();
    });

    // ==================== دوال جلب الموقع من API ====================
async function fetchLocationFromAddress(address, type = 'add') {
    const hintDiv = type === 'add' ? document.getElementById('add_location_hint') : document.getElementById('edit_location_hint');

    if (!address || address.trim() === '') {
        if (type === 'add') {
            document.getElementById('add_location_lat').value = '';
            document.getElementById('add_location_long').value = '';
            document.getElementById('add_formatted_address').value = '';
        } else {
            document.getElementById('edit_location_lat').value = '';
            document.getElementById('edit_location_long').value = '';
            document.getElementById('edit_formatted_address').value = '';
        }
        if (hintDiv) hintDiv.innerHTML = '';
        return;
    }

    if (hintDiv) {
        hintDiv.innerHTML = '<span class="location-loading"><i class="fas fa-spinner fa-spin"></i> جاري البحث عن الموقع...</span>';
    }

    try {
        const response = await fetch(`/api/get-location?address=${encodeURIComponent(address)}`);
        const data = await response.json();

        if (data.success && data.lat && data.lng) {
            if (type === 'add') {
                document.getElementById('add_location_lat').value = data.lat;
                document.getElementById('add_location_long').value = data.lng;
                document.getElementById('add_formatted_address').value = data.address || address;
            } else {
                document.getElementById('edit_location_lat').value = data.lat;
                document.getElementById('edit_location_long').value = data.lng;
                document.getElementById('edit_formatted_address').value = data.address || address;
            }

            if (hintDiv) {
                hintDiv.innerHTML = `<span class="location-success"><i class="fas fa-check-circle"></i> ✓ تم العثور على الموقع: ${(data.address || address).substring(0, 100)}</span>`;
                setTimeout(() => {
                    if (hintDiv && hintDiv.innerHTML.includes('تم العثور')) {
                        hintDiv.innerHTML = '';
                    }
                }, 5000);
            }
        } else {
            throw new Error(data.error || 'لم يتم العثور على الموقع');
        }
    } catch (error) {
        console.error('خطأ:', error);
        if (hintDiv) {
            hintDiv.innerHTML = '<span class="location-error"><i class="fas fa-exclamation-triangle"></i> لم يتم العثور على الموقع. جرب: صنعاء، اليمن أو مستشفى الثورة، صنعاء</span>';
            setTimeout(() => {
                if (hintDiv && hintDiv.innerHTML.includes('لم يتم العثور')) {
                    hintDiv.innerHTML = '';
                }
            }, 5000);
        }

        if (type === 'add') {
            document.getElementById('add_location_lat').value = '';
            document.getElementById('add_location_long').value = '';
            document.getElementById('add_formatted_address').value = '';
        } else {
            document.getElementById('edit_location_lat').value = '';
            document.getElementById('edit_location_long').value = '';
            document.getElementById('edit_formatted_address').value = '';
        }
    }
}

    // مراقبة التغييرات في حقل البحث عن الموقع
    function initLocationSearch() {
        const addSearchInput = document.getElementById('add_search_address');
        if (addSearchInput) {
            addSearchInput.addEventListener('input', function(e) {
                clearTimeout(locationFetchTimeout);
                locationFetchTimeout = setTimeout(() => {
                    fetchLocationFromAddress(e.target.value, 'add');
                }, 800);
            });
        }

        const editSearchInput = document.getElementById('edit_search_address');
        if (editSearchInput) {
            editSearchInput.addEventListener('input', function(e) {
                clearTimeout(locationFetchTimeout);
                locationFetchTimeout = setTimeout(() => {
                    fetchLocationFromAddress(e.target.value, 'edit');
                }, 800);
            });
        }
    }

    // دوال المودالات
    function openAddModal() {
        tempServices = [];
        renderAddedServices();
        // مسح حقول الموقع عند فتح المودال
        document.getElementById('add_location_lat').value = '';
        document.getElementById('add_location_long').value = '';
        document.getElementById('add_formatted_address').value = '';
        document.getElementById('add_search_address').value = '';
        const hintDiv = document.getElementById('add_location_hint');
        if (hintDiv) hintDiv.innerHTML = '';
        document.getElementById('addModal').classList.add('show');
    }

    function editGovernment(gov) {
        const categoryId = gov.category_id || gov.government_category_id || '';

        document.getElementById('edit_name').value = gov.name;
        document.getElementById('edit_description').value = gov.description || '';
        document.getElementById('edit_phone').value = gov.phone || '';
        document.getElementById('edit_category').value = categoryId;
        document.getElementById('edit_hours').value = gov.work_hours || '';
        document.getElementById('editForm').action = `/admin/governments/${gov.id}`;
        document.getElementById('edit_search_address').value = '';

        // تعبئة حقول الموقع المخفية من البيانات الموجودة
        document.getElementById('edit_location_lat').value = gov.location_lat || '';
        document.getElementById('edit_location_long').value = gov.location_long || '';
        document.getElementById('edit_formatted_address').value = gov.address || '';

        // عرض الموقع الحالي كتلميح
        const editHintDiv = document.getElementById('edit_location_hint');
        if (editHintDiv) {
            if (gov.address) {
                editHintDiv.innerHTML = `<span class="location-success"><i class="fas fa-info-circle"></i> 📍 الموقع الحالي: ${gov.address.substring(0, 100)}${gov.address.length > 100 ? '...' : ''}</span>`;
            } else {
                editHintDiv.innerHTML = '';
            }
        }

        currentImagesData = gov.images || [];
        const imagesList = document.getElementById('currentImagesList');
        imagesList.innerHTML = '';

        if (currentImagesData.length > 0) {
            currentImagesData.forEach((img, index) => {
                const col = document.createElement('div');
                col.className = 'col-3 position-relative';
                col.setAttribute('data-image-index', index);
                col.innerHTML = `
                    <img src="/storage/${img}" class="img-fluid img-preview" style="height: 100px; width: 100%; object-fit: cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" style="width: 24px; height: 24px; padding: 0;" onclick="removeImage(${index})">
                        <i class="fas fa-times fa-xs"></i>
                    </button>
                `;
                imagesList.appendChild(col);
            });
        } else {
            imagesList.innerHTML = '<div class="col-12 text-muted">لا توجد صور</div>';
        }

        const servicesList = document.getElementById('editServicesList');
        servicesList.innerHTML = '';
        const governmentServices = gov.services || [];
        if (governmentServices.length === 0) {
            servicesList.innerHTML = '<div class="alert alert-info">لا توجد خدمات مرتبطة بهذه الجهة</div>';
        } else {
            governmentServices.forEach(service => {
                const pivot = service.pivot || {};
                const serviceHtml = `
                    <div class="service-card card p-3 mb-3" data-service-id="${service.id}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0 fw-bold">${escapeHtml(service.name)}</h6>
                            <button type="button" class="btn-remove-service" onclick="removeEditService(${service.id})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="hidden" name="services[${service.id}][id]" value="${service.id}">
                        <div class="service-details-fields">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small">وصف الخدمة (لهذه الجهة)</label>
                                    <textarea name="services[${service.id}][description]" class="form-control form-control-sm" rows="2">${escapeHtml(pivot.description || '')}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label small">رقم الاتصال الخاص</label>
                                            <input type="text" name="services[${service.id}][contact_number]" class="form-control form-control-sm" value="${escapeHtml(pivot.contact_number || '')}" placeholder="مثال: 01 234 567">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">ساعات العمل</label>
                                            <input type="text" name="services[${service.id}][work_hours]" class="form-control form-control-sm" value="${escapeHtml(pivot.work_hours || '')}" placeholder="مثال: 8ص - 2م">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">الرسوم</label>
                                            <input type="text" name="services[${service.id}][price]" class="form-control form-control-sm" value="${escapeHtml(pivot.price || '')}" placeholder="مثال: 5000 ريال">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                servicesList.insertAdjacentHTML('beforeend', serviceHtml);
            });
        }

        document.getElementById('editModal').classList.add('show');
    }

    function deleteGovernment(id, name) {
        document.getElementById('deleteName').innerText = name;
        document.getElementById('deleteForm').action = `/admin/governments/${id}`;
        document.getElementById('deleteModal').classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
        if (modalId === 'addServiceModal') {
            document.getElementById('servicesDropdown').style.display = 'none';
        }
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }

    function removeImage(index) {
        if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
            let removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_images[]';
            removeInput.value = index;
            document.getElementById('editForm').appendChild(removeInput);

            const imageDiv = document.querySelector(`#currentImagesList [data-image-index="${index}"]`);
            if (imageDiv) imageDiv.remove();

            currentImagesData.splice(index, 1);

            document.querySelectorAll('#currentImagesList [data-image-index]').forEach((div, newIndex) => {
                div.setAttribute('data-image-index', newIndex);
            });
        }
    }

    function removeEditService(serviceId) {
        const serviceCard = document.querySelector(`#editServicesList .service-card[data-service-id="${serviceId}"]`);
        if (serviceCard) {
            serviceCard.remove();
        }
        const remainingServices = document.querySelectorAll('#editServicesList .service-card');
        if (remainingServices.length === 0) {
            document.getElementById('editServicesList').innerHTML = '<div class="alert alert-info">لا توجد خدمات مرتبطة بهذه الجهة</div>';
        }
    }

    // دوال الخدمات
    function filterServices() {
        const searchInput = document.getElementById('serviceSearchInput');
        const searchTerm = searchInput.value.toLowerCase().trim();
        const dropdown = document.getElementById('servicesDropdown');

        if (!searchTerm) {
            dropdown.style.display = 'none';
            return;
        }

        const filteredServices = allServicesList.filter(service =>
            service.name.toLowerCase().includes(searchTerm)
        );

        const inputRect = searchInput.getBoundingClientRect();
        const modalBody = searchInput.closest('.modal-body');
        const modalBodyRect = modalBody ? modalBody.getBoundingClientRect() : null;

        dropdown.style.position = 'absolute';
        if (modalBodyRect) {
            dropdown.style.top = (inputRect.bottom - modalBodyRect.top + 5) + 'px';
            dropdown.style.left = (inputRect.left - modalBodyRect.left) + 'px';
        } else {
            dropdown.style.top = (inputRect.bottom + window.scrollY + 5) + 'px';
            dropdown.style.left = (inputRect.left + window.scrollX) + 'px';
        }
        dropdown.style.width = inputRect.width + 'px';

        if (filteredServices.length === 0) {
            dropdown.innerHTML = '<div class="services-dropdown-item" style="color: #999;">لا توجد نتائج</div>';
            dropdown.style.display = 'block';
            return;
        }

        let html = '';
        filteredServices.forEach(service => {
            const name = service.name;
            const index = name.toLowerCase().indexOf(searchTerm);
            let highlightedName = name;
            if (index !== -1) {
                highlightedName = name.substring(0, index) +
                    '<strong style="color: #2f3e9e;">' + name.substring(index, index + searchTerm.length) + '</strong>' +
                    name.substring(index + searchTerm.length);
            }
            html += `
                <div class="services-dropdown-item" onclick="selectService(${service.id}, '${escapeHtml(service.name)}')">
                    ${highlightedName}
                </div>
            `;
        });

        dropdown.innerHTML = html;
        dropdown.style.display = 'block';
    }

    function selectService(id, name) {
        document.getElementById('serviceSearchInput').value = name;
        document.getElementById('selectedServiceId').value = id;
        document.getElementById('selectedServiceName').value = name;
        document.getElementById('servicesDropdown').style.display = 'none';
    }

    function checkAndShowDropdown() {
        const searchInput = document.getElementById('serviceSearchInput');
        if (searchInput.value.trim()) {
            filterServices();
        }
    }

    document.addEventListener('click', function(e) {
        const searchInput = document.getElementById('serviceSearchInput');
        const dropdown = document.getElementById('servicesDropdown');
        if (searchInput && dropdown) {
            if (e.target !== searchInput && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        }
    });

    function openAddServiceModal(type) {
        currentModalType = type;
        document.getElementById('serviceSearchInput').value = '';
        document.getElementById('selectedServiceId').value = '';
        document.getElementById('selectedServiceName').value = '';
        document.getElementById('serviceDescription').value = '';
        document.getElementById('serviceContactNumber').value = '';
        document.getElementById('serviceWorkHours').value = '';
        document.getElementById('servicePrice').value = '';
        document.getElementById('servicesDropdown').style.display = 'none';
        document.getElementById('addServiceModal').classList.add('show');
    }

    function confirmAddService() {
        const serviceId = document.getElementById('selectedServiceId').value;
        const serviceName = document.getElementById('selectedServiceName').value;

        if (!serviceId) {
            alert('الرجاء اختيار خدمة من القائمة');
            return;
        }

        const service = allServicesList.find(s => s.id == serviceId);
        if (!service) {
            alert('الخدمة غير موجودة');
            return;
        }

        const serviceData = {
            id: parseInt(serviceId),
            name: service.name,
            description: document.getElementById('serviceDescription').value,
            contact_number: document.getElementById('serviceContactNumber').value,
            work_hours: document.getElementById('serviceWorkHours').value,
            price: document.getElementById('servicePrice').value
        };

        if (currentModalType === 'add') {
            if (tempServices.some(s => s.id == serviceId)) {
                alert('هذه الخدمة مضافة بالفعل');
                return;
            }
            tempServices.push(serviceData);
            renderAddedServices();
        } else {
            const existingService = document.querySelector(`#editServicesList .service-card[data-service-id="${serviceId}"]`);
            if (existingService) {
                alert('هذه الخدمة مضافة بالفعل');
                return;
            }

            const container = document.getElementById('editServicesList');
            if (container.innerHTML.includes('لا توجد خدمات مرتبطة')) {
                container.innerHTML = '';
            }

            const serviceHtml = `
                <div class="service-card card p-3 mb-3" data-service-id="${serviceData.id}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0 fw-bold">${escapeHtml(serviceData.name)}</h6>
                        <button type="button" class="btn-remove-service" onclick="removeEditService(${serviceData.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <input type="hidden" name="services[${serviceData.id}][id]" value="${serviceData.id}">
                    <div class="service-details-fields">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small">وصف الخدمة (لهذه الجهة)</label>
                                <textarea name="services[${serviceData.id}][description]" class="form-control form-control-sm" rows="2">${escapeHtml(serviceData.description)}</textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small">رقم الاتصال الخاص</label>
                                        <input type="text" name="services[${serviceData.id}][contact_number]" class="form-control form-control-sm" value="${escapeHtml(serviceData.contact_number)}" placeholder="مثال: 01 234 567">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">ساعات العمل</label>
                                        <input type="text" name="services[${serviceData.id}][work_hours]" class="form-control form-control-sm" value="${escapeHtml(serviceData.work_hours)}" placeholder="مثال: 8ص - 2م">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">الرسوم</label>
                                        <input type="text" name="services[${serviceData.id}][price]" class="form-control form-control-sm" value="${escapeHtml(serviceData.price)}" placeholder="مثال: 5000 ريال">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', serviceHtml);
        }

        closeModal('addServiceModal');
    }

    function renderAddedServices() {
        const container = document.getElementById('addedServicesList');
        if (!container) return;

        if (tempServices.length === 0) {
            container.innerHTML = '<div class="alert alert-info">لا توجد خدمات مضافة حالياً</div>';
            return;
        }

        let html = '';
        tempServices.forEach((service) => {
            html += `
                <div class="service-card card p-3 mb-3" data-service-id="${service.id}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0 fw-bold">${escapeHtml(service.name)}</h6>
                        <button type="button" class="btn-remove-service" onclick="removeAddedService(${service.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <input type="hidden" name="services[${service.id}][id]" value="${service.id}">
                    <div class="service-details-fields">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small">وصف الخدمة (لهذه الجهة)</label>
                                <textarea name="services[${service.id}][description]" class="form-control form-control-sm" rows="2">${escapeHtml(service.description)}</textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small">رقم الاتصال الخاص</label>
                                        <input type="text" name="services[${service.id}][contact_number]" class="form-control form-control-sm" value="${escapeHtml(service.contact_number)}" placeholder="مثال: 01 234 567">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">ساعات العمل</label>
                                        <input type="text" name="services[${service.id}][work_hours]" class="form-control form-control-sm" value="${escapeHtml(service.work_hours)}" placeholder="مثال: 8ص - 2م">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">الرسوم</label>
                                        <input type="text" name="services[${service.id}][price]" class="form-control form-control-sm" value="${escapeHtml(service.price)}" placeholder="مثال: 5000 ريال">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    function removeAddedService(serviceId) {
        tempServices = tempServices.filter(s => s.id != serviceId);
        renderAddedServices();
    }

    // معاينة الصور
    document.getElementById('addImagesInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('addImagePreview');
        preview.innerHTML = '';
        const files = Array.from(e.target.files);
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const col = document.createElement('div');
                col.className = 'col-3';
                col.innerHTML = `<img src="${event.target.result}" class="img-fluid img-preview" style="height: 100px; object-fit: cover;">`;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });

    document.getElementById('newImagesInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('newImagePreview');
        preview.innerHTML = '';
        const files = Array.from(e.target.files);
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(event) {
                const col = document.createElement('div');
                col.className = 'col-3';
                col.innerHTML = `<img src="${event.target.result}" class="img-fluid img-preview" style="height: 100px; object-fit: cover;">`;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });

    // تهيئة مراقبة حقول الموقع عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        initLocationSearch();
    });


</script>
@push('scripts')
<script>
    // منع تكرار إدخالات الـ History بعد حفظ البيانات
    (function() {
        // التحقق من وجود رسالة نجاح (يعني تم حفظ البيانات)
        @if(session('success'))
            // استبدال الصفحة الحالية في التاريخ لمنع الرجوع إلى نموذج التعديل/الإضافة
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        @endif

        // عند إرسال أي نموذج، منع إضافة صفحة جديدة في التاريخ
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                // حفظ عنوان الصفحة الحالية قبل الإرسال
                sessionStorage.setItem('lastListPage', window.location.href);
            });
        });

        // إذا كنا قد عدنا من صفحة حفظ، نستبدل التاريخ
        if (sessionStorage.getItem('lastListPage') && window.location.href === sessionStorage.getItem('lastListPage')) {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
            sessionStorage.removeItem('lastListPage');
        }
    })();
</script>
@endpush
@endsection
