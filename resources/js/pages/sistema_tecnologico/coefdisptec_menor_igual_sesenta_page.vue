<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Coeficientes de disponibilidad técnica
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section class="no-padding">
        <q-table
          class="q-px-md q-pb-md"
          table-header-class="bg-primary text-white"
          flat
          dense
          :data="data"
          :columns="columns"
          row-key="id"
          :filter="filter"
          :loading="this.getterLoading()"
          :rows-per-page-options="[10, 20, 30, 40, 50]"
          no-data-label="No se encontraron elementos a mostrar"
          no-results-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col">
                <q-input
                  dense
                  outlined
                  flat
                  v-model="filter"
                  debounce="1000"
                  placeholder="Buscar"
                />
              </div>
            </div>

            <div class="full-width row justify-center q-gutter-x-xs">
              <div class="col-sm-12 col-md-3 col-lg-3">
                <q-select
                  v-model="parameter.coeficiente"
                  :options="options"
                  label="Filtrar por el coeficiente"
                  options-dense
                  option-value="id"
                  option-label="name"
                  emit-value
                  map-options
                >
                  <template v-slot:append>
                    <q-icon name="mdi-filter" />
                  </template>
                </q-select>
              </div>
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
          </template>
        </q-table>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import axios from "axios";
import { mapState, mapGetters, mapActions } from "vuex";
import { errorMessage, successMessage } from "../../utils/notificaciones";
export default {
  name: "CoefdisptecMenorIgualSesentaPage",
  data() {
    return {
      model: null,
      parameterOld: { osde_id: null, instalacion_id: null },
      parameter: { osde_id: null, instalacion_id: null, coeficiente: null },
      isDisable: false,
      osdes: [],
      instalaciones: [],
      optionsOld: [
        { name: "Menor o igual que 60", id: 1 },
        { name: "Menor o igual que 95", id: 2 },
      ],
      options: [
        { name: "Menor o igual que 60", id: 60 },
        { name: "Menor o igual que 95", id: 95 },
      ],
      filterInput: false,
      loading: true,
      filter: "",
      data: [],
      pagination: {
        sortBy: "desc",
        descending: false,
        page: 0,
        rowsPerPage: 1,
        rowsNumber: 10,
      },
      columns: [
        {
          name: "instalacion",
          align: "left",
          label: "Instalacion",
          field: (row) => row.instalacion,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "sistema",
          align: "left",
          label: "Sistema",
          field: (row) => row.sistema,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        ,
        {
          name: "equipo",
          align: "left",
          label: "Equipo",
          field: (row) => row.equipo,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "indicador tres",
          align: "left",
          label: "Coeficiente disponibilidad técnica",
          field: (row) => row.coeficienteDispTecnica,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "creado",
          align: "left",
          label: "Creado",
          field: (row) => row.fechaReporte,
          sortable: true,
          headerClasses: "text-uppercase",
        },
      ],
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
      { label: "Coeficientes de disponibilidad técnica" },
    ]);
  },
  methods: {
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    ...mapGetters("utils", ["getterLoading"]),
    async loadData() {
      return await axios
        .get("/api/coefdisptec_menor_igual_sesenta")
        .then((response) => {
          this.data = response.data;
          this.loading = false;
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
    async aplicarFiltroOld() {
      if (this.model == 1) {
        return await axios
          .get("/api/coefdisptec_menor_igual_sesenta")
          .then((response) => {
            this.data = response.data;
            this.loading = false;
          })
          .catch((err) => {
            this.$q.notify({
              color: "negative",
              position: "top",
              message: "Carga fallida",
              icon: "report_problem",
            });
          });
      } else {
        return await axios
          .get("/api/coefdisptec_menor_igual_noventa_y_cinco")
          .then((response) => {
            this.data = response.data;
            this.loading = false;
          })
          .catch((err) => {
            this.$q.notify({
              color: "negative",
              position: "top",
              message: "Carga fallida",
              icon: "report_problem",
            });
          });
      }
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
          });
        });
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
    async aplicarFiltro() {
      this.isDisable = true;
      return await axios
        .post("/api/coefdisptec_general", this.parameter)
        .then((response) => {
          this.data = response.data;
          this.loading = false;
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
    limpiarFiltro() {
      this.isDisable = false;
      this.parameter.instalacion_id = null;
      this.parameter.osde_id = null;
      this.parameter.coeficiente = null
      this.data = []
    },

    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    onRequest(props) {
      const { page } = props.pagination;
      this.pagination.page = page;
      this.loadData();
    },
  },
};
</script>

<style scoped>
.my-toolbars {
  border-bottom: 0;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}
</style>
