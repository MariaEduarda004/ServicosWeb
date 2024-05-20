<!DOCTYPE html>
<html>
<head>
    <title>Lista de Restaurantes</title>
</head>
<body>
    <h1>Lista de Restaurantes</h1>
    <ul>
        @foreach ($restaurantes as $restaurante)
            <li>{{ $restaurante->nome }}</li>
        @endforeach
    </ul>
</body>
</html>
