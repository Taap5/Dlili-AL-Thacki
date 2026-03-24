@extends('layouts.app')

@section('title', 'خدماتي')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 text-center py-5">
            <i class="fas fa-concierge-bell fa-4x text-muted mb-3"></i>
            <h4 class="fw-bold">خدماتي</h4>
            <p class="text-muted">هذه الصفحة تعرض الخدمات التي قمت بالتفاعل معها.</p>
            <a href="/" class="btn btn-primary">استعرض الخدمات</a>
        </div>
    </div>
</div>
@endsection
