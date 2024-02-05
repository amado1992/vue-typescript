<template>
  <div>
    <q-card>
      <q-card-section>
        <q-table
          table-header-class="text-uppercase"
          :data="colectivo"
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
              Gestionar colectivos líderes de calidad
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
              :disable="colectivo.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_lideres_calidad')"
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
                  v-if="scopes.includes('gestionar_lideres_calidad')"
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
                  v-if="scopes.includes('gestionar_lideres_calidad')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteColectivos(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
                <!--Menu-->
                <q-btn dense size="sm" icon="more_vert" color="primary" flat>
                  <q-tooltip>Ver más</q-tooltip>
                  <q-menu>
                    <q-list dense>
                      <q-item
                        dense
                        clickable
                        v-ripple
                        @click.prevent="openModal(true, 'estado', props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon color="primary" name="mdi-check" />
                        </q-item-section>
                        <q-item-section style="margin-left: -25px;">Procesar</q-item-section>
                      </q-item>
                      <q-separator />
                      <q-item
                        dense
                        clickable
                        v-ripple
                        @click.prevent="openModal(true, 'detalles', props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon color="primary" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section style="margin-left: -25px;">Argumentación</q-item-section>
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
                <!--End Menu-->
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
      <q-card style="width: 600px; max-width: 70vw;">
        <q-card-section class="no-padding">
          <q-card-section class="row no-padding">
            <q-toolbar class="bg-primary text-white">
              <q-toolbar-title>
                {{
                  this.accion == 'adicionar'
                    ? 'Adicionar colectivo'
                    : 'Actualizar colectivo'
                }}
              </q-toolbar-title>
              <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
                <q-tooltip>Cerrar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </q-card-section>
          <q-card-section>
            <div class="row q-px-sm">
              <div class="col-6">
                <q-select
                  v-model="form_data.sector_id"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  :options="items_sector"
                  label="Sector *"
                  option-label="nombre"
                  option-value="id"
                  :error-message="mensajesError('sector_id')"
                  :error="$v.form_data.sector_id.$error"
                />
              </div>
              <div class="col-6 q-pl-sm">
                <q-select
                  v-model="form_data.provincia_id"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  :options="items_provincia"
                  label="Provincia *"
                  option-label="nombre"
                  option-value="id"
                  :error-message="mensajesError('provincia_id')"
                  :error="$v.form_data.provincia_id.$error"
                />
              </div>
            </div>

            <div class="row q-px-sm">
              <div class="col-12">
                <q-input
                  v-model="form_data.argumentacion"
                  clearable
                  type="textarea"
                  autogrow
                  label="Argumentación *"
                  :error-message="mensajesError('argumentacion')"
                  :error="$v.form_data.argumentacion.$error"
                />
              </div>
            </div>
          </q-card-section>
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
    <q-dialog
      v-model="modalDialogEstado"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 550px; max-width: 70vw;">
        <q-card-section class="bg-primary no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title> Cambiar estado </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section class="q-px-md">
          <div class="row">
            <div class="col">
              <q-select
                v-model="estadoColectivo"
                hide-bottom-space
                emit-value
                map-options
                options-dense
                :options="optionsEstado"
                label="Estado"
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
            @click="cambiarEstadoColectivo()"
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
    <!-- Modal detalles -->
    <q-dialog
      v-model="modalDialogDetalles"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw;">
        <q-card-section class="bg-primary no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title> Ver argumentación </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section class="q-px-md">
          <div class="row q-px-sm">
            <div class="col-12">
              <q-input
                readonly
                v-model="form_data.argumentacion"
                type="textarea"
                autogrow
                label="Argumentación"
              />
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
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
import {
  infoMessage,
  warningMessage,
} from '../../../../resources/js/utils/notificaciones';
import * as message from '../../../../resources/js/utils/resources';
import { required, maxLength } from 'vuelidate/lib/validators';

