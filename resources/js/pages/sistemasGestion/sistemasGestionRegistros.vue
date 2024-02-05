<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <div  class="q-pb-sm text-subtitle1 text-weight-bold text-uppercase">
          Gestionar registros
        </div>
        <div class="row">
          <div class="col">
          </div>
          <div class="col">
            Instalación:{{this.form_data.instalacion_name}}
          </div>
          <div class="col">
            Entidad: entidad
          </div>
          <div class="col">
            OSDE: {{this.form_data.osde_name}}
          </div>
        </div>
      </q-card-section>
      <q-card-section>
        <q-table
          flat
          dense
          :data="data"
          :visible-columns="visibleColumns"
          :columns="columns"
          row-key="id"
          :loading="loading"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 15]"
          :filter="filter"
          @request="loadGetAllRegisters"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          no-results-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
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
              @click.prevent="openModalAdd()"
              class="q-mt-sm"
            >
              <q-tooltip>Nuevos datos</q-tooltip>
            </q-btn>
          </template>
          <template v-slot:body-cell-columnestado="props">
            <q-td key="columnestado" :props="props">
              <q-badge color="orange">
                {{ props.row.estado }}
              </q-badge>
            </q-td>
          </template>
          <template v-slot:body-cell-columnactions="props">
            <q-td :props="props">
              <div class="q-gutter-sm">
                <q-btn
                  v-if="scopes.includes('gestionar_indicadores_lin')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="settings"
                  @click.prevent="openModalEdit(props.row)"
                >
                  <q-tooltip>Configurar elemento</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_indicadores_lin')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="openModalDelete(props.row)"
                >
                  <q-tooltip>Eliminar elemento</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
    <!-- Dialog -->
    <q-dialog
      v-model="modalDialogAdd"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 500px">
        <q-form
          @reset="resetFormAdd"
          ref="formAdd"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  Adicionar Registro
                </q-toolbar-title>
                <q-btn
                  round
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
              <div class="row">
                <div class="col" style="padding: 0px 20px 20px 0px">
                  <label name="desc2"  type="text"  dense>Datos Generales </label>
                </div>
              </div>
                <div class="col-6">
                  <div class="q-pa-md" >
                    <div class="q-gutter-md">
                      <q-select
                        class="col"
                        id="systemtypes"
                        label="Sistemas de Gestión"
                        v-model="form_detailsregister.tipoSistGest_id"
                        dense
                        input-debounce="0"
                        :options="listsystemtype"
                        options-dense
                        option-label="desc_TipoSistGestion" option-value="id"
                        emit-value
                        map-options
                      >
                        <template v-slot:prepend>
                          <q-icon name="mdi-fireplace-off" />
                        </template>
                      </q-select>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="q-pa-md" >
                    <div class="q-gutter-md">
                      <q-select
                        class="col"
                        id="operators"
                        label="Operadoras"
                        v-model="form_detailsregister.operadora_id"
                        dense
                        input-debounce="0"
                        :options="listoperator"
                        options-dense
                        option-label="desc_Operadora" option-value="id"
                        emit-value
                        map-options
                      >
                        <template v-slot:prepend>
                          <q-icon name="mdi-calendar"/>
                        </template>
                      </q-select>
                    </div>
                  </div>
              </div>
              <div v-show="this.form_detailsregister.operadora_id == 1" class="col-6">
                <div class="q-pa-md" >
                  <div class="q-gutter-md">
                    <q-select
                      class="col-3"
                      id="cadenas"
                      label="Cadena"
                      v-model="form_detailsregister.cadena"
                      dense
                      input-debounce="0"
                      options-dense
                      emit-value
                      map-options
                      disable
                    >
                      <template v-slot:prepend>
                        <q-icon name="mdi-fireplace-off" />
                      </template>
                    </q-select>
                  </div>
                </div>

              </div>
            </q-card-section>
            <q-card-actions align="right">
              <q-btn type="button" :icon="'save'"
                     @click="addElement(form_data)" label="Guardar"
                     color="secondary" flat/>
            </q-card-actions>
          </q-card-section>
        </q-form>
      </q-card>
    </q-dialog>
    <q-dialog
      v-model="modalDialogEdit"
      persistent
      transition-show="scale"
      transition-hide="scale"
      maximized
    >
      <q-card >
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              Control del proceso de Implementación, Certificación y Renovación  de los Sistemas de Gestión
            </q-toolbar-title>
            <q-btn
              round
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
        <div class="q-pa-md">
          <template>
            <div class="q-pa-md">
              <q-stepper
                v-model="stepprocess"
                header-nav
                ref="stepper"
                color="primary"
                animated
              >
                <!--Datos Generales-->
                <q-step
                  :name="1"
                  title="Datos Generales"
                  icon="settings"
                  :done="donegeneral"
                >
                  <q-card-section>
                    <div class="row">
                      <div class="col-5">
                        <div class="q-pa-md" >
                          <div class="q-gutter-md">
                            <q-select
                              class="col"
                              id="systemtype"
                              label="Sistemas de Gestión"
                              v-model="form_detailsregister.tipoSistGest_id"
                              dense
                              input-debounce="0"
                              :options="listsystemtype"
                              options-dense
                              option-label="desc_TipoSistGestion" option-value="id"
                              emit-value
                              map-options
                              :disable="true"
                            >
                              <template v-slot:prepend>
                                <q-icon name="mdi-fireplace-off" />
                              </template>
                            </q-select>
                          </div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="q-pa-md" >
                          <div class="q-gutter-md">
                            <q-select
                              class="col"
                              id="operator"
                              label="Operadoras"
                              v-model="form_detailsregister.operadora_id"
                              dense
                              input-debounce="0"
                              :options="listoperator"
                              options-dense
                              option-label="desc_Operadora" option-value="id"
                              emit-value
                              map-options
                            >
                              <template v-slot:prepend>
                                <q-icon name="mdi-calendar"/>
                              </template>
                            </q-select>
                          </div>
                        </div>
                      </div>
                      <div v-show="form_detailsregister.operadora_id == 1" class="col-3">
                        <div class="q-pa-md" >
                          <div class="q-gutter-md">
                            <q-select
                              class="col-3"
                              id="cadenas1"
                              label="Cadena"
                              v-model="form_detailsregister.cadena"
                              dense
                              input-debounce="0"
                              options-dense
                              emit-value
                              map-options
                              disable
                            >
                              <template v-slot:prepend>
                                <q-icon name="mdi-fireplace-off" />
                              </template>
                            </q-select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </q-card-section>
                  <q-stepper-navigation>
                    <q-btn type="button" :icon="'save'"
                           @click="editElement('Datos generales')" label="Guardar"
                           color="secondary" flat/>
                  </q-stepper-navigation>
                </q-step>
                <!--Diseño e implementación-->
                <q-step
                  :name="2"
                  title="Diseño e implementación"
                  caption="Optional"
                  icon="create_new_folder"
                  :done="donedesign"
                >
                  <q-card-section class="no-padding">
                    <q-card-section>
                      <div class="row" >
                        <div class="col-4">
                          <div class="q-pa-md">
                            <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                              <q-item>
                                <q-item-section>Diagnóstico</q-item-section>
                              </q-item>
                              <q-separator color="orange" inset />
                              <q-item>
                                <q-item-section>
                                    <q-input :dense="dense" v-model="form_detailsregister.diagnostico_FI" label="Fecha Inicio">
                                      <template v-slot:append>
                                        <q-icon name="event" class="cursor-pointer">
                                          <q-popup-proxy @before-show="updateDate('diagnosticI')" transition-show="scale" transition-hide="scale">
                                            <q-date v-model="fi_diagnosticdate">
                                              <div class="row items-center justify-end q-gutter-sm">
                                                <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                <q-btn label="ok" color="primary" flat @click="saveDate('diagnosticI')"  v-close-popup/>
                                              </div>
                                            </q-date>
                                          </q-popup-proxy>
                                        </q-icon>
                                      </template>
                                    </q-input>
                                </q-item-section>
                              </q-item>
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.diagnostico_FC" label="Fecha Cierre">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('diagnosticC')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="fc_diagnosticdate">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('diagnosticC')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                            </q-list>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="q-pa-md">
                            <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                              <q-item>
                                <q-item-section>Formación y Capacitación</q-item-section>
                              </q-item>
                              <q-separator color="orange" inset />
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.formacion_FI" label="Fecha Inicio">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('formacionI')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="fi_formaciondate">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('formacionI')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.formacion_FC" label="Fecha Cierre">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('formacionC')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="fc_formaciondate">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('formacionC')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                            </q-list>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="q-pa-md">
                            <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                              <q-item>
                                <q-item-section>Diseño del Sist. Gest. y Preparación de la Información Documentada</q-item-section>
                              </q-item>
                              <q-separator color="orange" inset />
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.disenno_FI" label="Fecha Inicio">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('disennoI')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="fi_disennodate">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('disennoI')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.disenno_FC" label="Fecha Cierre">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('disennoC')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="fc_disennodate">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('disennoC')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                            </q-list>
                          </div>
                        </div>
                      </div>
                    </q-card-section>
                    <q-card-section>
                      <div class="row" >
                        <div class="col-4">
                          <div class="q-pa-md">
                            <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                              <q-item>
                                <q-item-section>Implementación</q-item-section>
                              </q-item>
                              <q-separator color="orange" inset />
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.implementacion_FI" label="Fecha Inicio">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('implementacionI')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="fi_implementaciondate">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('implementacionI')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.implementacion_FC" label="Fecha Cierre">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('implementacionC')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="fc_implementaciondate">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('implementacionC')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                            </q-list>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="q-pa-md">
                            <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                              <q-item>
                                <q-item-section>Auditoría Interna</q-item-section>
                              </q-item>
                              <q-separator color="orange" inset />
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.auditoria_F" label="Fecha">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer ">
                                        <q-popup-proxy @before-show="updateDate('auditoriaF')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="f_auditoriadate">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('auditoriaF')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                      <q-icon name="format_list_bulleted" class="cursor-pointer">
                                        <q-popup-edit
                                          v-model="auditoria_FList"
                                          v-slot="scope"
                                        >
                                          <q-input
                                            type="textarea"
                                            v-model="auditoria_FList"
                                            autofocus
                                            counter
                                            @keyup.enter.stop
                                            disable
                                          />
                                        </q-popup-edit>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                            </q-list>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="q-pa-md">
                            <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                              <q-item>
                                <q-item-section>Revisión por la Dirección</q-item-section>
                              </q-item>
                              <q-separator color="orange" inset />
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.direccion_F" label="Fecha">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('direccionF')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="f_direcciondate">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('direccionF')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                      <q-icon name="format_list_bulleted" class="cursor-pointer">
                                        <q-popup-edit
                                          v-model="direccion_FList"
                                          v-slot="scope"
                                        >
                                          <q-input
                                            type="textarea"
                                            v-model="direccion_FList"
                                            autofocus
                                            counter
                                            @keyup.enter.stop
                                            disable
                                          />
                                        </q-popup-edit>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                            </q-list>
                          </div>
                        </div>
                      </div>
                    </q-card-section>
                    <q-card-section>
                      <div class="row" >
                        <div class="col-8">
                          <template>
                            <div class="q-pa-md" >
                              <q-input
                                filled
                                clearable
                                type="textarea"
                                color="red-12"
                                label="Mejora"
                                v-model="form_detailsregister.mejora"
                              />
                            </div>
                          </template>
                        </div>
                      </div>
                    </q-card-section>
                  </q-card-section>
                  <q-stepper-navigation>
                    <q-btn type="button" :icon="'save'"
                           @click="editElement('Diseño e implementación')" label="Guardar"
                           color="secondary" flat/>
                  </q-stepper-navigation>
                </q-step>
                <!--Certificación Inicial-->
                <q-step
                  :name="3"
                  title="Certificación Inicial"
                  icon="add_comment"
                  :done="doneinitial"
                >
                  <q-stepper
                        v-model="stepinitial"
                        header-nav
                        vertical
                        ref="stepper"
                        color="primary"
                        animated
                      >
                        <q-step
                          :name="5"
                          title="1. Solicitud"
                          icon="settings"
                          :done="donerequest"
                        >
                          <q-card-section class="no-padding">
                            <q-card-section>
                              <div class="row" >
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Aval de la Entidad</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.aval_entidad" label="Fecha">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('aval_entidad')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_aval_entidaddate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('aval_entidad')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Inclusión de la Instalación en el Modelo Demanda de la Entidad para el año fiscal siguiente</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-checkbox
                                            v-model="inclusion"
                                            color="secondary"
                                            :label="inclusion"
                                            true-value="Si"
                                            false-value="No"
                                          />
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                              </div>
                            </q-card-section>
                            <q-card-section>
                              <div class="row" >
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Conciliación de la Demanda</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.conciliacion_demanda" label="Fecha">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('conciliacion_demanda')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_conciliacion_demandadate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('conciliacion_demanda')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Ratificación de la Demanda</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.ratificacion_demanda" label="Fecha">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('ratificacion_demanda')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_ratificacion_demandadate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('ratificacion_demanda')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Firma del Contrato</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.firma_contrato" label="Fecha">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('firma_contrato')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_firma_contratodate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('firma_contrato')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                              </div>
                            </q-card-section>
                          </q-card-section>
                          <q-stepper-navigation>
                            <q-btn type="button" :icon="'save'"
                                   @click="editElement('Certificación Inicial')" label="Guardar"
                                   color="secondary" flat/>
                          </q-stepper-navigation>
                        </q-step>
                        <q-step
                          :name="6"
                          title="2. Preparación para evaluación"
                          icon="create_new_folder"
                          :done="donepreparation"
                        >
                          <q-card-section>
                            <div class="row" >
                                <div class="col-4">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>FASE I Auditoría (Visita previa-Revisión documental)</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.fase1_auditoriaI" label="Fecha Inicio">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('fase1_auditoriaI')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_fase1_auditoriaIdate">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('fase1_auditoriaI')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.fase1_auditoriaC" label="Fecha Cierre">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('fase1_auditoriaC')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_fase1_auditoriaCdate">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('fase1_auditoriaC')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                          </q-card-section>
                          <q-stepper-navigation>
                            <q-btn type="button" :icon="'save'"
                                   @click="editElement('Certificación Inicial')" label="Guardar"
                                   color="secondary" flat/>
                          </q-stepper-navigation>
                        </q-step>
                        <q-step
                          :name="7"
                          title="3. Evaluación"
                          icon="add_comment"
                          :done="doneevaluation"
                        >
                          <q-card-section class="no-padding">
                            <q-card-section>
                              <div class="row" >
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>FASE II Auditoría in Situ</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.fase2_auditoriaI" label="Fecha Inicio">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('fase2_auditoriaI')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_fase2_auditoriaIdate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('fase2_auditoriaI')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.fase2_auditoriaC" label="Fecha Cierre">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('fase2_auditoriaC')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_fase2_auditoriaCdate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('fase2_auditoriaC')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Auditoría Adicional</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.auditoria_adicionalI" label="Fecha Inicio">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('auditoria_adicionalI')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_auditoria_adicionalIdate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('auditoria_adicionalI')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.auditoria_adicionalC" label="Fecha Cierre">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('auditoria_adicionalC')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_auditoria_adicionalCdate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('auditoria_adicionalC')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                              </div>
                            </q-card-section>
                          </q-card-section>
                          <q-stepper-navigation>
                            <q-btn type="button" :icon="'save'"
                                   @click="editElement('Certificación Inicial')" label="Guardar"
                                   color="secondary" flat/>
                          </q-stepper-navigation>
                        </q-step>
                        <q-step
                          :name="8"
                          title="4. Informe de la Evaluación y 5. Decisión sobre la Certificación"
                          icon="add_comment"
                          :done="donedecision"
                        >
                          <q-card-section class="no-padding">
                            <q-card-section>
                              <div class="row" >
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Certificado Otorgado</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-option-group
                                            v-model="form_detailsregister.certificado_otorgado"
                                            :options="options_certificado_otorgado"
                                            color="primary"
                                          />
                                          <div class="q-pa-md">
                                            <q-input v-if="form_detailsregister.certificado_otorgado !== 'opt0'" dense v-model="form_detailsregister.certificado_otorgadofecha" label="Fecha">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('certificado_otorgadofecha')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_certificado_otorgadofechadate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('certificado_otorgadofecha')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                            <ul v-if="form_detailsregister.certificado_otorgado !== 'opt0'" style="color: red;padding: 0px;font-size: revert;">
                                              {{error_certificacion_informe}}
                                            </ul>
                                          </div>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                                <div class="col-5">
                                  <div class="q-pa-md" >
                                    <q-input
                                      filled
                                      clearable
                                      type="textarea"
                                      color="red-12"
                                      label="Alcance de la Certificación"
                                      v-model="form_detailsregister.alcance_certificacion"
                                    />
                                  </div>
                                </div>
                              </div>
                            </q-card-section>
                            <q-card-section>
                              <div class="row" >
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Validez Hasta (3 años)</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.validez_hasta" label="Fecha">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('validez_hasta')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_validez_hastadate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('validez_hasta')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                                <div class="col-5">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Certificado Denegado</q-item-section>
                                        <q-icon name="event" class="cursor-pointer">
                                        </q-icon>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.certificado_denegado" label="Fecha" >
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('certificado_denegado')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_certificado_denegadodate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('certificado_denegado')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                          <div v-if="form_detailsregister.certificado_denegado !== null" class="q-py-md">
                                            <q-input
                                              filled
                                              clearable
                                              type="textarea"
                                              color="red-12"
                                              label="Motivo"
                                              v-model="form_detailsregister.motivo_denegacion"
                                            />
                                            <ul v-if="this.form_detailsregister.motivo_denegacion === null || this.form_detailsregister.motivo_denegacion === ''" style="color: red;padding: 0px;font-size: revert;">
                                              {{error_motivo_denegacion}}
                                            </ul>
                                          </div>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                              </div>
                            </q-card-section>
                          </q-card-section>
                          <q-stepper-navigation>
                            <q-btn type="button" :icon="'save'"
                                   @click="editElement('Certificación Inicial')" label="Guardar"
                                   color="secondary" flat/>
                          </q-stepper-navigation>
                        </q-step>
                        <q-step
                          :name="9"
                          title="6. Seguimiento"
                          icon="add_comment"
                          :done="donefollowup"
                        >
                          <q-card-section class="no-padding">
                            <q-card-section>
                              <div class="row" >
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Auditorías de Seguimiento Anual</q-item-section>
                                        <q-btn
                                          type="reset"
                                          key="add"
                                          dense
                                          icon="add"
                                          @click.prevent="openInputAdd_audseganual = true"
                                          class="q-mt-sm"
                                        >
                                          <q-tooltip>Nueva fecha</q-tooltip>
                                        </q-btn>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input v-if="openInputAdd_audseganual" :dense="dense" v-model="form_detailsregister.auditorias_seg_anual" label="Fecha">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('auditorias_seg_anual')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_auditorias_seg_anualdate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('auditorias_seg_anual')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                          <ul v-for="(index,row) in auditorias_seg_anualList" class="q-mt-xs">
                                            <li class="text-subtitle1">{{index}}</li>
                                          </ul>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Auditoría de Seguimiento Extraordinario</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.auditorias_seg_extraordinario" label="Fecha Inicio">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('auditorias_seg_extraordinario')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_auditorias_seg_extraordinariodate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('auditorias_seg_extraordinario')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                                <div class="col-4">
                                  <div class="q-pa-md">
                                    <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                      <q-item>
                                        <q-item-section>Cancelación del Certificado</q-item-section>
                                      </q-item>
                                      <q-separator color="orange" inset />
                                      <q-item>
                                        <q-item-section>
                                          <q-input :dense="dense" v-model="form_detailsregister.cancelacion_certificado" label="Fecha">
                                            <template v-slot:append>
                                              <q-icon name="event" class="cursor-pointer">
                                                <q-popup-proxy @before-show="updateDate('cancelacion_certificado')" transition-show="scale" transition-hide="scale">
                                                  <q-date v-model="f_cancelacion_certificadodate">
                                                    <div class="row items-center justify-end q-gutter-sm">
                                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                      <q-btn label="ok" color="primary" flat @click="saveDate('cancelacion_certificado')"  v-close-popup/>
                                                    </div>
                                                  </q-date>
                                                </q-popup-proxy>
                                              </q-icon>
                                            </template>
                                          </q-input>
                                          <div v-if="form_detailsregister.cancelacion_certificado !== null" class="q-py-md">
                                            <q-input
                                              filled
                                              clearable
                                              type="textarea"
                                              color="red-12"
                                              label="Motivo"
                                              v-model="form_detailsregister.motivo_denegacion2"
                                            />
                                            <ul v-if="this.form_detailsregister.motivo_denegacion2 === null || this.form_detailsregister.motivo_denegacion === ''" style="color: red;padding: 0px;font-size: revert;">
                                              {{error_motivo_denegacion2}}
                                            </ul>
                                          </div>
                                        </q-item-section>
                                      </q-item>
                                    </q-list>
                                  </div>
                                </div>
                              </div>
                            </q-card-section>
                          </q-card-section>
                          <q-stepper-navigation>
                            <q-btn type="button" :icon="'save'"
                                   @click="editElement('Certificación Inicial')" label="Guardar"
                                   color="secondary" flat/>
                          </q-stepper-navigation>
                        </q-step>
                  </q-stepper>
                </q-step>
                <!--Renovación de la Certificación-->
                <q-step
                  :name="4"
                  title="Renovación de la Certificación"
                  icon="add_comment"
                  :done="donerenewal"
                >
                  <q-stepper
                    v-model="stepinitial"
                    header-nav
                    vertical
                    ref="stepper"
                    color="primary"
                    animated
                  >
                    <q-step
                      :name="10"
                      title="1. Solicitud"
                      icon="settings"
                      :done="donerequest"
                    >
                      <q-card-section class="no-padding">
                        <q-card-section>
                          <div class="row" >
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Aval de la Entidad</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.aval_entidadR" label="Fecha">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('aval_entidadR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_aval_entidaddateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('aval_entidadR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Inclusión de la Instalación en el Modelo Demanda de la Entidad para el año fiscal siguiente</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-checkbox
                                        v-model="inclusionR"
                                        color="secondary"
                                        :label="inclusionR"
                                        true-value="Si"
                                        false-value="No"
                                      />
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                          </div>
                        </q-card-section>
                        <q-card-section>
                          <div class="row" >
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Conciliación de la Demanda</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.conciliacion_demandaR" label="Fecha">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('conciliacion_demandaR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_conciliacion_demandadateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('conciliacion_demandaR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Ratificación de la Demanda</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.ratificacion_demandaR" label="Fecha">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('ratificacion_demandaR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_ratificacion_demandadateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('ratificacion_demandaR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Firma del Contrato</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.firma_contratoR" label="Fecha">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('firma_contratoR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_firma_contratodateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('firma_contratoR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                          </div>
                        </q-card-section>
                      </q-card-section>
                      <q-stepper-navigation>
                        <q-btn type="button" :icon="'save'"
                               @click="editElement('Certificación Inicial')" label="Guardar"
                               color="secondary" flat/>
                      </q-stepper-navigation>
                    </q-step>
                    <q-step
                      :name="11"
                      title="2. Preparación para evaluación"
                      icon="create_new_folder"
                      :done="donepreparation"
                    >
                      <q-card-section>
                        <div class="row" >
                          <div class="col-4">
                            <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                              <q-item>
                                <q-item-section>FASE I Auditoría (Visita previa-Revisión documental)</q-item-section>
                              </q-item>
                              <q-separator color="orange" inset />
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.fase1_auditoriaIR" label="Fecha Inicio">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('fase1_auditoriaIR')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="f_fase1_auditoriaIdateR">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('fase1_auditoriaIR')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                              <q-item>
                                <q-item-section>
                                  <q-input :dense="dense" v-model="form_detailsregister.fase1_auditoriaCR" label="Fecha Cierre">
                                    <template v-slot:append>
                                      <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy @before-show="updateDate('fase1_auditoriaCR')" transition-show="scale" transition-hide="scale">
                                          <q-date v-model="f_fase1_auditoriaCdateR">
                                            <div class="row items-center justify-end q-gutter-sm">
                                              <q-btn label="cancel" color="primary" flat v-close-popup/>
                                              <q-btn label="ok" color="primary" flat @click="saveDate('fase1_auditoriaCR')"  v-close-popup/>
                                            </div>
                                          </q-date>
                                        </q-popup-proxy>
                                      </q-icon>
                                    </template>
                                  </q-input>
                                </q-item-section>
                              </q-item>
                            </q-list>
                          </div>
                        </div>
                      </q-card-section>
                      <q-stepper-navigation>
                        <q-btn type="button" :icon="'save'"
                               @click="editElement('Certificación Inicial')" label="Guardar"
                               color="secondary" flat/>
                      </q-stepper-navigation>
                    </q-step>
                    <q-step
                      :name="12"
                      title="3. Evaluación"
                      icon="add_comment"
                      :done="doneevaluation"
                    >
                      <q-card-section class="no-padding">
                        <q-card-section>
                          <div class="row" >
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>FASE II Auditoría in Situ</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.fase2_auditoriaIR" label="Fecha Inicio">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('fase2_auditoriaIR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_fase2_auditoriaIdateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('fase2_auditoriaIR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.fase2_auditoriaCR" label="Fecha Cierre">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('fase2_auditoriaCR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_fase2_auditoriaCdateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('fase2_auditoriaCR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Auditoría Adicional</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.auditoria_adicionalIR" label="Fecha Inicio">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('auditoria_adicionalIR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_auditoria_adicionalIdateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('auditoria_adicionalIR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.auditoria_adicionalCR" label="Fecha Cierre">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('auditoria_adicionalCR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_auditoria_adicionalCdateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('auditoria_adicionalCR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                          </div>
                        </q-card-section>
                      </q-card-section>
                      <q-stepper-navigation>
                        <q-btn type="button" :icon="'save'"
                               @click="editElement('Certificación Inicial')" label="Guardar"
                               color="secondary" flat/>
                      </q-stepper-navigation>
                    </q-step>
                    <q-step
                      :name="13"
                      title="4. Informe de la Evaluación y 5. Decisión sobre la Certificación"
                      icon="add_comment"
                      :done="donedecision"
                    >
                      <q-card-section class="no-padding">
                        <q-card-section>
                          <div class="row" >
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Certificado Otorgado</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-option-group
                                        v-model="form_detailsregister.certificado_otorgadoR"
                                        :options="options_certificado_otorgadoR"
                                        color="primary"
                                      />
                                      <div class="q-pa-md">
                                        <q-input v-if="form_detailsregister.certificado_otorgadoR !== 'opt0'" dense v-model="form_detailsregister.certificado_otorgadofechaR" label="Fecha">
                                          <template v-slot:append>
                                            <q-icon name="event" class="cursor-pointer">
                                              <q-popup-proxy @before-show="updateDate('certificado_otorgadofechaR')" transition-show="scale" transition-hide="scale">
                                                <q-date v-model="f_certificado_otorgadofechadateR">
                                                  <div class="row items-center justify-end q-gutter-sm">
                                                    <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                    <q-btn label="ok" color="primary" flat @click="saveDate('certificado_otorgadofechaR')"  v-close-popup/>
                                                  </div>
                                                </q-date>
                                              </q-popup-proxy>
                                            </q-icon>
                                          </template>
                                        </q-input>
                                        <ul v-if="form_detailsregister.certificado_otorgadoR !== 'opt0'" style="color: red;padding: 0px;font-size: revert;">
                                          {{error_certificacion_informeR}}
                                        </ul>
                                      </div>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                            <div class="col-5">
                              <div class="q-pa-md" >
                                <q-input
                                  filled
                                  clearable
                                  type="textarea"
                                  color="red-12"
                                  label="Alcance de la Certificación"
                                  v-model="form_detailsregister.alcance_certificacionR"
                                />
                              </div>
                            </div>
                          </div>
                        </q-card-section>
                        <q-card-section>
                          <div class="row" >
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Validez Hasta (3 años)</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.validez_hastaR" label="Fecha">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('validez_hastaR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_validez_hastadateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('validez_hastaR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                            <div class="col-5">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Certificado Denegado</q-item-section>
                                    <q-icon name="event" class="cursor-pointer">
                                    </q-icon>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.certificado_denegadoR" label="Fecha" >
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('certificado_denegadoR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_certificado_denegadodateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('certificado_denegadoR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                      <div v-if="form_detailsregister.certificado_denegadoR !== null" class="q-py-md">
                                        <q-input
                                          filled
                                          clearable
                                          type="textarea"
                                          color="red-12"
                                          label="Motivo"
                                          v-model="form_detailsregister.motivo_denegacionR"
                                        />
                                        <ul v-if="this.form_detailsregister.motivo_denegacionR === null || this.form_detailsregister.motivo_denegacionR === ''" style="color: red;padding: 0px;font-size: revert;">
                                          {{error_motivo_denegacionR}}
                                        </ul>
                                      </div>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                          </div>
                        </q-card-section>
                      </q-card-section>
                      <q-stepper-navigation>
                        <q-btn type="button" :icon="'save'"
                               @click="editElement('Certificación Inicial')" label="Guardar"
                               color="secondary" flat/>
                      </q-stepper-navigation>
                    </q-step>
                    <q-step
                      :name="14"
                      title="6. Seguimiento"
                      icon="add_comment"
                      :done="donefollowup"
                    >
                      <q-card-section class="no-padding">
                        <q-card-section>
                          <div class="row" >
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Auditorías de Seguimiento Anual</q-item-section>
                                    <q-btn
                                      type="reset"
                                      key="add"
                                      dense
                                      icon="add"
                                      @click.prevent="openInputAdd_audseganualR = true"
                                      class="q-mt-sm"
                                    >
                                      <q-tooltip>Nueva fecha</q-tooltip>
                                    </q-btn>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input v-if="openInputAdd_audseganualR" :dense="dense" v-model="form_detailsregister.auditorias_seg_anualR" label="Fecha">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('auditorias_seg_anualR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_auditorias_seg_anualdateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('auditorias_seg_anualR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                      <ul v-for="(index,row) in auditorias_seg_anualListR" class="q-mt-xs">
                                        <li class="text-subtitle1">{{index}}</li>
                                      </ul>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Auditoría de Seguimiento Extraordinario</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.auditorias_seg_extraordinarioR" label="Fecha Inicio">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('auditorias_seg_extraordinarioR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_auditorias_seg_extraordinariodateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('auditorias_seg_extraordinarioR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="q-pa-md">
                                <q-list class="shadow-2 rounded-borders" style="max-width: 100%; width: 100%;">
                                  <q-item>
                                    <q-item-section>Cancelación del Certificado</q-item-section>
                                  </q-item>
                                  <q-separator color="orange" inset />
                                  <q-item>
                                    <q-item-section>
                                      <q-input :dense="dense" v-model="form_detailsregister.cancelacion_certificadoR" label="Fecha">
                                        <template v-slot:append>
                                          <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy @before-show="updateDate('cancelacion_certificadoR')" transition-show="scale" transition-hide="scale">
                                              <q-date v-model="f_cancelacion_certificadodateR">
                                                <div class="row items-center justify-end q-gutter-sm">
                                                  <q-btn label="cancel" color="primary" flat v-close-popup/>
                                                  <q-btn label="ok" color="primary" flat @click="saveDate('cancelacion_certificadoR')"  v-close-popup/>
                                                </div>
                                              </q-date>
                                            </q-popup-proxy>
                                          </q-icon>
                                        </template>
                                      </q-input>
                                      <div v-if="form_detailsregister.cancelacion_certificadoR !== null" class="q-py-md">
                                        <q-input
                                          filled
                                          clearable
                                          type="textarea"
                                          color="red-12"
                                          label="Motivo"
                                          v-model="form_detailsregister.motivo_denegacion2R"
                                        />
                                        <ul v-if="this.form_detailsregister.motivo_denegacion2R === null || this.form_detailsregister.motivo_denegacionR === ''" style="color: red;padding: 0px;font-size: revert;">
                                          {{error_motivo_denegacion2R}}
                                        </ul>
                                      </div>
                                    </q-item-section>
                                  </q-item>
                                </q-list>
                              </div>
                            </div>
                          </div>
                        </q-card-section>
                      </q-card-section>
                      <q-stepper-navigation>
                        <q-btn type="button" :icon="'save'"
                               @click="editElement('Certificación Inicial')" label="Guardar"
                               color="secondary" flat/>
                      </q-stepper-navigation>
                    </q-step>
                  </q-stepper>
                </q-step>
              </q-stepper>
            </div>
          </template>
        </div>
      </q-card>
    </q-dialog>
    <q-dialog
      v-model="modalDialogDelete"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 500px; max-width: 70vw">
        <q-form
          ref="formRegister"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  Eliminar Registro
                </q-toolbar-title>
                <q-btn
                  round
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
              <div class="row">
                <div class="col" style="padding: 0px 20px 20px 0px">
                  <label name="desc1"  type="text"  dense>
                    Está seguro de eliminar el registro para el: <label class="text-subtitle2"> {{this.tiposg_desc}} </label> </label>
                </div>
              </div>
            </q-card-section>
            <q-card-actions align="right">
              <q-btn :icon="'delete'"
                     @click="deleteElement()" label="Eliminar"
                     color="primary" flat/>
            </q-card-actions>
          </q-card-section>
        </q-form>
      </q-card>
    </q-dialog>
  </div>
</template>
<script>

import {errorMessage, successMessage, warningMessage} from '../../utils/notificaciones'
import { mapActions } from "vuex";
import * as message from "../../utils/resources";
import {deleteRegister, getAllRegisters, getOperadoras, getTiposSistGestion} from "../../store/sistema_gestion/actions";
import {getInstalacionOsde} from "../../store/gestion_entidades/instalacion/actions";
import moment from "moment";

export default {
  name: 'managementsystemdemandstatus',
  data() {
    return {
      dense: false,
      scopes: sessionStorage.getItem('scopes'),
      data: [],
      datareg: [],
      filterInput: false,
      form_data: {
        instalacion_name:'',
        osde_name:'',
        id_installation:''
      },
      columns: [
        {
          name: 'columnid',
          label: 'id',
          align: 'center',
          field: row => row.id
        },
        {
          name: 'columnname',
          label: 'Tipo de Sistema de Gestión',
          align: 'left',
          field: row => row.tiposg_desc,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'columnestado',
          label: 'Estado',
          align: 'center',
          field: row => row.estado,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'columnactions',
          label: 'Acciones',
          align: 'center',
          field: 'action',
          headerClasses: 'text-uppercase'
        }

      ],
      visibleColumns: ['columnname','columnestado','columnactions'],
      loading: true,
      filter: '',
      modalDialogEdit: false,
      modalDialogAdd: false,
      modalDialogDelete:false,
      stepprocess: 1,
      donegeneral: false,
      donedesign: false,
      doneinitial: false,
      donerenewal: false,
      stepinitial: 1,
      donerequest: false,
      donepreparation: false,
      doneevaluation: false,
      donedecision: false,
      donefollowup: false,
      listsystemtype:[],
      listoperator:[],
      auditoria_FList:[],
      direccion_FList:[],
      auditorias_seg_anualList:[],
      auditorias_seg_anualListR:[],
       form_detailsregister: {
         tipoSistGest_id:'',
         operadora_id:'',
         cadena: 'Gaviota',
         certificado_otorgado:'opt0',
         motivo_denegacion:'',
         motivo_denegacion2:'',
         certificado_otorgadoR:'opt0',
         motivo_denegacionR:'',
         motivo_denegacion2R:'',
       },
      tiposg_desc:'',
      id_element:'',
      action:'',
      inclusion: 'No',
      inclusionR: 'No',

      diagnostico_FIdate:'',
      diagnostico_FCdate:'',
      formacion_FIdate:'',
      formacion_FCdate:'',
      disenno_FIdate:'',
      disenno_FCdate:'',
      implementacion_FIdate:'',
      implementacion_FCdate:'',
      auditoria_Fdate:'',
      direccion_Fdate:'',

      aval_entidaddate:'',
      conciliacion_demandadate:'',
      ratificacion_demandadate:'',
      firma_contratodate:'',
      fase1_auditoriaIdate:'',
      fase1_auditoriaCdate:'',
      fase2_auditoriaIdate:'',
      fase2_auditoriaCdate:'',
      auditoria_adicionalIdate:'',
      auditoria_adicionalCdate:'',
      certificado_otorgadofechadate:'',
      validez_hastadate:'',
      certificado_denegadodate:'',
      auditorias_seg_anualdate:'',
      auditorias_seg_extraordinariodate:'',
      cancelacion_certificadodate:'',

      aval_entidaddateR:'',
      conciliacion_demandadateR:'',
      ratificacion_demandadateR:'',
      firma_contratodateR:'',
      fase1_auditoriaIdateR:'',
      fase1_auditoriaCdateR:'',
      fase2_auditoriaIdateR:'',
      fase2_auditoriaCdateR:'',
      auditoria_adicionalIdateR:'',
      auditoria_adicionalCdateR:'',
      certificado_otorgadofechadateR:'',
      validez_hastadateR:'',
      certificado_denegadodateR:'',
      auditorias_seg_anualdateR:'',
      auditorias_seg_extraordinariodateR:'',
      cancelacion_certificadodateR:'',

      fi_diagnosticdate: '',
      fc_diagnosticdate: '',
      fi_formaciondate: '',
      fc_formaciondate: '',
      fi_disennodate: '',
      fc_disennodate: '',
      fi_implementaciondate: '',
      fc_implementaciondate: '',
      f_auditoriadate: '',
      f_auditorialist: '',
      f_direcciondate: '',
      f_direccionlist: '',
      f_auditorias_seg_anuallist:'',
      f_aval_entidaddate: '',
      f_conciliacion_demandadate: '',
      f_ratificacion_demandadate: '',
      f_firma_contratodate: '',
      f_fase1_auditoriaIdate: '',
      f_fase1_auditoriaCdate: '',
      f_fase2_auditoriaIdate: '',
      f_fase2_auditoriaCdate: '',
      f_auditoria_adicionalIdate: '',
      f_auditoria_adicionalCdate: '',
      f_certificado_otorgadofechadate: '',
      f_validez_hastadate: '',
      f_certificado_denegadodate: '',
      f_auditorias_seg_anualdate: '',
      f_auditorias_seg_extraordinariodate: '',
      f_cancelacion_certificadodate: '',

      f_auditorias_seg_anuallistR:'',
      f_aval_entidaddateR: '',
      f_conciliacion_demandadateR: '',
      f_ratificacion_demandadateR: '',
      f_firma_contratodateR: '',
      f_fase1_auditoriaIdateR: '',
      f_fase1_auditoriaCdateR: '',
      f_fase2_auditoriaIdateR: '',
      f_fase2_auditoriaCdateR: '',
      f_auditoria_adicionalIdateR: '',
      f_auditoria_adicionalCdateR: '',
      f_certificado_otorgadofechadateR: '',
      f_validez_hastadateR: '',
      f_certificado_denegadodateR: '',
      f_auditorias_seg_anualdateR: '',
      f_auditorias_seg_extraordinariodateR: '',
      f_cancelacion_certificadodateR: '',

      options_certificado_otorgado: [
        {
          label: 'Ninguno',
          value: 'opt0'
        },
        {
          label: 'No conformidades cerradas',
          value: 'opt1'
        },
        {
          label: 'Notas para auditoría de seguimiento',
          value: 'opt2'
        },
        {
          label: 'Con auditoría de seguimiento extraordinario',
          value: 'opt3'
        }
      ],
      options_certificado_otorgadoR: [
        {
          label: 'Ninguno',
          value: 'opt0'
        },
        {
          label: 'No conformidades cerradas',
          value: 'opt1'
        },
        {
          label: 'Notas para auditoría de seguimiento',
          value: 'opt2'
        },
        {
          label: 'Con auditoría de seguimiento extraordinario',
          value: 'opt3'
        }
      ],
      error_certificacion_informe:'* Debe especificar una fecha',
      error_motivo_denegacion:'* Debe escribir un motivo',
      error_motivo_denegacion2:'* Debe escribir un motivo',
      error_certificacion_informeR:'* Debe especificar una fecha',
      error_motivo_denegacionR:'* Debe escribir un motivo',
      error_motivo_denegacion2R:'* Debe escribir un motivo',
      error_requeridos:false,
      openInputAdd_audseganual:false,
      openInputAdd_audseganualR:false,
    }
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      {label: 'Dirección de Calidad'},
      {label: 'Sistema de Gestión de la Calidad'},
      {label: 'Captación de Registros'}])
    this.getAutenticated();
    this.loadDataAuthenticated();
    this.loadGetAllRegisters();
  },
  watch: {
    pagination: {
      handler() {
        this.loadGetAllRegisters({
          pagination: this.pagination,
          filter: this.filter
        })
      }
    },
  },
  methods: {
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    ...mapActions('managementsystem', ['getAllRegisters']),
    ...mapActions('managementsystem', ['getRegister']),
    ...mapActions('managementsystem', ['getTiposSistGestion']),
    ...mapActions('managementsystem', ['getOperadoras']),
    ...mapActions('setInstalacion', ['getInstalacionOsde']),
    ...mapActions('managementsystem', ['addRegister']),
    ...mapActions('managementsystem', ['editRegister']),
    ...mapActions('managementsystem', ['deleteRegister']),

    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    getAutenticated() {
      //this.userAuthenticated = sessionStorage.getItem('username')
      this.userAuthenticated = 1
    },
    async loadDataAuthenticated() {
      this.loading = true
      await this.getInstalacionOsde(this.userAuthenticated).then(result => {
        this.data = result.data;
        if (this.data.length > 0)
        {
          this.form_data.instalacion_name = this.data[0]['instalacion_name']
          this.form_data.osde_name = this.data[0]['osde_name']
          this.form_data.id_installation = this.data[0]['id']
        }
      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
      this.loading = false
    },
    async loadGetAllRegisters() {
      this.loading = true
      await this.getAllRegisters(this.userAuthenticated).then(result => {
        this.data = result.data;
        console.log(result.data)
      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
      this.loading = false
    },
    closeModal() {
      this.modalDialogDelete = false
      this.modalDialogEdit = false
      this.modalDialogAdd = false
    },
    openModalAdd() {
      this.resetFormDetailsRegister()
      this.loadDataRegister()
      this.modalDialogAdd = true
    },
    openModalEdit(data) {
      this.action = 'edit'
      this.id_element = data.id
      this.getTypeSystem();
      this.loadGetRegister(data.id)
      this.loadDataRegister()
      this.modalDialogEdit = true
    },
    openModalDelete(data) {
      this.tiposg_desc = data.tiposg_desc
      this.id_element = data.id
      this.modalDialogDelete = true
    },
    async loadGetRegister(id_register) {
      this.loading = true
      await this.getRegister(id_register).then(result => {
        if (result.data[0].diagnostico_FI !== null) {
          this.diagnostico_FIdate = result.data[0].diagnostico_FI
          result.data[0].diagnostico_FI = moment.utc(result.data[0].diagnostico_FI).format('DD/MM/YY')
        }
        if (result.data[0].diagnostico_FC !== null) {
          this.diagnostico_FCdate = result.data[0].diagnostico_FC
          result.data[0].diagnostico_FC = moment.utc(result.data[0].diagnostico_FC).format('DD/MM/YY')
        }

        if (result.data[0].formacion_FI !== null) {
          this.formacion_FIdate = result.data[0].formacion_FI
          result.data[0].formacion_FI = moment.utc(result.data[0].formacion_FI).format('DD/MM/YY')
        }
        if (result.data[0].formacion_FC !== null) {
          this.formacion_FCdate = result.data[0].formacion_FC
          result.data[0].formacion_FC = moment.utc(result.data[0].formacion_FC).format('DD/MM/YY')
        }

        if (result.data[0].disenno_FI !== null) {
          this.disenno_FIdate = result.data[0].disenno_FI
          result.data[0].disenno_FI = moment.utc(result.data[0].disenno_FI).format('DD/MM/YY')
        }
        if (result.data[0].disenno_FC !== null) {
          this.disenno_FCdate = result.data[0].disenno_FC
          result.data[0].disenno_FC = moment.utc(result.data[0].disenno_FC).format('DD/MM/YY')
        }

        if (result.data[0].implementacion_FI !== null) {
          this.implementacion_FIdate = result.data[0].implementacion_FI
          result.data[0].implementacion_FI = moment.utc(result.data[0].implementacion_FI).format('DD/MM/YY')
        }
        if (result.data[0].implementacion_FC !== null) {
          this.implementacion_FCdate = result.data[0].implementacion_FC
          result.data[0].implementacion_FC = moment.utc(result.data[0].implementacion_FC).format('DD/MM/YY')
        }

        if (result.data[0].auditoria_F !== null) {
          this.f_auditorialist = [];
          this.f_auditorialist = result.data[0].auditoria_F;
          this.auditoria_FList = [];
          for (let i = result.data[0].auditoria_F.length; i > 0; i--) {
            this.auditoria_FList.unshift(moment.utc(result.data[0].auditoria_F[i - 1]).format('DD/MM/YY'));
          }
          this.auditoria_Fdate = result.data[0].auditoria_F[0]
          result.data[0].auditoria_F = moment.utc(result.data[0].auditoria_F[0]).format('DD/MM/YY')
        }

        if (result.data[0].direccion_F !== null) {
          this.f_direccionlist = [];
          this.f_direccionlist = result.data[0].direccion_F;
          this.direccion_FList = [];
          for (let i = result.data[0].direccion_F.length; i > 0; i--) {
            this.direccion_FList.unshift(moment.utc(result.data[0].direccion_F[i - 1]).format('DD/MM/YY'));
          }
          this.direccion_Fdate = result.data[0].direccion_F[0]
          result.data[0].direccion_F = moment.utc(result.data[0].direccion_F[0]).format('DD/MM/YY')
        }

        if (result.data[0].aval_entidad !== null) {
          this.aval_entidaddate = result.data[0].aval_entidad
          result.data[0].aval_entidad = moment.utc(result.data[0].aval_entidad).format('DD/MM/YY')
        }
        this.inclusion = result.data[0].inclusion === true ? 'Si' : 'No'
        if (result.data[0].conciliacion_demanda !== null) {
          this.conciliacion_demandadate = result.data[0].conciliacion_demanda
          result.data[0].conciliacion_demanda = moment.utc(result.data[0].conciliacion_demanda).format('DD/MM/YY')
        }
        if (result.data[0].ratificacion_demanda !== null) {
          this.ratificacion_demandadate = result.data[0].ratificacion_demanda
          result.data[0].ratificacion_demanda = moment.utc(result.data[0].ratificacion_demanda).format('DD/MM/YY')
        }
        if (result.data[0].firma_contrato !== null) {
          this.firma_contratodate = result.data[0].firma_contrato
          result.data[0].firma_contrato = moment.utc(result.data[0].firma_contrato).format('DD/MM/YY')
        }
        if (result.data[0].fase1_auditoriaI !== null) {
          this.fase1_auditoriaIdate = result.data[0].fase1_auditoriaI
          result.data[0].fase1_auditoriaI = moment.utc(result.data[0].fase1_auditoriaI).format('DD/MM/YY')
        }
        if (result.data[0].fase1_auditoriaC !== null) {
          this.fase1_auditoriaCdate = result.data[0].fase1_auditoriaC
          result.data[0].fase1_auditoriaC = moment.utc(result.data[0].fase1_auditoriaC).format('DD/MM/YY')
        }
        if (result.data[0].fase2_auditoriaI !== null) {
          this.fase2_auditoriaIdate = result.data[0].fase2_auditoriaI
          result.data[0].fase2_auditoriaI = moment.utc(result.data[0].fase2_auditoriaI).format('DD/MM/YY')
        }
        if (result.data[0].fase2_auditoriaC !== null) {
          this.fase2_auditoriaCdate = result.data[0].fase2_auditoriaC
          result.data[0].fase2_auditoriaC = moment.utc(result.data[0].fase2_auditoriaC).format('DD/MM/YY')
        }
        if (result.data[0].auditoria_adicionalI !== null) {
          this.auditoria_adicionalIdate = result.data[0].auditoria_adicionalI
          result.data[0].auditoria_adicionalI = moment.utc(result.data[0].auditoria_adicionalI).format('DD/MM/YY')
        }
        if (result.data[0].auditoria_adicionalC !== null) {
          this.auditoria_adicionalCdate = result.data[0].auditoria_adicionalC
          result.data[0].auditoria_adicionalC = moment.utc(result.data[0].auditoria_adicionalC).format('DD/MM/YY')
        }
        if (result.data[0].certificado_otorgadofecha !== null) {
          this.certificado_otorgadofechadate = result.data[0].certificado_otorgadofecha
          result.data[0].certificado_otorgadofecha = moment.utc(result.data[0].certificado_otorgadofecha).format('DD/MM/YY')
        }
        if (result.data[0].certificado_otorgado !== 'opt0'){
          this.error_certificacion_informe = ''
        }
        if (result.data[0].validez_hasta !== null) {
          this.validez_hastadate = result.data[0].validez_hasta
          result.data[0].validez_hasta = moment.utc(result.data[0].validez_hasta).format('DD/MM/YY')
        }
        if (result.data[0].certificado_denegado !== null) {
          this.certificado_denegadodate = result.data[0].certificado_denegado
          result.data[0].certificado_denegado = moment.utc(result.data[0].certificado_denegado).format('DD/MM/YY')
        }
        if (result.data[0].auditorias_seg_anual !== null) {
           this.f_auditorias_seg_anuallist = [];
           this.f_auditorias_seg_anuallist = result.data[0].auditorias_seg_anual;
          this.auditorias_seg_anualList = [];
          for (let i = 0; i < result.data[0].auditorias_seg_anual.length; i++) {
            this.auditorias_seg_anualList.push(moment.utc(result.data[0].auditorias_seg_anual[i]).format('DD/MM/YY'));
          }
          result.data[0].auditorias_seg_anual = ''
        }
        if (result.data[0].auditorias_seg_extraordinario !== null) {
          this.auditorias_seg_extraordinariodate = result.data[0].auditorias_seg_extraordinario
          result.data[0].auditorias_seg_extraordinario = moment.utc(result.data[0].auditorias_seg_extraordinario).format('DD/MM/YY')
        }
        if (result.data[0].cancelacion_certificado !== null) {
          this.cancelacion_certificadodate = result.data[0].cancelacion_certificado
          result.data[0].cancelacion_certificado = moment.utc(result.data[0].cancelacion_certificado).format('DD/MM/YY')
        }




        if (result.data[0].aval_entidadR !== null) {
          this.aval_entidaddateR = result.data[0].aval_entidadR
          result.data[0].aval_entidadR = moment.utc(result.data[0].aval_entidadR).format('DD/MM/YY')
        }
        this.inclusionR = result.data[0].inclusionR === true ? 'Si' : 'No'
        if (result.data[0].conciliacion_demandaR !== null) {
          this.conciliacion_demandadateR = result.data[0].conciliacion_demandaR
          result.data[0].conciliacion_demandaR = moment.utc(result.data[0].conciliacion_demandaR).format('DD/MM/YY')
        }
        if (result.data[0].ratificacion_demandaR !== null) {
          this.ratificacion_demandadateR = result.data[0].ratificacion_demandaR
          result.data[0].ratificacion_demandaR = moment.utc(result.data[0].ratificacion_demandaR).format('DD/MM/YY')
        }
        if (result.data[0].firma_contratoR !== null) {
          this.firma_contratodateR = result.data[0].firma_contratoR
          result.data[0].firma_contratoR = moment.utc(result.data[0].firma_contratoR).format('DD/MM/YY')
        }
        if (result.data[0].fase1_auditoriaIR !== null) {
          this.fase1_auditoriaIdateR = result.data[0].fase1_auditoriaIR
          result.data[0].fase1_auditoriaIR = moment.utc(result.data[0].fase1_auditoriaIR).format('DD/MM/YY')
        }
        if (result.data[0].fase1_auditoriaCR !== null) {
          this.fase1_auditoriaCdateR = result.data[0].fase1_auditoriaCR
          result.data[0].fase1_auditoriaCR = moment.utc(result.data[0].fase1_auditoriaCR).format('DD/MM/YY')
        }
        if (result.data[0].fase2_auditoriaIR !== null) {
          this.fase2_auditoriaIdateR = result.data[0].fase2_auditoriaIR
          result.data[0].fase2_auditoriaIR = moment.utc(result.data[0].fase2_auditoriaIR).format('DD/MM/YY')
        }
        if (result.data[0].fase2_auditoriaCR !== null) {
          this.fase2_auditoriaCdateR = result.data[0].fase2_auditoriaCR
          result.data[0].fase2_auditoriaCR = moment.utc(result.data[0].fase2_auditoriaCR).format('DD/MM/YY')
        }
        if (result.data[0].auditoria_adicionalIR !== null) {
          this.auditoria_adicionalIdateR = result.data[0].auditoria_adicionalIR
          result.data[0].auditoria_adicionalIR = moment.utc(result.data[0].auditoria_adicionalIR).format('DD/MM/YY')
        }
        if (result.data[0].auditoria_adicionalCR !== null) {
          this.auditoria_adicionalCdateR = result.data[0].auditoria_adicionalCR
          result.data[0].auditoria_adicionalCR = moment.utc(result.data[0].auditoria_adicionalCR).format('DD/MM/YY')
        }
        if (result.data[0].certificado_otorgadofechaR !== null) {
          this.certificado_otorgadofechadateR = result.data[0].certificado_otorgadofechaR
          result.data[0].certificado_otorgadofechaR = moment.utc(result.data[0].certificado_otorgadofechaR).format('DD/MM/YY')
        }
        if (result.data[0].certificado_otorgadoR !== 'opt0'){
          this.error_certificacion_informeR = ''
        }
        if (result.data[0].validez_hastaR !== null) {
          this.validez_hastadateR = result.data[0].validez_hastaR
          result.data[0].validez_hastaR = moment.utc(result.data[0].validez_hastaR).format('DD/MM/YY')
        }
        if (result.data[0].certificado_denegadoR !== null) {
          this.certificado_denegadodateR = result.data[0].certificado_denegadoR
          result.data[0].certificado_denegadoR = moment.utc(result.data[0].certificado_denegadoR).format('DD/MM/YY')
        }
        if (result.data[0].auditorias_seg_anualR !== null) {
          this.f_auditorias_seg_anuallistR = [];
          this.f_auditorias_seg_anuallistR = result.data[0].auditorias_seg_anualR;
          this.auditorias_seg_anualListR = [];
          for (let i = 0; i < result.data[0].auditorias_seg_anualR.length; i++) {
            this.auditorias_seg_anualListR.push(moment.utc(result.data[0].auditorias_seg_anualR[i]).format('DD/MM/YY'));
          }
          result.data[0].auditorias_seg_anualR = ''
        }
        if (result.data[0].auditorias_seg_extraordinarioR !== null) {
          this.auditorias_seg_extraordinariodateR = result.data[0].auditorias_seg_extraordinarioR
          result.data[0].auditorias_seg_extraordinarioR = moment.utc(result.data[0].auditorias_seg_extraordinarioR).format('DD/MM/YY')
        }
        if (result.data[0].cancelacion_certificadoR !== null) {
          this.cancelacion_certificadodateR = result.data[0].cancelacion_certificadoR
          result.data[0].cancelacion_certificadoR = moment.utc(result.data[0].cancelacion_certificadoR).format('DD/MM/YY')
        }



        this.form_detailsregister = result.data[0]
        console.log(this.form_detailsregister)

      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
      this.loading = false
    },
    loadDataRegister() {
      this.loading = true
      this.getTypeSystem();
      this.getOperator();
      this.loading = false
    },
    async getTypeSystem() {
      await this.getTiposSistGestion().then(result => {
        const list = result.data;
        if (this.action == 'edit') {
          this.listsystemtype = list;
        } else {
          if (this.data.length > 0) {
            const ids = this.data.reduce((obj, item) => (obj[item.tiposg_id] = true, obj), {});
            this.listsystemtype = list.filter(item => !ids[item.id]);
          } else
            this.listsystemtype = list;
        }
      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
    },
    async getOperator() {
      await this.getOperadoras().then(result => {
        this.listoperator = result.data;
      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
    },
    async addElement(data) {
      this.form_detailsregister.instalacion_id = this.form_data.id_installation
      //this.form_detailsregister.estado = 'Datos generales'
      if (this.form_detailsregister.operadora_id !== '' && this.form_detailsregister.tipoSistGest_id !== '') {
        await this.addRegister(this.form_detailsregister).then(result => {
          if (result.success === true) {
            successMessage('Dato insertado satisfactoriamente')
            this.closeModal()
            this.loadGetAllRegisters()
          }
        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
      }else{
        warningMessage('Debe llenar todos los campos')
      }
    },
    async cargarEstado() {
      if(this.form_detailsregister.diagnostico_FI !== ''){
        this.form_detailsregister.estado = 'Diagnóstico'
      }
      if(this.form_detailsregister.disenno_FI !== ''){
        this.form_detailsregister.estado = 'Diseño'
      }
      if(this.form_detailsregister.implementacion_FI !== ''){
        this.form_detailsregister.estado = 'Implementación'
      }
      if(this.form_detailsregister.auditoria_F !== null){
        this.form_detailsregister.estado = 'Revisión y Auditoría'
      }
      if(this.form_detailsregister.certificado_otorgado !== 'opt0'){
        this.form_detailsregister.estado = 'Certificado'
      }
      if(this.form_detailsregister.auditoria_adicionalIR !== ''){
        this.form_detailsregister.estado = 'Renovado'
      }
      if(this.form_detailsregister.cancelacion_certificado !== '' || this.form_detailsregister.cancelacion_certificadoR !== ''){
        this.form_detailsregister.estado = 'Cancelado'
      }
    },
    async editElement() {
      //this.form_detailsregister.etapa = etapa
      this.form_detailsregister.diagnostico_FI = this.diagnostico_FIdate
      this.form_detailsregister.diagnostico_FC = this.diagnostico_FCdate
      this.form_detailsregister.formacion_FI = this.formacion_FIdate
      this.form_detailsregister.formacion_FC = this.formacion_FCdate
      this.form_detailsregister.disenno_FI = this.disenno_FIdate
      this.form_detailsregister.disenno_FC = this.disenno_FCdate
      this.form_detailsregister.implementacion_FI = this.implementacion_FIdate
      this.form_detailsregister.implementacion_FC = this.implementacion_FCdate
      if(this.auditoria_FList.length !== 0)
      {
        if(!this.f_auditorialist.includes( this.auditoria_Fdate)) {
          this.f_auditorialist.unshift(moment.utc(this.auditoria_Fdate).format('YYYY-MM-DD'))
        }
        this.form_detailsregister.auditoria_F = this.f_auditorialist
      }
      else
      {
        if (this.form_detailsregister.auditoria_F !== null) {
          let json = {
            "0": moment.utc(this.auditoria_Fdate).format('YYYY-MM-DD')
          };
          this.form_detailsregister.auditoria_F = json;
        }
      }
      if(this.direccion_FList.length !== 0)
      {
        if(!this.f_direccionlist.includes( this.direccion_Fdate)) {
          this.f_direccionlist.unshift(moment.utc(this.direccion_Fdate).format('YYYY-MM-DD'))
        }
        this.form_detailsregister.direccion_F = this.f_direccionlist
      }
      else
      {
        if (this.form_detailsregister.direccion_F !== null) {
          let json = {
            "0": moment.utc(this.direccion_Fdate).format('YYYY-MM-DD')
          };
          this.form_detailsregister.direccion_F = json;
        }
      }
      this.form_detailsregister.aval_entidad = this.aval_entidaddate
      this.form_detailsregister.inclusion = this.inclusion === 'Si' ? true : false
      this.form_detailsregister.conciliacion_demanda = this.conciliacion_demandadate
      this.form_detailsregister.ratificacion_demanda = this.ratificacion_demandadate
      this.form_detailsregister.firma_contrato = this.firma_contratodate
      this.form_detailsregister.fase1_auditoriaI = this.fase1_auditoriaIdate
      this.form_detailsregister.fase1_auditoriaC = this.fase1_auditoriaCdate
      this.form_detailsregister.fase2_auditoriaI = this.fase2_auditoriaIdate
      this.form_detailsregister.fase2_auditoriaC = this.fase2_auditoriaCdate
      this.form_detailsregister.auditoria_adicionalI = this.auditoria_adicionalIdate
      this.form_detailsregister.auditoria_adicionalC = this.auditoria_adicionalCdate
      if (this.form_detailsregister.certificado_otorgado !== 'opt0' && this.certificado_otorgadofechadate === ''){
        this.error_requeridos = true
      }
      this.form_detailsregister.certificado_otorgadofecha = this.certificado_otorgadofechadate
      if (this.form_detailsregister.certificado_otorgado === 'opt0'){
        this.certificado_otorgadofechadate = ''
        this.form_detailsregister.certificado_otorgadofecha = null
        this.error_requeridos = false
      }
      this.form_detailsregister.validez_hasta = this.validez_hastadate
      if ((this.form_detailsregister.motivo_denegacion === null || this.form_detailsregister.motivo_denegacion === '') && this.certificado_denegadodate !== ''){
        this.error_requeridos = true
      }else{
        this.form_detailsregister.certificado_denegado = this.certificado_denegadodate
      }
      if (this.auditorias_seg_anualdate !== ''){
        if (this.auditorias_seg_anualList.length > 0){
          this.f_auditorias_seg_anuallist.push(moment.utc(this.auditorias_seg_anualdate).format('YYYY-MM-DD'))
          this.form_detailsregister.auditorias_seg_anual = this.f_auditorias_seg_anuallist;
        }else{
          let json = {
            "0": moment.utc(this.auditorias_seg_anualdate).format('YYYY-MM-DD')
          };
          this.form_detailsregister.auditorias_seg_anual = json;
        }
      }
      this.form_detailsregister.auditorias_seg_extraordinario = this.auditorias_seg_extraordinariodate
      if ((this.form_detailsregister.motivo_denegacion2 === null || this.form_detailsregister.motivo_denegacion2 === '') && this.cancelacion_certificadodate !== ''){
        this.error_requeridos = true
      }else{
        this.form_detailsregister.cancelacion_certificado = this.cancelacion_certificadodate
      }


      this.form_detailsregister.aval_entidadR = this.aval_entidaddateR
      this.form_detailsregister.inclusionR = this.inclusionR === 'Si' ? true : false
      this.form_detailsregister.conciliacion_demandaR = this.conciliacion_demandadateR
      this.form_detailsregister.ratificacion_demandaR = this.ratificacion_demandadateR
      this.form_detailsregister.firma_contratoR = this.firma_contratodateR
      this.form_detailsregister.fase1_auditoriaIR = this.fase1_auditoriaIdateR
      this.form_detailsregister.fase1_auditoriaCR = this.fase1_auditoriaCdateR
      this.form_detailsregister.fase2_auditoriaIR = this.fase2_auditoriaIdateR
      this.form_detailsregister.fase2_auditoriaCR = this.fase2_auditoriaCdateR
      this.form_detailsregister.auditoria_adicionalIR = this.auditoria_adicionalIdateR
      this.form_detailsregister.auditoria_adicionalCR = this.auditoria_adicionalCdateR
      if (this.form_detailsregister.certificado_otorgadoR !== 'opt0' && this.certificado_otorgadofechadateR === ''){
        this.error_requeridos = true
      }
      this.form_detailsregister.certificado_otorgadofechaR = this.certificado_otorgadofechadateR
      if (this.form_detailsregister.certificado_otorgadoR === 'opt0'){
        this.certificado_otorgadofechadateR = ''
        this.form_detailsregister.certificado_otorgadofechaR = null
        this.error_requeridos = false
      }
      this.form_detailsregister.validez_hastaR = this.validez_hastadateR
      if ((this.form_detailsregister.motivo_denegacionR === null || this.form_detailsregister.motivo_denegacionR === '') && this.certificado_denegadodateR !== ''){
        this.error_requeridos = true
      }else{
        this.form_detailsregister.certificado_denegadoR = this.certificado_denegadodateR
      }
      if (this.auditorias_seg_anualdateR !== ''){
        if (this.auditorias_seg_anualListR.length > 0){
          this.f_auditorias_seg_anuallistR.push(moment.utc(this.auditorias_seg_anualdateR).format('YYYY-MM-DD'))
          this.form_detailsregister.auditorias_seg_anualR = this.f_auditorias_seg_anuallistR;
        }else{
          let json = {
            "0": moment.utc(this.auditorias_seg_anualdateR).format('YYYY-MM-DD')
          };
          this.form_detailsregister.auditorias_seg_anualR = json;
        }
      }
      this.form_detailsregister.auditorias_seg_extraordinarioR = this.auditorias_seg_extraordinariodateR
      if ((this.form_detailsregister.motivo_denegacion2R === null || this.form_detailsregister.motivo_denegacion2R === '') && this.cancelacion_certificadodateR !== ''){
        this.error_requeridos = true
      }else{
        this.form_detailsregister.cancelacion_certificadoR = this.cancelacion_certificadodateR
      }

      let est = this.cargarEstado()


      if (!this.error_requeridos) {
        const datas = {
          id: this.id_element,
          form_detailsregister: this.form_detailsregister
        }
        this.form_detailsregister=[]
        this.form_detailsregister.certificado_otorgado = 'opt0'
        this.openInputAdd_audseganual = false
        this.form_detailsregister.certificado_otorgadoR = 'opt0'
        this.openInputAdd_audseganualR = false
        await this.editRegister(datas).then(result => {
          if (result.success === true) {
            this.error_requeridos = false
            this.loadGetAllRegisters()
            this.loadGetRegister(this.id_element)
            successMessage('Dato editado satisfactoriamente')
          }
        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
      }else{
        warningMessage('Debe llenar los campos requeridos')
      }
    },
    async deleteElement() {
      await this.deleteRegister(this.id_element).then(result => {
        if (result.success === true) {
          this.loadGetAllRegisters()
          successMessage('Dato eliminado satisfactoriamente')
          this.closeModal()
        }
      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
    },
    resetFormDetailsRegister(){
      this.form_detailsregister.tipoSistGest_id='',
      this.form_detailsregister.operadora_id=''
    },
    resetFormAdd(){
      this.form_detailsregister.tipoSistGest_id='',
        this.form_detailsregister.operadora_id=''
    },
    updateDate (component) {
      switch (component) {
        case 'diagnosticI':
          this.fi_diagnosticdate = this.diagnostico_FIdate
          break
        case 'diagnosticC':
          this.fc_diagnosticdate = this.diagnostico_FCdate
          break
        case 'formacionI':
          this.fi_formaciondate = this.formacion_FIdate
          break
        case 'formacionC':
          this.fc_formaciondate = this.formacion_FCdate
          break
        case 'disennoI':
          this.fi_disennodate = this.disenno_FIdate
          break
        case 'disennoC':
          this.fc_disennodate = this.disenno_FCdate
          break
        case 'implementacionI':
          this.fi_implementaciondate = this.implementacion_FIdate
          break
        case 'implementacionC':
          this.fc_implementaciondate = this.implementacion_FCdate
          break
        case 'auditoriaF':
          this.f_auditoriadate = this.auditoria_Fdate
          break
        case 'direccionF':
          this.f_direcciondate = this.direccion_Fdate
          break
        case 'aval_entidad':
          this.f_aval_entidaddate = this.aval_entidaddate
          break
        case 'conciliacion_demanda':
          this.f_conciliacion_demandadate = this.conciliacion_demandadate
          break
        case 'ratificacion_demanda':
          this.f_ratificacion_demandadate = this.ratificacion_demandadate
          break
        case 'firma_contrato':
          this.f_firma_contratodate = this.firma_contratodate
          break
        case 'fase1_auditoriaI':
          this.f_fase1_auditoriaIdate = this.fase1_auditoriaIdate
          break
        case 'fase1_auditoriaC':
          this.f_fase1_auditoriaCdate = this.fase1_auditoriaCdate
          break
        case 'fase2_auditoriaI':
          this.f_fase2_auditoriaIdate = this.fase2_auditoriaIdate
          break
        case 'fase2_auditoriaC':
          this.f_fase2_auditoriaCdate = this.fase2_auditoriaCdate
          break
        case 'auditoria_adicionalI':
          this.f_auditoria_adicionalIdate = this.auditoria_adicionalIdate
          break
        case 'auditoria_adicionalC':
          this.f_auditoria_adicionalCdate = this.auditoria_adicionalCdate
          break
        case 'certificado_otorgadofecha':
          this.f_certificado_otorgadofechadate = this.certificado_otorgadofechadate
          break
        case 'validez_hasta':
          this.f_validez_hastadate = this.validez_hastadate
          break
        case 'certificado_denegado':
          this.f_certificado_denegadodate = this.certificado_denegado
          break
        case 'auditorias_seg_anual':
          this.f_auditorias_seg_anualdate = this.auditorias_seg_anualdate
          break
        case 'auditorias_seg_extraordinario':
          this.f_auditorias_seg_extraordinariodate = this.auditorias_seg_extraordinariodate
          break
        case 'cancelacion_certificado':
          this.f_cancelacion_certificadodate = this.cancelacion_certificadodate
          break
        case 'aval_entidadR':
          this.f_aval_entidaddateR = this.aval_entidaddateR
          break
        case 'conciliacion_demandaR':
          this.f_conciliacion_demandadateR = this.conciliacion_demandadateR
          break
        case 'ratificacion_demandaR':
          this.f_ratificacion_demandadateR = this.ratificacion_demandadateR
          break
        case 'firma_contratoR':
          this.f_firma_contratodateR = this.firma_contratodateR
          break
        case 'fase1_auditoriaIR':
          this.f_fase1_auditoriaIdateR = this.fase1_auditoriaIdateR
          break
        case 'fase1_auditoriaCR':
          this.f_fase1_auditoriaCdateR = this.fase1_auditoriaCdateR
          break
        case 'fase2_auditoriaIR':
          this.f_fase2_auditoriaIdateR = this.fase2_auditoriaIdateR
          break
        case 'fase2_auditoriaCR':
          this.f_fase2_auditoriaCdateR = this.fase2_auditoriaCdateR
          break
        case 'auditoria_adicionalIR':
          this.f_auditoria_adicionalIdateR = this.auditoria_adicionalIdateR
          break
        case 'auditoria_adicionalCR':
          this.f_auditoria_adicionalCdateR = this.auditoria_adicionalCdateR
          break
        case 'certificado_otorgadofechaR':
          this.f_certificado_otorgadofechadateR = this.certificado_otorgadofechadateR
          break
        case 'validez_hastaR':
          this.f_validez_hastadateR = this.validez_hastadateR
          break
        case 'certificado_denegadoR':
          this.f_certificado_denegadodateR = this.certificado_denegadoR
          break
        case 'auditorias_seg_anualR':
          this.f_auditorias_seg_anualdateR = this.auditorias_seg_anualdateR
          break
        case 'auditorias_seg_extraordinarioR':
          this.f_auditorias_seg_extraordinariodateR = this.auditorias_seg_extraordinariodateR
          break
        case 'cancelacion_certificadoR':
          this.f_cancelacion_certificadodateR = this.cancelacion_certificadodateR
          break
      }
    },
    saveDate (component) {
      switch (component) {
        case 'diagnosticI':
          this.diagnostico_FIdate = this.fi_diagnosticdate
          this.form_detailsregister.diagnostico_FI =  moment.utc(this.fi_diagnosticdate).format('DD/MM/YY')
          break
        case 'diagnosticC':
          this.diagnostico_FCdate = this.fc_diagnosticdate
          this.form_detailsregister.diagnostico_FC =  moment.utc(this.fc_diagnosticdate).format('DD/MM/YY')
          break
        case 'formacionI':
          this.formacion_FIdate = this.fi_formaciondate
          this.form_detailsregister.formacion_FI =  moment.utc(this.fi_formaciondate).format('DD/MM/YY')
          break
        case 'formacionC':
          this.formacion_FCdate = this.fc_formaciondate
          this.form_detailsregister.formacion_FC =  moment.utc(this.fc_formaciondate).format('DD/MM/YY')
          break
        case 'disennoI':
          this.disenno_FIdate = this.fi_disennodate
          this.form_detailsregister.disenno_FI =  moment.utc(this.fi_disennodate).format('DD/MM/YY')
          break
        case 'disennoC':
          this.disenno_FCdate = this.fc_disennodate
          this.form_detailsregister.disenno_FC =  moment.utc(this.fc_disennodate).format('DD/MM/YY')
          break
        case 'implementacionI':
          this.implementacion_FIdate = this.fi_implementaciondate
          this.form_detailsregister.implementacion_FI =  moment.utc(this.fi_implementaciondate).format('DD/MM/YY')
          break
        case 'implementacionC':
          this.implementacion_FCdate = this.fc_implementaciondate
          this.form_detailsregister.implementacion_FC =  moment.utc(this.fc_implementaciondate).format('DD/MM/YY')
          break
        case 'auditoriaF':
          this.auditoria_Fdate = this.f_auditoriadate
          this.form_detailsregister.auditoria_F =  moment.utc(this.f_auditoriadate).format('DD/MM/YY')
          break
        case 'direccionF':
          this.direccion_Fdate = this.f_direcciondate
          this.form_detailsregister.direccion_F =  moment.utc(this.f_direcciondate).format('DD/MM/YY')
          break
        case 'aval_entidad':
          this.aval_entidaddate = this.f_aval_entidaddate
          this.form_detailsregister.aval_entidad =  moment.utc(this.f_aval_entidaddate).format('DD/MM/YY')
          break
        case 'conciliacion_demanda':
          this.conciliacion_demandadate = this.f_conciliacion_demandadate
          this.form_detailsregister.conciliacion_demanda =  moment.utc(this.f_conciliacion_demandadate).format('DD/MM/YY')
          break
        case 'ratificacion_demanda':
          this.ratificacion_demandadate = this.f_ratificacion_demandadate
          this.form_detailsregister.ratificacion_demanda =  moment.utc(this.f_ratificacion_demandadate).format('DD/MM/YY')
          break
        case 'firma_contrato':
          this.firma_contratodate = this.f_firma_contratodate
          this.form_detailsregister.firma_contrato =  moment.utc(this.f_firma_contratodate).format('DD/MM/YY')
          break
        case 'fase1_auditoriaI':
          this.fase1_auditoriaIdate = this.f_fase1_auditoriaIdate
          this.form_detailsregister.fase1_auditoriaI =  moment.utc(this.f_fase1_auditoriaIdate).format('DD/MM/YY')
          break
        case 'fase1_auditoriaC':
          this.fase1_auditoriaCdate = this.f_fase1_auditoriaCdate
          this.form_detailsregister.fase1_auditoriaC =  moment.utc(this.f_fase1_auditoriaCdate).format('DD/MM/YY')
          break
        case 'fase2_auditoriaI':
          this.fase2_auditoriaIdate = this.f_fase2_auditoriaIdate
          this.form_detailsregister.fase2_auditoriaI =  moment.utc(this.f_fase2_auditoriaIdate).format('DD/MM/YY')
          break
        case 'fase2_auditoriaC':
          this.fase2_auditoriaCdate = this.f_fase2_auditoriaCdate
          this.form_detailsregister.fase2_auditoriaC =  moment.utc(this.f_fase2_auditoriaCdate).format('DD/MM/YY')
          break
        case 'auditoria_adicionalI':
          this.auditoria_adicionalIdate = this.f_auditoria_adicionalIdate
          this.form_detailsregister.auditoria_adicionalI =  moment.utc(this.f_auditoria_adicionalIdate).format('DD/MM/YY')
          break
        case 'auditoria_adicionalC':
          this.auditoria_adicionalCdate = this.f_auditoria_adicionalCdate
          this.form_detailsregister.auditoria_adicionalC =  moment.utc(this.f_auditoria_adicionalCdate).format('DD/MM/YY')
          break
        case 'certificado_otorgadofecha':
          this.certificado_otorgadofechadate = this.f_certificado_otorgadofechadate
          this.form_detailsregister.certificado_otorgadofecha =  moment.utc(this.f_certificado_otorgadofechadate).format('DD/MM/YY')
          this.error_certificacion_informe = ''
          break
        case 'validez_hasta':
          this.validez_hastadate = this.f_validez_hastadate
          this.form_detailsregister.validez_hasta =  moment.utc(this.f_validez_hastadate).format('DD/MM/YY')
          break
        case 'certificado_denegado':
          this.certificado_denegadodate = this.f_certificado_denegadodate
          this.form_detailsregister.certificado_denegado =  moment.utc(this.f_certificado_denegadodate).format('DD/MM/YY')
          break
        case 'auditorias_seg_anual':
          this.auditorias_seg_anualdate = this.f_auditorias_seg_anualdate
          this.form_detailsregister.auditorias_seg_anual =  moment.utc(this.f_auditorias_seg_anualdate).format('DD/MM/YY')
          break
        case 'auditorias_seg_extraordinario':
          this.auditorias_seg_extraordinariodate = this.f_auditorias_seg_extraordinariodate
          this.form_detailsregister.auditorias_seg_extraordinario =  moment.utc(this.f_auditorias_seg_extraordinariodate).format('DD/MM/YY')
          break
        case 'cancelacion_certificado':
          this.cancelacion_certificadodate = this.f_cancelacion_certificadodate
          this.form_detailsregister.cancelacion_certificado =  moment.utc(this.f_cancelacion_certificadodate).format('DD/MM/YY')
          break
        case 'aval_entidadR':
          this.aval_entidaddateR = this.f_aval_entidaddateR
          this.form_detailsregister.aval_entidadR =  moment.utc(this.f_aval_entidaddateR).format('DD/MM/YY')
          break
        case 'conciliacion_demandaR':
          this.conciliacion_demandadateR = this.f_conciliacion_demandadateR
          this.form_detailsregister.conciliacion_demandaR =  moment.utc(this.f_conciliacion_demandadateR).format('DD/MM/YY')
          break
        case 'ratificacion_demandaR':
          this.ratificacion_demandadateR = this.f_ratificacion_demandadateR
          this.form_detailsregister.ratificacion_demandaR =  moment.utc(this.f_ratificacion_demandadateR).format('DD/MM/YY')
          break
        case 'firma_contratoR':
          this.firma_contratodateR = this.f_firma_contratodateR
          this.form_detailsregister.firma_contratoR =  moment.utc(this.f_firma_contratodateR).format('DD/MM/YY')
          break
        case 'fase1_auditoriaIR':
          this.fase1_auditoriaIdateR = this.f_fase1_auditoriaIdateR
          this.form_detailsregister.fase1_auditoriaIR =  moment.utc(this.f_fase1_auditoriaIdateR).format('DD/MM/YY')
          break
        case 'fase1_auditoriaCR':
          this.fase1_auditoriaCdateR = this.f_fase1_auditoriaCdateR
          this.form_detailsregister.fase1_auditoriaCR =  moment.utc(this.f_fase1_auditoriaCdateR).format('DD/MM/YY')
          break
        case 'fase2_auditoriaIR':
          this.fase2_auditoriaIdateR = this.f_fase2_auditoriaIdateR
          this.form_detailsregister.fase2_auditoriaIR =  moment.utc(this.f_fase2_auditoriaIdateR).format('DD/MM/YY')
          break
        case 'fase2_auditoriaCR':
          this.fase2_auditoriaCdateR = this.f_fase2_auditoriaCdateR
          this.form_detailsregister.fase2_auditoriaCR =  moment.utc(this.f_fase2_auditoriaCdateR).format('DD/MM/YY')
          break
        case 'auditoria_adicionalIR':
          this.auditoria_adicionalIdateR = this.f_auditoria_adicionalIdateR
          this.form_detailsregister.auditoria_adicionalIR =  moment.utc(this.f_auditoria_adicionalIdateR).format('DD/MM/YY')
          break
        case 'auditoria_adicionalCR':
          this.auditoria_adicionalCdateR = this.f_auditoria_adicionalCdateR
          this.form_detailsregister.auditoria_adicionalCR =  moment.utc(this.f_auditoria_adicionalCdateR).format('DD/MM/YY')
          break
        case 'certificado_otorgadofechaR':
          this.certificado_otorgadofechadateR = this.f_certificado_otorgadofechadateR
          this.form_detailsregister.certificado_otorgadofechaR =  moment.utc(this.f_certificado_otorgadofechadateR).format('DD/MM/YY')
          this.error_certificacion_informeR = ''
          break
        case 'validez_hastaR':
          this.validez_hastadateR = this.f_validez_hastadateR
          this.form_detailsregister.validez_hastaR =  moment.utc(this.f_validez_hastadateR).format('DD/MM/YY')
          break
        case 'certificado_denegadoR':
          this.certificado_denegadodateR = this.f_certificado_denegadodateR
          this.form_detailsregister.certificado_denegadoR =  moment.utc(this.f_certificado_denegadodateR).format('DD/MM/YY')
          break
        case 'auditorias_seg_anualR':
          this.auditorias_seg_anualdateR = this.f_auditorias_seg_anualdateR
          this.form_detailsregister.auditorias_seg_anualR =  moment.utc(this.f_auditorias_seg_anualdateR).format('DD/MM/YY')
          break
        case 'auditorias_seg_extraordinarioR':
          this.auditorias_seg_extraordinariodateR = this.f_auditorias_seg_extraordinariodateR
          this.form_detailsregister.auditorias_seg_extraordinarioR =  moment.utc(this.f_auditorias_seg_extraordinariodateR).format('DD/MM/YY')
          break
        case 'cancelacion_certificadoR':
          this.cancelacion_certificadodateR = this.f_cancelacion_certificadodateR
          this.form_detailsregister.cancelacion_certificadoR =  moment.utc(this.f_cancelacion_certificadodateR).format('DD/MM/YY')
          break
      }
    }
  }
}

</script>

<style scoped>

</style>
