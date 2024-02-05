import Vue from 'vue'
import Vuex from 'vuex'

import breadcrumbs from './breadcrumbs'
import persona from './seguridad/persona'
import traza from './seguridad/traza'
import role from './seguridad/role'
import usuario from './seguridad/usuario'
import usuarios from './seguridad/usuarios'
import ayuda from './ayuda/ayuda'
import dashboard from './dashboard'
import utils from './utils'
import importacion from './gestion_importacion/importaciones'
import renglon from './gestion_importacion/renglon'
import indicador from './gestion_importacion/indicadores'
import disponibilidad from './disponibilidad'
import familia from './flujo_material/familia'
import producto from './flujo_material/producto'
import demanda from './flujo_material/demanda'
import colectivo from './movimientosLideresCalidad/colectivo'
import propuesta from './movimientosLideresCalidad/propuesta'
import osde from './nomencladores/osde'
import instalacion from './nomencladores/instalacion'
import entidad from './nomencladores/entidad'
import municipio from './nomencladores/municipio'
import provincia from './nomencladores/provincia'
import sector from './nomencladores/sector'
import tipoInst from './nomencladores/tipoInst'
import oace from './nomencladores/oace'
import cadena_hotelera from './nomencladores/cadena_hotelera'
import ueb from './nomencladores/ueb'
import delegacion_territorial from './nomencladores/delegacion_territorial'
import escuela_capacitacion from './nomencladores/escuela_capacitacion'
import oficina_empleo from './nomencladores/oficina_empleo'
import infotur from './nomencladores/infotur'
import empresa from './nomencladores/empresa'
import categoria_instalacion from './nomencladores/categoria_instalacion'
import sucursal from './nomencladores/sucursal'
import fintur from './nomencladores/fintur'
import onit from './nomencladores/onit'
import utit from './nomencladores/utit'
import escuela_ramal from './nomencladores/escuela_ramal'
import unidad_administrativa from './nomencladores/unidad_administrativa'
import premio from './gestion_premio/premio'
import categoria_premio from './gestion_premio/categoria_premio'
import indicadoresl210 from './get_indicadoresl210/indicadoresL210'
import lic_sanitaria from './get_indicadoresl210/lic_sanitaria'
import aval_citma from './get_indicadoresl210/aval_citma'
import aval_apci from './get_indicadoresl210/aval_apci'
import compra from './flujo_informativo/compra'
import tipo_proveedor from './flujo_informativo/tipo_proveedor'
import proveedor from './flujo_informativo/proveedor'
import unidad_medida from './flujo_informativo/unidad_medida'
import tipo_almacen from './gestion_almacenes/tipo_almacen'
import categoria_almacen from './gestion_almacenes/categoria_almacen'
import actividad_almacen from './gestion_almacenes/actividad_almacen'
import temperatura from './gestion_almacenes/temperatura'
import distribucion from './gestion_almacenes/distribucion'
import almacen from './gestion_almacenes/almacen'
import encargado_almacen from './gestion_almacenes/encargado_almacen'
import maintenance from './gestion_mantenimiento'
import managementsystem from './sistema_gestion'
import setInstalacion from './gestion_entidades/instalacion'
import costo_calidad from './gestion_costo_calidad/costo_calidad'
import costo_calidad_reportes from './gestion_costo_calidad/costo_calidad_reportes'
import costo_conformidad from './gestion_costo_calidad/costo_conformidad'
import medio_transporte from './gestion_medioTransporte/medioTransporte'
import tipo_flota from './transporte_flujo_informativo/tipo_flota'
import tipo_emnautico from './transporte_flujo_informativo/tipo_emnautico'
import tipo_vaservicio from './transporte_flujo_informativo/tipo_vaservicio'
import tipo_vespecializado from './transporte_flujo_informativo/tipo_vespecializado'
import tipo_vturistico from './transporte_flujo_informativo/tipo_vturistico'
import clasificacion_accidente from './transporte_flujo_informativo/clasificacion_accidente'
import causa_accidente from './transporte_flujo_informativo/causa_accidente'
import victima_accidente from './transporte_flujo_informativo/victima_accidente'
import etmtransporte from './transporte_flujo_informativo/etmtransporte'
import tcmtransporte from './transporte_flujo_informativo/tcmtransporte'
import samtransporte from './transporte_flujo_informativo/samtransporte'
import mpmtransporte from './transporte_flujo_informativo/mpmtransporte'
import cmtransporte from './transporte_flujo_informativo/cmtransporte'
import ubicacion_memnautico from './transporte_flujo_informativo/ubicacion_memnautico'
import vehiculo_ajeno from './transporte_flujo_informativo/vehiculo_ajeno'
import km_recorridos from './transporte_flujo_informativo/km_recorridos'
import gestion_accidente from './transporte_flujo_informativo/gestion_accidente'
import plan_recape from './plan_recape'
import cumplimiento_plan_recape from './cumplimiento_plan_recape'
import deteccion from './gestionar_evento_higienico_epidemiologico/deteccion'
import clasificacion_evento from './gestionar_evento_higienico_epidemiologico/clasificacion_evento'
import patogeno_identificado from './gestionar_evento_higienico_epidemiologico/patogeno_identificado'
import tipo_foco from './gestionar_evento_higienico_epidemiologico/tipo_foco'
import tipo_muestra from './gestionar_evento_higienico_epidemiologico/tipo_muestra'
import detectado_por from './gestionar_evento_higienico_epidemiologico/detectado_por'
import tipo_caso from './gestionar_evento_higienico_epidemiologico/tipo_caso'
import origen_caso from './gestionar_evento_higienico_epidemiologico/origen_caso'
import agente_causal_caso from './gestionar_evento_higienico_epidemiologico/agente_causal_caso'
import tipo_brote from './gestionar_evento_higienico_epidemiologico/tipo_brote'
import origen_brote from './gestionar_evento_higienico_epidemiologico/origen_brote'
import agente_causal_brote from './gestionar_evento_higienico_epidemiologico/agente_causal_brote'
import vehiculo from './gestionar_evento_higienico_epidemiologico/vehiculo'
import mecanismo_transmision from './gestionar_evento_higienico_epidemiologico/mecanismo_transmision'
import gestion_ehe from './gestionar_evento_higienico_epidemiologico/gestion_ehe'

