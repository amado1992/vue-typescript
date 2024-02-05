<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          :data="importacion"
          :columns="columns"
          :visible-columns="visibleColumns"
          row-key="id"
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar importaciones
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
              :disable="importacion.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_importaciones')"
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
                  v-if="scopes.includes('gestionar_importaciones')"
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
                  v-if="scopes.includes('gestionar_importaciones')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteImportaciones(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
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
        <!-- /tabla-->
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
          @submit="accion != 'adicionar' ? update() : save()"
          ref="formImportacion"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == 'adicionar'
                      ? 'Adicionar importación'
                      : 'Actualizar importación'
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
                <div class="col-3 q-pr-sm">
                  <q-input
                    input-class="cursor-pointer"
                    label="Mes *"
                    :value="form_data.mes"
                    @click="$refs.monthPicker.show()"
                    mask="##"
                    name="mes"
                    v-model="form_data.mes"
                    :error-message="mensajesError('mes')"
                    :error="$v.form_data.mes.$error"
                    debounce="1000"
                  >
                    <template v-slot:append>
                      <q-icon class="cursor-pointer">
                        <q-popup-proxy
                          ref="monthPicker"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            minimal
                            mask="MM"
                            :navigation-min-year-month="min"
                            emit-immediately
                            default-view="Months"
                            v-model="form_data.mes"
                            @input="checkValue"
                          />
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>
                <div class="col-3 q-pr-sm">
                  <q-input
                    input-class="cursor-pointer"
                    label="Año *"
                    :value="form_data.anno"
                    @click="$refs.yearPicker.show()"
                    mask="####"
                    name="anno"
                    v-model="form_data.anno"
                    :error-message="mensajesError('anno')"
                    :error="$v.form_data.anno.$error"
                    debounce="1000"
                  >
                    <template v-slot:append>
                      <q-icon class="cursor-pointer">
                        <q-popup-proxy
                          ref="yearPicker"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            minimal
                            mask="YYYY"
                            :navigation-min-year-month="min"
                            emit-immediately
                            default-view="Years"
                            v-model="form_data.anno"
                            @input="checkValue"
                          />
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>
                <div class="col-6">
                  <q-select
                    v-model="form_data.indicador_id"
                    :options="indicador"
                    label="Indicador *"
                    option-value="id"
                    option-label="nombre"
                    options-dense
                    emit-value
                    map-options
                    :error-message="mensajesError('indicador_id')"
                    :error="$v.form_data.indicador_id.$error"
                    debounce="1000"
                  />
                </div>
              </div>
              <div class="row q-px-sm">
                <div class="col-12">
                  <q-select
                    v-model="form_data.empresa_id"
                    emit-value
                    map-options
                    options-dense
                    :options="items_empresa"
                    label="Empresa importadora *"
                    option-label="empresa"
                    option-value="id"
                    :error-message="mensajesError('empresa_id')"
                    :error="$v.form_data.empresa_id.$error"
                  />
                </div>
              </div>
              <div class="row q-px-sm">
                <div class="col-6">
                  <q-input
                    v-model="form_data.plan"
                    label="Plan *"
                    name="plan"
                    type="number"
                    min="0"
                    :error-message="mensajesError('plan')"
                    :error="$v.form_data.plan.$error"
                    debounce="1000"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-input
                    v-model="form_data.real"
                    label="Real *"
                    name="real"
                    type="number"
                    min="0"
                    :error-message="mensajesError('real')"
                    :error="$v.form_data.real.$error"
                    debounce="1000"
                  />
                </div>
              </div>
              <div class="text-red" v-show="form_error">
                Faltan campos por completar
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
import { showDialog } from '../../utils/dialogo';
import { infoMessage } from '../../utils/notificaciones';
import * as message from '../../utils/resources';
import {date} from "quasar";
import { required } from 'vuelidate/lib/validators';

const timeStamp = Date.now()
const formattedString = date.formatDate(timeStamp, 'YYYY/MM',{})

