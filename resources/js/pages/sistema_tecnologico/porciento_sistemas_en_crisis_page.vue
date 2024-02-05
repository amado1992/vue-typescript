<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Filtrar por instalación u osde
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <div class="row justify-center q-gutter-x-xs">
          <div class="col-sm-12 col-md-3 col-lg-3">
            <q-select
              outline
              v-model="parameter.instalacion_id"
              :options="instalaciones"
              label="Instalaciones"
              options-dense
              option-value="id"
              option-label="nombre"
              emit-value
              map-options
            />
          </div>

          <div class="col-sm-12 col-md-3 col-lg-3">
            <q-select
              outline
              v-model="parameter.osde_id"
              :options="osdes"
              label="Osdes"
              options-dense
              option-value="id"
              option-label="nombre"
              emit-value
              map-options
            />
          </div>
          <div class="q-pt-lg">
            <q-btn
              @click="aplicarFiltro()"
              dense
              color="primary"
              icon="mdi-filter"
              :disable="isDisable"
            >
              <q-tooltip>Aplicar filtro</q-tooltip>
            </q-btn>
          </div>
          <div class="q-pt-lg">
            <q-btn
              @click="limpiarFiltro()"
              dense
              color="primary"
              icon-right="mdi-delete-sweep"
            >
              <q-tooltip>Limpiar filtro</q-tooltip>
            </q-btn>
          </div>
        </div>
        <div class="row q-pt-sm">
          <div class="col">
            <highcharts :options="chartExample"></highcharts>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { Chart } from "highcharts-vue";
import { mapState, mapGetters, mapActions } from "vuex";
import { date } from "quasar";
import axios from "axios";
import { errorMessage, successMessage } from "../../utils/notificaciones";

export default {
  name: "EntityAllCostsTotal",
  components: {
    highcharts: Chart,
  },
  data() {
    return {
     // parameter: { osde_id: 1, instalacion_id: 1 },
      parameter: { osde_id: null, instalacion_id: null},
      isDisable: false,
      osdes: [],
      instalaciones: [],
      //CHART
      chartExample: {
        chart: {
          type: "column",
        },
        title: {
          text: "% de equipos fuera de servicios en un sistema en crisis",
        },
        subtitle: {
          text: "",
        },
        accessibility: {
          announceNewData: {
            enabled: true,
          },
        },
        xAxis: {
          type: "category",
        },
        yAxis: {
          title: {
            text: "Total %",
          },
        },
        legend: {
          enabled: false,
        },
        plotOptions: {
          series: {
            borderWidth: 0,
            dataLabels: {
              enabled: true,
              format: "{point.y:.1f}%",
            },
          },
        },

        tooltip: {
          headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
          pointFormat:
            '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> del total<br/>',
        },

        series: [
          {
            name: "% de equipos fuera de servicios",
            colorByPoint: true,
            data: [],
          },
        ],
      },
    };
  },
  created() {
    this.loadInstalaciones();
    this.loadOsdes();
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Dirección de Servicios Técnicos" },
      { label: "Disponibilidad de los sitemas tecnológicos" },
      { label: "Reportes" },
      { label: "% de equipos fuera de servicios en un sistema en crisis" },
    ]);
    this.subtitle();
  },
  methods: {
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    ...mapActions("utils", ["setLoading"]),
    ...mapActions("costo_calidad", ["getYearEntiyAllCostTotalPage_report"]),
    ...mapGetters("utils", ["getterLoading"]),

    async aplicarFiltro() {
      this.isDisable = true;
      this.loadData()
    },
    limpiarFiltro() {
      this.isDisable = false;
      this.parameter.instalacion_id = null
      this.parameter.osde_id = null

      this.chartExample.series[0].data = [];
      this.subtitle();
    },
    async loadData() {
      this.subtitle();
      return await axios
        .post("/api/porciento_sistemas_en_crisis", this.parameter)
        .then((response) => {
          this.chartExample.series[0].data.push({
            name: "Mantenimiento y reparacion",
            y: parseInt(response.data[0].mantenimientoReparacion),
            drilldown: "mantenimientoReparacion",
          });

          this.chartExample.series[0].data.push({
            name: "Reparación capital",
            y: parseInt(response.data[0].reparacionCapital),
            drilldown: "reparacionCapital",
          });
          this.chartExample.series[0].data.push({
            name: "Reposición",
            y: parseInt(response.data[0].reposicion),
            drilldown: "reposicion",
          });
          successMessage("Los datos han sido enviados satisfactoriamente");
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadInstalaciones() {
      return await axios
        .get("/api/lists_instalaciones")
        .then((response) => {
          this.instalaciones = response.data.data;
        })
        .catch((error) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          })
        })
    },
    async loadOsdes() {
      return await axios
        .get("/api/lists_osdes")
        .then((response) => {
          this.osdes = response.data.data;
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    subtitle() {
      this.chartExample.subtitle.text = "";
    },
  },
};
</script>
