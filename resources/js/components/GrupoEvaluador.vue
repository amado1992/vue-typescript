<template>
  <div class="q-ma-md">
    <q-dialog
      v-model="modalDialogTable"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 800px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>Grupo evaluador</q-toolbar-title>

            <q-btn
              round
              dense
              flat
              icon="close"
              v-close-popup
              @click.prevent="closeModal()"
            >
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>

        <q-card-section>
          <div class="row">
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              gestionar grupo evaludor
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
              dense
              key="add"
              color="primary"
              icon="add"
              @click.prevent="openModal(true, 'add')"
              class="q-mt-sm"
            >
              <q-tooltip>Nuevos datos</q-tooltip>
            </q-btn>
          </div>
        </q-card-section>

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
            binary-state-sort
            no-data-label="No se encontraron elementos a mostrar"
            size="xs"
            dense
          >
            <template v-slot:body-cell-actions="props">
              <q-td :props="props">
                <div class="q-gutter-xs">
                  <q-btn
                    flat
                    key="see"
                    dense
                    size="sm"
                    text-color="primary"
                    icon="remove_red_eye"
                    @click.prevent="openModalSee(true, props.row)"
                  >
                    <q-tooltip>Ver detalles</q-tooltip>
                  </q-btn>
                  <q-btn
                    v-if="scopes.includes('gestionar_ths')"
                    flat
                    dense
                    size="sm"
                    text-color="primary"
                    icon="edit"
                    @click.prevent="openModalUpdate(true, 'update', props.row)"
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
                </div>
              </q-td>
            </template>

            <template v-slot:loading>
              <q-inner-loading showing color="primary" />
            </template>
          </q-table>
        </q-card-section>
        <!-- /tabla-->
      </q-card>
    </q-dialog>

    <!--Dialog form habilidad evaluacion-->
    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-form
          @reset="reset_form"
          @submit="accion != 'add' ? update() : save()"
          ref="form"
        >
          <q-card-section class="row no-padding">
            <q-toolbar class="bg-primary text-white">
              <q-toolbar-title>
                {{
                  this.accion == "add"
                    ? "Adicionar experto"
                    : "Actualizar experto"
                }}
              </q-toolbar-title>
              <q-btn
                round
                dense
                flat
                icon="close"
                @click="closeModal()"
                v-close-popup
              >
                <q-tooltip>Cerrar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </q-card-section>

          <q-card-section class="no-padding">
            <q-card-section>
              <div class="row q-gutter-y-md">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    v-model="numeroRegistro"
                    :dense="dense"
                    readonly
                    label="Número de registro"
                  />
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    v-model="instalacion"
                    :dense="dense"
                    readonly
                    label="Instalación"
                  />
                </div>
              
                <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
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

                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 q-pa-md">
                  <strong>Es jefe</strong>
                  <q-radio v-model="esJefe" val="si" label="Si" />

                  <q-radio v-model="esJefe" val="no" label="No" />
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <strong>Participa en:</strong>
                  <q-checkbox
                    left-label
                    dense
                    v-model="form.evalUno"
                    label="Primera evaluación"
                    color="teal"
                  />

                  <q-checkbox
                    left-label
                    dense
                    v-model="form.evalDos"
                    label="Segunda evaluación"
                    color="teal"
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
                :icon="accion === 'add' ? 'save' : 'edit'"
              >
              </q-btn>
              <q-btn
                type="button"
                icon="close"
                @click.prevent="closeModal()"
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
    <!--Dialog form habilidad evaluacion-->
    <!--Ver mas datos del habilidad evaluacion-->
    <q-dialog
      v-model="modalDialogSee"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>Datos</q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal()" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>

        <q-card-section>
          <div class="q-pl-md q-pb-md">
            <div class="column" style="height: 150px">
              <div class="col">Mes: {{ form.fechaSeguimientoMensual }}</div>
              <div class="col">
                Semestre: {{ form.fechaSeguimientoSemestral }}
              </div>
              <div class="col">
                Suspensión temporal del certificado:
                {{ form.fechaSuspensionTemporalCertificado }}
              </div>
              <div class="col">
                Retiro de la suspensión temporal del certificado:
                {{ form.fechaRetiroSuspensionTemporalCertificado }}
              </div>
              <div class="col">
                Cancelación de la certificación:
                {{ form.fechaCanceladoCertificado }}
              </div>
              <div class="col">
                Requisito incumplido: {{ form.requisitoIncumplido }}
              </div>
            </div>
          </div>
        </q-card-section>
        <q-separator inset />
        <q-card-actions align="right">
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
      </q-card>
    </q-dialog>
    <!--Fin del ver mas datos del habilidad evaluacion-->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { showDialog } from "../utils/dialogo";
