@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('content')
<style>
    /* تنسيقات خاصة بصفحة إدارة المستخدمين */
    main {
        padding-top: 100px !important;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .admin-title {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-title i {
        color: #2f3e9e;
    }

    .btn-edit {
        background: none;
        border: 1px solid #2f3e9e;
        color: #2f3e9e;
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        margin: 0 2px;
    }

    .btn-edit:hover {
        background: #2f3e9e;
        color: white;
    }

    .btn-delete {
        background: none;
        border: 1px solid #dc3545;
        color: #dc3545;
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        margin: 0 2px;
    }

    .btn-delete:hover {
        background: #dc3545;
        color: white;
    }

    .admin-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th,
    .admin-table td {
        padding: 12px 16px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .admin-table th {
        background: #f8f9fa;
        font-weight: 600;
    }

    .admin-table tr:hover {
        background: #fafafa;
    }

    .badge-admin {
        background: #dc3545;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .badge-user {
        background: #2f3e9e;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 400px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 16px 20px;
        border-top: 1px solid #eee;
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
        font-weight: 500;
    }

    .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-primary {
        background: #2f3e9e;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: #6c757d;
    }

    .py-5 {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .admin-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .admin-table th, .admin-table td {
            padding: 8px 10px;
            font-size: 12px;
        }
    }
</style>

<div class="container py-5">
    <div class="admin-header">
        <h2 class="admin-title">
            <i class="fas fa-users"></i>
            إدارة المستخدمين
        </h2>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    的人
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الدور</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->user_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                <span class="{{ $user->hasRole('admin') ? 'badge-admin' : 'badge-user' }}">
                                    {{ $user->hasRole('admin') ? 'مسؤول' : 'مستخدم' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                <button class="btn-edit" onclick='editUser({{ json_encode($user) }})'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($user->id !== Auth::id())
                                    <button class="btn-delete" onclick='deleteUser({{ $user->id }}, "{{ $user->user_name }}")'>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">لا يوجد مستخدمين</td>
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
                <h3>تعديل دور المستخدم</h3>
                <button type="button" onclick="closeModal('editModal')" style="background:none; border:none; font-size:24px;">&times;</button>
            </div>
            <div class="modal-body">
                <p>تعديل دور المستخدم: <strong id="editUserName"></strong></p>
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
                <h3 style="color: #dc3545;">تأكيد الحذف</h3>
                <button type="button" onclick="closeModal('deleteModal')" style="background:none; border:none; font-size:24px;">&times;</button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف المستخدم <strong id="deleteUserName"></strong>؟</p>
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
    function editUser(user) {
        document.getElementById('editUserName').innerText = user.user_name;
        document.getElementById('editUserRole').value = user.hasRole('admin') ? 'admin' : 'registered';
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
