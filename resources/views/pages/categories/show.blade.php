@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container py-5">
    <h2 class="mb-4">{{ $category->name }}</h2>

    <div class="row">
        @foreach($governments as $gov)
            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100">
                    <h5>{{ $gov->name }}</h5>
                    <p class="text-muted small">{{ $gov->description }}</p>

                    <ul class="small">
                        @foreach($gov->services as $service)
                            <li>{{ $service->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
