<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Horarios con más accidentes
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
        <q-tab name="madrugada" label="Madrugada" />
        <q-tab name="mannana" label="Mañana" />
        <q-tab name="tarde" label="Tarde" />
        <q-tab name="noche" label="Noche" />
      </q-tabs>
      <q-tab-panels v-model="tab" animated>
        <q-tab-panel name="madrugada">
          <q-table
            table-header-class="bg-primary text-white text-center text-uppercase"
            flat
            dense
            :data="data[0] && data[0].madrugada"
            :columns="columns"
            row-key="id"
            :loading="this.getterLoading()"
            loading-label="Cargando elementos"
            no-data-label="No se encontraron elementos a mostrar"
            hide-pagination
          >
          </q-table>
        </q-tab-panel>
        <q-tab-panel name="mannana">
          <q-table
            table-header-class="bg-primary text-white text-center text-uppercase"
            flat
            dense
            :data="data[0] && data[0].mannana"
            :columns="columns"
            row-key="id"
            :loading="this.getterLoading()"
            loading-label="Cargando elementos"
            no-data-label="No se encontraron elementos a mostrar"
            hide-pagination
          >
          </q-table>
        </q-tab-panel>
        <q-tab-panel name="tarde">
          <q-table
            table-header-class="bg-primary text-white text-center text-uppercase"
            flat
            dense
            :data="data[0] && data[0].tarde"
            :columns="columns"
            row-key="id"
            :loading="this.getterLoading()"
            loading-label="Cargando elementos"
            no-data-label="No se encontraron elementos a mostrar"
            hide-pagination
          >
          </q-table>
        </q-tab-panel>
        <q-tab-panel name="noche">
          <q-table
            table-header-class="bg-primary text-white text-center text-uppercase"
            flat
            dense
            :data="data[0] && data[0].noche"
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
  name: 'ReporteHorarioConMasAccidentes',

  data() {
    return {
      tab: 'madrugada',
      data: [],
      loading: false,
      columns: [
        {
          name: 'instalacion',
          align: 'left',
          label: 'instalación',
          field: 'instalacion',
          sortable: true,
        },
        {
          name: 'cant_vehiculos',
          align: 'left',
          label: 'cant. vehiculos',
          field: 'cant_vehiculos',
          sortable: true,
        }
      ],
    };
  },
  mounted() {
    this.loadData();
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Reportes' },
      { label: 'Horario con más accidentes' },
    ]);
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('gestion_accidente', ['getHorarioAccidentes']),

    loadData() {
      this.setLoading(true);
      this.getHorarioAccidentes().then((r) => {
        this.data = r;
        this.setLoading(false);
      });
    },
  },
};
</script>

<style></style>

