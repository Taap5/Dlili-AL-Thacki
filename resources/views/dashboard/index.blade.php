@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <i class="fas fa-building fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">الجهات</h5>
                <p class="text-muted">تصفح جميع الجهات الحكومية</p>
                <a href="/" class="btn btn-outline-primary">استعرض</a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <i class="fas fa-concierge-bell fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">الخدمات</h5>
                <p class="text-muted">تصفح جميع الخدمات المتوفرة</p>
                <a href="/" class="btn btn-outline-primary">استعرض</a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <i class="fas fa-heart fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">المفضلة</h5>
                <p class="text-muted">الخدمات والجهات التي حفظتها</p>
                <a href="{{ route('favorites') }}" class="btn btn-outline-primary">عرض</a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mt-3">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-user-circle fa-3x text-primary"></i>
                <div>
                    <h4 class="fw-bold mb-0">مرحباً {{ Auth::user()->user_name }}</h4>
                    <p class="text-muted mb-0">مرحباً بك في لوحة التحكم الخاصة بك</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
