<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-4 text-h6 text-uppercase">Reporte de accidentalidad</div>
          <q-space></q-space>

          <div class="col-1">
            <q-btn
              :disable="dataReporte.length === 0"
              icon="mdi-file-download"
              dense
              color="primary"
              size="sm"
              @click.prevent="exportPDF()"
            >
              <q-tooltip>Exportar a PDF</q-tooltip>
            </q-btn>
          </div>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <!-- tabla-->
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white text-center"
          flat
          dense
          :data="dataReporte"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          no-data-label="No se encontraron elementos a mostrar"
          :visible-columns="visibleColumns"
          :rows-per-page-options="[25, 50, 75, 100]"
        >
          <template v-slot:top>
            <div class="row full-width">
              <div class="col-10">
                <q-select
                  multiple
                  use-chips
                  use-input
                  new-value-mode="add-unique"
                  stack-label
                  hide-dropdown-icon
                  dense
                  outlined
                  id="inputFilters"
                  label="Filtros de búsqueda"
                  v-model="filtro"
                >
                  <q-popup-proxy
                    transition-show="jump-down"
                    transition-hide="jump-up"
                    ref="inputFilters"
                  >
                    <!-- Opciones -->
                    <q-card style="min-width: 750px;">
                      <q-card-section>
                        <div class="row no-wrap q-gutter-x-xs q-pb-xs">
                          <div class="col-4">
                            <q-select
                              id="mes"
                              label="Mes"
                              v-model="mes"
                              dense
                              outlined
                              input-debounce="0"
                              :options="listmes"
                              options-dense
                              option-label="mes" option-value="id"
                              emit-value
                              map-options
                              style="width: 240px;"
                            >
                            </q-select>
                          </div>
                          <div class="col-4">
                            <q-select
                              disable
                              dense
                              outlined
                              v-model="modelOsde"
                              multiple
                              :options="optionsMinturSelect"
                              label="OSDE"
                              style="width: 240px;"
                            />
                          </div>
                          <div class="col-4">
                            <q-select
                              disable
                              dense
                              outlined
                              v-model="modelEntidadInstalacion"
                              :options="optionsMinturSelect"
                              label="Empresa/Instalación"
                              style="width: 240px;"
                            />
                          </div>
                        </div>
                        <div class="row q-pt-xs q-gutter-x-xs no-wrap">
                          <div class="col-4">
                            <q-select
                              disable
                              dense
                              outlined
                              v-model="modelUPMintur"
                              multiple
                              :options="optionsMinturSelect"
                              label="UP MINTUR"
                              style="width: 240px;"
                            />
                          </div>
                          <div class="col-4">
                            <q-select
                              dense
                              outlined
                              v-model="modelDelegacion"
                              :options="optionsDelegacionSelect"
                              option-label="delegacion_territorial"
                              option-value="id"
                              label="Delegación"
                              style="width: 240px;"
                            />
                          </div>
                          <div class="col-4">
                            <q-select
                              dense
                              outlined
                              v-model="modelProvincia"
                              :options="optionsProvinciaSelect"
                              option-label="nombre"
                              option-value="id"
                              label="Provincia"
                              style="width: 240px;"
                            />
                          </div>
                        </div>
                        <div class="row q-pt-xs q-gutter-x-xs no-wrap">
                          <q-checkbox
                            disable
                            left-label
                            v-model="fintur"
                            label="Fintur"
                          />
                        </div>
                      </q-card-section>

                      <q-card-actions align="right" class="text-primary">
                        <q-btn flat label="Guardar" v-close-popup/>
                      </q-card-actions>
                    </q-card>
                    <!-- End Opciones -->
                  </q-popup-proxy>
                </q-select>
              </div>
              <div class="col-2 q-pl-md">
                <q-btn
                  :disable="filtro.length <= 0"
                  icon="mdi-magnify"
                  dense
                  color="primary"
                  @click="loadData()"
                />
                <q-btn
                  :disable="filtro.length <= 0"
                  icon="mdi-notification-clear-all"
                  dense
                  color="primary"
                  @click="filterReset()"
                >
                  <q-tooltip>Resetear filtro</q-tooltip>
                </q-btn>
              </div>
            </div>
          </template>
          <template v-slot:header="props" style="border-spacing: 1px">
            <q-tr v-for="(row, index) in headerMaker" :key="index">
              <q-th
                :auto-width="cell.colspan === '0'"
                v-for="(cell, cellCount) in row"
                :key="cellCount"
                :colspan="cell.colspan"
                :rowspan="cell.rowspan"
                align="left"
                class="bg-primary text-white text-bold"
              >
                {{ cell.label }}
              </q-th>
            </q-tr>
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import {warningMessage, errorMessage} from '../../../utils/notificaciones';
import * as message from '../../../utils/resources';
import Vue from 'vue';

