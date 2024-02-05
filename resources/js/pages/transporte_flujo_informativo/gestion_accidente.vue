<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          :data="gestion_accidente"
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
              Gestionar accidentes
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
              :disable="gestion_accidente.length != 0 ? false : true"
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
                <q-btn
                  v-if="scopes.includes('gestionar_dir_transporte_fi')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteGestion_accidentes(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
          <template v-slot:body-cell-fecha="props">
            <q-td rops="props" align="left">
              {{ new Date(props.value).toLocaleDateString() }}
            </q-td>
          </template>
          <template v-slot:body-cell-imputable="props">
            <q-td rops="props" align="left">
              {{ props.row.imputable === true ? 'Si' : 'No' }}
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
          ref="gestAccidenteRadicado"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == 'adicionar'
                      ? 'Adicionar accidente radicado'
                      : 'Actualizar accidente radicado'
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
            <q-card-section class="no-padding">
              <q-stepper
                flat
                header-class="text-uppercase"
                v-model="step"
                ref="stepper"
                color="primary"
                animated
              >
                <q-step
                  :name="1"
                  title="Datos generales"
                  icon="settings"
                  :done="step > 1"
                >
                  <div class="row q-px-sm">
                    <div class="col-4">
                      <q-input
                        v-model="form_data.fecha"
                        label="Fecha del accidente *"
                        name="fecha"
                        mask="####-##-##"
                        :error-message="mensajesError('fecha')"
                        :error="$v.form_data.fecha.$error"
                        debounce="1000"
                      >
                        <template>
                          <q-popup-proxy
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-date
                              v-model="form_data.fecha"
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
                    <div class="col-4 q-px-sm">
                      <q-input
                        v-model="form_data.hora"
                        label="Hora del accidente *"
                        name="hora"
                        mask="##:##"
                        :error-message="mensajesError('hora')"
                        :error="$v.form_data.hora.$error"
                        debounce="1000"
                      >
                        <template>
                          <q-popup-proxy
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-time
                              v-model="form_data.hora"
                              format24h
                              mask="HH:mm"
                            >
                              <div class="row items-center justify-end">
                                <q-btn
                                  v-close-popup
                                  label="Guardar"
                                  color="primary"
                                  flat
                                />
                              </div>
                            </q-time>
                          </q-popup-proxy>
                        </template>
                      </q-input>
                    </div>
                    <div class="col-4">
                      <q-checkbox
                        v-model="form_data.imputable"
                        name="imputable"
                        label="Imputable"
                      />
                    </div>
                  </div>
                  <div class="row q-px-sm">
                    <div class="col-4">
                      <q-select
                        v-model="form_data.clasificacion_accidente_id"
                        emit-value
                        map-options
                        options-dense
                        :options="items_clasificacion_accidente"
                        label="Clasificación del accidente *"
                        option-label="clasificacion_accidente"
                        option-value="id"
                        :error-message="mensajesError('clasificacion_accidente_id')"
                        :error="$v.form_data.clasificacion_accidente_id.$error"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-select
                        v-model="form_data.causa_accidente_id"
                        emit-value
                        map-options
                        options-dense
                        :options="items_causa_accidente"
                        label="Causa del accidente *"
                        option-label="causa_accidente"
                        option-value="id"
                        :error-message="mensajesError('causa_accidente_id')"
                        :error="$v.form_data.causa_accidente_id.$error"
                      />
                    </div>
                    <div class="col-4">
                      <q-select
                        v-model="form_data.vehiculo_empresa_id"
                        emit-value
                        map-options
                        options-dense
                        :options="items_vehiculo_empresa"
                        label="Vehículo de la empresa *"
                        option-label="matricula"
                        option-value="id"
                        :error-message="mensajesError('vehiculo_empresa_id')"
                        :error="$v.form_data.vehiculo_empresa_id.$error"
                      />
                    </div>
                  </div>
                  <div class="row q-px-sm">
                    <div class="col-4">
                      <q-select
                        v-model="form_data.victima_accidentes_id"
                        emit-value
                        map-options
                        options-dense
                        :options="items_victima_accidente"
                        label="Accidente con *"
                        option-label="victima_accidente"
                        option-value="id"
                        :error-message="mensajesError('victima_accidentes_id')"
                        :error="$v.form_data.victima_accidentes_id.$error"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-select
                        v-model="form_data.vehiculo_ajeno_id"
                        emit-value
                        map-options
                        options-dense
                        :options="items_vehiculo_ajeno"
                        label="Vehículo ajeno"
                        option-label="matricula"
                        option-value="id"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        v-model="form_data.perdidas_materiales"
                        label="Daños/Pérdidas materiales *"
                        name="perdidas_materiales"
                        type="number"
                        :error-message="mensajesError('perdidas_materiales')"
                        :error="$v.form_data.perdidas_materiales.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>
                  <div class="row q-px-sm">
                    <div class="col-4">
                      <q-checkbox
                        v-model="form_data.accidente_via"
                        name="accidente_via"
                        label="Accidente en la vía"
                        :disable="form_data.accidente_base === true"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-checkbox
                        v-model="form_data.accidente_base"
                        name="accidente_base"
                        label="Accidente en la base"
                        :disable="form_data.accidente_via === true"
                      />
                    </div>
                  </div>
                </q-step>
                <q-step
                  :name="2"
                  title="Otros datos"
                  icon="settings"
                  :done="step > 1"
                >
                  <div class="row no-wrap">
                    <div class="col-4">
                      <q-input
                        v-model="form_data.lugar"
                        label="Lugar *"
                        name="lugar"
                        :error-message="mensajesError('lugar')"
                        :error="$v.form_data.lugar.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        v-model="form_data.herido"
                        label="No. Heridos *"
                        name="herido"
                        type="number"
                        :error-message="mensajesError('herido')"
                        :error="$v.form_data.herido.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        v-model="form_data.fallecido"
                        label="No. Fallecidos *"
                        name="fallecido"
                        type="number"
                        :error-message="mensajesError('fallecido')"
                        :error="$v.form_data.fallecido.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>
                  <div class="row no-wrap">
                    <div class="col-4">
                      <q-input
                        v-model="form_data.nombre_apellidos"
                        label="Nombre y Apellidos"
                        name="nombre_apellidos"
                        :error-message="mensajesError('nombre_apellidos')"
                        :error="$v.form_data.nombre_apellidos.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        v-model="form_data.annos_experiencia"
                        label="No. años de experiencia *"
                        name="annos_experiencia"
                        type="number"
                        :error-message="mensajesError('annos_experiencia')"
                        :error="$v.form_data.annos_experiencia.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        v-model="form_data.expediente_laboral"
                        label="Expediente laboral *"
                        name="expediente_laboral"
                        :error-message="mensajesError('expediente_laboral')"
                        :error="$v.form_data.expediente_laboral.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>
                  <div class="row no-wrap">
                    <div class="col-4">
                      <q-input
                        v-model="form_data.estacion_pnr"
                        label="Estación de PNR"
                        name="estacion_pnr"
                        :error-message="mensajesError('estacion_pnr')"
                        :error="$v.form_data.estacion_pnr.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        v-model="form_data.tribunal_competente"
                        label="Tribunal competente"
                        name="tribunal_competente"
                        :error-message="mensajesError('tribunal_competente')"
                        :error="$v.form_data.tribunal_competente.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        v-model="form_data.atestado"
                        label="Atestado *"
                        name="atestado"
                        type="number"
                        :error-message="mensajesError('atestado')"
                        :error="$v.form_data.atestado.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>


                </q-step>
              </q-stepper>
              <div class="text-red q-ml-md" v-show="form_error">
                Faltan campos por completar
              </div>
            </q-card-section>
            <q-card-actions align="right">
              <q-btn
                @click="$refs.stepper.next()"
                :color="step === 2 ? 'positive' : 'primary'"
                :label="step === 2 ? 'Listo' : 'Continuar'"
              />
              <q-btn
                v-if="step > 1"
                flat
                color="primary"
                @click="$refs.stepper.previous()"
                label="Atrás"
                class="q-ml-sm"
              />
              <q-space></q-space>
              <q-btn
                :disable="step !== 2 && !form_error"
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
              <span class="text-weight-bold">No. radicación:</span>
              <span class="">{{ radicacion }}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">Lugar:</span>
              <span class="">{{ form_data.lugar }}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">Fecha:</span>
              <span class="">{{ moment(form_data.fecha).format('DD-MM-YYYY') }}</span>
            </div>
          </div>

          <div class="row no-wrap">
            <div class="col-4">
              <span class="text-weight-bold">Hora:</span>
              <span class="">{{ form_data.hora }}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">Imputable:</span>
              <span class="">{{ form_data.imputable === true ? 'Si' : 'No'}}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">Clasificación:</span>
              <span class="">{{ clasificacion }}</span>
            </div>
          </div>

          <div class="row no-wrap">
            <div class="col-4">
              <span class="text-weight-bold">Accidente con:</span>
              <span class="">{{ victima }}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">Fallecidos:</span>
              <span class="">{{ form_data.fallecido }}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">Heridos:</span>
              <span class="">{{ form_data.herido }}</span>
            </div>
          </div>
          <q-expansion-item
            dense
            group="detallesmt"
            label="Otros detalles del accidente"
            header-class="text-subtitle1 text-weight-medium text-uppercase"
          >
            <q-card>
              <q-card-section>
                <div class="row q-pt-md no-wrap">
                  <div class="col-4">
                    <span class="text-weight-bold">Perdidas materiales:</span>
                    <span class="">{{ form_data.perdidas_materiales }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Vehiculo del accidente:</span>
                    <span class="">{{ vehiculo1 }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Vehiculo ajeno:</span>
                    <span class="">{{ vehiculo2 === null ? 'No' : vehiculo2 }}</span>
                  </div>
                </div>

                <div class="row q-pt-md no-wrap">
                  <div class="col-4">
                    <span class="text-weight-bold">Causa del accidente:</span>
                    <span class="">{{ causa }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Años de experiencia:</span>
                    <span class="">{{ form_data.annos_experiencia }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Nombre y apellidos:</span>
                    <span class="">{{ form_data.nombre_apellidos }}</span>
                  </div>
                </div>

                <div class="row q-pt-md no-wrap">
                  <div class="col-4">
                    <span class="text-weight-bold">Expediente laboral:</span>
                    <span class="">{{ form_data.expediente_laboral }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Estación de pnr:</span>
                    <span class="">{{ form_data.estacion_pnr }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Tribunal competente:</span>
                    <span class="">{{ form_data.tribunal_competente }}</span>
                  </div>
                </div>

                <div class="row q-pt-md no-wrap">
                  <div class="col-4">
                    <span class="text-weight-bold">Atestado:</span>
                    <span class="">{{ form_data.atestado }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Accidente en base:</span>
                    <span class="">{{ form_data.accidente_base === true ? 'Si' : 'No' }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Accidente en vía:</span>
                    <span class="">{{ form_data.accidente_via === true ? 'Si' : 'No' }}</span>
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </q-expansion-item>

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
import { showDialog } from '../../utils/dialogo';
import {errorMessage, infoMessage} from '../../utils/notificaciones';
import * as message from '../../utils/resources';
import { required, alpha } from 'vuelidate/lib/validators';
import moment from 'moment';

moment.locale('es');
export default {
  name: 'gestion_accidentes',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      radicacion : "",
      clasificacion: "",
      victima: "",
      vehiculo1: "",
      vehiculo2: "",
      causa : "",
      items_causa_accidente: [],
      items_clasificacion_accidente: [],
      items_vehiculo_ajeno: [],
      items_vehiculo_empresa: [],
      items_victima_accidente: [],
      step: 1,
      form_data: {
        fecha: '',
        lugar: '',
        hora: '',
        imputable: false,
        accidente_via: false,
        accidente_base: false,
        tipo_combustible: '',
        clasificacion_accidente_id: '',
        victima_accidentes_id: '',
        fallecido: '',
        herido: '',
        perdidas_materiales: '',
        vehiculo_empresa_id: '',
        vehiculo_ajeno_id: '',
        causa_accidente_id: '',
        annos_experiencia: '',
        nombre_apellidos: '',
        expediente_laboral: '',
        estacion_pnr: '',
        tribunal_competente: '',
        atestado: ''
      },
      modalDialog: false,
      modalDialogDetail: false,
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
        'radicacion',
        'fecha',
        'hora',
        'imputable',
        'clasificacion_accidente_id',
        'action'
      ],
      columns: [
        {
          name: 'radicacion',
          align: 'left',
          label: 'No. radicación',
          field: 'radicacion',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'fecha',
          align: 'left',
          label: 'Fecha',
          field: 'fecha',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'hora',
          align: 'left',
          label: 'Hora',
          field: 'hora',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'imputable',
          align: 'left',
          label: 'imputable',
          field: 'imputable',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'clasificacion_accidente_id',
          align: 'left',
          label: 'Clasificación',
          field: (row) => row.clasificacionaccidentes && row.clasificacionaccidentes.clasificacion_accidente,
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
      fecha: {
        required,
      },
      lugar: {
        required,
      },
      hora: {
        required,
      },
      imputable: {
        required,
      },
      clasificacion_accidente_id: {
        required,
      },
      victima_accidentes_id: {
        required,
      },
      fallecido: {
        required,
      },
      herido: {
        required,
      },
      perdidas_materiales: {
        required,
      },
      vehiculo_empresa_id: {
        required,
      },
      causa_accidente_id: {
        required,
      },
      annos_experiencia: {
        required,
      },
      nombre_apellidos: {
        required, alpha
      },
      expediente_laboral: {
        required,
      },
      estacion_pnr: {
        required,
      },
      tribunal_competente: {
        required,
      },
      atestado: {
        required,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Gestionar accidentes' },
    ]);
    this.getClasificacion_accidentes().then((r) => {
      this.items_clasificacion_accidente = r;
    });
    this.getCausa_accidentes().then((r) => {
      this.items_causa_accidente = r;
    });
    this.listMedioTransporte().then((r) => {
      this.items_vehiculo_empresa = r;
    });
    this.getVehiculo_ajenos().then((r) => {
      this.items_vehiculo_ajeno = r;
    });
    this.getVictima_accidentes().then((r) => {
      this.items_victima_accidente = r;
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
    ...mapState('gestion_accidente', ['gestion_accidente']),
  },
  methods: {
    moment,
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('gestion_accidente', [
      'listGestion_accidente',
      'saveGestion_accidente',
      'editGestion_accidente',
      'deleteGestion_accidente',
    ]),
    ...mapActions('clasificacion_accidente', ['getClasificacion_accidentes']),
    ...mapActions('causa_accidente', ['getCausa_accidentes']),
    ...mapActions('victima_accidente', ['getVictima_accidentes']),
    ...mapActions('medio_transporte', ['listMedioTransporte']),
    ...mapActions('vehiculo_ajeno', ['getVehiculo_ajenos']),
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'fecha') {
        if (!this.$v.form_data.fecha.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'lugar') {
        if (!this.$v.form_data.lugar.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'hora') {
        if (!this.$v.form_data.hora.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'imputable') {
        if (!this.$v.form_data.imputable.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'clasificacion_accidente_id') {
        if (!this.$v.form_data.clasificacion_accidente_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'victima_accidentes_id') {
        if (!this.$v.form_data.victima_accidentes_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'fallecido') {
        if (!this.$v.form_data.fallecido.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'herido') {
        if (!this.$v.form_data.herido.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'perdidas_materiales') {
        if (!this.$v.form_data.perdidas_materiales.required) {
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
      if (campo === 'causa_accidente_id') {
        if (!this.$v.form_data.causa_accidente_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'annos_experiencia') {
        if (!this.$v.form_data.annos_experiencia.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'nombre_apellidos') {
        if (!this.$v.form_data.nombre_apellidos.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.nombre_apellidos.alpha) {
          this.required_error = true;
          return 'Solo se aceptan caracteres alfabéticos';
        }
      }
      if (campo === 'expediente_laboral') {
        if (!this.$v.form_data.expediente_laboral.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'estacion_pnr') {
        if (!this.$v.form_data.estacion_pnr.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'tribunal_competente') {
        if (!this.$v.form_data.tribunal_competente.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'atestado') {
        if (!this.$v.form_data.atestado.required) {
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
      this.form_data.fecha = '';
      this.form_data.lugar = '';
      this.form_data.hora = '';
      this.form_data.imputable = false;
      this.form_data.accidente_via = false;
      this.form_data.accidente_base = false;
      this.form_data.clasificacion_accidente_id = '';
      this.form_data.victima_accidentes_id = '';
      this.form_data.fallecido = '';
      this.form_data.herido = '';
      this.form_data.perdidas_materiales = '';
      this.form_data.vehiculo_empresa_id = '';
      this.form_data.vehiculo_ajeno_id = '';
      this.form_data.causa_accidente_id = '';
      this.form_data.annos_experiencia = '';
      this.form_data.nombre_apellidos = '';
      this.form_data.expediente_laboral = '';
      this.form_data.estacion_pnr = '';
      this.form_data.tribunal_competente = '';
      this.form_data.atestado = '';
      this.step = 1;
      this.form_error = false;
      this.required_error = false;
      this.$v.form_data.$reset();
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
    setFormData(data) {
      this.radicacion = data.radicacion
      this.clasificacion = data.clasificacionaccidentes.clasificacion_accidente
      this.victima = data.victimaaccidentes.victima_accidente
      this.vehiculo1 = data.vehiculoempresas.matricula
      if (this.vehiculo2)
        this.vehiculo2 = data.vehiculoajenos.matricula
      else
        this.vehiculo2 = null
      this.causa = data.causaaccidentes.causa_accidente
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
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.listGestion_accidente(data).then((r) => {
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
        infoMessage(message.inputInvalid);
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.saveGestion_accidente(this.form_data);
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
        await this.editGestion_accidente(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      }
      this.setLoading(false);
    },
    deleteGestion_accidentes(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteGestion_accidente(selected.id);
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
