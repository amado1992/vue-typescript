<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          table-header-class="text-uppercase"
          :data="almacen"
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
              Gestionar almacen
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
              :disable="almacen.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_almacenes')"
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
                  v-if="scopes.includes('gestionar_almacenes')"
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
                  v-if="scopes.includes('gestionar_almacenes')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteAlmacenes(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
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
        <q-toolbar class="bg-primary text-white">
          <q-toolbar-title>
            {{
              this.accion == 'adicionar'
                ? 'Adicionar almacen'
                : 'Actualizar almacen'
            }}
          </q-toolbar-title>
          <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
            <q-tooltip>Cerrar</q-tooltip>
          </q-btn>
        </q-toolbar>
        <q-card-section class="q-px-md">

          <q-stepper v-model="step" vertical color="primary" animated v-scroll flat>

            <q-step :name="1" title="Datos generales" icon="settings" :done="step > 1">
              <div class="row">
                <div class="col-6">
                  <q-input dense v-model="form_data.nombre" label="Nombre *" name="nombre"
                           :error-message="mensajesError('nombre')"
                           :error="$v.form_data.nombre.$error"/>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <q-input dense v-model="form_data.direccion" label="Dirección *" name="direccion"
                           type="textarea" autogrow :error-message="mensajesError('direccion')"
                           :error="$v.form_data.direccion.$error"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <q-input dense type="number" v-model="form_data.latitud" label="Latitud *"
                           name="lat" :error-message="mensajesError('latitud')"
                           :error="$v.form_data.latitud.$error"
                  />
                </div>
                <div class="col q-pl-sm">
                  <q-input dense type="number" v-model="form_data.longitud" label="Longitud *"
                           name="long" :error-message="mensajesError('longitud')"
                           :error="$v.form_data.longitud.$error"
                  />
                </div>
                <div class="q-pl-sm">
                  <q-checkbox
                    v-model="mapa" class="text-grey-8 text-weight-light" label="Mostrar mapa"
                  />
                </div>
              </div>
              <q-card-section v-if="mapa" class="no-padding" style="width: 290px;">
                <Geolocalizacion></Geolocalizacion>
              </q-card-section>
              <q-stepper-navigation>
                <q-btn
                  :disable="!(form_data.nombre && form_data.direccion && form_data.latitud && form_data.longitud)"
                  @click="step = 2" color="primary" label="Siguiente"/>
              </q-stepper-navigation>
            </q-step>

            <q-step :name="2" title="Datos técnicos" icon="settings_applications" :done="step > 2">
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.tipo_almacen_id" :options="items_tipo_almacen"
                            label="Tipo de almacén *" options-dense option-value="id" option-label="nombre"
                            emit-value map-options :error-message="mensajesError('tipo_almacen_id')"
                            :error="$v.form_data.tipo_almacen_id.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense v-model="form_data.temperatura_id" :options="items_temperatura"
                            label="Temperatura *" options-dense option-value="id" option-label="nombre"
                            emit-value map-options :error-message="mensajesError('temperatura_id')"
                            :error="$v.form_data.temperatura_id.$error"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.categoria_id" :options="items_categoria"
                            label="Categoría *" options-dense option-value="id" option-label="nombre"
                            emit-value map-options :error-message="mensajesError('categoria_id')"
                            :error="$v.form_data.categoria_id.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense v-model="form_data.distribucion_id" :options="items_distribucion"
                            label="Distribución *" options-dense option-value="id" option-label="nombre"
                            emit-value map-options :error-message="mensajesError('distribucion_id')"
                            :error="$v.form_data.distribucion_id.$error"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.actividad_id" :options="items_actividad"
                            label="Actividad *" options-dense option-value="id" option-label="nombre"
                            emit-value map-options :error-message="mensajesError('actividad_id')"
                            :error="$v.form_data.actividad_id.$error"
                  />
                </div>
              </div>

              <q-stepper-navigation>
                <q-btn flat @click="step = 1" color="primary" label="Anterior" class="q-ml-sm"/>
                <q-btn
                  :disable="!(form_data.tipo_almacen_id && form_data.temperatura_id && form_data.categoria_id && form_data.distribucion_id && form_data.actividad_id)"
                  @click="step = 3" color="primary" label="Siguiente"/>
              </q-stepper-navigation>
            </q-step>

            <q-step :name="3" title="Dimensiones" icon="square_foot" :done="step > 3">
              <div class="row">
                <div class="col-6">
                  <q-input dense v-model="form_data.largo" type="number" label="Largo *" name="largo"
                           :error-message="mensajesError('largo')"
                           :error="$v.form_data.largo.$error"/>
                </div>
                <div class="col-6 q-pl-sm">
                  <q-input dense v-model="form_data.ancho" type="number" label="Ancho *" name="ancho"
                           :error-message="mensajesError('ancho')"
                           :error="$v.form_data.ancho.$error"/>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <q-input dense v-model="form_data.puntal_libre" type="number" label="Puntal libre *"
                           name="puntal_libre" :error-message="mensajesError('puntal_libre')"
                           :error="$v.form_data.puntal_libre.$error"/>
                </div>
                <div class="col-6 q-pl-sm">
                  <q-input dense v-model="form_data.altura_prom_estiba" type="number" label="Altura de estiba(promedio) *"
                           name="altura_prom_estiba" :error-message="mensajesError('altura_prom_estiba')"
                           :error="$v.form_data.altura_prom_estiba.$error"/>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn flat @click="step = 2" color="primary" label="Anterior" class="q-ml-sm"/>
                <q-btn
                  :disable="!(form_data.largo && form_data.ancho && form_data.puntal_libre && form_data.altura_prom_estiba)"
                  @click="step = 4" color="primary" label="Siguiente"/>
              </q-stepper-navigation>
            </q-step>

            <q-step :name="4" title="Equipamiento" icon="build" :done="step > 4">
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.cant_montacargas" :options="options"
                            label="Cant. montacargas *" options-dense
                            :error-message="mensajesError('cant_montacargas')"
                            :error="$v.form_data.cant_montacargas.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense v-model="form_data.cant_transpaletas" :options="options"
                            label="Cant. transpaletas *" options-dense emit-value map-options
                            :error-message="mensajesError('cant_transpaletas')"
                            :error="$v.form_data.cant_transpaletas.$error"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.cant_esteras" :options="options"
                            label="Cant. esteras *" options-dense emit-value map-options
                            :error-message="mensajesError('cant_esteras')"
                            :error="$v.form_data.cant_esteras.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense v-model="form_data.cant_basculas" :options="options"
                            label="Cant. basculas *" options-dense emit-value map-options
                            :error-message="mensajesError('cant_basculas')"
                            :error="$v.form_data.cant_basculas.$error"
                  />
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn flat @click="step = 3" color="primary" label="Anterior" class="q-ml-sm"/>
                <q-btn
                  :disable="!(form_data.cant_montacargas && form_data.cant_transpaletas && form_data.cant_esteras && form_data.cant_basculas)"
                  @click="step = 5" color="primary" label="Siguiente"/>
              </q-stepper-navigation>
            </q-step>

            <q-step :name="5" title="Medios de almacenamiento" icon="list_alt" :done="step > 5">
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.cant_estantes" :options="options"
                            label="Cant. estantes *" options-dense emit-value map-options
                            :error-message="mensajesError('cant_estantes')"
                            :error="$v.form_data.cant_estantes.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense v-model="form_data.cant_paletas" :options="options"
                            label="Cant. paletas *" options-dense emit-value map-options
                            :error-message="mensajesError('cant_paletas')"
                            :error="$v.form_data.cant_paletas.$error"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.cant_cajas_paletas" :options="options"
                            label="Cant. paletas(cajas) *" options-dense emit-value map-options
                            :error-message="mensajesError('cant_cajas_paletas')"
                            :error="$v.form_data.cant_cajas_paletas.$error"
                  />
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn flat @click="step = 4" color="primary" label="Anterior" class="q-ml-sm"/>
                <q-btn
                  :disable="!(form_data.cant_estantes && form_data.cant_paletas && form_data.cant_cajas_paletas)"
                  @click="step = 6" color="primary" label="Siguiente"/>
              </q-stepper-navigation>
            </q-step>

            <q-step :name="6" title="Estado constructivo" icon="pan_tool" :done="step > 6">
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.estado_constructivo" :options="options"
                            label="Estado constructivo *" options-dense emit-value map-options
                            :error-message="mensajesError('estado_constructivo')"
                            :error="$v.form_data.estado_constructivo.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense v-model="form_data.estado_estructura" :options="options"
                            label="Estado de estructura *" options-dense emit-value map-options
                            :error-message="mensajesError('estado_estructura')"
                            :error="$v.form_data.estado_estructura.$error"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.estado_paredes" :options="options"
                            label="Estado de paredes *" options-dense emit-value map-options
                            :error-message="mensajesError('estado_paredes')"
                            :error="$v.form_data.estado_paredes.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense v-model="form_data.estado_piso" :options="options"
                            label="Estado del piso *" options-dense emit-value map-options
                            :error-message="mensajesError('estado_piso')"
                            :error="$v.form_data.estado_piso.$error"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.estado_puertas" :options="options"
                            label="Estado de puertas *" options-dense emit-value map-options
                            :error-message="mensajesError('estado_puertas')"
                            :error="$v.form_data.estado_puertas.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense v-model="form_data.estado_ventanas" :options="options"
                            label="Estado de ventanas *" options-dense emit-value map-options
                            :error-message="mensajesError('estado_ventanas')"
                            :error="$v.form_data.estado_ventanas.$error"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.estado_techo" :options="options"
                            label="Estado del techo *" options-dense emit-value map-options
                            :error-message="mensajesError('estado_techo')"
                            :error="$v.form_data.estado_techo.$error"
                  />
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn flat @click="step = 5" color="primary" label="Anterior" class="q-ml-sm"/>
                <q-btn
                  :disable="!(form_data.estado_constructivo && form_data.estado_estructura && form_data.estado_paredes && form_data.estado_piso && form_data.estado_puertas && form_data.estado_ventanas && form_data.estado_techo)"
                  @click="step = 7" color="primary" label="Siguiente"/>
              </q-stepper-navigation>
            </q-step>

            <q-step :name="7" title="Ventilación" icon="ac_unit" :done="step > 7">
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.ventilacion_natural" :options="options1"
                            label="Ventilación natural *" options-dense
                            emit-value map-options
                            :error-message="mensajesError('ventilacion_natural')"
                            :error="$v.form_data.ventilacion_natural.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense
                            v-model="form_data.ventilacion_artificial" :options="options1"
                            label="Ventilación artificial *" options-dense
                            emit-value map-options
                            :error-message="mensajesError('ventilacion_artificial')"
                            :error="$v.form_data.ventilacion_artificial.$error"
                  />
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn flat @click="step = 6" color="primary" label="Anterior" class="q-ml-sm"/>
                <q-btn
                  :disable="!(form_data.ventilacion_natural && form_data.ventilacion_artificial)"
                  @click="step = 8" color="primary" label="Siguiente"/>
              </q-stepper-navigation>
            </q-step>

            <q-step :name="8" title="Iluminación" icon="lightbulb" :done="step > 8">
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.iluminacion_natural" :options="options1"
                            label="Iluminación natural *" options-dense
                            emit-value map-options
                            :error-message="mensajesError('iluminacion_natural')"
                            :error="$v.form_data.iluminacion_natural.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense
                            v-model="form_data.iluminacion_artificial" :options="options1"
                            label="Iluminación artificial *" options-dense
                            emit-value map-options
                            :error-message="mensajesError('iluminacion_artificial')"
                            :error="$v.form_data.iluminacion_artificial.$error"
                  />
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn flat @click="step = 7" color="primary" label="Anterior" class="q-ml-sm"/>
                <q-btn
                  :disable="!(form_data.iluminacion_natural && form_data.iluminacion_artificial)"
                  @click="step = 9" color="primary" label="Siguiente"/>
              </q-stepper-navigation>
            </q-step>

            <q-step :name="9" title="Protección" icon="security" :done="step > 9">
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.prot_contra_intrusos" :options="options2"
                            label="Protección contra intrusos *" options-dense
                            emit-value map-options
                            :error-message="mensajesError('prot_contra_intrusos')"
                            :error="$v.form_data.prot_contra_intrusos.$error"
                  />
                </div>
                <div class="col-6 q-pl-sm">
                  <q-select dense v-model="form_data.prot_contra_incendios" :options="options2"
                            label="Protección contra incendios *" options-dense emit-value map-options
                            :error-message="mensajesError('prot_contra_incendios')"
                            :error="$v.form_data.prot_contra_incendios.$error"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <q-input dense :disable="!(form_data.prot_contra_incendios || form_data.prot_contra_intrusos)"
                           type="textarea" autogrow v-model="form_data.prot_observaciones"
                           label="Observaciones" name="prot_observaciones"/>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn flat @click="step = 8" color="primary" label="Anterior" class="q-ml-sm"/>
                <q-btn
                  :disable="!(form_data.prot_contra_incendios && form_data.prot_contra_intrusos)"
                  @click="step = 10" color="primary" label="Siguiente"/>
              </q-stepper-navigation>
            </q-step>

            <q-step :name="10" title="Inversión y mantenimiento constructivo" icon="attach_money">
              La Empresa tiene contemplado en su Plan los recursos necesarios para el Almacén;
              de estar declarado No Potencial a categorizar por problemas constructivos.
              <div class="row">
                <div class="col-6">
                  <q-select dense v-model="form_data.plan_inversiones" :options="options2"
                            label="*" options-dense emit-value map-options
                            :error-message="mensajesError('plan_inversiones')"
                            :error="$v.form_data.plan_inversiones.$error"
                  />
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn
                  :disable="!(form_data.plan_inversiones)"
                  flat @click="step = 9" color="primary" label="Anterior" class="q-ml-sm"/>
              </q-stepper-navigation>
            </q-step>
          </q-stepper>

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
  </div>
