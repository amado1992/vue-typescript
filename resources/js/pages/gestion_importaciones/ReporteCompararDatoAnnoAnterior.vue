<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Comparar plan con año anterior
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <!-- tabla-->
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white"
          flat
          :data="dataReporte"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          no-data-label="No se encontraron elementos a mostrar"
          dense
          hide-pagination
        >
          <template v-slot:header="props">
            <q-tr v-for="(row, index) in headerData" :key="index">
              <q-th
                :auto-width="cell.colspan === '0'"
                v-for="(cell, cellCount) in row"
                :key="cellCount"
                :colspan="cell.colspan"
                :rowspan="cell.rowspan"
                align="center"
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
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="renglon" :props="props" class="text-weight-bold">
                {{ props.row.renglon }}
              </q-td>
              <q-td key="indicadores" :props="props">
                {{ props.row.indicador }}
              </q-td>
              <q-td key="plan_last_year" :props="props">
                {{ props.row.total_plan_last_year }}
              </q-td>
              <q-td key="real_last_year" :props="props">
                {{ props.row.total_real_last_year }}
              </q-td>
              <q-td key="xciento_lastyear" :props="props">
                {{ props.row.xciento_lastyear }}
              </q-td>
              <q-td key="total_plan_year" :props="props">
                {{ props.row.total_plan_year }}
              </q-td>
              <q-td key="total_real_year" :props="props">
                {{ props.row.total_real_year }}
              </q-td>
              <q-td key="xciento_year" :props="props">
                {{ props.row.xciento_year }}
              </q-td>
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
export default {
  name: 'ReporteCompararPlanImportacion',
  data() {
    return {
      filterInput: false,
      loading: false,
      filter: '',
      dataReporte: [],
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      headerData: [
        [
          {
            label: 'Familia de productos',
            colspan: '3',
          },
          {
            label: 'Año Anterior',
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
          {
            label: 'Año Actual',
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
          name: 'renglon',
          align: 'left',
          label: 'Renglón',
          field: 'renglon',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'indicadores',
          align: 'left',
          label: 'Indicadores',
          field: 'indicador',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'plan_last_year',
          align: 'center',
          label: 'plan',
          field: 'total_plan_last_year',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'real_last_year',
          align: 'center',
          label: 'real',
          field: 'total_real_last_year',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'xciento_lastyear',
          align: 'center',
          label: '%',
          field: 'xciento_lastyear',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'total_plan_year',
          align: 'center',
          label: 'plan',
          field: 'total_plan_year',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'total_real_year',
          align: 'center',
          label: 'real',
          field: 'total_real_year',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'xciento_year',
          align: 'center',
          label: '%',
          field: 'xciento_year',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
      ],
    };
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Logística' },
      { label: 'Importaciones' },
      { label: 'Reportes' },
      { label: 'Comparar plan con año anterior' },
    ]);
    this.loadData();
  },
  computed: {},
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('importacion', ['getDataPlanAnteriorvsActual']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    async loadData() {
      this.setLoading(true);
      this.dataReporte = await this.getDataPlanAnteriorvsActual();
      this.setLoading(false);
    },
  },
};
</script>

<style scoped></style>