Vue.use(Vuex)

/*
 * If not building with SSR mode, you can
 * directly export the Store instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Store instance.
 */

export default function (/* { ssrContext } */) {
    const Store = new Vuex.Store({
        modules: {
            breadcrumbs,
            persona,
            colectivo,
            propuesta,
            demanda,
            provincia,
            familia,
            municipio,
            producto,
            tipoInst,
            oace,
            cadena_hotelera,
            ueb,
            delegacion_territorial,
            escuela_capacitacion,
            oficina_empleo,
            infotur,
            empresa,
            categoria_instalacion,
            sucursal,
            fintur,
            onit,
            utit,
            escuela_ramal,
            unidad_administrativa,
            sector,
            traza,
            role,
            usuario,
            usuarios,
            ayuda,
            utils,
            importacion,
            entidad,
            instalacion,
            osde,
            renglon,
            indicador,
            disponibilidad,
            premio,
            categoria_premio,
            indicadoresl210,
            lic_sanitaria,
            aval_citma,
            aval_apci,
            compra,
            tipo_proveedor,
            proveedor,
            unidad_medida,
            tipo_almacen,
            categoria_almacen,
            actividad_almacen,
            temperatura,
            distribucion,
            maintenance,
            managementsystem,
            setInstalacion,
            almacen,
            encargado_almacen,
            costo_calidad,
            costo_calidad_reportes,
            costo_conformidad,
            medio_transporte,
            tipo_flota,
            tipo_emnautico,
            tipo_vaservicio,
            tipo_vespecializado,
            tipo_vturistico,
            clasificacion_accidente,
            causa_accidente,
            victima_accidente,
            etmtransporte,
            tcmtransporte,
            samtransporte,
            mpmtransporte,
            cmtransporte,
            ubicacion_memnautico,
            vehiculo_ajeno,
            km_recorridos,
            gestion_accidente,
            plan_recape,
            cumplimiento_plan_recape,
            deteccion,
            clasificacion_evento,
            patogeno_identificado,
            tipo_foco,
            tipo_muestra,
            detectado_por,
            tipo_caso,
            origen_caso,
            agente_causal_caso,
            tipo_brote,
            origen_brote,
            agente_causal_brote,
            vehiculo,
            mecanismo_transmision,
            gestion_ehe,
            dashboard
        },

        // enable strict mode (adds overhead!)
        // for dev mode only
        strict: process.env.DEV
    })

    return Store
}
