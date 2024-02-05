<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Almacenes con mal estado constructivo
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white text-center text-uppercase"
          flat
          dense
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
  name: 'mostrar_almacenesestadoconstructivo',

  data() {
    return {
      data: [],
      loading: false,
      columns: [
        {
          name: 'osde',
          align: 'left',
          label: 'osde',
          field: 'osde',
          sortable: true,
        },
        {
          name: 'almacenes_mal_estado_constructivo',
          align: 'center',
          label: 'almacenes con mal estado constructivo',
          field: 'almacenes_mal_estado_constructivo',
          sortable: true,
        },
        {
          name: 'total_almacenes',
          align: 'center',
          label: 'almacenes (total)',
          field: 'total_almacenes',
          sortable: true,
        },
        {
          name: 'porciento',
          align: 'center',
          label: 'porciento (%)',
          field: 'porciento',
          sortable: true,
        }
      ],
    };
  },
  mounted() {
    this.loadData();
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Logística' },
      { label: 'Almacenes' },
      { label: 'Reportes' },
      { label: 'Almacenes con mal estado constructivo' },
    ]);
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('almacen', ['mostrar_almacenes_estadoconstructivo']),

    loadData() {
      this.setLoading(true);
      this.mostrar_almacenes_estadoconstructivo().then((r) => {
        this.data = r;
        this.setLoading(false);
      });
    },
  },
};
</script>

<style></style>