export default {
  name: 'colectivo',
  data() {
    return {
      visibleColumns: [
        'sector',
        'provincia',
        'osde',
        'instalacion',
        'estado',
        'action',
      ],
      filterInput: false,
      modalDialog: false,
      modalDialogEstado: false,
      modalDialogDetalles: false,
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
      estadoColectivo: '',
      permissions: [],
      items_sector: [],
      items_provincia: [],
      form_data: {
        sector_id: '',
        provincia_id: '',
        osde_id: sessionStorage.getItem('osde_id'),
        instalacion_id: sessionStorage.getItem('instalacion_id'),
        argumentacion: '',
        estado: '',
      },
      optionsEstado: ['Aceptada', 'Rechazada'],
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
          name: 'sector',
          align: 'center',
          label: 'Sector',
          field: (row) => (row.sector !== undefined ? row.sector.nombre : ''),
        },
        {
          name: 'provincia',
          align: 'center',
          label: 'Provincia',
          field: (row) =>
            row.provincia !== undefined ? row.provincia.nombre : '',
        },
        {
          name: 'osde',
          align: 'center',
          label: 'Osde',
          field: (row) => (row.osde !== undefined ? row.osde.nombre : ''),
        },
        {
          name: 'instalacion',
          align: 'center',
          label: 'Instalación',
          field: (row) =>
            row.instalacion !== undefined ? row.instalacion.nombre : '',
        },
        {
          name: 'argumentacion',
          label: 'Argumentación',
          align: 'center',
          field: 'argumentacion',
          search: true,
        },
        {
          name: 'estado',
          align: 'center',
          label: 'Estado',
          field: 'estado',
        },
        {
          name: 'action',
          align: 'left',
          label: 'acciones',
          field: 'action',
        },
      ],
    };
  },
  validations: {
    form_data: {
      argumentacion: {
        required,
        maxLength: maxLength(30),
      },
      sector_id: {
        required,
      },
      provincia_id: {
        required,
      },
      argumentacion: {
        required,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Movimientos líderes de calidad' },
      { label: 'Propuesta Colectivo Líder ' },
    ]);
    this.getProvincias().then((r) => {
      this.items_provincia = r;
    });
    this.getSectores().then((r) => {
      this.items_sector = r;
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
    ...mapState('colectivo', ['colectivo']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('sector', ['getSectores']),
    ...mapActions('provincia', ['getProvincias']),
    ...mapActions('colectivo', [
      'getColectivo',
      'saveColectivo',
      'editColectivo',
      'deleteColectivo',
    ]),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'argumentacion') {
        if (!this.$v.form_data.argumentacion.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.argumentacion.maxLength) {
          this.required_error = false;
          return 'El nombre solo puede tener 30 carácteres';
        }
      }
      if (campo === 'sector_id') {
        if (!this.$v.form_data.sector_id.required) {
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
      if (campo === 'argumentacion') {
        if (!this.$v.form_data.argumentacion.required) {
          this.required_error = true;
          return '';
        }
      }
    },
    closeModal() {
      this.modalDialog = false;
      this.modalDialogEstado = false;
      this.reset_form();
      this.selected = [];
    },
    cambiarEstado() {
      if (this.selected[0].estado !== 'Sin Procesar') {
        this.estadoColectivo = this.selected[0].estado;
      }
      this.modalDialogEstado = true;
    },
    openModal(data, accion, rowData) {
      this.accion = accion;
      if (accion === 'adicionar') {
        this.modalDialog = data;
      } else {
        if (accion === 'estado') {
          if (rowData.estado !== 'Sin Procesar')
            this.estadoColectivo = rowData.estado;
          this.setFormData(rowData);
          this.modalDialogEstado = data;
        } else {
          if (accion === 'actualizar') {
            if (rowData) {
              this.setFormData(rowData);
              this.modalDialog = data;
            }
          } else {
            if (accion === 'detalles') {
              if (rowData) {
                this.setFormData(rowData);
                this.modalDialogDetalles = data;
              }
            }
          }
        }
      }
    },
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data);
    },
    reset_form() {
      this.form_data.argumentacion = '';
      this.form_data.sector_id = '';
      this.form_data.provincia_id = '';
      this.form_data.osde_id = '';
      this.form_data.instalacion_id = '';
      this.form_data.estado = '';
      this.estadoColectivo = '';
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
      await this.getColectivo(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    async save() {
      debugger;
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos inválidos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        if (this.form_data.estado === '') {
          this.form_data.estado = 'Sin Procesar';
        }
        this.setLoading(true);
        await this.saveColectivo(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    async update() {
      console.log(this.form_data);
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos inválidos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        if (this.form_data.estado === '') {
          this.form_data.estado = 'Sin Procesar';
        }
        this.setLoading(true);
        await this.editColectivo(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    async cambiarEstadoColectivo() {
      this.form_data.estado = this.estadoColectivo;
      this.setLoading(true);
      await this.editColectivo(this.form_data);
      this.reset_form();
      this.closeModal();
      this.loadData({ pagination: this.pagination, filter: this.filter });
      this.setLoading(false);
    },
    deleteColectivos(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteColectivo(selected.id);
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
