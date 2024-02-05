<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Plan anual por indicador
          </div>
          <q-space></q-space>
          <div class="col-2 text-subtitle1 text-uppercase q-mr-xl">
            plan total
            <q-badge
              outline
              align="middle"
              color="primary"
              class="text-body1 text-bold"
            >
              {{ totalPlan }}
            </q-badge>
          </div>
          <div class="col-2 text-subtitle1 text-uppercase">
            real total
            <q-badge
              outline
              align="middle"
              color="primary"
              class="text-body1 text-bold"
            >
              {{ totalReal }}
            </q-badge>
          </div>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <!-- tabla-->
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white"
          flat
          :data="importacion"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          dense
          hide-pagination
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col-4">
                <q-select
                  dense
                  outlined
                  v-model="form_data.mes_start"
                  :options="meses"
                  options-dense
                  label="Mes inicial"
                  emit-value
                  map-options
                  options-cover
                  @input="searchInfo()"
                  style="min-width: 150px;"
                  :rules="[(val) => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-4 q-px-sm">
                <q-select
                  dense
                  outlined
                  v-model="form_data.mes_end"
                  :options="meses"
                  options-dense
                  label="Mes final"
                  emit-value
                  map-options
                  @input="searchInfo()"
                  options-cover
                  style="min-width: 150px;"
                  :rules="[(val) => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-4">
                <q-select
                  dense
                  outlined
                  v-model="form_data.indicador_id"
                  :options="indicador"
                  options-dense
                  label="Indicador"
                  option-value="id"
                  option-label="nombre"
                  emit-value
                  map-options
                  @input="searchInfo()"
                  options-cover
                  style="min-width: 150px;"
                  :rules="[(val) => !!val || 'Campo requerido']"
                />
              </div>
            </div>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="empresa" :props="props">
                {{ props.row.empresa.empresa }}
              </q-td>
              <q-td key="plan" :props="props">
                {{ props.row.plan }}
              </q-td>
              <q-td key="real" :props="props">
                {{ props.row.real }}
              </q-td>
              <q-td key="xciento" :props="props">
                {{ Math.round((props.row.real / props.row.plan) * 100) }} %
              </q-td>
            </q-tr>
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { getImportacionIndicadorMes } from '../../store/gestion_importacion/importaciones/actions';
export default {
  name: 'ReporteImportacionesIndicador',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      modalDialog: false,
      filterInput: false,
      loading: false,
      filter: '',
      meses: [
        {
          value: 1,
          label: 'Enero',
        },
        {
          value: 2,
          label: 'Febrero',
        },
        {
          value: 3,
          label: 'Marzo',
        },
        {
          value: 4,
          label: 'Abril',
        },
        {
          value: 5,
          label: 'Mayo',
        },
        {
          value: 6,
          label: 'Junio',
        },
        {
          value: 7,
          label: 'Julio',
        },
        {
          value: 8,
          label: 'Agosto',
        },
        {
          value: 9,
          label: 'Septiembre',
        },
        {
          value: 10,
          label: 'Octubre',
        },
        {
          value: 11,
          label: 'Noviembre',
        },
        {
          value: 12,
          label: 'Diciembre',
        },
      ],
      form_data: {
        mes_start: '',
        mes_end: '',
        indicador_id: '',
      },
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
        {
          name: 'empresa',
          align: 'left',
          label: 'emp. importadora',
          field: (row) => row.empresa && row.empresa.empresa,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'plan',
          align: 'left',
          label: 'plan',
          field: 'plan',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'real',
          align: 'left',
          label: 'real',
          field: 'real',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'xciento',
          align: 'left',
          label: '%',
          field: 'xciento',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
      ],
    };
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Logística' },
      { label: 'Importaciones' },
      { label: 'Reportes' },
      { label: 'Plan anual por indicador' },
    ]);
    this.getIndicador();
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
  },
  computed: {
    ...mapState('importacion', ['importacion']),
    ...mapState('indicador', ['indicador']),
    totalPlan() {
      let suma = 0;
      this.importacion.forEach((element) => {
        suma += element.plan;
      });
      return suma;
    },
    totalReal() {
      let suma = 0;
      this.importacion.forEach((element) => {
        suma += element.real;
      });
      return suma;
    },
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('importacion', ['getImportacionIndicadorMes']),
    ...mapActions('indicador', ['getIndicador']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    async loadData(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getImportacionIndicadorMes(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    searchInfo() {
      if (
        this.form_data.indicador_id &&
        this.form_data.mes_start &&
        this.form_data.mes_end
      )
        this.getImportacionIndicadorMes(this.form_data);
    },
    xciento(x, y) {
      return (x / y) * 100;
    },
  },
};
</script>

<style scoped></style>
