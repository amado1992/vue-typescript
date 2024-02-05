<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          :data="medioTransporte"
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
              Gestionar medios de transporte
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
              :disable="medioTransporte.length != 0 ? false : true"
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
                  @click.prevent="deleteMediosTransporte(props.row)"
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
                          Mostrar detalles
                        </q-item-section>
                      </q-item>

                      <q-separator />

                      <q-item clickable>
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="primary" name="folder" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px;"
                        >
                          Situación actual
                        </q-item-section>
                        <q-item-section side>
                          <q-icon :size="'xs'" name="keyboard_arrow_right" />
                        </q-item-section>

                        <q-menu
                          auto-close
                          transition-show="jump-down"
                          transition-hide="jump-up"
                        >
                          <q-list style="min-width: 100px;">
                            <q-item
                              dense
                              clickable
                              v-ripple
                              @click.prevent="
                                openModal(true, 'historial', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon
                                  :size="'xs'"
                                  color="blue"
                                  name="mdi-eye"
                                />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px;"
                              >
                                Mostrar historial
                              </q-item-section>
                            </q-item>

                            <q-separator />

                            <q-item
                              dense
                              clickable
                              v-ripple
                              @click.prevent="
                                openModal(true, 'situacionAct', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon :size="'xs'" color="blue" name="edit" />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px;"
                              >
                                Modificar
                              </q-item-section>
                            </q-item>
                          </q-list>
                        </q-menu>
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
                <!--End ver mas-->
              </div>
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
          ref="gestMedioTransporte"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == 'adicionar'
                      ? 'Adicionar medio de transporte'
                      : 'Actualizar medio de transporte'
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
                      <q-select
                        options-dense
                        v-model="form_data.tipo_flota"
                        :options="tipoFlota"
                        label="Tipo de Flota *"
                        option-label="tipo_flota"
                        option-value="tipo_flota"
                        emit-value
                        map-options
                        :error-message="mensajesError('tipo_flota')"
                        :error="$v.form_data.tipo_flota.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-select
                        :loading="this.getterLoading()"
                        options-dense
                        v-model="form_data.tipo_medio_transporte"
                        :options="optionsTipoVehiculo"
                        label="Tipo de medio de transporte *"
                        option-label="tipo"
                        option-value="tipo"
                        emit-value
                        map-options
                        :error-message="mensajesError('tipo_medio_transporte')"
                        :error="$v.form_data.tipo_medio_transporte.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4">
                      <q-select
                        options-dense
                        v-model="form_data.estado_tecnico"
                        :options="estadoTecnico"
                        label="Estado técnico *"
                        option-label="estado"
                        option-value="estado"
                        emit-value
                        map-options
                        :error-message="mensajesError('estado_tecnico')"
                        :error="$v.form_data.estado_tecnico.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>
                  <div class="row q-px-sm">
                    <div class="col-4">
                      <q-input
                        v-model="form_data.marca"
                        label="Marca *"
                        name="marca"
                        :error-message="mensajesError('marca')"
                        :error="$v.form_data.marca.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        v-model="form_data.modelo"
                        label="Modelo *"
                        name="modelo"
                        :error-message="mensajesError('modelo')"
                        :error="$v.form_data.modelo.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        v-model="form_data.color"
                        label="Color *"
                        name="color"
                        :error-message="mensajesError('color')"
                        :error="$v.form_data.color.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>
                  <div class="row q-px-sm">
                    <div class="col-2">
                      <q-checkbox
                        left-label
                        v-model="form_data.electrico"
                        name="electrico"
                        label="Eléctrico"
                      />
                    </div>
                    <div
                      :class="
                        form_data.situacion_actual !== 'Paralizado'
                          ? 'col-4 q-pl-sm'
                          : 'col-6 q-pl-sm'
                      "
                    >
                      <q-select
                        options-dense
                        :disable="form_data.electrico"
                        v-model="form_data.tipo_combustible"
                        :options="tipoCombustible"
                        label="Tipo de combustible *"
                        option-label="combustible"
                        option-value="combustible"
                        emit-value
                        map-options
                        :error-message="mensajesError('tipo_combustible')"
                        :error="$v.form_data.tipo_combustible.$error"
                        debounce="1000"
                      />
                    </div>
                    <div
                      :class="
                        form_data.situacion_actual !== 'Paralizado'
                          ? 'col-3 q-px-sm'
                          : 'col-4 q-pl-sm'
                      "
                    >
                      <q-input
                        v-model="form_data.num_motor"
                        label="No. Motor *"
                        name="num_motor"
                        :error-message="mensajesError('num_motor')"
                        :error="$v.form_data.num_motor.$error"
                        debounce="1000"
                      />
                    </div>
                    <div
                      class="col-3"
                      v-if="form_data.situacion_actual !== 'Paralizado'"
                    >
                      <q-select
                        :disable="accion === 'actualizar'"
                        options-dense
                        v-model="form_data.situacion_actual"
                        :options="
                          form_data.tipo_flota ===
                          'Embarcaciones o medios náuticos'
                            ? situacionActualEmbarcaciones
                            : situacionActual
                        "
                        label="Situación actual *"
                        option-label="situacion_actual"
                        option-value="situacion_actual"
                        emit-value
                        map-options
                        :error-message="mensajesError('situacion_actual')"
                        :error="$v.form_data.situacion_actual.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>
                  <div
                    class="row q-px-sm"
                    v-if="form_data.situacion_actual === 'Paralizado'"
                  >
                    <div class="col-6">
                      <q-select
                        :disable="accion === 'actualizar'"
                        options-dense
                        v-model="form_data.situacion_actual"
                        :options="
                          form_data.tipo_flota ===
                          'Embarcaciones o medios náuticos'
                            ? situacionActualEmbarcaciones
                            : situacionActual
                        "
                        label="Situación actual *"
                        option-label="situacion_actual"
                        option-value="situacion_actual"
                        emit-value
                        map-options
                        :error-message="mensajesError('situacion_actual')"
                        :error="$v.form_data.situacion_actual.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-6 q-pl-sm">
                      <q-select
                        options-dense
                        v-model="form_data.motivo_paralizado"
                        :options="motivoParalizado"
                        label="Motivo *"
                        option-label="motivo"
                        option-value="motivo"
                        emit-value
                        map-options
                        :error-message="mensajesError('motivo_paralizado')"
                        :error="$v.form_data.motivo_paralizado.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>

                  <div class="row">
                    <span class="text-subtitle1 text-weight-medium">
                      Asignado a:
                    </span>
                  </div>

                  <div class="row q-px-sm">
                    <div class="col-4">
                      <q-input
                        v-model="form_data.asignado_cargo"
                        label="Cargo *"
                        name="cargo"
                        :error-message="mensajesError('asignado_cargo')"
                        :error="$v.form_data.asignado_cargo.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-8 q-pl-sm">
                      <q-input
                        v-model="form_data.asignado_nombre_apellidos"
                        label="Nombre y Apellidos"
                        name="nombre_apellido"
                        :error-message="
                          mensajesError('asignado_nombre_apellidos')
                        "
                        :error="$v.form_data.asignado_nombre_apellidos.$error"
                        debounce="1000"
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
                      <q-select
                        options-dense
                        v-model="form_data.clase"
                        :options="claseVehiculo"
                        label="Clase *"
                        option-label="clase"
                        option-value="clase"
                        emit-value
                        map-options
                        :error-message="mensajesError('clase')"
                        :error="$v.form_data.clase.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        v-model="form_data.matricula"
                        label="Matrícula *"
                        name="matricula"
                        :error-message="mensajesError('matricula')"
                        :error="$v.form_data.matricula.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        v-model="form_data.num_carroceria"
                        label="No. Carrocería *"
                        name="num_carroceria"
                        :error-message="mensajesError('num_carroceria')"
                        :error="$v.form_data.num_carroceria.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>
                  <div class="row no-wrap">
                    <div class="col-4">
                      <q-input
                        type="number"
                        v-model="form_data.anno_fabricacion"
                        label="Año fabricación *"
                        name="fabricacion"
                        mask="####"
                        :error-message="mensajesError('anno_fabricacion')"
                        :error="$v.form_data.anno_fabricacion.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        type="number"
                        v-model="form_data.capacidad_carga"
                        label="Capacidad de carga *"
                        name="cap_carga"
                        :error-message="mensajesError('capacidad_carga')"
                        :error="$v.form_data.capacidad_carga.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        type="number"
                        v-model="form_data.neumaticos"
                        label="Neumáticos *"
                        name="neumaticos"
                        :error-message="mensajesError('neumaticos')"
                        :error="$v.form_data.neumaticos.$error"
                        debounce="1000"
                      />
                    </div>
                  </div>
                  <div class="row no-wrap">
                    <div class="col-4">
                      <q-input
                        type="number"
                        v-model="form_data.indice_consumo"
                        label="Consumo(Km/L) *"
                        name="ind_consumo"
                        :disable="form_data.electrico"
                        :error-message="mensajesError('indice_consumo')"
                        :error="$v.form_data.indice_consumo.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        type="number"
                        v-model="form_data.combustible_asignado"
                        label="Combustible asignado *"
                        name="combustible_asig"
                        :error-message="mensajesError('combustible_asignado')"
                        :error="$v.form_data.combustible_asignado.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        v-model="form_data.fecha_ficav"
                        label="Fecha FICAV *"
                        name="ficav"
                        mask="####-##-##"
                        :error-message="mensajesError('fecha_ficav')"
                        :error="$v.form_data.fecha_ficav.$error"
                        debounce="1000"
                      >
                        <template>
                          <q-popup-proxy
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-date
                              v-model="form_data.fecha_ficav"
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
                  </div>
                  <div class="row no-wrap">
                    <div class="col-4">
                      <q-input
                        :disable="
                          form_data.tipo_flota !==
                          'Embarcaciones o medios náuticos'
                        "
                        type="number"
                        v-model="form_data.folio"
                        label="Folio *"
                        name="folio"
                        :error-message="mensajesError('folio')"
                        :error="$v.form_data.folio.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-select
                        :disable="
                          form_data.tipo_flota !==
                          'Embarcaciones o medios náuticos'
                        "
                        options-dense
                        v-model="form_data.ubicacion_motor"
                        :options="ubicacionMotor"
                        label="Ubicación del motor *"
                        option-label="ubicacion"
                        option-value="ubicacion"
                        emit-value
                        map-options
                        :error-message="mensajesError('ubicacion_motor')"
                        :error="$v.form_data.ubicacion_motor.$error"
                        debounce="1000"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        :disable="
                          form_data.tipo_flota !==
                          'Embarcaciones o medios náuticos'
                        "
                        type="number"
                        v-model="form_data.moto_horas"
                        label="Moto horas *"
                        name="moto_horas"
                        :error-message="mensajesError('moto_horas')"
                        :error="$v.form_data.moto_horas.$error"
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
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section class="q-pa-md q-gutter-lg no-wrap">
          <div class="row no-wrap">
            <div class="col-3">
              <span class="text-weight-bold">Tipo de flota:</span>
              <span class="">{{ detallesData['tipo_flota'] }}</span>
            </div>
            <div class="col-3">
              <span class="text-weight-bold">Tipo de medio de transporte:</span>
              <span class="">{{ detallesData['tipo_medio_transporte'] }}</span>
            </div>
            <div class="col-3">
              <span class="text-weight-bold">Estado técnico:</span>
              <span class="">{{ detallesData['estado_tecnico'] }}</span>
            </div>
            <div class="col-3">
              <span class="text-weight-bold">Situación actual:</span>
              <span class="">{{ detallesData['situacion_actual'] }}</span>
            </div>
          </div>

          <div class="row no-wrap">
            <div class="col-3">
              <span class="text-weight-bold">Marca:</span>
              <span class="">{{ detallesData['marca'] }}</span>
            </div>
            <div class="col-3">
              <span class="text-weight-bold">Modelo:</span>
              <span class="">{{ detallesData['modelo'] }}</span>
            </div>
            <div class="col-3">
              <span class="text-weight-bold">Color:</span>
              <span class="">{{ detallesData['color'] }}</span>
            </div>
          </div>

          <div class="row no-wrap">
            <div class="col-4">
              <span class="text-weight-bold">Eléctrico:</span>
              <span class="">{{
                detallesData['electrico'] === true ? 'Si' : 'No'
              }}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">Tipo combustible:</span>
              <span class="">{{ detallesData['tipo_combustible'] }}</span>
            </div>
            <div class="col-4">
              <span class="text-weight-bold">No. Motor:</span>
              <span class="">{{ detallesData['num_motor'] }}</span>
            </div>
          </div>
          <q-expansion-item
            dense
            group="detallesmt"
            label="Otros detalles del vehículo"
            header-class="text-subtitle1 text-weight-medium text-uppercase"
          >
            <q-card>
              <q-card-section>
                <div class="row">
                  <span class="text-weight-bold">Asignado a:</span>
                </div>
                <div class="row">
                  <div class="col-6">
                    <span class="text-weight-bold">Cargo:</span>
                    <span class="">{{ detallesData['asignado_cargo'] }}</span>
                  </div>
                  <div class="col-6">
                    <span class="text-weight-bold">Nombre y Apellidos:</span>
                    <span class="">{{
                      detallesData['asignado_nombre_apellidos']
                    }}</span>
                  </div>
                </div>
                <div class="row q-pt-md no-wrap">
                  <div class="col-4">
                    <span class="text-weight-bold">Matrícula:</span>
                    <span class="">{{ detallesData['matricula'] }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">No. Carrocería:</span>
                    <span class="">{{ detallesData['num_carroceria'] }}</span>
                  </div>
                  <div class="col-3">
                    <span class="text-weight-bold">Año de fabricación:</span>
                    <span class="">{{ detallesData['anno_fabricacion'] }}</span>
                  </div>
                  <div class="col-1">
                    <span class="text-weight-bold">Edad:</span>
                    <span class="">{{ detallesData['edad'] }}</span>
                  </div>
                </div>
                <div class="row q-pt-md no-wrap">
                  <div class="col-4">
                    <span class="text-weight-bold">Clase:</span>
                    <span class="">{{ detallesData['clase'] }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Combustible asignado:</span>
                    <span class="">{{
                      detallesData['combustible_asignado']
                    }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Fecha FICAV:</span>
                    <span class="">{{
                      moment(detallesData['fecha_ficav']).format('YYYY-MM-DD')
                    }}</span>
                  </div>
                </div>
                <div class="row q-pt-md no-wrap">
                  <div class="col-3">
                    <span class="text-weight-bold">Capacidad de carga:</span>
                    <span class=""
                      >{{ detallesData['capacidad_carga'] }}
                      {{ detallesData['capacidad_carga_um'] }}</span
                    >
                  </div>
                  <div class="col-3">
                    <span class="text-weight-bold"
                      >Cantidad de neumáticos:</span
                    >
                    <span class="">{{ detallesData['neumaticos'] }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Índice de consumo:</span>
                    <span class="">{{ detallesData['indice_consumo'] }}</span>
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </q-expansion-item>

          <q-expansion-item
            dense
            group="detallesmt"
            label="Otros detalles de la embarcación o medio náutico"
            header-class="text-subtitle1 text-weight-medium text-uppercase"
          >
            <q-card>
              <q-card-section>
                <div class="row no-wrap">
                  <div class="col-4">
                    <span class="text-weight-bold">Folio:</span>
                    <span class="">{{ detallesData['folio'] }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Ubicación del motor:</span>
                    <span class="">{{ detallesData['ubicacion_motor'] }}</span>
                  </div>
                  <div class="col-4">
                    <span class="text-weight-bold">Moto Horas:</span>
                    <span class="">{{ detallesData['moto_horas'] }}</span>
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
    <!-- Situación actual-->
    <q-dialog
      v-model="modalDialogST"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vm;">
        <q-form
          @reset="reset_form"
          @submit="updateSituacionActual()"
          ref="gestSituacionActual"
        >
          <q-card-section class="no-padding">
            <q-toolbar class="bg-primary text-white">
              <q-toolbar-title>Modificar situación actual</q-toolbar-title>
              <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
                <q-tooltip>Cerrar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </q-card-section>
          <q-card-section>
            <div class="col-6">
              <q-select
                :disable="accion === 'actualizar'"
                options-dense
                v-model="form_data.situacion_actual"
                :options="
                  form_data.tipo_flota === 'Embarcaciones o medios náuticos'
                    ? situacionActualEmbarcaciones
                    : situacionActual
                "
                label="Situación actual"
                option-label="situacion_actual"
                option-value="situacion_actual"
                emit-value
                map-options
              />
            </div>
            <div class="col-6">
              <q-select
                :disable="form_data.situacion_actual !== 'Paralizado'"
                options-dense
                v-model="form_data.motivo_paralizado"
                :options="motivoParalizado"
                label="Motivo *"
                option-label="motivo"
                option-value="motivo"
                emit-value
                map-options
                :error-message="mensajesError('motivo_paralizado')"
                :error="$v.form_data.motivo_paralizado.$error"
                debounce="1000"
              />
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
              @click="closeModal()"
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
    <!-- End situación actual-->
    <!-- Historial-->
    <q-dialog
      v-model="modalDialogHistorico"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 800px; max-width: 90vm;">
        <q-card-section class="no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>Historial del vehículo</q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <q-tabs
            v-model="tab"
            dense
            class="text-grey"
            active-color="primary"
            indicator-color="primary"
            align="justify"
            narrow-indicator
          >
            <q-tab name="estado" label="Estados" />
            <q-tab name="accidente" label="Accidentes" />
          </q-tabs>

          <q-separator />

          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="estado">
              <q-table
                flat
                dense
                :data="historico"
                :columns="historicoColumns"
                row-key="id"
                :loading="this.getterLoading()"
                loading-label="Cargando elementos"
                :rows-per-page-options="[5, 10, 25, 50]"
                binary-state-sort
                no-data-label="No se encontraron elementos a mostrar"
                :hide-pagination="false"
              />
            </q-tab-panel>

            <q-tab-panel name="accidente">
              <q-table
                flat
                dense
                :data="historicoAccidente"
                :columns="accidenteColumns"
                row-key="id"
                :loading="this.getterLoading()"
                loading-label="Cargando elementos"
                :rows-per-page-options="[5, 10, 25, 50]"
                binary-state-sort
                no-data-label="No se encontraron elementos a mostrar"
                :hide-pagination="false"
              />
            </q-tab-panel>
          </q-tab-panels>
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
    <!-- End Historial-->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../utils/dialogo';
import { infoMessage } from '../../utils/notificaciones';
import * as message from '../../utils/resources';
import {
  required,
  alphaNum,
  numeric,
  alpha,
  maxValue,
  minValue,
  requiredIf,
  integer,
} from 'vuelidate/lib/validators';
import moment from 'moment';
import Vue from 'vue';
import _ from 'lodash';

moment.locale('es');
const greaterThanZero = (value) => value > 0;
export default {
  name: 'GestMedioTransporte',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      tab: 'estado',
      step: 1,
      detallesData: [],
      historico: [],
      historicoAccidente: [],
      form_data: {
        tipo_flota: '',
        tipo_medio_transporte: '',
        marca: '',
        modelo: '',
        color: '',
        estado_tecnico: '',
        electrico: false,
        tipo_combustible: '',
        num_motor: '',
        situacion_actual: '',
        motivo_paralizado: '',
        clase: '',
        matricula: '',
        num_carroceria: '',
        anno_fabricacion: '',
        capacidad_carga: '',
        neumaticos: '',
        indice_consumo: '',
        combustible_asignado: '',
        fecha_ficav: '',
        asignado_cargo: '',
        asignado_nombre_apellidos: '',
        folio: '',
        ubicacion_motor: '',
        moto_horas: '',
      },
      modalDialog: false,
      modalDialogDetail: false,
      modalDialogST: false,
      modalDialogHistorico: false,
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
        'tipo_medio_transporte',
        'marca',
        'modelo',
        'clase_id',
        'estado_tecnico',
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
          name: 'tipo_medio_transporte',
          align: 'left',
          label: 'tipo',
          field: 'tipo_medio_transporte',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'clase',
          align: 'left',
          label: 'clase',
          field: 'clase',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'marca',
          align: 'left',
          label: 'marca',
          field: 'marca',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'modelo',
          align: 'left',
          label: 'modelo',
          field: 'modelo',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'estado_tecnico',
          align: 'left',
          label: 'estado tec.',
          field: 'estado_tecnico',
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
      historicoColumns: [
        {
          name: 'estado',
          align: 'left',
          label: 'estado',
          field: 'estado',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'estado_fechaInicial',
          align: 'left',
          label: 'fecha inicio',
          field: 'estado_fechaInicial',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'estado_fechaFinal',
          align: 'left',
          label: 'fecha fin',
          field: 'estado_fechaFinal',
          headerClasses: 'text-uppercase',
        },
      ],
      accidenteColumns: [
        {
          name: 'cant_accidentes',
          align: 'center',
          label: 'cant. accidentes',
          field: 'cant_accidentes',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'fecha',
          align: 'left',
          label: 'fecha',
          field: 'fecha',
          headerClasses: 'text-uppercase',
        },
      ],
      tipoFlota: [],
      estadoTecnico: [],
      tipoCombustible: [],
      claseVehiculo: [],
      ubicacionMotor: [],
      situacionActual: [],
      situacionActualEmbarcaciones: [],
      medioNauticoEmbarcaciones: [],
      optionsTipoVehiculo: [],
      motivoParalizado: [],
    };
  },
  validations: {
    form_data: {
      tipo_flota: {
        required,
      },
      tipo_medio_transporte: {
        required,
      },
      marca: {
        required,
        alpha,
      },
      modelo: {
        required,
        alphaNum,
      },
      color: {
        required,
        alpha,
      },
      estado_tecnico: {
        required,
      },
      tipo_combustible: {
        required: requiredIf(function (form_data) {
          return form_data.electrico === false;
        }),
      },
      num_motor: {
        required,
      },
      situacion_actual: {
        required,
      },
      motivo_paralizado: {
        required: requiredIf(function (form_data) {
          return form_data.situacion_actual === 'Paralizado';
        }),
      },
      clase: {
        required,
      },
      matricula: {
        required,
        alphaNum,
      },
      num_carroceria: {
        required,
        alphaNum,
      },
      anno_fabricacion: {
        required,
        numeric,
      },
      capacidad_carga: {
        required,
        numeric,
        maxValue: greaterThanZero,
      },
      neumaticos: {
        required,
        numeric,
      },
      indice_consumo: {
        required: requiredIf(function (form_data) {
          return form_data.electrico === false;
        }),
        numeric,
      },
      combustible_asignado: {
        required,
        integer,
      },
      fecha_ficav: {
        required,
      },
      asignado_cargo: {
        required,
      },
      asignado_nombre_apellidos: {},
      folio: {
        required: requiredIf(function (form_data) {
          return (
            form_data.tipo_flota === 'Embarcaciones o medios náuticos' &&
            (form_data.tipo_medio_transporte === 'Bote' ||
              form_data.tipo_medio_transporte === 'Patana')
          );
        }),
        numeric,
      },
      ubicacion_motor: {
        required: requiredIf(function (form_data) {
          return (
            form_data.tipo_flota === 'Embarcaciones o medios náuticos' &&
            (form_data.tipo_medio_transporte === 'Bote' ||
              form_data.tipo_medio_transporte === 'Patana')
          );
        }),
      },
      moto_horas: {
        required: requiredIf(function (form_data) {
          return (
            form_data.tipo_flota === 'Embarcaciones o medios náuticos' &&
            (form_data.tipo_medio_transporte === 'Bote' ||
              form_data.tipo_medio_transporte === 'Patana')
          );
        }),
        numeric,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Transporte' },
      { label: 'Flujo informativo' },
      { label: 'Gestionar medios de transporte' },
    ]);
    this.loadDataNomencladores();
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
    'form_data.tipo_flota': {
      handler: async function () {
        if (this.accion !== 'situacionAct') {
          switch (this.form_data.tipo_flota) {
            case 'Administrativo y de Servicio':
              this.setLoading(true);
              this.form_data.tipo_medio_transporte = '';
              this.optionsTipoVehiculo = [];
              this.optionsTipoVehiculo = await this.getTipo_vaservicio();
              this.situacionActual = this.removeItemFromArr(
                this.situacionActual,
                'Alta'
              );
              this.setLoading(false);
              break;

            case 'Especializado':
              this.setLoading(true);
              this.form_data.tipo_medio_transporte = '';
              this.optionsTipoVehiculo = [];
              this.optionsTipoVehiculo = await this.getTipo_vespecializado();
              this.situacionActual = this.removeItemFromArr(
                this.situacionActual,
                'Alta'
              );
              this.setLoading(false);
              break;

            case 'Turístico':
              this.setLoading(true);
              this.form_data.tipo_medio_transporte = '';
              this.optionsTipoVehiculo = [];
              this.optionsTipoVehiculo = await this.getTipo_vturistico();
              this.situacionActual = this.removeItemFromArr(
                this.situacionActual,
                'Alta'
              );
              this.setLoading(false);
              break;

            case 'Embarcaciones o medios náuticos':
              this.setLoading(true);
              this.form_data.tipo_medio_transporte = '';
              this.optionsTipoVehiculo = [];
              this.optionsTipoVehiculo = await this.getTipo_emnautico();
              this.situacionActualEmbarcaciones = this.removeItemFromArr(
                this.situacionActualEmbarcaciones,
                'Trabajando'
              );
              this.setLoading(false);
              break;

            default:
              break;
          }
        }
      },
    },
    'form_data.situacion_actual': {
      handler: async function () {
        if (this.form_data.situacion_actual === 'Paralizado') {
          this.setLoading(true);
          this.motivoParalizado = await this.getMpmtransporte();
          this.setLoading(false);
        }
      },
    },
  },
  computed: {
    ...mapState('medio_transporte', ['medioTransporte']),
  },
  methods: {
    moment,
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('historico_vehiculo', ['listHistoricos']),
    ...mapActions('tipo_flota', ['getTipo_flota']),
    ...mapActions('etmtransporte', ['getEtmtransporte']),
    ...mapActions('tcmtransporte', ['getTcmtransporte']),
    ...mapActions('cmtransporte', ['getCmtransporte']),
    ...mapActions('ubicacion_memnautico', ['getUbicacion_memnautico']),
    ...mapActions('samtransporte', ['getSamtransporte']),
    ...mapActions('tipo_emnautico', ['getTipo_emnautico']),
    ...mapActions('tipo_vturistico', ['getTipo_vturistico']),
    ...mapActions('tipo_vespecializado', ['getTipo_vespecializado']),
    ...mapActions('tipo_vaservicio', ['getTipo_vaservicio']),
    ...mapActions('mpmtransporte', ['getMpmtransporte']),
    ...mapActions('medio_transporte', [
      'listMedioTransporte',
      'saveMedioTransporte',
      'editMedioTransporte',
      'deleteMedioTransporte',
    ]),
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'tipo_flota') {
        if (!this.$v.form_data.tipo_flota.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'tipo_medio_transporte') {
        if (!this.$v.form_data.tipo_medio_transporte.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'marca') {
        if (!this.$v.form_data.marca.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.marca.alpha) {
          return message.vAlpha;
        }
      }
      if (campo === 'modelo') {
        if (!this.$v.form_data.modelo.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.modelo.alpha) {
          return message.vAlphanumeric;
        }
      }
      if (campo === 'color') {
        if (!this.$v.form_data.color.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.color.alpha) {
          return message.vAlpha;
        }
      }
      if (campo === 'estado_tecnico') {
        if (!this.$v.form_data.estado_tecnico.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'tipo_combustible') {
        if (!this.$v.form_data.tipo_combustible.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'num_motor') {
        if (!this.$v.form_data.num_motor.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'situacion_actual') {
        if (!this.$v.form_data.situacion_actual.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'motivo_paralizado') {
        if (!this.$v.form_data.motivo_paralizado.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'clase') {
        if (!this.$v.form_data.clase.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'matricula') {
        if (!this.$v.form_data.matricula.required) {
          this.required_error = true;
          return '';
        }

        if (!this.$v.form_data.matricula.maxValue) {
          return message.vMaxValue;
        }
      }
      if (campo === 'num_carroceria') {
        if (!this.$v.form_data.num_carroceria.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.num_carroceria.alphaNum) {
          return message.vAlphanumeric;
        }
      }
      if (campo === 'anno_fabricacion') {
        if (!this.$v.form_data.anno_fabricacion.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.anno_fabricacion.numeric) {
          return message.vNumeric;
        }
      }
      if (campo === 'capacidad_carga') {
        if (!this.$v.form_data.capacidad_carga.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.capacidad_carga.numeric) {
          return message.vNumeric;
        }
        if (!this.$v.form_data.capacidad_carga.maxValue) {
          return message.vMayor0;
        }
      }
      if (campo === 'neumaticos') {
        if (!this.$v.form_data.neumaticos.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.neumaticos.numeric) {
          return message.vNumeric;
        }
      }
      if (campo === 'indice_consumo') {
        if (!this.$v.form_data.indice_consumo.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'combustible_asignado') {
        if (!this.$v.form_data.indice_consumo.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.indice_consumo.integer) {
          return message.vInteger;
        }
      }
      if (campo === 'fecha_ficav') {
        if (!this.$v.form_data.fecha_ficav.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'asignado_cargo') {
        if (!this.$v.form_data.asignado_cargo.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.asignado_cargo.alpha) {
          return message.vAlpha;
        }
      }
      if (campo === 'asignado_nombre_apellidos') {
        if (!this.$v.form_data.asignado_nombre_apellidos.alpha) {
          return message.vAlphanumeric;
        }
        if (!this.$v.form_data.asignado_nombre_apellidos.alpha) {
          return message.vAlpha;
        }
      }
      if (campo === 'folio') {
        if (!this.$v.form_data.folio.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.folio.numeric) {
          return message.vNumeric;
        }
      }
      if (campo === 'ubicacion_motor') {
        if (!this.$v.form_data.ubicacion_motor.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'moto_horas') {
        if (!this.$v.form_data.moto_horas.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.moto_horas.numeric) {
          return message.vNumeric;
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
      this.form_data.tipo_medio_transporte = '';
      this.form_data.tipo_flota = '';
      this.form_data.marca = '';
      this.form_data.modelo = '';
      this.form_data.color = '';
      this.form_data.estado_tecnico = '';
      this.form_data.electrico = false;
      this.form_data.tipo_combustible = '';
      this.form_data.num_motor = '';
      this.form_data.situacion_actual = '';
      this.form_data.clase = '';
      this.form_data.matricula = '';
      this.form_data.num_carroceria = '';
      this.form_data.anno_fabricacion = '';
      this.form_data.capacidad_carga = '';
      this.form_data.neumaticos = '';
      this.form_data.indice_consumo = '';
      this.form_data.combustible_asignado = '';
      this.form_data.fecha_ficav = '';
      this.form_data.asignado_cargo = '';
      this.form_data.asignado_nombre_apellidos = '';
      this.form_data.folio = '';
      this.form_data.ubicacion_motor = '';
      this.form_data.moto_horas = '';
      this.historico = [];
      this.historicoAccidente = [];
      this.form_error = false;
      this.required_error = false;
      this.$v.form_data.$reset();
      this.step = 1;
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
        case 'detalles':
          if (rowData) {
            this.modalDialogDetail = data;
            this.detallesData = rowData;
          } else {
            infoMessage('Debe seleccionar un elemento para ver los detalles.');
          }
          break;
        case 'situacionAct':
          if (rowData) {
            this.setFormData(rowData);
            this.modalDialogST = data;
          } else {
            infoMessage('Debe seleccionar un elemento para modificarlo.');
          }
          break;
        case 'historial':
          if (rowData) {
            this.detallesData = rowData;
            this.loadDataHistorico();
            this.loadDataHistoricoAccidente(rowData);
            this.modalDialogHistorico = data;
          } else {
            infoMessage('Debe seleccionar un elemento para ver su historico');
          }
          break;

        default:
          break;
      }
    },
    async loadDataNomencladores() {
      this.tipoFlota = await this.getTipo_flota();
      this.estadoTecnico = await this.getEtmtransporte();
      this.tipoCombustible = await this.getTcmtransporte();
      this.claseVehiculo = await this.getCmtransporte();
      this.ubicacionMotor = await this.getUbicacion_memnautico();
      this.situacionActualEmbarcaciones = this.situacionActual = await this.getSamtransporte();
    },
    loadDataHistorico() {
      this.setLoading(true);
      return Vue.prototype.$axios
        .post('/api/historico_vehiculo', this.detallesData)
        .then((result) => {
          this.historico = result.data.data;
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        })
        .finally(this.setLoading(false));
    },
    loadDataHistoricoAccidente(row) {
      this.setLoading(true);
      const data = {
        vehiculo_id: row.id,
      };
      return Vue.prototype.$axios
        .post('/api/historico_accidente', data)
        .then((result) => {
          this.historicoAccidente = result.data.data;
        })
        .catch((err) => {
          errorMessage(message.eDataError, err);
        })
        .finally(this.setLoading(false));
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
      await this.listMedioTransporte(data).then((r) => {
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
        await this.saveMedioTransporte(this.form_data);
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
        await this.editMedioTransporte(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      }
      this.setLoading(false);
    },
    async updateSituacionActual() {
      this.setLoading(true);
      await this.editMedioTransporte(this.form_data);
      this.reset_form();
      this.closeModal();
      this.loadData({
        pagination: this.pagination,
        filter: this.filter,
      });
      this.setLoading(false);
    },
    deleteMediosTransporte(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteMedioTransporte(selected.id);
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
    removeItemFromArr(arr, item) {
      return _.filter(arr, function (n) {
        return n.situacion_actual !== item;
      });
    },
    demoDialog() {
      alert('Soy un demo ;)');
    },
  },
};
</script>

<style></style>
