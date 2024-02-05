<?php
use Carbon\Carbon;
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Informe conclusivo</title>
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

<body class="A4 portrait" style="font-family: arial,helvetica,sans-serif; font-size: 14px;">
    <section>
        <article style="text-align: center; font-size: 16px;"><b>Evento Epidemiológico.</b></article>
    </section>
    <br>
    <section>
        <b><u>Datos generales</u></b>
        <br>
        <br>
        <b>Número de Registro:</b> {{$data['cod_registro']}}
        <br>
        <b>Fecha de Registro:</b> {{Carbon::parse($data['fecha_registro'])->format('d/m/Y')}}
        <br>
        <b>Fecha de Detección:</b> {{Carbon::parse($data['fecha_deteccion'])->format('d/m/Y')}}
        <br>
        <b>Detección:</b> {{$data['deteccion']}}
        <br>
        <b>Detectado por:</b> {{$data['detectado_por']}}
        <br>
        <b>Instalación:</b> {{$data['instalaciones']['nombre']}}
        <br>
        <b>Código:</b>
        <br>
        <b>OSDE:</b> {{$data['instalaciones']['entidades']['osde']['nombre']}}
        <br>
        <b>Territorio:</b> {{$data['instalaciones']['municipio']['provincia']['nombre']}}
        <br>
        <b>Municipio:</b> {{$data['instalaciones']['municipio']['nombre']}}
        <br>
        <br>
        <b><u>Clasificación del Evento</u></b>
        <br>
        <br>
        <b>El evento es un:</b> {{$data['clasificacion_evento']}}
        <br>

        @if ($data['clasificacion_evento'] === 'Foco')
        <b>Tipo de Foco:</b> {{$data['tipo_foco']}}
        <b>Número de Focos:</b> {{$data['cant_focos']}} {{$data['cant_focos']}}
        <b>Depósito:</b> {{$data['deposito_foco']}}
        <b>Ubicación:</b> {{$data['ubicacion_foco']}}
        @endif
        
        @if ($data['clasificacion_evento'] === 'Muestra Positiva')
        <b>Tipo de Muestra:</b> {{$data['tipo_muestra']}}
        <br>
        <b>Patógeno Identificado:</b> {{$data['patogeno_identificado_muestra']}}
        <br>
        <b>Ubicación:</b> {{$data['ubicacion_muestra']}}
        @endif
        <br>
        @if ($data['clasificacion_evento'] === 'Caso')
        <b>Tipo de Caso:</b> {{$data['tipo_caso']}}
        <br>
        <b>Origen del Caso:</b> {{$data['origen_caso']}}
        <br>
        <b>Agente Causal:</b> {{$data['agente_causal_caso']}}
        <br>
        <b>Mecanismo de Transmisión:</b> {{$data['mecanismo_transmision_caso']}}
        <br>
        <b>Vehículo:</b> {{$data['vehiculo_caso']}}
        <br>
        <b>Alimento Involucrado:</b> {{$data['alimento_involucrado_caso']}}
        @endif
        <br>
        @if ($data['clasificacion_evento'] === 'Brote')
        <b>Tipo de Brote:</b> {{$data['tipo_brote']}}
        <br>
        <b>Origen del Brote:</b> {{$data['origen_brote']}}
        <br>
        <b>Agente Causal:</b> {{$data['agente_causal_brote']}}
        <br>
        <b>Mecanismo de Transmisión:</b> {{$data['mecanismo_transmision_brote']}}
        <br>
        <b>Vehículo:</b> {{$data['vehiculo_brote']}}
        <br>
        <b>Alimento Involucrado:</b> {{$data['alimento_involucrado_brote']}}
        @endif
        
        <b>Estado del Proceso:</b> {{$data['estado_proceso']}}
        <br>
        <br>
        <b><u>Cantidad de Clientes/Trabajadores:</u></b>
        <br>
        <br>
        <b>Expuestos:</b> {{$data['expuestos']}}
        <br>
        <b>Afectados:</b> {{$data['afectados']}}
        <br>
        <b>Ingresados:</b> {{$data['ingresados']}}
        <br>
        <b>Fallecidos:</b> {{$data['fallecidos']}}
        <br>
        <br>
        <b><u>Respuesta al Evento:</u></b>
        <br>
        <br>
        @if ($data['plan_accion'] === true)
        <b>El evento tiene un Plan de Acción.</b>
        @endif
        <br>
        <b>Medidas Disciplinarias:</b> {{$data['medida_disciplinaria']}}
        <br>
        <b>Naturaleza de las Medidas Disciplinarias:</b> {{$data['naturaleza_medida_disciplinaria']}}
        <br>
        <br>
        @if ($data['seguimiento_evento'] === true)
        <b><u>Seguimiento del Evento</u></b>
        <br>
        <br>
        <b>Tipo de Seguimiento:</b> {{$data['tipo_seguimiento']}}
        <br>
        <b>Fecha:</b> {{Carbon::parse($data['fecha_seguimiento_evento'])->format('d/m/Y')}}
        <br>
        <b>Ejecutado por:</b> {{$data['ejecutado_por']}}
        <br>
        <b>Resultado:</b> {{$data['resultado_seguimiento_evento']}}
        <br>
        <br>
        @endif
        <b><u>Cierre del evento</u></b>
        <br>
        <br>
        @if ($data['informe_conclusivo'] === true)
        <b>Se envió un Informe Conclusivo aprobado por las autoridades competentes. </b>
        <br>
        @endif
        <b>Fecha de Cierre:</b>{{Carbon::parse($data['fecha_cierre'])->format('d/m/Y')}}
    </section>
    <script>
    </script>
</body>

</html>