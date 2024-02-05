<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Reporte de accidentalidad</title>
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
        <article style="text-align: center; font-size: 16px;"><b>Reporte de accidentalidad</b>&nbsp;&nbsp;&nbsp; Fecha:{{Carbon\Carbon::today()->format('d/m/Y')}}</article>
    </section>
    <br>
    <section>
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th colspan="3">Mes</th>
                    <th colspan="3">Acumulado</th>
                    <th colspan="3">Total general</th>
                </tr>
                <tr>
                    <th>Indicadores</th>
                    <th>Año anterior</th>
                    <th>Año actual</th>
                    <th>Diferencia</th>
                    <th>Año anterior</th>
                    <th>Año actual</th>
                    <th>Diferencia</th>
                    <th>Año anterior</th>
                    <th>Año actual</th>
                    <th>Diferencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $items)
{{--                {{$num = $loop->index}}--}}
                <tr>
{{--                    <td style="text-align: center">{{$num + 1}}</td>--}}
                    <td>{{$items->indicador}}</td>
                    <td>{{$items->mes_ano_anterior}}</td>
                    <td>{{$items->mes_ano_actual}}</td>
                    <td>{{$items->mes_ano_diferencia}}</td>
                    <td>{{$items->acumulado_ano_anterior}}</td>
                    <td>{{$items->acumulado_ano_actual}}</td>
                    <td>{{$items->acumulado_ano_diferencia}}</td>
                    <td>{{$items->total_ano_anterior}}</td>
                    <td>{{$items->total_ano_actual}}</td>
                    <td>{{$items->total_ano_diferencia}}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
    </section>
    <script>
    </script>
</body>

</html>
