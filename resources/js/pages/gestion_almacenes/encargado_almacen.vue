<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          table-header-class="text-uppercase"
          :data="encargado"
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
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar encargado de almacen
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
              :disable="encargado.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_almacenes')"
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
                  v-if="scopes.includes('gestionar_almacenes')"
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
                  v-if="scopes.includes('gestionar_almacenes')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteEncargados(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>

          <template v-slot:body-cell-capacitacion="props">
            <q-td rops="props" align="left">
              {{ props.row.capacitacion === 'Si' ? 'Si' : 'No' }}
            </q-td>
          </template>
          <template v-slot:body-cell-almacen_id="props">
            <q-td rops="props" align="left">
              {{ props.row.almacen_id === null ? '' : props.row.almacenes.nombre }}
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
        <q-toolbar class="bg-primary text-white">
          <q-toolbar-title>
            {{
              this.accion == 'adicionar'
                ? 'Adicionar encargado de almacen'
                : 'Actualizar encargado de almacen'
            }}
          </q-toolbar-title>
          <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
            <q-tooltip>Cerrar</q-tooltip>
          </q-btn>
        </q-toolbar>
        <q-card-section class="q-px-md">
          <div class="row">
            <div class="col-6">
              <q-input dense v-model="form_data.nombre" label="Nombre *" name="nombre"
                       :error-message="mensajesError('nombre')"
                       :error="$v.form_data.nombre.$error"
              />
            </div>
            <div class="col-6 q-pl-sm">
              <q-input dense v-model="form_data.apellidos" label="Apellidos *" name="apellidos"
                       :error-message="mensajesError('apellidos')"
                       :error="$v.form_data.apellidos.$error"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-input dense v-model="form_data.email" type="email" label="Email" name="email"
                       :rules="[val => !!val.includes('@') || 'Incluye un signo @']"
                       :error-message="mensajesError('email')"
                       :error="$v.form_data.email.$error"/>
            </div>
            <div class="col-6 q-pl-sm">
              <q-input v-model="form_data.telefono" label="Teléfono *" dense name="telefono"
                       type="number"
                       :error-message="mensajesError('telefono')"
                       :error="$v.form_data.telefono.$error" min="0"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-select dense v-model="form_data.capacitacion" :options="options"
                        label="Capacitación *" options-dense emit-value map-options
                        :error-message="mensajesError('capacitacion')"
                        :error="$v.form_data.capacitacion.$error"/>
            </div>
            <div class="col-6 q-pl-sm">
              <q-select dense v-model="form_data.almacen_id" hide-bottom-space
                        emit-value map-options options-dense :options="items_almacen"
                        label="Almacen" option-label="nombre" option-value="id"/>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-uploader flat bordered hide-upload-btn label="Imagen de usuario" accept=".jpg, image/*"
                          style="max-width: 290px"
                          @rejected="onRejected" @added="encodeImageFileAsURL"/>
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
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../../../resources/js/utils/dialogo';
import {  infoMessage, warningMessage } from '../../../../resources/js/utils/notificaciones';
import * as message from '../../../../resources/js/utils/resources';
import { required, alpha, numeric, email } from 'vuelidate/lib/validators';

export default {
  name: 'encargados',
  data() {
    return {
      visibleColumns: ['nombre', 'apellidos', 'email', 'telefono', 'capacitacion',
        'almacen_id', 'action'],
      modalDialog: false,
      filterInput: false,
      permiso: true,
      val: false,
      selected: [],
      aux: [],
      add: true,
      scopes: sessionStorage.getItem('scopes'),
      filter: '',
      accion: '',
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      options: ['Si', 'No'],
      permissions: [],
      items_almacen: [],
      form_data: {
        nombre: '',
        apellidos: '',
        email: '',
        telefono: '',
        capacitacion: '',
        foto: '',
        almacen_id: ''
      },
      form_error: false,
      required_error: false,
      columns: [
        {
          name: 'nombre',
          label: 'Nombre',
          align: 'left',
          field: 'nombre',
          search: true
        },
        {
          name: 'apellidos',
          label: 'Apellidos',
          align: 'left',
          field: 'apellidos',
          search: true
        },
        {
          name: 'email',
          align: 'left',
          label: 'Correo',
          field: 'email',
          search: true
        },
        {
          name: 'telefono',
          align: 'left',
          label: 'telefono',
          field: 'telefono',
          search: true
        },
        {
          name: 'capacitacion',
          label: 'Capacitación',
          align: 'left',
          field: 'capacitacion',
          search: true
        },
        {
          name: 'almacen_id',
          align: 'left',
          label: 'Almacen',
          field: row => row.almacenes !== undefined ? row.almacenes.nombre : ''
        },
        {
          name: 'action',
          align: 'center',
          label: 'acciones',
          field: 'action',
        }
      ],
    };
  },
  validations: {
    form_data: {
      nombre: {
        required, alpha
      },
      apellidos: {
        required, alpha
      },
      email: {
        required, email
      },
      telefono: {
        required, numeric
      },
      capacitacion: {
        required
      }
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Logística' },
      { label: 'Almacenes' },
      { label: 'Encargados de almacenes' },
    ]);
    this.getAlmacenes().then((r) => {
      this.items_almacen = r;
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
    ...mapState('encargado_almacen', ['encargado']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('almacen', ['getAlmacenes']),
    ...mapActions('encargado_almacen', [
      'getEncargado',
      'saveEncargado',
      'editEncargado',
      'deleteEncargado',
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
      if (campo === 'apellidos') {
        if (!this.$v.form_data.apellidos.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.apellidos.alpha) {
          this.required_error = true;
          return 'Solo se aceptan caracteres alfabéticos';
        }
      }
      if (campo === 'email') {
        if (!this.$v.form_data.email.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.email.email) {
          this.required_error = true;
          return 'Escriba una dirección de correo electrónico válida';
        }
      }
      if (campo === 'telefono') {
        if (!this.$v.form_data.telefono.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.telefono.numeric) {
          this.required_error = false;
          return 'Solo se aceptan números';
        }
      }
      if (campo === 'capacitacion') {
        if (!this.$v.form_data.capacitacion.required) {
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
    convert(activo) {
      return activo === '1';
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
      this.selected = [];
    },
    encodeImageFileAsURL(files) {
      const vm = this
      const reader = new FileReader()
      reader.onloadend = function () {
        vm.form_data.foto = reader.result
      }
      reader.readAsDataURL(files[0])
    },
    onRejected() {
      errorMessage('Formato no valido')
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
      this.form_data.nombre = '';
      this.form_data.apellidos = '';
      this.form_data.email = '';
      this.form_data.telefono = '';
      this.form_data.capacitacion = '';
      this.form_data.foto = '';
      this.form_data.almacen_id = '';
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
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getEncargado(data).then((r) => {
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
        await this.saveEncargado(this.form_data).then(result => {
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
        await this.editEncargado(this.form_data).then(result => {
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
    deleteEncargados(selected) {
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteEncargado(this.form_data.id);
          this.reset_form();
          await this.loadData({
            pagination: this.pagination,
            filter: this.filter,
          });
          this.setLoading(false);
        });
      } else {
        infoMessage('Debe seleccionar la fila a eliminar.');
      }
    },
  },
};
</script>

<style scoped></style>
