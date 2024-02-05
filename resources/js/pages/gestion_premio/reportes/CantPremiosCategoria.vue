<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Cant. de premios por categoría
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-tabs
        v-model="tab"
        dense
        class="text-grey"
        active-color="primary"
        indicator-color="primary"
        align="justify"
        narrow-indicator
      >
        <q-tab name="nac" label="Nacionales" />
        <q-tab name="inter" label="Internacionales" />
      </q-tabs>
      <q-tab-panels v-model="tab" animated>
        <q-tab-panel name="nac">
          <q-table
            table-header-class="bg-primary text-white text-center text-uppercase"
            flat
            dense
            :data="data[0] && data[0].premios_nacionales"
            :columns="columns"
            row-key="id"
            :loading="this.getterLoading()"
            loading-label="Cargando elementos"
            no-data-label="No se encontraron elementos a mostrar"
            hide-pagination
          >
          </q-table>
        </q-tab-panel>

        <q-tab-panel name="inter">
          <q-table
            table-header-class="bg-primary text-white text-center text-uppercase"
            flat
            dense
            :data="data[0] && data[0].premios_internacionales"
            :columns="columns"
            row-key="id"
            :loading="this.getterLoading()"
            loading-label="Cargando elementos"
            no-data-label="No se encontraron elementos a mostrar"
            hide-pagination
          >
          </q-table>
        </q-tab-panel>
      </q-tab-panels>
    </q-card>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
export default {
  name: 'CantPremiosCategoria',

  data() {
    return {
      tab: 'nac',
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
          name: 'cant_premios',
          align: 'center',
          label: 'cant. premios',
          field: 'cant_premios',
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
      { label: 'Cant. de premios por categoria' },
    ]);
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('premio', ['getReporteCantPremioCategoria']),

    loadData() {
      this.setLoading(true);
      this.getReporteCantPremioCategoria().then((r) => {
        this.data = r;
        this.setLoading(false);
      });
    },
  },
};
</script>

<style></style>
