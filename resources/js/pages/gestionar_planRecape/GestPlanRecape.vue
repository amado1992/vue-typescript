<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          :data="dataModel"
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
              Gestionar plan de recape
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
              :disable="dataModel.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_dir_transporte_fi')"
              :disable="moment().month() <= 6"
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
                <q-btn
                  v-if="scopes.includes('eliminar_dir_transporte_fi')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="eliminarPlanRecape(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
                <!--ver mas-->
                <q-btn size="sm" dense icon="more_vert" color="grey" flat>
                  <q-tooltip>Ver más</q-tooltip>
                  <q-menu>
                    <q-list dense style="min-width: 100px;">
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModal(true, 'detalles', props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px;"
                        >
                          Ver detalles
                        </q-item-section>
                      </q-item>

                      <q-separator />
                      <q-item
                        dense
                        clickable
                        v-ripple
                        @click.prevent="
                          openModal(true, 'actualizarCumplimiento', props.row)
                        "
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="edit" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px;"
                        >
                          Actualizar plan
                        </q-item-section>
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
                <!--End ver mas-->
              </div>
            </q-td>
          </template>
          <template v-slot:body-cell-fecha="props">
            <q-td :props="props">
              {{ moment(props.row.fecha).format('YYYY-MM-DD') }}
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
      <q-card style="width: 700px; max-width: 70vw;">
        <q-form
          @reset="reset_form"
          @submit="accion != 'adicionar' ? update() : save()"
          ref="gestPlanRecape"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == 'adicionar'
                      ? 'Adicionar plan de recape'
                      : 'Actualizar plan de recape'
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
                <div class="col-6">
                  <q-input
                    v-model="form_data.potencial"
                    label="Potencial *"
                    name="potencial"
                    :error-message="mensajesError('potencial')"
                    :error="$v.form_data.potencial.$error"
                    debounce="1000"
                  />
                </div>
                <div class="col-6 q-px-sm">
                  <q-input
                    v-model="form_data.plan_recape"
                    label="Plan *"
                    name="planrecape"
                    :error-message="mensajesError('plan_recape')"
                    :error="$v.form_data.plan_recape.$error"
                    debounce="1000"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <q-input
                    type="textarea"
                    autogrow
                    v-model="form_data.observaciones"
                    label="Observaciones"
                    name="observaciones"
                  />
                </div>
              </div>
              <div class="text-red q-ml-md" v-show="form_error">
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
    <!-- End Dialog gestionar -->
    <!--Dialog ver detalles -->
    <q-dialog
      v-model="modalDialogDetail"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 650px; max-width: 90vw;">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>Ver detalles</q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section class="q-pa-md q-gutter-lg no-wrap">
          <div class="row no-wrap">
            <div class="col-4">
              <span class="text-weight-bold">Osde:</span>
              <span class="">{{
                detallesData &&
                detallesData.instalaciones &&
                detallesData.instalaciones.osdes &&
                detallesData.instalaciones.osdes.nombre
              }}</span>
            </div>
            <div class="col-3">
              <span class="text-weight-bold">Provincia:</span>
              <span class="">{{
                detallesData &&
                detallesData.instalaciones &&
                detallesData.instalaciones.provincia &&
                detallesData.instalaciones.provincia.nombre
              }}</span>
            </div>
            <div class="col-2">
              <span class="text-weight-bold">Año:</span>
              <span class="">{{ detallesData.anno }}</span>
            </div>
            <div class="col-3">
              <span class="text-weight-bold">Fecha:</span>
              <span class="">{{
                moment(detallesData.fecha).format('YYYY-MM-DD')
              }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <span class="text-weight-bold">Observaciones:</span>
              <span>{{ detallesData.observaciones }}</span>
            </div>
          </div>
          <q-separator inset></q-separator>
          <div class="row justify-center">
            <q-table
              class="full-width"
              flat
              dense
              :data="detallesActualizaciones"
              :columns="columnsActualizaciones"
              row-key="id"
              :pagination.sync="pagination"
              :loading="this.getterLoading()"
              loading-label="Cargando elementos"
              :rows-per-page-options="[10, 25, 50]"
              binary-state-sort
              no-data-label="No se encontraron elementos a mostrar"
            >
              <template v-slot:body-cell-fecha_cumplimiento="props">
                <q-td :props="props">
                  {{
                    moment(props.row.fecha_cumplimiento).format('YYYY-MM-DD')
                  }}
                </q-td>
              </template>
              <template v-slot:top>
                <div class="text-weight-bold">
                  Actualizaciones plan de recape
                </div>
              </template>
            </q-table>
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
    <!-- Actualizar plan de recape-->
    <q-dialog
      v-model="modalDialogAct"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vm;">
        <q-form
          @reset="reset_form"
          @submit="updateCumplimientoPlan()"
          ref="actualizarPlanRecape"
        >
          <q-card-section class="no-padding">
            <q-toolbar class="bg-primary text-white">
              <q-toolbar-title>Actualizar plan de recape</q-toolbar-title>
              <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
                <q-tooltip>Cerrar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </q-card-section>
          <q-card-section>
            <div class="row q-px-sm">
              <div class="col-6">
                <q-input
                  v-model="form_data_actualizar.fecha_cumplimiento"
                  label="Fecha *"
                  name="fecha_cumplimiento"
                  mask="####-##-##"
                  :error-message="mensajesError('fecha_cumplimiento')"
                  :error="$v.form_data_actualizar.fecha_cumplimiento.$error"
                  debounce="1000"
                >
                  <template>
                    <q-popup-proxy
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date
                        v-model="form_data_actualizar.fecha_cumplimiento"
                        mask="YYYY-MM-DD"
                        minimal
                      >
                        <div class="row items-center justify-end">
                          <q-btn
                            v-close-popup
                            label="Guardar"
                            color="primary"
                            flat
                          />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </template>
                </q-input>
              </div>
              <div class="col-6 q-px-sm">
                <q-input
                  type="number"
                  v-model="form_data_actualizar.recapados"
                  label="Recapados *"
                  name="recapados"
                  :error-message="mensajesError('recapados')"
                  :error="$v.form_data_actualizar.recapados.$error"
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
    <!-- End actualizar plan de recape -->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../utils/dialogo';
import { infoMessage } from '../../utils/notificaciones';
import * as message from '../../utils/resources';
import { required, maxValue, integer } from 'vuelidate/lib/validators';
import moment from 'moment';
import Vue from 'vue';

moment.locale('es');
const greaterThanZero = (value) => value > 0;
export default {
  name: 'GestPlanRecape',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      dataModel: [],
      detallesData: [],
      detallesActualizaciones: [],
      form_data: {
        potencial: '',
        plan_recape: '',
        observaciones: '',
      },
      form_data_actualizar: {
        plan_id: null,
        fecha_cumplimiento: '',
        recapados: null,
      },
      modalDialog: false,
      modalDialogDetail: false,
      modalDialogAct: false,
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
        'id',
        'osde',
        'provincia',
        'potencial',
        'anno',
        'plan_recape',
        'fecha',
        'action',
      ],
      columns: [
        {
          name: 'id',
          align: 'left',
          label: 'No.',
          field: 'id',
          sortable: true,
          headerClasses: '',
        },
        {
          name: 'osde',
          align: 'left',
          label: 'osde',
          field: (row) =>
            row.instalaciones &&
            row.instalaciones.osdes &&
            row.instalaciones.osdes.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'provincia',
          align: 'left',
          label: 'provincia',
          field: (row) =>
            row.instalaciones &&
            row.instalaciones.osdes &&
            row.instalaciones.provincia &&
            row.instalaciones.provincia.nombre,
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
          name: 'potencial',
          align: 'left',
          label: 'potencial',
          field: 'potencial',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'plan_recape',
          align: 'left',
          label: 'plan',
          field: 'plan_recape',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'fecha',
          align: 'left',
          label: 'fecha',
          field: 'fecha',
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
      columnsActualizaciones: [
        {
          name: 'id',
          align: 'left',
          label: 'No.',
          field: 'id',
          sortable: true,
          headerClasses: '',
        },
        {
          name: 'fecha_cumplimiento',
          align: 'left',
          label: 'fecha',
          field: 'fecha_cumplimiento',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'recapados',
          align: 'left',
          label: 'recapados',
          field: 'recapados',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
      ],
    };
  },
  validations: {
    form_data: {
      potencial: {
        required,
        integer,
      },
      plan_recape: {
        required,
        integer,
        maxValue: greaterThanZero,
      },
    },
    form_data_actualizar: {
      fecha_cumplimiento: {
        required,
      },
      recapados: {
        required,
        integer,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Gestionar plan de recape' },
    ]);
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
  computed: {},
  methods: {
    moment,
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('plan_recape', [
      'listPlanRecape',
      'savePlanRecape',
      'editPlanRecape',
      'deletePlanRecape',
    ]),
    ...mapActions('cumplimiento_plan_recape', [
      'getCumplimientoPlanRecape',
      'saveCumplimientoPlanRecape',
    ]),
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'potencial') {
        if (!this.$v.form_data.potencial.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.potencial.integer) {
          return message.vInteger;
        }
      }
      if (campo === 'plan_recape') {
        if (!this.$v.form_data.plan_recape.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.plan_recape.integer) {
          return message.vInteger;
        }
        if (!this.$v.form_data.plan_recape.maxValue) {
          return message.vMayor0;
        }
        if (!this.$v.form_data.plan_recape.minValue) {
          return 'dsdsd';
        }
      }
      if (campo === 'fecha_cumplimiento') {
        if (!this.$v.form_data_actualizar.fecha_cumplimiento.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'recapados') {
        if (!this.$v.form_data_actualizar.recapados.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data_actualizar.recapados.integer) {
          return message.vInteger;
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
      this.form_data.potencial = '';
      this.form_data.plan_recape = '';
      this.form_data.observaciones = '';
      this.form_data_actualizar.fecha_cumplimiento = '';
      this.form_data_actualizar.recapados = null;
      this.form_data_actualizar.plan_id = null;
      this.detallesActualizaciones = [];
      this.form_error = false;
      this.required_error = false;
      this.$v.form_data.$reset();
      this.$v.form_data_actualizar.$reset();
    },
    closeModal() {
      this.modalDialog = false;
      this.modalDialogDetail = false;
      this.modalDialogAct = false;
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
        case 'detalles':
          if (rowData) {
            this.modalDialogDetail = data;
            this.detallesData = rowData;
            this.loadDataCumplimiento(rowData.id);
          } else {
            infoMessage('Debe seleccionar un elemento para ver los detalles.');
          }
          break;
        case 'actualizarCumplimiento':
          if (rowData) {
            this.form_data_actualizar.plan_id = rowData.id;
            this.modalDialogAct = data;
          } else {
            infoMessage('Debe seleccionar un elemento para modificarlo.');
          }
          break;

        default:
          break;
      }
    },
    loadDataCumplimiento(id) {
      this.setLoading(true);
      const data = {
        id: id,
      };
      return Vue.prototype.$axios
        .post('/api/listcumplimiento_planrecape', data)
        .then((result) => {
          this.detallesActualizaciones = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        });
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
      await this.listPlanRecape(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.dataModel = r;
        this.setLoading(false);
      });
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
        await this.savePlanRecape(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      }
      this.setLoading(false);
    },
    async updateCumplimientoPlan() {
      this.$v.form_data_actualizar.$touch();
      if (this.$v.form_data_actualizar.$invalid) {
        infoMessage(message.inputInvalid);
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.saveCumplimientoPlanRecape(this.form_data_actualizar);
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
        await this.editPlanRecape(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      }
      this.setLoading(false);
    },
    eliminarPlanRecape(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deletePlanRecape(selected.id);
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

<style></style>