</template>

<script>
import {mapState, mapGetters, mapActions} from 'vuex';
import {showDialog} from '../../../../resources/js/utils/dialogo';
import {infoMessage, warningMessage} from '../../../../resources/js/utils/notificaciones';
import * as message from '../../../../resources/js/utils/resources';
import {required, alpha, numeric} from 'vuelidate/lib/validators';
import Geolocalizacion from "../../components/Geolocalizacion";

export default {
  name: 'compras',
  components: {Geolocalizacion},
  data() {
    return {
      visibleColumns: ['nombre', 'codigo', 'categoria_id', 'distribucion_id',
        'tipo_almacen_id', 'actividad_id', 'temperatura_id', 'action'],
      modalDialog: false,
      filterInput: false,
      step: 1,
      mapa: false,
      permiso: true,
      val: false,
      selected: [],
      aux: [],
      add: true,
      scopes: sessionStorage.getItem('scopes'),
      filter: '',
      accion: '',
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      options: ['Bueno', 'Regular', 'Malo'],
      options1: ['Bueno', 'Regular', 'Malo', 'No existe'],
      options2: ['Si', 'No'],
      permissions: [],
      items_categoria: [],
      items_distribucion: [],
      items_tipo_almacen: [],
      items_actividad: [],
      items_temperatura: [],
      form_data: {
        nombre: '',
        codigo: '',
        direccion: '',
        latitud: '',
        longitud: '',
        largo: '',
        ancho: '',
        puntal_libre: '',
        altura_prom_estiba: '',
        area_util: '',
        volumen_util: '',
        cant_montacargas: '',
        cant_transpaletas: '',
        cant_esteras: '',
        cant_basculas: '',
        cant_estantes: '',
        cant_paletas: '',
        cant_cajas_paletas: '',
        estado_constructivo: '',
        estado_estructura: '',
        estado_paredes: '',
        estado_piso: '',
        estado_puertas: '',
        estado_ventanas: '',
        estado_techo: '',
        ventilacion_natural: '',
        ventilacion_artificial: '',
        iluminacion_natural: '',
        iluminacion_artificial: '',
        prot_contra_intrusos: '',
        prot_contra_incendios: '',
        prot_observaciones: '',
        plan_inversiones: '',
        categoria_id: '',
        distribucion_id: '',
        tipo_almacen_id: '',
        actividad_id: '',
        temperatura_id: ''
      },
      form_error: false,
      required_error: false,
      columns: [
        {
          name: 'nombre',
          label: 'Nombre',
          align: 'left',
          field: 'nombre',
          search: true
        },
        {
          name: 'codigo',
          label: 'Código',
          align: 'left',
          field: 'codigo',
          search: true
        },
        {
          name: 'categoria_id',
          align: 'left',
          label: 'Categoría',
          field: row => row.categorias !== undefined ? row.categorias.nombre : ''
        },
        {
          name: 'distribucion_id',
          align: 'left',
          label: 'Distribución',
          field: row => row.distribuciones !== undefined ? row.distribuciones.nombre : ''
        },
        {
          name: 'tipo_almacen_id',
          align: 'left',
          label: 'Tipo de almacen',
          field: row => row.tipo_almacenes !== undefined ? row.tipo_almacenes.nombre : '',
        },
        {
          name: 'actividad_id',
          align: 'left',
          label: 'Actividad',
          field: row => row.actividades !== undefined ? row.actividades.nombre : ''
        },
        {
          name: 'temperatura_id',
          align: 'left',
          label: 'Temperatura',
          field: row => row.temperaturas !== undefined ? row.temperaturas.nombre : ''
        },
        {
          name: 'action',
          align: 'center',
          label: 'acciones',
          field: 'action',
        }
      ],
    };
  },
  validations: {
    form_data: {
      nombre: {
        required, alpha
      },
      direccion: {
        required
      },
      latitud: {
        required
      },
      longitud: {
        required
      },
      largo: {
        required
      },
      ancho: {
        required
      },
      puntal_libre: {
        required
      },
      altura_prom_estiba: {
        required
      },
      cant_montacargas: {
        required
      },
      cant_transpaletas: {
        required
      },
      cant_esteras: {
        required
      },
      cant_basculas: {
        required
      },
      cant_estantes: {
        required
      },
      cant_paletas: {
        required
      },
      cant_cajas_paletas: {
        required
      },
      estado_constructivo: {
        required
      },
      estado_estructura: {
        required
      },
      estado_paredes: {
        required
      },
      estado_piso: {
        required
      },
      estado_puertas: {
        required
      },
      estado_ventanas: {
        required
      },
      estado_techo: {
        required
      },
      ventilacion_natural: {
        required
      },
      ventilacion_artificial: {
        required
      },
      iluminacion_natural: {
        required
      },
      iluminacion_artificial: {
        required
      },
      prot_contra_intrusos: {
        required
      },
      prot_contra_incendios: {
        required
      },
      plan_inversiones: {
        required
      },
      categoria_id: {
        required
      },
      distribucion_id: {
        required
      },
      tipo_almacen_id: {
        required
      },
      actividad_id: {
        required
      },
      temperatura_id: {
        required
      }
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      {label: 'Dirección de Logística'},
      {label: 'Almacenes'},
      {label: 'Gestión de almacenes'},
    ]);
    this.$root.$on('geoCoordenadas', data => {
      this.form_data.latitud = data[0].position.lat
      this.form_data.longitud = data[0].position.lng
    })
    this.getCategoria_almacenes().then(r => {
      this.items_categoria = r
    })
    this.getDistribuciones().then(r => {
      this.items_distribucion = r
    })
    this.getTipo_almacenes().then(r => {
      this.items_tipo_almacen = r
    })
    this.getActividad_almacenes().then(r => {
      this.items_actividad = r
    })
    this.getTemperaturas().then(r => {
      this.items_temperatura = r
    })
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
    ...mapState('almacen', ['almacen']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('categoria_almacen', ['getCategoria_almacenes']),
    ...mapActions('distribucion', ['getDistribuciones']),
    ...mapActions('tipo_almacen', ['getTipo_almacenes']),
    ...mapActions('actividad_almacen', ['getActividad_almacenes']),
    ...mapActions('temperatura', ['getTemperaturas']),
    ...mapActions('almacen', [
      'getAlmacen',
      'saveAlmacen',
      'editAlmacen',
      'deleteAlmacen',
    ]),
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
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'nombre') {
        if (!this.$v.form_data.nombre.required) {
          this.required_error = true
          return ''
        }
        if (!this.$v.form_data.nombre.alpha) {
          this.required_error = true;
          return 'Solo se aceptan caracteres alfabéticos';
        }
      }
      if (campo === 'direccion') {
        if (!this.$v.form_data.direccion.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'latitud') {
        if (!this.$v.form_data.latitud.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'longitud') {
        if (!this.$v.form_data.longitud.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'categoria_id') {
        if (!this.$v.form_data.categoria_id.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'distribucion_id') {
        if (!this.$v.form_data.distribucion_id.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'tipo_almacen_id') {
        if (!this.$v.form_data.tipo_almacen_id.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'actividad_id') {
        if (!this.$v.form_data.actividad_id.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'temperatura_id') {
        if (!this.$v.form_data.temperatura_id.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'largo') {
        if (!this.$v.form_data.largo.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'ancho') {
        if (!this.$v.form_data.ancho.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'puntal_libre') {
        if (!this.$v.form_data.puntal_libre.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'altura_prom_estiba') {
        if (!this.$v.form_data.altura_prom_estiba.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'area_util') {
        if (!this.$v.form_data.area_util.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'volumen_util') {
        if (!this.$v.form_data.volumen_util.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'cant_montacargas') {
        if (!this.$v.form_data.cant_montacargas.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'cant_transpaletas') {
        if (!this.$v.form_data.cant_transpaletas.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'cant_esteras') {
        if (!this.$v.form_data.cant_esteras.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'cant_basculas') {
        if (!this.$v.form_data.cant_basculas.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'cant_estantes') {
        if (!this.$v.form_data.cant_estantes.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'cant_paletas') {
        if (!this.$v.form_data.cant_paletas.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'cant_cajas_paletas') {
        if (!this.$v.form_data.cant_cajas_paletas.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'estado_constructivo') {
        if (!this.$v.form_data.estado_constructivo.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'estado_estructura') {
        if (!this.$v.form_data.estado_estructura.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'estado_paredes') {
        if (!this.$v.form_data.estado_paredes.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'estado_piso') {
        if (!this.$v.form_data.estado_piso.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'estado_puertas') {
        if (!this.$v.form_data.estado_puertas.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'estado_ventanas') {
        if (!this.$v.form_data.estado_ventanas.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'estado_techo') {
        if (!this.$v.form_data.estado_techo.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'ventilacion_natural') {
        if (!this.$v.form_data.ventilacion_natural.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'ventilacion_artificial') {
        if (!this.$v.form_data.ventilacion_artificial.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'iluminacion_natural') {
        if (!this.$v.form_data.iluminacion_natural.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'iluminacion_artificial') {
        if (!this.$v.form_data.iluminacion_artificial.required) {
          this.required_error = true
          return ''
        }
      }if (campo === 'prot_contra_intrusos') {
        if (!this.$v.form_data.prot_contra_intrusos.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'prot_contra_incendios') {
        if (!this.$v.form_data.prot_contra_incendios.required) {
          this.required_error = true
          return ''
        }
      }
      if (campo === 'plan_inversiones') {
        if (!this.$v.form_data.plan_inversiones.required) {
          this.required_error = true
          return ''
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
      this.form_data = Object.assign(this.form_data, data);
    },
    reset_form() {
      this.form_data.nombre = ''
      this.form_data.codigo = ''
      this.form_data.direccion = ''
      this.form_data.latitud = ''
      this.form_data.longitud = ''
      this.form_data.largo = ''
      this.form_data.ancho = ''
      this.form_data.puntal_libre = ''
      this.form_data.altura_prom_estiba = ''
      this.form_data.area_util = ''
      this.form_data.volumen_util = ''
      this.form_data.cant_montacargas = ''
      this.form_data.cant_transpaletas = ''
      this.form_data.cant_esteras = ''
      this.form_data.cant_basculas = ''
      this.form_data.cant_estantes = ''
      this.form_data.cant_paletas = ''
      this.form_data.cant_cajas_paletas = ''
      this.form_data.estado_constructivo = ''
      this.form_data.estado_estructura = ''
      this.form_data.estado_paredes = ''
      this.form_data.estado_piso = ''
      this.form_data.estado_puertas = ''
      this.form_data.estado_ventanas = ''
      this.form_data.estado_techo = ''
      this.form_data.ventilacion_natural = ''
      this.form_data.ventilacion_artificial = ''
      this.form_data.iluminacion_natural = ''
      this.form_data.iluminacion_artificial = ''
      this.form_data.prot_contra_intrusos = ''
      this.form_data.prot_contra_incendios = ''
      this.form_data.prot_observaciones = ''
      this.form_data.plan_inversiones = ''
      this.form_data.categoria_id = ''
      this.form_data.distribucion_id = ''
      this.form_data.tipo_almacen_id = ''
      this.form_data.actividad_id = ''
      this.form_data.temperatura_id = ''
      this.form_error = false;
      this.step = 1
      this.val = false;
      this.required_error = false;
      this.permiso = true;
      this.add = true;
      this.$v.form_data.$reset();
    },
    async loadData(props) {
      const {page, rowsPerPage, sortBy, descending} = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getAlmacen(data).then((r) => {
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
        await this.saveAlmacen(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({ pagination: this.pagination, filter: this.filter });
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
        await this.editAlmacen(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({pagination: this.pagination, filter: this.filter});
        this.setLoading(false);
      }
    },
    deleteAlmacenes(selected) {
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteAlmacen(this.form_data.id);
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

<style scoped></style>
