import Vue from 'vue'
import VueRouter from 'vue-router'

import NotAuthorized from '../pages/NotAuthorized';
import Index from '../pages/index';
import login from '../pages/login/login';
import layout from '../layout/layout';
import Persona from '../pages/seguridad/persona';
import Traza from '../pages/seguridad/traza';
import Role from '../pages/seguridad/role';
import Usuario from '../pages/seguridad/usuario';
import Usuarios from '../pages/seguridad/usuarios';
import Ayuda from '../pages/ayuda/ayuda';
import Renglones from '../pages/nomencladores/GestionarRenglon';
import Instalacion from '../pages/nomencladores/instalacion';
import Indicadores from '../pages/nomencladores/GestionarIndicador';
import Osdes from '../pages/nomencladores/osde';
import Entidades from '../pages/nomencladores/entidad';
import Provincias from '../pages/nomencladores/provincia';
import Municipios from '../pages/nomencladores/municipio';
import TipoInst from '../pages/nomencladores/tipoInst';
import Sector from '../pages/nomencladores/sector';
import Oace from '../pages/nomencladores/oace';
import Cadena_Hotelera from '../pages/nomencladores/cadena_hotelera';
import Ueb from '../pages/nomencladores/ueb';
import Delegacion_Territorial from '../pages/nomencladores/delegacion_territorial';
import Escuela_Capacitacion from '../pages/nomencladores/escuela_capacitacion';
import Oficina_Empleo from '../pages/nomencladores/oficina_empleo';
import Infotur from '../pages/nomencladores/infotur';
import Empresa from '../pages/nomencladores/empresa';
import Categoria_Instalacion from '../pages/nomencladores/categoria_instalacion';
import Sucursal from '../pages/nomencladores/sucursal';
import Fintur from '../pages/nomencladores/fintur';
import Onit from '../pages/nomencladores/onit';
import Utit from '../pages/nomencladores/utit';
import Escuela_Ramal from '../pages/nomencladores/escuela_ramal';
import Unidad_Administrativa from '../pages/nomencladores/unidad_administrativa';
import CostoConformidad from '../pages/costo_calidad/costo_conformidad';
import CostoCalidadConformidad from '../pages/costo_calidad/costo_calidad_conformidad';
import GestCostoCalidadReportes from '../pages/costo_calidad/costo_calidad_reportes';
import EntityAllCost from '../pages/costo_calidad/entity_all_cost';
import EntityAllIndicator from '../pages/costo_calidad/entity_all_indicator';
import EntityAllCostTotal from '../pages/costo_calidad/entity_all_costs_total_page';
import EntityAllCostChart from '../pages/costo_calidad/entity_all_cost_chart_page';
import TurismoHS from '../pages/turismo_mas_higienico_seguro/turismo_mas_higienico_seguro_page';
import ExpedienteTurismoHS from '../pages/turismo_mas_higienico_seguro/expediente_proceso_page';
import SeguimientoTurismoHS from '../pages/turismo_mas_higienico_seguro/seguimiento_proceso_page';
import GestionarPremios from '../pages/gestion_premio/NuevoPremio';
import GestionarCategoriaPremios from '../pages/gestion_premio/categoria_premio';
import CantPremiosOsde from '../pages/gestion_premio/reportes/CantPremiosOsde';
import CantPremiosCategoria from '../pages/gestion_premio/reportes/CantPremiosCategoria';
import InstalacionesPremiadas from '../pages/gestion_premio/reportes/InstalacionesPremiadas';
import GestColectivoLideresCalidad from '../pages/movimientosLideresCalidad/colectivoLider';
import GestPropuestaLider from '../pages/movimientosLideresCalidad/propuestaLider';
import ListaPorEstadoColectivo from '../pages/movimientosLideresCalidad/listadoPorEstadoColectivo';
import ListaPorEstadoColectivoFiltro from '../pages/movimientosLideresCalidad/filtradoPorEstadoColectivo';
import ListaPorEstadoPropuesta from '../pages/movimientosLideresCalidad/listadoPorEstadoPropuesta';
import GestIndicadoresL210 from '../pages/indicadoresL210/GestIndicadoresL210';
import LicAvalesReporte from '../pages/indicadoresL210/reporte/LicAvalesReporte';
import ListCubasolReporte from '../pages/indicadoresL210/reporte/ListCubasolReporte';
import ListServiturReporte from '../pages/indicadoresL210/reporte/ListServiturReporte';
import GestDemandaFlujoMaterial from '../pages/flujo_material/demanda';
import GestFamiliaFlujoMaterial from '../pages/flujo_material/familia';
import GestProductoFlujoMaterial from '../pages/flujo_material/producto';
import ReporteProductoFlujoMaterial from '../pages/flujo_material/reporteProducto';
import ReporteHistDemandaFlujoMaterial from '../pages/flujo_material/historico_demanda';
import Gestionar_CompraFlujoInformativo from '../pages/flujo_informativo/compra';
import Tipo_proveedorFlujoInformativo from '../pages/flujo_informativo/tipo_proveedor';
import ProveedorFlujoInformativo from '../pages/flujo_informativo/proveedor';
import Unidad_medidaFlujoInformativo from '../pages/flujo_informativo/unidad_medida';
import FIReporte1 from '../pages/flujo_informativo/comparar_volumenrealsxmesxfamiliaproducto';
import FIReporte2 from '../pages/flujo_informativo/comparar_precioxempresaxvolumenreal';
import FIReporte3 from '../pages/flujo_informativo/comparar_preciosxproductoxterritorio';
import FIReporte4 from '../pages/flujo_informativo/list_volumeninventarioxterritorioxfamiliaproducto';
import GestImportaciones from '../pages/gestion_importaciones/GestionarImportaciones';
import ReporteImportacionesAll from '../pages/gestion_importaciones/ReporteImportacionesAll';
import ReporteImportacionesEntidad from '../pages/gestion_importaciones/ReporteImportacionesEntidad';
import ReporteImportacionesIndicador from '../pages/gestion_importaciones/ReporteImportacionesIndicador';
import ReporteCompararPlan from '../pages/gestion_importaciones/ReporteCompararDatoAnnoAnterior';
import GestDispHabitaciones from '../pages/disponibilidad_habitaciones/GestionDisponibilidad';
import ReporteDisponibilidad from '../pages/disponibilidad_habitaciones/dispHabReport/PorcientoDisponibilidad';
import ReporteMayorIncidencia from '../pages/disponibilidad_habitaciones/dispHabReport/MayorIncidencia';
import ReporteHabNoDisponibles from '../pages/disponibilidad_habitaciones/dispHabReport/HabNoDisp';
//import GestionMantenimiento from '../pages/gestion_mantenimiento/GestionMantenimiento';
//Sistema de gestion de la calidad
//Almacenes
import Tipo_almacen from '../pages/gestion_almacenes/tipo_almacen';
import Categoria_almacen from '../pages/gestion_almacenes/categoria_almacen';
import Actividad_almacen from '../pages/gestion_almacenes/actividad_almacen';
import Temperatura from '../pages/gestion_almacenes/temperatura';
import Distribucion from '../pages/gestion_almacenes/distribucion';
import Almacen from '../pages/gestion_almacenes/almacen';
import Encargado_almacen from '../pages/gestion_almacenes/encargado_almacen';
import GAReporte1 from '../pages/gestion_almacenes/mostrar_ubicaciongeografica';
import GAReporte2 from '../pages/gestion_almacenes/mostrar_almacenescategoria';
import GAReporte3 from '../pages/gestion_almacenes/mostrar_encargadoscapacitados';
import GAReporte4 from '../pages/gestion_almacenes/mostrar_almacenesinversionmantenimiento';
import GAReporte5 from '../pages/gestion_almacenes/mostrar_almacenesestadoconstructivo';

