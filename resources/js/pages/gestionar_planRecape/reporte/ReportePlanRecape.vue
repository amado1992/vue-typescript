<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Reporte cumplimiento plan recape
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
          hide-pagination
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
                            <q-input
                              outlined
                              dense
                              input-class="cursor-pointer"
                              label="Año "
                              :value="modelFecha"
                              @click="$refs.yearPicker.show()"
                              mask="####"
                              name="anno"
                              v-model="modelFecha"
                              debounce="1000"
                            >
                              <template v-slot:append>
                                <q-icon class="cursor-pointer">
                                  <q-popup-proxy
                                    ref="yearPicker"
                                    transition-show="scale"
                                    transition-hide="scale"
                                  >
                                    <q-date
                                      minimal
                                      mask="YYYY"
                                      emit-immediately
                                      default-view="Years"
                                      v-model="modelFecha"
                                      @input="checkValue"
                                    />
                                  </q-popup-proxy>
                                </q-icon>
                              </template>
                            </q-input>
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
                              disable
                              dense
                              outlined
                              v-model="modelDelegacion"
                              :options="optionsMinturSelect"
                              label="Delegación"
                              style="width: 240px;"
                            />
                          </div>
                          <div class="col-4">
                            <q-select
                              disable
                              dense
                              outlined
                              v-model="modelProvincia"
                              :options="optionsMinturSelect"
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
                        <q-btn flat label="Guardar" v-close-popup />
                      </q-card-actions>
                    </q-card>
                    <!-- End Opciones -->
                  </q-popup-proxy>
                </q-select>
              </div>
              <div class="col-2 q-pl-md">
                <q-btn
                  :disable="filtro === null"
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
          <template v-slot:header="props">
            <q-tr v-for="(row, index) in headerData" :key="index">
              <q-th
                :auto-width="cell.colspan === '0'"
                v-for="(cell, cellCount) in row"
                :key="cellCount"
                :colspan="cell.colspan"
                :rowspan="cell.rowspan"
                align="left"
                class="bg-primary text-white text-uppercase text-center"
              >
                {{ cell.label }}
              </q-th>
            </q-tr>
            <q-tr :props="props" class="bg-primary">
              <q-th v-for="col in props.cols" :key="col.name" :props="props">
                <span class="text-bold text-white">{{ col.label }}</span>
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
import { mapGetters, mapActions } from 'vuex';
import { warningMessage, errorMessage } from '../../../utils/notificaciones';
import * as message from '../../../utils/resources';
import Vue from 'vue';

export default {
  name: 'ReportePlanRecape',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      dataReporte: [],
      filtro: [],
      headerData: [
        [
          {
            label: '',
            colspan: '1',
          },
          {
            label: ' ',
            colspan: '1',
          },
          {
            label: 'año x',
            colspan: '12',
          },
          {
            label: '',
            colspan: '1',
          },
          {
            label: '',
            colspan: '1',
          },
          {
            label: '',
            colspan: '1',
          },
        ],
      ],
      columns: [
        {
          name: 'nombre_osde',
          align: 'left',
          label: 'osde',
          field: 'nombre_osde',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'potencial',
          align: 'center',
          label: 'potencial',
          field: 'potencial',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'enero',
          align: 'center',
          label: 'e',
          field: 'enero',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'febrero',
          align: 'center',
          label: 'f',
          field: 'febrero',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'marzo',
          align: 'center',
          label: 'm',
          field: 'marzo',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'abril',
          align: 'center',
          label: 'a',
          field: 'abril',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'mayo',
          align: 'center',
          label: 'm',
          field: 'mayo',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'junio',
          align: 'center',
          label: 'j',
          field: 'junio',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'julio',
          align: 'center',
          label: 'j',
          field: 'julio',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'agosto',
          align: 'center',
          label: 'a',
          field: 'agosto',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'septiembre',
          align: 'center',
          label: 's',
          field: 'septiembre',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'octubre',
          align: 'center',
          label: 'o',
          field: 'octubre',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'noviembre',
          align: 'center',
          label: 'n',
          field: 'noviembre',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'diciembre',
          align: 'center',
          label: 'd',
          field: 'diciembre',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'plan',
          align: 'center',
          label: 'plan',
          field: 'plan',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'real',
          align: 'center',
          label: 'real',
          field: '_real',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'porciento',
          align: 'center',
          label: '%',
          field: 'porciento',
          headerClasses: 'text-uppercase',
        },
      ],
      optionsMinturSelect: ['OSDE', 'FINTUR', 'UP MINTUR'],
      modelFecha: '',
      modelMintur: null,
      modelOsde: null,
      modelEntidadInstalacion: null,
      modelProvincia: null,
      modelUPMintur: null,
      modelDelegacion: null,
      modelTipoFlota: null,
      fintur: false,
    };
  },
  async mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Reportes' },
      { label: 'Reporte cumplimiento plan de recape' },
    ]);
    this.loadData();
  },
  watch: {
    modelFecha: {
      handler() {
        if (!this.filtro.includes('Fecha')) {
          this.filtro.push('Fecha');
        }
      },
    },
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('plan_recape', ['getReportePlanRecape']),

    async loadData() {
      this.setLoading(true);
      const data = {
        anno: this.modelFecha,
        osde: this.modelProvincia,
        provincia: this.modelProvincia,
        up_mintur: this.modelUPMintur,
        delegacion: this.modelDelegacion,
      };
      return Vue.prototype.$axios
        .post('/api/reporte_cumplimientopr', data)
        .then((result) => {
          this.dataReporte = result.data.data;
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        })
        .finally(() => {
          this.setLoading(false);
        });
    },
    filterReset() {
      this.modelMintur = null;
      this.modelOsde = null;
      this.modelEntidadInstalacion = null;
      this.modelProvincia = null;
      this.modelUPMintur = null;
      this.modelDelegacion = null;
      this.modelFecha = '';
      this.filtro = [];
      this.fintur = false;
    },
    checkValue(val, reason, details) {
      if (reason === 'year') {
        this.$refs.yearPicker.hide();
      }
    },
  },
};
</script>

<style></style>
