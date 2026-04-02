@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<style>
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
        margin-bottom: 32px;
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

    .stats-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
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

    .stats-badge.admin {
        background: linear-gradient(135deg, #fff0f0, #ffe0e0);
        color: var(--danger);
    }

    .stats-badge.user {
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        color: var(--primary);
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

    /* تبويبات */
    .tabs-container {
        display: flex;
        gap: 12px;
        margin-bottom: 28px;
        border-bottom: 2px solid var(--border-light);
        padding-bottom: 0;
    }

    .tab-btn {
        padding: 10px 24px;
        background: transparent;
        border: none;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        border-radius: 30px 30px 0 0;
    }

    .tab-btn i {
        margin-left: 8px;
    }

    .tab-btn:hover {
        color: var(--primary);
    }

    .tab-btn.active {
        color: var(--primary);
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        right: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
        border-radius: 3px;
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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

    .alert-danger-custom {
        background: #ffebee;
        color: #c62828;
        border-right: 4px solid #c62828;
    }

    /* جداول المستخدمين */
    .users-table-wrapper {
        background: white;
        border-radius: 20px;
        overflow-x: auto;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-light);
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    .users-table th {
        padding: 12px 16px;
        text-align: center;
        background: #fafbfc;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-light);
    }

    .users-table td {
        padding: 12px 16px;
        text-align: center;
        color: var(--text-muted);
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }

    .users-table tr:last-child td {
        border-bottom: none;
    }

    .users-table tr:hover td {
        background: #fafbfc;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #f0f4ff, #e8eaf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 14px;
        flex-shrink: 0;
    }

    .user-avatar.admin {
        background: linear-gradient(135deg, #ffe0e0, #ffcccc);
        color: var(--danger);
    }

    .user-details {
        text-align: right;
    }

    .user-name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
        margin-bottom: 2px;
    }

    .user-email {
        font-size: 0.65rem;
        color: #9ca3af;
    }

    .badge-admin {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }

    .badge-user {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
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

    /* المودالات */
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
        max-width: 450px;
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

    .form-select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.85rem;
        transition: all 0.2s;
        background: white;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(47, 62, 158, 0.1);
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

    @media (max-width: 768px) {
        .admin-container {
            padding: 0 15px;
        }

        .page-title {
            font-size: 1.3rem;
        }

        .tabs-container {
            gap: 4px;
        }

        .tab-btn {
            padding: 6px 14px;
            font-size: 0.75rem;
        }

        .users-table th,
        .users-table td {
            padding: 8px 10px;
            font-size: 0.7rem;
        }

        .user-info {
            flex-direction: column;
            gap: 4px;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .user-name {
            font-size: 0.75rem;
        }

        .user-email {
            font-size: 0.6rem;
        }

        .badge-admin,
        .badge-user {
            padding: 3px 8px;
            font-size: 0.6rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 4px;
        }

        .btn-edit,
        .btn-delete {
            padding: 3px 8px;
            font-size: 0.6rem;
        }
    }
</style>

<div class="admin-container py-5">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-users me-2"></i>
            إدارة المستخدمين
        </h1>
        <div class="stats-row">
            <div class="stats-badge admin">
                <i class="fas fa-user-shield"></i>
                <span>المسؤولين: {{ $users->filter(function($u) { return $u->hasRole('admin'); })->count() }}</span>
            </div>
            <div class="stats-badge user">
                <i class="fas fa-user"></i>
                <span>المستخدمين: {{ $users->filter(function($u) { return !$u->hasRole('admin'); })->count() }}</span>
            </div>
        </div>
    </div>

    <!-- شريط البحث والفلترة -->
    <div class="filters-bar">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="ابحث عن مستخدم بالاسم أو البريد..." value="{{ request('search') }}">
        </div>
        <div class="filter-select">
            <select id="roleFilter">
                <option value="">كل المستخدمين</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>مسؤولين</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>مستخدمين عاديين</option>
            </select>
        </div>
        <button class="reset-btn" id="resetBtn">
            <i class="fas fa-undo-alt me-1"></i>
            إعادة تعيين
        </button>
    </div>

    <!-- تنبيهات -->
    @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <i class="fas fa-check-circle fa-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-custom alert-danger-custom">
            <i class="fas fa-exclamation-circle fa-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- تبويبات -->
    <div class="tabs-container">
        <button class="tab-btn active" data-tab="all">
            <i class="fas fa-globe"></i>
            الكل
        </button>
        <button class="tab-btn" data-tab="admins">
            <i class="fas fa-user-shield"></i>
            المسؤولين
        </button>
        <button class="tab-btn" data-tab="users">
            <i class="fas fa-user"></i>
            المستخدمين العاديين
        </button>
    </div>

    <!-- تبويب الكل -->
    <div class="tab-content active" data-content="all">
        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الدور</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr data-user-id="{{ $user->id }}" data-user-role="{{ $user->hasRole('admin') ? 'admin' : 'user' }}">
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar {{ $user->hasRole('admin') ? 'admin' : '' }}">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                        @else
                                            <i class="fas {{ $user->hasRole('admin') ? 'fa-user-shield' : 'fa-user' }}"></i>
                                        @endif
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $user->user_name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                <span class="{{ $user->hasRole('admin') ? 'badge-admin' : 'badge-user' }}">
                                    {{ $user->hasRole('admin') ? 'مسؤول' : 'مستخدم' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('Y/m/d') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick='editUser({{ json_encode($user) }})'>
                                        <i class="fas fa-edit"></i>
                                        <span>تعديل</span>
                                    </button>
                                    @if($user->id !== Auth::id())
                                        <button class="btn-delete" onclick='deleteUser({{ $user->id }}, "{{ $user->user_name }}")'>
                                            <i class="fas fa-trash-alt"></i>
                                            <span>حذف</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <p>لا يوجد مستخدمين مسجلين</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">
            {{ $users->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- تبويب المسؤولين -->
    <div class="tab-content" data-content="admins">
        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الدور</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @php $admins = $users->filter(function($u) { return $u->hasRole('admin'); }); @endphp
                    @forelse($admins as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar admin">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                        @else
                                            <i class="fas fa-user-shield"></i>
                                        @endif
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $user->user_name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td><span class="badge-admin">مسؤول</span></td>
                            <td>{{ $user->created_at->format('Y/m/d') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick='editUser({{ json_encode($user) }})'>
                                        <i class="fas fa-edit"></i>
                                        <span>تعديل</span>
                                    </button>
                                    @if($user->id !== Auth::id())
                                        <button class="btn-delete" onclick='deleteUser({{ $user->id }}, "{{ $user->user_name }}")'>
                                            <i class="fas fa-trash-alt"></i>
                                            <span>حذف</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <p>لا يوجد مسؤولين</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- تبويب المستخدمين العاديين -->
    <div class="tab-content" data-content="users">
        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الدور</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @php $regularUsers = $users->filter(function($u) { return !$u->hasRole('admin'); }); @endphp
                    @forelse($regularUsers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $user->user_name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td><span class="badge-user">مستخدم</span></td>
                            <td>{{ $user->created_at->format('Y/m/d') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick='editUser({{ json_encode($user) }})'>
                                        <i class="fas fa-edit"></i>
                                        <span>تعديل</span>
                                    </button>
                                    @if($user->id !== Auth::id())
                                        <button class="btn-delete" onclick='deleteUser({{ $user->id }}, "{{ $user->user_name }}")'>
                                            <i class="fas fa-trash-alt"></i>
                                            <span>حذف</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <p>لا يوجد مستخدمين عاديين</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal تعديل دور المستخدم -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h3>
                    <i class="fas fa-user-edit me-2"></i>
                    تعديل دور المستخدم
                </h3>
                <button type="button" onclick="closeModal('editModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">
                    تعديل دور المستخدم:
                    <strong class="fw-bold" id="editUserName"></strong>
                </p>
                <div class="form-group">
                    <label class="form-label">الدور</label>
                    <select name="role" class="form-select" id="editUserRole">
                        <option value="registered">مستخدم عادي</option>
                        <option value="admin">مسؤول</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('editModal')">إلغاء</button>
                <button type="submit" class="btn-primary">تحديث</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal حذف مستخدم -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h3 style="color: var(--danger);">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    تأكيد الحذف
                </h3>
                <button type="button" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p class="text-center">
                    هل أنت متأكد من حذف المستخدم
                    <strong class="fw-bold" id="deleteUserName"></strong>؟
                </p>
                <p class="text-muted text-center small">لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">إلغاء</button>
                <button type="submit" class="btn-danger">حذف</button>
            </div>
        </form>
    </div>
</div>

<script>
    // البحث والفلترة
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const resetBtn = document.getElementById('resetBtn');

    function applyFilters() {
        const search = searchInput.value;
        const role = roleFilter.value;
        let url = new URL(window.location.href);

        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }

        if (role) {
            url.searchParams.set('role', role);
        } else {
            url.searchParams.delete('role');
        }

        window.location.href = url.toString();
    }

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });

    roleFilter.addEventListener('change', applyFilters);

    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        roleFilter.value = '';
        applyFilters();
    });

    // التبويبات
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;

            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelector(`.tab-content[data-content="${tab}"]`).classList.add('active');
        });
    });

    function editUser(user) {
        document.getElementById('editUserName').innerText = user.user_name;
        document.getElementById('editUserRole').value = user.hasRole ? (user.hasRole('admin') ? 'admin' : 'registered') : 'registered';
        document.getElementById('editForm').action = `/admin/users/${user.id}/role`;
        document.getElementById('editModal').classList.add('show');
    }

    function deleteUser(id, name) {
        document.getElementById('deleteUserName').innerText = name;
        document.getElementById('deleteForm').action = `/admin/users/${id}`;
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
</script>
@endsection
