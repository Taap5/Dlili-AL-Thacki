@extends('layouts.app')

@section('title', 'إدارة الخدمات')

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

        /* جدول الخدمات */
        .services-table-wrapper {
            background: white;
            border-radius: 20px;
            overflow-x: auto;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        .services-table th {
            padding: 12px 16px;
            text-align: center;
            background: #fafbfc;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.8rem;
            border-bottom: 1px solid var(--border-light);
        }

        .services-table td {
            padding: 12px 16px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.8rem;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
        }

        .services-table tr:last-child td {
            border-bottom: none;
        }

        .services-table tr:hover td {
            background: #fafbfc;
        }

        .service-info {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }

        .service-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 14px;
            flex-shrink: 0;
        }

        .service-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.85rem;
            text-align: right;
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

        .service-description {
            max-width: 220px;
            text-align: right;
            line-height: 1.4;
            font-size: 0.75rem;
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

        /* تنسيقات المودالات */
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
            border-radius: 24px;
            width: 90%;
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-header button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #9ca3af;
            transition: color 0.2s;
        }

        .modal-header button:hover {
            color: var(--danger);
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 16px 20px 20px;
            border-top: 1px solid var(--border-light);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.8rem;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .btn-secondary {
            background: #f0f4ff;
            border: none;
            border-radius: 30px;
            padding: 8px 20px;
            color: var(--primary);
            font-weight: 500;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #e5e9f5;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: none;
            border-radius: 30px;
            padding: 8px 20px;
            color: white;
            font-weight: 500;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(47, 62, 158, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            border-radius: 30px;
            padding: 8px 20px;
            color: white;
            font-weight: 500;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .images-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview-item {
            position: relative;
            width: 70px;
            height: 70px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-light);
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-remove-btn {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 20px;
            height: 20px;
            background: rgba(220, 53, 69, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 9px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .image-remove-btn:hover {
            background: var(--danger);
        }

        .text-muted {
            font-size: 0.7rem;
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 0 15px;
            }

            .page-title {
                font-size: 1.3rem;
            }

            .services-table th,
            .services-table td {
                padding: 8px 10px;
                font-size: 0.7rem;
            }

            .service-info {
                flex-direction: column;
                gap: 4px;
            }

            .service-icon {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }

            .service-name {
                font-size: 0.75rem;
            }

            .service-description {
                max-width: 120px;
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
        }
    </style>

    <div class="admin-container py-5">
        <x-admin-breadcrumb :items="[['name' => 'إدارة الخدمات']]" />
        <!-- رأس الصفحة -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-concierge-bell me-2"></i>
                إدارة الخدمات
            </h1>
            <div class="stats-badge">
                <i class="fas fa-chart-line"></i>
                <span>إجمالي الخدمات: {{ $services->total() }}</span>
            </div>
        </div>

        <!-- شريط البحث والفلترة -->
        <div class="filters-bar">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="ابحث عن خدمة..." value="{{ request('search') }}">
            </div>
            <div class="filter-select">
                <select id="categoryFilter">
                    <option value="">كل التصنيفات</option>
                    @foreach ($categories as $cat)
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
            إضافة خدمة جديدة
        </button>

        <!-- تنبيهات -->
        @if (session('success'))
            <div class="alert-custom alert-success-custom">
                <i class="fas fa-check-circle fa-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- جدول الخدمات -->
        <div class="services-table-wrapper">
            <table class="services-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الخدمة</th>
                        <th>التصنيف</th>
                        <th>الوصف</th>
                        <th>الصور</th>
                        <th>الأيقونة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>
                                <div class="service-info">
                                    <div class="service-icon">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                    <div class="service-name">
                                        {{ $service->name }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($service->category)
                                    <span class="category-badge">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $service->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="service-description">
                                {{ Str::limit($service->description, 60) ?: '-' }}
                            </td>
                            <td>
                                @if ($service->images && count($service->images) > 0)
                                    <div class="images-badge">
                                        <i class="fas fa-image"></i>
                                        {{ count($service->images) }} صورة
                                    </div>
                                @else
                                    <span class="text-muted">لا توجد</span>
                                @endif
                            </td>
                            <td>
                                @if ($service->icon_image)
                                    <img src="{{ asset('storage/' . $service->icon_image) }}" alt="{{ $service->name }}"
                                        style="width: 40px; height: 40px; object-fit: contain; border-radius: 8px;">
                                @else
                                    <div class="service-icon">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick='editService({{ json_encode($service) }})'>
                                        <i class="fas fa-edit"></i>
                                        <span>تعديل</span>
                                    </button>
                                    <button class="btn-delete"
                                        onclick='deleteService({{ $service->id }}, "{{ $service->name }}")'>
                                        <i class="fas fa-trash-alt"></i>
                                        <span>حذف</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-concierge-bell"></i>
                                </div>
                                <p>لا توجد خدمات مسجلة</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- الترقيم -->
        <div class="pagination-wrapper">
            {{ $services->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- Modal إضافة خدمة -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h3>
                        <i class="fas fa-plus-circle"></i>
                        إضافة خدمة جديدة
                    </h3>
                    <button type="button" onclick="closeModal('addModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">المجموعة (اختياري)</label>
                                <input type="text" name="group_name" id="group_name" class="form-control"
                                    placeholder="مثال: عيادات خارجية, طوارئ">
                                <small class="text-muted">اتركه فارغاً للخدمات المستقلة</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">اسم الخدمة *</label>
                                <input type="text" name="service_name" id="service_name" class="form-control" required
                                    placeholder="مثال: باطنية, أطفال">
                            </div>
                        </div>
                    </div>


                    <!-- معاينة الاسم النهائي -->
                    <div id="previewBox" class="alert alert-info"
                        style="display: none; font-size: 12px; padding: 8px 12px; margin-bottom: 16px;">
                        <i class="fas fa-eye"></i>
                        <strong>الاسم النهائي:</strong>
                        <code id="finalNamePreview"></code>
                    </div>
                    <div class="form-group">
                        <label class="form-label">التصنيف</label>
                        <select name="category_id" class="form-select">
                            <option value="">بدون تصنيف</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="وصف الخدمة..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">صور الخدمة</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*"
                            id="imagesInput">
                        <div class="text-muted mt-1">يمكنك اختيار أكثر من صورة (jpg, png, gif)</div>
                        <div id="imagePreview" class="images-preview"></div>
                    </div>
                </div>
                <!-- أيقونة الخدمة -->
                <div class="form-group">
                    <label class="form-label">أيقونة الخدمة (اختياري)</label>
                    <input type="file" name="icon_image" id="icon_image" class="form-control" accept="image/*">
                    <div class="text-muted mt-1">صورة مميزة للخدمة (يفضل 80x80 بكسل، PNG أو SVG)</div>
                    <div id="iconPreviewAdd" class="mt-2" style="display: none;">
                        <img id="iconPreviewImgAdd" src=""
                            style="width: 60px; height: 60px; object-fit: contain; border: 1px solid #ddd; border-radius: 10px; padding: 5px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('addModal')">إلغاء</button>
                    <button type="submit" class="btn-primary">حفظ الخدمة</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal تعديل خدمة -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h3>
                        <i class="fas fa-edit"></i>
                        تعديل الخدمة
                    </h3>
                    <button type="button" onclick="closeModal('editModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">المجموعة</label>
                                <input type="text" name="group_name" id="edit_group_name" class="form-control"
                                    placeholder="مثال: عيادات خارجية">
                                <small class="text-muted">اتركه فارغاً للخدمات المستقلة</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">اسم الخدمة *</label>
                                <input type="text" name="service_name" id="edit_service_name" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div id="editPreviewBox" class="alert alert-info"
                        style="display: none; font-size: 12px; padding: 8px 12px; margin-bottom: 16px;">
                        <i class="fas fa-eye"></i>
                        <strong>الاسم النهائي:</strong>
                        <code id="editFinalNamePreview"></code>
                    </div>
                    <div class="form-group">
                        <label class="form-label">التصنيف</label>
                        <select name="category_id" id="edit_category" class="form-select">
                            <option value="">بدون تصنيف</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">الصور الحالية</label>
                        <div id="currentImagesList" class="images-preview"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">إضافة صور جديدة</label>
                        <input type="file" name="new_images[]" class="form-control" multiple accept="image/*"
                            id="newImagesInput">
                        <div class="text-muted mt-1">يمكنك إضافة صور جديدة دون حذف الصور الحالية</div>
                        <div id="newImagePreview" class="images-preview"></div>
                    </div>
                </div>
                <!-- الأيقونة الحالية -->
                <div class="form-group">
                    <label class="form-label">أيقونة الخدمة الحالية</label>
                    <div id="currentIconContainer" class="mb-2">
                        <!-- ستظهر هنا الصورة الحالية -->
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="remove_icon" id="remove_icon" class="form-check-input"
                            value="1">
                        <label for="remove_icon" class="form-check-label text-danger">حذف الأيقونة الحالية</label>
                    </div>
                    <label class="form-label mt-2">تغيير الأيقونة</label>
                    <input type="file" name="icon_image" id="edit_icon_image" class="form-control" accept="image/*">
                    <div class="text-muted mt-1">ارفع صورة جديدة لتحل محل القديمة</div>
                    <div id="iconPreviewEdit" class="mt-2" style="display: none;">
                        <img id="iconPreviewImgEdit" src=""
                            style="width: 60px; height: 60px; object-fit: contain; border: 1px solid #ddd; border-radius: 10px; padding: 5px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('editModal')">إلغاء</button>
                    <button type="submit" class="btn-primary">تحديث الخدمة</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal حذف خدمة -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h3 style="color: var(--danger);">
                        <i class="fas fa-exclamation-triangle"></i>
                        تأكيد الحذف
                    </h3>
                    <button type="button" onclick="closeModal('deleteModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        هل أنت متأكد من حذف الخدمة
                        <strong class="fw-bold" id="deleteName"></strong>؟
                    </p>
                    <div class="text-muted text-center">لا يمكن التراجع عن هذا الإجراء.</div>
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

        // دوال المودالات
        function openAddModal() {
            document.getElementById('addModal').classList.add('show');
        }

        function editService(service) {
            // تفكيك الاسم إلى مجموعة وخدمة فرعية
            let groupName = '';
            let serviceName = service.name;

            if (service.name && service.name.includes('-')) {
                const parts = service.name.split('-');
                groupName = parts[0].trim();
                serviceName = parts.slice(1).join('-').trim();
            }

            // تعيين القيم في الحقول الجديدة
            document.getElementById('edit_group_name').value = groupName;
            document.getElementById('edit_service_name').value = serviceName;
            document.getElementById('edit_description').value = service.description || '';
            document.getElementById('edit_category').value = service.government_category_id || '';
            // عرض الأيقونة الحالية
            const currentIconContainer = document.getElementById('currentIconContainer');
            if (service.icon_image) {
                currentIconContainer.innerHTML = `
        <div class="image-preview-item" style="width: 80px; height: 80px;">
            <img src="/storage/${service.icon_image}" alt="أيقونة الخدمة" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
    `;
            } else {
                currentIconContainer.innerHTML = '<div class="text-muted">لا توجد أيقونة</div>';
            }

            // إعادة تعيين حقل رفع الأيقونة
            document.getElementById('edit_icon_image').value = '';
            document.getElementById('iconPreviewEdit').style.display = 'none';
            document.getElementById('remove_icon').checked = false;
            document.getElementById('editForm').action = `/admin/services/${service.id}`;

            // تحديث المعاينة
            updateEditPreview();

            // معالجة الصور
            currentImagesData = service.images || [];
            const imagesList = document.getElementById('currentImagesList');
            imagesList.innerHTML = '';

            if (currentImagesData.length > 0) {
                currentImagesData.forEach((img, index) => {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item';
                    div.setAttribute('data-image-index', index);
                    div.innerHTML = `
                <img src="/storage/${img}" alt="صورة الخدمة">
                <div class="image-remove-btn" onclick="removeImage(${index})">
                    <i class="fas fa-times"></i>
                </div>
            `;
                    imagesList.appendChild(div);
                });
            } else {
                imagesList.innerHTML = '<div class="text-muted">لا توجد صور</div>';
            }

            document.getElementById('editModal').classList.add('show');
        }

        // دالة معاينة الاسم للتعديل
        function updateEditPreview() {
            const groupName = document.getElementById('edit_group_name')?.value || '';
            const serviceName = document.getElementById('edit_service_name')?.value || '';
            const previewBox = document.getElementById('editPreviewBox');
            const finalNameSpan = document.getElementById('editFinalNamePreview');

            if (serviceName) {
                let finalName = serviceName;
                if (groupName.trim() !== '') {
                    finalName = groupName.trim() + ' - ' + serviceName.trim();
                }
                finalNameSpan.innerText = finalName;
                previewBox.style.display = 'block';
            } else {
                previewBox.style.display = 'none';
            }
        }

        // ربط المستمعات عند فتح مودال التعديل
        document.getElementById('editModal').addEventListener('shown.bs.modal', function() {
            const groupInput = document.getElementById('edit_group_name');
            const serviceInput = document.getElementById('edit_service_name');

            if (groupInput && serviceInput) {
                groupInput.addEventListener('input', updateEditPreview);
                serviceInput.addEventListener('input', updateEditPreview);
                updateEditPreview();
            }
        });

        // عند إغلاق مودال التعديل، نظف
        document.getElementById('editModal').addEventListener('hidden.bs.modal', function() {
            const groupInput = document.getElementById('edit_group_name');
            const serviceInput = document.getElementById('edit_service_name');
            if (groupInput) groupInput.value = '';
            if (serviceInput) serviceInput.value = '';
            document.getElementById('editPreviewBox').style.display = 'none';
        });

        function deleteService(id, name) {
            document.getElementById('deleteName').innerText = name;
            document.getElementById('deleteForm').action = `/admin/services/${id}`;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }

        function removeImage(index) {
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                let removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.name = 'remove_images[]';
                removeInput.value = index;
                document.getElementById('editForm').appendChild(removeInput);

                const imageDiv = document.querySelector(
                    `#currentImagesList .image-preview-item[data-image-index="${index}"]`);
                if (imageDiv) imageDiv.remove();

                currentImagesData.splice(index, 1);

                document.querySelectorAll('#currentImagesList .image-preview-item').forEach((div, newIndex) => {
                    div.setAttribute('data-image-index', newIndex);
                });
            }
        }

        // معاينة الصور للإضافة
        document.getElementById('imagesInput')?.addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            const files = Array.from(e.target.files);

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item';
                    div.innerHTML = `<img src="${event.target.result}" alt="معاينة">`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });

        // معاينة الصور الجديدة للتعديل
        document.getElementById('newImagesInput')?.addEventListener('change', function(e) {
            const preview = document.getElementById('newImagePreview');
            preview.innerHTML = '';
            const files = Array.from(e.target.files);

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item';
                    div.innerHTML = `<img src="${event.target.result}" alt="معاينة">`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
        // دالة معاينة الاسم النهائي (للمودال)
        function updateNamePreview() {
            const groupName = document.getElementById('group_name')?.value || '';
            const serviceName = document.getElementById('service_name')?.value || '';
            const previewBox = document.getElementById('previewBox');
            const finalNameSpan = document.getElementById('finalNamePreview');

            if (serviceName) {
                let finalName = serviceName;
                if (groupName.trim() !== '') {
                    finalName = groupName.trim() + ' - ' + serviceName.trim();
                }
                finalNameSpan.innerText = finalName;
                previewBox.style.display = 'block';
            } else {
                previewBox.style.display = 'none';
            }
        }

        // ربط المستمعات عند فتح مودال الإضافة
        document.getElementById('addModal').addEventListener('shown.bs.modal', function() {
            const groupInput = document.getElementById('group_name');
            const serviceInput = document.getElementById('service_name');

            if (groupInput && serviceInput) {
                groupInput.addEventListener('input', updateNamePreview);
                serviceInput.addEventListener('input', updateNamePreview);
                updateNamePreview();
            }
        });

        // عند إغلاق المودال، نظف الحقول
        document.getElementById('addModal').addEventListener('hidden.bs.modal', function() {
            const groupInput = document.getElementById('group_name');
            const serviceInput = document.getElementById('service_name');
            if (groupInput) groupInput.value = '';
            if (serviceInput) serviceInput.value = '';
            document.getElementById('previewBox').style.display = 'none';
        });
        // معاينة أيقونة الإضافة
        document.getElementById('icon_image')?.addEventListener('change', function(e) {
            const previewDiv = document.getElementById('iconPreviewAdd');
            const previewImg = document.getElementById('iconPreviewImgAdd');

            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                previewDiv.style.display = 'none';
                previewImg.src = '';
            }
        });

        // معاينة أيقونة التعديل
        document.getElementById('edit_icon_image')?.addEventListener('change', function(e) {
            const previewDiv = document.getElementById('iconPreviewEdit');
            const previewImg = document.getElementById('iconPreviewImgEdit');

            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                previewDiv.style.display = 'none';
                previewImg.src = '';
            }
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
