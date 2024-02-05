<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Filtrar costos por entidades
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <div class="row justify-center q-gutter-x-xs">
          <q-btn
            outline
            color="grey-8"
            label="Rango de fecha para el año actual"
            @click="prompt = true"
          ></q-btn>

          <q-btn
            outline
            color="grey-8"
            label="Rango de fecha para el año anterior"
            @click="promptLast = true"
          ></q-btn>

          <q-btn
            @click="aplicarFiltro()"
            dense
            color="primary"
            icon="mdi-filter"
            :disable="isDisable"
          >
            <q-tooltip>Aplicar filtro</q-tooltip>
          </q-btn>
          <q-btn
            @click="limpiarFiltro()"
            dense
            color="primary"
            icon-right="mdi-delete-sweep"
          >
            <q-tooltip>Limpiar filtro</q-tooltip>
          </q-btn>
        </div>
        <div class="row q-pt-sm">
          <div class="col-md-6">
            <highcharts :options="chartExample"></highcharts>
          </div>
          <div class="col-md-6">
            <highcharts :options="chartAnnoAnterior"></highcharts>
          </div>
        </div>
      </q-card-section>
      <!-- QDialog para las fechas-->
      <q-dialog v-model="prompt" persistent>
        <q-card style="min-width: 19%;">
          <q-card-section class="no-padding">
            <template>
              <div>
                <q-date v-model="datePicker" mask="YYYY-MM-DD" range />
              </div>
            </template>
          </q-card-section>

          <q-card-actions align="right" class="text-primary">
            <q-btn flat label="Cancelar" v-close-popup />
            <q-btn flat label="Aceptar" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>

      <q-dialog v-model="promptLast" persistent>
        <q-card style="min-width: 19%;">
          <q-card-section class="no-padding">
            <template>
              <div>
                <q-date v-model="datePickerLast" mask="YYYY-MM-DD" range />
              </div>
            </template>
          </q-card-section>

          <q-card-actions align="right" class="text-primary">
            <q-btn flat label="Cancelar" v-close-popup />
            <q-btn flat label="Aceptar" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End QDialog para las fechas-->
    </q-card>
  </div>
</template>

<script>
import { Chart } from 'highcharts-vue';
import { mapState, mapGetters, mapActions } from 'vuex';
import { date } from 'quasar';

