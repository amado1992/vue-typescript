<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          class="table-title"
          title="Gestionar estado de incidencia de grupo electrógeno"
          :grid="xssmallScreen || smallScreen"
          flat
          dense
          :data="data"
          :columns="columns"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          no-data-label="No se encontraron elementos a mostrar"
          :hide-pagination="false"
        >
          <!--<template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar categoría de instalación
            </div>
            <q-space></q-space>
            <q-input
              dense
              autofocus
              flat
              v-if="filterInput"
              v-model="filter"
              debounce="1000"
              placeholder="Buscar"
              class="q-ml-md"
            />
            <q-btn
              key="filter"
              dense
              :disable="categoria_instalacion.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('nomencladores')"
              type="reset"
              key="add"
              dense
              color="primary"
              icon="add"
              @click.prevent="openModal(true, 'adicionar')"
              class="q-mt-sm"
            >
              <q-tooltip>Nuevos datos</q-tooltip>
            </q-btn>
          </template>-->

 <template v-slot:top-right>
            <q-input
              dense
              autofocus
              flat
              v-model="filter"
              debounce="1000"
              placeholder="Buscar"
            >
             <template v-slot:append>
                <q-btn key="filter" dense :color="'primary'" icon="search">
                </q-btn>
              </template>
            </q-input>
            <q-btn
              v-if="scopes.includes('nomencladores')"
              type="reset"
              key="add"
              dense
              color="primary"
              icon="add"
              @click.prevent="openModal(true, 'adicionar')"
               class="q-mx-sm only-xs"
            >
              <q-tooltip>Nuevos datos</q-tooltip>
            </q-btn>
          </template>

          <template v-slot:item="props">
            <q-card class="q-ma-xs">
              <q-card-section>
                <q-btn
                  v-if="scopes.includes('nomencladores')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="openModalUpdate(true, 'actualizar', props.row)"
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('nomencladores')"
                  :disable="props.row.activo === '0'"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRow(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </q-card-section>
              <q-separator></q-separator>
              <q-list dense>
                <q-item :key="col.id" v-for="col in props.cols">
                  <q-item-section>
                    <q-item-label v-if="col.field != 'action'">{{
                      col.label
                    }}</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-item-label caption>{{ col.value }}</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card>
          </template>

          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <div class="q-gutter-xs">
                <q-btn
                  v-if="scopes.includes('nomencladores')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="openModalUpdate(true, 'actualizar', props.row)"
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('nomencladores')"
                  :disable="props.row.activo === '0'"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRow(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
          <template v-slot:body-cell-activo="props">
            <q-td key="activo" align="left">
              {{ props.row.activo === '1' ? 'Si' : 'No' }}
            </q-td>
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
    <!-- Dialog gestionar -->
    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-form
          @reset="reset_form"
          @submit="accion != 'adicionar' ? update() : save()"
          ref="myForm"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == "adicionar"
                      ? "Adicionar estado de incidencia del grupo electrógeno"
                      : "Actualizar estado de incidencia del grupo electrógeno"
                  }}
                </q-toolbar-title>
                <q-btn
                  round
                  dense
                  flat
                  icon="close"
                  @click="closeModal"
                  v-close-popup
                >
                  <q-tooltip>Cerrar</q-tooltip>
                </q-btn>
              </q-toolbar>
            </q-card-section>

            <q-card-section>
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 q-px-sm">
                  <q-input
                    ref="name"
                    v-model="form_create.nombre"
                    label="Nombre *"
                    lazy-rules
                    :rules="[
                      (val) =>
                        (val && val.length > 0) || 'Por favor escriba algo',
                    ]"
                  />
                </div>
              </div>
            </q-card-section>

            <q-card-actions align="right">
              <q-btn
                type="submit"
                key="guardar"
                label="Guardar"
                flat
                color="primary"
                :icon="accion === 'adicionar' ? 'save' : 'edit'"
                v-show="add"
                :disable="isComplete"
              >
              </q-btn>
              <q-btn
                type="button"
                icon="close"
                @click="closeModal()"
                label="Cancelar"
                v-close-popup
                color="negative"
                flat
              />
            </q-card-actions>
          </q-card-section>
        </q-form>
      </q-card>
    </q-dialog>
    <!-- End Dialog gestionar -->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { showDialog } from "../../utils/dialogo";
import { infoMessage } from "../../utils/notificaciones";
import * as message from "../../utils/resources";

