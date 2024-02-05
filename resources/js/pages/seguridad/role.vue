<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          :grid="xssmallScreen || smallScreen"
          table-header-class="text-uppercase"
          :data="role"
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
          dense
          flat
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar roles
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
              :disable="role.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('seguridad')"
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
              <div class="q-gutter-xs">
                <q-btn
                  v-if="scopes.includes('seguridad')"
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
                  v-if="scopes.includes('seguridad')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRoles(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
          <template v-slot:item="props">
            <q-card class="q-ma-xs">
              <q-card-section>
                <q-btn
                  v-if="scopes.includes('seguridad')"
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
                  v-if="scopes.includes('seguridad')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRoles(props.row)"
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
          <template v-slot:body-cell-enabled="props">
            <q-td class="text-center" key="enabled">
              <q-badge
                square
                :color="props.row.enabled === '1' ? 'green' : 'red'"
              >
                {{ props.row.enabled === "1" ? "Habilitado" : "NO Habilitado" }}
              </q-badge>
            </q-td>
          </template>
          <template v-slot:body-cell-created_at="props">
            <q-td :props="props">
              <span>{{ new Date(props.value).toLocaleString() }}</span>
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
                this.accion == "adicionar" ? "Adicionar rol" : "Actualizar rol"
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-form
          @reset="reset_form"
          @submit="accion != 'adicionar' ? update() : save()"
          ref="myform"
        >
          <q-card-section>
            <q-scroll-area style="height: 250px; max-width: 600px">
              <div class="row items-center">
                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 q-px-sm">
                  <!--<q-input
                v-model="form_data.name"
                type="text"
                label="Nombre"
                dense
                name="name"
                :error-message="mensajesError('name')"
                :error="$v.form_data.name.$error"
                @input="nombre"
                debounce="1000"
                autogrow
                @keydown="nameKeydown($event)"
                lazy-rules
                :rules="[characterSpecial]"
                hint="No se admiten caracteres como: ?!)$@#%^"
              />-->
                  <q-input
                    v-model="form_data.name"
                    type="text"
                    label="Nombre"
                    dense
                    name="name"
                    :error-message="mensajesError('name')"
                    :error="$v.form_data.name.$error"
                    @input="nombre"
                    debounce="1000"
                    autogrow
                    lazy-rules
                    :rules="[characterSpecial]"
                    hint="No se admiten caracteres como: ?!)$@#%^"
                    maxlength="190"
                  />
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 q-px-sm">
                  <q-toggle
                    v-model="form_data.enabled"
                    label="Disponible"
                    name="activo"
                    false-value="0"
                    true-value="1"
                  />
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 q-px-sm">
                  <q-input
                    v-model="form_data.description"
                    type="textarea"
                    label="Descripción"
                    dense
                    name="description"
                    autogrow
                    lazy-rules
                    :rules="[characterSpecial]"
                    hint="No se admiten caracteres como: ?!)$@#%^"
                  />
                </div>
                <div
                  class="col-xs-12 col-sm-12 col-md-12 col-lg-12 q-px-sm"
                  style="margin-bottom: 14px"
                >
                  <q-select
                    v-model="form_data.permiso"
                    input-debounce="0"
                    use-input
                    @filter="filterFn"
                    hint="Cantidad Permisos"
                    counter
                    multiple
                    emit-value
                    map-options
                    options-dense
                    :options="Object.freeze(options)"
                    label="Permiso"
                    option-label="visible_name"
                    option-value="id"
                    use-chips
                    :error-message="mensajesError('permiso')"
                    :error="$v.form_data.permiso.$error"
                    v-show="permiso"
                  >
                    <template v-slot:no-option>
                      <q-item>
                        <q-item-section class="text-grey">
                          No hay resultados
                        </q-item-section>
                      </q-item>
                    </template>
                  </q-select>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <q-toggle
                    v-model="val"
                    label="Todos los permisos"
                    @input="todosPermisos"
                  />
                </div>
                <div class="text-red" v-show="form_error">
                  Faltan campos por completar
                </div>
              </div>
            </q-scroll-area>

            <q-card-actions align="right">
              <q-btn
                type="submit"
                icon="save"
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
          </q-card-section>
        </q-form>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { showDialog } from "../../utils/dialogo";
import { infoMessage, warningMessage } from "../../utils/notificaciones";
import * as message from "../../utils/resources";
import { required, maxLength } from "vuelidate/lib/validators";
import SmallScreen from "../../mixin/SmallScreen";

