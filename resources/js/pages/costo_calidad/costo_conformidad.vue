<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="text-subtitle1 text-weight-bold text-uppercase">
            gestionar costos calidad
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
            :disable="
              costoConformidadCC.length != 0 || costoNoConformidadCC.length != 0
                ? false
                : true
            "
            :color="filterInput ? 'blue-grey-3' : 'primary'"
            icon="search"
            @click.prevent="filterStatus()"
            class="q-mr-sm q-ml-sm q-mt-sm"
          >
            <q-tooltip>Buscar</q-tooltip>
          </q-btn>
          <q-btn
            v-if="scopes.includes('gestionar_costo_calidad')"
            type="reset"
            key="add"
            dense
            color="primary"
            icon="add"
            @click.prevent="openModal(true, 'add')"
            class="q-mt-sm"
          >
            <q-tooltip>Nuevos datos</q-tooltip>
          </q-btn>
        </div>
      </q-card-section>
      <q-card-section class="no-padding">
        <q-tabs
          dense
          v-model="tab"
          align="center"
          active-color=""
          active-bg-color=""
          narrow-indicator
          indicator-color="primary"
        >
          <q-tab name="conformidad" label="Calidad Conformidad" />
          <q-tab name="no_conformidad" label="Calidad No Conformidad" />
        </q-tabs>
        <q-tab-panels
          v-model="tab"
          animated
          transition-prev="fade"
          transition-next="fade"
        >
          <q-tab-panel name="conformidad">
            <q-table
              flat
              dense
              :data="costoConformidadCC"
              :visible-columns="visibleColumns"
              :columns="columns"
              :pagination.sync="pagination"
              :loading="this.getterLoading()"
              row-key="id"
              loading-label="Cargando elementos"
              :rows-per-page-options="[5, 10, 25, 50]"
              :filter="filter"
              @request="loadData"
              binary-state-sort
              no-data-label="No se encontraron elementos a mostrar"
            >
              <template v-slot:body-cell-acciones="props">
                <q-td :props="props">
                  <div class="q-gutter-xs">
                    <q-btn
                      flat
                      key="see"
                      dense
                      size="sm"
                      text-color="primary"
                      icon="remove_red_eye"
                      @click.prevent="openModal(true, 'see', props.row)"
                    >
                      <q-tooltip>Ver detalles</q-tooltip>
                    </q-btn>
                    <q-btn
                      v-if="scopes.includes('gestionar_costo_calidad')"
                      flat
                      dense
                      size="sm"
                      text-color="primary"
                      icon="edit"
                      @click.prevent="openModal(true, 'update', props.row)"
                    >
                      <q-tooltip>Editar datos</q-tooltip>
                    </q-btn>
                    <q-btn
                      v-if="scopes.includes('gestionar_costo_calidad')"
                      flat
                      dense
                      size="sm"
                      text-color="negative"
                      icon="delete"
                      @click.prevent="eliminar(props.row)"
                    >
                      <q-tooltip>Eliminar datos</q-tooltip>
                    </q-btn>
                  </div>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>

          <q-tab-panel name="no_conformidad">
            <q-table
              flat
              dense
              :data="costoNoConformidadCC"
              :visible-columns="visibleColumns"
              :columns="columnsNo_conformidad"
              row-key="id"
              :pagination.sync="pagination2"
              :loading="this.getterLoading()"
              loading-label="Cargando elementos"
              :rows-per-page-options="[5, 10, 25, 50]"
              :filter="filter"
              @request="loadDataNoConformidad"
              binary-state-sort
              no-data-label="No se encontraron elementos a mostrar"
            >
              <template v-slot:body-cell-acciones="props">
                <q-td :props="props">
                  <div class="q-gutter-xs">
                    <q-btn
                      flat
                      key="see"
                      dense
                      size="sm"
                      text-color="primary"
                      icon="remove_red_eye"
                      @click.prevent="openModal(true, 'see', props.row)"
                    >
                      <q-tooltip>Ver detalles</q-tooltip>
                    </q-btn>
                    <q-btn
                      v-if="scopes.includes('gestionar_costo_calidad')"
                      flat
                      dense
                      size="sm"
                      text-color="primary"
                      icon="edit"
                      @click.prevent="openModal(true, 'update', props.row)"
                    >
                      <q-tooltip>Editar datos</q-tooltip>
                    </q-btn>
                    <q-btn
                      v-if="scopes.includes('gestionar_costo_calidad')"
                      flat
                      dense
                      size="sm"
                      text-color="negative"
                      icon="delete"
                      @click.prevent="eliminar(props.row)"
                    >
                      <q-tooltip>Eliminar datos</q-tooltip>
                    </q-btn>
                  </div>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>
    <!-- Dialog -->
    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw;">
        <q-form
          @reset="reset_form"
          @submit="this.action != 'add' ? update() : nuevo()"
          ref="formPremio"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.action === 'add'
                      ? 'Adicionar datos'
                      : 'Actualizar datos'
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
              <div class="row q-px-sm">
                <div class="col">
                  <q-input
                    v-model="form_sistema.nombre"
                    type="text"
                    label="Nombre *"
                    dense
                    name="nombre"
                    :error-message="mensajesError('nombre')"
                    :error="$v.form_sistema.nombre.$error"
                    debounce="1000"
                    :rules="[(val) => !!val || 'Por favor escriba algo']"
                    @keydown="nameKeydown($event)"
                  />
                </div>
              </div>
              <div class="row q-px-sm">
                <div class="col">
                  <q-select
                    v-model="form_sistema.tipo"
                    :options="options"
                    label="Tipo *"
                    name="tipo"
                    :error-message="mensajesError('tipo')"
                    :error="$v.form_sistema.tipo.$error"
                    debounce="1000"
                    lazy-rules
                    :rules="[(val) => !!val || 'Por favor escriba algo']"
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
                @click="
                  action != 'add'
                    ? update(form_sistema.id, form_sistema)
                    : nuevo(form_sistema)
                "
                label="Guardar"
                color="primary"
                flat
                :loading="this.getterLoading()"
                :disable="isComplete"
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
    <q-dialog
      v-model="modalDialogSee"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw;">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title> Consultar datos </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section class="q-pa-lg">
          <div class="row">
            <div class="col">
              <q-item-label
                ><span class="text-bold">Nombre:</span>
                {{ form_sistema.nombre }}</q-item-label
              >
            </div>
          </div>
          <div class="row q-pt-md">
            <div class="col">
              <q-item-label
                ><span class="text-bold">Tipo:</span>
                {{ form_sistema.tipo }}</q-item-label
              >
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="check"
            @click="closeModal()"
            label="Aceptar"
            v-close-popup
            color="primary"
            flat
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import {
  errorMessage,
  successMessage,
  infoMessage,
} from '../../utils/notificaciones';
import * as message from '../../utils/resources';
import { showDialog } from '../../utils/dialogo';
import { mapState, mapGetters, mapActions } from 'vuex';
import { required, maxLength } from 'vuelidate/lib/validators';

