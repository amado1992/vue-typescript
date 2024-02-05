<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">Reporte CDT totalizador</div>
          <q-space></q-space>

          <div class="col-1">
            <q-btn icon="print" dense color="primary" size="sm">
              <q-tooltip>Imprimir</q-tooltip>
            </q-btn>
            <q-btn icon="mdi-file-download" dense color="primary" size="sm">
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
          hide-pagination
        >
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import * as message from '../../../utils/resources';
import Vue from 'vue';

export default {
  name: 'ReporteParqueTotal',
  data() {
    return {
      dataReporte: [],
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
        'baja_tecnica',
        'pendiente_baja',
        'otros',
        'motor',
        'taller',
        'atm',
        'trabajando',
        'cantidad',
      ],
    };
  },
  async mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Reportes' },
      { label: 'Reporte CDT totalizador' },
    ]);
    this.loadData();
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('medio_transporte', ['parqueTotal']),

    async loadData() {
      this.setLoading(true);
      this.dataReporte = await this.parqueTotal();
      this.setLoading(false);
    },
  },
};
</script>

<style></style>