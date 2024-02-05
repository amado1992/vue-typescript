<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          :data="indicadoresl210"
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
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar indicadores
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
              :disable="indicadoresl210.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_indicadores_lin')"
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
                  v-if="scopes.includes('gestionar_indicadores_lin')"
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
                  v-if="scopes.includes('gestionar_indicadores_lin')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteIndicadores(props.row)"
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
          ref="formIndicadorL210"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == 'adicionar'
                      ? 'Adicionar indicador'
                      : 'Actualizar indicador'
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
                <q-input
                  class="col-4"
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
              <div class="row q-pt-sm q-px-sm">
                <div class="col-4">
                  <q-select
                    v-model="form_data.licsanitaria_id"
                    emit-value
                    map-options
                    options-dense
                    :options="licencias"
                    label="Lic. sanitaria *"
                    option-label="estado"
                    option-value="id"
                    name="licsanitaria_id"
                    :error-message="mensajesError('licsanitaria_id')"
                    :error="$v.form_data.licsanitaria_id.$error"
                    debounce="1000"
                  />
                </div>
                <div class="col-4 q-px-sm">
                  <q-select
                    v-model="form_data.avalcitma_id"
                    emit-value
                    map-options
                    options-dense
                    :options="avalcitma"
                    label="Aval Citma *"
                    option-label="estado"
                    option-value="id"
                    name="avalcitma_id"
                    :error-message="mensajesError('avalcitma_id')"
                    :error="$v.form_data.avalcitma_id.$error"
                    debounce="1000"
                  />
                </div>
                <div class="col-4">
                  <q-select
                    v-model="form_data.avalapci_id"
                    emit-value
                    map-options
                    options-dense
                    :options="avalapci"
                    label="Aval Apci *"
                    option-label="estado"
                    option-value="id"
                    name="avalapci_id"
                    :error-message="mensajesError('avalapci_id')"
                    :error="$v.form_data.avalapci_id.$error"
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
import { required } from 'vuelidate/lib/validators';

export default {
  name: 'GestIndicadoresL210',
  data() {
    return {
      visibleColumns: [
        'mes',
        'anno',
        'instalaciones',
        'licencia',
        'avalcitma',
        'avalapci',
        'action',
      ],
      scopes: sessionStorage.getItem('scopes'),
      monthPicked: [],
      modalDialog: false,
      filterInput: false,
      loading: false,
      filter: '',
      selected: [],
      val: false,
      add: true,
      accion: '',
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
        id: null,
        mes: null,
        anno: null,
        licsanitaria_id: null,
        avalcitma_id: null,
        avalapci_id: null,
      },
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
        {
          name: 'id',
          align: 'left',
          label: 'id',
          field: 'id',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
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
          name: 'licencia',
          align: 'left',
          label: 'lic. sanitaria',
          field: (row) => row.licencia && row.licencia.estado,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'avalcitma',
          align: 'left',
          label: 'aval citma',
          field: (row) => row.avalcitma && row.avalcitma.estado,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'avalapci',
          align: 'left',
          label: 'aval apci',
          field: (row) => row.avalapci && row.avalapci.estado,
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
      licencias: [],
      avalcitma: [],
      avalapci: [],
      form_error: false,
      required_error: false,
    };
  },
  validations: {
    form_data: {
      mes: {
        required,
      },
      licsanitaria_id: {
        required,
      },
      avalcitma_id: {
        required,
      },
      avalapci_id: {
        required,
      },
    },
  },
  async mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Indicadores L210' },
      { label: 'Gestionar Indicadores' },
    ]),
      (this.licencias = await this.getLicSanitarias());
    this.avalcitma = await this.getAvalesCitma();
    this.avalapci = await this.getAvalesApci();
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
    ...mapState('indicadoresl210', ['indicadoresl210']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('indicadoresl210', [
      'getIndicadorl210',
      'saveIndicadorl210',
      'editIndicadorl210',
      'deleteIndicadorl210',
    ]),
    ...mapActions('lic_sanitaria', ['getLicSanitarias']),
    ...mapActions('aval_citma', ['getAvalesCitma']),
    ...mapActions('aval_apci', ['getAvalesApci']),

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
      if (campo === 'licsanitaria_id') {
        if (!this.$v.form_data.licsanitaria_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'avalcitma_id') {
        if (!this.$v.form_data.avalcitma_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'avalapci_id') {
        if (!this.$v.form_data.avalapci_id.required) {
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
      this.form_data.avalapci_id = '';
      this.form_data.licsanitaria_id = '';
      this.form_data.avalcitma_id = '';
      this.monthPicked = '';
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
      await this.getIndicadorl210(data).then((r) => {
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
        await this.saveIndicadorl210(this.form_data);
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
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage(message.inputInvalid);
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.editIndicadorl210(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      }
      this.setLoading(false);
    },
    deleteIndicadores(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteIndicadorl210(selected.id);
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
        let temp = this.monthPicked.split('/');
        this.form_data.mes = temp[0];
        this.form_data.anno = temp[1];
      }
    },
  },
};
</script>

<style scoped></style>