import { infoMessage } from "../utils/notificaciones";
import * as message from "../utils/resources";
import axios from "axios";
import { date } from "quasar";
import { errorMessage, successMessage } from "../utils/notificaciones";

export default {
  name: "GrupoEvaluador",
  props: {
    itemSelected: Object,
  },
  data() {
    return {
      scopes: sessionStorage.getItem("scopes"),
      nombreApellidos: "",
      idioma: "",
      instalacion: "",
      numeroRegistro: "",
      filterInput: false,
      loading: false,
      filter: "",
      data: [],
      expertos: [],
      idiomas: [],
      habilidades: [],
      evaluaciones: [],
      dense: false,
      modalDialog: false,
      modalDialogTable: false,
      modalDialogSee: false,
      accion: "",
      form: {
        id: 0,
        mtfichaexperto_id: null,
        proceso_id: null,
        esJefe: false,
        evalUno: true,
        evalDos: false,
      },
      esJefe: "",
      eval: "",
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
          name: "carnetIdentidad",
          align: "left",
          label: "Carnet de identidad",
          field: (row) => row.experto.carnetIdentidad,
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
      required: (val) => !!val || "Por favor, escriba algo",
    };
  },
  created() {},
  mounted() {
    this.$root.$on("openTableGV", (data) => {
      this.modalDialogTable = true;
      this.loadData();
      this.loadExpertos();
      this.loadIdiomas();
      this.loadHabilidades();
      this.loadEvaluaciones();
    });
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    closeModal() {
      this.$root.$emit("closeTableGV");
      this.reset_form();
      this.modalDialog = false;
    },
    reset_form() {
      this.esJefe = "";
      this.form.id = 0;
      this.form.mtfichaexperto_id = null;
      this.form.evalUno = true
      this.form.evalDos = false
    },
    setFormData(data) {
      Object.assign(this.form, data);
    },
    async save() {
      if (this.esJefe == "si") {
        this.form.esJefe = true;
      }
      if (this.esJefe == "no") {
        this.form.esJefe = false;
      }
      return await axios
        .post("/api/grupoevaluador", this.form)
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
      if (this.esJefe == "si") {
        this.form.esJefe = true;
      }
      if (this.esJefe == "no") {
        this.form.esJefe = false;
      }
      let id = this.form.id;

      return await axios
        .put("/api/grupoevaluador/" + id, this.form)
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
    async deleteRow(dataRow) {
      this.confirmDelete(dataRow);
    },
    async deleteRowRequest(dataRow) {
      this.setFormData(dataRow);
      const id = this.form.id;
      this.$q.loading.show();
      return await axios
        .delete("/api/grupoevaluador/" + id)
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
          message: "¿Estás seguro de eliminar este experto?",
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
      if (accion === "add") {
        this.modalDialog = data;
      }
    },
    openModalUpdate(data, accion, dataRow) {
      this.accion = accion;

      if (accion === "update") {
        this.setFormData(dataRow);

        console.log("WWW", this.form);
        if (this.form.esJefe == 1 || this.form.esJefe) {
          this.form.esJefe = true;
          this.esJefe = "si";
        }
        if (this.form.esJefe == 0 || !this.form.esJefe) {
          this.form.esJefe = false;
          this.esJefe = "no";
        }

        if (this.form.evalUno == 1 || this.form.evalUno) {
          this.form.evalUno = true;
        }else{
           this.form.evalUno = false;
        }
       if (this.form.evalDos == 1 || this.form.evalDos) {
          this.form.evalDos = true;
        }else{
           this.form.evalDos = false;
        }
        this.modalDialog = data;
      }
    },
    async loadData() {
      this.numeroRegistro = this.itemSelected.numeroRegistro;
      this.instalacion = this.itemSelected.instalacion.nombre;
      this.form.proceso_id = this.itemSelected.id;
      return await axios
        .get("/api/evaluadores/" + this.itemSelected.id)
        .then((result) => {
          this.data = result.data;
        })
        .catch((err) => {
          console.log(err);
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
    async loadHabilidades() {
      return await axios
        .get("/api/habilidades")
        .then((response) => {
          this.habilidades = response.data.data;
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
    async loadEvaluaciones() {
      return await axios
        .get("/api/evaluaciones")
        .then((response) => {
          this.evaluaciones = response.data.data;
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
    openModalSee(data, dataRow) {
      this.setFormData(dataRow);
      this.modalDialogSee = data;
    },
  },
};
</script>
