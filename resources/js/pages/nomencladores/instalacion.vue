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
          :data="instalacion"
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
              Gestionar instalación
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
              :disable="instalacion.length != 0 ? false : true"
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
                  @click.prevent="deleteInstalaciones(props.row)"
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
          <template v-slot:body-cell-fechaRegistro="props">
            <q-td :props="props">
              <span>{{ moment(props.value).format('L') }}</span>
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
          ref="formInstalacion"
        >
        <q-card-section class="no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              {{
                this.accion == 'adicionar'
                  ? 'Adicionar instalación'
                  : 'Actualizar instalación'
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
              <q-toggle
                v-model="form_data.activo"
                label="Activo"
                name="activo"
                false-value="0"
                true-value="1"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
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
              <q-input
                v-model="form_data.codigo"
                type="text"
                label="Código *"
                name="codigo"
                debounce="1000"
                :error-message="mensajesError('codigo')"
                :error="$v.form_data.codigo.$error"
                :rules="[val => !!val || 'El campo es requerido', isValidAlphaNum]"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
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
            <div class="col-6 q-px-sm">
              <q-select
                v-model="form_data.osde_id"
                :options="items_osde"
                label="Osde *"
                option-value="id"
                option-label="nombre"
                options-dense
                emit-value
                map-options
                :error-message="mensajesError('osde_id')"
                :error="$v.form_data.osde_id.$error"
                :rules="[val => !!val || 'El campo es requerido']"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-input
                label="Complejo"
                v-model="form_data.complejo"
                type="textarea"
                name="complejo"
                autogrow
              />
            </div>
            <div class="col-6 q-px-sm">
              <q-select
                v-model="form_data.tipoInst_id"
                hide-bottom-space
                emit-value
                map-options
                options-dense
                :options="items_tipoInsta"
                label="Tipo de Instalación *"
                option-label="nombre"
                option-value="id"
                :error-message="mensajesError('tipoInst_id')"
                :error="$v.form_data.tipoInst_id.$error"
                :rules="[val => !!val || 'El campo es requerido']"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-select
                v-model="form_data.categoria_id"
                hide-bottom-space
                emit-value
                map-options
                options-dense
                :options="items_categoria"
                label="Categoría *"
                option-label="categoria_instalacion"
                option-value="id"
                :error-message="mensajesError('categoria_id')"
                :error="$v.form_data.categoria_id.$error"
                :rules="[val => !!val || 'El campo es requerido']"
              />
            </div>
            <div class="col-6 q-px-sm">
              <q-select
                v-model="form_data.cadHotelera_id"
                hide-bottom-space
                emit-value
                map-options
                options-dense
                :options="items_cadHotelera"
                label="Cadena Hotelera *"
                option-label="cadena_hotelera"
                option-value="id"
                :error-message="mensajesError('cadHotelera_id')"
                :error="$v.form_data.cadHotelera_id.$error"
                :rules="[val => !!val || 'El campo es requerido']"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-input
                v-model="form_data.tomo"
                label="Tomo *"
                name="tomo"
                type="number"
                :error-message="mensajesError('tomo')"
                :error="$v.form_data.tomo.$error"
                :rules="[val => !!val || 'El campo es requerido']"
                min="0"
              />
            </div>
            <div class="col-6 q-px-sm">
              <q-input
                v-model="form_data.folio"
                label="Folio *"
                name="folio"
                type="number"
                :error-message="mensajesError('folio')"
                :error="$v.form_data.folio.$error"
                :rules="[val => !!val || 'El campo es requerido']"
                min="0"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-input
                v-model="form_data.registro"
                label="Registro"
                name="registro"
                type="number"
                min="0"
              />
            </div>
            <div class="col-6 q-px-sm">
              <q-input
                v-model="form_data.fecha_registro"
                label="Fecha registro"
                name="fecha_registro"
              >
                <template>
                  <q-popup-proxy
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-date
                      dense
                      v-model="form_data.fecha_registro"
                      mask="YYYY-MM-DD"
                      today-btn
                    >
                      <div class="row items-center justify-end">
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
          <div class="row">
            <div class="col-6">
              <q-input
                label="Dirección *"
                v-model="form_data.direccion"
                type="textarea"
                autogrow
                :error-message="mensajesError('direccion')"
                :error="$v.form_data.direccion.$error"
                :rules="[val => !!val || 'El campo es requerido']"
              />
            </div>
            <div class="col-6 q-px-sm">
              <q-input
                v-model="form_data.correo_electronico"
                type="email"
                label="Correo Electrónico *"
                name="correo_electronico"
                v-if="form_data.activo === '1'"
                :rules="[val => !!val.includes('@') || 'Incluye un signo @']"
                :error-message="mensajesError('correo_electronico')"
                :error="$v.form_data.correo_electronico.$error"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-input
                v-model="form_data.telefono"
                label="Teléfono *"
                name="telefono"
                type="number"
                :error-message="mensajesError('telefono')"
                :error="$v.form_data.telefono.$error"
                :rules="[val => !!val || 'El campo es requerido']"
                min="0"
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
import {required, maxLength, numeric, minValue, email} from 'vuelidate/lib/validators';
import moment from "moment";
import SmallScreen from "../../mixin/SmallScreen";

