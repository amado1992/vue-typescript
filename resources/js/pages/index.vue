<template>
  <div class="q-pa-sm">
    <!-- Boton de opciones del dashboard -->
    <div class="row justify-end">
      <q-btn dense flat color="grey-7" icon="mdi-tune">
        <q-tooltip anchor="top left" self="top middle">
          Ajustes del dashboard
        </q-tooltip>
        <q-menu
          transition-show="jump-down"
          transition-hide="jump-up"
          auto-close
        >
          <q-list style="min-width: 100px;">
            <q-item
              clickable
              v-for="(entry, index) in menu_links"
              :key="index"
              v-show="scopes.includes(entry.scope)"
              @click="showDasboard(entry.route)"
            >
              <q-item-section>{{
                replaceUnderlineToSpace(index)
              }}</q-item-section>
            </q-item>
          </q-list>
        </q-menu>
      </q-btn>
    </div>
    <!-- End Boton de opciones del dashboard -->
    <div
      class="row justify-center"
      ref="data_dashboard"
      v-if="dashboard_active !== ''"
    >
      <div
        v-if="
          scopes.includes('ver_costo_calidad') &&
          dashboard_active === 'costo_calidad'
        "
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section>
            <grafico
              :graph_type="'pie'"
              :title="'Reportes Costo Calidad'"
              :subtitle="'Total de reportes de los últimos 5 años'"
              :series="grafico_data"
            />
          </q-card-section>
        </q-card>
      </div>

      <div
        v-if="scopes.includes('ver_premios') && dashboard_active === 'premios'"
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section class="row no-wrap">
            <div class="col-6 q-pl-xs">
              <grafico
                :graph_type="grafico_data.graph_type"
                :title="grafico_data.graph_title"
                :series="grafico_data"
                :xAxis_text="grafico_data.xAxis_text"
              />
            </div>
            <q-separator vertical />
            <div class="col-6 q-pr-xs">
              <grafico
                :title="grafico_data_2.graph_title"
                :series="grafico_data_2"
                :xAxis_text="grafico_data.xAxis_text"
              />
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div
        v-if="
          scopes.includes('ver_lideres_calidad') &&
          dashboard_active === 'lideres_calidad'
        "
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section class="row no-wrap">
            <div class="col-6 q-pl-xs">
              <grafico
                :graph_type="grafico_data.graph_type"
                :title="grafico_data.graph_title"
                :series="grafico_data"
                :xAxis_text="grafico_data.xAxis_text"
              />
            </div>
            <q-separator vertical />
            <div class="col-6 q-pr-xs">
              <grafico
                :graph_type="grafico_data_2.graph_type"
                :title="grafico_data_2.graph_title"
                :series="grafico_data_2"
                :xAxis_text="grafico_data_2.xAxis_text"
              />
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div
        v-if="
          scopes.includes('ver_sistemas_tec') &&
          dashboard_active === 'sistemas_tec'
        "
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section>
            <grafico
              :graph_type="grafico_data.graph_type"
              :title="grafico_data.graph_title"
              :series="grafico_data"
            />
          </q-card-section>
        </q-card>
      </div>

      <div
        v-if="
          scopes.includes('ver_disp_habitaciones') &&
          dashboard_active === 'disp_habitaciones'
        "
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section>
            <grafico
              :graph_type="grafico_data.graph_type"
              :title="grafico_data.graph_title"
              :series="grafico_data"
            />
          </q-card-section>
        </q-card>
      </div>

      <div
        v-if="
          scopes.includes('ver_importaciones') &&
          dashboard_active === 'importaciones'
        "
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section>
            <grafico
              :graph_type="grafico_data.graph_type"
              :title="grafico_data.graph_title"
              :series="grafico_data"
              :xAxis_text="grafico_data.xAxis_text"
              :yAxis_title="grafico_data.yAxis_text"
            />
          </q-card-section>
        </q-card>
      </div>

      <div
        v-if="
          scopes.includes('ver_indicadores_lineamientos') &&
          dashboard_active === 'indicadores_lineamientos'
        "
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section>
            <grafico
              :graph_type="grafico_data.graph_type"
              :title="grafico_data.graph_title"
              :series="grafico_data"
            />
          </q-card-section>
        </q-card>
      </div>

      <div
        v-if="
          scopes.includes('ver_flujo_informativo') &&
          dashboard_active === 'flujo_informativo'
        "
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section>
            <grafico
              :graph_type="grafico_data.graph_type"
              :title="grafico_data.graph_title"
              :series="grafico_data"
              :xAxis_text="grafico_data.xAxis_text"
            />
          </q-card-section>
        </q-card>
      </div>

      <div
        v-if="
          scopes.includes('ver_flujo_material') &&
          dashboard_active === 'flujo_material'
        "
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section>
            <grafico
              :graph_type="grafico_data.graph_type"
              :title="grafico_data.graph_title"
              :series="grafico_data"
              :xAxis_text="grafico_data.xAxis_text"
            />
          </q-card-section>
        </q-card>
      </div>

      <div
        v-if="
          scopes.includes('ver_dir_transporte_fi') &&
          dashboard_active === 'dir_transporte_fi'
        "
      >
        <q-card style="width: 70vw; max-width: 70vw;">
          <q-card-section>
            <div class="row no-wrap">
              <div class="col-6 q-pl-xs">
                <grafico
                  :graph_type="grafico_data.graph_type"
                  :title="grafico_data.graph_title"
                  :series="grafico_data.data"
                  :xAxis_text="grafico_data.xAxis_text"
                />
              </div>
              <q-separator vertical />
              <div class="col-6 q-pr-xs">
                <grafico
                  :title="grafico_data_2.graph_title"
                  :series="grafico_data_2"
                  :xAxis_text="grafico_data_2.xAxis_text"
                />
              </div>
            </div>
            <q-separator inset />

            <div class="row no-wrap">
              <div class="col-6 q-pl-xs">
                <grafico
                  :graph_type="grafico_data_3.graph_type"
                  :title="grafico_data_3.graph_title"
                  :series="grafico_data_3.data"
                  :xAxis_text="grafico_data_3.xAxis_text"
                />
              </div>
              <q-separator inset vertical />
              <div class="col-6 q-pr-xs">
                <grafico
                  :title="grafico_data_4.graph_title"
                  :series="grafico_data_4"
                  :xAxis_text="grafico_data_4.xAxis_text"
                />
              </div>
            </div>

            <q-separator inset />

            <div class="row no-wrap">
              <div class="col-12">
                <grafico
                  :graph_type="grafico_data_5.graph_type"
                  :title="grafico_data_5.graph_title"
                  :series="grafico_data_5.data"
                  :xAxis_text="grafico_data_5.xAxis_text"
                />
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
    <div class="row justify-center q-pt-md" v-if="dashboard_active === ''">
      <img
        alt="Mintur logo"
        src="../static/dashboard-animate.svg"
        width="42%"
      />
    </div>
  </div>
