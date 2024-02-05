<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Información del vehículo.</title>
</head>

<body>
    <p>El sistema tiene registrado medios de transporte con el FICAV próximo a vencer.</p>
    <p>Estos son los datos del vehículo:</p>
    @if (!is_null($vehiculo))
    <ul>
        <li><b>Ultima FICAV:</b> {{ \Carbon\Carbon::parse($vehiculo->fecha_ficav)->format('d/m/Y') }}</li>
        <li><b>Matrícula:</b> {{ $vehiculo->matricula }}</li>
        <li><b>Tipo de flota:</b> {{ $vehiculo->tipo_flota }}</li>
        <li><b>Tipo de medio de transporte:</b> {{ $vehiculo->tipo_medio_transporte }}</li>
        <li><b>Marca:</b> {{ $vehiculo->marca }}</li>
        <li><b>Modelo:</b> {{ $vehiculo->modelo }}</li>
    </ul>
    @endif
</body>

</html>