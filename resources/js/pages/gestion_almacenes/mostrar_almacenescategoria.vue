<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Almacenes por nivel de categorías
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
  name: 'mostrar_almacenescategoria',

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
          name: 'categoria_nivel_uno',
          align: 'center',
          label: '1er Nivel Tecnológico',
          field: 'categoria_nivel_uno',
          sortable: true,
        },
        {
          name: 'categoria_nivel_dos',
          align: 'center',
          label: '2do Nivel Tecnológico',
          field: 'categoria_nivel_dos',
          sortable: true,
        },
        {
          name: 'categoria_nivel_tres',
          align: 'center',
          label: '3er Nivel Tecnológico',
          field: 'categoria_nivel_tres',
          sortable: true,
        },
      ],
    };
  },
  mounted() {
    this.loadData();
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Logística' },
      { label: 'Almacenes' },
      { label: 'Reportes' },
      { label: 'Almacenes por nivel de categorías' },
    ]);
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('almacen', ['mostrar_almacenes_categoria']),

    loadData() {
      this.setLoading(true);
      this.mostrar_almacenes_categoria().then((r) => {
        this.data = r;
        this.setLoading(false);
      });
    },
  },
};
</script>

<style></style>
