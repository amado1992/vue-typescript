<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Habitaciones no disponibles
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <q-table
          flat
          dense
          table-header-class="bg-primary text-white text-center text-uppercase"
          id="disp"
          :data="data"
          @request="loadData"
          :columns="columns"
          :loading="loading"
          :rows-per-page-options="[10, 20]"
          no-data-label="No se encontraron elementos a mostrar"
          no-results-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col-12">
                <q-select
                  class="col-2"
                  id="disp"
                  label="Entidades"
                  v-model="form_data.entidades"
                  dense
                  outlined
                  input-debounce="0"
                  :options="options"
                  options-dense
                  option-label="nombre"
                  option-value="id"
                >
                </q-select>
              </div>
            </div>
          </template>
          <template v-slot:header="props">
            <q-tr v-for="(row, index) in headerMaker" :key="index">
              <q-th
                :auto-width="cell.colspan === '0'"
                v-for="(cell, cellCount) in row"
                :key="cellCount"
                :colspan="cell.colspan"
                :rowspan="cell.rowspan"
                align="left"
                class="bg-primary text-white text-bold"
              >
                {{ cell.label }}
              </q-th>
            </q-tr>
            <q-tr :props="props" class="bg-primary">
              <q-th v-for="col in props.cols" :key="col.name" :props="props">
                <span class="text-bold text-white">{{ col.label }}</span>
              </q-th>
            </q-tr>
          </template>
        </q-table>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import Vue from 'vue';
export default {
  name: 'HabNoDisp',
  data() {
    return {
      data: [],
      options: [],
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      form_data: {
        entidades: '',
      },
      loading: false,
      headerMaker: [
        [
          {
            label: 'Clasificacion',
            colspan: '1',
            rowspan: '2',
          },
          {
            label: 'Cant. Hab. fuera de orden por:',
            colspan: '3',
          },
        ],
        [
          {
            label: 'Mtto. imprevisto',
            colspan: '1',
          },
          {
            label: 'Reparación Capital ',
            colspan: '1',
          },
          {
            label: 'Reposición',
            colspan: '1',
          },
        ],
      ],
      columns: [
        {
          name: 'clasificacion_id',
          align: 'left',
          label: '1',
          field: (row) => row.nombre,
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'mtto',
          align: 'left',
          label: '2',
          field: (row) => row.mtto,
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'reparacion',
          align: 'left',
          label: '3',
          field: (row) => row.reparacion,
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
        {
          name: 'reposicion',
          align: 'left',
          label: '4',
          field: (row) => row.reposicion,
          sortable: true,
          headerClasses: 'text-uppercase bg-primary text-white',
        },
      ],
    };
  },
  computed: {
    ...mapGetters('entidad', ['getterEntidad']),
  },
  watch: {
    pagination: {
      handler() {
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      },
    },

    'form_data.entidades': function () {
      if (this.form_data.entidades !== '') this.loadData();
    },
  },
  mounted() {
    this.loadEntidades();
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Servicios Técnicos' },
      { label: 'Disponibilidad de habitaciones' },
      { label: 'Reportes' },
      { label: 'Habitaciones no disponibles' },
    ]);
  },
  methods: {
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('entidad', ['getEntidad']),
    ...mapActions('disponibilidad', ['getHabNoDisp']),

    async loadEntidades() {
      this.setLoading(true);
      await this.getEntidad(this.form_data);
      this.options = this.getterEntidad;
      this.setLoading(false);
    },

    async loadData() {
      await this.getHabNoDisp(this.form_data).then((r) => {
        this.data = r;
        this.setLoading(false);
      });
    },
  },
};
</script>

<style>
#disp .q-table tr:last-child {
  font-weight: bold !important;
}
</style>
