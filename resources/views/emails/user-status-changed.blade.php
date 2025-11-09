<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
</head>
<body style="font-family: system-ui, -apple-system, Roboto, 'Segoe UI', Arial; color:#0f172a;">
    <h1 style="margin:0 0 12px 0; font-size:20px;">{{ $title }}</h1>

    @foreach($lines as $line)
        <p style="margin:0 0 10px 0;">{!! $line !!}</p>
    @endforeach

    <p style="margin-top:24px;">— Equipo HydroBox</p>
</body>
</html>
