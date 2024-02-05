<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          :data="reportes"
          :columns="columns"
          :visible-columns="visibleColumns"
          row-key="id"
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar reportes costo calidad
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
              :disable="reportes.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_costo_calidad')"
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
          </template>
          <template v-slot:body-cell-acciones="props">
            <q-td :props="props">
              <div class="q-gutter-xs">
                <q-btn
                  v-if="scopes.includes('gestionar_costo_calidad')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="openModal(true, 'actualizar', props.row)"
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_costo_calidad')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteReportes(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
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
          ref="formReporte"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == "adicionar"
                      ? "Adicionar reporte"
                      : "Actualizar reporte"
                  }}
                </q-toolbar-title>
                <q-btn
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
                <div class="col-6">
                  <q-input
                    lazy-rules
                    :rules="[(val) => !!val || 'Por favor escriba algo']"
                    v-model="form_sistema.nombre"
                    type="text"
                    label="Nombre reporte *"
                    name="nombre"
                    :error-message="mensajesError('nombre')"
                    :error="$v.form_sistema.nombre.$error"
                    debounce="1000"
                  />
                </div>
                <div class="col-6 q-pl-md">
                  <q-input
                    v-model="form_sistema.fecha"
                    label="Fecha reporte *"
                    name="fecha"
                    :error-message="mensajesError('fecha')"
                    :error="$v.form_sistema.fecha.$error"
                    debounce="1000"
                    lazy-rules
                    :rules="[
                      (val) => !!val || 'Por favor escriba algo',
                      (val) =>
                        val < formattedString ||
                        'Por favor escriba una fecha menor o igual a la actual',
                    ]"
                  >
                    <template>
                      <q-popup-proxy
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date
                          v-model="form_sistema.fecha"
                          mask="YYYY-MM-DD"
                          minimal
                        >
                          <div class="row justify-end">
                            <q-btn
                              v-close-popup
                              label="Cerrar"
                              color="primary"
                              flat
                            />
                          </div>
                        </q-date>
                      </q-popup-proxy>
                    </template>
                  </q-input>
                </div>
              </div>
              <div class="row q-pt-sm">
                <div class="col-6">
                  <q-input
                    lazy-rules
                    :rules="[
                      (val) => !!val || 'Por favor escriba algo',
                      (val) => val > 0 || 'Por favor escriba un número válido',
                    ]"
                    v-model="form_sistema.ventaNetaImporte"
                    type="number"
                    label="Venta neta importe *"
                    prefix="$"
                    name="ventaNetaImporte"
                    :error-message="mensajesError('ventaNetaImporte')"
                    :error="$v.form_sistema.ventaNetaImporte.$error"
                    debounce="1000"
                  >
                  </q-input>
                </div>
                <div class="col-6">
                  <q-input
                    lazy-rules
                    :rules="[
                      (val) => !!val || 'Por favor escriba algo',
                      (val) => val > 0 || 'Por favor escriba un número válido',
                    ]"
                    v-model="form_sistema.ventaNetaAcumulada"
                    type="number"
                    label="Venta neta acumulada *"
                    prefix="$"
                    style="margin-left: 10px"
                    name="ventaNetaAcumulada"
                    :error-message="mensajesError('ventaNetaAcumulada')"
                    :error="$v.form_sistema.ventaNetaAcumulada.$error"
                    debounce="1000"
                  >
                  </q-input>
                </div>
              </div>
              <div class="text-red" v-show="form_error">
                Faltan campos por completar
              </div>
            </q-card-section>
            <q-card-actions align="right">
              <q-btn
                type="submit"
                key="guardar"
                label="Guardar"
                flat
                color="primary"
                icon="save"
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
import { required, maxLength } from "vuelidate/lib/validators";
import { date } from "quasar";

