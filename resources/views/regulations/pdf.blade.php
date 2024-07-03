<!-- resources/views/regulations/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $regulation->name }}</title>
</head>
<body>
    <h1>{{ $regulation->name }}</h1>
    <p>Type: {{ $regulation->type }}</p>
    
    <h2>Menimbang</h2>
    <ul>
        @foreach($regulation->menimbang as $item)
            <li>{{ $item->content }}</li>
        @endforeach
    </ul>
    
    <h2>Mengingat</h2>
    <ul>
        @foreach($regulation->mengingat as $item)
            <li>{{ $item->content }}</li>
        @endforeach
    </ul>
    
    <h2>Memutuskan</h2>
    @foreach($regulation->memutuskan as $item)
        <h3>{{ $item->title }}</h3>
        <p>{{ $item->content }}</p>
        @if($item->subMemutuskan->count())
            <ul>
                @foreach($item->subMemutuskan as $subItem)
                    <li>{{ $subItem->content }}</li>
                @endforeach
            </ul>
        @endif
    @endforeach
</body>
</html>
