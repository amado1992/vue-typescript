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
            <q-toolbar-title>
              <b># de registro: {{
                numeroRegistro + " - " + instalacionNombre
              }}</b></q-toolbar-title
            >

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
              gestionar seguimiento de alcance
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
                      @click.prevent="
                      openModalSee(true, props.row)
                    "
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

    <!--Dialog form seguimiento-->
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
                    ? "Adicionar seguimiento"
                    : "Actualizar seguimiento"
                }}
              </q-toolbar-title>
              <q-btn
                round
                dense
                flat
                icon="close"
                @click="closeModalForm()"
                v-close-popup
              >
                <q-tooltip>Cerrar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </q-card-section>

          <q-card-section class="no-padding">
            <q-card-section>
              <div class="row q-gutter-y-md">
                 <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                      <q-input
                        label="Primera evaluación"
                        v-model="form.fechaPrimeraEvaluacion"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form.fechaPrimeraEvaluacion"
                                mask="YYYY-MM-DD"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                      <q-input
                        label="Segunda evaluación"
                        v-model="form.fechaSegundaEvaluacion"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form.fechaSegundaEvaluacion"
                                mask="YYYY-MM-DD"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Fecha del mes"
                    v-model="form.fechaSeguimientoMensual"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            v-model="form.fechaSeguimientoMensual"
                            mask="YYYY-MM-DD"
                          >
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Fecha del semestre"
                    v-model="form.fechaSeguimientoSemestral"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            v-model="form.fechaSeguimientoSemestral"
                            mask="YYYY-MM-DD"
                          >
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Supensión temporal del certificado"
                    v-model="form.fechaSuspensionTemporalCertificado"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            v-model="form.fechaSuspensionTemporalCertificado"
                            mask="YYYY-MM-DD"
                          >
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Retiro de la supensión temporal del certificado"
                    v-model="form.fechaRetiroSuspensionTemporalCertificado"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            v-model="
                              form.fechaRetiroSuspensionTemporalCertificado
                            "
                            mask="YYYY-MM-DD"
                          >
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Cancelación del certificado"
                    v-model="form.fechaCanceladoCertificado"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            v-model="form.fechaCanceladoCertificado"
                            mask="YYYY-MM-DD"
                          >
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    v-model="form.requisitoIncumplido"
                    label="Requisito incumplido"
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
                @click.prevent="closeModalForm()"
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
    <!--Dialog form seguimiento-->
<!--Ver mas datos del seguimiento-->
    <q-dialog
      v-model="modalDialogSee"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title> Datos de seguimiento </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModalForm()" v-close-popup>
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
            @click="closeModalForm()"
            label="Cancelar"
            v-close-popup
            color="negative"
            flat
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!--Fin del ver mas datos del seguimiento-->
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
  name: "SeguimientoTurismoMasHS",
  props: {
    itemSelected: Object,
  },
  data() {
    return {
      scopes: sessionStorage.getItem("scopes"),
      instalacionNombre: "",
      numeroRegistro: "",
      filterInput: false,
      loading: false,
      filter: "",
      data: [],
      modalDialog: false,
      modalDialogTable: false,
      modalDialogSee: false,
      accion: "",
      form: {
        id: 0,
        fechaSeguimientoMensual: "",
        fechaSeguimientoSemestral: "",
        fechaSuspensionTemporalCertificado: "",
        fechaRetiroSuspensionTemporalCertificado: "",
        fechaCanceladoCertificado: "",
        requisitoIncumplido: "",
        mtalcance_id: 1,

        fechaPrimeraEvaluacion: "",
        fechaSegundaEvaluacion: "",
      },
      columns: [
        {
          name: "fechaMensual",
          align: "left",
          label: "Mes",
          field: (row) => row.fechaSeguimientoMensual,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "fechaSemestral",
          align: "left",
          label: "Semestre",
          field: (row) => row.fechaSeguimientoSemestral,
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
    };
  },
  created() {},
  mounted() {
    this.$root.$on("openTable", (data) => {
      this.modalDialogTable = true;
      this.loadData();
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
      this.$root.$emit("closeTable");
      this.reset_form();
      this.modalDialog = false;
    },
    closeModalForm() {
      this.reset_form();
      this.modalDialog = false;
    },
    reset_form() {
      this.form.id = 0;
      this.form.mtalcance_id = null;
      this.form.fechaSeguimientoMensual = "";
      this.form.fechaSeguimientoSemestral = "";
      this.form.fechaSuspensionTemporalCertificado = "";
      this.form.fechaRetiroSuspensionTemporalCertificado = "";
      this.form.fechaCanceladoCertificado = "";
      this.form.requisitoIncumplido = "";

      this.form.fechaPrimeraEvaluacion = "";
      this.form.fechaSegundaEvaluacion = "";
    },
    setFormData(data) {
      Object.assign(this.form, data);
    },
    async save() {
      return await axios
        .post("/api/seguimiento_alcance_tmhs", this.form)
        .then((result) => {
          this.loadData();
          successMessage("Los datos se insertaron satisfactoriamente");
          this.closeModalForm();
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
        .put("/api/seguimiento_alcance_tmhs/" + id, this.form)
        .then((result) => {
          this.loadData();
          successMessage("Los datos se actualizaron satisfactoriamente");
          this.closeModalForm();
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
        .delete("/api/seguimiento_alcance_tmhs/" + id)
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
          message: "¿Estás seguro de eliminar este seguimiento?",
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
        this.modalDialog = data;
      }
    },
    async loadData() {
      console.log("hola", this.itemSelected)
     // this.numeroRegistro = this.itemSelected.proceso_turismo_mas_higienico_seguro.numeroRegistro;
      this.numeroRegistro = this.itemSelected.numeroRegistro;

     // this.instalacionNombre = this.itemSelected.proceso_turismo_mas_higienico_seguro.instalacion.nombre;

       this.instalacionNombre = this.itemSelected.instalacion.nombre;
      this.form.mtalcance_id = this.itemSelected.id;
      return await axios
        .get("/api/seguimientos_alcance/" + this.itemSelected.id)
        .then((result) => {
          this.data = result.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
     openModalSee(data, dataRow) {
        this.setFormData(dataRow);
        this.modalDialogSee = data;
    },
  },
};
</script>
