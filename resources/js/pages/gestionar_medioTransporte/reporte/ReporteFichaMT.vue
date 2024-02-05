<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Reporte Ficha de medios de transporte
          </div>
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
            <div class="row full-width">
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
                        <div class="row q-gutter-x-xs no-wrap">
                          <div class="col-4">
                            <q-select
                              dense
                              outlined
                              v-model="modelClase"
                              :options="optionsClase"
                              label="Clase"
                              option-label="clase"
                              option-value="clase"
                              emit-value
                              map-options
                              style="width: 240px;"
                            />
                          </div>
                          <div class="col-4">
                            <q-select
                              dense
                              disable
                              outlined
                              v-model="modelMedioTransporte"
                              :options="optionsMedioTransporte"
                              label="Medio de transporte"
                              option-label="tipo"
                              option-value="tipo"
                              emit-value
                              map-options
                              style="width: 240px;"
                            />
                          </div>
                          <div class="col-4">
                            <q-select
                              dense
                              outlined
                              v-model="modelEstadoTecnico"
                              :options="optionsEstadoTecnico"
                              option-label="estado"
                              option-value="estado"
                              emit-value
                              map-options
                              label="Estado técnico"
                              style="width: 240px;"
                            />
                          </div>
                        </div>
                        <div class="row q-pt-xs q-gutter-x-xs no-wrap">
                          <div class="col-4">
                            <q-select
                              dense
                              outlined
                              v-model="modelTipoCombustible"
                              multiple
                              :options="optionsTipoCombustible"
                              label="Tipo de combustible"
                              option-label="combustible"
                              option-value="combustible"
                              emit-value
                              map-options
                              style="width: 240px;"
                            />
                          </div>
                          <div class="col-4">
                            <q-select
                              dense
                              outlined
                              v-model="modelUbicacionMotor"
                              :options="optionsUbicacionMotor"
                              option-label="ubicacion"
                              option-value="ubicacion"
                              emit-value
                              map-options
                              label="Ubicación del motor"
                              style="width: 240px;"
                            />
                          </div>
                        </div>
                        <div class="row q-pt-xs q-gutter-x-xs no-wrap">
                          <div class="col-4">
                            <q-select
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
                />
              </div>
              <div class="col-2 q-pl-md">
                <q-btn
                  :disable="modelTipoFlota === null"
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
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import { errorMessage } from '../../../utils/notificaciones';
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
          name: 'matricula',
          align: 'left',
          label: 'matrícula',
          field: 'matricula',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'marca',
          align: 'left',
          label: 'marca',
          field: 'marca',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'modelo',
          align: 'left',
          label: 'modelo',
          field: 'modelo',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'color',
          align: 'left',
          label: 'color',
          field: 'color',
          headerClasses: 'text-uppercase',
        },

        {
          name: 'anno_fabricacion',
          align: 'left',
          label: 'año',
          field: 'anno_fabricacion',
          headerClasses: 'text-uppercase',
        },

        {
          name: 'num_motor',
          align: 'left',
          label: 'num. motor',
          field: 'num_motor',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'num_carroceria',
          align: 'left',
          label: 'num. carrocería',
          field: 'num_carroceria',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'folio',
          align: 'left',
          label: 'folio',
          field: 'folio',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'electrico',
          align: 'left',
          label: 'eléctrico',
          field: 'electrico',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'indice_consumo',
          align: 'left',
          label: 'índice consumo',
          field: 'indice_consumo',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'combustible_asignado',
          align: 'left',
          label: 'combustible asignado',
          field: 'combustible_asignado',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'capacidad_carga',
          align: 'left',
          label: 'capacidad carga',
          field: 'capacidad_carga',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'neumaticos',
          align: 'left',
          label: 'cant. neumaticos',
          field: 'neumaticos',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'fecha_ficav',
          align: 'left',
          label: 'ficav',
          field: 'fecha_ficav',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'moto_horas',
          align: 'left',
          label: 'motor horas',
          field: 'moto_horas',
          headerClasses: 'text-uppercase',
        },
      ],
      visibleColumns: [
        'marca',
        'modelo',
        'color',
        'matricula',
        'anno_fabricacion',
        'electrico',
        'num_motor',
        'num_carroceria',
        'indice_consumo',
        'combustible_asignado',
        'capacidad_carga',
        'neumaticos',
        'fecha_ficav',
        'moto_horas',
      ],
      optionsMinturSelect: ['OSDE', 'FINTUR', 'UP MINTUR'],
      modelMintur: null,
      modelOsde: null,
      modelEntidadInstalacion: null,
      modelProvincia: null,
      modelUPMintur: null,
      modelDelegacion: null,
      modelTipoFlota: null,
      modelMedioTransporte: null,
      modelEstadoTecnico: null,
      modelTipoCombustible: null,
      modelClase: null,
      modelUbicacionMotor: null,
      optionsTipoFlota: [],
      optionsMedioTransporte: [],
      optionsEstadoTecnico: [],
      optionsTipoCombustible: [],
      optionsClase: [],
      optionsUbicacionMotor: [],
      optionsProvincia: [],
    };
  },
  async mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Reportes' },
      { label: 'Reporte Ficha de medios de transporte' },
    ]);
    this.loadDataNomencladores();
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('medio_transporte', ['reporteCDC', 'tipoFlotaList']),
    ...mapActions('tipo_flota', ['getTipo_flota']),
    ...mapActions('cmtransporte', ['getCmtransporte']),
    ...mapActions('tcmtransporte', ['getTcmtransporte']),
    ...mapActions('etmtransporte', ['getEtmtransporte']),
    ...mapActions('ubicacion_memnautico', ['getUbicacion_memnautico']),

    async loadData() {
      this.setLoading(true);
      const data = {
        tipo_flota: this.modelTipoFlota.tipo_flota,
        tipo_medio_transporte: this.modelUPMintur,
        estado_tecnico: this.modelEstadoTecnico,
        tipo_combustible: this.modelTipoCombustible,
        clase: this.modelClase,
        ubicacion_motor: this.modelUbicacionMotor,
        fecha_inicio: this.fecha.from,
        fecha_fin: this.fecha.to,
        instalacion_id: '',
        osde: this.modelProvincia,
        provincia: this.modelProvincia,
        up_mintur: this.modelUPMintur,
        delegacion: this.modelDelegacion,
      };
      return Vue.prototype.$axios
        .post('/api/reporte_fichamt', data)
        .then((result) => {
          this.dataReporte = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.setLoading(false);
          errorMessage(message.eDataError, err);
        });
    },
    async loadDataNomencladores() {
      this.optionsTipoFlota = await this.getTipo_flota();
      this.optionsTipoCombustible = await this.getTcmtransporte();
      this.optionsClase = await this.getCmtransporte();
      this.optionsEstadoTecnico = await this.getEtmtransporte();
      this.optionsUbicacionMotor = await this.getUbicacion_memnautico();
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
    },
    async exportPDF() {
      this.setLoading(true);
      const url = '/api/reporte_fichamt/pdf';
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
          link.download = 'Reporte-Ficha_medios_de_transporte.pdf';
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