export default {
  name: 'EntityAllCostsTotal',
  components: {
    highcharts: Chart,
  },
  data() {
    return {
      prompt: false,
      datePicker: { from: '2020-07-08', to: '2020-07-17' },
      promptLast: false,
      datePickerLast: { from: '2020-07-08', to: '2020-07-17' },
      parametersDatePickerLast: { fechaComienzo: '', fechaFin: '' },
      parametersDatePicker: { fechaComienzo: '', fechaFin: '' },
      isDisable: false,
      chartExample: {
        chart: {
          type: 'column',
        },
        title: {
          text: 'Costos por entidades',
        },
        subtitle: {
          text: '',
        },
        xAxis: {
          categories: [],
          crosshair: true,
        },
        yAxis: {
          min: 0,
          title: {
            text: '$ Costos por entidades',
          },
        },
        tooltip: {
          headerFormat:
            '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat:
            '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true,
        },
        plotOptions: {
          column: {
            pointPadding: 0.2,
            borderWidth: 0,
          },
        },
        series: [
          {
            name: 'Total de costos de conformidad',
            data: [],
          },
          {
            name: 'Total de costos de no conformidad',
            data: [],
          },
          {
            name: 'Total de costos de calidad',
            data: [],
          },
          {
            name: 'Total de ventas netas',
            data: [],
          },
        ],
      },

      chartAnnoAnterior: {
        chart: {
          type: 'column',
        },
        title: {
          text: 'Costos por entidades',
        },
        subtitle: {
          text: '',
        },
        xAxis: {
          categories: [],
          crosshair: true,
        },
        yAxis: {
          min: 0,
          title: {
            text: '$ Costos por entidades',
          },
        },
        tooltip: {
          headerFormat:
            '<span style="font-size:10px">{point.key}</span><table>',
          pointFormat:
            '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true,
        },
        plotOptions: {
          column: {
            pointPadding: 0.2,
            borderWidth: 0,
          },
        },
        series: [
          {
            name: 'Total de costos de conformidad',
            data: [],
          },
          {
            name: 'Total de costos de no conformidad',
            data: [],
          },
          {
            name: 'Total de costos de calidad',
            data: [],
          },
          {
            name: 'Total de ventas netas',
            data: [],
          },
        ],
      },
    };
  },

  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Costo de calidad' },
      { label: 'Reportes' },
      { label: 'Filtrar costos por entidades' },
    ]);
    let timeStamp = Date.now();
    let formattedString = date.formatDate(timeStamp, 'YYYY-MM-DD');
    this.datePicker.from = formattedString;
    this.datePicker.to = formattedString;

    this.datePickerLast.from = formattedString;
    this.datePickerLast.to = formattedString;
    this.subtitle();
  },
  methods: {
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('costo_calidad', [
      'loadDataYear_report',
      'loadDataLastYear_report',
    ]),
    ...mapGetters('utils', ['getterLoading']),

    async aplicarFiltro() {
      this.isDisable = true;
      this.loadDataAnnoActual();
      this.loadDataAnnoAnterior();
    },
    limpiarFiltro() {
      this.isDisable = false;

      let timeStamp = Date.now();
      let formattedString = date.formatDate(timeStamp, 'YYYY-MM-DD');
      this.datePicker.from = formattedString;
      this.datePicker.to = formattedString;

      this.datePickerLast.from = formattedString;
      this.datePickerLast.to = formattedString;

      this.chartExample.series[0].data = [];
      this.chartExample.series[1].data = [];
      this.chartExample.series[2].data = [];
      this.chartExample.series[3].data = [];

      this.chartExample.categories = [];

      this.chartAnnoAnterior.series[0].data = [];
      this.chartAnnoAnterior.series[1].data = [];
      this.chartAnnoAnterior.series[2].data = [];
      this.chartAnnoAnterior.series[3].data = [];

      this.chartAnnoAnterior.categories = [];
      this.subtitle();
    },
    async loadDataAnnoActual() {
      this.subtitle();
      this.parametersDatePicker.fechaComienzo = this.datePicker.from;
      this.parametersDatePicker.fechaFin = this.datePicker.to;
      await this.loadDataYear_report(this.parametersDatePicker).then(
        (response) => {
          for (let entry of response.data) {
            this.chartExample.xAxis.categories.push(entry.nombre);

            this.chartExample.series[0].data.push(
              parseFloat(entry.total_costo_conformidad)
            );

            this.chartExample.series[1].data.push(
              parseFloat(entry.total_costo_noconformidad)
            );

            this.chartExample.series[2].data.push(
              parseFloat(entry.total_costo_calidad)
            );

            this.chartExample.series[3].data.push(
              parseFloat(entry.total_venta_neta)
            );
          }
        }
      );
    },
    async loadDataAnnoAnterior() {
      this.subtitle();
      this.parametersDatePickerLast.fechaComienzo = this.datePickerLast.from;
      this.parametersDatePickerLast.fechaFin = this.datePickerLast.to;
      await this.loadDataLastYear_report(this.parametersDatePickerLast).then(
        (response) => {
          for (let entry of response.data) {
            this.chartAnnoAnterior.xAxis.categories.push(entry.nombre);

            this.chartAnnoAnterior.series[0].data.push(
              parseFloat(entry.total_costo_conformidad)
            );

            this.chartAnnoAnterior.series[1].data.push(
              parseFloat(entry.total_costo_noconformidad)
            );

            this.chartAnnoAnterior.series[2].data.push(
              parseFloat(entry.total_costo_calidad)
            );

            this.chartAnnoAnterior.series[3].data.push(
              parseFloat(entry.total_venta_neta)
            );
          }
        }
      );
    },
    subtitle() {
      this.chartExample.subtitle.text =
        'Trimestre del año actual [' +
        this.datePicker.from +
        ' hasta ' +
        this.datePicker.to +
        ']';

      this.chartAnnoAnterior.subtitle.text =
        'Trimestre del año anterior [' +
        this.datePickerLast.from +
        ' hasta ' +
        this.datePickerLast.to +
        ']';
    },
  },
};
</script>
