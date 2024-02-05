<template>
  <div class="q-pa-md">
    <q-toolbar class="my-toolbars bg-primary text-white">
      <transition-group
        appear
        enter-active-class="animated fadeIn"
        leave-active-class="animated fadeOut"
      >
       <!-- <q-btn
          v-if="scopes.includes('write_entidades')"
          type="reset"
          key="add"
          label="Nuevo"
          flat
          color="white"
          icon="add"
          :disable="selected.length === 0 ? false : true"
          @click.prevent="openModal(true, 'adicionar')"
        >
          <q-tooltip>Nuevos datos</q-tooltip>
        </q-btn>
        -->
        <q-btn
          v-if="scopes.includes('write_entidades')"
          type="reset"
          key="add"
          label="Nuevo"
          flat
          color="white"
          icon="add"
          @click.prevent="openModal(true, 'adicionar')"
        >
          <q-tooltip>Nuevos datos</q-tooltip>
        </q-btn>
        <!--<q-btn
          v-if="scopes.includes('update_entidades')"
          type="reset"
          key="updated"
          label="Editar"
          flat
          color="white"
          icon="edit"
          @click.prevent="openModal(true, 'actualizar')"
          :disable="selected.length !== 0 ? false : true"
        >
          <q-tooltip>Editar datos</q-tooltip>
        </q-btn>

        <q-btn
          v-if="scopes.includes('delete_entidades')"
          type="reset"
          key="delete"
          label="eliminar"
          flat
          color="white"
          icon="delete"
          @click.prevent="deleteData()"
          :disable="selected.length !== 0 ? false : true"
        >
          <q-tooltip>Eliminar datos</q-tooltip>
        </q-btn>
        -->

        <q-btn
          v-if="scopes.includes('update_entidades')"
          type="reset"
          key="updated"
          label="T+HS"
          flat
          color="white"
          icon="mdi-medical-bag"
          to="/turismo_mas_higienico_seguro"
        >
          <q-tooltip>Ver todos los procesos T+HS</q-tooltip>
        </q-btn>

      </transition-group>
      <q-space></q-space>
      <q-input
        dark
        dense
        standout
        autofocus
        v-if="filterInput"
        v-model="filter"
        debounce="1000"
        placeholder="Buscar"
        class="q-ml-md"
      ></q-input>
      <q-btn
        key="filter"
        flat
        :color="filterInput ? 'blue-grey-3' : ''"
        icon="search"
        @click.prevent="filterStatus()"
      >
        <q-tooltip>Buscar</q-tooltip>
      </q-btn>
    </q-toolbar>
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <!-- <q-table
          flat
          :data="data"
          :columns="columns"
          row-key="id"
          :selection="this.typeSelection()"
          :selected.sync="selected"
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          @row-click="onRowClick"
          size="xs"
          dense
        >-->
        <q-table
          flat
          :data="data"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          @row-click="onRowClick"
          size="xs"
          dense
        >

         <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <q-btn
                dense
                round
                flat
                color="blue"
                @click="openModalUpdate(true, 'actualizar', props.row)"
                icon="edit"
              >
                <q-tooltip>Editar datos</q-tooltip>
              </q-btn>

              <q-btn
                dense
                round
                flat
                color="red"
                @click="deleteRow(props.row)"
                icon="delete"
              >
                <q-tooltip>Eliminar datos</q-tooltip>
              </q-btn>
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
      <q-card style="width: 500px; max-width: 700vw">
        <q-form
          @reset="reset_form"
          @submit="accion != 'adicionar' ? update() : save()"
          ref="formRenglon"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == "adicionar"
                      ? "Adicionar expediente"
                      : "Actualizar expediente"
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
              <div class="q-pa-md">
                <div class="row">
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    <q-select
                      v-model="form_create.proceso_id"
                      :options="procesos"
                      label="Proceso T+HS"
                      options-dense
                      option-value="id"
                      option-label="numeroRegistro"
                      emit-value
                      map-options
                      :rules="[(val) => !!val || 'Campo requerido']"
                    />
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-12 q-mt-md q-mt-sm">
                    <q-uploader
                      :factory="uploadFiles"
                      url="http://localhost:4444/upload"
                      label="Cargar documento"
                      multiple
                      style="max-width: 300px"
                    />
                  </div>
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
  name: "GestionarTHS",
  data() {
    return {
      scopes: sessionStorage.getItem("scopes"),
      modalDialog: false,
      filterInput: false,
      loading: false,
      filter: "",
      selected: [],
      val: false,
      add: true,
      accion: "",
      form_data: {
        nombre: "",
      },
      autodiagnostico: null,
      capacitacionInicial: null,
      form_create: {
        id: 0,
        nombreElemento: "",
        urlElemento: "",
        proceso_id: null,
      },
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
         {
          name: "numeroRegistro",
          align: "left",
          label: "proceso T+HS",
          field: (row) =>
            row.proceso_turismo_mas_higienico_seguro.numeroRegistro,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "instalacion",
          align: "left",
          label: "instalación",
          field: (row) =>
            row.proceso_turismo_mas_higienico_seguro.instalacion.nombre,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "provincia",
          align: "left",
          label: "provincia",
          field: (row) =>
            row.proceso_turismo_mas_higienico_seguro.instalacion.municipios
              .provincia.nombre,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "elemeto",
          align: "left",
          label: "elemento del expediente",
          field: (row) => row.nombreElemento,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        /*{
          name: "url",
          align: "left",
          label: "url",
          field: (row) => row.urlElemento,
          sortable: true,
          headerClasses: "text-uppercase",
        }*/
        { name: "actions", label: "Acciones", field: "", align: "center" }
      ],
      step: 1,
      procesos: [],
      data: [],
      today: new Date(),
    };
  },
  created() {
    this.loadData2();
    this.loadDataProcesos();
  },
  mounted() {
    this.addToHomeBreadcrumbs([{ label: "Expedientes del proceso" }]);
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
    ...mapState("renglon", ["renglon"]),
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("renglon", [
      "getRenglon",
      "saveRenglon",
      "editRenglon",
      "deleteRenglon",
    ]),
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
      //Registro
      this.form_create.proceso_id = null
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
      this.selected = [];
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
      /* await this.getRenglon(data).then(r => {
                this.pagination.rowsNumber = r.total
                this.pagination.page = page
                this.pagination.rowsPerPage = rowsPerPage
                this.pagination.sortBy = sortBy
                this.pagination.descending = descending
                this.setLoading(false)
            })*/

      return await axios
        .get("/api/procesos_turismo_mas_higienico_seguro")
        .then((result) => {
          this.data = result.data.data;
          this.pagination.rowsNumber = result.data.meta.total;
          this.pagination.page = page;
          this.pagination.rowsPerPage = rowsPerPage;
          this.pagination.sortBy = sortBy;
          this.pagination.descending = descending;
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
    async loadData2() {
      this.setLoading(true);
      return await axios
        .get("/api/expedientes")
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
    async loadDataProcesos() {
      return await axios
        .get("/api/procesos_turismo_mas_higienico_seguro")
        .then((result) => {
          this.procesos = result.data.data;
          console.log("PPOC", this.procesos);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    typeSelection() {
      return this.scopes.includes("update_renglones") ||
        this.scopes.includes("delete_renglones")
        ? "single"
        : "none";
    },
    filterFn(val, update) {
      update(() => {
        const needle = val.toLowerCase();
        this.options = this.permissions.filter(
          (v) => v.name.toLowerCase().indexOf(needle) > -1
        );
      });
    },
    onRowClick(evt, row) {
      if (this.selected.length === 0) {
        this.selected.push(row);
      } else if (
        this.selected.length === 1 &&
        this.selected[0].nombre !== row.nombre
      ) {
        this.selected = [];
        this.selected.push(row);
      } else if (
        this.selected.length === 1 &&
        this.selected[0].nombre === row.nombre
      ) {
        this.selected = [];
      }
    },
   /* openModal(data, accion) {
      this.accion = accion;
      if (accion === "adicionar") {
        this.modalDialog = data;
      } else {
        if (accion === "actualizar") {
          if (this.selected.length > 0) {
            this.setFormData(this.selected);
            this.modalDialog = data;
          } else {
            infoMessage("Debe seleccionar una fila para modificar.");
          }
        }
      }
    },*/
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
     async deleteRow(dataRow) {
      this.confirmDelete(dataRow);
    },
    async deleteRowRequest(dataRow) {
      this.setFormData(dataRow);
      const id = this.form_create.id;
      
      
       console.log("eee", id)
      this.$q.loading.show();
      return await axios
        .delete("/api/expediente/" + id)
        .then((response) => {
          this.$q.loading.hide();
          this.loadData2();
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
          message: "¿Estás seguro de eliminar este documento?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRowRequest(dataRow);
        })
        .onCancel(() => {});
    },
    setFormData(data) {
      //Antes con select en el q-table
      //this.form_data = Object.assign(this.form_data, data[0]);

     //Ahora sin el select, solo con las acciones en una columna
      this.form_create = Object.assign(this.form_create, data);
    },
    async save2() {
      /*            this.$v.form_data.$touch();
                        if (this.$v.form_data.$invalid) {
                            infoMessage("Revise los campos inválidos");
                            if (this.required_error) {
                                this.form_error = true;
                            }
                        } else {*/
      this.setLoading(true);
      await this.saveRenglon(this.form_data);
      this.reset_form();
      this.closeModal();
      await this.loadData({
        pagination: this.pagination,
        filter: this.filter,
      });
      this.setLoading(false);
      /*}*/
    },
    async save() {
     /* return await axios
        .post("/api/proceso_turismo_mas_higienico_seguro", this.form_create)
        .then((result) => {
          this.loadData2();
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
        });*/
    },
    async update() {
      let id = this.form_create.id;

      if (this.autodiagnostico == "Yes") {
        this.form_create.autodiagnostico = true;
      }
      if (this.autodiagnostico == "No") {
        this.form_create.autodiagnostico = false;
      }

      if (this.capacitacionInicial == "Yes") {
        this.form_create.capacitacionInicial = true;
      }
      if (this.capacitacionInicial == "No") {
        this.form_create.capacitacionInicial = false;
      }

      return await axios
        .put(
          "/api/proceso_turismo_mas_higienico_seguro/" + id,
          this.form_create
        )
        .then((result) => {
          this.loadData2();
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
    async uploadFiles(file) {
      //this.uploadPercentage = true
      let files = file;

      const found = this.procesos.find(
        (element) => element.id == this.form_create.proceso_id
      );

      const data = new FormData();
      for (var i = 0; i < files.length; i++) {
        let file = files[i];
        data.append(`files[${i}]`, file);
      }
      data.append("proceso_id", this.form_create.proceso_id);
      data.append("exp", found.numeroRegistro);

      return await axios
        .post("/api/expediente/", data, {
          headers: { "content-type": "multipart/form-data" },
          processData: false,
          contentType: false,
        })
        .then((result) => {
          //this.uploadPercentage = false
          this.loadData2()
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
    async update2() {
      /*            this.$v.form_data.$touch();
                        if (this.$v.form_data.$invalid) {
                            infoMessage("Revise los campos inválidos");
                            if (this.required_error) {
                                this.form_error = true;
                            }
                        } else {*/
      this.setLoading(true);
      await this.editRenglon(this.form_data);
      this.reset_form();
      this.closeModal();
      await this.loadData({
        pagination: this.pagination,
        filter: this.filter,
      });
      this.setLoading(false);
      /* }*/
    },
    async deleteData() {
      this.confirm();
    },
    async deleteRequest() {
      if (this.selected.length > 0) {
        this.setFormData(this.selected);
        const id = this.form_create.id;
        this.$q.loading.show();
        return await axios
          .delete("/api/proceso_turismo_mas_higienico_seguro/" + id)
          .then((response) => {
            this.$q.loading.hide();
            this.loadData2();
            successMessage("Los datos se eliminaron satisfactoriamente");
          })
          .catch((error) => {
            console.log(error.response);
          });
      } else {
        errorMessage("Debe seleccionar la fila a eliminar.");
      }
    },
    confirm() {
      this.$q
        .dialog({
          title: "Confirme",
          message: "Está seguro de eliminar el proceso?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRequest();
        })
        .onCancel(() => {});
    },
    deleteRenglones() {
      if (this.selected.length > 0) {
        this.setFormData(this.selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteRenglon(this.form_data.id);
          this.reset_form();
          this.selected = [];
          await this.loadData({
            pagination: this.pagination,
            filter: this.filter,
          });
          this.setLoading(false);
        });
      } else {
        infoMessage("Debe seleccionar la fila a eliminar.");
      }
    },
  },
};
</script>

<style scoped>

</style>
