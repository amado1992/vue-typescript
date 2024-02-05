<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Disponilidad de habitaciones
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <div class="row justify-center">
          <vue-highcharts :options="pieOptions" ref="pieChart"></vue-highcharts>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>
<script>
import VueHighcharts from 'vue2-highcharts';
import { mapState, mapGetters, mapActions } from 'vuex';
export default {
  name: 'PorciertoDisponibilidad',
  components: {
    VueHighcharts,
  },
  data() {
    return {
      form_data: {},
      pieOptions: {
        chart: {
          type: 'pie',
          options3d: {
            enabled: true,
            alpha: 45,
          },
        },
        title: {
          text: 'Disponibilidad menor o igual al 95%',
        },
        plotOptions: {
          pie: {
            innerSize: 100,
            depth: 45,
          },
        },
        series: [
          {
            name: 'Cantidad de habitaciones disponibles',
            data: [],
          },
        ],
      },
    };
  },
  mounted() {
    this.loadData();
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Servicios Técnicos' },
      { label: 'Disponibilidad de habitaciones' },
      { label: 'Reportes' },
      { label: '% Disponibilidad de habitaciones' },
    ]);
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('disponibilidad', ['getPorciento']),

    llenarOption(data) {
      let newOption = null;
      return (newOption = {
        chart: {
          type: 'pie',
          options3d: {
            enabled: true,
            alpha: 45,
          },
        },
        title: {
          text: 'Disponibilidad menor o igual al 95%',
        },
        plotOptions: {
          pie: {
            innerSize: 100,
            depth: 45,
          },
        },
        series: [
          {
            name: 'Cantidad de habitaciones disponibles',
            data: data,
          },
        ],
      });
    },

    async loadData() {
      this.setLoading(true);
      await this.getPorciento(this.form_data).then((r) => {
        const datosllenos = this.llenarOption(r);
        this.pieOptions = datosllenos;
        this.setLoading(false);
      });
    },
  },
};
</script>