import GestionMttoAnexo2 from '../pages/gestionMantenimiento/gestionMantenimientoAnexo2';
import GestionMttoAnexo3 from '../pages/gestionMantenimiento/gestionMantenimientoAnexo3';
import GestionMttoReporteAnexo2 from '../pages/gestionMantenimiento/gestionMantenimientoReporteAnexo2';
import GestionMttoReporteAnexo3 from '../pages/gestionMantenimiento/gestionMantenimientoReporteAnexo3';

import SistemaGestionNomenEstadoDemanda from '../pages/sistemasGestion/sistemasGestionNomenEstadosDemanda';
import SistemaGestionNomenOperadora from '../pages/sistemasGestion/sistemasGestionNomenOperadoras';
import SistemaGestionNomenTipoSistGestion from '../pages/sistemasGestion/sistemasGestionMomenTiposSistGestion';
import SistemaGestionRegistro from '../pages/sistemasGestion/sistemasGestionRegistros';
import SistemaGestionDemanda from '../pages/sistemasGestion/sistemasGestionDemandas';
import SistemaGestionNomenDemandaServicio from '../pages/sistemasGestion/sistemasGestionNomenDemandaServicio';
import SistemaGestionReportes from '../pages/sistemasGestion/sistemasGestionReportes';

import Sistema from '../pages/sistema_tecnologico/sistema_page';
import Equipo from '../pages/sistema_tecnologico/equipo_page';
import SistemaTecnologico from '../pages/sistema_tecnologico/sistema_tecnologico_page';
import CoefdisptecMenorIgualSesenta from '../pages/sistema_tecnologico/coefdisptec_menor_igual_sesenta_page';
import CoefdisptecMenorIgualNoventaCinco from '../pages/sistema_tecnologico/coefdisptec_menor_igual_noventa_y_cinco_page';
import NoContradoEnMantenimiento from '../pages/sistema_tecnologico/no_contrado_en_mantenimiento_page';
import PorcientoSistemasEnCrisis from '../pages/sistema_tecnologico/porciento_sistemas_en_crisis_page';
import TipoInstTurismoMasHSPage from '../pages/turismo_mas_higienico_seguro/TipoInstTurismoMasHSPage';
import ValorTurismoMasHSPage from '../pages/turismo_mas_higienico_seguro/ValorTurismoMasHSPage';
import OacePage from '../pages/turismo_mas_higienico_seguro/OacePage';
import EmpresaPage from '../pages/turismo_mas_higienico_seguro/EmpresaPage';
import IdiomaPage from '../pages/turismo_mas_higienico_seguro/IdiomaPage';
import UebPage from '../pages/turismo_mas_higienico_seguro/UebPage';
import CategoriaDocenteCientificaPage from '../pages/turismo_mas_higienico_seguro/CategoriaDocenteCientificaPage';
import FichaExpertoPage from '../pages/turismo_mas_higienico_seguro/FichaExpertoPage';
import ExpertoIdiomaPage from '../pages/turismo_mas_higienico_seguro/ExpertoIdiomaPage';

