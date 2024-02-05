<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Plan anual de importaciones
          </div>
          <q-space></q-space>
          <div class="col-2 text-subtitle1 text-uppercase q-mr-xl">
            plan total
            <q-badge
              outline
              align="middle"
              color="primary"
              class="text-body1 text-bold"
            >
              {{ totalPlan }}
            </q-badge>
          </div>
          <div class="col-2 text-subtitle1 text-uppercase">
            real total
            <q-badge
              outline
              align="middle"
              color="primary"
              class="text-body1 text-bold"
            >
              {{ totalReal }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <!-- tabla-->
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white"
          flat
          :data="importacion"
          :columns="columns"
          row-key="id"
          virtual-scroll
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          dense
          hide-pagination
        >
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="empresa" :props="props">
                {{ props.row.empresa.empresa }}
              </q-td>
              <q-td key="mes" :props="props">
                {{ mesesToString(props.row.mes) }}
              </q-td>
              <q-td key="plan" :props="props">
                {{ props.row.plan }}
              </q-td>
              <q-td key="real" :props="props">
                {{ props.row.real }}
              </q-td>
              <q-td key="xciento" :props="props">
                {{ Math.round((100 / props.row.plan) * props.row.real) }} %
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
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
  name: 'ReporteImportacionesAll',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      filterInput: false,
      loading: false,
      filter: '',
      importacion: [],
      totalPlan: 0,
      totalReal: 0,
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
        {
          name: 'empresa',
          align: 'left',
          label: 'emp. importadora',
          field: (row) => row.empresa && row.empresa.empresa,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'mes',
          align: 'left',
          label: 'mes',
          field: 'mes',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'plan',
          align: 'left',
          label: 'plan',
          field: 'plan',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'real',
          align: 'left',
          label: 'real',
          field: 'real',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'xciento',
          align: 'left',
          label: '%',
          field: '',
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
      { label: 'Plan anual de importaciones' },
    ]);
  },
  watch: {
    pagination: {
      handler() {
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      },
    },
    importacion: {
      handler() {
        this.importacion.forEach((element) => {
          this.totalPlan += element.plan;
          this.totalReal += element.real;
        });
      },
    },
  },

  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('importacion', ['getAllImportaciones']),
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
    async loadData(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getAllImportaciones(data).then((r) => {
        this.importacion = r;
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    xciento(x, y) {
      return (x / y) * 100;
    },
    mesesToString(data) {
      let mes = '';
      switch (data) {
        case 1:
          mes = 'Enero';
          break;
        case 2:
          mes = 'Febrero';
          break;
        case 3:
          mes = 'Marzo';
          break;
        case 4:
          mes = 'Abril';
          break;
        case 5:
          mes = 'Mayo';
          break;
        case 6:
          mes = 'Junio';
          break;
        case 7:
          mes = 'Julio';
          break;
        case 8:
          mes = 'Agosto';
          break;
        case 9:
          mes = 'Septiembre';
          break;
        case 10:
          mes = 'Octubre';
          break;
        case 11:
          mes = 'Noviembre';
          break;
        case 12:
          mes = 'Diciembre';
          break;
      }
      return mes;
    },
  },
};
</script>

<style scoped></style>
