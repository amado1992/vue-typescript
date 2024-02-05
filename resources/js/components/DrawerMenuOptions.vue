<template>
  <div>
    <q-list>
      <q-item>
        <q-item-section avatar>
          <q-avatar>
            <img :src="'images/zunpms_indigo.png'" />
          </q-avatar>
        </q-item-section>
        <q-item-section>
          <q-item-label class="text-grey-3">{{ username }}</q-item-label>
          <q-item-label caption class="text-weight-light text-grey-3"
            >{{ email }}
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
    <q-separator inset dark />

    <q-list class="nav">
      <q-expansion-item
        v-for="(parent, index) in menu_links"
        :key="index"
        group="parentgroup"
        :icon="parent.icon"
        v-show="scopes.includes(parent.scope)"
        v-model="selected_model[index]"
        :label="replaceUnderlineToSpace(index)"
        class="q-pt-xs"
        header-class="text-subtitle1 text-grey-3"
        expand-separator
        expand-icon="mdi-chevron-right"
        expanded-icon="mdi-chevron-down"
        dark
        dense
      >
        <q-expansion-item
          v-for="(child, index) in parent.procesos"
          v-show="scopes.includes(child.scope)"
          :key="index"
          group="childgroup"
          :icon="child.icon"
          :label="child.name"
          header-class="text-subtitle2 text-grey-3"
          dense
          dense-toggle
          dark
          expand-icon="mdi-chevron-right"
          expanded-icon="mdi-chevron-down"
          :content-inset-level="0.2"
        >
          <!-- Nomencladores -->
          <q-expansion-item
            v-if="child.nomencladores.length !== 0"
            group="nomencladoresgroup"
            icon="mdi-sticker-check-outline"
            label="Nomencladores"
            header-class="text-subtitle2 text-grey-3"
            dense
            dark
            expand-icon="mdi-chevron-right"
            expanded-icon="mdi-chevron-down"
            :content-inset-level="0.1"
          >
            <q-item
              v-for="(nomencladores, index) in child.nomencladores"
              :key="index"
              clickable
              v-ripple
              v-show="scopes.includes(nomencladores.scope)"
              dense
              :to="nomencladores.route"
              active-class="link-active"
              dark
            >
              <q-item-section avatar>
                <q-icon color="" :name="nomencladores.icon" />
              </q-item-section>
              <q-item-section>{{ nomencladores.name }}</q-item-section>
            </q-item>
          </q-expansion-item>
          <!-- Nomencladores End-->
          <!-- Reportes-->
          <q-expansion-item
            v-if="child.reportes.length !== 0"
            group="reportesgroup"
            icon="mdi-chart-box-outline"
            label="Reportes"
            header-class="text-subtitle2 text-grey-3"
            dense
            dark
            :content-inset-level="0.1"
            expand-icon="mdi-chevron-right"
            expanded-icon="mdi-chevron-down"
          >
            <q-item
              v-for="(reportes, index) in child.reportes"
              v-show="scopes.includes(reportes.scope)"
              :key="index"
              clickable
              v-ripple
              dense
              :to="reportes.route"
              class="text-grey-3"
              active-class="link-active"
              dark
            >
              <q-item-section avatar>
                <q-icon color="" :name="reportes.icon" />
              </q-item-section>
              <q-item-section>{{ reportes.name }}</q-item-section>
            </q-item>
          </q-expansion-item>
          <!-- Reportes End-->
          <q-item
            v-for="(kid, index) in child.routes"
            v-show="scopes.includes(kid.scope)"
            :key="index"
            clickable
            v-ripple
            dense
            :to="kid.route"
            class="text-grey-3"
            active-class="link-active"
            dark
          >
            <q-item-section avatar>
              <q-icon color="" :name="kid.icon" />
            </q-item-section>
            <q-item-section>{{ kid.name }}</q-item-section>
          </q-item>
        </q-expansion-item>
      </q-expansion-item>
    </q-list>
  </div>
</template>