//Direccion de transporte
import Gestionar_MedioTransporte from '../pages/gestionar_medioTransporte/GestMedioTransporte';
import Tipo_Flota from '../pages/transporte_flujo_informativo/tipo_flota';
import Tipo_Emnautico from '../pages/transporte_flujo_informativo/tipo_emnauticos';
import Tipo_Vaservicios from '../pages/transporte_flujo_informativo/tipo_vaservicios';
import Tipo_Vespecializados from '../pages/transporte_flujo_informativo/tipo_vespecializados';
import Tipo_Vturisticos from '../pages/transporte_flujo_informativo/tipo_vturisticos';
import ClasificacionAccidentes from '../pages/transporte_flujo_informativo/clasificacion_accidentes';
import CausaAccidentes from '../pages/transporte_flujo_informativo/causa_accidentes';
import VictimaAccidentes from '../pages/transporte_flujo_informativo/victima_accidentes';
import Etmtransportes from '../pages/transporte_flujo_informativo/etmtransporte';
import Tcmtransportes from '../pages/transporte_flujo_informativo/tcmtransporte';
import Samtransportes from '../pages/transporte_flujo_informativo/samtransporte';
import Mpmtransportes from '../pages/transporte_flujo_informativo/mpmtransporte';
import Cmtransportes from '../pages/transporte_flujo_informativo/cmtransporte';
import Ubicacion_Memnautico from '../pages/transporte_flujo_informativo/ubicacion_memnauticos';
import Vehiculo_Ajeno from '../pages/transporte_flujo_informativo/vehiculo_ajeno';
import Km_Recorridos from '../pages/transporte_flujo_informativo/km_recorridos';
import Gestion_Accidente from '../pages/transporte_flujo_informativo/gestion_accidente';
import Reporte_Accidentalidad from '../pages/transporte_flujo_informativo/reportes/reporte_accidentalidad.vue';
import ReporteCDT from '../pages/gestionar_medioTransporte/reporte/ReporteCDT';
import ReporteParqueTotal from '../pages/gestionar_medioTransporte/reporte/ReporteParqueTotal';
import ReporteVehiculoMasAccidentado from '../pages/transporte_flujo_informativo/reportes/vehiculo_con_mas_accidentes';
import ReporteHorarioMasAccidentado from '../pages/transporte_flujo_informativo/reportes/horario_con_mas_accidentes';
import FichaMediosTransporte from '../pages/gestionar_medioTransporte/reporte/ReporteFichaMT';
import PlanRecape from '../pages/gestionar_planRecape/GestPlanRecape';
import ReportePlanRecape from '../pages/gestionar_planRecape/reporte/ReportePlanRecape';
import Deteccion from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/deteccion';
import Clasificacion_Evento from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/clasificacion_evento';
import Patogeno_Identificado from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/patogeno_identificado';
import Tipo_Foco from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/tipo_foco';
import Tipo_Muestra from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/tipo_muestra';
import Detectado_Por from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/detectadopor';
import Tipo_Caso from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/tipo_caso';
import Origen_Caso from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/origen_caso';
import Agente_Causal_Caso from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/agente_causal_caso';
import Tipo_Brote from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/tipo_brote';
import Origen_Brote from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/origen_brote';
import Agente_Causal_Brote from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/agente_causal_brote';
import Vehiculo_Transmision from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/vehiculo';
import Mecanismo_Transmision from '../pages/gestionar_evento_higienico_epidemiologico/nomenclador/mecanismo_transmision';
import GestionarEHE from '../pages/gestionar_evento_higienico_epidemiologico/GestionarEHE';

import ReportUnoListExperto from '../pages/turismo_mas_higienico_seguro/ReportUnoListExperto';
import ReportDosRegistroBase from '../pages/turismo_mas_higienico_seguro/ReportDosRegistroBase';

import TipoGEPage from '../pages/grupo_electrogeno/TipoGEPage';
import EstadoGEPage from '../pages/grupo_electrogeno/EstadoGEPage';
import EstadoIncGEPage from '../pages/grupo_electrogeno/EstadoIncGEPage';
import EstadoTecGEPage from '../pages/grupo_electrogeno/EstadoTecGEPage';

Vue.use(VueRouter);

