<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          :grid="xssmallScreen || smallScreen"
          table-header-class="text-uppercase"
          :data="usuario"
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
              Gestionar usuarios
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
              :disable="usuario.length != 0 ? false : true"
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
                  @click.prevent="deleteUsuarios(props.row)"
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
                  @click.prevent="deleteUsuarios(props.row)"
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
          <template v-slot:body-cell-activo="props">
            <q-td class="text-center" key="activo">
              <q-badge
                square
                :color="props.row.activo === '1' ? 'green' : 'red'"
              >
                {{ props.row.activo === "1" ? "Habilitado" : "NO Habilitado" }}
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
                this.accion == "adicionar"
                  ? "Adicionar usuario"
                  : "Actualizar usuario"
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <q-scroll-area style="height: 304px; max-width: 600px">
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 q-px-sm">
                <q-input
                  v-model="form_data.username"
                  type="text"
                  label="Nombre"
                  name="username"
                  :error-message="mensajesError('username')"
                  :error="$v.form_data.username.$error"
                  autogrow
                />
              </div>
              <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 q-px-sm toggle">
                <q-toggle
                  v-model="form_data.activo"
                  label="Disponible"
                  name="activo"
                  false-value="0"
                  true-value="1"
                />
              </div>
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
                <q-select
                  v-model="form_data.persona_id"
                  emit-value
                  map-options
                  options-dense
                  :options="items_persona"
                  label="Personas"
                  option-label="nombre_completo"
                  option-value="id"
                  @input="changeEmail"
                  :error-message="mensajesError('persona_id')"
                  :error="$v.form_data.persona_id.$error"
                />
              </div>
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
                <q-input
                  v-model="form_data.email"
                  type="text"
                  label="Correo"
                  name="email"
                  disable
                />
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 q-px-sm">
                <q-select
                  v-model="form_data.role"
                  input-debounce="0"
                  use-input
                  @filter="filterFn"
                  hint="Cantidad Permisos"
                  counter
                  multiple
                  emit-value
                  map-options
                  options-dense
                  :options="Object.freeze(options_role)"
                  label="Roles"
                  option-label="name"
                  option-value="id"
                  use-chips
                  :error-message="mensajesError('role')"
                  :error="$v.form_data.role.$error"
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
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 q-px-sm">
                <q-select
                  v-model="form_data.instalacion_id"
                  emit-value
                  map-options
                  options-dense
                  :options="items_instalacion"
                  label="Instalciones"
                  option-label="nombre"
                  option-value="id"
                  :error-message="mensajesError('instalacion_id')"
                  :error="$v.form_data.instalacion_id.$error"
                />
              </div>
            </div>
            <div class="text-red" v-show="form_error">
              Falan campos por completar.
            </div>
          </q-scroll-area>
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
            v-show="addButton"
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
    <div class="q-pa-md">
      <div class="q-gutter-md row items-center">
        <q-inner-loading :showing="spi2">
          <q-spinner-pie color="green" v-show="spi2" size="5.5em" />
        </q-inner-loading>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { showDialog } from "../../utils/dialogo";
import {
  infoMessage,
  successMessage,
  warningMessage,
} from "../../utils/notificaciones";
import * as message from "../../utils/resources";
import {
  required,
  sameAs,
  minLength,
  maxLength,
} from "vuelidate/lib/validators";
import SmallScreen from "../../mixin/SmallScreen";

