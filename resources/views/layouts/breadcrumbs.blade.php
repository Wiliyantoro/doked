@php
    $breadcrumbs = [
        'Home' => '/',
        'Kegiatan' => '/kegiatan',
        'Daftar Kegiatan' => '',
    ];

    if (isset($breadcrumbItems)) {
        $breadcrumbs = array_merge(['Home' => '/'], $breadcrumbItems);
    }
@endphp

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $title => $url)
            @if($url)
                <li class="breadcrumb-item"><a href="{{ htmlspecialchars($url) }}">{{ htmlspecialchars($title) }}</a></li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ htmlspecialchars($title) }}</li>
            @endif
        @endforeach
    </ol>
</nav>
