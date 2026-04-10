@props(['items' => []])

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                <i class="fas fa-tachometer-alt me-1"></i> لوحة التحكم
            </a>
        </li>

        @foreach($items as $item)
            @if(isset($item['url']))
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] }}" class="text-decoration-none">
                        {{ $item['name'] }}
                    </a>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $item['name'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
