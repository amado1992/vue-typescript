<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Plan anual por empresa
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
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          size="xs"
          dense
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col-5">
                <q-select
                  v-model="form_data.empresa_id"
                  clearable
                  outlined
                  transition-show="jump-up"
                  transition-hide="jump-up"
                  dense
                  options-dense
                  emit-value
                  map-options
                  :options="items_empresa"
                  label="Empresa importadora *"
                  option-label="empresa"
                  option-value="id"
                  options-cover
                  :rules="[(val) => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-3 q-px-xs">
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
                  :rules="[(val) => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-3">
                <q-select
                  dense
                  outlined
                  v-model="form_data.mes_end"
                  :options="meses"
                  options-dense
                  label="Mes final"
                  emit-value
                  map-options
                  options-cover
                  :disable="!form_data.mes_start"
                  :rules="[(val) => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-1 q-px-xs">
                <q-btn
                  icon="mdi-magnify"
                  dense
                  color="primary"
                  @click.prevent="searchInfo()"
                  :disable="!form_data.mes_end"
                />
                <q-tooltip>Buscar</q-tooltip>
              </div>
            </div>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="empresa_id" :props="props">
                {{ props.row.empresa.empresa }}
              </q-td>
              <q-td key="plan" :props="props">
                {{ props.row.plan }}
              </q-td>
              <q-td key="real" :props="props">
                {{ props.row.real }}
              </q-td>
              <q-td key="xciento" :props="props">
                {{ Math.round((props.row.real * 100) / props.row.plan) }} %
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
import { warningMessage } from '../../utils/notificaciones';

export default {
  name: 'ReporteImportacionesEmpresa',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      modalDialog: false,
      filterInput: false,
      loading: false,
      filter: '',
      items_empresa: [],
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
        empresa_id: '',
      },
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
        {
          name: 'empresa_id',
          align: 'left',
          label: 'emp. importadora',
          field: (row) => row.empresa && row.empresa.nombre,
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
      { label: 'Plan anual por empresa' },
    ]);
    this.getEmpresaImportadora().then((r) => {
      this.items_empresa = r;
    });
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
    ...mapState('empresa', ['empresa']),
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
    ...mapActions('importacion', ['getImportacionEmpresaMes']),
    ...mapActions('empresa', ['getEmpresaImportadora']),
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
      await this.getImportacionEmpresaMes(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    searchInfo() {
      if (this.form_data.empresa_id){
        if (this.form_data.mes_start < this.form_data.mes_end){
        this.getImportacionEmpresaMes(this.form_data);
        }else
          return warningMessage('Verifique los meses')
      }else
        return warningMessage('Faltan datos por insertar')
    },
    xciento(x, y) {
      return (x / y) * 100;
    },
  },
};
</script>

<style scoped></style>
