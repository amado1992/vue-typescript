<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="text-subtitle1 text-weight-bold text-uppercase">
            gestionar costos calidad conformidad
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
            :disable="conformidadCC.length != 0 || noConformidadCC.length != 0 ? false : true"
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
            @click.prevent="openModal(true, 'add')"
            class="q-mt-sm"
          >
            <q-tooltip>Nuevos datos</q-tooltip>
          </q-btn>
        </div>
      </q-card-section>
      <q-card-section class="no-padding">
        <q-tabs
          dense
          v-model="tab"
          align="center"
          active-color=""
          active-bg-color=""
          narrow-indicator
          indicator-color="primary"
        >
          <q-tab name="conformidad" label="Calidad Conformidad" />
          <q-tab name="no_conformidad" label="Calidad No Conformidad" />
        </q-tabs>
        <q-tab-panels
          v-model="tab"
          animated
          transition-prev="fade"
          transition-next="fade"
        >
          <q-tab-panel name="conformidad">
            <q-table
              flat
              dense
              :data="conformidadCC"
              :visible-columns="visibleColumns"
              :columns="columns"
              :loading="this.getterLoading()"
              row-key="id"
              loading-label="Cargando elementos"
              :rows-per-page-options="[5, 10, 25, 50]"
              :filter="filter"
              binary-state-sort
              no-data-label="No se encontraron elementos a mostrar"
            >
              <template v-slot:body-cell-acciones="props">
                <q-td :props="props">
                  <div class="q-gutter-xs">
                    <q-btn
                      flat
                      key="see"
                      dense
                      size="sm"
                      text-color="primary"
                      icon="remove_red_eye"
                      @click.prevent="openModal(true, 'see', props.row)"
                    >
                      <q-tooltip>Ver detalles</q-tooltip>
                    </q-btn>
                    <q-btn
                      v-if="scopes.includes('gestionar_costo_calidad')"
                      flat
                      dense
                      size="sm"
                      text-color="primary"
                      icon="edit"
                      @click.prevent="openModal(true, 'update', props.row)"
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
                      @click.prevent="eliminar(props.row)"
                    >
                      <q-tooltip>Eliminar datos</q-tooltip>
                    </q-btn>
                  </div>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>

          <q-tab-panel name="no_conformidad">
            <q-table
              flat
              dense
              :data="noConformidadCC"
              :visible-columns="visibleColumns"
              :columns="columnsNo_Conformidad"
              row-key="id"
              :loading="this.getterLoading()"
              loading-label="Cargando elementos"
              :rows-per-page-options="[5, 10, 25, 50]"
              :filter="filter"
              binary-state-sort
              no-data-label="No se encontraron elementos a mostrar"
            >
              <template v-slot:body-cell-acciones="props">
                <q-td :props="props">
                  <div class="q-gutter-xs">
                    <q-btn
                      flat
                      key="see"
                      dense
                      size="sm"
                      text-color="primary"
                      icon="remove_red_eye"
                      @click.prevent="openModal(true, 'see', props.row)"
                    >
                      <q-tooltip>Ver detalles</q-tooltip>
                    </q-btn>
                    <q-btn
                      v-if="scopes.includes('gestionar_costo_calidad')"
                      flat
                      dense
                      size="sm"
                      text-color="primary"
                      icon="edit"
                      @click.prevent="openModal(true, 'update', props.row)"
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
                      @click.prevent="eliminar(props.row)"
                    >
                      <q-tooltip>Eliminar datos</q-tooltip>
                    </q-btn>
                  </div>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>
        </q-tab-panels>
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
          @submit="action != 'add' ? update() : nuevo()"
          ref="formConformidad"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.action == "add"
                      ? "Adicionar datos"
                      : "Actualizar datos"
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
              <div class="row q-px-sm">
                <div v-show="action === 'add'" class="col-12">
                  <q-radio
                    dense
                    v-model="form_sistema.tipo"
                    val="Calidad Conformidad"
                    label="Conformidad"
                  />
                  <q-radio
                    class="q-pl-md"
                    dense
                    v-model="form_sistema.tipo"
                    val="Calidad No Conformidad"
                    label="No Conformidad"
                  />
                </div>
              </div>
              <div
                v-show="form_sistema.tipo === 'Calidad Conformidad'"
                class="row"
              >
                <div class="col q-pr-sm">
                  <q-select
                    v-model="form_sistema.conformidad_id"
                    :options="conformidad"
                    label="Conformidad *"
                    options-dense
                    option-value="id"
                    option-label="nombre"
                    emit-value
                    map-options
                    name="conformidad_id"
                    :error-message="mensajesError('conformidad_id')"
                    :error="$v.form_sistema.conformidad_id.$error"
                    debounce="1000"
                  />
                </div>
              </div>
              <div
                v-show="form_sistema.tipo === 'Calidad No Conformidad'"
                class="row"
              >
                <div class="col q-pr-sm">
                  <q-select
                    v-model="form_sistema.noconformidad_id"
                    :options="noconformidad"
                    label="No Conformidad *"
                    options-dense
                    option-value="id"
                    option-label="nombre"
                    emit-value
                    map-options
                    name="noconformidad_id"
                    :error-message="mensajesError('noconformidad_id')"
                    :error="$v.form_sistema.noconformidad_id.$error"
                    debounce="1000"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col q-pr-sm">
                  <q-select
                    v-model="form_sistema.reportCostoCalidad_id"
                    :options="reportCostoCalidad"
                    label="Reporte Costo Calidad *"
                    options-dense
                    option-value="id"
                    option-label="nombre"
                    emit-value
                    map-options
                    name="reportCostoCalidad_id"
                    :error-message="mensajesError('reportCostoCalidad_id')"
                    :error="$v.form_sistema.reportCostoCalidad_id.$error"
                    debounce="1000"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <q-input
                    lazy-rules
                     :rules="[
                      (val) => !!val || 'Por favor escriba algo',
                      (val) => val > 0 || 'Por favor escriba un número válido',
                    ]"
                    v-model="form_sistema.importe"
                    type="number"
                    label="Importe *"
                    prefix="$"
                    name="importe"
                    :error-message="mensajesError('importe')"
                    :error="$v.form_sistema.importe.$error"
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
                    v-model="form_sistema.acumulado"
                    type="number"
                    label="Acumulado *"
                    prefix="$"
                    style="margin-left: 10px"
                    name="acumulado"
                    :error-message="mensajesError('acumulado')"
                    :error="$v.form_sistema.acumulado.$error"
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
              <!--<q-btn
                type="button"
                icon="save"
                @click="
                  action != 'add'
                    ? update(form_sistema.id, form_sistema)
                    : nuevo(form_sistema)
                "
                label="Guardar"
                color="primary"
                flat
              />-->
              <q-btn
                type="submit"
                :icon="action === 'add' ? 'save' : 'edit'"
                label="Guardar"
                color="primary"
                flat
                :disable="isComplete"
              />
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
    <!-- Dialog detalles -->
    <q-dialog
      v-model="modalDialogSee"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title> Consultar datos </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section class="q-pa-lg">
          <div class="row">
            <div class="col">
              <q-item-label
                ><span class="text-bold">Nombre reporte:</span>
                {{ reporte }}</q-item-label
              >
            </div>
          </div>
          <div class="row q-pt-md">
            <div class="col">
              <q-item-label
                ><span class="text-bold">Tipo:</span>
                {{ form_sistema.tipo }}</q-item-label
              >
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="check"
            @click="closeModal()"
            label="Aceptar"
            v-close-popup
            color="primary"
            flat
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import {
  errorMessage,
  successMessage,
  infoMessage,
} from "../../utils/notificaciones";
import Vue from "vue";
import * as message from "../../utils/resources";
import { showDialog } from "../../utils/dialogo";
import { mapState, mapGetters, mapActions } from "vuex";
import { required } from "vuelidate/lib/validators";

