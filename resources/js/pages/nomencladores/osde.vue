<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          :grid="xssmallScreen || smallScreen"
          flat
          dense
          table-header-class="text-uppercase"
          :data="osde"
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
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar osdes
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
              :disable="osde.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('nomencladores')"
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
                  v-if="scopes.includes('nomencladores')"
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
                  v-if="scopes.includes('nomencladores')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteOsdes(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
          <template v-slot:body-cell-activo="props">
            <q-td key="activo" align="left">
              {{ props.row.activo === '1' ? 'Si' : 'No' }}
            </q-td>
          </template>
          <template v-slot:item="props">
            <q-card class="q-ma-xs">
              <q-card-section>
                <q-btn
                  v-if="scopes.includes('nomencladores')"
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
                  v-if="scopes.includes('nomencladores')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteInstalaciones(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </q-card-section>
              <q-separator></q-separator>
              <q-list dense>
                <q-item
                  :key="col.id"
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
        <q-form
          @reset="reset_form"
          @submit="accion != 'adicionar' ? update() : save()"
          ref="formOsde"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                  this.accion == 'adicionar'
                  ? 'Adicionar osde'
                  : 'Actualizar osde'
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
            <q-card-section class="q-px-md">
              <div class="row q-py-sm">
                <div class="col-6">
                  <q-toggle
                    v-model="form_data.activo"
                    label="Activo"
                    name="activo"
                    false-value="0"
                    true-value="1"
                  />
                </div>
                <div class="col-6 q-py-sm">
                  <q-radio
                    dense
                    v-model="form_data.tipo"
                    val="Hotelera"
                    label="Hotelera"
                  />
                  <q-radio
                    class="q-pl-md"
                    dense
                    v-model="form_data.tipo"
                    val="Servicios"
                    label="Servicios"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6 q-px-sm">
                  <q-input
                    v-model="form_data.nombre"
                    type="text"
                    label="Nombre *"
                    name="nombre"
                    debounce="1000"
                    :error-message="mensajesError('nombre')"
                    :error="$v.form_data.nombre.$error"
                    :rules="[val => !!val || 'El campo es requerido', isValidAlphaNum]"
                  />
                </div>
                <div class="col-6 q-px-sm">
                  <q-select
                    v-model="form_data.provincia_id"
                    :options="items_provincia"
                    label="Provincia *"
                    option-value="id"
                    option-label="nombre"
                    options-dense
                    emit-value
                    map-options
                    :error-message="mensajesError('provincia_id')"
                    :error="$v.form_data.provincia_id.$error"
                    :rules="[val => !!val || 'El campo es requerido']"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6 q-px-sm">
                  <q-select
                    v-model="form_data.oace_id"
                    :options="items_oace"
                    label="OACE *"
                    option-value="id"
                    option-label="siglas"
                    options-dense
                    emit-value
                    map-options
                    :error-message="mensajesError('oace_id')"
                    :error="$v.form_data.oace_id.$error"
                    :rules="[val => !!val || 'El campo es requerido']"
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
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../../../resources/js/utils/dialogo';
import { infoMessage, warningMessage,} from '../../../../resources/js/utils/notificaciones';
import * as message from '../../../../resources/js/utils/resources';
import { required, alphaNum } from 'vuelidate/lib/validators';
import SmallScreen from "../../mixin/SmallScreen";

export default {
  name: 'osde',
  mixins:[SmallScreen],
  data() {
    return {
      visibleColumns: ['activo','nombre','tipo','provincia','oace', 'action'],
      filterInput: false,
      modalDialog: false,
      permiso: true,
      val: false,
      selected: [],
      roles: [],
      rolesName: [],
      aux: [],
      add: true,
      scopes: sessionStorage.getItem('scopes'),
      filter: '',
      accion: '',
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      options: [],
      permissions: [],
      items_provincia: [],
      items_oace: [],
      form_data: {
        nombre: '',
        activo: '1',
        codigo: '',
        tipo: 'Hotelera',
        provincia_id: '',
        oace_id: '',
      },
      form_error: false,
      required_error: false,
      columns: [
        {
          name: 'id',
          label: 'Id',
          align: 'center',
          field: (row) => row.id,
        },
        {
          name: 'activo',
          align: 'left',
          label: 'Activo',
          field: 'activo',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'nombre',
          label: 'Nombre',
          align: 'left',
          field: 'nombre',
          search: true,
        },
        // {
        //   name: 'codigo',
        //   align: 'left',
        //   label: 'Código',
        //   field: 'codigo',
        //   search: true,
        // },
        {
          name: 'tipo',
          align: 'left',
          label: 'Tipo',
          field: 'tipo',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'provincia',
          align: 'left',
          label: 'Provincia',
          field: (row) =>
            row.provincia !== undefined ? row.provincia.nombre : '',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'oace',
          align: 'left',
          label: 'Oace',
          field: (row) =>
            row.oace !== undefined ? row.oace.siglas : '',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'action',
          align: 'center',
          label: 'acciones',
          field: 'action',
          headerClasses: 'text-uppercase',
        }
      ],
    };
  },
  validations: {
    form_data: {
      nombre: {
        required
      },
      tipo: {
        required,
      },
      provincia_id: {
        required,
      },
      oace_id: {
        required,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Configuraciones' },
      { label: 'Nomencladores' },
      { label: 'Osdes' },
    ]);
    this.getProvincias().then((r) => {
      this.items_provincia = r;
    });
    this.listOace().then((r) => {
      this.items_oace = r.data.data;
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
    ...mapState('osde', ['osde']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('osde', ['listOsde', 'saveOsde', 'editOsde', 'deleteOsde']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('provincia', ['getProvincias']),
    ...mapActions('oace', ['listOace']),
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
      }
      if (campo === 'tipo') {
        if (!this.$v.form_data.tipo.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'provincia_id') {
        if (!this.$v.form_data.provincia_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'oace_id') {
        if (!this.$v.form_data.oace_id.required) {
          this.required_error = true;
          return '';
        }
      }
    },
    isValidAlphaNum (val) {
      const pattern = /^[a-zA-Z0-9 ]*$/;
      return pattern.test(val) || 'No se aceptan caracteres especiales';
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
      this.form_data.activo = '1';
      this.form_data.codigo = '';
      this.form_data.tipo = 'Hotelera';
      this.form_data.provincia_id = '';
      this.form_data.oace_id = '';
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
      await this.listOsde(data).then((r) => {
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
        infoMessage('Revise los campos requeridos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.saveOsde(this.form_data).then(result => {
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
        await this.editOsde(this.form_data).then(result => {
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
    deleteOsdes(selected) {
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteOsde(this.form_data.id);
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
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
  },
};
</script>

<style scoped></style>