moment.locale("es");
export default {
  name: 'instalaciones',
  mixins:[SmallScreen],
  data() {
    return {
      visibleColumns: [
        'activo',
        'nombre',
        'codigo',
        'provincia',
        'osde',
        'tipoInsta',
        'categoria',
        'cadHotelera',
        'tomo',
        'folio',
        'registro',
        'fechaRegistro',
        'correoElect',
        'telefono',
        'action',
      ],
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
      items_tipoInsta: [],
      items_provincia: [],
      items_entidad: [],
      items_municipio: [],
      items_osde: [],
      items_categoria: [],
      items_cadHotelera: [],
      form_data: {
        activo:'1',
        nombre: '',
        codigo: '',
        provincia_id: '',
        osde_id: '',
        complejo:'',
        tipoInst_id: '',
        categoria_id: '',
        cadHotelera_id: '',
        tomo: '',
        folio: '',
        registro: '',
        fecha_registro: '',
        direccion: '',
        correo_electronico: '',
        telefono: '',
        entidad_id: '',
        municipio_id: '',
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
          search: true,
        },
        {
          name: 'nombre',
          label: 'Nombre',
          align: 'left',
          field: 'nombre',
          search: true,
        },
        {
          name: 'codigo',
          label: 'Código',
          align: 'left',
          field: 'codigo',
          search: true,
        },
        {
          name: 'provincia',
          align: 'left',
          label: 'Provincia',
          field: (row) =>
            row.provincia !== undefined ? row.provincia.nombre : '',
        },
        {
          name: 'osde',
          align: 'left',
          label: 'Osde',
          field: (row) =>
            row.osdes !== undefined ? row.osdes.nombre : '',
        },
        {
          name: 'complejo',
          label: 'Complejo',
          align: 'left',
          field: 'complejo',
          search: true,
        },
        {
          name: 'tipoInsta',
          align: 'left',
          label: 'Tipo de Instalación',
          field: (row) =>
            row.tipoinst !== undefined ? row.tipoinst.nombre : '',
        },
        {
          name: 'categoria',
          align: 'left',
          label: 'Categoría',
          field: (row) =>
            row.categorias !== undefined ? row.categorias.categoria_instalacion : '',
        },
        {
          name: 'cadHotelera',
          align: 'left',
          label: 'Cadena Hotelera',
          field: (row) =>
            row.cad_hotelera !== undefined ? row.cad_hotelera.cadena_hotelera : '',
        },
        {
          name: 'tomo',
          label: 'Tomo',
          align: 'left',
          field: 'tomo',
          search: true,
        },
        {
          name: 'folio',
          label: 'Folio',
          align: 'left',
          field: 'folio',
          search: true,
        },
        {
          name: 'registro',
          label: 'Registro',
          align: 'left',
          field: 'registro',
          search: true,
        },
        {
          name: 'fechaRegistro',
          label: 'Fecha Registro',
          align: 'left',
          field: 'fecha_registro',
          search: true,
        },
        {
          name: 'direccion',
          label: 'Dirección',
          align: 'left',
          field: 'direccion',
          search: true,
        },
        {
          name: 'correoElect',
          label: 'Correo Electrónico',
          align: 'left',
          field: 'correo_electronico',
          search: true,
        },
        {
          name: 'telefono',
          label: 'Teléfono',
          align: 'left',
          field: 'telefono',
          search: true,
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
      nombre: {
        required,
      },
      codigo: {
        required,
      },
      provincia_id: {
        required,
      },
      osde_id: {
        required,
      },
      tipoInst_id: {
        required,
      },
      categoria_id: {
        required,
      },
      cadHotelera_id: {
        required,
      },
      tomo: {
        required,
        numeric,
        minValue: minValue(1),
      },
      folio: {
        required,
        numeric,
        minValue: minValue(1),
      },
      registro: {
        numeric,
        minValue: minValue(1),
      },
      direccion: {
        required,
      },
      correo_electronico: {
        required, email
      },
      telefono: {
        required, numeric
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Configuraciones' },
      { label: 'Nomencladores' },
      { label: 'Instalaciones' },
    ]);

    this.getProvincias().then((r) => {
      this.items_provincia = r;
    });
    this.getTipoInsts().then((r) => {
      this.items_tipoInsta = r;
    });
    this.getEntidades().then((r) => {
      this.items_entidad = r;
    });
    this.getMunicipios().then((r) => {
      this.items_municipio = r;
    });
    this.listOsde().then((r) => {
      this.items_osde = r.data.data;
    });
    this.getCategoria_instalacion().then((r) => {
      this.items_categoria = r;
    });
    this.getCadena_hotelera().then((r) => {
      this.items_cadHotelera = r;
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
    ...mapState('instalacion', ['instalacion']),
  },
  methods: {
    moment,
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('municipio', ['getMunicipios']),
    ...mapActions('entidad', ['getEntidades']),
    ...mapActions('tipoInst', ['getTipoInsts']),
    ...mapActions('instalacion', [ 'getInstalacion', 'saveInstalacion', 'editInstalacion', 'deleteInstalacion', ]),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('provincia', ['getProvincias']),
    ...mapActions('osde', ['listOsde']),
    ...mapActions('categoria_instalacion', ['getCategoria_instalacion']),
    ...mapActions('cadena_hotelera', ['getCadena_hotelera']),
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
      if (campo === 'codigo') {
        if (!this.$v.form_data.codigo.required) {
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
      if (campo === 'osde_id') {
        if (!this.$v.form_data.osde_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'tipoInst_id') {
        if (!this.$v.form_data.tipoInst_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'categoria_id') {
        if (!this.$v.form_data.categoria_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'cadHotelera_id') {
        if (!this.$v.form_data.cadHotelera_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'tomo') {
        if (!this.$v.form_data.tomo.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.tomo.numeric) {
          this.required_error = true;
          return 'Solo se aceptan números enteros';
        }
      }
      if (campo === 'folio') {
        if (!this.$v.form_data.folio.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.folio.numeric) {
          this.required_error = true;
          return 'Solo se aceptan números enteros';
        }
      }
      if (campo === 'registro') {
        if (!this.$v.form_data.registro.numeric) {
          this.required_error = true;
          return 'Solo se aceptan números enteros';
        }
      }
      if (campo === 'direccion') {
        if (!this.$v.form_data.direccion.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'correo_electronico') {
        if (!this.$v.form_data.correo_electronico.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.correo_electronico.email) {
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
          this.required_error = true;
          return 'Solo se aceptan números';
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
      this.form_data.codigo = '';
      this.form_data.activo = '1';
      this.form_data.provincia_id = '';
      this.form_data.osde_id = '';
      this.form_data.complejo = '';
      this.form_data.tipoInst_id = '';
      this.form_data.categoria_id = '';
      this.form_data.cadHotelera_id = '';
      this.form_data.tomo = '';
      this.form_data.folio = '';
      this.form_data.registro = '';
      this.form_data.fecha_registro = '';
      this.form_data.direccion = '';
      this.form_data.correo_electronico = '';
      this.form_data.telefono = '';
      this.form_data.entidad_id = '';
      this.form_data.municipio_id = '';
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
      await this.getInstalacion(data).then((r) => {
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
        await this.saveInstalacion(this.form_data).then(result => {
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
        await this.editInstalacion(this.form_data).then(result => {
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
    deleteInstalaciones(selected) {
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteInstalacion(this.form_data.id);
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