export default {
  name: "role",
  mixins: [SmallScreen],
  data() {
    return {
      visibleColumns: ["codigo", "name", "description", "action"],
      modalDialog: false,
      permiso: true,
      val: false,
      selected: [],
      roles: [],
      rolesName: [],
      aux: [],
      add: true,
      scopes: sessionStorage.getItem("scopes"),
      filterInput: false,
      filter: "",
      accion: "",
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      characterSpecial: (val) =>
        /^[a-zA-Z0-9\s.]*$/.test(val) || "Por favor, escriba algo válido",
      required: (val) => !!val || "Por favor, escriba algo",
      options: [],
      permissions: [],
      form_data: {
        name: "",
        permiso: [],
        description: "",
        enabled: "0",
      },
      form_error: false,
      required_error: false,
      columns: [
        {
          name: "id",
          label: "Id",
          align: "left",
          field: (row) => row.id,
        },
        {
          name: "codigo",
          label: "Código",
          align: "left",
          field: "codigo",
          search: true,
        },
        {
          name: "name",
          label: "Nombre",
          align: "left",
          field: "name",
          search: true,
        },
        {
          name: "description",
          align: "left",
          label: "Descripción",
          field: "description",
          search: true,
        },
        {
          name: "enabled",
          align: "center",
          label: "Activo",
          field: "enabled",
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
      name: {
        required,
        maxLength: maxLength(30),
      },
      permiso: {
        required,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Configuraciones" },
      { label: "Seguridad" },
      { label: "Roles" },
    ]);
    this.getRolePermission().then((r) => {
      this.permissions = this.options = r;
      const auxPermission = [];
      this.permissions.forEach(function (item) {
        auxPermission.push(item.id);
      });
      this.aux = auxPermission;
    });
    this.getTotalUsuariosRol().then((r) => {
      this.roles = r;
    });
  },
  watch: {
    selected: {
      handler() {
        const app = this;
        if (this.selected.length > 0) {
          const array = [];
          this.selected[0].permisos.forEach(function (item) {
            array.push(item.permission_id);
          });
          app.form_data.permiso = array;
        }
      },
    },
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
    ...mapState("role", ["role"]),
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("usuario", ["getTotalUsuariosRol"]),
    ...mapActions("role", [
      "getRole",
      "saveRole",
      "editRole",
      "deleteRole",
      "getRolePermission",
      "getNombreRole",
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
      if (campo === "name") {
        if (!this.$v.form_data.name.required) {
          this.required_error = true;
          return "";
        }
        if (!this.$v.form_data.name.maxLength) {
          this.required_error = false;
          return "El nombre solo puede tener 30 carácteres";
        }
      }
      if (campo === "permiso") {
        if (!this.$v.form_data.permiso.required) {
          this.required_error = true;
          return "";
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
            for (let obj of rowData.permisos) {
              this.form_data.permiso.push(obj.permission_id);
            }
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
      this.form_data.codigo = "";
      this.form_data.name = "";
      this.form_data.description = "";
      //this.form_data.permiso = null;
      this.form_data.permiso = [];
      this.form_data.enabled = "0";
      this.form_error = false;
      this.val = false;
      this.required_error = false;
      this.permiso = true;
      this.add = true;
      this.$v.form_data.$reset();
    },
    async loadData(props) {
      this.getNombreRole().then((r) => {
        this.rolesNombre = r;
      });
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
      await this.getRole(data).then((r) => {
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
        infoMessage("Revise los campos inválidos");
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.saveRole(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    async update() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage("Revise los campos inválidos");
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.editRole(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    deleteRoles(selected) {
      let ban = false;
      for (var e = 0; e < this.roles.length; e++) {
        if (selected.id === this.roles[e].role_id) {
          ban = true;
        }
      }
      if (ban === true) {
        warningMessage(message.usoElementWarningRow);
      } else {
        if (selected) {
          this.setFormData(selected);
          showDialog(message.lAskForRemove).onOk(async () => {
            this.setLoading(true);
            await this.deleteRole(this.form_data.id);
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
      }
    },
    nombre() {
      var mio = false;
      var mio1 = false;
      for (var i = 0; i < this.rolesNombre.length; i++) {
        if (this.form_data.name === this.rolesNombre[i].name) {
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
    todosPermisos() {
      this.setLoading(true);
      if (this.val === true) {
        this.form_data.permiso = this.aux;
        this.permiso = false;
        this.setLoading(false);
      } else {
        this.permiso = true;
        this.form_data.permiso = [];
        this.setLoading(false);
      }
    },

    nameKeydown(e) {
      /* if (/^\W$/.test(e.key)) {
        e.preventDefault();
      }*/
      /*  var pattern = /^\w|\s$/;
      if (pattern.test(e.key)) {
        return true;
      } else {
        e.preventDefault();
      }*/
    },
  },
};
</script>

<style scoped></style>