import axios from "axios";
import { date } from "quasar";
import { errorMessage, successMessage } from "../../utils/notificaciones";
import SmallScreen from "../../mixin/SmallScreen";

export default {
  name: "EstadoIncGEPage",
  mixins:[SmallScreen],
  data() {
    return {
      scopes: sessionStorage.getItem("scopes"),

      //Computed
      a: 1,
      //counter: 0,
      clicks: 0,
      //End computed

      //Watch
      someText: "",
      //End watch

      modalDialog: false,
      filterInput: false,
      loading: false,
      filter: "",
      val: false,
      add: true,
      accion: "",
      form_create: {
        id: 0,
        nombre: "",
        nombre: ""
      },
      columns: [
        {
          name: "nombre",
          align: "left",
          label: "Nombre",
          field: (row) => row.nombre,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "actions",
          label: "Acciones",
          field: "",
          align: "center",
          headerClasses: "text-uppercase",
        },
      ],
      data: [],
    };
  },
  created() {
    this.loadData();
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Operaciones y calidad" },
      { label: "Servicios técnicos" },
      { label: "Nomencladores" },
      { label: "Estado de incidencia de grupo electrógeno" }
    ]);
  },
  watch: {
    someText() {
      //cuando el input someText cambia entoces se incrementa clicks
      this.clicks++;
    },
  },
  computed: {
    //Computed test
    counter() {
      //counter cambia cuando clicks que esta dentro del el cambia.
      return this.clicks * 2;
    },
    // get only
    aDouble: function () {
      return this.a * 2;
    },
    // both get and set
    aPlus: {
      get: function () {
        return this.a + 1;
      },
      set: function (v) {
        this.a = v - 1;
      },
    },
    //End computed test

    isComplete() {
      if (this.form_create.nombre) {
        return false;
      }
      return true;
    },
  },
  methods: {
    //Computed test
    increment() {
      this.clicks++;
    },

    incrementAplus() {
      //this.aPlus = 3;
      this.aPlus = 5;
    },
    //End computed test

    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("utils", ["setLoading"]),
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    reset_form() {
      this.form_create.id = 0;
      this.form_create.nombre = "";
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
    async loadData() {
      this.setLoading(true);
      return await axios
        .get("/api/estadosincidencias_ge")
        .then((result) => {
          this.data = result.data.data;
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
    async deleteRow(dataRow) {
      this.confirmDelete(dataRow);
    },
    async deleteRowRequest(dataRow) {
      this.setFormData(dataRow);
      const id = this.form_create.id;
      this.$q.loading.show();
      return await axios
        .delete("/api/estadoincidencia_ge/" + id)
        .then((response) => {
          this.reset_form();
          this.$q.loading.hide();
          this.loadData();
          successMessage("Los datos se eliminaron satisfactoriamente");
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    confirmDelete(dataRow) {
      this.$q
        .dialog({
          title: "Confirme",
          message: "¿Estás seguro que desea eliminar este estado de incidencia del grupo electrógeno?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRowRequest(dataRow);
        })
        .onCancel(() => {});
    },
    openModal(data, accion) {
      this.accion = accion;
      if (accion === "adicionar") {
        this.modalDialog = data;
      }
    },

    openModalUpdate(data, accion, dataRow) {
      this.accion = accion;

      if (accion === "actualizar") {
        this.setFormData(dataRow);
        this.modalDialog = data;
      }
    },
    setFormData(data) {
      this.form_create = Object.assign(this.form_create, data);
    },
    async save() {
      this.$refs.myForm.validate().then((success) => {
        if (success) {
          //valid value
        } else {
          //invalid value
        }
      });

      return await axios
        .post("/api/estadoincidencia_ge", this.form_create)
        .then((result) => {
          this.loadData();
          successMessage("Los datos se insertaron satisfactoriamente");
          this.closeModal();
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
    async update() {
      let id = this.form_create.id;
      return await axios
        .put("/api/estadoincidencia_ge/" + id, this.form_create)
        .then((result) => {
          this.loadData();
          successMessage("Los datos se actualizaron satisfactoriamente");
          this.closeModal();
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
  },
};
</script>

<style scoped>
.table-title >>> .q-table__title {
  font-size: 1rem;
  letter-spacing: 0.005em;
  font-weight: 700;
  text-transform: uppercase;
}

.only-xs {
  margin-top: 0px;
}
@media screen and (max-width: 387px) {
  .only-xs {
    margin-top: 4px;
  }
}
</style>
