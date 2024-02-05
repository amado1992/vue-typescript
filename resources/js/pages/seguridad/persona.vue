<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
        :grid="xssmallScreen || smallScreen"
          table-header-class="text-uppercase"
          :data="persona"
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
              Gestionar personas
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
              :disable="persona.length != 0 ? false : true"
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
                  @click.prevent="deletePersonas(props.row)"
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
                  @click.prevent="deletePersonas(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
                                   
                                </q-card-section>
                                <q-separator></q-separator>
                                <q-list dense>
                                    <q-item :key="col.id"
                                            

                                            v-for="col in props.cols"
                                           >
                                        <q-item-section>
                                            <q-item-label  v-if="col.field != 'action'">{{ col.label }}</q-item-label>
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
                {{ props.row.activo === '1' ? 'Habilitado' : 'NO Habilitado' }}
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
      <q-card style="width: 600px; max-width: 70vw;">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              {{
                this.accion == 'adicionar'
                  ? 'Adicionar persona'
                  : 'Actualizar persona'
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
           <!--<q-scroll-area style="height:250px; max-width: 600px;">-->
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
              <q-input
                v-model="form_data.nombre_completo"
                type="text"
                label="Nombre Completo"
                name="nombre_completo"
                :error-message="mensajesError('nombre_completo')"
                :error="$v.form_data.nombre_completo.$error"
                @input="nombre"
                debounce="1000"
                autogrow
              />
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
              <q-input
                v-model="form_data.correo"
                type="text"
                label="Correo"
                name="correo"
                :error-message="mensajesError('correo')"
                :error="$v.form_data.correo.$error"
                @input="correo"
                debounce="1000"
                autogrow
              />
            </div>
          <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 q-px-sm">
              <q-input
                v-model="form_data.telefono"
                type="text"
                label="Teléfono"
                name="telefono"
                :error-message="mensajesError('telefono')"
                :error="$v.form_data.telefono.$error"
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
          <div class="text-red" v-show="form_error">
            Faltan campos por llenar
          </div>
          </div>
          <!--</q-scroll-area>-->
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
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../utils/dialogo';
import { infoMessage, warningMessage } from '../../utils/notificaciones';
import * as message from '../../utils/resources';
import {
  required,
  email,
  minLength,
  maxLength,
  numeric,
} from 'vuelidate/lib/validators';
import SmallScreen from "../../mixin/SmallScreen";

export default {
  name: 'persona',
  mixins:[SmallScreen],
  data() {
    return {
      visibleColumns: [
        'codigo',
        'nombre_completo',
        'correo',
        'telefono',
        'action',
      ],
      modalDialog: false,
      selected: [],
      add: true,
      personas: [],
      personasNombre: [],
      personasCorreo: [],
      scopes: sessionStorage.getItem('scopes'),
      filter: '',
      filterInput: false,
      accion: '',
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      form_data: {
        nombre_completo: '',
        correo: '',
        telefono: '',
        activo: '0',
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
          name: 'codigo',
          label: 'Código',
          align: 'left',
          field: 'codigo',
          search: true,
        },
        {
          name: 'nombre_completo',
          label: 'Nombre Completo',
          align: 'left',
          field: 'nombre_completo',
          search: true,
        },
        {
          name: 'correo',
          align: 'left',
          label: 'Correo',
          field: 'correo',
          search: true,
        },
        {
          name: 'telefono',
          align: 'left',
          label: 'Teléfono',
          field: 'telefono',
          search: true,
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
      telefono: {
        required,
        minLength: minLength(7),
        maxLength: maxLength(12),
        numeric,
      },
      nombre_completo: {
        required,
        maxLength: maxLength(30),
      },
      correo: {
        required,
        email,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Configuraciones' },
      { label: 'Seguridad' },
      { label: 'Personas' },
    ]);
    this.getTotalUsuarios().then((r) => {
      this.personas = r;
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
    ...mapState('persona', ['persona']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuarios']),
    ...mapActions('persona', [
      'getPersona',
      'savePersona',
      'editPersona',
      'deletePersona',
      'getNombrePersona',
      'getCorreoPersona',
    ]),
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
      if (campo === 'telefono') {
        if (!this.$v.form_data.telefono.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.telefono.minLength) {
          this.required_error = false;
          return 'Debe tener más de 7 dígitos';
        }
        if (!this.$v.form_data.telefono.maxLength) {
          this.required_error = false;
          return 'Debe tener menos de 12 dígitos';
        }
        if (!this.$v.form_data.telefono.numeric) {
          this.required_error = false;
          return 'Solo puede insertar números';
        }
      }
      if (campo === 'correo') {
        if (!this.$v.form_data.correo.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.correo.email) {
          this.required_error = false;
          return 'Formato de correo invalido';
        }
      }
      if (campo === 'nombre_completo') {
        if (!this.$v.form_data.nombre_completo.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.nombre_completo.maxLength) {
          this.required_error = false;
          return 'El nombre solo puede tener 30 carácteres';
        }
      }
    },
    typeSelection() {
      return this.scopes.includes('update_persona') ||
        this.scopes.includes('delete_persona')
        ? 'single'
        : 'none';
    },
    convert(activo) {
      return activo === '1';
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
      this.selected = [];
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
    openModal(data, accion, rowData) {
      this.accion = accion;
      if (accion === 'adicionar') {
        this.modalDialog = data;
      } else {
        if (accion === 'actualizar') {
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
      this.form_data.nombre_completo = '';
      this.form_data.correo = '';
      this.form_data.telefono = '';
      this.form_data.activo = '0';
      this.form_error = false;
      this.required_error = false;
      this.add = true;
      this.$v.form_data.$reset();
    },
    async loadData(props) {
      this.getNombrePersona().then((r) => {
        this.personasNombre = r;
      });
      this.getCorreoPersona().then((p) => {
        this.personasCorreo = p;
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
      await this.getPersona(data).then((r) => {
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
        await this.savePersona(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    async update() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos inválidos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.editPersona(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    deletePersonas(selected) {
      let ban = false;
      for (var e = 0; e < this.personas.length; e++) {
        if (selected.id === this.personas[e].persona_id) {
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
            await this.deletePersona(this.form_data.id);
            this.reset_form();
            this.selected = [];
            await this.loadData({
              pagination: this.pagination,
              filter: this.filter,
            });
            this.setLoading(false);
          });
        } else {
          infoMessage('Debe seleccionar la fila a eliminar.');
        }
      }
    },
    nombre() {
      var mio = false;
      var mio1 = false;
      for (var i = 0; i < this.personasNombre.length; i++) {
        if (
          this.form_data.nombre_completo ===
          this.personasNombre[i].nombre_completo
        ) {
          mio = true;
        } else {
          mio1 = true;
        }
      }
      if (mio === true && mio1 === false) {
        warningMessage('Ya existe un registro con dicho nombre, escriba otro');
        this.add = false;
      } else {
        if (mio === false && mio1 === true) {
          this.add = true;
        } else {
          if (mio === true && mio1 === true) {
            warningMessage(
              'Ya existe un registro con dicho nombre, escriba otro'
            );
            this.add = false;
          }
        }
      }
    },
    correo() {
      var mio = false;
      var mio1 = false;
      for (var i = 0; i < this.personasCorreo.length; i++) {
        if (this.form_data.correo === this.personasCorreo[i].correo) {
          mio = true;
        } else {
          mio1 = true;
        }
      }
      if (mio === true && mio1 === false) {
        warningMessage('Ya existe un registro con dicho correo, escriba otro');
        this.add = false;
      } else {
        if (mio === false && mio1 === true) {
          this.add = true;
        } else {
          if (mio === true && mio1 === true) {
            warningMessage(
              'Ya existe un registro con dicho correo, escriba otro'
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
.toggle {
      margin-top: 33px; 
 
    } 
@media screen and (max-width: 1023px) { 
 
    .toggle {
        margin-top: 0px; 
 
    } 
    }
</style>
