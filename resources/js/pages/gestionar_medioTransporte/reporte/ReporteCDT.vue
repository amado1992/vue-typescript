<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-4 text-h6 text-uppercase">Reporte CDT</div>
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
          :rows-per-page-options="[10, 20, 30, 40, 50, 100]"
        >
          <template v-slot:top>
            <form
              @submit.prevent.stop="loadData"
              @reset.prevent.stop="filterReset"
              class="row full-width"
            >
              <div class="col-6">
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
                          <div class="col-6">
                            <q-input
                              dense
                              outlined
                              v-model="fecha.from"
                              label="Fecha inicio"
                              :rules="[(val) => !!val || 'Campo obligatorio']"
                              lazy-rules
                              mask="####-##-##"
                            >
                              <template v-slot:append>
                                <q-icon name="event" class="cursor-pointer">
                                  <q-popup-proxy
                                    ref="qDateProxyinicio"
                                    transition-show="scale"
                                    transition-hide="scale"
                                  >
                                    <q-date
                                      minimal
                                      v-model="fecha.from"
                                      mask="YYYY-MM-DD"
                                    >
                                      <div class="row items-center justify-end">
                                        <q-btn
                                          v-close-popup
                                          label="Guardar"
                                          color="primary"
                                          flat
                                        />
                                      </div>
                                    </q-date>
                                  </q-popup-proxy>
                                </q-icon>
                              </template>
                            </q-input>
                          </div>
                          <div class="col-6">
                            <q-input
                              :disable="!fecha.from"
                              dense
                              outlined
                              v-model="fecha.to"
                              label="Fecha fin"
                              :rules="[(val) => !!val || 'Campo obligatorio']"
                              lazy-rules
                              mask="####-##-##"
                            >
                              <template v-slot:append>
                                <q-icon name="event" class="cursor-pointer">
                                  <q-popup-proxy
                                    ref="qDateProxyfin"
                                    transition-show="scale"
                                    transition-hide="scale"
                                  >
                                    <q-date
                                      minimal
                                      v-model="fecha.to"
                                      mask="YYYY-MM-DD"
                                    >
                                      <div class="row items-center justify-end">
                                        <q-btn
                                          v-close-popup
                                          label="Guardar"
                                          color="primary"
                                          flat
                                        />
                                      </div>
                                    </q-date>
                                  </q-popup-proxy>
                                </q-icon>
                              </template>
                            </q-input>
                          </div>
                        </div>
                        <div class="row q-pt-xs q-gutter-x-xs no-wrap">
                          <div class="col-4">
                            <q-select
                              disable
                              dense
                              outlined
                              v-model="modelMintur"
                              multiple
                              :options="optionsMinturSelect"
                              label="MINTUR"
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
              <div class="col-4 q-pl-xs">
                <q-select
                  outlined
                  dense
                  options-dense
                  v-model="modelTipoFlota"
                  :options="optionsTipoFlota"
                  label="Tipo de flota"
                  option-label="tipo_flota"
                  option-value="tipo_flota"
                  :rules="[(val) => !!val || 'Campo obligatorio']"
                  lazy-rules
                  autofocus
                />
              </div>
              <div class="col-2 q-pl-md">
                <q-btn
                  type="submit"
                  :disable="filtro.length <= 0"
                  icon="mdi-magnify"
                  dense
                  color="primary"
                />
                <q-btn
                  type="reset"
                  :disable="filtro.length <= 0"
                  icon="mdi-notification-clear-all"
                  dense
                  color="primary"
                >
                  <q-tooltip>Resetear filtro</q-tooltip>
                </q-btn>
              </div>
            </form>
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
  name: 'ReporteCDT',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      dataReporte: [],
      filtro: [],
      fecha: { from: '', to: '' },
      columns: [
        {
          name: 'id',
          align: 'left',
          label: 'No.',
          field: 'id',
          sortable: true,
          headerClasses: '',
        },
        {
          name: 'tipo_medio_transporte',
          align: 'left',
          label: 'Tipo',
          field: 'tipo_medio_transporte',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'cantidad',
          align: 'left',
          label: 'cantidad',
          field: 'cantidad',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'trabajando',
          align: 'left',
          label: 'trabajando',
          field: 'trabajando',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'atm',
          align: 'left',
          label: 'atm',
          field: 'atm',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'taller',
          align: 'left',
          label: 'taller',
          field: 'taller',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'motor',
          align: 'left',
          label: 'motor',
          field: 'motor',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'otros',
          align: 'left',
          label: 'otros',
          field: 'otros',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'pendiente_baja',
          align: 'left',
          label: 'p/b',
          field: 'pendiente_baja',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'baja_tecnica',
          align: 'left',
          label: 'b/t',
          field: 'baja_tecnica',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'baja_turistica',
          align: 'left',
          label: 'b/tur',
          field: 'baja_turistica',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'coeficiente_disposicion_tecnica',
          align: 'left',
          label: 'por ciento',
          field: 'coeficiente_disposicion_tecnica',
          headerClasses: 'text-uppercase',
        },
      ],
      visibleColumns: [
        'coeficiente_disposicion_tecnica',
        'baja_turistica',
        'baja_tecnica',
        'pendiente_baja',
        'otros',
        'motor',
        'taller',
        'atm',
        'trabajando',
        'cantidad',
        'tipo_medio_transporte',
      ],
      optionsMinturSelect: ['OSDE', 'FINTUR', 'UP MINTUR'],
      modelMintur: null,
      modelOsde: null,
      modelEntidadInstalacion: null,
      modelProvincia: null,
      modelUPMintur: null,
      modelDelegacion: null,
      modelTipoFlota: null,
      optionsTipoFlota: [],
    };
  },
  async mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Reportes' },
      { label: 'Reporte CDT' },
    ]);
    this.loadTipoFlota();
  },
  watch: {
    'fecha.to': {
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
    ...mapActions('medio_transporte', ['reporteCDC', 'tipoFlotaList']),

    async loadData() {
      this.setLoading(true);
      const data = {
        tipo_flota: this.modelTipoFlota.tipo_flota,
        fecha_inicio: this.fecha.from,
        fecha_fin: this.fecha.to,
        instalacion_id: '',
        osde: this.modelProvincia,
        provincia: this.modelProvincia,
        up_mintur: this.modelUPMintur,
        delegacion: this.modelDelegacion,
      };
      this.dataReporte = await this.reporteCDC(data);
      this.setLoading(false);
    },
    async loadTipoFlota() {
      return Vue.prototype.$axios
        .post('/api/list_tipo_flota_instalacion')
        .then((result) => {
          this.optionsTipoFlota = result.data.data;
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    filterReset() {
      this.modelMintur = null;
      this.modelOsde = null;
      this.modelEntidadInstalacion = null;
      this.modelProvincia = null;
      this.modelUPMintur = null;
      this.modelDelegacion = null;
      this.fecha.from = '';
      this.fecha.to = '';
      this.filtro = [];
      this.modelTipoFlota = [];
    },
    async exportPDF() {
      this.setLoading(true);
      const url = '/api/reporte_CDT/pdf';
      return Vue.prototype
        .$axios({
          method: 'post',
          url: url,
          responseType: 'arraybuffer',
          data: {
            tipo_flota: this.modelTipoFlota.tipo_flota,
            tipo_medio_transporte: '',
            estado_tecnico: '',
            tipo_combustible: '',
            clase: '',
            ubicacion_motor: '',
            fecha_inicio: this.fecha.from,
            fecha_fin: this.fecha.to,
            instalacion_id: '',
            osde: this.modelProvincia,
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
          link.download = 'Reporte-CDT.pdf';
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
