<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Reporte Ficha de medios de transporte</title>
    <style>
        @page {
            size: A4 landscape
        }

        body {
            margin: 1.5cm 2cm 2cm;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black
        }

        th,
        td {
            padding: 5px;
        }

        th {
            text-align: center;
        }
    </style>
</head>

<body class="A4 landscape" style="font-family: arial,helvetica,sans-serif; font-size: 12px;">
    <section>
        <article style="text-align: center; font-size: 16px;"><b>Ficha de medios de transporte.</b>&nbsp;&nbsp;&nbsp; Fecha:{{Carbon\Carbon::today()->format('d/m/Y')}}</article>
    </section>
    <br>
    <section>
        <table">
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Color</th>
                    <th>Año</th>
                    <th>No. motor</th>
                    <th>No. carrocería</th>
                    <th>Eléctrico</th>
                    <th>Índice de consumo</th>
                    <th>Combustible asignado</th>
                    <th>Capacidad de carga</th>
                    <th>Cant. neumáticos</th>
                    <th>Ficav</th>
                    <th>Motor horas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $items)
                <tr>
                    <td>{{$items->matricula}}</td>
                    <td>{{$items->marca}}</td>
                    <td>{{$items->modelo}}</td>
                    <td>{{$items->color}}</td>
                    <td>{{$items->anno_fabricacion}}</td>
                    <td>{{$items->num_motor}}</td>
                    <td>{{$items->num_carroceria}}</td>
                    <td>{{$items->electrico}}</td>
                    <td>{{$items->indice_consumo}}</td>
                    <td>{{$items->combustible_asignado}}</td>
                    <td>{{$items->capacidad_carga}}</td>
                    <td>{{$items->neumaticos}}</td>
                    <td>{{$items->fecha_ficav}}</td>
                    <td>{{$items->moto_horas}}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
    </section>
    <script>
    </script>
</body>

</html>