export default {
  name: "usuario",
  mixins: [SmallScreen],
  data() {
    return {
      visibleColumns: [
        "codigo",
        "username",
        "correo",
        "persona",
        "instalacion",
        "action",
      ],
      modalDialog: false,
      spi2: false,
      addButton: true,
      selected: [],
      traza: [],
      userNombre: [],
      scopes: sessionStorage.getItem("scopes"),
      items_persona: [],
      items_instalacion: [],
      options_role: [],
      roles: [],
      filter: "",
      filterInput: false,
      accion: "",
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      password: null,
      isPwd: true,
      passwordConfirm: null,
      isPwdConfirm: true,
      form_data: {
        username: "",
        email: "",
        persona_id: "",
        instalacion_id: "",
        // password: '',
        // passwordConfirm: '',
        role: [],
        activo: "0",
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
        },
        {
          name: "username",
          label: "Usuario",
          align: "left",
          field: "username",
        },
        {
          name: "persona",
          align: "left",
          label: "Persona",
          field: (row) =>
            row.persona !== undefined ? row.persona.nombre_completo : "",
        },
        {
          name: "correo",
          align: "left",
          label: "Correo",
          field: (row) => (row.persona !== undefined ? row.persona.correo : ""),
        },
        {
          name: "instalacion",
          align: "left",
          label: "Instalación",
          field: (row) =>
            row.instalacion !== undefined ? row.instalacion.nombre : "",
        },
        {
          name: "activo",
          align: "left",
          label: "Activo",
          field: "activo",
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
      username: {
        required,
        maxLength: maxLength(30),
      },
      persona_id: {
        required,
      },
      instalacion_id: {
        required,
      },
      role: {
        required,
      },
    },
  },
  mounted() {
    this.getActivoPersona().then((r) => {
      this.items_persona = r;
    });
    this.getInstalaciones().then((r) => {
      this.items_instalacion = r;
    });
    this.getActivoRole().then((r) => {
      this.options_role = this.roles = r;
    });
    this.getTotalTraza().then((r) => {
      this.traza = r;
    });
    this.getTotalUsuarios().then((r) => {
      this.userPersona = r;
    });
    this.addToHomeBreadcrumbs([
      { label: "Configuraciones" },
      { label: "Seguridad" },
      { label: "Usuarios" },
    ]);
    this.getActivoRole().then((r) => {
      this.options_role = this.roles = r;
    });
    this.getTotalTraza().then((r) => {
      this.traza = r;
    });
  },
  watch: {
    selected: {
      handler() {
        const app = this;
        if (this.selected.length > 0) {
          const array = [];
          this.selected[0].roles.forEach(function (item) {
            array.push(item.role_id);
          });

          this.form_data.role = array;
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
    ...mapState("usuario", ["usuario"]),
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("usuario", [
      "getUsuario",
      "saveUsuario",
      "editUsuario",
      "deleteUsuario",
      "getTotalUsuarios",
      "getNombreUsuarios",
      "resetPasswordUsuario",
    ]),
    ...mapActions("traza", ["getTotalTraza"]),
    ...mapActions("persona", ["getActivoPersona"]),
    ...mapActions("instalacion", ["getInstalaciones"]),
    ...mapActions("role", ["getActivoRole"]),
    ...mapActions("utils", ["setLoading"]),
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),

    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === "username") {
        if (!this.$v.form_data.username.required) {
          this.required_error = true;
          return "";
        }
        if (!this.$v.form_data.username.maxLength) {
          this.required_error = false;
          return "El nombre de usuario solo puede tener 30 carácteres";
        }
      }
      if (campo === "persona_id") {
        if (!this.$v.form_data.persona_id.required) {
          this.required_error = true;
          return "";
        }
      }
      if (campo === "instalacion_id") {
        if (!this.$v.form_data.instalacion_id.required) {
          this.required_error = true;
          return "";
        }
      }
      if (campo === "role") {
        if (!this.$v.form_data.role.required) {
          this.required_error = true;
          return "";
        }
      }
    },
    typeSelection() {
      return this.scopes.includes("update_usuario") ||
        this.scopes.includes("delete_usuario")
        ? "single"
        : "none";
    },
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    filterFn(val, update) {
      update(() => {
        const needle = val.toLowerCase();
        this.options_role = this.roles.filter(
          (v) => v.name.toLowerCase().indexOf(needle) > -1
        );
      });
    },
    onRowClick(evt, row) {
      if (this.selected.length === 0) {
        this.selected.push(row);
      } else if (
        this.selected.length === 1 &&
        this.selected[0].codigo !== row.codigo
      ) {
        this.selected = [];
        this.selected.push(row);
      } else if (
        this.selected.length === 1 &&
        this.selected[0].codigo === row.codigo
      ) {
        this.selected = [];
      }
    },
    changeEmail(value) {
      let ban = false;
      for (var e = 0; e < this.userPersona.length; e++) {
        if (this.form_data.persona_id === this.userPersona[e].persona_id) {
          ban = true;
        }
      }
      if (this.selected.length > 0) {
        if (
          ban === true &&
          this.selected[0].persona_id !== this.form_data.persona_id
        ) {
          this.addButton = false;
          warningMessage(message.personaExistente);
        } else {
          this.addButton = true;
          successMessage(message.personaDisponible);
          const app = this;
          this.items_persona.forEach(function (item) {
            if (item.id === value) {
              app.form_data.email = item.correo;
            }
          });
        }
      } else {
        if (ban === true) {
          this.addButton = false;
          warningMessage(message.personaExistente);
        } else {
          this.addButton = true;
          successMessage(message.personaDisponible);
          const app = this;
          this.items_persona.forEach(function (item) {
            if (item.id === value) {
              app.form_data.email = item.correo;
            }
          });
        }
      }
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
          this.addButton = true;
          if (rowData) {
            for (let obj of rowData.roles) {
              this.form_data.role.push(obj.role_id);
            }
            this.setFormData(rowData);
            if (this.form_data.persona_id === rowData.persona.id)
              this.form_data.email = rowData.persona.correo;
            this.modalDialog = data;
          } else {
            infoMessage("Debe seleccionar una fila para modificar.");
          }
        }
      }
    },
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data);
      if (this.accion === "actualizar") {
        this.form_data.password = null;
      }
    },
    reset_form() {
      this.form_data.codigo = "";
      this.form_data.email = "";
      this.form_data.username = null;
      this.form_data.persona_id = null;
      this.form_data.instalacion_id = null;
      //this.form_data.role = null;
      this.form_data.role = [];
      // this.form_data.password = null
      // this.form_data.passwordConfirm = null
      this.form_data.activo = "0";
      this.accion = null;
      this.form_error = false;
      this.required_error = false;
      this.addButton = true;
      this.$v.form_data.$reset();
    },
    async loadData(props) {
      this.getNombreUsuarios().then((r) => {
        this.userNombre = r;
      });
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getUsuario(data).then((r) => {
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
        await this.saveUsuario(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    async update() {
      this.$v.form_data.$touch();
      if (
        this.$v.form_data.username.$invalid ||
        this.$v.form_data.role.$invalid ||
        this.$v.form_data.persona_id.$invalid
      ) {
        infoMessage("Revise los campos inválidos");
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.editUsuario(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    deleteUsuarios(selected) {
      let ban = false;
      for (var e = 0; e < this.traza.length; e++) {
        if (selected === this.traza[e].usuario_id) {
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
            await this.deleteUsuario(this.form_data.id);
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
    reiniciarPassword() {
      this.spi2 = true;
      this.resetPasswordUsuario({ id: this.selected[0].id }).then((r) => {
        if (r.success) {
          this.selected = [];
          this.spi2 = false;
        } else {
          this.spi2 = false;
        }
      });
    },
    // async logueoSA() {
    //     const zsa = client();
    //     try {
    //         const usuarioSA = await zsa.session.login({
    //             username: this.usuario, //'mkadmin '
    //             password: this.password, //'ZunmkAdminPrueba*2020'
    //         });
    //         this.$q.sessionStorage.set('session', usuarioSA)
    //         console.log(usuarioSA);
    //         this.$router.replace('/')
    //         // const users = await zsa.admin.getUsers(99);
    //         // console.log(users);
    //     } catch (err) {
    //         let pepe = err.toString()
    //         let pepa = ''
    //         pepa = pepe.substring(0, 5)
    //         pepe = pepe.substring(5)
    //         this.$q.notify(
    //             {
    //                 message: '<span class="text-orange-6 text-bold">' + '' + pepa + '' + '</span>' + '<span class="text-white text-bold">' + pepe + '</span>',
    //                 textColor: 'warning',
    //                 icon: 'error',
    //                 html: true,
    //                 position: "top"
    //             })
    //     }
    // },
  },
};
</script>

<style scoped>
.toggle {
  margin-top: 33px;
}
@media screen and (max-width: 1023px) {
  .toggle {
    margin-top: 0px;
  }
}
</style>