let router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'login',
      component: login
    },
    {
      path: '/admin',
      component: layout,
      children: [
        { path: '/notAuthorized', name: 'notAuthorized', component: NotAuthorized },
        {
          path: '/dashboard',
          name: 'dashboard',
          component: Index,
          meta: { scopes: 'ver_dashboard', showName: 'Inicio' }
        },
        {
          path: '/persona',
          name: 'persona',
          component: Persona,
          meta: { scopes: 'seguridad', showName: 'Gestionar persona' }
        },
        {
          path: '/traza',
          name: 'traza',
          component: Traza,
          meta: { scopes: 'seguridad', showName: 'Ver traza' }
        },
        {
          path: '/role',
          name: 'role',
          component: Role,
          meta: { scopes: 'seguridad', showName: 'Gestionar roles' }
        },
        {
          path: '/usuario',
          name: 'usuario',
          component: Usuario,
          meta: { scopes: 'seguridad', showName: 'Gestionar usuario' }
        },
        {
          path: '/usuarios',
          name: 'usuarios',
          component: Usuarios,
          meta: { scopes: 'seguridad', showName: 'Usuarios Sa' }
        },
        {
          path: '/ayuda',
          name: 'ayuda',
          component: Ayuda,
          meta: { scopes: 'ver_ayuda', showName: 'Ayuda' }
        },
        {
          path: '/renglones',
          name: 'renglones',
          component: Renglones,
          meta: { scopes: 'nomencladores', showName: 'Gestionar renglones' }
        },
        {
          path: '/instalacion',
          name: 'instalacion',
          component: Instalacion,
          meta: { scopes: 'nomencladores', showName: 'Gestionar instalaciones' }
        },
        {
          path: '/indicadores',
          name: 'indicadores',
          component: Indicadores,
          meta: { scopes: 'nomencladores', showName: 'Gestionar indicadores' }
        },
        {
          path: '/osde',
          name: 'osde',
          component: Osdes,
          meta: { scopes: 'nomencladores', showName: 'Gestionar osdes' }
        },
        {
          path: '/entidad',
          name: 'entidad',
          component: Entidades,
          meta: { scopes: 'nomencladores', showName: 'Gestionar entidades' }
        },
        {
          path: '/provincia',
          name: 'provincia',
          component: Provincias,
          meta: { scopes: 'nomencladores', showName: 'Gestionar provincias' }
        },
        {
          path: '/municipio',
          name: 'municipio',
          component: Municipios,
          meta: { scopes: 'nomencladores', showName: 'Gestionar municipios' }
        },
        {
          path: '/tipo_inst',
          name: 'tipo_inst',
          component: TipoInst,
          meta: { scopes: 'nomencladores', showName: 'Gestionar tipo instalación' }
        },
        {
          path: '/oace',
          name: 'oace',
          component: Oace,
          meta: { scopes: 'nomencladores', showName: 'Oace' }
        },
        {
          path: '/cadena_hotelera',
          name: 'cadena_hotelera',
          component: Cadena_Hotelera,
          meta: { scopes: 'nomencladores', showName: 'CadenaHotelera' }
        },
        {
          path: '/ueb',
          name: 'ueb',
          component: Ueb,
          meta: { scopes: 'nomencladores', showName: 'Ueb' }
        },
        {
          path: '/delegacion_territorial',
          name: 'delegacion_territorial',
          component: Delegacion_Territorial,
          meta: { scopes: 'nomencladores', showName: 'Delegacion_Territorial' }
        },
        {
          path: '/escuela_capacitacion',
          name: 'escuela_capacitacion',
          component: Escuela_Capacitacion,
          meta: { scopes: 'nomencladores', showName: 'EscuelaCapacitacion' }
        },
        {
          path: '/oficina_empleo',
          name: 'oficina_empleo',
          component: Oficina_Empleo,
          meta: { scopes: 'nomencladores', showName: 'OficinaEmpleo' }
        },
        {
          path: '/infotur',
          name: 'infotur',
          component: Infotur,
          meta: { scopes: 'nomencladores', showName: 'Infotur' }
        },
        {
          path: '/empresa',
          name: 'empresa',
          component: Empresa,
          meta: { scopes: 'nomencladores', showName: 'Empresa' }
        },
        {
          path: '/categoria_instalacion',
          name: 'categoria_instalacion',
          component: Categoria_Instalacion,
          meta: { scopes: 'nomencladores', showName: 'CategoriaInstalacion' }
        },
        {
          path: '/sucursal',
          name: 'sucursal',
          component: Sucursal,
          meta: { scopes: 'nomencladores', showName: 'Sucursal' }
        },
        {
          path: '/fintur',
          name: 'fintur',
          component: Fintur,
          meta: { scopes: 'nomencladores', showName: 'Fintur' }
        },
        {
          path: '/onit',
          name: 'onit',
          component: Onit,
          meta: { scopes: 'nomencladores', showName: 'Onit' }
        },
        {
          path: '/utit',
          name: 'utit',
          component: Utit,
          meta: { scopes: 'nomencladores', showName: 'Utit' }
        },
        {
          path: '/escuela_ramal',
          name: 'escuela_ramal',
          component: Escuela_Ramal,
          meta: { scopes: 'nomencladores', showName: 'Escuela_Ramal' }
        },
        {
          path: '/unidad_administrativa',
          name: 'unidad_administrativa',
          component: Unidad_Administrativa,
          meta: { scopes: 'nomencladores', showName: 'Unidad_Administrativa' }
        },
        {
          path: '/sector',
          name: 'sector',
          component: Sector,
          meta: { scopes: 'nomencladores', showName: 'Gestionar sector' }
        },
        {
          path: '/costo_conformidad',
          name: 'costo_conformidad',
          component: CostoConformidad,
          meta: { scopes: 'gestionar_costo_calidad', showName: 'Gestionar costo conformidad' }
        },
        {
          path: '/costo_calidad_conformidad',
          name: 'costo_calidad_conformidad',
          component: CostoCalidadConformidad,
          meta: { scopes: 'gestionar_costo_calidad', showName: 'Gestionar costo calidad conformidad' }
        },
        {
          path: '/costo_calidad_reportes',
          name: 'costo_calidad_reportes',
          component: GestCostoCalidadReportes,
          meta: { scopes: 'gestionar_costo_calidad', showName: 'Gestionar reportes' }
        },
        {
          path: '/entity_all_cost',
          name: 'entity_all_cost',
          component: EntityAllCost,
          meta: { scopes: 'ver_costo_calidad', showName: 'Reporte entidades por costo' }
        },
        {
          path: '/entity_all_indicator',
          name: 'entity_all_indicator',
          component: EntityAllIndicator,
          meta: { scopes: 'ver_costo_calidad', showName: 'Reporte entidades por indicadores' }
        },
        {
          path: '/entity_all_costs_total',
          name: 'entity_all_costs_total',
          component: EntityAllCostTotal,
          meta: { scopes: 'ver_costo_calidad', showName: 'Reporte entidades por costos' }
        },
        {
          path: '/entity_all_cost_chart',
          name: 'entity_all_cost_chart',
          component: EntityAllCostChart,
          meta: { scopes: 'ver_costo_calidad', showName: 'Reporte entidades por costos' }
        },
        {
          path: '/turismo_mas_higienico_seguro',
          name: 'turismo_mas_higienico_seguro',
          component: TurismoHS,
          meta: { scopes: 'gestionar_ths', showName: 'Gestionar THS' }
        },
        {
          path: '/expediente_proceso',
          name: 'expediente_proceso',
          component: ExpedienteTurismoHS,
          meta: { scopes: 'gestionar_ths', showName: 'Gestionar expediente THS' }
        },
        {
          path: '/seguimiento_proceso',
          name: 'seguimiento_proceso',
          component: SeguimientoTurismoHS,
          meta: { scopes: 'gestionar_ths', showName: 'Seguimiento proceso THS' }
        },
        {
          path: '/tipo_inst_turismo_mhs',
          name: 'tipo_inst_turismo_mhs',
          component: TipoInstTurismoMasHSPage,
          meta: {scopes: 'gestionar_ths', showName: 'Tipo de instalacion'}
        },
        {
          path: '/valor_turismo_mhs',
          name: 'valor_turismo_mhs',
          component: ValorTurismoMasHSPage,
          meta: {scopes: 'gestionar_ths', showName: 'Valor'}
        },
        {
          path: '/oace_turismo_mhs',
          name: 'oace_turismo_mhs',
          component: OacePage,
          meta: {scopes: 'gestionar_ths', showName: 'Oace'}
        },
        {
          path: '/empresa_turismo_mhs',
          name: 'empresa_turismo_mhs',
          component: EmpresaPage,
          meta: {scopes: 'gestionar_ths', showName: 'Empresa'}
        },
        {
          path: '/idioma_turismo_mhs',
          name: 'idioma_turismo_mhs',
          component: IdiomaPage,
          meta: {scopes: 'gestionar_ths', showName: 'Idioma'}
        },
        {
          path: '/ueb_turismo_mhs',
          name: 'ueb_turismo_mhs',
          component: UebPage,
          meta: {scopes: 'gestionar_ths', showName: 'Ueb'}
        },
        {
          path: '/categoria_dc_turismo_mhs',
          name: 'categoria_dc_turismo_mhs',
          component: CategoriaDocenteCientificaPage,
          meta: {scopes: 'gestionar_ths', showName: 'Categorías docentes y científicas'}
        },
        {
          path: '/ficha_experto_turismo_mhs',
          name: 'ficha_experto_turismo_mhs',
          component: FichaExpertoPage,
          meta: {scopes: 'gestionar_ths', showName: 'Ficha de expertos'}
        },
        {
          path: '/exp_idioma_turismo_mhs',
          name: 'exp_idioma_turismo_mhs',
          component: ExpertoIdiomaPage,
          meta: {scopes: 'gestionar_ths', showName: 'Expertos idiomas'}
        },
        {
          path: '/report_uno_turismo_mhs',
          name: 'report_uno_turismo_mhs',
          component: ReportUnoListExperto,
          meta: {scopes: 'gestionar_ths', showName: 'Expertos'}
        },
        {
          path: '/report_dos_turismo_mhs',
          name: 'report_dos_turismo_mhs',
          component: ReportDosRegistroBase,
          meta: {scopes: 'gestionar_ths', showName: 'Registro base T+HS'}
        },
        {
          path: '/premios',
          name: 'Premios&Distinciones',
          component: GestionarPremios,
          meta: { scopes: 'gestionar_premios', showName: 'Gestionar premios y distinciones' }
        },
        {
          path: '/categoria_premio',
          name: 'categoria_premio',
          component: GestionarCategoriaPremios,
          meta: { scopes: 'gestionar_premios', showName: 'Gestionar categorías de premios' }
        },
        {
          path: '/cant_premios_osde',
          name: 'cant_premios_osde',
          component: CantPremiosOsde,
          meta: { scopes: 'ver_premios', showName: 'Reporte cantidad de premios' }
        },
        {
          path: '/cant_premios_categoria',
          name: 'cant_premios_categoria',
          component: CantPremiosCategoria,
          meta: { scopes: 'ver_premios', showName: 'Reporte cantidad de premios' }
        },
        {
          path: '/instalaciones_premiadas',
          name: 'instalaciones_premiadas',
          component: InstalacionesPremiadas,
          meta: { scopes: 'ver_premios', showName: 'Reporte instalaciones premiadas' }
        },
        {
          path: '/colectivoLider',
          name: 'colectivoLider',
          component: GestColectivoLideresCalidad,
          meta: { scopes: 'gestionar_lideres_calidad', showName: 'Gestionar líderes de calidad' }
        },
        {
          path: '/propuestaLider',
          name: 'propuestaLider',
          component: GestPropuestaLider,
          meta: { scopes: 'gestionar_lideres_calidad', showName: 'Gestionar propuesta lider' }
        },
        {
          path: '/listadoColectivoEstado',
          name: 'listadoColectivoEstado',
          component: ListaPorEstadoColectivo,
          meta: { scopes: 'ver_lideres_calidad', showName: 'Reporte listado colectivo' }
        },
        {
          path: '/filtradoColectivoEstado',
          name: 'filtradoColectivoEstado',
          component: ListaPorEstadoColectivoFiltro,
          meta: { scopes: 'ver_lideres_calidad', showName: 'Reporte filtrado por estado' }
        },
        {
          path: '/listadoPropuestaEstado',
          name: 'listadoPropuestaEstado',
          component: ListaPorEstadoPropuesta,
          meta: { scopes: 'ver_lideres_calidad', showName: 'Reporte por estado' }
        },
        {
          path: '/indlineamiento',
          name: 'GestIndicadoresL210',
          component: GestIndicadoresL210,
          meta: { scopes: 'gestionar_indicadores_lineamientos', showName: 'Gestionar Indicadores L210' }
        },
        {
          path: '/listalicaval',
          name: 'LicAvales',
          component: LicAvalesReporte,
          meta: { scopes: 'ver_indicadores_lineamientos', showName: 'Reporte lista lic. y avales' }
        },
        {
          path: '/listadocubasol',
          name: 'listacubasol',
          component: ListCubasolReporte,
          meta: { scopes: 'ver_indicadores_lineamientos', showName: 'Reporte lista Cubasol' }
        },
        {
          path: '/listadoservitur',
          name: 'ListServiturReporte',
          component: ListServiturReporte,
          meta: { scopes: 'ver_indicadores_lineamientos', showName: 'Reporte lista Servitur' }
        },
        {
          path: '/gestdemandafm',
          name: 'Gestdemandafm',
          component: GestDemandaFlujoMaterial,
          meta: { scopes: 'gestionar_flujo_material', showName: 'Gestionar demanda' }
        },
        {
          path: '/gestfamiliafm',
          name: 'Gestfamiliafm',
          component: GestFamiliaFlujoMaterial,
          meta: { scopes: 'gestionar_flujo_material', showName: 'Gestionar familia de productos' }
        },
        {
          path: '/gestproductofm',
          name: 'Gestproductofm',
          component: GestProductoFlujoMaterial,
          meta: { scopes: 'gestionar_flujo_material', showName: 'Gestionar productos' }
        },
        {
          path: '/listaproducto',
          name: 'listaproducto',
          component: ReporteProductoFlujoMaterial,
          meta: { scopes: 'ver_flujo_material', showName: 'Reporte lista productos' }
        },
        {
          path: '/historico_producto',
          name: 'historico_producto',
          component: ReporteHistDemandaFlujoMaterial,
          meta: { scopes: 'ver_flujo_material', showName: 'Reporte listado historico' }
        },
        {
          path: '/gestcomprasfi',
          name: 'gestcomprasfi',
          component: Gestionar_CompraFlujoInformativo,
          meta: { scopes: 'gestionar_flujo_informativo', showName: 'Gestioinar compras' }
        },
        {
          path: '/gestproveedorfi',
          name: 'gestproveedorfi',
          component: ProveedorFlujoInformativo,
          meta: { scopes: 'gestionar_flujo_informativo', showName: 'Gestionar proveedor' }
        },
        {
          path: '/gesttipoproveedorfi',
          name: 'gesttipoproveedorfi',
          component: Tipo_proveedorFlujoInformativo,
          meta: { scopes: 'gestionar_flujo_informativo', showName: 'Tipo de proveedor' }
        },
        {
          path: '/gestunidadmedidafi',
          name: 'gestunidadmedidafi',
          component: Unidad_medidaFlujoInformativo,
          meta: { scopes: 'gestionar_flujo_informativo', showName: 'Unidad de medida' }
        },
        {
          path: '/flujo_informativo/reporte1',
          name: 'fi_reporte1',
          component: FIReporte1,
          meta: { scopes: 'ver_flujo_informativo', showName: 'Reporte_Flujo_Informativo_1' }
        },
        {
          path: '/flujo_informativo/reporte2',
          name: 'fi_reporte2',
          component: FIReporte2,
          meta: { scopes: 'ver_flujo_informativo', showName: 'Reporte_Flujo_Informativo_2' }
        },
        {
          path: '/flujo_informativo/reporte3',
          name: 'fi_reporte3',
          component: FIReporte3,
          meta: { scopes: 'ver_flujo_informativo', showName: 'Reporte_Flujo_Informativo_3' }
        },
        {
          path: '/flujo_informativo/reporte4',
          name: 'fi_reporte4',
          component: FIReporte4,
          meta: { scopes: 'ver_flujo_informativo', showName: 'Reporte_Flujo_Informativo_4' }
        },
        {
          path: '/gestimportaciones',
          name: 'gestimportaciones',
          component: GestImportaciones,
          meta: { scopes: 'gestionar_importaciones', showName: 'Gestionar importaciones' }
        },
        {
          path: '/allimportacionesrep',
          name: 'allimportacionesrep',
          component: ReporteImportacionesAll,
          meta: { scopes: 'ver_importaciones', showName: 'Reporte importaciones' }
        },
        {
          path: '/importacionesind',
          name: 'importacionesind',
          component: ReporteImportacionesIndicador,
          meta: { scopes: 'ver_importaciones', showName: 'Reporte importaciones' }
        },
        {
          path: '/importacionesent',
          name: 'importacionesent',
          component: ReporteImportacionesEntidad,
          meta: { scopes: 'ver_importaciones', showName: 'Reporte importaciones por entidad' }
        },
        {
          path: '/compararplanimp',
          name: 'compararplanimportaciones',
          component: ReporteCompararPlan,
          meta: { scopes: 'ver_importaciones', showName: 'Reporte Comparar plan' }
        },
        {
          path: '/disponibilidad',
          name: 'disponibilidad',
          component: GestDispHabitaciones,
          meta: { scopes: 'gestionar_disp_habitaciones', showName: 'Gest disponibilidad de habitaciones' }
        },
        {
          path: '/porcientoDisponibilidad',
          name: 'porcientoDisponibilidad',
          component: ReporteDisponibilidad,
          meta: { scopes: 'ver_disp_habitaciones', showName: 'Reporte disponibilidad de habitaciones' }
        },
        {
          path: '/mayorIncidencia',
          name: 'mayorIncidencia',
          component: ReporteMayorIncidencia,
          meta: { scopes: 'ver_disp_habitaciones', showName: 'Reporte mayor incidencia' }
        },
        {
          path: '/habNoDisp',
          name: 'habNoDisp',
          component: ReporteHabNoDisponibles,
          meta: { scopes: 'ver_disp_habitaciones', showName: 'Reporte habitaciones no disponibles' }
        },
        {
          path: '/tipo_almacen',
          name: 'tipo_almacen',
          component: Tipo_almacen,
          meta: { scopes: 'gestionar_almacenes', showName: 'Tipo_almacenes' }
        },
        {
          path: '/categoria_almacen',
          name: 'categoria_almacen',
          component: Categoria_almacen,
          meta: { scopes: 'gestionar_almacenes', showName: 'Categoria_almacenes' }
        },
        {
          path: '/actividad_almacen',
          name: 'actividad_almacen',
          component: Actividad_almacen,
          meta: { scopes: 'gestionar_almacenes', showName: 'Actividad_almacenes' }
        },
        {
          path: '/temperatura',
          name: 'temperatura',
          component: Temperatura,
          meta: { scopes: 'gestionar_almacenes', showName: 'Temperaturas' }
        },
        {
          path: '/distribucion',
          name: 'distribucion',
          component: Distribucion,
          meta: { scopes: 'gestionar_almacenes', showName: 'Distribuciones' }
        },
        {
          path: '/almacen',
          name: 'almacen',
          component: Almacen,
          meta: { scopes: 'gestionar_almacenes', showName: 'Almacenes' }
        },
        {
          path: '/encargado_almacen',
          name: 'encargado_almacen',
          component: Encargado_almacen,
          meta: { scopes: 'gestionar_almacenes', showName: 'Encargado_almacenes' }
        },
        {
          path: '/almacenes/reporte1',
          name: 'ga_reporte1',
          component: GAReporte1,
          meta: { scopes: 'ver_almacenes', showName: 'Reporte_Almacenes_1' }
        },
        {
          path: '/almacenes/reporte2',
          name: 'ga_reporte2',
          component: GAReporte2,
          meta: { scopes: 'ver_almacenes', showName: 'Reporte_Almacenes_2' }
        },
        {
          path: '/almacenes/reporte3',
          name: 'ga_reporte3',
          component: GAReporte3,
          meta: { scopes: 'ver_almacenes', showName: 'Reporte_Almacenes_3' }
        },
        {
          path: '/almacenes/reporte4',
          name: 'ga_reporte4',
          component: GAReporte4,
          meta: { scopes: 'ver_almacenes', showName: 'Reporte_Almacenes_4' }
        },
        {
          path: '/almacenes/reporte5',
          name: 'ga_reporte5',
          component: GAReporte5,
          meta: { scopes: 'ver_almacenes', showName: 'Reporte_Almacenes_5' }
        },
        {
          path: '/gestionMttoAnexo2',
          name: 'gestionMttoAnexo2',
          component: GestionMttoAnexo2,
          meta: { scopes: 'gestion_mantenimiento', showName: 'GestionMttoAnexo2' }
        },
        {
          path: '/gestionMttoAnexo3',
          name: 'gestionMttoAnexo3',
          component: GestionMttoAnexo3,
          meta: { scopes: 'gestion_mantenimiento', showName: 'GestionMttoAnexo3' }
        },
        {
          path: '/gestionMttoReporteAnexo2',
          name: 'gestionMttoReporteAnexo2',
          component: GestionMttoReporteAnexo2,
          meta: { scopes: 'gestion_mantenimiento', showName: 'GestionMttoReporteAnexo2' }
        },
        {
          path: '/gestionMttoReporteAnexo3',
          name: 'gestionMttoReporteAnexo3',
          component: GestionMttoReporteAnexo3,
          meta: { scopes: 'gestion_mantenimiento', showName: 'GestionMttoReporteAnexo3' }
        },
        {
          path: '/sistemaGestionNomenEstadoDemanda',
          name: 'sistemaGestionNomenEstadoDemanda',
          component: SistemaGestionNomenEstadoDemanda,
          meta: { scopes: 'gestionar_sist_gestcalidad', showName: 'SistemaGestionNomenEstadoDemanda' }
        },
        {
          path: '/sistemaGestionNomenOperadora',
          name: 'sistemaGestionNomenOperadora',
          component: SistemaGestionNomenOperadora,
          meta: { scopes: 'gestionar_sist_gestcalidad', showName: 'SistemaGestionNomenOperadora' }
        },
        {
          path: '/sistemaGestionNomenTipoSistGestion',
          name: 'sistemaGestionNomenTipoSistGestion',
          component: SistemaGestionNomenTipoSistGestion,
          meta: { scopes: 'gestionar_sist_gestcalidad', showName: 'SistemaGestionNomenTipoSistGestion' }
        },
        {
          path: '/sistemaGestionRegistro',
          name: 'sistemaGestionRegistro',
          component: SistemaGestionRegistro,
          meta: { scopes: 'gestionar_sist_gestcalidad', showName: 'SistemaGestionRegistro' }
        },
        {
          path: '/sistemaGestionDemanda',
          name: 'sistemaGestionDemanda',
          component: SistemaGestionDemanda,
          meta: { scopes: 'gestionar_sist_gestcalidad', showName: 'SistemaGestionDemanda' }
        },
        {
          path: '/sistemaGestionNomenDemandaServicio',
          name: 'sistemaGestionNomenDemandaServicio',
          component: SistemaGestionNomenDemandaServicio,
          meta: { scopes: 'gestionar_sist_gestcalidad', showName: 'SistemaGestionNomenDemandaServicio' }
        }
        ,
        {
          path: '/sistemaGestionReportes',
          name: 'sistemaGestionReportes',
          component: SistemaGestionReportes,
          meta: { scopes: 'gestionar_sist_gestcalidad', showName: 'SistemaGestionReportes' }
        },
        {
          path: '/sistemaTec',
          name: 'sistemaTec',
          component: Sistema,
          meta: { scopes: 'gestion_sistemas_tec', showName: 'Sistema' }
        },
        {
          path: '/equipoTec',
          name: 'equipoTec',
          component: Equipo,
          meta: { scopes: 'gestion_sistemas_tec', showName: 'Equipo' }
        },
        {
          path: '/sistemaTecnologico',
          name: 'sistemaTecnologico',
          component: SistemaTecnologico,
          meta: { scopes: 'gestion_sistemas_tec', showName: 'sistemaTecnologico' }
        },
        {
          path: '/sistema_tecnologico_report_one',
          name: 'sistema_tecnologico_report_one',
          component: CoefdisptecMenorIgualSesenta,
          meta: { scopes: 'gestion_sistemas_tec', showName: 'sistema_tecnologico_report_one' }
        },
        {
          path: '/sistema_tecnologico_report_two',
          name: 'sistema_tecnologico_report_two',
          component: PorcientoSistemasEnCrisis,
          meta: { scopes: 'gestion_sistemas_tec', showName: 'sistema_tecnologico_report_two' }
        },
        {
          path: '/sistema_tecnologico_report_three',
          name: 'sistema_tecnologico_report_three',
          component: CoefdisptecMenorIgualNoventaCinco,
          meta: { scopes: 'gestion_sistemas_tec', showName: 'sistema_tecnologico_report_three' }
        },
        {
          path: '/sistema_tecnologico_report_four',
          name: 'sistema_tecnologico_report_four',
          component: NoContradoEnMantenimiento,
          meta: { scopes: 'gestion_sistemas_tec', showName: 'sistema_tecnologico_report_four' }
        },
        {
          path: '/transporte',
          name: 'GestMedioTransporte',
          component: Gestionar_MedioTransporte,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'GestMedioTransporte' }
        },
        {
          path: '/tipo_flota',
          name: 'tipo_flota',
          component: Tipo_Flota,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'TipoFlota' }
        },
        {
          path: '/tipo_emnautico',
          name: 'tipo_emnautico',
          component: Tipo_Emnautico,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'TipoEmnautico' }
        },
        {
          path: '/tipo_vaservicio',
          name: 'tipo_vaservicio',
          component: Tipo_Vaservicios,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'TipoVaservicios' }
        },
        {
          path: '/tipo_vespecializado',
          name: 'tipo_vespecializado',
          component: Tipo_Vespecializados,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'TipoVespecializado' }
        },
        {
          path: '/tipo_vturistico',
          name: 'tipo_vturistico',
          component: Tipo_Vturisticos,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'TipoVturisticos' }
        },
        {
          path: '/clasificacion_accidente',
          name: 'clasificacion_accidente',
          component: ClasificacionAccidentes,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'ClasificacionAccidentes' }
        },
        {
          path: '/causa_accidente',
          name: 'causa_accidente',
          component: CausaAccidentes,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'CausaAccidentes' }
        },
        {
          path: '/victima_accidente',
          name: 'victima_accidente',
          component: VictimaAccidentes,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'VictimaAccidentes' }
        },
        {
          path: '/etmtransporte',
          name: 'etmtransporte',
          component: Etmtransportes,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'Etmtransportes' }
        },
        {
          path: '/tcmtransporte',
          name: 'tcmtransporte',
          component: Tcmtransportes,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'Tcmtransportes' }
        },
        {
          path: '/samtransporte',
          name: 'samtransporte',
          component: Samtransportes,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'Samtransportes' }
        },
        {
          path: '/mpmtransporte',
          name: 'mpmtransporte',
          component: Mpmtransportes,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'Mpmtransportes' }
        },
        {
          path: '/cmtransporte',
          name: 'cmtransporte',
          component: Cmtransportes,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'Cmtransportes' }
        },
        {
          path: '/ubicacion_memnautico',
          name: 'ubicacion_memnautico',
          component: Ubicacion_Memnautico,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'UbicacionMemnautico' }
        },
        {
          path: '/vehiculo_ajeno',
          name: 'vehiculo_ajeno',
          component: Vehiculo_Ajeno,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'VehiculoAjeno' }
        },
        {
          path: '/km_recorridos',
          name: 'km_recorridos',
          component: Km_Recorridos,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'KmRecorridos' }
        },
        {
          path: '/gestion_accidente',
          name: 'gestion_accidente',
          component: Gestion_Accidente,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'GestionAccidente' }
        },
        {
          path: '/reportecdt',
          name: 'ReporteCDT',
          component: ReporteCDT,
          meta: { scopes: 'ver_dir_transporte_fi', showName: 'ReporteCDT' }
        },
        {
          path: '/reportept',
          name: 'ReporteParqueTotal',
          component: ReporteParqueTotal,
          meta: { scopes: 'ver_dir_transporte_fi', showName: 'ReporteParqueTotal' }
        },
        {
          path: '/vehiculoconmasaccidentes',
          name: 'vehiculoconmasaccidentes',
          component: ReporteVehiculoMasAccidentado,
          meta: { scopes: 'ver_dir_transporte_fi', showName: 'ReporteVehiculoMasAccidentado' }
        },
        {
          path: '/horarioconmasaccidentes',
          name: 'horarioconmasaccidentes',
          component: ReporteHorarioMasAccidentado,
          meta: { scopes: 'ver_dir_transporte_fi', showName: 'ReporteHorarioMasAccidentado' }
        },
        {
          path: '/reporte_accidentalidad',
          name: 'reporte_accidentalidad',
          component: Reporte_Accidentalidad,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'ReporteAccidentalidad' }
        },
        {
          path: '/reportefmt',
          name: 'FichaMediosTransporte',
          component: FichaMediosTransporte,
          meta: { scopes: 'ver_dir_transporte_fi', showName: 'FichaMediosTransporte' }
        },
        {
          path: '/planrecape',
          name: 'PlanRecape',
          component: PlanRecape,
          meta: { scopes: 'gestionar_dir_transporte_fi', showName: 'PlanRecape' }
        },
        {
          path: '/planrecape_reporte',
          name: 'ReportePlanRecape',
          component: ReportePlanRecape,
          meta: { scopes: 'ver_dir_transporte_fi', showName: 'ReportePlanRecape' }
        },
        {
          path: '/deteccion',
          name: 'deteccion',
          component: Deteccion,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'Deteccion' }
        },
        {
          path: '/clasificacion_evento',
          name: 'clasificacion_evento',
          component: Clasificacion_Evento,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'ClasificacionEvento' }
        },
        {
          path: '/patogeno_identificado',
          name: 'patogeno_identificado',
          component: Patogeno_Identificado,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'PatogenoIdentificado' }
        },
        {
          path: '/tipo_foco',
          name: 'tipo_foco',
          component: Tipo_Foco,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'TipoFoco' }
        },
        {
          path: '/tipo_muestra',
          name: 'tipo_muestra',
          component: Tipo_Muestra,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'TipoMuestra' }
        },
        {
          path: '/detectado_por',
          name: 'detectado_por',
          component: Detectado_Por,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'DetectadoPor' }
        },
        {
          path: '/tipo_caso',
          name: 'tipo_caso',
          component: Tipo_Caso,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'TipoCaso' }
        },
        {
          path: '/origen_caso',
          name: 'origen_caso',
          component: Origen_Caso,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'OrigenCaso' }
        },
        {
          path: '/agente_causal_caso',
          name: 'agente_causal_caso',
          component: Agente_Causal_Caso,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'AgenteCausalCaso' }
          // meta: { scopes: 'ver_dir_higienico_epidemiologico', showName: 'Deteccion' }
        },
        {
          path: '/tipo_brote',
          name: 'tipo_brote',
          component: Tipo_Brote,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'TipoBrote' }
        },
        {
          path: '/origen_brote',
          name: 'origen_brote',
          component: Origen_Brote,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'OrigenBrote' }
        },
        {
          path: '/agente_causal_brote',
          name: 'agente_causal_brote',
          component: Agente_Causal_Brote,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'AgenteCausalBrote' }
        },
        {
          path: '/vehiculo_transmision',
          name: 'vehiculo_transmision',
          component: Vehiculo_Transmision,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'VehiculoTransmision' }
        },
        {
          path: '/mecanismo_transmision',
          name: 'mecanismo_transmision',
          component: Mecanismo_Transmision,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'MecanismoTransmision' }
          // meta: { scopes: 'ver_dir_higienico_epidemiologico', showName: '...' }
        },
        {
          path: '/gestionehe',
          name: 'gestion_ehe',
          component: GestionarEHE,
          meta: { scopes: 'gestionar_dir_higienico_epidemiologico', showName: 'GestionarEHE' }
        },
        {
          path: '/tipo_ge',
          name: 'tipo_ge',
          component: TipoGEPage,
          meta: {scopes: 'nomencladores', showName: 'Tipo de grupo electrógeno'}
        },
        {
          path: '/estado_ge',
          name: 'estado_ge',
          component: EstadoGEPage,
          meta: {scopes: 'nomencladores', showName: 'estado de grupo electrógeno'}
        },
        {
          path: '/estadoinc_ge',
          name: 'estadoinc_ge',
          component: EstadoIncGEPage,
          meta: {scopes: 'nomencladores', showName: 'Estado de incidencia de grupo electrógeno'}
        },
        {
          path: '/estadotec_ge',
          name: 'estadotec_ge',
          component: EstadoTecGEPage,
          meta: {scopes: 'nomencladores', showName: 'Estado técnico de grupo electrógeno'}
        },
      ]
    }
  ]
})

router.beforeEach((to, from, next) => {
  if (to.name === 'login') { next() } else {
    if (to.name !== 'notAuthorized') {
      const scopes = sessionStorage.getItem('scopes')
      if (scopes.includes(to.meta.scopes)) { next() } else { next('notAuthorized') }
    } else { next() }
  }
})

export default router;
