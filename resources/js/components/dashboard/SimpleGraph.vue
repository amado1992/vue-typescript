<template>
  <div>
    <highcharts :options="chartOptions" :callback="myCallback"></highcharts>
  </div>
</template>

<script>
import { Chart } from 'highcharts-vue';

export default {
  name: 'SimpleGraph',
  components: {
    highcharts: Chart,
  },
  props: {
    /** Tipo de gráfico a utilizar
     * Opciones:'spline', 'line', 'bar', 'pie'
     */
    graph_type: {
      type: String,
      default: 'pie',
    },
    /** Datos que utilizará el gráfico
     * Ejemplo: series: [{name: 'Total reportes', data: [15, 3, 6, 2, 6, 4, 5, 5]}, ]
     */
    series: {
      default: () => {},
    },
    /** Título del gráfico */
    title: {
      type: String,
      default: '',
    },
    /** Subtítulo del gráfico */
    subtitle: {
      type: String,
      default: '',
    },
    /** Texto a mostrar en el eje X
     * Ejemplo:['Apples', 'Bananas', 'Oranges']
     */
    xAxis_text: {
      type: Array,
      default: () => [],
    },
    /** Texto a mostrar en el eje Y
     * Ejemplo: text:'Fruits'
     */
    yAxis_title: {
      type: String,
      default: '',
    },
    /** Opciones para los gráficos
     * Ejemplo: { pie: { allowPointSelect: true } }
     */
    plotOptions: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {};
  },
  computed: {
    chartOptions() {
      return {
        chart: { type: this.graph_type },
        title: { text: this.title },
        subtitle: { text: this.subtitle },
        xAxis: { categories: this.xAxis_text },
        yAxis: { min: 0, title: { text: this.yAxis_title } },
        series: this.series,
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: false,
            },
            showInLegend: true,
          },
          bar: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: false,
            },
            showInLegend: true,
          },
          line: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: false,
            },
            showInLegend: true,
          },
          spline: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: false,
            },
            showInLegend: true,
          },
          column: {
            pointPadding: 0.2,
            borderWidth: 0,
          },
        },
      };
    },
  },
  methods: {
    myCallback() {
      console.log('component loaded! :)');
    },
  },
};
</script>

<style></style>
