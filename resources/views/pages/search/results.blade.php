@extends('layouts.app')

@section('title', 'نتائج البحث')

@section('content')
<div class="container py-5">

    {{-- شريط ملخص البحث --}}
    <div class="search-summary mb-4 text-center">
        <h3 class="fw-bold text-primary">
            نتائج البحث عن: <span class="text-dark">"{{ $query }}"</span>
        </h3>

        @if(isset($results) && $results->count())
            <p class="text-muted mt-2">
                تم العثور على {{ $results->count() }} نتيجة مطابقة
            </p>
        @endif
    </div>

    @if(!isset($results) || $results->isEmpty())
        {{-- حالة عدم وجود نتائج --}}
        <div class="alert alert-light text-center p-5 shadow-sm">
            <h5 class="fw-bold mb-3">لم يتم العثور على نتائج مطابقة</h5>
            <p class="text-muted mb-4">
                جرّب البحث بكلمات أخرى أو استخدم التصنيفات لتضييق نطاق البحث
            </p>
            <a href="{{ route('home') }}" class="btn btn-primary">العودة للبحث</a>
        </div>
    @else
        {{-- فصل الجهات عن الخدمات --}}
        @php
            $governments = $results->where('type', 'government');
            $services = $results->where('type', 'service');
        @endphp

        {{-- قسم الجهات الحكومية --}}
        @if($governments->count())
            <div class="mb-5">
                <h4 class="mb-3 fw-bold text-secondary">الجهات الحكومية</h4>
                <div class="row g-4">
                    @foreach($governments as $gov)
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold mb-2">{{ $gov['name'] }}</h5>
                                    <p class="text-muted small flex-grow-1">
                                        جهة حكومية تقدم مجموعة من الخدمات للمواطنين
                                    </p>
                                    <a href="{{ route('governments.show', $gov['id']) }}"
                                       class="btn btn-outline-primary btn-sm mt-auto">
                                       عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- قسم الخدمات --}}
        @if($services->count())
            <div class="mb-5">
                <h4 class="mb-3 fw-bold text-secondary">الخدمات المتوفرة</h4>
                <div class="row g-4">
                    @foreach($services as $srv)
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold mb-2">{{ $srv['name'] }}</h5>

                                    @if(isset($srv['governments']) && $srv['governments']->count())
                                        <p class="text-muted small flex-grow-1">
                                            مقدمة من:
                                            @foreach($srv['governments'] as $gov)
                                                <span class="badge bg-light text-dark">{{ $gov['name'] }}</span>
                                            @endforeach
                                        </p>
                                    @else
                                        <p class="text-muted small flex-grow-1">
                                            لا توجد جهات محددة
                                        </p>
                                    @endif

                                    <a href="{{ route('services.show', $srv['id']) }}"
                                       class="btn btn-outline-primary btn-sm mt-auto">
                                       عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
@endsection


