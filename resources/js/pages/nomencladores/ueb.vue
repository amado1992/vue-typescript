<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          class="table-title"
          title="Gestionar ueb"
          :grid="xssmallScreen || smallScreen"
          flat
          dense
          :data="ueb"
          :visible-columns="visibleColumns"
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
          :hide-pagination="false"
        >
          <!--<template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar ueb
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
              :disable="ueb.length != 0 ? false : true"
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
                <q-btn key="filter" dense :color="'primary'" icon="search">
                </q-btn>
              </template>
            </q-input>
            <q-btn
              v-if="scopes.includes('nomencladores')"
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
                  :disable="props.row.activo === '0'"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteUebs(props.row)"
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
                  :disable="props.row.activo === '0'"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteUebs(props.row)"
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
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
    <!-- Dialog gestionar -->
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
          ref="formUeb"
        >
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              {{
                this.accion == 'adicionar'
                  ? 'Adicionar ueb'
                  : 'Actualizar ueb'
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <q-toggle
                v-model="form_data.activo"
                label="Activo"
                name="activo"
                false-value="0"
                true-value="1"
                v-if="this.accion != 'adicionar'"
              />
            </div>
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
              <q-input
                v-model="form_data.ueb"
                type="text"
                label="Nombre *"
                dense
                name="ueb"
                v-if="form_data.activo === '1'"
                :error-message="mensajesError('ueb')"
                :error="$v.form_data.ueb.$error"
                :rules="[val => !!val || 'El campo es requerido', isValidAlphaNum]"
                debounce="1000"
                autogrow
              />
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
              <q-select
                v-model="form_data.empresa_id"
                dense
                emit-value
                map-options
                options-dense
                :options="items_empresa"
                label="Empresa *"
                option-label="empresa"
                option-value="id"
                v-if="form_data.activo === '1'"
                :error-message="mensajesError('empresa_id')"
                :error="$v.form_data.empresa_id.$error"
                :rules="[val => !!val || 'El campo es requerido']"
              />
            </div>
         <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
              <q-input
                v-model="form_data.telefono"
                type="number"
                label="Teléfono *"
                dense
                min="0"
                name="telefono"
                v-if="form_data.activo === '1'"
                :error-message="mensajesError('telefono')"
                :error="$v.form_data.telefono.$error"
                :rules="[val => !!val || 'El campo es requerido']"
                debounce="1000"
              />
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
              <q-input
                v-model="form_data.direccion"
                type="textarea"
                label="Dirección *"
                dense
                name="direccion"
                debounce="1000"
                autogrow
                v-if="form_data.activo === '1'"
                :error-message="mensajesError('direccion')"
                :error="$v.form_data.direccion.$error"
                :rules="[val => !!val || 'El campo es requerido']"
              />
            </div>
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 q-px-sm">
              <q-input
                v-model="form_data.correo"
                type="email"
                label="Correo *"
                dense
                name="correo"
                v-if="form_data.activo === '1'"
                :rules="[val => !!val.includes('@') || 'Incluye un signo @']"
                :error-message="mensajesError('correo')"
                :error="$v.form_data.correo.$error"
                debounce="1000"
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
    <!-- End Dialog gestionar -->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../../../resources/js/utils/dialogo';
import { infoMessage, warningMessage } from '../../../../resources/js/utils/notificaciones';
import * as message from '../../../../resources/js/utils/resources';
import { required, alphaNum, email, maxLength } from 'vuelidate/lib/validators';
import SmallScreen from "../../mixin/SmallScreen";

export default {
  name: 'ueb',
  mixins:[SmallScreen],
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      add: true,
      form_data: {
        activo: '1',
        ueb: '',
        empresa_id: '',
        direccion: '',
        correo: '',
        telefono: ''
      },
      items_empresa: [],
      modalDialog: false,
      filterInput: false,
      filter: '',
      accion: '',
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      form_error: false,
      required_error: false,
      visibleColumns: [
        'activo', 'ueb', 'empresa_id', 'direccion', 'correo', 'telefono', 'action'
      ],
      columns: [
        {
          name: 'activo',
          align: 'left',
          label: 'activo',
          field: 'activo',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'empresa_id',
          align: 'left',
          label: 'empresa',
          field: (row) => row.empresas && row.empresas.empresa,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'ueb',
          align: 'left',
          label: 'ueb',
          field: 'ueb',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'correo',
          align: 'left',
          label: 'correo',
          field: 'correo',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'telefono',
          align: 'left',
          label: 'telefono',
          field: 'telefono',
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
      ueb: {
        required
      },
      empresa_id: {
        required,
      },
      direccion: {
        required,
      },
      correo: {
        required, email
      },
      telefono: {
        required,
      }
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Configuraciones' },
      { label: 'Nomencladores' },
      { label: 'Ueb' },
    ]);
    this.getEmpresa().then((r) => {
      this.items_empresa = r;
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
    ...mapState('ueb', ['ueb']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('ueb', ['listUeb', 'saveUeb', 'editUeb', 'deleteUeb']),
    ...mapActions('empresa', ['getEmpresa']),
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'ueb') {
        if (!this.$v.form_data.ueb.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'empresa_id') {
        if (!this.$v.form_data.empresa_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'direccion') {
        if (!this.$v.form_data.direccion.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'correo') {
        if (!this.$v.form_data.correo.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.correo.email) {
          this.required_error = true;
          return 'Escriba una dirección de correo electrónico válida';
        }
      }
      if (campo === 'telefono') {
        if (!this.$v.form_data.telefono.required) {
          this.required_error = true;
          return '';
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
    isValidAlphaNum (val) {
      const pattern = /^[a-zA-Z0-9 ]*$/;
      return pattern.test(val) || 'No se aceptan caracteres especiales';
    },
    reset_form() {
      this.form_data.activo = '1';
      this.form_data.ueb = '';
      this.form_data.empresa_id = '';
      this.form_data.direccion = '';
      this.form_data.correo = '';
      this.form_data.telefono = '';
      this.form_error = false;
      this.required_error = false;
      this.$v.form_data.$reset();
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data);
    },
    openModal(data, accion, rowData) {
      this.accion = accion;
      switch (accion) {
        case 'adicionar':
          this.modalDialog = data;
          break;
        case 'actualizar':
          if (rowData) {
            this.setFormData(rowData);
            this.modalDialog = data;
          } else {
            infoMessage('Debe seleccionar una fila para modificar.');
          }
          break;
        default:
          break;
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
      await this.listUeb(data).then((r) => {
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
        await this.saveUeb(this.form_data).then(result => {
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
        await this.editUeb(this.form_data).then(result => {
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
    deleteUebs(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteUeb(selected.id);
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
