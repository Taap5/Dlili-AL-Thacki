@extends('layouts.app')

@section('title', 'إدارة الجهات')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* تنسيقات خاصة بصفحة إدارة الجهات */
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

    .btn-add {
        background-color: #2f3e9e;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-add:hover {
        background-color: #1e2a6e;
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

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
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
        max-width: 700px;
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

    .form-control, .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #2f3e9e;
    }

    .btn-secondary {
        background: #6c757d;
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

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }

    .col-md-6 {
        flex: 1;
        min-width: 200px;
    }

    .col-3 {
        width: 25%;
        padding: 0 8px;
    }

    .col-12 {
        width: 100%;
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

    .mt-2 {
        margin-top: 0.5rem;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .g-2 {
        gap: 0.5rem;
    }

    .img-preview {
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }

    .position-relative {
        position: relative;
    }

    .end-0 {
        right: 0;
    }

    .top-0 {
        top: 0;
    }

    .m-1 {
        margin: 0.25rem;
    }

    .rounded-circle {
        border-radius: 50%;
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
        .col-3 {
            width: 50%;
        }
    }
</style>

<div class="container py-5">
    <div class="admin-header">
        <h2 class="admin-title">
            <i class="fas fa-building"></i>
            إدارة الجهات
        </h2>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> إضافة جهة جديدة
        </button>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="admin-card">
        <div class="table-responsive">
            <table class="admin-table">
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
                            <td>{{ $gov->name }}</td>
                            <td>{{ $gov->category->name ?? 'غير مصنف' }}</td>
                            <td>{{ $gov->phone ?? '-' }}</td>
                            <td>{{ $gov->work_hours ?? '-' }}</td>
                            <td>
                                @if($gov->images && count($gov->images) > 0)
                                    <span class="badge bg-primary">{{ count($gov->images) }} صورة</span>
                                @else
                                    <span class="badge bg-secondary">لا توجد</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn-edit" onclick='editGovernment({{ json_encode($gov) }})'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" onclick='deleteGovernment({{ $gov->id }}, "{{ $gov->name }}")'>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">لا توجد جهات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal إضافة جهة -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <form method="POST" action="{{ route('admin.governments.store') }}" enctype="multipart/form-data">
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

                <!-- حقل البحث عن الموقع -->
                <div class="form-group">
                    <label class="form-label">البحث عن الموقع (اختياري)</label>
                    <input type="text" name="search_address" class="form-control" placeholder="مثال: مستشفى الكويت، صنعاء، اليمن">
                    <small class="text-muted">سيتم جلب الإحداثيات والعنوان التفصيلي تلقائياً من الخريطة</small>
                </div>

                <div class="form-group">
                    <label class="form-label">التصنيف *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">اختر التصنيف</option>
                        @foreach($categories as $cat)
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

                <!-- قسم الصور -->
                <div class="form-group">
                    <label class="form-label">صور الجهة</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" id="imagesInput">
                    <small class="text-muted">يمكنك اختيار أكثر من صورة (jpg, png, gif)</small>
                    <div id="imagePreview" class="row mt-2 g-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('addModal')">إلغاء</button>
                <button type="submit" class="btn-add">حفظ</button>
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

                <!-- حقل البحث عن الموقع (لتحديث الموقع) -->
                <div class="form-group">
                    <label class="form-label">البحث عن الموقع (اختياري - لتحديث الموقع)</label>
                    <input type="text" name="search_address" id="edit_search_address" class="form-control" placeholder="مثال: مستشفى الكويت، صنعاء، اليمن">
                    <small class="text-muted">اتركه فارغاً للاحتفاظ بالموقع الحالي، أو أدخل عنواناً جديداً لتحديث الإحداثيات والعنوان</small>
                </div>

                <div class="form-group">
                    <label class="form-label">التصنيف *</label>
                    <select name="category_id" id="edit_category" class="form-select" required>
                        @foreach($categories as $cat)
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

                <!-- قسم الصور الحالية -->
                <div class="form-group" id="currentImagesContainer">
                    <label class="form-label">الصور الحالية</label>
                    <div class="row g-2" id="currentImagesList">
                        <!-- سيتم ملؤها بواسطة JavaScript -->
                    </div>
                </div>

                <!-- قسم إضافة صور جديدة -->
                <div class="form-group">
                    <label class="form-label">إضافة صور جديدة</label>
                    <input type="file" name="new_images[]" class="form-control" multiple accept="image/*" id="newImagesInput">
                    <small class="text-muted">يمكنك إضافة صور جديدة دون حذف الصور الحالية</small>
                    <div id="newImagePreview" class="row mt-2 g-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('editModal')">إلغاء</button>
                <button type="submit" class="btn-add">تحديث</button>
            </div>
        </form>
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

    function openAddModal() {
        document.getElementById('addModal').classList.add('show');
    }

    function editGovernment(gov) {
        document.getElementById('edit_name').value = gov.name;
        document.getElementById('edit_description').value = gov.description || '';
        document.getElementById('edit_phone').value = gov.phone || '';
        document.getElementById('edit_category').value = gov.category_id;
        document.getElementById('edit_hours').value = gov.work_hours || '';
        document.getElementById('editForm').action = `/admin/governments/${gov.id}`;

        // إعادة تعيين حقل البحث
        document.getElementById('edit_search_address').value = '';

        // عرض الصور الحالية
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

        document.getElementById('editModal').classList.add('show');
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

    function deleteGovernment(id, name) {
        document.getElementById('deleteName').innerText = name;
        document.getElementById('deleteForm').action = `/admin/governments/${id}`;
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

    // معاينة الصور للإضافة
    document.getElementById('imagesInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
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

    // معاينة الصور الجديدة للتعديل
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
</script>
@endsection
