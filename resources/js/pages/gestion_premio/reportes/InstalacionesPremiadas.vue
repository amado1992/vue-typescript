<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Instalaciones premiadas
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white text-center text-uppercase"
          dense
          flat
          :data="data"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          no-data-label="No se encontraron elementos a mostrar"
          hide-pagination
        >
        </q-table>
      </q-card-section>
    </q-card>
  </div>
</template>
<script>
import { mapState, mapGetters, mapActions } from 'vuex';
export default {
  name: 'InstalacionesPremiadas',

  data() {
    return {
      data: [],
      loading: false,
      columns: [
        {
          name: 'nombre',
          align: 'left',
          label: 'osde',
          field: 'nombre',
          sortable: true,
        },
        {
          name: 'porciento',
          align: 'center',
          label: '%',
          field: 'porciento',
          sortable: true,
        },
      ],
    };
  },
  mounted() {
    this.loadData();
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Reportes' },
      { label: '% de instalaciones premiadas' },
    ]);
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('premio', ['getReportePorcientoPremiosEntidad']),

    loadData() {
      this.setLoading(true);
      this.getReportePorcientoPremiosEntidad().then((r) => {
        this.data = r;
        this.setLoading(false);
      });
    },
  },
};
</script>

<style></style>