export default {
  name: 'GestionarImportaciones',
  data() {
    return {
      min: formattedString,
      scopes: sessionStorage.getItem('scopes'),
      modalDialog: false,
      filterInput: false,
      loading: false,
      filter: '',
      items_empresa: [],
      selected: [],
      val: false,
      add: true,
      accion: '',
      visibleColumns: ['empresa', 'mes', 'plan', 'real', 'indicador', 'renglon', 'action'],
      meses: [
        {
          value: 1,
          label: 'Enero',
        },
        {
          value: 2,
          label: 'Febrero',
        },
        {
          value: 3,
          label: 'Marzo',
        },
        {
          value: 4,
          label: 'Abril',
        },
        {
          value: 5,
          label: 'Mayo',
        },
        {
          value: 6,
          label: 'Junio',
        },
        {
          value: 7,
          label: 'Julio',
        },
        {
          value: 8,
          label: 'Agosto',
        },
        {
          value: 9,
          label: 'Septiembre',
        },
        {
          value: 10,
          label: 'Octubre',
        },
        {
          value: 11,
          label: 'Noviembre',
        },
        {
          value: 12,
          label: 'Diciembre',
        },
      ],
      form_data: {
        mes: '',
        anno: '',
        plan: null,
        real: null,
        empresa_id: '',
        indicador_id: '',
      },
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
        {
          name: 'mes',
          align: 'left',
          label: 'mes',
          field: 'mes',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'anno',
          align: 'left',
          label: 'año',
          field: 'anno',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'plan',
          align: 'left',
          label: 'plan',
          field: 'plan',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'real',
          align: 'left',
          label: 'real',
          field: 'real',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'empresa',
          align: 'left',
          label: 'empresa',
          field: (row) => row.empresa && row.empresa.empresa,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'indicador',
          align: 'left',
          label: 'indicador',
          field: (row) => row.indicador && row.indicador.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'renglon',
          align: 'left',
          label: 'renglon',
          field: (row) => row.indicador && row.indicador.renglon && row.indicador.renglon.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'action',
          align: 'left',
          label: 'acciones',
          field: 'action',
          headerClasses: 'text-uppercase',
        },
      ],
      form_error: false,
      required_error: false,
    };
  },
  validations: {
    form_data: {
      mes: {
        required,
      },
      anno: {
        required,
      },
      plan: {
        required,
      },
      real: {
        required,
      },
      indicador_id: {
        required,
      },
      empresa_id: {
        required,
      }
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Logística' },
      { label: 'Importaciones' },
      { label: 'Gestionar importaciones' },
    ]);
    this.getIndicador();
    this.getEmpresaImportadora().then((r) => {
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
    ...mapState('importacion', ['importacion']),
    ...mapState('empresa', ['empresa']),
    ...mapState('indicador', ['indicador']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('importacion', [
      'getImportacion',
      'saveImportacion',
      'editImportacion',
      'deleteImportacion',
    ]),
    ...mapActions('empresa', ['getEmpresaImportadora']),
    ...mapActions('indicador', ['getIndicador']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
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
      if (campo === 'anno') {
        if (!this.$v.form_data.anno.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'plan') {
        if (!this.$v.form_data.plan.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'real') {
        if (!this.$v.form_data.real.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'indicador_id') {
        if (!this.$v.form_data.indicador_id.required) {
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
    },
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    reset_form() {
      this.form_data.mes = '';
      this.form_data.anno = '';
      this.form_data.plan = '';
      this.form_data.real = '';
      this.form_data.indicador_id = '';
      this.form_data.empresa_id = '';
      this.form_error = false;
      this.required_error = false;
      this.$v.form_data.$reset();
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
      this.selected = [];
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
      await this.getImportacion(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    filterFn(val, update) {
      update(() => {
        const needle = val.toLowerCase();
        this.options = this.permissions.filter(
          (v) => v.name.toLowerCase().indexOf(needle) > -1
        );
      });
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
    async save() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage(message.inputInvalid);
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        this.setFormData(this.form_data);
        await this.saveImportacion(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      }
      this.setLoading(false);
    },
    async update() {
      if (this.$v.form_data.$invalid) {
        infoMessage(message.inputInvalid);
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.editImportacion(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      }
      this.setLoading(false);
    },
    deleteImportaciones(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteImportacion(selected.id);
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
      } else {
        this.$refs.yearPicker.hide();
      }
    },
  },
};
</script>

<style scoped></style>
