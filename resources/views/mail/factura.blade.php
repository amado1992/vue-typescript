<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <style>
        @page {
            size: A4 landscape;
            margin: 50px 50px;
            font-family: Arial;
        }

        body {
            margin: 3.5cm 2cm 2cm;
        }

        header {
            position: fixed;
            left: 0px;
            top: -40px;
            right: 0px;
            background-color: white;
        }

        #footer {
            position: fixed;
            left: 0px;
            bottom: -180px;
            right: 0px;
            height: 250px;
            background-color: white;
        }

        #footer .page:after {
            content: counter(page, upper-roman);
        }

        th {
            border: 1px solid white;
        }

        td {
            border: 1px solid white;
        }
    </style>
    <style>
        .logo-bg {
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body class="A4 landscape" style="font-family: arial,helvetica,sans-serif; font-size: 12px;">
<section>
    <header>
        <table>
            @php $title = ''; @endphp
            @foreach($solicitud as $record)
                @if($record['estado']['nombre'] === 'Reserva')
                    @php $title = 'Factura de Reserva'; @endphp
                @endif
                @if($record['estado']['nombre'] === 'PreReserva')
                    @php $title = 'Factura de la Pre-Reserva'; @endphp
                @endif
            @endforeach
            <tr>
                <th>Notificación del sitio web ZUNeventos</th>
                <th style="font-size: 16px;">{{$title}}</th>
                <th>
                    <img class="logo-bg" src="{{asset('images/zunpms_indigo_old.png')}}"/>
                </th>
            </tr>
        </table>
        <table>
            @foreach($solicitud as $record)
            @endforeach
            @php $record = [];@endphp
            @foreach($solicitud as $record)
                @php $arrayId = array_column($record['menu'], 'sub_familia_id'); @endphp
                @foreach($subFamilia as $item)
                    @if(in_array($item->id,$arrayId))
                    @endif
                    @php $cont = 0;@endphp
                    @php $precioArticuloTotal = 0;@endphp
                    @php $precioMedioTotal = 0;@endphp
                    @php $propina = 0;@endphp
                    @php $articulo = [];@endphp
                    @php $medio = [];@endphp
                    @foreach($record['menu'] as $articulo)
                        @php $precioArticuloTotal += $articulo['precio_venta'] * $articulo['cantidad'];@endphp
                        @if($item->id === $articulo['sub_familia_id'])
                            @php $cont ++; @endphp
                        @endif
                    @endforeach
                @endforeach
            @endforeach
            @foreach($record['medios'] as $medio)
                @php $precioMedioTotal += $medio['precio'] * $medio['cantidad'];@endphp
            @endforeach
            <tr>
                <th style="width:5%; text-align: left">Empresa Proveedor</th>
                <th></th>
                <th style="width:5%; text-align: left">Cliente</th>
            </tr>
            <tr>
                <th style="width:5%; text-align: left">Nombre: {{$record['empresa']['nombre']}}</th>
                <th></th>
                <th style="width:5%; text-align: left">Nombre: {{$record['cliente']['nombre']}}</th>
            </tr>
            <tr>
                <th style="width:5%; text-align: left">Dirección: {{$record['empresa']['direccion']}}</th>
                <th></th>
                <th style="width:5%; text-align: left">Dirección: {{$record['cliente']['direccion']}}</th>
            </tr>
            <tr>
                <th style="width:5%; text-align: left">Teléfono: {{$record['empresa']['telefono']}}</th>
                <th></th>
                <th style="width:5%; text-align: left">Teléfono: {{$record['cliente']['telefono']}}</th>
            </tr>
                <tr>
                    <th style="width:5%; text-align: left">Correo: {{$record['empresa']['email']}}</th>
                    <th></th>
                    <th style="width:5%; text-align: left">Correo: {{$record['cliente']['email']}}</th>
                </tr>
            <tr>
                <th style="width:5%; text-align: left">Nombre del Salón: {{$record['salon']['nombre']}}</th>
                <th></th>
                @if(count($record['fecha'])>1)
                    <th style="width:5%; text-align: center">Fecha
                        Evento: {{'[ '.Carbon\Carbon::parse($record['fecha'][0]['fecha_evento'])->format('d/m/Y').' - '.Carbon\Carbon::parse($record['fecha'][count($record['fecha'])-1]['fecha_evento'])->format('d/m/Y').' ]'}}</th>
                @elseif(count($record['fecha'])==1)
                    <th style="width:5%; text-align: center">Fecha
                        Evento: {{Carbon\Carbon::parse($record['fecha'][0]['fecha_evento'])->format('d/m/Y')}}</th>
                @endif
            </tr>
        </table>
    </header>
</section>
<section>
</section>
<br>
<section>
    <main>
        <section>
            <table style="border-collapse: collapse;">
                <thead style="display: table-header-group;">
                <tr>
                    <th>_______________________________</th>
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">Artículo</th>
                    <th></th>
                    <th style="width:5%; text-align: left">Cantidad</th>
                    <th></th>
                    <th style="width:5%; text-align: left">Precio</th>
                </tr>
                </thead>
                <tbody>
                @php $record = [];@endphp
                @foreach($solicitud as $record)
                    @php $arrayId = array_column($record['menu'], 'sub_familia_id'); @endphp
                    @foreach($subFamilia as $item)
                        @if(in_array($item->id,$arrayId))
                        @endif
                        @php $cont = 0;@endphp
                        @php $precioArticuloTotal = 0;@endphp
                        @php $precioMedioTotal = 0;@endphp
                        @php $propina = 0;@endphp
                        @php $articulo = [];@endphp
                        @php $medio = [];@endphp
                        @php $deco = [];@endphp
                        @php $precioDecoTotal = 0;@endphp
                        @foreach($record['menu'] as $articulo)
                            @php $precioArticuloTotal += $articulo['precio_venta'] * $articulo['cantidad'];@endphp
                            @if($item->id === $articulo['sub_familia_id'])
                                <tr>
                                    <th style="width:5%; text-align: left">{{$articulo['nombre']}}</th>
                                    <th></th>
                                    <th style="width:5%; text-align: center">{{$articulo['cantidad']}}</th>
                                    <th></th>
                                    <th style="width:5%; text-align: right">{{$articulo['precio_venta']}}</th>
                                </tr>

                                @php $cont ++; @endphp
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
                </tbody>
            </table>
            <table>
            </table>
            <table style="border-collapse: collapse;">
                <thead style="display: table-header-group;">
                <tr>
                    <th>
                        _______________________________
                    </th>
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">Medio</th>
                    <th></th>
                    <th style="width:5%; text-align: right">Cantidad</th>
                    <th></th>
                    <th style="width:5%; text-align: right">Precio</th>
                </tr>
                </thead>
                @foreach($record['medios'] as $medio)
                    @php $precioMedioTotal += $medio['precio'] * $medio['cantidad'];@endphp
                    <tbody>
                    <tr>
                        <th style="width:5%; text-align: left">{{$medio['nombre']}}</th>
                        <th></th>
                        <th style="width:5%; text-align: center">{{$medio['cantidad']}}</th>
                        <th></th>
                        <th style="width:5%; text-align: right">{{$medio['precio']}}</th>
                    </tr>
                    </tbody>
                @endforeach
            </table>
            <table style="border-collapse: collapse;">
                <thead style="display: table-header-group;">
                <tr>
                    <th>
                        _______________________________
                    </th>
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">Decoración</th>
                    <th></th>
                    <th style="width:5%; text-align: right">Precio</th>
                </tr>
                </thead>
                @foreach($record['decoraciones'] as $deco)
                    @php $precioDecoTotal += $deco['precio'];@endphp
                    <tbody>
                    <tr>
                        <th style="width:5%; text-align: left">{{$deco['nombre']}}</th>
                        <th></th>
                        <th style="width:5%; text-align: right">{{$deco['precio']}}</th>
                    </tr>
                    </tbody>
                @endforeach
            </table>
            <table style="border-collapse: collapse;">
                <tr>
                    <th>_______________________________</th>
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">{{"Precio total menú"}}</th>
                    <th>----</th>
                    <th style="width:5%; text-align: left">{{$precioArticuloTotal }}</th>
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">{{"Precio total medio"}}</th>
                    <th>----</th>
                    <th style="width:5%; text-align: left">{{$precioMedioTotal }}</th>
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">{{"Precio total decoración"}}</th>
                    <th>----</th>
                    <th style="width:5%; text-align: left">{{$precioDecoTotal }}</th>
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">{{"Precio salón"}}</th>
                    <th>----</th>
                    <th style="width:5%; text-align: left">{{$record['salon']['precio']}}</th>
                </tr>
                <tr>
                    <th>_______________________________</th>
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">{{"Total a pagar en CUP"}}</th>
                    <th>----</th>
                    @if(count($record['fecha'])>1)
                        <th style="width:5%; text-align: left">{{($precioMedioTotal + $precioArticuloTotal + $record['salon']['precio'] + $precioDecoTotal) * count($record['fecha'])}}</th>
                    @elseif(count($record['fecha'])==1)
                        <th style="width:5%; text-align: left">{{$precioMedioTotal + $precioArticuloTotal + $record['salon']['precio'] + $precioDecoTotal}}</th>
                    @endif
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">{{"Total a pagar en CUC"}}</th>
                    <th>----</th>
                    @if(count($record['fecha'])>1)
                        <th style="width:5%; text-align: left">{{(($precioMedioTotal + $precioArticuloTotal + $record['salon']['precio'] + $precioDecoTotal) * count($record['fecha'])) / 25}}</th>
                    @elseif(count($record['fecha'])==1)
                        <th style="width:5%; text-align: left">{{($precioMedioTotal + $precioArticuloTotal + $record['salon']['precio'] + $precioDecoTotal) / 25}}</th>
                    @endif
                </tr>
                <tr>
                    <th style="width:5%; text-align: left">{{"Total a pagar en MLC"}}</th>
                    <th>----</th>
                    @if(count($record['fecha'])>1)
                        <th style="width:5%; text-align: left">{{((($precioMedioTotal + $precioArticuloTotal + $record['salon']['precio'] + $precioDecoTotal) * count($record['fecha'])) / 25) * 1.50}}</th>
                    @elseif(count($record['fecha'])==1)
                        <th style="width:5%; text-align: left">{{(($precioMedioTotal + $precioArticuloTotal + $record['salon']['precio'] + $precioDecoTotal) / 25) * 1.50}}</th>
                    @endif
                </tr>
            </table>
        </section>
    </main>
</section>
<section>
    <div id="footer">
        <table>
            <tr>
                <th>_______________________________</th>
            </tr>
            <tr>
                <th style="width:5%; text-align: left">Firma del Operador</th>
                <th colspan="100"></th>
                <th style="width:5%; text-align: left">Firma del Cliente</th>
                <th colspan="100"></th>
                <th style="width:5%; text-align: left">Fecha de Entrega</th>
            </tr>
            <tr>
                <th style="width:5%; text-align: left">____________________</th>
                <th colspan="100"></th>
                <th style="width:5%; text-align: left">_____________________</th>
                <th colspan="100"></th>
                <th style="width:5%; text-align: center">
                    Fecha: {{Carbon\Carbon::today()->format('d/m/Y')}}</th>
            </tr>
        </table>
        <p>Gracias, Equipo de ZUNeventos</p>
    </div>
</section>
</body>
</html>
