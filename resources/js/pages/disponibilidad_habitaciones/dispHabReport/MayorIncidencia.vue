<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Habitaciones con mayor incidencia
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <q-table
          flat
          dense
          table-header-class="bg-primary text-white text-center text-uppercase"
          :data="data"
          :columns="columns"
          :filter="filter"
          :loading="loading"
          @request="loadData"
          :pagination.sync="pagination"
          :rows-per-page-options="[10, 20]"
          no-data-label="No se encontraron elementos a mostrar"
          no-results-label="No se encontraron elementos a mostrar"
        >
        </q-table>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { infoMessage } from '../../../utils/notificaciones';
export default {
  name: 'MayorIncidencia',
  data() {
    return {
      filterInput: false,
      filter: '',
      loading: false,
      data: [],
      options1: [],
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
        {
          name: 'entidad_id',
          align: 'left',
          label: 'Osde',
          field: (row) => row.entidades.nombre,
          sortable: true,
        },
        {
          name: 'clasificacion_id',
          align: 'left',
          label: 'Sistema',
          field: (row) => row.clasificaciones.nombre,
          sortable: true,
        },
        {
          name: 'causa_id',
          align: 'left',
          label: 'Causa',
          field: (row) => row.causas.nombre,
          sortable: true,
        },
        {
          name: 'cant_habitaciones_nd',
          align: 'center',
          label: 'Cantidad de Habitaciones',
          field: 'cant_habitaciones_nd',
          sortable: true,
        },
      ],
    };
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Servicios Técnicos' },
      { label: 'Disponibilidad de habitaciones' },
      { label: 'Reportes' },
      { label: 'Habitaciones con mayor incidencia' },
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
  },
  methods: {
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('disponibilidad', ['getMayorIncidencia']),

    async loadData(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const arraySearch = [];
      this.columns.forEach(function (item) {
        if (item.search) {
          arraySearch.push({
            name: item.field,
            value: filter,
          });
        }
      });
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: arraySearch,
      };
      await this.getMayorIncidencia(data).then((r) => {
        if (r[0] === null) infoMessage('No hay Osdes');
        else {
          this.pagination.rowsNumber = r.total;
          this.pagination.page = page;
          this.pagination.rowsPerPage = rowsPerPage;
          this.pagination.sortBy = sortBy;
          this.pagination.descending = descending;
          this.data = r;
          this.setLoading(false);
        }
      });
    },
  },
};
</script>

<style scoped></style>
