<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Registro base T+HS
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
          <template v-slot:loading>
            <q-inner-loading showing color="primary" />
          </template>
          <template v-slot:body-cell-esContratadoMantenimiento="props">
            <q-td :props="props">
              <p v-if="props.esContratadoMantenimiento">Si</p>
              <p v-else>No</p>
            </q-td>
          </template>
          <template v-slot:top>
           <!-- <div class="full-width row justify-between">
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
            </div>-->

            
            <div class="full-width row justify-center q-gutter-x-xs">
               <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <q-input
                  dense
                  outlined
                  flat
                  v-model="filter"
                  debounce="1000"
                  placeholder="Buscar"
                />
              </div>
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <q-select
                  outline
                  v-model="parameter.municipio_id"
                  :options="municipios"
                  label="Municipios"
                  options-dense
                  option-value="id"
                  option-label="nombre"
                  emit-value
                  map-options
                />
              </div>

              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <q-select
                  outline
                  v-model="parameter.oace_id"
                  :options="oaces"
                  label="Oaces"
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
import { date } from 'quasar'

export default {
  name: "ReportDosRegistroBase",
  data() {
    return {
      filterInput: false,
      //loading: true,
      filter: "",
      data: [],
      isDisable: false,
      parameter: { municipio_id: null},
      osdes: [],
      municipios: [],
      oaces: [],
      instalaciones: [],
      pagination: {
        sortBy: "desc",
        descending: false,
        page: 0,
        rowsPerPage: 1,
        rowsNumber: 10,
      },
      columns: [
        {
          name: "numeroRegistro",
          align: "left",
          label: "Número de registro",
          field: (row) => row.numeroRegistro,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "fechaEntregaMintur",
          align: "left",
          label: "Entrega al mintur",
          field: 'fechaEntregaMintur',
          sortable: true,
          format: (val, row) => date.formatDate(val, 'DD-MMM-YYYY'),
          headerClasses: "text-uppercase",
        },
        /*{
          name: "Entidad",
          align: "left",
          label: "entidad",
          field: (row) => row.entidad,
          sortable: true,
          headerClasses: "text-uppercase",
        },*/
        {
          name: "instalacion",
          align: "left",
          label: "instalacion",
          field: (row) => row.instalacion,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        /* {
          name: "phone",
          align: "left",
          label: "Teléfono",
          field: (row) => row.phone,
          sortable: true,
          headerClasses: "text-uppercase",
        },
         {
          name: "email",
          align: "left",
          label: "Correo electrónico",
          field: (row) => row.email,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "annosExperienciaSectorLaboral",
          align: "left",
          label: "Experiencia laboral",
          field: (row) => row.annosExperienciaSectorLaboral,
          sortable: true,
          headerClasses: "text-uppercase",
        },
         {
          name: "cargoLaboral",
          align: "left",
          label: "Cargo laboral",
          field: (row) => row.cargoLaboral,
          sortable: true,
          headerClasses: "text-uppercase",
        },
          {
          name: "tituloAcademico",
          align: "left",
          label: "Título académico",
          field: (row) => row.tituloAcademico,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "catc",
          align: "left",
          label: "Categoría docente/Científia",
          field: (row) => row.catc,
          sortable: true,
          headerClasses: "text-uppercase",
        }*/
      ],
    };
  },
  created() {
    this.loadInstalaciones()
    this.loadOsdes()
    this.loadMunicipios()
    this.loadOaces()
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Dirección de Calidad" },
      { label: "Turismo más higiénico y seguro" },
      { label: "Reportes" },
      { label: "Registro base T+HS" }
    ]);

    this.onRequest({
      pagination: this.pagination,
      filter: undefined,
    });
  },
  methods: {
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("utils", ["setLoading"]),
    async loadData() {
      this.setLoading(true);
      return await axios
        .get("/api/no_contrado_en_mantenimiento")
        .then((response) => {
          this.data = response.data;
          //this.loading = false
          this.setLoading(false);
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
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
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
    async loadMunicipios() {
      return await axios
        .get("/api/municipios")
        .then((response) => {
          this.municipios = response.data.data;
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
     async loadOaces() {
      this.setLoading(true);
      return await axios
        .get("/api/oaces")
        .then((result) => {
          this.oaces = result.data.data;
          this.setLoading(false);
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
     let id = this.parameter.municipio_id
      return await axios
        .get("/api/report_register_base/" + id)
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
      this.parameter.municipio_id = null;
      this.data = []
    },
    onRequest(props) {
      const { page } = props.pagination;
      this.pagination.page = page;
      //this.loadData();
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