export default {
  name: 'costo_conformidad',
  data() {
    return {
      tab: 'conformidad',
      visibleColumns: ['nombre', 'acciones'],
      modalDialog: false,
      filterInput: false,
      loading: true,
      modalDialogSee: false,
      selected: [],
      add: true,
      data: [],
      dataNoConformidad: [],
      scopes: sessionStorage.getItem('scopes'),
      filter: '',
      filter2: '',
      action: '',
      form_sistema: {
        id: '',
        codigo: '',
        nombre: '',
        id: '',
        tipo: '',
      },
      model: null,
      options: ['Conformidad', 'No Conformidad'],
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      pagination2: {
        page: 1,
        rowsNumber: 0,
      },
      dense: false,
      denseOpts: false,
      form_error: false,
      required_error: false,
      columns: [
        {
          name: 'nombre',
          label: 'NOMBRE',
          align: 'left',
          field: 'nombre',
          search: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'acciones',
          label: 'ACCIONES',
          align: 'center',
          field: 'action',
          headerClasses: 'text-uppercase',
        },
      ],
      columnsNo_conformidad: [
        {
          name: 'nombre',
          label: 'NOMBRE',
          align: 'left',
          field: 'nombre',
          search: true,
        },
        {
          name: 'acciones',
          label: 'ACCIONES',
          align: 'center',
          field: 'action',
          headerClasses: 'text-uppercase',
        },
      ],
    };
  },
  validations: {
    form_sistema: {
      nombre: {
        required,
        maxLength: maxLength(30),
      },
      tipo: {
        required,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Costo de Calidad' },
      { label: 'Gestionar costos' },
    ]);
    this.loadData({
      pagination: this.pagination,
      filter: this.filter,
    });
    this.loadDataNoConformidad({
      pagination: this.pagination2,
      filter: this.filter2,
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
    pagination2: {
      handler() {
        this.loadDataNoConformidad({
          pagination: this.pagination2,
          filter: this.filter2,
        });
      },
    },

    /* 'form_sistema.nombre': function (val, oldVal) {
       console.log("Write value", val) },*/

    'form_sistema.nombre': function (val, oldVal) {
      this.form_sistema.nombre = val.replace(/\W/g, ''); //ok
    },
  },
  computed: {
    ...mapState('costo_conformidad', [
      'costoConformidadCC',
      'costoNoConformidadCC',
    ]),
    isComplete() {
      if (this.form_sistema.nombre && this.form_sistema.tipo) {
        return false;
      }
      return true;
    },
  },
  methods: {
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('costo_conformidad', [
      'getCostoConformidadList',
      'getNoConformidadList',
      'saveConformidad',
      'editCostoConformidad',
      'deleteCostoConformidad',
    ]),
    ...mapGetters('utils', ['getterLoading']),

    nameKeydown(e) {
      if (/^\W$/.test(e.key)) {
        e.preventDefault();
      }
    },

    mensajesError(campo) {
      if (!this.$v.form_sistema.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'nombre') {
        if (!this.$v.form_sistema.nombre.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_sistema.nombre.maxLength) {
          this.required_error = false;
          return 'El nombre solo puede tener 30 carácteres';
        }
      }
      if (campo === 'fecha') {
        if (!this.$v.form_sistema.tipo.required) {
          this.required_error = true;
          return '';
        }
      }
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
    openModal(data, accion, rowData) {
      this.action = accion;
      if (accion === 'add') {
        this.Ramdon();
        this.modalDialog = data;
      } else {
        if (accion === 'update') {
          this.setFormData(rowData);
          this.modalDialog = data;
        } else {
          this.setFormData(rowData);
          this.modalDialogSee = data;
        }
      }
    },
    setFormData(data) {
      this.form_sistema = Object.assign(this.form_sistema, data);
    },
    Ramdon() {
      var caracteres = 'abcdefghijkmnpqrtuvwxyzABCDEFGHJKMNPQRTUVWXYZ12346789';
      var id_muestra = '';
      for (var i = 0; i < 10; i++)
        id_muestra += caracteres.charAt(
          Math.floor(Math.random() * caracteres.length)
        );
      this.form_sistema.codigo = id_muestra;
    },
    reset_form() {
      this.form_sistema.id = '';
      this.form_sistema.codigo = '';
      this.form_sistema.nombre = '';
      this.form_sistema.tipo = '';
      this.form_error = false;
      this.required_error = false;
      this.add = true;
      this.$v.form_sistema.$reset();
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
      await this.getCostoConformidadList(data).then((r) => {
        this.data = r;
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    async loadDataNoConformidad(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      this.tab = 'no_conformidad';
      await this.getNoConformidadList(data).then((r) => {
        this.dataNoConformidad = r;
        this.pagination2.rowsNumber = r.total;
        this.pagination2.page = page;
        this.pagination2.rowsPerPage = rowsPerPage;
        this.pagination2.sortBy = sortBy;
        this.pagination2.descending = descending;
        this.setLoading(false);
      });
    },
    async nuevo() {
      this.$v.form_sistema.$touch();
      if (this.$v.form_sistema.$invalid) {
        infoMessage(message.inputInvalid);
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.saveConformidad(this.form_sistema);
        if (this.form_sistema.tipo === 'Conformidad') {
          this.tab = 'conformidad';
        } else {
          this.tab = 'no_conformidad';
        }
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
        this.loadDataNoConformidad({
          pagination: this.pagination2,
          filter: this.filter2,
        });
        this.setLoading(false);
      }
    },
    async update() {
      this.$v.form_sistema.$touch();
      if (this.$v.form_sistema.$invalid) {
        infoMessage(message.inputInvalid);
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.editCostoConformidad(this.form_sistema);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
        this.loadDataNoConformidad({
          pagination: this.pagination2,
          filter: this.filter2,
        });
        this.setLoading(false);
      }
    },
    async eliminar(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteCostoConformidad(selected);
          this.reset_form();
          await this.loadData({
            pagination: this.pagination,
            filter: this.filter,
          });
          this.loadDataNoConformidad({
            pagination: this.pagination2,
            filter: this.filter2,
          });
          this.setLoading(false);
        });
      } else {
        infoMessage('Debe seleccionar la fila a eliminar.');
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
