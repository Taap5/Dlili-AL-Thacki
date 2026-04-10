@extends('layouts.app')

@section('title', 'إدارة عروض ' . ($government->name ?? ''))

@section('content')
    <div class="container py-4">
        <x-admin-breadcrumb :items="[
    ['name' => 'إدارة الجهات', 'url' => route('admin.governments')],
    ['name' => 'عروض ' . $government->name]
]" />
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-gift text-primary me-2"></i>
                عروض ومميزات: {{ $government->name ?? '' }}
            </h1>
            <a href="{{ route('admin.governments') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-1"></i> رجوع للجهات
            </a>
        </div>

        {{-- رسائل النجاح --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- نموذج إضافة عرض جديد --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-1"></i>
                    إضافة عرض جديد
                </h5>
            </div>
            <div class="card-body">
               <form method="POST" action="{{ route('admin.government-offers.store', $government->id) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">عنوان العرض *</label>
                            <input type="text" name="title" class="form-control" required
                                placeholder="مثال: أدوية مجانية لمرضى السكر">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">نوع العرض *</label>
                            <select name="offer_type" class="form-select" required>
                                <option value="discount">تخفيض</option>
                                <option value="free_service">خدمة مجانية</option>
                                <option value="special_feature">ميزة خاصة</option>
                                <option value="donation">تبرعات/مساعدة</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">وصف العرض</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="وصف تفصيلي للعرض..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الفئة المستهدفة</label>
                            <input type="text" name="target_audience" class="form-control"
                                placeholder="مثال: مرضى السكر، كبار السن">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">أيقونة العرض</label>
                            <input type="text" name="icon" class="form-control" placeholder="مثال: fas fa-heart"
                                value="fas fa-tag">
                            <small class="text-muted">رمز FontAwesome (مثال: fas fa-gift, fas fa-heart)</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">تاريخ البداية</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">تاريخ النهاية</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="is_permanent" class="form-check-input" id="is_permanent"
                                    value="1">
                                <label class="form-check-label" for="is_permanent">
                                    عرض مستمر (بدون تاريخ انتهاء)
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">الشروط والأحكام</label>
                            <textarea name="terms" class="form-control" rows="2" placeholder="الشروط المطلوبة للاستفادة من العرض..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">رقم الاستفسار عن العرض</label>
                            <input type="tel" name="contact_number" class="form-control" placeholder="مثال: 01 234 567">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> إضافة العرض
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- قائمة العروض الحالية --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-1"></i>
                    العروض الحالية
                </h5>
            </div>
            <div class="card-body">
                @if ($offers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>العنوان</th>
                                    <th>النوع</th>
                                    <th>الفئة المستهدفة</th>
                                    <th>المدة</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($offers as $offer)
                                    <tr>
                                        <td>
                                            <i class="{{ $offer->icon }} me-1"></i>
                                            {{ $offer->title }}
                                        </td>
                                        <td>
                                            @switch($offer->offer_type)
                                                @case('discount')
                                                    تخفيض
                                                @break

                                                @case('free_service')
                                                    خدمة مجانية
                                                @break

                                                @case('special_feature')
                                                    ميزة خاصة
                                                @break

                                                @case('donation')
                                                    تبرعات
                                                @break

                                                @default
                                                    أخرى
                                            @endswitch
                                        </td>
                                        <td>{{ $offer->target_audience ?? '-' }}</td>
                                        <td>
                                            @if ($offer->is_permanent)
                                                <span class="badge bg-success">مستمر</span>
                                            @else
                                                {{ $offer->start_date ?: 'غير محدد' }} -
                                                {{ $offer->end_date ?: 'غير محدد' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($offer->is_active)
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-secondary">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST"
                                               action="{{ route('admin.government-offers.destroy', [$government->id, $offer->id]) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-gift fa-2x mb-2 d-block"></i>
                        <p>لا توجد عروض خاصة مسجلة لهذه الجهة</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
