<template>
  <div class="q-pa-md">
    <!-- tabla-->
    <q-card>
      <q-card-section class="no-padding">
        <q-table
          class="q-px-md"
          :data="premio"
          :columns="columns"
          row-key="id"
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          dense
          flat
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar premios
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
              :disable="premio.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_premios')"
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
          <template v-slot:body-cell-action="props">
            <q-td :props="props">
              <div class="q-gutter-sm">
                <q-btn
                  v-if="scopes.includes('gestionar_premios')"
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
                  v-if="scopes.includes('gestionar_premios')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deletePremios(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
          <template v-slot:body-cell-fecha="props">
            <q-td :props="props">
              {{ fecha.formatDate(props.row.fecha, "DD-MM-YYYY") }}
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
          ref="formPremio"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == "adicionar"
                      ? "Adicionar premio"
                      : "Actualizar premio"
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
                    v-model="form_data.nombre"
                    label="Nombre *"
                    name="nombre"
                    type="text"
                    :rules="[val => !!val || 'Campo requerido']"
                    :error-message="mensajesError('nombre')"
                    :error="$v.form_data.nombre.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-input
                    v-model="form_data.fecha"
                    label="Fecha recibido *"
                    name="fecha"
                    mask="####/##/##"
                    :error-message="mensajesError('fecha')"
                    :error="$v.form_data.fecha.$error"
                    debounce="1000"
                  >
                    <template>
                      <q-popup-proxy
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date
                          v-model="form_data.fecha"
                          mask="YYYY-MM-DD"
                          :navigation-min-year-month="min"
                          :navigation-max-year-month="max"
                          minimal
                        >
                          <div class="row items-center justify-end">
                            <q-btn
                              v-close-popup
                              label="Guardar"
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
              <div class="row">
                <div class="col-6">
                  <q-select
                    v-model="form_data.categoriapremio_id"
                    emit-value
                    map-options
                    options-dense
                    :options="items_categoria"
                    label="Categoría de premio *"
                    option-label="nombre"
                    option-value="id"
                    :error-message="mensajesError('categoriapremio_id')"
                    :error="$v.form_data.categoriapremio_id.$error"
                  />
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
import {mapActions, mapGetters, mapState} from "vuex";
import {infoMessage, warningMessage} from "../../utils/notificaciones";
import {showDialog} from "../../utils/dialogo";
import * as message from "../../utils/resources";
import {date} from "quasar";
import {required, alpha} from 'vuelidate/lib/validators';

const timeStamp = Date.now()
const formattedString = date.formatDate(timeStamp, 'YYYY/MM',{})

export default {
  name: "NuevoPremio",
  data() {
    return {
      fecha: date,
      min: formattedString,
      max: formattedString,
      scopes: sessionStorage.getItem("scopes"),
      modalDialog: false,
      filterInput: false,
      val: false,
      add: true,
      accion: "",
      loading: false,
      filter: "",
      selected: [],
      items_categoria: [],
      form_data: {
        nombre: "",
        fecha: "",
        categoriapremio_id: "",
      },
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      data: [],
      form_error: false,
      required_error: false,
      columns: [
        {
          name: "fecha",
          align: "center",
          label: "fecha",
          field: "fecha",
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "nombre",
          align: "center",
          label: "premio",
          field: "nombre",
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "tipo",
          align: "center",
          label: "tipo de premio",
          field: (row) => row.categoriapremios && row.categoriapremios.nombre,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "action",
          align: "left",
          label: "acciones",
          field: "action",
          headerClasses: "text-uppercase",
        },
      ],
    };
  },
  validations: {
    form_data: {
      nombre: {
        required, alpha
      },
      fecha: {
        required,
      },
      categoriapremio_id: {
        required
      }
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      {label: "Dirección de Calidad"},
      {label: "Gestionar premios y distinciones"},
      {label: "Gestionar premios"},
    ]);
    this.getCategoria_premios().then((r) => {
      this.items_categoria = r;
    });
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
    ...mapState("premio", ["premio"]),
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    ...mapActions("utils", ["setLoading"]),
    ...mapActions("premio", [
      "getPremio",
      "savePremio",
      "editPremio",
      "deletePremio",
    ]),
    ...mapActions('categoria_premio', ['getCategoria_premios']),

    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    reset_form() {
      this.form_data.nombre = '';
      this.form_data.fecha = '';
      this.form_data.categoriapremio_id = '';
      this.form_error = false;
      this.val = false;
      this.required_error = false;
      this.permiso = true;
      this.add = true;
      this.$v.form_data.$reset();
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
      this.selected = [];
    },
    async loadData(props) {
      const {page, rowsPerPage, sortBy, descending} = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getPremio(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'nombre') {
        if (!this.$v.form_data.nombre.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.nombre.alpha) {
          this.required_error = true;
          return 'Solo se aceptan caracteres alfabéticos';
        }
      }
      if (campo === 'fecha') {
        if (!this.$v.form_data.fecha.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'categoriapremio_id') {
        if (!this.$v.form_data.categoriapremio_id.required) {
          this.required_error = true;
          return '';
        }
      }
    },
    filterFn(val, update) {
      update(() => {
        const needle = val.toLowerCase();
        this.options = this.permissions.filter(
          (v) => v.name.toLowerCase().indexOf(needle) > -1
        );
      });
    },
    openModal(data, accion, rowData) {
      this.accion = accion;
      if (accion === "adicionar") {
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
      this.form_data = Object.assign(this.form_data, data);
    },
    async save() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos requeridos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.savePremio(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
        this.setLoading(false);
      }
    },
    async update() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos requeridos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.editPremio(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
        this.setLoading(false);
      }
    },
    deletePremios(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deletePremio(selected.id);
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
