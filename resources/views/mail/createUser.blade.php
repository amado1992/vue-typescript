<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Notificación del sitio web ZUNeventos</title>
</head>
<body>
<p>Hola! <strong>{{ $user->username }}</strong>, nuevo usuario creado a
    las {{ $user->created_at }} y por cuestiones de seguridad </p>
<p>Su clave es: <strong>{{ $password }}</strong></p>
<p>Gracias, Equipo de ZUNeventos</p>
</body>
</html>
