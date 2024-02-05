<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Notificación problemas con la asignación de los km recorridos.</title>
</head>

<body>
    <p>Usted debe agregar los Km recorridos al sistema.</p>
    <p>Detalles del vehículo sin km asignados:</p>
    @if (!is_null($data))
    <ul>
        <li><b>Matrícula:</b> {{ $data->vehiculo_matricula }}</li>
        <li><b>Tipo de flota:</b> {{ $data->vehiculo_tipo_flota }}</li>
        <li><b>Tipo de medio de transporte:</b> {{ $data->vehiculo_tipo_medio_transporte }}</li>
        <li><b>Marca:</b> {{ $data->vehiculo_marca }}</li>
        <li><b>Modelo:</b> {{ $data->vehiculo_modelo }}</li>
    </ul>
    @endif
</body>

</html>