<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">Solo Cubasol</div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <!-- tabla-->
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white"
          flat
          dense
          :data="data"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          no-data-label="No se encontraron elementos a mostrar"
          :visible-columns="visibleColumns"
          hide-pagination
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col-4">
                <q-input
                  autofocus
                  dense
                  outlined
                  id="anno"
                  label="Año"
                  v-model="form_data.anno"
                  mask="####"
                  @input="searchInfo()"
                />
              </div>
              <div class="col-4 q-px-sm">
                <q-select
                  id="mes"
                  label="Mes"
                  v-model="form_data.mes"
                  dense
                  outlined
                  input-debounce="0"
                  :options="meses"
                  options-dense
                  option-label="label"
                  option-value="value"
                  @input="searchInfo()"
                />
              </div>
              <div class="col-4">
                <q-select
                  v-model="visibleColumns"
                  label="Filtrar columnas"
                  multiple
                  outlined
                  dense
                  options-dense
                  display-value=""
                  emit-value
                  map-options
                  :options="columns"
                  option-value="name"
                  style="min-width: 150px;"
                />
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
import { warningMessage } from '../../../utils/notificaciones';

export default {
  name: 'ListCubasolReporte',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      monthPicked: [],
      loading: false,
      data: [],
      meses: [
        {
          value: 1,
          label: 'Enero',
        },
        {
          value: 2,
          label: 'Febrero',
        },
        {
          value: 3,
          label: 'Marzo',
        },
        {
          value: 4,
          label: 'Abril',
        },
        {
          value: 5,
          label: 'Mayo',
        },
        {
          value: 6,
          label: 'Junio',
        },
        {
          value: 7,
          label: 'Julio',
        },
        {
          value: 8,
          label: 'Agosto',
        },
        {
          value: 9,
          label: 'Septiembre',
        },
        {
          value: 10,
          label: 'Octubre',
        },
        {
          value: 11,
          label: 'Noviembre',
        },
        {
          value: 12,
          label: 'Diciembre',
        },
      ],
      form_data: {
        mes: null,
        anno: null,
        instalacion_id: 1,
      },
      visibleColumns: [
        'empresa',
        'total_inst_xentidad',
        'total_inst_xlic',
        'lic_retirada',
        'lic_tramite',
        'lic_vigente',
        'calculoXcientoLicVigente',
        'total_inst_xavalcitma',
        'avalcitma_vencida',
        'avalcitma_tramite',
        'avalcitma_vigente',
        'avalcitma_vigente',
        'calculoXcientoAvalCitmaVigente',
        'total_inst_xavalapci',
        'avalapci_vencida',
        'avalapci_tramite',
        'avalapci_vigente',
        'calculoXcientoAvalApciVigente',
      ],
      columns: [
        {
          name: 'empresa',
          align: 'left',
          label: 'empresa',
          field: (row) => row.entidad,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'total_inst_xentidad',
          align: 'center',
          label: 'total inst.',
          field: 'total_inst_xentidad',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'total_inst_xlic',
          align: 'center',
          label: 'inst.',
          field: 'total_inst_xlic',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'lic_retirada',
          align: 'center',
          label: 'lic. retirada',
          field: 'lic_retirada',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'lic_tramite',
          align: 'center',
          label: 'lic. tramite',
          field: 'lic_tramite',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'lic_vigente',
          align: 'center',
          label: 'lic. vigente',
          field: 'lic_vigente',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'calculoXcientoLicVigente',
          align: 'center',
          label: '% vigente',
          field: 'calculoXcientoLicVigente',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'total_inst_xavalcitma',
          align: 'center',
          label: 'inst.',
          field: 'total_inst_xavalcitma',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'avalcitma_vencida',
          align: 'center',
          label: 'avalcitma vencida',
          field: 'avalcitma_vencida',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'avalcitma_tramite',
          align: 'center',
          label: 'avalcitma tramite',
          field: 'avalcitma_tramite',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'avalcitma_vigente',
          align: 'center',
          label: 'avalcitma vigente',
          field: 'avalcitma_vigente',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'calculoXcientoAvalCitmaVigente',
          align: 'center',
          label: '% vigente',
          field: 'calculoXcientoAvalCitmaVigente',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'total_inst_xavalapci',
          align: 'center',
          label: 'inst.',
          field: 'total_inst_xavalapci',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'avalapci_vencida',
          align: 'center',
          label: 'avalapci vencida',
          field: 'avalapci_vencida',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'avalapci_tramite',
          align: 'center',
          label: 'avalapci tramite',
          field: 'avalapci_tramite',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'avalapci_vigente',
          align: 'center',
          label: 'avalapci vigente',
          field: 'avalapci_vigente',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'calculoXcientoAvalApciVigente',
          align: 'center',
          label: '% vigente',
          field: 'calculoXcientoAvalApciVigente',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
      ],
    };
  },
  async mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Indicadores L210' },
      { label: 'Reportes' },
      { label: 'Lista solo Cubasol' },
    ]);
  },
  computed: {},
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('indicadoresl210', ['getReporteListaCubasol']),

    async loadData() {
      if (this.form_data.mes === null && this.form_data.anno === null) {
        warningMessage('Debe seleccionar un criterio de busqueda');
      } else {
        this.setLoading(true);
        const data = {
          mes: this.form_data.mes,
          anno: this.form_data.anno,
        };
        await this.getReporteListaCubasol(data).then((response) => {
          this.data = response;
          this.setLoading(false);
        });
      }
    },
    searchInfo() {
      if (this.form_data.anno && this.form_data.mes) this.loadData();
    },
  },
};
</script>

<style lang="sass"></style>>
