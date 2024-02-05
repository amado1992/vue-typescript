<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          class="table-title"
          title="Gestionar estado técnico de medios de transportes"
          :grid="xssmallScreen || smallScreen"
          table-header-class="text-uppercase"
          :data="etmtransporte"
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
          :visible-columns="visibleColumns"
          flat
          dense
        >
         <!--<template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar estado técnico de medios de transportes
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
              :disable="etmtransporte.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_dir_transporte_fi')"
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
                <q-btn
              key="filter"
              dense
              :disable="etmtransporte.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>

              </template>
            </q-input>
            <q-btn
              v-if="scopes.includes('gestionar_dir_transporte_fi')"
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
                  v-if="scopes.includes('gestionar_dir_transporte_fi')"
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
                  v-if="scopes.includes('gestionar_dir_transporte_fi')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteEtmtransportes(props.row)"
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

          <template v-slot:body-cell-action="props">
            <q-td :props="props">
              <div class="q-gutter-xs">
                <q-btn
                  v-if="scopes.includes('gestionar_dir_transporte_fi')"
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
                  v-if="scopes.includes('gestionar_dir_transporte_fi')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteEtmtransportes(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
        </q-table>
      </q-card-section>
    </q-card>
    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              {{
                this.accion == "adicionar"
                  ? "Adicionar estado técnico de medios de transporte"
                  : "Actualizar estado técnico de medios de transporte"
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
           <div class="row q-pa-sm">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <q-input
                v-model="form_data.estado"
                type="text"
                label="Estado técnico de medios de transporte *"
                dense
                name="estado"
                :error-message="mensajesError('estado')"
                :error="$v.form_data.estado.$error"
                debounce="1000"
                autogrow
              />
            </div>
          </div>
          <div class="text-red" v-show="form_error">
            Faltan campos por completar
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="save"
            @click="accion != 'adicionar' ? update() : save()"
            label="Guardar"
            color="primary"
            flat
            :loading="this.getterLoading()"
            v-show="add"
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
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { showDialog } from "../../../../resources/js/utils/dialogo";
import {
  infoMessage,
  warningMessage,
} from "../../../../resources/js/utils/notificaciones";
import * as message from "../../../../resources/js/utils/resources";
import { required, alpha, maxLength } from "vuelidate/lib/validators";
import SmallScreen from "../../mixin/SmallScreen";

export default {
  name: "etmtransportes",
  mixins:[SmallScreen],
  data() {
    return {
      visibleColumns: ["estado", "action"],
      modalDialog: false,
      permiso: true,
      val: false,
      selected: [],
      roles: [],
      rolesName: [],
      filterInput: false,
      aux: [],
      add: true,
      scopes: sessionStorage.getItem("scopes"),
      filter: "",
      accion: "",
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      options: [],
      permissions: [],
      form_data: {
        estado: "",
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
          name: "estado",
          label: "Estado técnico de medios de transporte",
          align: "left",
          field: "estado",
          search: true,
        },
        {
          name: "action",
          align: "center",
          label: "acciones",
          field: "action",
        },
      ],
    };
  },
  validations: {
    form_data: {
      estado: {
        required, alpha, maxLength: maxLength(30),
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Dirección de Transporte" },
      { label: "Flujo informativo" },
      { label: "Nomencladores" },
      { label: "Estado técnico de medios de transporte" },
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
    ...mapState("etmtransporte", ["etmtransporte"]),
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("etmtransporte", [
      "getEtmtransporte",
      "saveEtmtransporte",
      "editEtmtransporte",
      "deleteEtmtransporte",
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
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === "estado") {
        if (!this.$v.form_data.estado.required) {
          this.required_error = true;
          return "";
        }
        if (!this.$v.form_data.estado.alpha) {
          this.required_error = true;
          return 'Solo se aceptan caracteres alfabéticos';
        }
        if (!this.$v.form_data.estado.maxLength) {
          this.required_error = false;
          return "El estado técnico de medios de transporte solo puede tener 30 carácteres";
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
    convert(activo) {
      return activo === "1";
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
      this.selected = [];
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
    reset_form() {
      this.form_data.estado = "";
      this.form_error = false;
      this.val = false;
      this.required_error = false;
      this.permiso = true;
      this.add = true;
      this.$v.form_data.$reset();
    },
    async loadData(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const arraySearch = [];
      this.columns.forEach(function (item) {
        if (item.search) {
          arraySearch.push({
            name: item.field,
            value: filter,
          });
        }
      });
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: arraySearch,
      };
      await this.getEtmtransporte(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    async save() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos inválidos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.saveEtmtransporte(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({ pagination: this.pagination, filter: this.filter });
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
        await this.editEtmtransporte(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({pagination: this.pagination, filter: this.filter});
        this.setLoading(false);
      }
    },
    deleteEtmtransportes(selected) {
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteEtmtransporte(this.form_data.id);
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
    estado() {
      var mio = false;
      var mio1 = false;
      for (var i = 0; i < this.rolesNombre.length; i++) {
        if (this.form_data.estado === this.rolesNombre[i].name) {
          mio = true;
        } else {
          mio1 = true;
        }
      }
      if (mio === true && mio1 === false) {
        warningMessage("Ya existe un registro con dicho nombre, escriba otro");
        this.add = false;
      } else {
        if (mio === false && mio1 === true) {
          this.add = true;
        } else {
          if (mio === true && mio1 === true) {
            warningMessage(
              "Ya existe un registro con dicho nombre, escriba otro"
            );
            this.add = false;
          }
        }
      }
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
