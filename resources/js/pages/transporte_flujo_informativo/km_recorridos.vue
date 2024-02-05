<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          table-header-class="text-uppercase"
          :data="km_recorridos"
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
              Gestionar los kilómetros recorridos
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
              :disable="km_recorridos.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_dir_transporte_fi')"
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
                  flat
                  key="see"
                  dense
                  size="sm"
                  text-color="primary"
                  icon="remove_red_eye"
                  @click.prevent="openModalSee(true, 'see', props.row)"
                >
                  <q-tooltip>Ver detalles</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_dir_transporte_fi')"
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
          <template v-slot:body-cell-mes="props">
            <q-td :props="props">
              {{ mesesToString(props.row.mes) }}
            </q-td>
          </template>
        </q-table>
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
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              {{
                this.accion == 'adicionar'
                  ? 'Adicionar kilómetros recorridos'
                  : 'Actualizar kilómetros recorridos'
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal()" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section class="q-px-md">
          <div class="row">
            <div class="col-6">
              <q-input
                v-model="monthPicked"
                label="Fecha *"
                readonly
                name="mes"
                :error-message="mensajesError('mes')"
                :error="$v.form_data.mes.$error"
                debounce="1000"
              >
                <template v-slot:append>
                  <q-btn icon="event" flat color="primary">
                    <q-popup-proxy
                      transition-show="scale"
                      transition-hide="scale"
                      ref="monthPicker"
                    >
                      <q-date
                        minimal
                        mask="MM/YYYY"
                        emit-immediately
                        default-view="Years"
                        v-model="monthPicked"
                        @input="checkValue"
                      />
                    </q-popup-proxy>
                    <q-tooltip>Insertar fecha</q-tooltip>
                  </q-btn>
                </template>
              </q-input>
            </div>
            <div class="col-6 q-px-sm">
              <q-select
                v-model="form_data.vehiculo_empresa_id"
                emit-value
                map-options
                options-dense
                :options="items_vehiculo_empresa"
                label="Vehiculo *"
                option-label="matricula"
                option-value="id"
                :error-message="mensajesError('vehiculo_empresa_id')"
                :error="$v.form_data.vehiculo_empresa_id.$error"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-input
                v-model="form_data.km_recorridos"
                label="Kilómetros recorridos *"
                name="km_recorridos"
                type="number"
                suffix="KM"
                min="0"
                :error-message="mensajesError('km_recorridos')"
                :error="$v.form_data.km_recorridos.$error"
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
    <!-- End Dialog gestionar -->
    <!--Dialog ver detalles -->
    <q-dialog
      v-model="modalDialogDetail"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 800px; max-width: 90vw;">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>Ver detalles</q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal()" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section class="q-pa-md q-gutter-lg no-wrap">
          <div class="row no-wrap">
            <div class="col-4">
              <span class="text-weight-bold">Mes:</span>
              <span class="">{{ mesesToString(form_data.mes) }}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">Vehiculo:</span>
              <span class="">{{ vehiculo }}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">Kilometros:</span>
              <span class="">{{ form_data.km_recorridos }}</span>
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
    <!-- End Dialog ver detalles -->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../../../resources/js/utils/dialogo';
import {errorMessage, infoMessage, warningMessage } from '../../../../resources/js/utils/notificaciones';
import * as message from '../../../../resources/js/utils/resources';
import { required, maxLength } from 'vuelidate/lib/validators';

export default {
  name: 'km_recorridos',
  data() {
    return {
      vehiculo: "",
      listmes: [
        {
          id: 1,
          mes: 'Enero'
        },
        {
          id: 2,
          mes: 'Febrero'
        },
        {
          id: 3,
          mes: 'Marzo'
        },
        {
          id: 4,
          mes: 'Abril'
        },
        {
          id: 5,
          mes: 'Mayo'
        },
        {
          id: 6,
          mes: 'Junio'
        },
        {
          id: 7,
          mes: 'Julio'
        },
        {
          id: 8,
          mes: 'Agosto'
        },
        {
          id: 9,
          mes: 'Septiembre'
        },
        {
          id: 10,
          mes: 'Octubre'
        },
        {
          id: 11,
          mes: 'Noviembre'
        },
        {
          id: 12,
          mes: 'Diciembre'
        }
      ],
      items_vehiculo_empresa: [],
      visibleColumns: ['mes','anno', 'vehiculo_empresa_id','km_recorridos','action'],
      modalDialog: false,
      modalDialogDetail: false,
      permiso: true,
      val: false,
      monthPicked: [],
      selected: [],
      roles: [],
      rolesName: [],
      filterInput: false,
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
      form_data: {
        mes: '',
        anno: '',
        vehiculo_empresa_id: '',
        km_recorridos: ''
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
          name: 'mes',
          label: 'Mes',
          align: 'left',
          field: (row) => row.mes,
          search: true,
        },
        {
          name: 'anno',
          label: 'año',
          align: 'left',
          field: (row) => row.anno,
          search: true,
        },
        {
          name: 'vehiculo_empresa_id',
          label: 'Vehículo de la empresa',
          align: 'left',
          field: (row) => row.vehiculos && row.vehiculos.matricula,
          search: true,
        },
        {
          name: 'km_recorridos',
          label: 'Kilómetros Recorridos',
          align: 'left',
          field: 'km_recorridos',
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
      mes: {
        required,
      },
      vehiculo_empresa_id: {
        required,
      },
      km_recorridos: {
        required,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Nomencladores' },
      { label: 'Kilómetros Recorridos' },
    ]);
    this.listMedioTransporte().then((r) => {
      this.items_vehiculo_empresa = r;
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
    ...mapState('km_recorridos', ['km_recorridos']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('km_recorridos', ['getKm_recorridos', 'saveKm_recorridos', 'editKm_recorridos']),
    ...mapActions('medio_transporte', ['listMedioTransporte']),
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
    mesesToString(data) {
      let mes = '';
      switch (data) {
        case 1:
          mes = 'Enero';
          break;
        case 2:
          mes = 'Febrero';
          break;
        case 3:
          mes = 'Marzo';
          break;
        case 4:
          mes = 'Abril';
          break;
        case 5:
          mes = 'Mayo';
          break;
        case 6:
          mes = 'Junio';
          break;
        case 7:
          mes = 'Julio';
          break;
        case 8:
          mes = 'Agosto';
          break;
        case 9:
          mes = 'Septiembre';
          break;
        case 10:
          mes = 'Octubre';
          break;
        case 11:
          mes = 'Noviembre';
          break;
        case 12:
          mes = 'Diciembre';
          break;
      }
      return mes;
    },
    checkValue(val, reason, details) {
      if (reason === 'month') {
        this.$refs.monthPicker.hide();
        let temp = this.monthPicked.split('/');
        this.form_data.mes = temp[0];
        this.form_data.anno = temp[1];
      }
    },
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'mes') {
        if (!this.$v.form_data.mes.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'vehiculo_empresa_id') {
        if (!this.$v.form_data.vehiculo_empresa_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'km_recorridos') {
        if (!this.$v.form_data.km_recorridos.required) {
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
      this.vehiculo = data.vehiculos.matricula
      this.form_data = Object.assign(this.form_data, data);
    },
    reset_form() {
      this.monthPicked = '';
      this.form_data.mes = '';
      this.form_data.anno = '';
      this.form_data.km_recorridos = '';
      this.form_data.vehiculo_empresa_id = '';
      this.form_error = false;
      this.val = false;
      this.required_error = false;
      this.permiso = true;
      this.add = true;
      this.$v.form_data.$reset();
    },
    openModalSee(data, action, selected) {
      this.action = action;
      if (action === "see") {
        if (selected) {
          this.setFormData(selected);
          this.modalDialogDetail = data;
        } else {
          errorMessage("Debe seleccionar la fila a modificar.");
        }
      }
    },
    async loadData(props) {
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
      await this.getKm_recorridos(data).then((r) => {
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
        await this.saveKm_recorridos(this.form_data);
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
        await this.editKm_recorridos(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
  },
};
</script>

<style scoped></style>
