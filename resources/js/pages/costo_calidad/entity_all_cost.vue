<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Costo calidad por entidades
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section class="no-padding">
        <q-table
          class="q-px-md q-pb-md"
          table-header-class="bg-primary text-white text-uppercase"
          flat
          dense
          :data="data"
          :columns="columns"
          row-key="id"
          :filter="filter"
          :loading="this.getterLoading()"
          :rows-per-page-options="[10, 20, 30, 40, 50]"
          no-data-label="No se encontraron elementos a mostrar"
          no-results-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col">
                <q-input
                  dense
                  outlined
                  flat
                  v-model="filter"
                  debounce="1000"
                  placeholder="Buscar"
                />
              </div>
            </div>
          </template>
        </q-table>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
export default {
  name: 'EntityAllCost',
  data() {
    return {
      filterInput: false,
      loading: true,
      filter: '',
      data: [],
      pagination: {
        sortBy: 'desc',
        descending: false,
        page: 0,
        rowsPerPage: 1,
        rowsNumber: 10,
      },
      columns: [
        {
          name: 'nombre',
          align: 'center',
          label: 'entidad',
          field: (row) => row.nombre,
          sortable: true,
        },
        {
          name: 'costo conformidad',
          align: 'center',
          label: 'costo conformidad',
          field: (row) => row.total_costo_conformidad,
          sortable: true,
        },
        {
          name: 'costo no conformidad',
          align: 'center',
          label: 'costo no conformidad',
          field: (row) => row.total_costo_noconformidad,
          sortable: true,
        },
        {
          name: 'costo de calidad',
          align: 'center',
          label: 'costo calidad',
          field: (row) => row.total_costo_calidad,
          sortable: true,
        },
        {
          name: 'venta neta',
          align: 'center',
          label: 'venta neta',
          field: (row) => row.total_venta_neta,
          sortable: true,
        },
      ],
    };
  },
  created() {
    this.loadData();
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Costo de calidad' },
      { label: 'Reportes' },
      { label: 'Costo calidad por entidades' },
    ]);
    this.onRequest({
      pagination: this.pagination,
      filter: undefined,
    });
  },
  methods: {
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('costo_calidad', ['getDataEntiyAllCost_report', '']),
    ...mapGetters('utils', ['getterLoading']),

    async loadData() {
      this.setLoading(true);
      this.data = await this.getDataEntiyAllCost_report();
      this.setLoading(false);
    },
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    onRequest(props) {
      const { page } = props.pagination;
      this.pagination.page = page;
      this.loadData();
    },
  },
};
</script>

<style scoped></style>