export default {
  name: "costo_calidad_conformidad",
  data() {
    return {
      tab: "conformidad",
      visibleColumns: [
        "conformidad",
        "reporte",
        "importe",
        "acumulado",
        "acciones",
      ],
      modalDialog: false,
      modalDialogSee: false,
      loading: true,
      selected: [],
      reportCostoCalidad: [],
      dataNoConformidad: [],
      conformidad: [],
      noconformidad: [],
      filterInput: false,
      add: true,
      scopes: sessionStorage.getItem("scopes"),
      filter: "",
      action: "",
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      form_sistema: {
        id: "",
        conformidad_id: "",
        reportCostoCalidad_id: "",
        importe: "",
        acumulado: "",
        tipo: "",
      },
      options: ["Calidad Conformidad", "Calidad No Conformidad"],
      instal: "",
      conformidades: "",
      reporte: "",
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
          name: "conformidad",
          label: "CONFORMIDAD",
          align: "left",
          field: (row) => row.costo_conformidad && row.costo_conformidad.nombre,
          search: true,
        },
        {
          name: "reporte",
          label: "REPORTE",
          align: "left",
          field: (row) =>
            row.costo_calidad_report && row.costo_calidad_report.nombre,
          search: true,
        },
        {
          name: "importe",
          label: "IMPORTE",
          align: "center",
          field: "importe",
          search: true,
        },
        {
          name: "acumulado",
          label: "ACUMULADO",
          align: "center",
          field: "acumulado",
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
      columnsNo_Conformidad: [
        {
          name: "id",
          label: "Id",
          align: "center",
          field: (row) => row.id,
        },
        {
          name: "conformidad",
          label: "NO CONFORMIDAD",
          align: "left",
          field: (row) =>
            row.costo_no_conformidad && row.costo_no_conformidad.nombre,
          search: true,
        },
        {
          name: "reporte",
          label: "REPORTE",
          align: "left",
          field: (row) =>
            row.costo_calidad_report && row.costo_calidad_report.nombre,
          search: true,
        },
        {
          name: "importe",
          label: "IMPORTE",
          align: "center",
          field: "importe",
          search: true,
        },
        {
          name: "acumulado",
          label: "ACUMULADO",
          align: "center",
          field: "acumulado",
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
    };
  },
  validations: {
    form_sistema: {
      conformidad_id: {
        required,
      },
      noconformidad_id: {
        required,
      },
      reportCostoCalidad_id: {
        required,
      },
      importe: {
        required,
      },
      acumulado: {
        required,
      },
    },
  },
  async mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Dirección de Calidad  " },
      { label: "Costo de calidad" },
      { label: "Gestionar costo calidad conformidad" },
    ]);
    this.loadData();
    this.reportCostoCalidad = await this.getDataCalidadReporte();
    this.conformidad = await this.getDataConformidad();
    this.noconformidad = await this.getDataNoConformidad();
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
    ...mapState("costo_calidad", ["conformidadCC", "noConformidadCC"]),

    isComplete() {
      if (
        this.form_sistema.reportCostoCalidad_id &&
        this.form_sistema.importe &&
        this.form_sistema.acumulado
      ) {
        return false;
      }
      return true;
    },
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    ...mapActions("utils", ["setLoading"]),
    ...mapActions("costo_calidad", [
      "getConformidadList",
      "getNoConformidadList",
      "getDataCalidadReporte",
      "getDataConformidad",
      "getDataNoConformidad",
      "saveConformidad",
      "editConformidad",
      "deleteConformidad",
    ]),
    mensajesError(campo) {
      if (!this.$v.form_sistema.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === "conformidad_id") {
        if (!this.$v.form_sistema.conformidad_id.required) {
          this.required_error = true;
          return "";
        }
      }
      if (campo === "noconformidad_id") {
        if (!this.$v.form_sistema.conformidad_id.required) {
          this.required_error = true;
          return "";
        }
      }
      if (campo === "reportCostoCalidad_id") {
        if (!this.$v.form_sistema.reportCostoCalidad_id.required) {
          this.required_error = true;
          return "";
        }
      }
      if (campo === "importe") {
        if (!this.$v.form_sistema.importe.required) {
          this.required_error = true;
          return "";
        }
      }
      if (campo === "acumulado") {
        if (!this.$v.form_sistema.acumulado.required) {
          this.required_error = true;
          return "";
        }
      }
    },
    loadData() {
      this.getListConformidad();
      this.getListNoConformidad();
    },
    getListConformidad() {
      this.setLoading(true);
      this.getConformidadList().then(() => {
        this.setLoading(false);
      });
    },
    getListNoConformidad() {
      this.setLoading(true);
      this.getNoConformidadList().then(() => {
        this.setLoading(false);
      });
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
      this.selected = [];
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
    async openModal(data, action, selected) {
      this.action = action;
      if (action === "see" && selected) {
        this.conformidades = await this.searchConformidad(selected.id);
        this.reporte = await this.searchReporte(selected.reportCostoCalidad_id);
        this.setFormData(selected);
        this.modalDialogSee = data;
      } else if (action === "update" && selected) {
        this.setFormData(selected);
        this.modalDialog = data;
      } else {
        this.Ramdon();
        this.modalDialog = data;
      }
    },
    async searchConformidad(id) {
      return await Vue.prototype.$axios
        .get(`/api/costos_conformidades/${id}`)
        .then((result) => {
          if (result.status === 200) {
            return result.data.data.nombre;
          }
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    async searchReporte(id) {
      return await Vue.prototype.$axios
        .get(`/api/costo_calidad_report/${id}`)
        .then((result) => {
          if (result.status === 200) {
            return result.data.data.nombre;
          }
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
    },
    setFormData(data) {
      this.form_sistema = Object.assign(this.form_sistema, data);
    },
    reset_form() {
      this.form_sistema.conformidad_id = "";
      this.form_sistema.reportCostoCalidad_id = "";
      this.form_sistema.importe = "";
      this.form_sistema.acumulado = "";
      this.form_sistema.tipo = "";
      this.form_error = false;
      this.required_error = false;
      this.add = true;
      this.$v.form_sistema.$reset();
    },
    async nuevo() {
      this.$v.form_sistema.$touch();
      //Revisar este codigo en las condicionales
      /* if (this.$v.form_sistema.$invalid) {
        infoMessage('Revise los campos inválidos');
        
        if (this.required_error) {
          this.form_error = true;
        }
      } else {*/
      this.setLoading(true);
      await this.saveConformidad(this.form_sistema);
      this.reset_form();
      this.loadData();
      this.closeModal();
      // }
      this.setLoading(false);
    },
    async updateo(id, form_sitema) {
      this.$v.form_sistema.$touch();
      if (this.$v.form_sistema.$invalid) {
        infoMessage("Revise los campos inválidos");
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        return await Vue.prototype.$axios
          .put(`/api/costo_calidad_conformidad/${id}`, { data: form_sitema })
          .then((result) => {
            if (result.status === 200) {
              this.loadData();
              successMessage(message.sUpdateRow);
              this.closeModal();
              this.reset_form();
            }
            this.setLoading(false);
          })
          .catch((err) => {
            errorMessage(message.eUpdateRow, err);
          });
      }
    },
    async update() {
      this.$v.form_sistema.$touch();
      /*  if (this.$v.form_sistema.$invalid) {
        infoMessage('Revise los campos inválidos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {*/
      this.setLoading(true);
      await this.editConformidad(this.form_sistema);
      this.reset_form();
      this.closeModal();
      this.loadData();
      this.setLoading(false);
      // }
    },
    eliminar(selected) {
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteConformidad(selected);
          this.reset_form();
          await this.loadData();
          this.setLoading(false);
        });
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
  },
};
</script>

<style scoped></style>
