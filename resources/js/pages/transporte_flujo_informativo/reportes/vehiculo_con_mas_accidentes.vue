<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Vehiculos con más accidentes
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <div class="row justify-center">
          <grafico
            :graph_type="grafico_data.graph_type"
            :title="grafico_data.graph_title"
            :series="grafico_data"
          />
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>
<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import SimpleGraph from "../../../components/dashboard/SimpleGraph";
import Vue from "vue";
import {errorMessage} from "../../../utils/notificaciones";
import * as message from "../../../utils/resources";
export default {
  name: 'Vehiculomasaccidentado',
  components: {
    grafico: SimpleGraph,
  },
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      instalacion_id: sessionStorage.getItem('instalacion_id'),
      grafico_data: {
        name: null,
        data: null,
        xAxis_text: [],
        yAxis_text: null,
        graph_type: null,
        graph_title: null,
        graph_subtitle: null,
      }
    };
  },
  mounted() {
    this.loadData();
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Reportes' },
      { label: 'Vehiculo más accidentado' },
    ]);
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),


    resetData() {
      this.grafico_data.name = null;
      this.grafico_data.data = null;
      this.grafico_data.xAxis_text = [];
      this.grafico_data.yAxis_text = null;
    },

    async loadData() {
      this.setLoading(true);
      this.resetData();
      this.loadDataDispHabitaciones();
    },
    loadDataDispHabitaciones() {
      const data = {
        instalacion_id: this.instalacion_id,
      };
      Vue.prototype.$axios
        .post('/api/reporte/vehiculoconmasaccidentes', data)
        .then((result) => {
          this.grafico_data.graph_type = 'pie';
          this.grafico_data.data = result.data.data;
          this.grafico_data.name = 'Total de accidentes';
          this.grafico_data.graph_title = 'Accidentalidad por color del vehiculo';
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
  },
};
</script>
