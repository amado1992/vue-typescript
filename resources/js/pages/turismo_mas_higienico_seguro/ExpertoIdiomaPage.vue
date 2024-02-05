<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="text-subtitle1 text-weight-bold text-uppercase">
            gestionar experto idioma
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
            s
            :color="filterInput ? 'blue-grey-3' : 'primary'"
            icon="search"
            @click.prevent="filterStatus()"
            class="q-mr-sm q-ml-sm q-mt-sm"
          >
            <q-tooltip>Buscar</q-tooltip>
          </q-btn>
          <q-btn
            v-if="scopes.includes('gestionar_ths')"
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
        </div>
      </q-card-section>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          :data="data"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          no-data-label="No se encontraron elementos a mostrar"
          dense
        >
          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <div class="q-gutter-xs">
                <q-btn
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="
                    openModalUpdate(true, 'actualizar', props.row)
                  "
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRow(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
                  <!--Menu ver mas proceso principal-->
                <q-btn size="sm" dense icon="more_vert" color="grey" flat>
                  <q-tooltip>Ver más</q-tooltip>
                  <q-menu>
                    <q-list dense style="min-width: 198px">
                      <q-item
                        clickable
                        v-ripple
                       @click.prevent="openIdiomaHabilidadEvaluacion(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Habilidad evaluación</q-item-section
                        >
                      </q-item>

                      <q-separator />
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalSee(true, 'see', props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Ver más datos</q-item-section
                        >
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
                <!--Fin menu ver mas proceso principal-->
              </div>
            </q-td>
          </template>

          <template v-slot:loading>
            <q-inner-loading showing color="primary" />
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
    <!-- Dialog -->
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
                      ? "Adicionar experto idioma"
                      : "Actualizar experto idioma"
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
              <div class="row q-gutter-y-md">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                <q-select
                    v-model="form.mtfichaexperto_id"
                    :options="expertos"
                    label="Expertos *"
                    options-dense
                    option-value="id"
                    option-label="nombreApellidos"
                    emit-value
                    map-options
                    :rules="[(val) => !!val || 'Por favor seleccione algo']"
                  >
                  </q-select>
                </div>

                 <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-select
                    v-model="form.mtidioma_id"
                    :options="idiomas"
                    label="Idiomas *"
                    options-dense
                    option-value="id"
                    option-label="nombre"
                    emit-value
                    map-options
                     :rules="[(val) => !!val || 'Por favor seleccione algo']"
                  >
                  </q-select>
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
    <ExpertoIdioma v-show="showForm" :itemSelected="form"/>
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
import ExpertoIdioma from "../../components/ExpertoIdioma";

export default {
  name: "ExpertoIdiomaPage",
  components: {
    ExpertoIdioma,
  },
  data() {
    return {
      scopes: sessionStorage.getItem("scopes"),
      modalDialog: false,
      filterInput: false,
       showForm: false,
       required: (val) => !!val || "Por favor, escriba algo",
      //loading: false,
      filter: "",
      accion: "",
     form: {
        //Tabla experto-idioma
        id: 0,
        mtidioma_id: null,
        mtfichaexperto_id: null
      },
      columns: [
        {
          name: "nombreApellidos",
          align: "left",
          label: "Nombre y apellidos",
          field: (row) => row.experto.nombreApellidos,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "ci",
          align: "left",
          label: "Carnet de identidad",
          field: (row) => row.experto.carnetIdentidad,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "idioma",
          align: "left",
          label: "Idioma",
          field: (row) => row.idioma.nombre,
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
      expertos: [],
      idiomas: [],
    };
  },
  created() {
    this.loadData()
    this.loadExpertos()
    this.loadIdiomas()
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Dirección de Calidad" },
      { label: "Turismo más higiénico y seguro" },
      { label: "Nomencladores" },
      { label: "Expertos idiomas" }
    ]);
  },
  watch: {},
  computed: {
    isComplete() {
      if (
        this.form.mtidioma_id &&
        this.form.mtfichaexperto_id
      ) {
        return false;
      }
      return true;
    },
  },
  methods: {
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
      this.form.id = 0;
      this.form.mtidioma_id = null
      this.form.mtfichaexperto_id = null
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
    async loadData() {
      this.setLoading(true);
      return await axios
        .get("/api/idiomas_expertos")
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
      const id = this.form.id;
      this.$q.loading.show();
      return await axios
        .delete("/api/idioma_experto/" + id)
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
          message: "¿Estás seguro que desea eliminar este experto idioma?",
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
     Object.assign(this.form, data);
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
        .post("/api/idioma_experto", this.form)
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
      let id = this.form.id;
      return await axios
        .put("/api/idioma_experto/" + id, this.form)
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
      async loadExpertos() {
      return await axios
        .get("/api/ficha_expertos")
        .then((response) => {
          this.expertos = response.data.data;
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
      async loadIdiomas() {
      return await axios
        .get("/api/idiomas")
        .then((response) => {
          this.idiomas = response.data.data;
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
     async openIdiomaHabilidadEvaluacion(dataRow) {
      Object.assign(this.form, dataRow);
      this.$root.$emit("openTable");
      this.showForm = true;
    },
  },
};
</script>

<style scoped>
</style>
