<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="text-subtitle1 text-weight-bold text-uppercase">
            gestionar equipo
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
            :disable="data.length != 0 ? false : true"
            :color="filterInput ? 'blue-grey-3' : 'primary'"
            icon="search"
            @click.prevent="filterStatus()"
            class="q-mr-sm q-ml-sm q-mt-sm"
          >
            <q-tooltip>Buscar</q-tooltip>
          </q-btn>
          <q-btn
            v-if="scopes.includes('gestion_sistemas_tec')"
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
                  v-if="scopes.includes('gestion_sistemas_tec')"
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
                  v-if="scopes.includes('gestion_sistemas_tec')"
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
                      ? "Adicionar equipo"
                      : "Actualizar equipo"
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
              <div class="row q-gutter-y-xs">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <q-input
                    label="Descripción *"
                    v-model="form_create.descripcion"
                    type="textarea"
                    lazy-rules
                    :rules="[
                      (val) =>
                        (val && val.length > 0) || 'Por favor escriba algo',
                    ]"
                  />
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <q-select
                    v-model="form_create.sistema_id"
                    :options="sistemas"
                    label="Sistemas *"
                    options-dense
                    option-value="id"
                    option-label="nombre"
                    emit-value
                    map-options
                    :rules="[(val) => !!val || 'Por favor seleccione algo']"
                  >
                    <template v-slot:append>
                      <q-icon name="mdi-desktop-classic" />
                    </template>
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

export default {
  name: "EquipoPage",
  data() {
    return {
      scopes: sessionStorage.getItem("scopes"),
      modalDialog: false,
      filterInput: false,
      //loading: false,
      filter: "",
      val: false,
      add: true,
      accion: "",
      form_create: {
        id: 0,
        nombre: "",
        descripcion: "",
        sistema_id: null,
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
          name: "descripcion",
          align: "left",
          label: "Descripción",
          field: (row) => row.descripcion,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "sistema",
          align: "left",
          label: "Sistema",
          field: (row) => row.sistema.nombre,
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
      sistemas: [],
    };
  },
  created() {
    this.loadData();
    this.loadDataSistemas();
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Dirección de Servicios Técnicos" },
      { label: "Disponibilidad de los sitemas tecnológicos" },
      { label: "Nomencladores" },
      { label: "Equipo" },
    ]);
  },
  watch: {},
  computed: {
    isComplete() {
      // isComplete cambia cuando form_create.nombre o form_create.descripcion cambian, los cuales estan dentro de el .
      if (
        this.form_create.nombre &&
        this.form_create.descripcion &&
        this.form_create.sistema_id
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
      this.form_create.id = 0;
      this.form_create.nombre = "";
      this.form_create.descripcion = "";
      this.form_create.sistema_id = null;
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
    async loadData() {
      this.setLoading(true);
      return await axios
        .get("/api/equipos")
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
        .delete("/api/equipo/" + id)
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
          message: "¿Estás seguro que desea eliminar este equipo?",
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
        .post("/api/equipo", this.form_create)
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
        .put("/api/equipo/" + id, this.form_create)
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
    async loadDataSistemas() {
      return await axios
        .get("/api/sistemas")
        .then((result) => {
          this.sistemas = result.data.data;
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
          console.log(err);
        });
    },
  },
};
</script>

<style scoped>
</style>