export default {
  name: "costo_calidad_reportes",
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
      visibleColumns: [
        "nombre",
        "instalacion",
        "fecha",
        "ventaNetaImporte",
        "ventaNetaAcumulada",
        "acciones",
      ],
      form_sistema: {
        codigo: "",
        nombre: "",
        id: "",
        fecha: "",
        ventaNetaImporte: "",
        ventaNetaAcumulada: "",
        instalacion_id: sessionStorage.getItem("instalacion_id"),
      },
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      form_error: false,
      required_error: false,
      columns: [
        {
          name: "id",
          label: "Id",
          align: "center",
          field: (row) => row.id,
        },
        {
          name: "codigo",
          label: "codigo",
          align: "center",
          field: "codigo",
        },
        {
          name: "nombre",
          label: "REPORTE",
          align: "left",
          field: "nombre",
          search: true,
        },
        {
          name: "instalacion",
          label: "INSTALACIÓN",
          align: "left",
          field: (row) => row.instalacion && row.instalacion.nombre,
          search: true,
        },
        {
          name: "fecha",
          label: "FECHA",
          align: "center",
          field: "fecha",
          search: true,
        },
        {
          name: "ventaNetaImporte",
          label: "VENTA NETA IMPORTE",
          align: "center",
          field: "ventaNetaImporte",
          search: true,
        },
        {
          name: "ventaNetaAcumulada",
          label: "VENTA NETA ACUMULADA",
          align: "center",
          field: "ventaNetaAcumulada",
          search: true,
        },
        {
          name: "acciones",
          label: "ACCIONES",
          align: "center",
          field: "action",
          headerClasses: "text-uppercase",
        },
      ],
      formattedString: "",
    };
  },
  validations: {
    form_sistema: {
      nombre: {
        required,
        maxLength: maxLength(30),
      },
      fecha: {
        required,
      },
      ventaNetaImporte: {
        required,
      },
      ventaNetaAcumulada: {
        required,
      },
    },
  },
  created() {
    let timeStamp = Date.now();

    this.formattedString = date.formatDate(
      timeStamp,
      "YYYY-MM-DDTHH:mm:ss.SSSZ"
    );
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Dirección de Calidad" },
      { label: "Costo de calidad" },
      { label: "Gestionar reportes" },
    ]);
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
    ...mapState("costo_calidad_reportes", ["reportes"]),

    isComplete() {
      if (
        this.form_sistema.nombre &&
        this.form_sistema.fecha &&
        this.form_sistema.ventaNetaImporte &&
        this.form_sistema.ventaNetaAcumulada
      ) {
        return false;
      }
      return true;
    },
  },
  methods: {
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    ...mapActions("utils", ["setLoading"]),
    ...mapActions("costo_calidad_reportes", [
      "saveReporte",
      "editReporte",
      "deleteReporte",
      "getReportes",
    ]),
    ...mapGetters("utils", ["getterLoading"]),

    mensajesError(campo) {
      if (!this.$v.form_sistema.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === "nombre") {
        if (!this.$v.form_sistema.nombre.required) {
          this.required_error = true;
          return "";
        }
        if (!this.$v.form_sistema.nombre.maxLength) {
          this.required_error = false;
          return "El nombre solo puede tener 30 carácteres";
        }
      }
      if (campo === "fecha") {
        if (!this.$v.form_sistema.fecha.required) {
          this.required_error = true;
          return "";
        }
      }
      if (campo === "ventaNetaImporte") {
        if (!this.$v.form_sistema.ventaNetaImporte.required) {
          this.required_error = true;
          return "";
        }
      }
      if (campo === "ventaNetaAcumulada") {
        if (!this.$v.form_sistema.ventaNetaAcumulada.required) {
          this.required_error = true;
          return "";
        }
      }
    },

    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    reset_form() {
      this.form_sistema.codigo = "";
      this.form_sistema.nombre = "";
      this.form_sistema.fecha = "";
      this.form_sistema.ventaNetaImporte = "";
      this.form_sistema.ventaNetaAcumulada = "";
      this.add = true;
      this.form_error = false;
      this.val = false;
      this.required_error = false;
      this.$v.form_sistema.$reset();
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
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
      await this.getReportes(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    filterFn(val, update) {
      update(() => {
        const needle = val.toLowerCase();
        this.options = this.permissions.filter(
          (v) => v.name.toLowerCase().indexOf(needle) > -1
        );
      });
    },
    Ramdon() {
      var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHJKMNPQRTUVWXYZ12346789";
      var id_muestra = "";
      for (var i = 0; i < 10; i++)
        id_muestra += caracteres.charAt(
          Math.floor(Math.random() * caracteres.length)
        );
      this.form_sistema.codigo = id_muestra;
    },
    openModal(data, accion, rowData) {
      this.accion = accion;
      if (accion === "adicionar") {
        this.Ramdon();
        this.modalDialog = data;
      } else {
        if (accion === "actualizar") {
          if (rowData) {
            this.setFormData(rowData);
            this.modalDialog = data;
          } else {
            infoMessage("Debe seleccionar una fila para modificar.");
          }
        }
      }
    },
    setFormData(data) {
      this.form_sistema = Object.assign(this.form_sistema, data);
    },
    async save() {
      this.$v.form_sistema.$touch();
      if (this.$v.form_sistema.$invalid) {
        infoMessage("Revise los campos inválidos");
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.saveReporte(this.form_sistema);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
        this.setLoading(false);
      }
    },
    async update() {
      this.$v.form_sistema.$touch();
      if (this.$v.form_sistema.$invalid) {
        infoMessage("Revise los campos inválidos");
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.editReporte(this.form_sistema);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
        this.setLoading(false);
      }
    },
    deleteReportes(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteReporte(selected.id);
          this.reset_form();
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

<style scoped></style>
