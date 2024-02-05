<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
        :grid="xssmallScreen || smallScreen"
          dense
          flat
          table-header-class="text-uppercase"
          :data="usuarios"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          :visible-columns="visibleColumns"
          :pagination.sync="pagination"
          @request="loadData"
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar usuarios ZUNsa
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
              :disable="usuarios.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-ml-sm q-mt-xs"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
          </template>
          <template v-slot:body-cell-action="props">
            <q-td :props="props">
              <div class="q-gutter-sm">
                <q-btn
                  v-if="scopes.includes('gestionar_usuario')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="openModal(true, 'actualizar', props.row)"
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
          <template v-slot:body-cell-activo="props">
            <q-td class="text-center" key="activo">
              <q-badge
                square
                :color="props.row.activo === true ? 'green' : 'red'"
              >
                {{ props.row.activo === true ? 'Habilitado' : 'NO Habilitado' }}
              </q-badge>
            </q-td>
          </template>
          <template v-slot:body-cell-admin="props">
            <q-td class="text-center" key="admin">
              <q-badge
                square
                :color="props.row.admin === true ? 'green' : 'red'"
              >
                {{ props.row.admin === true ? 'Habilitado' : 'NO Habilitado' }}
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
      <q-card style="width: 500px;">
        <q-card-section class="bg-primary">
          <div class="text-h6 text-white">
            {{
              this.accion == 'adicionar'
                ? 'Adicionar Usuario'
                : 'Gestionar Roles'
            }}
          </div>
        </q-card-section>
        <q-card-section class="q-pa-lg">
          <div class="row">
            <div class="col" style="padding: 0px 20px 20px 0px;">
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
                dense
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
          </div>
          <div class="text-red" v-show="form_error">
            Faltan campos por completar
          </div>
        </q-card-section>
        <q-separator inset />
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
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../utils/dialogo';
import {
  infoMessage,
  successMessage,
  warningMessage,
} from '../../utils/notificaciones';
import * as message from '../../utils/resources';
import {
  required,
  sameAs,
  minLength,
  maxLength,
} from 'vuelidate/lib/validators';
import SmallScreen from "../../mixin/SmallScreen";

export default {
  name: 'usuarios',
  mixins:[SmallScreen],
  data() {
    return {
      visibleColumns: [
        'nombre',
        'apellidos',
        'email',
        'admin',
        'activo',
        'action',
      ],
      modalDialog: false,
      spi2: false,
      addButton: true,
      selected: [],
      traza: [],
      userNombre: [],
      scopes: sessionStorage.getItem('scopes'),
      items_persona: [],
      options_role: [],
      roles: [],
      data: [],
      filter: '',
      filterInput: false,
      accion: '',
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      password: null,
      isPwd: true,
      passwordConfirm: null,
      isPwdConfirm: true,
      form_data: {
        email: '',
        persona_id: '',
        role: [],
        nombre: '',
        apellidos: '',
        activo: '0',
        admin: '0',
      },
      form_error: false,
      required_error: false,
      columns: [
        {
          name: 'id',
          label: 'Id',
          align: 'left',
          field: (row) => row.id,
        },
        {
          name: 'nombre',
          label: 'Nombre',
          align: 'left',
          field: 'nombre',
        },
        {
          name: 'apellidos',
          label: 'Apellidos',
          align: 'left',
          field: 'apellidos',
        },
        {
          name: 'email',
          align: 'left',
          label: 'Email',
          field: 'email',
        },
        {
          name: 'admin',
          align: 'left',
          label: 'Admin',
          field: 'admin',
        },
        {
          name: 'activo',
          align: 'left',
          label: 'Activo',
          field: 'activo',
        },
        {
          name: 'action',
          align: 'center',
          label: 'acciones',
          field: 'action',
        },
      ],
    };
  },
  validations: {
    form_data: {
      role: {
        required,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Configuraciones' },
      { label: 'Seguridad' },
      { label: 'Usuarios ZUNsa' },
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
          this.selected[0].roles_sa.forEach(function (item) {
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
    ...mapState('usuarios', ['usuarios']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', [
      'getUsuario',
      'saveUsuario',
      'editUsuario',
      'deleteUsuario',
      'getTotalUsuarios',
      'getNombreUsuarios',
      'resetPasswordUsuario',
    ]),
    ...mapActions('traza', ['getTotalTraza']),
    ...mapActions('usuarios', ['getUsuarios', 'editUsuarios']),
    ...mapActions('persona', ['getActivoPersona']),
    ...mapActions('role', ['getActivoRole']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'role') {
        if (!this.$v.form_data.role.required) {
          this.required_error = true;
          return '';
        }
      }
    },
    typeSelection() {
      return this.scopes.includes('update_usuario') ||
        this.scopes.includes('delete_usuario')
        ? 'single'
        : 'none';
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
    async loadData(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getUsuarios(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    convert(activo) {
      return activo === '1';
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
      this.selected = [];
    },
    openModal(data, accion, rowData) {
      this.accion = accion;
      if (accion === 'adicionar') {
        this.modalDialog = data;
      } else {
        if (accion === 'actualizar') {
          this.addButton = true;
          if (rowData) {
            this.setFormData(rowData);
            this.modalDialog = data;
          } else {
            infoMessage('Debe seleccionar una fila para modificar.');
          }
        }
      }
    },
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data);
    },
    reset_form() {
      this.form_data.codigo = '';
      this.form_data.email = '';
      this.form_data.username = null;
      this.form_data.persona_id = null;
      this.form_data.role = null;
      this.form_data.activo = '0';
      this.accion = null;
      this.form_error = false;
      this.required_error = false;
      this.addButton = true;
      this.$v.form_data.$reset();
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
        await this.saveUsuario(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    async update() {
      this.setLoading(true);
      await this.editUsuarios(this.form_data);
      this.reset_form();
      this.closeModal();
      this.loadData({ pagination: this.pagination, filter: this.filter });
      this.setLoading(false);
    },
  },
};
</script>

<style scoped></style>