</template>
<script>
import moment from 'moment';
import Vue from 'vue';
import { mapActions } from 'vuex';
import SimpleGraph from '../components/dashboard/SimpleGraph.vue';
import { errorMessage } from '../utils/notificaciones';
import * as message from '../utils/resources';

moment.locale('es');
export default {
  name: 'Index',
  components: {
    grafico: SimpleGraph,
  },
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      instalacion_id: sessionStorage.getItem('instalacion_id'),
      dashboard_active: '',
      menu_links: {
        Costo_calidad: {
          scope: 'ver_costo_calidad',
          state: null,
          route: 'costo_calidad',
        },
        Premios: {
          scope: 'ver_premios',
          state: null,
          route: 'premios',
        },
        Lideres_de_Calidad: {
          scope: 'ver_lideres_calidad',
          state: null,
          route: 'lideres_calidad',
        },
        Indicadores_lineamientos: {
          scope: 'ver_indicadores_lineamientos',
          state: null,
          route: 'indicadores_lineamientos',
        },
        Sistemas_tecnologicos: {
          scope: 'ver_sistemas_tec',
          state: null,
          route: 'sistemas_tec',
        },
        Disponiilidad_de_habitaciones: {
          scope: 'ver_disp_habitaciones',
          state: null,
          route: 'disp_habitaciones',
        },
        Importaciones: {
          scope: 'ver_importaciones',
          state: null,
          route: 'importaciones',
        },
        Flujo_Material: {
          scope: 'ver_flujo_material',
          state: null,
          route: 'flujo_material',
        },
        Flujo_Informativo: {
          scope: 'ver_flujo_informativo',
          state: null,
          route: 'flujo_informativo',
        },
        Dir_transporte: {
          scope: 'ver_dir_transporte_fi',
          state: null,
          route: 'dir_transporte_fi',
        },
      },
      grafico_data: {
        name: null,
        data: null,
        xAxis_text: [],
        yAxis_text: null,
        graph_type: null,
        graph_title: null,
        graph_subtitle: null,
      },
      grafico_data_2: {
        name: null,
        data: null,
        xAxis_text: [],
        yAxis_text: null,
        graph_type: null,
        graph_title: null,
        graph_subtitle: null,
      },
      grafico_data_3: {
        name: null,
        data: null,
        xAxis_text: [],
        yAxis_text: null,
        graph_type: null,
        graph_title: null,
        graph_subtitle: null,
      },
      grafico_data_4: {
        name: null,
        data: null,
        xAxis_text: [],
        yAxis_text: null,
        graph_type: null,
        graph_title: null,
        graph_subtitle: null,
      },
      grafico_data_5: {
        name: null,
        data: null,
        xAxis_text: [],
        yAxis_text: null,
        graph_type: null,
        graph_title: null,
        graph_subtitle: null,
      },
    };
  },
  async mounted() {
    this.addToHomeBreadcrumbs([{ label: 'Dashboard' }]);
  },
  methods: {
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),

    replaceUnderlineToSpace(text) {
      while (text.indexOf('_') !== -1) {
        text = text.replace('_', ' ');
      }
      return text;
    },
    resetData() {
      this.grafico_data.name = null;
      this.grafico_data.data = null;
      this.grafico_data.xAxis_text = [];
      this.grafico_data.yAxis_text = null;
    },
    showDasboard(param) {
      switch (param) {
        case 'costo_calidad':
          this.dashboard_active = '';
          this.dashboard_active = 'costo_calidad';
          this.resetData();
          this.loadDataCostoCalidad();
          break;
        case 'premios':
          this.dashboard_active = '';
          this.dashboard_active = 'premios';
          this.resetData();
          this.loadDataPremio();
          this.loadDataPremio5year();
          break;
        case 'lideres_calidad':
          this.dashboard_active = '';
          this.dashboard_active = 'lideres_calidad';
          this.resetData();
          this.loadDataLideresCalidadXestado();
          this.loadDataLideresCalidadXsector();
          break;
        case 'indicadores_lineamientos':
          this.dashboard_active = '';
          this.dashboard_active = 'indicadores_lineamientos';
          this.resetData();
          this.loadDataIndicadoresLicVencidas();
          break;
        case 'sistemas_tec':
          this.dashboard_active = '';
          this.dashboard_active = 'sistemas_tec';
          this.resetData();
          this.loadDataSistemasTec();
          break;
        case 'disp_habitaciones':
          this.dashboard_active = '';
          this.dashboard_active = 'disp_habitaciones';
          this.resetData();
          this.loadDataDispHabitaciones();
          break;
        case 'importaciones':
          this.dashboard_active = '';
          this.dashboard_active = 'importaciones';
          this.resetData();
          this.loadDataImportaciones();
          break;
        case 'flujo_material':
          this.dashboard_active = '';
          this.dashboard_active = 'flujo_material';
          this.resetData();
          this.loadDataFlujoMaterial();
          break;
        case 'flujo_informativo':
          this.dashboard_active = '';
          this.dashboard_active = 'flujo_informativo';
          this.resetData();
          this.loadDataFlujoInformativo();
          break;
        case 'dir_transporte_fi':
          this.dashboard_active = '';
          this.dashboard_active = 'dir_transporte_fi';
          this.resetData();
          this.loadDataTransporte();
          this.loadDataTransporteCDTT();
          this.loadDataPlanRecapeOsdes();
          this.loadDataPlanRecapeTotal();
          this.loadDataIndiceAccidentalidad();
          break;

        default:
          break;
      }
    },
    loadDataCostoCalidad() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dashboard_totalReport_ccr', data)
        .then((result) => {
          this.grafico_data.data = result.data;
          this.grafico_data.name = 'Total reportes';
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataPremio5year() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dasboard_cantPremios5years', data)
        .then((result) => {
          this.grafico_data_2.graph_type = 'pie';
          this.grafico_data_2.data = result.data.data;
          this.grafico_data_2.name = 'Total de premios';
          this.grafico_data_2.graph_title = 'Total de premios por año';
          result.data.data.forEach((element) => {
            this.grafico_data_2.xAxis_text.push(element.name);
          });
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataPremio() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dasboard_cantPremiosCat', data)
        .then((result) => {
          this.grafico_data.data = result.data.data;
          this.grafico_data.name = 'Total de premios';
          this.grafico_data.graph_type = 'column';
          this.grafico_data.graph_title = 'Premios por categoría';
          result.data.data.forEach((element) => {
            this.grafico_data.xAxis_text.push(element.name);
          });
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataLideresCalidadXestado() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dashboard_totalXestado_pl', data)
        .then((result) => {
          this.grafico_data.data = result.data.data;
          this.grafico_data.name = 'Total';
          this.grafico_data.graph_type = 'pie';
          this.grafico_data.graph_title = 'Propuestas por estado';
          result.data.data.forEach((element) => {
            this.grafico_data.xAxis_text.push(element.name);
          });
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataLideresCalidadXsector() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dashboard_totalXsector_pl', data)
        .then((result) => {
          this.grafico_data_2.data = result.data.data;
          this.grafico_data_2.name = 'Total';
          this.grafico_data_2.graph_type = 'pie';
          this.grafico_data_2.graph_title = 'Propuestas por sector';
          result.data.data.forEach((element) => {
            this.grafico_data_2.xAxis_text.push(element.name);
          });
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataSistemasTec() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dasboard_sismantenimiento_stec', data)
        .then((result) => {
          this.grafico_data.graph_type = 'pie';
          this.grafico_data.data = result.data;
          this.grafico_data.name = 'Total';
          this.grafico_data.graph_title = 'Sist. con contrato de mantenimiento';
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataImportaciones() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dashboard_info_imp', data)
        .then((result) => {
          this.grafico_data.graph_type = 'bar';
          this.grafico_data.data = result.data.data;
          this.grafico_data.name = 'Importaciones';
          this.grafico_data.graph_title = 'Total de importaciones';
          result.data.data.forEach((element) => {
            this.grafico_data.xAxis_text.push(element.name);
          });
          this.grafico_data.yAxis_text = 'Total de importaciones';
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataDispHabitaciones() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/list_porciento', data)
        .then((result) => {
          this.grafico_data.graph_type = 'pie';
          this.grafico_data.data = result.data;
          this.grafico_data.name = 'Total';
          this.grafico_data.graph_title = 'Disponibilidad menor o igual al 95%';
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataIndicadoresLicVencidas() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dashboard_total_lic_estadoyear', data)
        .then((result) => {
          this.grafico_data.graph_type = 'pie';
          this.grafico_data.data = result.data.data;
          this.grafico_data.name = 'Total';
          this.grafico_data.graph_title = 'Licencias vencidas';
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataFlujoMaterial() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dashboard_demanda_aprobada', data)
        .then((result) => {
          this.grafico_data.graph_type = 'bar';
          this.grafico_data.data = result.data.data;
          this.grafico_data.name = 'Demandas';
          this.grafico_data.graph_title = 'Demandas aprobadas';
          result.data.data.forEach((element) => {
            this.grafico_data.xAxis_text.push(element.name);
          });
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataFlujoInformativo() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/dashboard_cantcompras', data)
        .then((result) => {
          this.grafico_data.graph_type = 'bar';
          this.grafico_data.data = result.data.data;
          this.grafico_data.name = 'Compras';
          this.grafico_data.graph_title = 'Compras';
          result.data.data.forEach((element) => {
            this.grafico_data.xAxis_text.push(element.name);
          });
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataTransporte() {
      const data = {};
      Vue.prototype.$axios
        .post('/api/mediotransporte_dashboard', data)
        .then((result) => {
          this.grafico_data.graph_type = 'column';
          this.grafico_data.data = result.data.data[1];
          this.grafico_data.graph_title = 'Coeficiente de Disposición Técnica';
          this.grafico_data.xAxis_text = result.data.data[0];
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataTransporteCDTT() {
      const data = {};
      Vue.prototype.$axios
        .post('/api/mediotransporte_dashboard_CDTT', data)
        .then((result) => {
          this.grafico_data_2.graph_type = 'pie';
          this.grafico_data_2.data = result.data.data;
          this.grafico_data_2.graph_title =
            'Coeficiente de Disposición Técnica Total';
          this.grafico_data_2.xAxis_text = result.data.data;
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataPlanRecapeOsdes() {
      const data = {};
      Vue.prototype.$axios
        .post('/api/cumplimientopr_dashboard', data)
        .then((result) => {
          this.grafico_data_3.graph_type = 'column';
          this.grafico_data_3.data = result.data.data[1];
          this.grafico_data_3.graph_title = 'Cumplimiento Plan recape';
          this.grafico_data_3.xAxis_text = result.data.data[0];
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataPlanRecapeTotal() {
      const data = {};
      Vue.prototype.$axios
        .post('/api/cumplimientoprtotal_dashboard', data)
        .then((result) => {
          this.grafico_data_4.graph_type = 'pie';
          this.grafico_data_4.data = result.data.data;
          this.grafico_data_4.graph_title =
            'Cumplimiento del plan de recape Total';
          this.grafico_data_4.xAxis_text = result.data.data;
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    loadDataIndiceAccidentalidad() {
      const data = {};
      Vue.prototype.$axios
        .post('/api/accidente_dashboard', data)
        .then((result) => {
          this.grafico_data_5.graph_type = 'line';
          this.grafico_data_5.data = result.data.data;
          this.grafico_data_5.graph_title = 'Índice de accidentalidad';
          this.grafico_data_5.xAxis_text = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre',
          ];
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
  },
};
</script>
<style scoped></style>