<script>
export default {
  name: 'DrawerMenuOptions',
  props: ['username', 'email', 'scopes'],
  data() {
    return {
      myrouter: '',
      selected_model: {},
      menu_links: {
        Dirección_de_Calidad: {
          icon: 'mdi-creation',
          scope: 'ver_dir_calidad',
          state: false,
          procesos: [
            {
              icon: 'attach_money',
              name: 'Costo de Calidad',
              scope: 'ver_costo_calidad',
              state: false,
              routes: [
                {
                  route: { name: 'costo_conformidad' },
                  icon: 'mdi-circle-medium',
                  name: 'Conformidad y no Conformidad',
                  scope: 'gestionar_costo_calidad',
                  state: false,
                },
                {
                  route: { name: 'costo_calidad_conformidad' },
                  icon: 'mdi-circle-medium',
                  name: 'Conformidad y No Conformidad calidad',
                  scope: 'gestionar_costo_calidad',
                  state: false,
                },
                {
                  route: { name: 'costo_calidad_reportes' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestión de Reportes',
                  scope: 'gestionar_costo_calidad',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'entity_all_cost' },
                  icon: 'mdi-circle-medium',
                  name: 'Costos calidad por osdes',
                  scope: 'ver_costo_calidad',
                  state: false,
                },
                {
                  route: { name: 'entity_all_indicator' },
                  icon: 'mdi-circle-medium',
                  name: 'Indicadores por osdes',
                  scope: 'ver_costo_calidad',
                  state: false,
                },
                {
                  route: { name: 'entity_all_costs_total' },
                  icon: 'mdi-circle-medium',
                  name: 'Totales de costos calidad',
                  scope: 'ver_costo_calidad',
                  state: false,
                },
                {
                  route: { name: 'entity_all_cost_chart' },
                  icon: 'mdi-circle-medium',
                  name: 'Costos por osdes',
                  scope: 'ver_costo_calidad',
                  state: false,
                },
              ],
              nomencladores: [],
            },
            {
              icon: 'mdi-medical-bag',
              name: 'Turismo más higiénico y seguro',
              scope: 'ver_ths',
              state: false,
              routes: [
                {
                  route: { name: 'turismo_mas_higienico_seguro' },
                  icon: 'mdi-circle-medium',
                  name: 'Turismo más higiénico y seguro',
                  scope: 'gestionar_ths',
                  state: false,
                },
                /*{
                  route: {name:'expediente_proceso'},
                  icon: 'mdi-circle-medium',
                  name: 'Expedientes del proceso T+HS',
                  scope: 'gestionar_ths',
                  state: false,
                },
                {
                  route: {name:'seguimiento_proceso'},
                  icon: 'mdi-circle-medium',
                  name: 'Seguimiento del proceso T+HS',
                  scope: 'gestionar_ths',
                  state: false,
                },*/
              ],
              nomencladores: [
               {
                  route: {name:'oace_turismo_mhs'},
                  icon: 'mdi-circle-medium',
                  name: 'Organismo de la Administración Central del Estado (OACE)',
                  scope: 'gestionar_ths',
                  state: false,
                },
                 {
                  route: {name:'empresa_turismo_mhs'},
                  icon: 'mdi-circle-medium',
                  name: 'Empresas asociadas a Osdes',
                  scope: 'gestionar_ths',
                  state: false,
                },
                 {
                  route: {name:'ueb_turismo_mhs'},
                  icon: 'mdi-circle-medium',
                  name: 'Uebs asociadas a empresas',
                  scope: 'gestionar_ths',
                  state: false,
                },
                {
                  route: {name:'idioma_turismo_mhs'},
                  icon: 'mdi-circle-medium',
                  name: 'Idiomas',
                  scope: 'gestionar_ths',
                  state: false,
                },
                  {
                  route: {name:'categoria_dc_turismo_mhs'},
                  icon: 'mdi-circle-medium',
                  name: 'Categorías docentes y científicas',
                  scope: 'gestionar_ths',
                  state: false,
                },
                 {
                  route: {name:'ficha_experto_turismo_mhs'},
                  icon: 'mdi-circle-medium',
                  name: 'Ficha de expertos',
                  scope: 'gestionar_ths',
                  state: false,
                },
                  {
                  route: {name:'exp_idioma_turismo_mhs'},
                  icon: 'mdi-circle-medium',
                  name: 'Expertos idiomas',
                  scope:'gestionar_ths',
                  state: false,
                },
               {
                  route: {name:'valor_turismo_mhs'},
                  icon: 'mdi-circle-medium',
                  name: 'Valores',
                  scope: 'gestionar_ths',
                  state: false,
                },
                {
                  route: {name:'tipo_inst_turismo_mhs'},
                  icon: 'mdi-circle-medium',
                  name: 'Tipos de instalaciones',
                  scope: 'gestionar_ths',
                  state: false,
                }],
                reportes: [
                {
                  route: { name: 'report_uno_turismo_mhs' },
                  icon: 'mdi-circle-medium',
                  name: 'Expertos',
                  scope: 'gestionar_ths',
                  state: false,
                },
                {
                  route: { name: 'report_dos_turismo_mhs' },
                  icon: 'mdi-circle-medium',
                  name:
                    'Registro base T+HS',
                  scope: 'gestionar_ths',
                  state: false,
                }
              ],
            },
            {
              icon: 'mdi-certificate',
              name: 'Premios y Distinciones',
              scope: 'ver_premios',
              state: false,
              routes: [
                {
                  route: { name: 'Premios&Distinciones' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar premios y distinciones',
                  scope: 'gestionar_premios',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'cant_premios_osde' },
                  icon: 'mdi-circle-medium',
                  name: 'Cant. de premios por OSDE',
                  scope: 'ver_premios',
                  state: false,
                },
                {
                  route: { name: 'cant_premios_categoria' },
                  icon: 'mdi-circle-medium',
                  name: 'Cant. de premios por categoría',
                  scope: 'ver_premios',
                  state: false,
                },
                {
                  route: { name: 'instalaciones_premiadas' },
                  icon: 'mdi-circle-medium',
                  name: '% de instalaciones premiadas',
                  scope: 'ver_premios',
                  state: false,
                },
              ],
              nomencladores: [
                {
                  route: { name: 'categoria_premio' },
                  icon: 'mdi-circle-medium',
                  name: 'Categoría de premios',
                  scope: 'gestionar_premios',
                  state: false,
                },
              ],
            },
            {
              icon: 'mdi-account-group',
              name: 'Movimiento Líderes de Calidad',
              scope: 'ver_lideres_calidad',
              state: false,
              routes: [
                {
                  route: { name: 'colectivoLider' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar colectivos líderes de la calidad',
                  scope: 'gestionar_lideres_calidad',
                  state: false,
                },
                {
                  route: { name: 'propuestaLider' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar propuestas de líderes de la calidad',
                  scope: 'gestionar_lideres_calidad',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'listadoColectivoEstado' },
                  icon: 'mdi-circle-medium',
                  name: 'Listado de colectivos por estados',
                  scope: 'ver_lideres_calidad',
                  state: false,
                },
                {
                  route: { name: 'filtradoColectivoEstado' },
                  icon: 'mdi-circle-medium',
                  name: 'Listado de colectivos por filtros',
                  scope: 'ver_lideres_calidad',
                  state: false,
                },
                {
                  route: { name: 'listadoPropuestaEstado' },
                  icon: 'mdi-circle-medium',
                  name: 'Listado de propuestas de líderes por estados',
                  scope: 'ver_lideres_calidad',
                  state: false,
                },
              ],
              nomencladores: [],
            },
            {
              icon: 'mdi-auto-fix',
              name: 'Sistema de Gestión de la Calidad',
              scope: 'ver_sist_gestcalidad',
              state: false,
              routes: [
                {
                  route: { name: 'sistemaGestionRegistro' },
                  icon: 'mdi-circle-medium',
                  name: 'Registros',
                  scope: 'ver_sist_gestcalidad',
                  state: false,
                },
                {
                  route: { name: 'sistemaGestionDemanda' },
                  icon: 'mdi-circle-medium',
                  name: 'Demandas',
                  scope: 'ver_sist_gestcalidad',
                  state: false,
                },
              ],
              reportes: [],
              nomencladores: [
                {
                  route: { name: 'sistemaGestionNomenEstadoDemanda' },
                  icon: 'mdi-circle-medium',
                  name: 'Estados de Demanda',
                  scope: 'ver_sist_gestcalidad',
                  state: false,
                },
                {
                  route: { name: 'sistemaGestionNomenOperadora' },
                  icon: 'mdi-circle-medium',
                  name: 'Operadoras',
                  scope: 'ver_sist_gestcalidad',
                  state: false,
                },
                {
                  route: { name: 'sistemaGestionNomenTipoSistGestion' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipos de Sistemas de Gestión',
                  scope: 'ver_sist_gestcalidad',
                  state: false,
                },
              ],
            },
            {
              icon: 'mdi-book',
              name: 'Indicadores L210',
              scope: 'ver_indicadores_lineamientos',
              state: false,
              routes: [
                {
                  route: { name: 'GestIndicadoresL210' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar indicadores',
                  scope: 'gestionar_indicadores_lineamientos',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'LicAvales' },
                  icon: 'mdi-circle-medium',
                  name: 'Listado de licencias y avales',
                  scope: 'ver_indicadores_lineamientos',
                  state: false,
                },
                {
                  route: { name: 'listacubasol' },
                  icon: 'mdi-circle-medium',
                  name: 'Listado Cubasol',
                  scope: 'ver_indicadores_lineamientos',
                  state: false,
                },
                {
                  route: { name: 'ListServiturReporte' },
                  icon: 'mdi-circle-medium',
                  name: 'Listado Servitur',
                  scope: 'ver_indicadores_lineamientos',
                  state: false,
                },
              ],
              nomencladores: [],
            },
            {
              icon: 'mdi-doctor',
              name: 'Evento Higiénico-Epidemiológico',
              scope: 'ver_dir_higienico_epidemiologico',
              state: false,
              routes: [
                {
                  route: { name: 'gestion_ehe' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar E.H.E',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
              ],
              reportes: [
                // {
                //   route: { name: 'Reporte1' },
                //   icon: 'mdi-circle-medium',
                //   name: 'Listado de ...',
                //   scope: 'ver_dir_higienico_epidemiologico',
                //   state: false,
                // },
              ],
              nomencladores: [
                {
                  route: { name: 'deteccion' },
                  icon: 'mdi-circle-medium',
                  name: 'Detección',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'detectado_por' },
                  icon: 'mdi-circle-medium',
                  name: 'Detectado por',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'clasificacion_evento' },
                  icon: 'mdi-circle-medium',
                  name: 'Clasificación de eventos',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'patogeno_identificado' },
                  icon: 'mdi-circle-medium',
                  name: 'Patógeno identificado',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'tipo_foco' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipo de focos',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'tipo_muestra' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipo de muestras',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'tipo_caso' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipo de casos',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'origen_caso' },
                  icon: 'mdi-circle-medium',
                  name: 'Origen de casos',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'agente_causal_caso' },
                  icon: 'mdi-circle-medium',
                  name: 'Agente causal de caso',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'tipo_brote' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipo de brotes',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'origen_brote' },
                  icon: 'mdi-circle-medium',
                  name: 'Origen de brotes',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'agente_causal_brote' },
                  icon: 'mdi-circle-medium',
                  name: 'Agente causal de brote',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'vehiculo_transmision' },
                  icon: 'mdi-circle-medium',
                  name: 'Vehiculo de transmision',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
                {
                  route: { name: 'mecanismo_transmision' },
                  icon: 'mdi-circle-medium',
                  name: 'Mecanismo de transmisión',
                  scope: 'gestionar_dir_higienico_epidemiologico',
                  state: false,
                },
              ],
            },
          ],
        },
        Dirección_de_Logística: {
          icon: 'mdi-key-star',
          scope: 'ver_dir_logistica',
          state: false,
          procesos: [
            {
              icon: 'mdi-resistor-nodes',
              name: 'Flujo material',
              scope: 'ver_flujo_material',
              routes: [
                {
                  route: { name: 'Gestfamiliafm' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar familias',
                  scope: 'gestionar_flujo_material',
                  state: false,
                },
                {
                  route: { name: 'Gestproductofm' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar productos',
                  scope: 'gestionar_flujo_material',
                  state: false,
                },
                {
                  route: { name: 'Gestdemandafm' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar demanda',
                  scope: 'gestionar_flujo_material',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'listaproducto' },
                  icon: 'mdi-circle-medium',
                  name: 'Listado productos',
                  scope: 'ver_flujo_material',
                  state: false,
                },
                {
                  route: { name: 'historico_producto' },
                  icon: 'mdi-circle-medium',
                  name: 'Historico de productos',
                  scope: 'ver_flujo_material',
                  state: false,
                },
              ],
              nomencladores: [],
            },
            {
              icon: 'mdi-resistor-nodes',
              name: 'Flujo informativo',
              scope: 'ver_flujo_informativo',
              routes: [
                {
                  route: { name: 'gestproveedorfi' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar proveedor',
                  scope: 'gestionar_flujo_informativo',
                  state: false,
                },
                {
                  route: { name: 'gestcomprasfi' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar compras',
                  scope: 'gestionar_flujo_informativo',
                  state: false,
                }
              ],
              nomencladores: [
                {
                  route: { name: 'gesttipoproveedorfi' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipo proveedor',
                  scope: 'gestionar_flujo_informativo',
                  state: false,
                },
                {
                  route: { name: 'gestunidadmedidafi' },
                  icon: 'mdi-circle-medium',
                  name: 'Unidad de medida',
                  scope: 'gestionar_flujo_informativo',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'fi_reporte1' },
                  icon: 'mdi-circle-medium',
                  name: 'Volumen real por fechas y osdes',
                  scope: 'ver_flujo_informativo',
                  state: false,
                },
                {
                  route: { name: 'fi_reporte2' },
                  icon: 'mdi-circle-medium',
                  name: 'Precios de los productos entre las empresas',
                  scope: 'ver_flujo_informativo',
                  state: false,
                },
                {
                  route: { name: 'fi_reporte3' },
                  icon: 'mdi-circle-medium',
                  name: 'Precios por productos y territorios',
                  scope: 'ver_flujo_informativo',
                  state: false,
                },
                {
                  route: { name: 'fi_reporte4' },
                  icon: 'mdi-circle-medium',
                  name: 'Inventario por territorio y familia de productos',
                  scope: 'ver_flujo_informativo',
                  state: false,
                },
              ],
            },
            {
              icon: 'mdi-home-import-outline',
              name: 'Importaciones',
              scope: 'ver_importaciones',
              state: false,
              routes: [
                {
                  route: { name: 'gestimportaciones' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar importaciones',
                  scope: 'gestionar_importaciones',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'allimportacionesrep' },
                  icon: 'mdi-circle-medium',
                  name: 'Plan anual de importaciones',
                  scope: 'ver_importaciones',
                  state: false,
                },
                {
                  route: { name: 'importacionesind' },
                  icon: 'mdi-circle-medium',
                  name: 'Plan anual por indicador',
                  scope: 'ver_importaciones',
                  state: false,
                },
                {
                  route: { name: 'importacionesent' },
                  icon: 'mdi-circle-medium',
                  name: 'Plan anual por empresa',
                  scope: 'ver_importaciones',
                  state: false,
                },
                {
                  route: { name: 'compararplanimportaciones' },
                  icon: 'mdi-circle-medium',
                  name: 'Comparar plan con año anterior',
                  scope: 'ver_importaciones',
                  state: false,
                },
              ],
              nomencladores: [],
            },
            {
              icon: 'mdi-warehouse',
              name: 'Almacenes',
              scope: 'ver_almacenes',
              routes: [
                {
                  route: { name: 'almacen' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar almacen',
                  scope: 'gestionar_almacenes',
                  state: false,
                },
                {
                  route: { name: 'encargado_almacen' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar encargado de almacen',
                  scope: 'gestionar_almacenes',
                  state: false,
                },
              ],
              nomencladores: [
                {
                  route: { name: 'tipo_almacen' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipo de almacen',
                  scope: 'gestionar_almacenes',
                  state: false,
                },
                {
                  route: { name: 'categoria_almacen' },
                  icon: 'mdi-circle-medium',
                  name: 'Categoría de almacen',
                  scope: 'gestionar_almacenes',
                  state: false,
                },
                {
                  route: { name: 'actividad_almacen' },
                  icon: 'mdi-circle-medium',
                  name: 'Actividad de almacen',
                  scope: 'gestionar_almacenes',
                  state: false,
                },
                {
                  route: { name: 'temperatura' },
                  icon: 'mdi-circle-medium',
                  name: 'Temperatura',
                  scope: 'gestionar_almacenes',
                  state: false,
                },
                {
                  route: { name: 'distribucion' },
                  icon: 'mdi-circle-medium',
                  name: 'Distribución',
                  scope: 'gestionar_almacenes',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'ga_reporte1' },
                  icon: 'mdi-circle-medium',
                  name: 'Ubicación geográfica de los almacenes',
                  scope: 'ver_almacenes',
                  state: false,
                },
                {
                  route: { name: 'ga_reporte2' },
                  icon: 'mdi-circle-medium',
                  name: 'Categoría de los almacenes',
                  scope: 'ver_almacenes',
                  state: false,
                },
                {
                  route: { name: 'ga_reporte3' },
                  icon: 'mdi-circle-medium',
                  name: 'Encargados no capacitados',
                  scope: 'ver_almacenes',
                  state: false,
                },
                {
                  route: { name: 'ga_reporte4' },
                  icon: 'mdi-circle-medium',
                  name: 'Almacenes sin plan de inversión y mantenimiento',
                  scope: 'ver_almacenes',
                  state: false,
                },
                {
                  route: { name: 'ga_reporte5' },
                  icon: 'mdi-circle-medium',
                  name: 'Almacenes con mal estado constructivo',
                  scope: 'ver_almacenes',
                  state: false,
                },
              ],
            },
          ],
        },
        Dirección_de_Servicios_Técnicos: {
          icon: 'mdi-account-hard-hat',
          scope: 'ver_dir_serviciostec',
          state: false,
          procesos: [
            {
              icon: 'mdi-hospital-building',
              name: 'Disponibilidad de habitaciones',
              scope: 'ver_disp_habitaciones',
              state: false,
              routes: [
                {
                  route: { name: 'disponibilidad' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar no disponibilidad',
                  scope: 'gestionar_disp_habitaciones',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'porcientoDisponibilidad' },
                  icon: 'mdi-circle-medium',
                  name: 'Disponibilidad menor o igual al 95%',
                  scope: 'ver_disp_habitaciones',
                  state: false,
                },
                {
                  route: { name: 'mayorIncidencia' },
                  icon: 'mdi-circle-medium',
                  name: 'Habitaciones con mayor incidencia',
                  scope: 'ver_disp_habitaciones',
                  state: false,
                },
                {
                  route: { name: 'habNoDisp' },
                  icon: 'mdi-circle-medium',
                  name: 'Habitaciones no disponibles',
                  scope: 'ver_disp_habitaciones',
                  state: false,
                },
              ],
              nomencladores: [],
            },
            {
              icon: 'mdi-hammer-wrench',
              name: 'Gestión de mantenimiento',
              scope: 'gestion_mantenimiento',
              routes: [
                {
                  route: {name:'gestionMttoAnexo2'},
                  icon: 'mdi-circle-medium',
                  name: 'Anexos',
                  scope: 'gestion_mantenimiento',
                  state: false,
                }
              ],
              reportes: [
                {
                  route: {name:'gestionMttoReporteAnexo2'},
                  icon: 'mdi-circle-medium',
                  name: 'Reportes Anexos 2',
                  scope: 'gestion_mantenimiento',
                  state: false,
                },
                {
                  route: {name:'gestionMttoReporteAnexo3'},
                  icon: 'mdi-circle-medium',
                  name: 'Reportes Anexos 3',
                  scope: 'gestion_mantenimiento',
                  state: false,
                },
              ],
              nomencladores: [],
            },
            {
              icon: 'mdi-desktop-classic',
              name: 'Disponibilidad de los sistemas técnologicos',
              scope: 'gestion_sistemas_tec',
              routes: [
                {
                  route: { name: 'sistemaTec' },
                  icon: 'mdi-circle-medium',
                  name: 'Sistemas',
                  scope: 'gestion_sistemas_tec',
                  state: false,
                },
                {
                  route: { name: 'equipoTec' },
                  icon: 'mdi-circle-medium',
                  name: 'Equipos',
                  scope: 'gestion_sistemas_tec',
                  state: false,
                },
                {
                  route: { name: 'sistemaTecnologico' },
                  icon: 'mdi-circle-medium',
                  name: 'Registros de sistemas tecnológicos',
                  scope: 'gestion_sistemas_tec',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'sistema_tecnologico_report_one' },
                  icon: 'mdi-circle-medium',
                  name: 'Coeficientes de disponibilidad técnica',
                  scope: 'gestion_sistemas_tec',
                  state: false,
                },
                {
                  route: { name: 'sistema_tecnologico_report_two' },
                  icon: 'mdi-circle-medium',
                  name:
                    '% de equipos fuera de servicios en un sistema en crisis',
                  scope: 'gestion_sistemas_tec',
                  state: false,
                },
                /*{
                  route: {name:'sistema_tecnologico_report_three'},
                  icon: 'mdi-circle-medium',
                  name: 'Coeficiente menor o igual que noventa y cinco',
                  scope: 'gestion_sistemas_tec',
                  state: false,
                },*/
                {
                  route: { name: 'sistema_tecnologico_report_four' },
                  icon: 'mdi-circle-medium',
                  name: 'Sistemas no contratados en mantenimientos',
                  scope: 'gestion_sistemas_tec',
                  state: false,
                },
              ],
              nomencladores: [
                {
                  route: {name:'sistemaTec'},
                  icon: 'mdi-circle-medium',
                  name: 'Sistemas',
                  scope: 'gestion_sistemas_tec',
                  state: false,
                },
                {
                  route: {name:'equipoTec'},
                  icon: 'mdi-circle-medium',
                  name: 'Equipos',
                  scope: 'gestion_sistemas_tec',
                  state: false,
                }]
            },
          ],
        },
        Dirección_de_Transporte: {
          icon: 'mdi-train-car',
          scope: 'ver_dir_transporte_fi',
          state: false,
          procesos: [
            {
              icon: 'mdi-car-info',
              name: 'Flujo informativo',
              scope: 'gestionar_dir_transporte_fi',
              state: false,
              routes: [
                {
                  route: { name: 'GestMedioTransporte' },
                  icon: 'mdi-circle-medium',
                  name: 'Medios de transporte',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'gestion_accidente' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestión de accidentes',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'PlanRecape' },
                  icon: 'mdi-circle-medium',
                  name: 'Plan de recape',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'km_recorridos' },
                  icon: 'mdi-circle-medium',
                  name: 'Kilómetros recorridos',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
              ],
              reportes: [
                {
                  route: { name: 'ReporteCDT' },
                  icon: 'mdi-circle-medium',
                  name: 'CDT',
                  scope: 'ver_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'ReporteParqueTotal' },
                  icon: 'mdi-circle-medium',
                  name: 'Parque total',
                  scope: 'ver_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'FichaMediosTransporte' },
                  icon: 'mdi-circle-medium',
                  name: 'Ficha de transporte',
                  scope: 'ver_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'reporte_accidentalidad' },
                  icon: 'mdi-circle-medium',
                  name: 'Reporte de accidentalidad',
                  scope: 'ver_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'vehiculoconmasaccidentes' },
                  icon: 'mdi-circle-medium',
                  name: 'Vehiculo más accidentado',
                  scope: 'ver_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'horarioconmasaccidentes' },
                  icon: 'mdi-circle-medium',
                  name: 'Horario más accidentado',
                  scope: 'ver_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'ReportePlanRecape' },
                  icon: 'mdi-circle-medium',
                  name: 'Plan recape',
                  scope: 'ver_dir_transporte_fi',
                  state: false,
                },
              ],
              nomencladores: [
                {
                  route: { name: 'tipo_flota' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipo de flotas',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'tipo_emnautico' },
                  icon: 'mdi-circle-medium',
                  name: 'Embarcaciones y medios náuticos',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'tipo_vaservicio' },
                  icon: 'mdi-circle-medium',
                  name: 'Vehiculos administrativos y de servicios',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'tipo_vespecializado' },
                  icon: 'mdi-circle-medium',
                  name: 'Vehículos especializados',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'tipo_vturistico' },
                  icon: 'mdi-circle-medium',
                  name: 'Vehículos turísticos',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'clasificacion_accidente' },
                  icon: 'mdi-circle-medium',
                  name: 'Clasificación del accidente',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'causa_accidente' },
                  icon: 'mdi-circle-medium',
                  name: 'Causa del accidente',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'victima_accidente' },
                  icon: 'mdi-circle-medium',
                  name: 'Accidente con',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'etmtransporte' },
                  icon: 'mdi-circle-medium',
                  name: 'Estado técnico de medios de transportes',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'tcmtransporte' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipo de combustible de medios de transportes',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'samtransporte' },
                  icon: 'mdi-circle-medium',
                  name: 'Situación actual de medios de transportes',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'mpmtransporte' },
                  icon: 'mdi-circle-medium',
                  name: 'Motivo de parálisis de medios de transportes',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'cmtransporte' },
                  icon: 'mdi-circle-medium',
                  name: 'Clase de medios de transportes',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'ubicacion_memnautico' },
                  icon: 'mdi-circle-medium',
                  name:
                    'Ubicacion del motor en una embarcacion o medio nautico',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
                {
                  route: { name: 'vehiculo_ajeno' },
                  icon: 'mdi-circle-medium',
                  name: 'Vehiculo ajeno',
                  scope: 'gestionar_dir_transporte_fi',
                  state: false,
                },
              ],
            },
          ],
        },

         Operaciones_y_calidad: {
          icon: 'mdi-quality-high',
          scope: 'ver_dir_transporte_fi',
          state: false,
          procesos: [
            {
              icon: 'mdi-face-agent',
              name: 'Servicios técnicos',
              scope: 'gestionar_dir_transporte_fi',
              state: false,
              routes: [
              ],
              reportes: [
                
              ],
              nomencladores: [
                {
                  route: { name: 'tipo_ge' },
                  icon: 'mdi-circle-medium',
                  name: 'Tipo de grupo electrógeno',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'estado_ge' },
                  icon: 'mdi-circle-medium',
                  name: 'Estado del grupo electrógeno',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'estadoinc_ge' },
                  icon: 'mdi-circle-medium',
                  name: 'Estado de incidencia de grupo electrógeno',
                  scope: 'nomencladores',
                  state: false,
                },
                 {
                  route: { name: 'estadotec_ge' },
                  icon: 'mdi-circle-medium',
                  name: 'Estado técnico de grupo electrógeno',
                  scope: 'nomencladores',
                  state: false,
                },
                
              ],
            },
          ],
        },
        Configuraciones: {
          icon: 'settings',
          scope: 'ver_configuraciones',
          state: false,
          procesos: [
            {
              icon: 'mdi-shield-lock-outline',
              name: 'Seguridad',
              scope: 'seguridad',
              state: false,
              routes: [
                {
                  route: { name: 'role' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar roles',
                  scope: 'seguridad',
                  state: false,
                },
                {
                  route: { name: 'persona' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar personas',
                  scope: 'seguridad',
                  state: false,
                },
                {
                  route: { name: 'usuario' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar usuarios',
                  scope: 'seguridad',
                  state: false,
                },
                {
                  route: { name: 'usuarios' },
                  icon: 'mdi-circle-medium',
                  name: 'Listar usuarios SA',
                  scope: 'seguridad',
                  state: false,
                },
                {
                  route: { name: 'traza' },
                  icon: 'mdi-circle-medium',
                  name: 'Ver trazas',
                  scope: 'seguridad',
                  state: false,
                },
              ],
              reportes: [],
              nomencladores: [],
            },
            {
              icon: 'mdi-sticker-check-outline',
              name: 'Nomencladores',
              scope: 'nomencladores',
              state: false,
              routes: [
                {
                  route: { name: 'renglones' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar renglones',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'indicadores' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar indicadores',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'osde' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar osde',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'entidad' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar entidades',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'provincia' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar provincias',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'municipio' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar municipios',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'sector' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar sectores',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'instalacion' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar instalaciones',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'tipo_inst' },
                  icon: 'mdi-circle-medium',
                  name: 'Gestionar tipos de instalaciones',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'categoria_instalacion' },
                  icon: 'mdi-circle-medium',
                  name: 'Categoría de instalaciones',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'sucursal' },
                  icon: 'mdi-circle-medium',
                  name: 'Sucursales',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'ueb' },
                  icon: 'mdi-circle-medium',
                  name: 'UEB',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'empresa' },
                  icon: 'mdi-circle-medium',
                  name: 'Empresas',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'fintur' },
                  icon: 'mdi-circle-medium',
                  name: 'Fintur',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'onit' },
                  icon: 'mdi-circle-medium',
                  name: 'Onit',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'utit' },
                  icon: 'mdi-circle-medium',
                  name: 'Utit',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'escuela_ramal' },
                  icon: 'mdi-circle-medium',
                  name: 'Escuela ramal',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'unidad_administrativa' },
                  icon: 'mdi-circle-medium',
                  name: 'Unidad administrativa',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'escuela_capacitacion' },
                  icon: 'mdi-circle-medium',
                  name: 'Escuelas de capacitación',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'oficina_empleo' },
                  icon: 'mdi-circle-medium',
                  name: 'Oficinas de empleo',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'infotur' },
                  icon: 'mdi-circle-medium',
                  name: 'Infotur',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'delegacion_territorial' },
                  icon: 'mdi-circle-medium',
                  name: 'Delegaciones territoriales',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'cadena_hotelera' },
                  icon: 'mdi-circle-medium',
                  name: 'Cadenas hoteleras',
                  scope: 'nomencladores',
                  state: false,
                },
                {
                  route: { name: 'oace' },
                  icon: 'mdi-circle-medium',
                  name: 'OACE',
                  scope: 'nomencladores',
                  state: false,
                },
              ],
              reportes: [],
              nomencladores: [],
            },
          ],
        },
      },
    };
  },
  async mounted() {
    this.myrouter = await this.$router.currentRoute;
  },
  computed: {},
  methods: {
    replaceUnderlineToSpace(text) {
      while (text.indexOf('_') !== -1) {
        text = text.replace('_', ' ');
      }
      return text;
    },
  },
};
</script>

<style lang="sass" scoped>
$naranaja: orange

.link-active
  border-right: 5px solid $naranaja

.nav
  .q-item.q-router-link--exact-active.link-active .q-icon
    color: $naranaja !important
</style>