export default {
  name: 'ReporteAccidentalidad',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      dataReporte: [],
      mes: null,
      listmes: [
        {
          id: 1,
          mes: 'Enero'
        },
        {
          id: 2,
          mes: 'Febrero'
        },
        {
          id: 3,
          mes: 'Marzo'
        },
        {
          id: 4,
          mes: 'Abril'
        },
        {
          id: 5,
          mes: 'Mayo'
        },
        {
          id: 6,
          mes: 'Junio'
        },
        {
          id: 7,
          mes: 'Julio'
        },
        {
          id: 8,
          mes: 'Agosto'
        },
        {
          id: 9,
          mes: 'Septiembre'
        },
        {
          id: 10,
          mes: 'Octubre'
        },
        {
          id: 11,
          mes: 'Noviembre'
        },
        {
          id: 12,
          mes: 'Diciembre'
        }
      ],
      filtro: [],
      headerMaker: [
        [
          {
            label: 'Indicadores',
            colspan: '1',
            rowspan: '2',
          },
          {
            label: 'Mes',
            colspan: '3',
          },
          {
            label: 'Acumulado',
            colspan: '3',
          },
          {
            label: 'Total General',
            colspan: '3',
          },
        ],
        [
          {
            label: 'Año Anterior',
            colspan: '1',
          },
          {
            label: 'Año Actual',
            colspan: '1',
          },
          {
            label: 'Diferencia',
            colspan: '1',
          },
          {
            label: 'Año Anterior',
            colspan: '1',
          },
          {
            label: 'Año Actual',
            colspan: '1',
          },
          {
            label: 'Diferencia',
            colspan: '1',
          },
          {
            label: 'Año Anterior',
            colspan: '1',
          },
          {
            label: 'Año Actual',
            colspan: '1',
          },
          {
            label: 'Diferencia',
            colspan: '1',
          },
        ],
      ],
      columns: [
        {
          name: 'indicador',
          align: 'left',
          label: 'Indicador',
          field: 'indicador',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'mes_ano_anterior',
          align: 'left',
          label: 'Mes anterior',
          field: 'mes_ano_anterior',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'mes_ano_actual',
          align: 'left',
          label: 'Mes actual',
          field: 'mes_ano_actual',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'mes_ano_diferencia',
          align: 'left',
          label: 'Diferencia',
          field: 'mes_ano_diferencia',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'acumulado_ano_anterior',
          align: 'left',
          label: 'Año anterior',
          field: 'acumulado_ano_anterior',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'acumulado_ano_actual',
          align: 'left',
          label: 'Año actual',
          field: 'acumulado_ano_actual',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'acumulado_ano_diferencia',
          align: 'left',
          label: 'Diferencia',
          field: 'acumulado_ano_diferencia',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'total_ano_anterior',
          align: 'left',
          label: 'Año anterior',
          field: 'total_ano_anterior',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'total_ano_actual',
          align: 'left',
          label: 'Año actual',
          field: 'total_ano_actual',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'total_ano_diferencia',
          align: 'left',
          label: 'Diferencia',
          field: 'total_ano_diferencia',
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        }
      ],
      visibleColumns: [
        'indicador',
        'mes_ano_anterior',
        'mes_ano_actual',
        'mes_ano_diferencia',
        'acumulado_ano_anterior',
        'acumulado_ano_actual',
        'acumulado_ano_diferencia',
        'total_ano_anterior',
        'total_ano_actual',
        'total_ano_diferencia'
      ],
      optionsMinturSelect: ['OSDE', 'FINTUR', 'UP MINTUR'],
      modelMintur: null,
      modelOsde: null,
      modelEntidadInstalacion: null,
      modelProvincia: null,
      optionsProvinciaSelect: [],
      modelUPMintur: null,
      modelDelegacion: null,
      optionsDelegacionSelect: [],
      fintur: false,
    };
  },
  async mounted() {
    this.addToHomeBreadcrumbs([
      {label: 'Dirección de Transporte'},
      {label: 'Flujo informativo'},
      {label: 'Reportes'},
      {label: 'Reporte de accidentalidad'},
    ]);
    this.getProvincias().then((r) => {
      this.optionsProvinciaSelect = r;
    });
    this.getDelegacion_territorial().then((r) => {
      this.optionsDelegacionSelect = r;
    });
  },
  watch: {
    mes: {
      handler() {
        if (!this.filtro.includes('Mes')) {
          this.filtro.push('Mes');
        }
      },
    },
    provincia: {
      handler() {
        if (!this.filtro.includes('modelDelegacion')) {
          this.filtro.push('Provincia');
        }
      },
    },
    delegacion: {
      handler() {
        if (!this.filtro.includes('modelDelegacion')) {
          this.filtro.push('Delegación Territorial');
        }
      },
    },
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('gestion_accidente', ['getReporteAccidentalidad']),
    ...mapActions('provincia', ['getProvincias']),
    ...mapActions('delegacion_territorial', ['getDelegacion_territorial']),

    async loadData() {
      this.setLoading(true);
      const data = {
        mes: this.mes,
        osde: this.modelOsde,
        provincia: this.modelProvincia,
        up_mintur: this.modelUPMintur,
        delegacion: this.modelDelegacion,
      };
      this.dataReporte = await this.getReporteAccidentalidad(data);
      this.setLoading(false);
    },
    filterReset() {
      this.modelMintur = null;
      this.modelOsde = null;
      this.modelEntidadInstalacion = null;
      this.modelProvincia = null;
      this.modelUPMintur = null;
      this.modelDelegacion = null;
      this.fintur = false;
      this.filtro = [];
    },
    async exportPDF() {
      this.setLoading(true);
      const url = '/api/reporte/accidentalidad/pdf';
      return Vue.prototype
        .$axios({
          method: 'post',
          url: url,
          responseType: 'arraybuffer',
          data: {
            mes_ano_anterior: '',
            mes_ano_actual: '',
            mes_ano_diferencia: '',
            acumulado_ano_anterior: '',
            acumulado_ano_actual: '',
            acumulado_ano_diferencia: '',
            total_ano_anterior: '',
            total_ano_actual: '',
            total_ano_diferencia: '',
            mes: this.mes,
            instalacion_id: '',
            osde: this.modelOsde,
            provincia: this.modelProvincia,
            up_mintur: this.modelUPMintur,
            delegacion: this.modelDelegacion,
          },
        })
        .then((response) => {
          const blob = new Blob([response.data], {
            type: 'application/pdf',
          });
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = 'Reporte-Accidentalidad.pdf';
          link.click();
          this.setLoading(false);
        })
        .catch((error) => {
          this.setLoading(false);
          errorMessage(message.eDataError, error);
        });
    },
  },
};
</script>

<style></style>
