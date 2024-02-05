<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Reporte CDT</title>
    <style>
        @page {
            size: A4 landscape
        }

        body {
            margin: 3.5cm 2cm 2cm;
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
        <article style="text-align: center; font-size: 16px;"><b>Reporte CDT.</b>&nbsp;&nbsp;&nbsp; Fecha:{{Carbon\Carbon::today()->format('d/m/Y')}}</article>
    </section>
    <br>
    <p>
        <b>Vehículos {{$tipo_flota}}</b>
        <br>
    </p>
    <section>
        <table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Trabajando</th>
                    <th>ATM</th>
                    <th>Taller</th>
                    <th>Motor</th>
                    <th>Otros</th>
                    <th>P/B</th>
                    <th>B/T</th>
                    <th>B/Tur</th>
                    <th>Por ciento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $items)
                {{$num = $loop->index}}
                <tr>
                    @if ($loop->last)
                    <td>&nbsp;</td>
                    <td><b>{{$items->tipo_medio_transporte}}</b></td>
                    @else
                    <td style="text-align: center">{{$num + 1}}</td>
                    <td>{{$items->tipo_medio_transporte}}</td>
                    @endif
                    <td>{{$items->cantidad}}</td>
                    <td>{{$items->trabajando}}</td>
                    <td>{{$items->atm}}</td>
                    <td>{{$items->taller}}</td>
                    <td>{{$items->motor}}</td>
                    <td>{{$items->otros}}</td>
                    <td>{{$items->pendiente_baja}}</td>
                    <td>{{$items->baja_tecnica}}</td>
                    <td>{{$items->baja_turistica}}</td>
                    <td>{{$items->coeficiente_disposicion_tecnica}}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
    </section>
    <script>
    </script>
</body>

</html>