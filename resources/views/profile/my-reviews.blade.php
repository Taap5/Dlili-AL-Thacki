@extends('layouts.app')

@section('title', 'تقييماتي')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-star text-warning me-2"></i>
            تقييماتي
        </h2>
        <span class="badge bg-primary rounded-pill px-3 py-2">{{ $reviews->count() }} تقييم</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($reviews->count() > 0)
        <div class="row g-4">
            @foreach($reviews as $review)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-bold mb-2">
                                        <a href="{{ route('governments.show', $review->government->id) }}"
                                           class="text-decoration-none text-dark">
                                            {{ $review->government->name }}
                                        </a>
                                    </h5>
                                    <div class="text-warning mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="text-muted ms-2">({{ $review->rating }}/5)</span>
                                    </div>
                                    <p class="text-muted mb-0">{{ $review->comment ?? '<span class="text-muted fst-italic">لا يوجد تعليق</span>' }}</p>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $review->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                               onclick="event.preventDefault(); if(confirm('هل أنت متأكد من حذف هذا التقييم؟')) document.getElementById('delete-form-{{ $review->id }}').submit();">
                                                <i class="fas fa-trash-alt me-2"></i>حذف
                                            </a>
                                            <form id="delete-form-{{ $review->id }}" action="{{ route('my.reviews.destroy', $review->id) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 bg-light">
            <div class="card-body text-center py-5">
                <i class="fas fa-star fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">لا توجد تقييمات</h4>
                <p class="small text-muted">لم تقم بإضافة أي تقييمات بعد</p>
                <a href="/" class="btn btn-primary mt-2">استعرض الجهات</a>
            </div>
        </div>
    @endif
</div>
@endsection
