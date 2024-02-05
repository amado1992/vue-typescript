<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          :data="eventoEHE"
          :visible-columns="visibleColumns"
          :columns="columns"
          row-key="id"
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[20, 50, 100]"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          :hide-pagination="false"
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar eventos higienico-epidemiologico
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
              :disable="eventoEHE.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_dir_higienico_epidemiologico')"
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
                  v-if="
                    scopes.includes('gestionar_dir_higienico_epidemiologico')
                  "
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteEventosEHE(props.row)"
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

                      <q-item
                        :disable="props.row.informe_conclusivo === false"
                        clickable
                        v-ripple
                        @click.prevent="exportarInformePDF(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-file-download"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px;"
                        >
                          Guardar informe
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
                          Plan de acción
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
                              :disable="props.row.plan_accion === false"
                              dense
                              clickable
                              v-ripple
                              @click.prevent="
                                openModal(true, 'plan_accion', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon
                                  :size="'xs'"
                                  color="blue"
                                  name="mdi-plus"
                                />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px;"
                              >
                                Añadir
                              </q-item-section>
                            </q-item>
                            <q-item
                              :disable="props.row.plan_accion === false"
                              dense
                              clickable
                              v-ripple
                              @click.prevent="
                                openModal(true, 'list_plan_accion', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon
                                  :size="'xs'"
                                  color="blue"
                                  name="mdi-format-list-bulleted-triangle"
                                />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px;"
                              >
                                Listar
                              </q-item-section>
                            </q-item>
                          </q-list>
                        </q-menu>
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
                          Resultado
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
                              :disable="props.row.resultado"
                              dense
                              clickable
                              v-ripple
                              @click.prevent="
                                openModal(true, 'doc_resumen', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon
                                  :size="'xs'"
                                  color="blue"
                                  name="mdi-plus"
                                />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px;"
                              >
                                Añadir
                              </q-item-section>
                            </q-item>
                            <q-item
                              :disable="props.row.resultado"
                              dense
                              clickable
                              v-ripple
                              @click.prevent="
                                openModal(true, 'list_doc_resumen', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon
                                  :size="'xs'"
                                  color="blue"
                                  name="mdi-format-list-bulleted-triangle"
                                />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px;"
                              >
                                Listar
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
          ref="gestEventoEHE"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == 'adicionar'
                      ? 'Adicionar evento'
                      : 'Actualizar evento'
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
                contracted
              >
                <q-step
                  :name="1"
                  title=""
                  icon="mdi-file-document-outline"
                  :done="step > 1"
                >
                  <div class="row">
                    <div class="col-4">
                      <q-input
                        v-model="form_data.fecha_deteccion"
                        label="Fecha detección *"
                        name="fecha_deteccion"
                        mask="####-##-##"
                      >
                        <template>
                          <q-popup-proxy
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-date
                              v-model="form_data.fecha_deteccion"
                              mask="YYYY-MM-DD"
                              minimal
                            >
                              <div class="row justify-end">
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
                      <q-select
                        v-model="form_data.deteccion"
                        :options="deteccion"
                        label="Detección *"
                        name="deteccion"
                        options-dense
                        option-label="deteccion"
                        option-value="deteccion"
                        emit-value
                        map-options
                      />
                    </div>
                    <div class="col-4">
                      <q-select
                        :disable="form_data.deteccion != 'Externa'"
                        v-model="form_data.detectado_por"
                        :options="detectadoPor"
                        label="Detectado por *"
                        name="detectado_por"
                        options-dense
                        option-label="detectado_por"
                        option-value="detectado_por"
                        emit-value
                        map-options
                      />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-4">
                      <q-select
                        v-model="form_data.clasificacion_evento"
                        multiple
                        :options="clasificacionEvento"
                        label="Clasificación del evento"
                        name="clasificacion_evento"
                        options-dense
                        option-label="clasificacion_evento"
                        option-value="clasificacion_evento"
                        emit-value
                        map-options
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-select
                        :disable="form_data.clasificacion_evento != 'Foco'"
                        v-model="form_data.tipo_foco"
                        :options="tipoFoco"
                        label="Tipo de foco *"
                        name="tipo_foco"
                        options-dense
                        option-label="tipo_foco"
                        option-value="tipo_foco"
                        emit-value
                        map-options
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Foco'"
                        type="number"
                        v-model="form_data.cant_focos"
                        label="No. de focos *"
                        name="cant_focos"
                      />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Foco'"
                        v-model="form_data.deposito_foco"
                        label="Depósito *"
                        name="deposito_foco"
                      />
                    </div>
                    <div class="col-6 q-pl-sm">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Foco'"
                        v-model="form_data.ubicacion_foco"
                        label="Ubicación *"
                        name="ubicacion_foco"
                      />
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-4">
                      <q-select
                        :disable="
                          form_data.clasificacion_evento != 'Muestra Positiva'
                        "
                        v-model="form_data.tipo_muestra"
                        :options="tipoMuestra"
                        label="Tipo de muestra *"
                        name="tipo_muestra"
                        options-dense
                        option-label="tipo_muestra"
                        option-value="tipo_muestra"
                        emit-value
                        map-options
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-select
                        :disable="
                          form_data.clasificacion_evento != 'Muestra Positiva'
                        "
                        v-model="form_data.patogeno_identificado_muestra"
                        :options="patogenoIdentificado"
                        label="Patógeno identificado *"
                        name="patogeno_identificado"
                        options-dense
                        option-label="patogeno_identificado"
                        option-value="patogeno_identificado"
                        emit-value
                        map-options
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        :disable="
                          form_data.clasificacion_evento != 'Muestra Positiva'
                        "
                        v-model="form_data.ubicacion_muestra"
                        label="Ubicación *"
                        name="ubicacion_muestra"
                      />
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-4">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Caso'"
                        v-model="form_data.tipo_caso"
                        label="Tipo de caso *"
                        name="tipo_caso"
                      />
                    </div>
                    <div class="col-4 q-pl-sm">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Caso'"
                        v-model="form_data.origen_caso"
                        label="Origen del caso *"
                        name="origen_caso"
                      />
                    </div>
                    <div class="col-4 q-pl-sm">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Caso'"
                        v-model="form_data.agente_causal_caso"
                        label="Agente causal *"
                        name="agente_causal_caso"
                      />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-4">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Caso'"
                        v-model="form_data.mecanismo_transmision_caso"
                        label="Mecanismo de transmisión *"
                        name="mecanismo_transmision_caso"
                      />
                    </div>
                    <div class="col-4 q-pl-sm">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Caso'"
                        v-model="form_data.vehiculo_caso"
                        label="Vehículo *"
                        name="vehiculo_caso"
                      />
                    </div>
                    <div class="col-4 q-pl-sm">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Caso'"
                        v-model="form_data.alimento_involucrado_caso"
                        label="Alimento involucrado *"
                        name="alimento_involucrado_caso"
                      />
                    </div>
                  </div>
                </q-step>
                <q-step
                  :name="2"
                  title=""
                  icon="mdi-file-document-outline"
                  :done="step > 1"
                >
                  <div class="row">
                    <div class="col-4">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Brote'"
                        v-model="form_data.tipo_brote"
                        label="Tipo Brote *"
                        name="tipo_brote"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Brote'"
                        v-model="form_data.origen_brote"
                        label="Origen del brote *"
                        name="origen_brote"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Brote'"
                        v-model="form_data.agente_causal_brote"
                        label="Agente causal *"
                        name="agente_causal_brote"
                      />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-4">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Brote'"
                        v-model="form_data.mecanismo_transmision_brote"
                        label="Mecanismo de transmisión *"
                        name="mecanismo_transmision_brote"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Brote'"
                        v-model="form_data.vehiculo_brote"
                        label="Vehículo *"
                        name="vehiculo_brote"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        :disable="form_data.clasificacion_evento != 'Brote'"
                        v-model="form_data.alimento_involucrado_brote"
                        label="Alimento involucrado *"
                        name="alimento_involucrado_brote"
                      />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-3">
                      <q-input
                        type="number"
                        v-model="form_data.expuestos"
                        label="Expuestos *"
                        name="expuestos"
                      />
                    </div>
                    <div class="col-3 q-px-sm">
                      <q-input
                        type="number"
                        v-model="form_data.afectados"
                        label="Afectados *"
                        name="afectados"
                      />
                    </div>
                    <div class="col-3">
                      <q-input
                        type="number"
                        v-model="form_data.ingresados"
                        label="Ingresados *"
                        name="ingresados"
                      />
                    </div>
                    <div class="col-3 q-pl-sm">
                      <q-input
                        type="number"
                        v-model="form_data.fallecidos"
                        label="Fallecidos *"
                        name="fallecidos"
                      />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-3 self-center">
                      <q-checkbox
                        class="text-grey-8"
                        v-model="form_data.plan_accion"
                        label="Plan de acción"
                        name="plan_accion"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        type="number"
                        v-model="form_data.medida_disciplinaria"
                        label="Medidas disciplinarias"
                        name="medida_disciplinaria"
                      />
                    </div>
                    <div class="col-5">
                      <q-input
                        v-model="form_data.naturaleza_medida_disciplinaria"
                        label="Naturaleza de las medidas"
                        name="naturaleza_medida_disciplinaria"
                      />
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-4 self-center">
                      <q-checkbox
                        class="text-grey-8"
                        v-model="form_data.seguimiento_evento"
                        label="Seguimiento"
                        name="seguimiento_evento"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        v-model="form_data.tipo_seguimiento"
                        label="Tipo seguimiento *"
                        name="tipo_seguimiento"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        v-model="form_data.fecha_seguimiento_evento"
                        label="Fecha *"
                        name="fecha_seguimiento_evento"
                        mask="####-##-##"
                      >
                        <template>
                          <q-popup-proxy
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-date
                              v-model="form_data.fecha_seguimiento_evento"
                              mask="YYYY-MM-DD"
                              minimal
                            >
                              <div class="row justify-end">
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
                  <div class="row">
                    <div class="col-4">
                      <q-input
                        v-model="form_data.ejecutado_por"
                        label="Ejecutado por"
                        name="ejecutado_por"
                      />
                    </div>
                    <div class="col-4 q-px-sm">
                      <q-input
                        autogrow
                        v-model="form_data.descripcion"
                        label="Descripción"
                        name="descripcion"
                      />
                    </div>
                    <div class="col-4">
                      <q-input
                        v-model="form_data.resultado_seguimiento_evento"
                        label="Resultado"
                        name="resultado_seguimiento_evento"
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
          <q-stepper
            flat
            header-class="text-uppercase"
            header-nav
            v-model="step"
            ref="stepper"
            color="primary"
            animated
            vertical
            bordered
          >
            <q-step
              :name="1"
              title="Datos generales"
              icon="mdi-file-document-outline"
              :done="step > 1"
            >
              <div class="row no-wrap">
                <div class="col-3">
                  <span class="text-weight-bold">No. Registro:</span>
                  <span class="">{{ detallesData['cod_registro'] }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Fecha:</span>
                  <span class="">{{
                    moment(detallesData['fecha_deteccion']).format('DD-MM-YYYY')
                  }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Detección:</span>
                  <span class="">{{ detallesData['deteccion'] }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Instalación:</span>
                  <span class="">{{}}</span>
                </div>
              </div>
              <div class="row no-wrap q-pt-md">
                <div class="col-3">
                  <span class="text-weight-bold">Cod. instalación:</span>
                  <span class="">{{}}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Osde:</span>
                  <span class="">{{}}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Territorio:</span>
                  <span class="">{{}}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Municipio:</span>
                  <span class="">{{}}</span>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn @click="step = 2" color="primary" label="Siguiente" />
              </q-stepper-navigation>
            </q-step>
            <q-step
              :name="2"
              title="Datos del foco"
              icon="mdi-file-document-outline"
              :done="step > 1"
            >
              <div class="row no-wrap">
                <div class="col-3">
                  <span class="text-weight-bold">Tipo de foco:</span>
                  <span class="">{{}}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">No. de focos:</span>
                  <span class="">{{ detallesData['cant_focos'] }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Depósito:</span>
                  <span class="">{{ detallesData['deposito_foco'] }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Ubicación:</span>
                  <span class="">{{ detallesData['ubicacion_foco'] }}</span>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn @click="step = 3" color="primary" label="Siguiente" />
                <q-btn
                  flat
                  @click="step = 1"
                  color="primary"
                  label="Atrás"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <q-step
              :name="3"
              title="Detalles de la muestra"
              icon="mdi-file-document-outline"
              :done="step > 2"
            >
              <div class="row no-wrap">
                <div class="col-4">
                  <span class="text-weight-bold">Tipo de muestra:</span>
                  <span class="">{{ detallesData['tipo_muestra'] }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Patógeno identificativo:</span>
                  <span class="">{{
                    detallesData['patogeno_identificado_muestra']
                  }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Ubicación:</span>
                  <span class="">{{ detallesData['ubicacion_muestra'] }}</span>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn @click="step = 4" color="primary" label="Siguiente" />
                <q-btn
                  flat
                  @click="step = 2"
                  color="primary"
                  label="Atrás"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <q-step
              :name="4"
              title="Detalles del caso"
              icon="mdi-file-document-outline"
              :done="step > 3"
            >
              <div class="row no-wrap">
                <div class="col-4">
                  <span class="text-weight-bold">Tipo de caso:</span>
                  <span class="">{{ detallesData['tipo_caso'] }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Origen del caso:</span>
                  <span class="">{{ detallesData['origen_caso'] }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Agente causal:</span>
                  <span class="">{{ detallesData['agente_causal_caso'] }}</span>
                </div>
              </div>

              <div class="row no-wrap q-pt-md">
                <div class="col-4">
                  <span class="text-weight-bold"
                    >Mecanismo de transmisión:</span
                  >
                  <span class="">{{
                    detallesData['mecanismo_transmision_caso']
                  }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Vehículo:</span>
                  <span class="">{{ detallesData['vehiculo_caso'] }}</span>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn @click="step = 5" color="primary" label="Siguiente" />
                <q-btn
                  flat
                  @click="step = 3"
                  color="primary"
                  label="Atrás"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <q-step
              :name="5"
              title="Detalles del brote"
              icon="mdi-file-document-outline"
              :done="step > 4"
            >
              <div class="row no-wrap">
                <div class="col-4">
                  <span class="text-weight-bold">Tipo de brote:</span>
                  <span class="">{{ detallesData['tipo_brote'] }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Origen del brote:</span>
                  <span class="">{{ detallesData['origen_brote'] }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Agente causal:</span>
                  <span class="">{{
                    detallesData['agente_causal_brote']
                  }}</span>
                </div>
              </div>

              <div class="row no-wrap q-pt-md">
                <div class="col-4">
                  <span class="text-weight-bold"
                    >Mecanismo de transmisión:</span
                  >
                  <span class="">{{
                    detallesData['mecanismo_transmision_brote']
                  }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Vehiculo:</span>
                  <span class="">{{ detallesData['vehiculo_brote'] }}</span>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn @click="step = 6" color="primary" label="Siguiente" />
                <q-btn
                  flat
                  @click="step = 4"
                  color="primary"
                  label="Atrás"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <q-step
              :name="6"
              title="Cantidad de clientes/ trabajadores"
              icon="mdi-file-document-outline"
              :done="step > 5"
            >
              <div class="row no-wrap">
                <div class="col-3">
                  <span class="text-weight-bold">Expuestos:</span>
                  <span class="">{{ detallesData['expuestos'] }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Afectados:</span>
                  <span class="">{{ detallesData['afectados'] }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Ingresados:</span>
                  <span class="">{{ detallesData['ingresados'] }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Fallecidos:</span>
                  <span class="">{{ detallesData['fallecidos'] }}</span>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn @click="step = 7" color="primary" label="Siguiente" />
                <q-btn
                  flat
                  @click="step = 5"
                  color="primary"
                  label="Atrás"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <q-step
              :name="7"
              title="Respuesta al evento"
              icon="mdi-file-document-outline"
              :done="step > 6"
            >
              <div class="row no-wrap">
                <div class="col-3">
                  <span class="text-weight-bold">Plan de acción:</span>
                  <span class="">{{
                    detallesData['plan_accion'] ? 'Si' : 'No'
                  }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Medidas disciplinarias:</span>
                  <span class="">{{
                    detallesData['medida_disciplinaria']
                  }}</span>
                </div>
                <div class="col-6">
                  <span class="text-weight-bold"
                    >Naturaleza de las medidas:</span
                  >
                  <span class="">{{
                    detallesData['naturaleza_medida_disciplinaria']
                  }}</span>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn @click="step = 8" color="primary" label="Siguiente" />
                <q-btn
                  flat
                  @click="step = 6"
                  color="primary"
                  label="Atrás"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <q-step
              :name="8"
              title="Seguimiento"
              icon="mdi-file-document-outline"
              :done="step > 7"
            >
              <div class="row no-wrap">
                <div class="col-3">
                  <span class="text-weight-bold">Tipo de seguimiento:</span>
                  <span class="">{{ detallesData['tipo_seguimiento'] }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Fecha:</span>
                  <span class="">{{
                    moment(detallesData['fecha_seguimiento_evento']).format(
                      'DD-MM-YYYY'
                    )
                  }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Ejecutado por:</span>
                  <span class="">{{ detallesData['ejecutado_por'] }}</span>
                </div>
                <div class="col-3">
                  <span class="text-weight-bold">Resultado:</span>
                  <span class="">{{
                    detallesData['resultado_seguimiento_evento']
                  }}</span>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn @click="step = 9" color="primary" label="Siguiente" />
                <q-btn
                  flat
                  @click="step = 7"
                  color="primary"
                  label="Atrás"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
            <q-step
              :name="9"
              title="Cierre del evento"
              icon="mdi-file-document-outline"
              :done="step > 8"
            >
              <div class="row no-wrap">
                <div class="col-4">
                  <span class="text-weight-bold">Informe conclusivo:</span>
                  <span class="">{{
                    detallesData['informe_conclusivo'] ? 'Si' : 'No'
                  }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Fecha de cierre:</span>
                  <span class="">{{
                    moment(detallesData['fecha_cierre']).format('DD-MM-YYYY')
                  }}</span>
                </div>
                <div class="col-4">
                  <span class="text-weight-bold">Estado del proceso:</span>
                  <span class="">{{ detallesData['estado_proceso'] }}</span>
                </div>
              </div>
              <q-stepper-navigation>
                <q-btn
                  flat
                  @click="step = 8"
                  color="primary"
                  label="Atrás"
                  class="q-ml-sm"
                />
              </q-stepper-navigation>
            </q-step>
          </q-stepper>
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
    <!-- File Uploader dialog-->
    <q-dialog v-model="fileUploadDialog">
      <q-card style="width: 500px; max-width: 90vw;">
        <q-card-section class="no-padding">
          <q-uploader
            flat
            class="full-width"
            label="Seleccione un documento"
            style="max-width: 500px;"
            :accept="
              uploadtype === 'plan_accion'
                ? 'application/pdf'
                : 'application/pdf,.doc,.xls,.xlsx,text/csv'
            "
            @rejected="showErrorMessage()"
            @added="file_selected"
            hide-upload-btn
          />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="check"
            @click="uploadtype === 'plan_accion' ? uploadFiles() : uploadDocs()"
            label="Aceptar"
            v-close-popup
            color="primary"
            flat
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
    <!-- File Uploader dialog-->
    <!-- List plan accion dialog-->
    <q-dialog v-model="listPlanAccionDialog">
      <q-card style="width: 500px; max-width: 90vw;">
        <q-card-section class="no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              Plan de acción
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
          <q-card-section>
            <q-table
              flat
              dense
              :data="planAccionData"
              :columns="columnspa"
              :visible-columns="visibleColumnsPA"
              row-key="id"
              :loading="this.getterLoading()"
              loading-label="Cargando elementos"
              binary-state-sort
              no-data-label="No se encontraron elementos a mostrar"
              hide-pagination
            >
              <template v-slot:body-cell-action="props">
                <q-td :props="props">
                  <div class="q-gutter-xs">
                    <q-btn
                      flat
                      dense
                      size="sm"
                      text-color="primary"
                      icon="mdi-download"
                      @click.prevent="
                        openModal(true, 'download_plan_accion', props.row)
                      "
                    >
                      <q-tooltip>Descargar documento</q-tooltip>
                    </q-btn>
                    <q-btn
                      flat
                      dense
                      size="sm"
                      text-color="negative"
                      icon="delete"
                      @click.prevent="
                        openModal(true, 'delete_plan_accion', props.row)
                      "
                    >
                      <q-tooltip>Eliminar documento</q-tooltip>
                    </q-btn>
                  </div>
                </q-td>
              </template>
            </q-table>
          </q-card-section>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="check"
            label="Aceptar"
            v-close-popup
            color="primary"
            flat
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
    <!-- List plan accion dialog-->
    <!-- List docresumen dialog-->
    <q-dialog v-model="listDocDialog">
      <q-card style="width: 500px; max-width: 90vw;">
        <q-card-section class="no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              Documentos Resumen
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
          <q-card-section>
            <q-table
              flat
              dense
              :data="docresumenData"
              :columns="columnspa"
              :visible-columns="visibleColumnsPA"
              row-key="id"
              :loading="this.getterLoading()"
              loading-label="Cargando elementos"
              binary-state-sort
              no-data-label="No se encontraron elementos a mostrar"
              hide-pagination
            >
              <template v-slot:body-cell-action="props">
                <q-td :props="props">
                  <div class="q-gutter-xs">
                    <q-btn
                      flat
                      dense
                      size="sm"
                      text-color="primary"
                      icon="mdi-download"
                      @click.prevent="
                        openModal(true, 'download_doc_resumen', props.row)
                      "
                    >
                      <q-tooltip>Descargar documento</q-tooltip>
                    </q-btn>
                    <q-btn
                      flat
                      dense
                      size="sm"
                      text-color="negative"
                      icon="delete"
                      @click.prevent="
                        openModal(true, 'delete_doc_resumen', props.row)
                      "
                    >
                      <q-tooltip>Eliminar documento</q-tooltip>
                    </q-btn>
                  </div>
                </q-td>
              </template>
            </q-table>
          </q-card-section>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="check"
            label="Aceptar"
            v-close-popup
            color="primary"
            flat
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
    <!-- List plan accion dialog-->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../utils/dialogo';
import {
  infoMessage,
  errorMessage,
  successMessage,
} from '../../utils/notificaciones';
import * as message from '../../utils/resources';
import {
  required,
  alphaNum,
  numeric,
  alpha,
  maxValue,
  requiredIf,
  integer,
} from 'vuelidate/lib/validators';
import moment from 'moment';
import Vue from 'vue';
import _ from 'lodash';

moment.locale('es');
const greaterThanZero = (value) => value > 0;
export default {
  name: 'GestionarEHE',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      uploadtype: '',
      step: 1,
      detallesData: [],
      planAccionData: [],
      docresumenData: [],
      clasificacionEvento: [],
      tipoFoco: [],
      tipoMuestra: [],
      patogenoIdentificado: [],
      deteccion: [],
      detectadoPor: [],
      form_data: {
        id: '',
        cod_registro: '',
        fecha_deteccion: '',
        deteccion: null,
        detectado_por: null,
        clasificacion_evento: null,
        tipo_foco: null,
        cant_focos: '',
        deposito_foco: '',
        ubicacion_foco: '',
        tipo_muestra: null,
        patogeno_identificado_muestra: null,
        ubicacion_muestra: '',
        tipo_caso: '',
        origen_caso: '',
        agente_causal_caso: '',
        mecanismo_transmision_caso: '',
        vehiculo_caso: '',
        alimento_involucrado_caso: '',
        tipo_brote: '',
        origen_brote: '',
        vehiculo_brote: '',
        agente_causal_brote: '',
        mecanismo_transmision_brote: '',
        alimento_involucrado_brote: '',
        expuestos: '',
        afectados: '',
        ingresados: '',
        fallecidos: '',
        plan_accion: false,
        medida_disciplinaria: '',
        naturaleza_medida_disciplinaria: '',
        seguimiento_evento: false,
        tipo_seguimiento: '',
        fecha_seguimiento_evento: '',
        ejecutado_por: '',
        descripcion: '',
        resultado_seguimiento_evento: '',
        informe_conclusivo: false,
      },
      modalDialog: false,
      modalDialogDetail: false,
      fileUploadDialog: false,
      listPlanAccionDialog: false,
      listDocDialog: false,
      filterInput: false,
      filter: '',
      accion: '',
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      form_error: false,
      haveMedia: false,
      file: null,
      required_error: false,
      visibleColumns: [
        'instalacion',
        'osde',
        'territorio',
        'deteccion',
        'clasificacion_evento',
        'estado_proceso',
        'action',
      ],
      visibleColumnsPA: ['nombre', 'action'],
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
          name: 'instalacion',
          align: 'left',
          label: 'instalación',
          field: (row) => row.instalaciones && row.instalaciones.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
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
          name: 'territorio',
          align: 'left',
          label: 'territorio',
          field: (row) =>
            row.instalaciones &&
            row.instalaciones.provincia &&
            row.instalaciones.provincia.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'deteccion',
          align: 'left',
          label: 'deteccion',
          field: 'deteccion',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'clasificacion_evento',
          align: 'left',
          label: 'clasificación',
          field: 'clasificacion_evento',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'estado_proceso',
          align: 'left',
          label: 'estado del proceso',
          field: 'estado_proceso',
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
      columnspa: [
        {
          name: 'id',
          align: 'left',
          label: 'No.',
          field: 'id',
          sortable: true,
          headerClasses: '',
        },
        {
          name: 'nombre',
          align: 'left',
          label: 'documentos',
          field: 'nombre',
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
      validations: {
        form_data: {},
      },
    };
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Eventos Higiénico-Epidemiológico' },
      { label: 'Gestionar eventos higiénico-epidemiológico' },
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
  },
  computed: {
    ...mapState('gestion_ehe', ['eventoEHE']),
  },
  methods: {
    moment,
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('gestion_ehe', [
      'listEventoEHE',
      'saveEventoEHE',
      'editEventoEHE',
      'deleteEventoEHE',
      'exportarInforme',
    ]),
    ...mapActions('clasificacion_evento', ['listClasificacion_evento']),
    ...mapActions('tipo_foco', ['listTipo_foco']),
    ...mapActions('tipo_muestra', ['listTipo_muestra']),
    ...mapActions('patogeno_identificado', ['listPatogeno_identificado']),
    ...mapActions('deteccion', ['listDeteccion']),
    ...mapActions('detectado_por', ['getDetectado_por']),

    mensajesError() {},
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    reset_form() {
      this.uploadtype = '';
      this.form_data.id = '';
      this.form_data.cod_registro = '';
      this.form_data.fecha_deteccion = '';
      this.form_data.deteccion = null;
      this.form_data.detectado_por = null;
      this.form_data.clasificacion_evento = null;
      this.form_data.tipo_foco = null;
      this.form_data.cant_focos = '';
      this.form_data.deposito_foco = '';
      this.form_data.ubicacion_foco = '';
      this.form_data.tipo_muestra = null;
      this.form_data.patogeno_identificado_muestra = null;
      this.form_data.ubicacion_muestra = '';
      this.form_data.tipo_caso = null;
      this.form_data.origen_caso = '';
      this.form_data.agente_causal_caso = '';
      this.form_data.mecanismo_transmision_caso = '';
      this.form_data.vehiculo_caso = '';
      this.form_data.alimento_involucrado_caso = '';
      this.form_data.tipo_brote = '';
      this.form_data.origen_brote = '';
      this.form_data.vehiculo_brote = '';
      this.form_data.agente_causal_brote = '';
      this.form_data.mecanismo_transmision_brote = '';
      this.form_data.alimento_involucrado_brote = '';
      this.form_data.expuestos = '';
      this.form_data.afectados = '';
      this.form_data.ingresados = '';
      this.form_data.fallecidos = '';
      this.form_data.plan_accion = false;
      this.form_data.medida_disciplinaria = '';
      this.form_data.naturaleza_medida_disciplinaria = '';
      this.form_data.seguimiento_evento = false;
      this.form_data.tipo_seguimiento = '';
      this.form_data.fecha_seguimiento_evento = '';
      this.form_data.ejecutado_por = '';
      this.form_data.descripcion = '';
      this.form_data.resultado_seguimiento_evento = '';
      this.form_data.informe_conclusivo = false;
      this.form_error = false;
      this.required_error = false;
      this.step = 1;
      this.detallesData = [];
      this.planAccionData = [];
      this.file = null;
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data);
    },
    async openModal(data, accion, rowData) {
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
        case 'plan_accion':
          if (rowData) {
            this.uploadtype = 'plan_accion';
            this.setFormData(rowData);
            this.fileUploadDialog = data;
          } else {
            infoMessage('Debe seleccionar un elemento para ver los detalles.');
          }
          break;
        case 'list_plan_accion':
          if (rowData) {
            this.setFormData(rowData);
            this.listPlanAccion();
            this.listPlanAccionDialog = data;
          } else {
            infoMessage('Debe seleccionar un elemento para ver los detalles.');
          }
          break;
        case 'download_plan_accion':
          if (rowData) {
            this.downloadPA(rowData);
          } else {
            infoMessage('Debe seleccionar un elemento.');
          }
          break;
        case 'delete_plan_accion':
          if (rowData) {
            this.deletePA(rowData);
          } else {
            infoMessage('Debe seleccionar un elemento.');
          }
          break;
        case 'doc_resumen':
          if (rowData) {
            this.uploadtype = 'doc_resumen';
            this.setFormData(rowData);
            this.fileUploadDialog = data;
          } else {
            infoMessage('Debe seleccionar un elemento para ver los detalles.');
          }
          break;
        case 'list_doc_resumen':
          if (rowData) {
            this.setFormData(rowData);
            this.listDoc();
            this.listDocDialog = data;
          } else {
            infoMessage('Debe seleccionar un elemento para ver los detalles.');
          }
          break;
        case 'download_doc_resumen':
          if (rowData) {
            this.downloadDoc(rowData);
          } else {
            infoMessage('Debe seleccionar un elemento.');
          }
          break;
        case 'delete_doc_resumen':
          if (rowData) {
            this.deleteDoc(rowData);
          } else {
            infoMessage('Debe seleccionar un elemento.');
          }
          break;
        default:
          break;
      }
    },
    async loadDataNomencladores() {
      this.listClasificacion_evento().then((response) => {
        this.clasificacionEvento = response.data;
      });

      this.listTipo_foco().then((response) => {
        this.tipoFoco = response.data;
      });

      this.listTipo_muestra().then((response) => {
        this.tipoMuestra = response.data;
      });

      this.listPatogeno_identificado().then((response) => {
        this.patogenoIdentificado = response.data;
      });

      this.listDeteccion().then((response) => {
        this.deteccion = response.data;
      });

      this.getDetectado_por().then((response) => {
        this.detectadoPor = response;
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
      await this.listEventoEHE(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    async save() {
      this.setLoading(true);
      await this.saveEventoEHE(this.form_data);
      this.reset_form();
      this.closeModal();
      this.loadData({
        pagination: this.pagination,
        filter: this.filter,
      });

      this.setLoading(false);
    },
    async update() {
      this.setLoading(true);
      await this.editEventoEHE(this.form_data);
      this.reset_form();
      this.closeModal();
      this.loadData({
        pagination: this.pagination,
        filter: this.filter,
      });
      this.setLoading(false);
    },
    deleteEventosEHE(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteEventoEHE(selected.id);
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
    exportarInformePDF(rowData) {
      this.setLoading(true);
      const url = '/api/eventohe/resumenPDF';
      return Vue.prototype
        .$axios({
          method: 'post',
          url: url,
          responseType: 'arraybuffer',
          data: rowData,
        })
        .then((response) => {
          const blob = new Blob([response.data], {
            type: 'application/pdf',
          });
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = 'Resumen.pdf';
          link.click();
          this.setLoading(false);
        })
        .catch((error) => {
          this.setLoading(false);
          errorMessage(message.eDataError, error);
        });
    },
    showErrorMessage() {
      errorMessage(message.eUpLoadFile, 'Formato no admitido');
    },
    async uploadFiles() {
      if (this.haveMedia) {
        const formData = new FormData();
        formData.append('file', this.file);
        formData.append('codigo_registro', this.form_data.cod_registro);
        formData.append('ehe_id', this.form_data.id);

        return Vue.prototype.$axios
          .post('/api/eventohe/uploadplanaccion', formData, {
            headers: { 'content-type': 'multipart/form-data' },
            processData: false,
            contentType: false,
          })
          .then(() => {
            successMessage(message.sUpLoadFile);
          })
          .catch((error) => {
            errorMessage(message.eUpLoadFile, error);
          });
      } else {
        errorMessage('Tiene que agregar un plan de acción');
      }
    },
    file_selected(file) {
      this.file = file[0];
      this.haveMedia = true;
    },
    listPlanAccion() {
      const data = this.form_data;
      return Vue.prototype.$axios
        .post('/api/eventohe/listplanaccion', data)
        .then((response) => {
          this.planAccionData = response.data.data;
        })
        .catch((error) => {
          errorMessage(message.eDataError, error);
        });
    },
    deletePA(param) {
      const data = param;
      showDialog(message.lAskForRemove).onOk(async () => {
        this.setLoading(true);
        return Vue.prototype.$axios
          .delete('/api/eventohe/deleteplanaccion/' + data.id)
          .then((response) => {
            this.planAccionData = response.data.data;
            successMessage(message.sRemoveOk);
            this.setLoading(false);
          })
          .catch((err) => {
            errorMessage(message.eRemoveError, err);
          });
      });
    },
    downloadPA(param) {
      const data = param;

      return Vue.prototype.$axios
        .post('/api/eventohe/downloadplanaccion', data, {
          responseType: 'blob',
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', data.nombre);
          document.body.appendChild(link);
          link.click();
          this.reset_form();
        })
        .catch((error) => {
          errorMessage(message.eDataError, error);
        });
    },
    async uploadDocs() {
      if (this.haveMedia) {
        const formData = new FormData();
        formData.append('file', this.file);
        formData.append('codigo_registro', this.form_data.cod_registro);
        formData.append('ehe_id', this.form_data.id);
        return Vue.prototype.$axios
          .post('/api/eventohe/uploadresumen', formData, {
            headers: { 'content-type': 'multipart/form-data' },
            processData: false,
            contentType: false,
          })
          .then(() => {
            successMessage(message.sUpLoadFile);
          })
          .catch((error) => {
            errorMessage(message.eUpLoadFile, error);
          });
      } else {
        errorMessage('Tiene que agregar un plan de acción');
      }
    },
    files_selected(file) {
      this.file = file;
      this.haveMedia = true;
    },
    listDoc() {
      const data = this.form_data;
      return Vue.prototype.$axios
        .post('/api/eventohe/listresumen', data)
        .then((response) => {
          this.docresumenData = response.data.data;
        })
        .catch((error) => {
          errorMessage(message.eDataError, error);
        });
    },
    downloadDoc(param) {
      const data = param;

      return Vue.prototype.$axios
        .post('/api/eventohe/downloadresumen', data, {
          responseType: 'blob',
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', data.nombre);
          document.body.appendChild(link);
          link.click();
          this.reset_form();
        })
        .catch((error) => {
          errorMessage(message.eDataError, error);
        });
    },
    deleteDoc(param) {
      const data = param;
      showDialog(message.lAskForRemove).onOk(async () => {
        this.setLoading(true);
        return Vue.prototype.$axios
          .delete('/api/eventohe/deleteresumen/' + data.id)
          .then((response) => {
            this.docresumenData = response.data.data;
            successMessage(message.sRemoveOk);
            this.setLoading(false);
          })
          .catch((err) => {
            errorMessage(message.eRemoveError, err);
          });
      });
    },
    removeItemFromArr() {
      //TODO
    },
  },
};
</script>

<style></style>
