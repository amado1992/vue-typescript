<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="text-subtitle1 text-weight-bold text-uppercase">
            gestionar turismo más higiénico y seguro
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
            :disable="data.length != 0 ? false : true"
            :color="filterInput ? 'blue-grey-3' : 'primary'"
            icon="search"
            @click.prevent="filterStatus()"
            class="q-mr-sm q-ml-sm q-mt-sm"
          >
            <q-tooltip>Buscar</q-tooltip>
          </q-btn>
          <q-btn
            v-if="scopes.includes('gestionar_ths')"
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
        </div>
      </q-card-section>
      <!-- tabla-->
      <q-card-section>
        <q-table
        :grid="xssmallScreen || smallScreen"
          flat
          :data="data"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          @row-click="onRowClick"
          size="xs"
          dense
        >
          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <div class="q-gutter-xs">
                <q-btn
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="
                    openModalUpdate(true, 'actualizar', props.row)
                  "
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRow(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>

                <!--Menu ver mas proceso principal-->
                <q-btn size="sm" dense icon="more_vert" color="grey" flat>
                  <q-tooltip>Ver más</q-tooltip>
                  <q-menu>
                    <q-list dense style="min-width: 100px">
                      <q-item clickable>
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="folder" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Expediente</q-item-section
                        >
                        <q-item-section side>
                          <q-icon :size="'xs'" name="keyboard_arrow_right" />
                        </q-item-section>

                        <q-menu
                          auto-close
                          transition-show="jump-down"
                          transition-hide="jump-up"
                        >
                          <q-list style="min-width: 100px">
                            <q-separator />
                            <q-item
                              clickable
                              v-ripple
                              @click="
                                openModalExpediente(true, 'addExp', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon :size="'xs'" color="green" name="add" />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px"
                                >Adicionar</q-item-section
                              >
                            </q-item>
                            <!-- <q-separator />
                            <q-item clickable v-ripple>
                              <q-item-section avatar>
                                <q-icon
                                  :size="'xs'"
                                  color="blue"
                                  name="create"
                                />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px"
                                >Editar</q-item-section
                              >
                            </q-item>-->

                            <q-separator />
                            <q-item
                              clickable
                              v-ripple
                              @click="openExp(props.row)"
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
                                style="margin-left: -25px"
                                >Listar</q-item-section
                              >
                            </q-item>
                          </q-list>
                        </q-menu>
                      </q-item>
                      <q-separator />

                      <!--Seguimiento-->
                      <q-item clickable>
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-arrow-up-circle"
                          />
                        </q-item-section>
                        <q-item-section
                          class="q-mr-md text-body2"
                          style="margin-left: -25px"
                          >Seguimiento</q-item-section
                        >
                        <q-item-section side>
                          <q-icon :size="'xs'" name="keyboard_arrow_right" />
                        </q-item-section>

                        <q-menu
                          auto-close
                          transition-show="jump-down"
                          transition-hide="jump-up"
                        >
                          <q-list style="min-width: 100px">
                            <q-item
                              clickable
                              v-ripple
                              @click="
                                openModalSeguimiento(true, 'addNext', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon :size="'xs'" color="green" name="add" />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px"
                                >Adicionar</q-item-section
                              >
                            </q-item>

                            <q-separator />
                            <q-item
                              clickable
                              v-ripple
                              @click="openListSeguimientoProcess(props.row)"
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
                                style="margin-left: -25px"
                                >Listar</q-item-section
                              >
                            </q-item>
                          </q-list>
                        </q-menu>
                      </q-item>
                      <q-separator />

                      <!--Menu principal alcance-->
                      <q-item clickable>
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-home-group"
                          />
                        </q-item-section>
                        <q-item-section
                          class="q-mr-md text-body2"
                          style="margin-left: -25px"
                          >Alcance</q-item-section
                        >
                        <q-item-section side>
                          <q-icon :size="'xs'" name="keyboard_arrow_right" />
                        </q-item-section>

                        <q-menu
                          auto-close
                          transition-show="jump-down"
                          transition-hide="jump-up"
                        >
                          <q-list style="min-width: 100px">
                            <q-item
                              clickable
                              v-ripple
                              @click="
                                openModalAlcance(true, 'addAlcance', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon :size="'xs'" color="green" name="add" />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px"
                                >Adicionar</q-item-section
                              >
                            </q-item>

                            <q-separator />
                            <q-item
                              clickable
                              v-ripple
                              @click.prevent="openListAlcance(props.row)"
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
                                style="margin-left: -25px"
                                >Listar</q-item-section
                              >
                            </q-item>
                          </q-list>
                        </q-menu>
                      </q-item>
                      <!--Fin menu principal alcance-->
                      <q-separator />
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalGrupoEvaluador(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-account-multiple-plus"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Grupo evaluador</q-item-section
                        >
                      </q-item>
                      <q-separator />

                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalRenovacion(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-autorenew"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Renovación</q-item-section
                        >
                      </q-item>

                      <q-separator />
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalDictamen(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-account-voice"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Dictamen 1ra eval</q-item-section
                        >
                      </q-item>
                      <q-separator />
                       <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalDictamenEvalTwo(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-account-voice"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Dictamen 2da eval</q-item-section
                        >
                      </q-item>
                      <q-separator />
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalSee(true, 'see', props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Ver más datos</q-item-section
                        >
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
                <!--Fin menu ver mas proceso principal-->
              </div>
            </q-td>
          </template>

            <template v-slot:item="props">
                            <q-card class="q-ma-xs">
                                <q-card-section>
                                   <q-btn
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="
                    openModalUpdate(true, 'actualizar', props.row)
                  "
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRow(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>

                <!--Menu ver mas proceso principal-->
                <q-btn size="sm" dense icon="more_vert" color="grey" flat>
                  <q-tooltip>Ver más</q-tooltip>
                  <q-menu>
                    <q-list dense style="min-width: 100px">
                      <q-item clickable>
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="folder" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Expediente</q-item-section
                        >
                        <q-item-section side>
                          <q-icon :size="'xs'" name="keyboard_arrow_right" />
                        </q-item-section>

                        <q-menu
                          auto-close
                          transition-show="jump-down"
                          transition-hide="jump-up"
                        >
                          <q-list style="min-width: 100px">
                            <q-separator />
                            <q-item
                              clickable
                              v-ripple
                              @click="
                                openModalExpediente(true, 'addExp', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon :size="'xs'" color="green" name="add" />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px"
                                >Adicionar</q-item-section
                              >
                            </q-item>
                            <!-- <q-separator />
                            <q-item clickable v-ripple>
                              <q-item-section avatar>
                                <q-icon
                                  :size="'xs'"
                                  color="blue"
                                  name="create"
                                />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px"
                                >Editar</q-item-section
                              >
                            </q-item>-->

                            <q-separator />
                            <q-item
                              clickable
                              v-ripple
                              @click="openExp(props.row)"
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
                                style="margin-left: -25px"
                                >Listar</q-item-section
                              >
                            </q-item>
                          </q-list>
                        </q-menu>
                      </q-item>
                      <q-separator />

                      <!--Seguimiento-->
                      <q-item clickable>
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-arrow-up-circle"
                          />
                        </q-item-section>
                        <q-item-section
                          class="q-mr-md text-body2"
                          style="margin-left: -25px"
                          >Seguimiento</q-item-section
                        >
                        <q-item-section side>
                          <q-icon :size="'xs'" name="keyboard_arrow_right" />
                        </q-item-section>

                        <q-menu
                          auto-close
                          transition-show="jump-down"
                          transition-hide="jump-up"
                        >
                          <q-list style="min-width: 100px">
                            <q-item
                              clickable
                              v-ripple
                              @click="
                                openModalSeguimiento(true, 'addNext', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon :size="'xs'" color="green" name="add" />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px"
                                >Adicionar</q-item-section
                              >
                            </q-item>

                            <q-separator />
                            <q-item
                              clickable
                              v-ripple
                              @click="openListSeguimientoProcess(props.row)"
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
                                style="margin-left: -25px"
                                >Listar</q-item-section
                              >
                            </q-item>
                          </q-list>
                        </q-menu>
                      </q-item>
                      <q-separator />

                      <!--Menu principal alcance-->
                      <q-item clickable>
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-home-group"
                          />
                        </q-item-section>
                        <q-item-section
                          class="q-mr-md text-body2"
                          style="margin-left: -25px"
                          >Alcance</q-item-section
                        >
                        <q-item-section side>
                          <q-icon :size="'xs'" name="keyboard_arrow_right" />
                        </q-item-section>

                        <q-menu
                          auto-close
                          transition-show="jump-down"
                          transition-hide="jump-up"
                        >
                          <q-list style="min-width: 100px">
                            <q-item
                              clickable
                              v-ripple
                              @click="
                                openModalAlcance(true, 'addAlcance', props.row)
                              "
                            >
                              <q-item-section avatar>
                                <q-icon :size="'xs'" color="green" name="add" />
                              </q-item-section>
                              <q-item-section
                                class="text-body2"
                                style="margin-left: -25px"
                                >Adicionar</q-item-section
                              >
                            </q-item>

                            <q-separator />
                            <q-item
                              clickable
                              v-ripple
                              @click.prevent="openListAlcance(props.row)"
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
                                style="margin-left: -25px"
                                >Listar</q-item-section
                              >
                            </q-item>
                          </q-list>
                        </q-menu>
                      </q-item>
                      <!--Fin menu principal alcance-->
                      <q-separator />
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalGrupoEvaluador(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-account-multiple-plus"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Grupo evaluador</q-item-section
                        >
                      </q-item>
                      <q-separator />

                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalRenovacion(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-autorenew"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Renovación</q-item-section
                        >
                      </q-item>

                      <q-separator />
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalDictamen(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-account-voice"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Dictamen 1ra eval</q-item-section
                        >
                      </q-item>
                      <q-separator />
                       <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalDictamenEvalTwo(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-account-voice"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Dictamen 2da eval</q-item-section
                        >
                      </q-item>
                      <q-separator />
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalSee(true, 'see', props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Ver más datos</q-item-section
                        >
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
                <!--Fin menu ver mas proceso principal-->
                                   
                                </q-card-section>
                                <q-separator></q-separator>
                                <q-list dense>
                                    <q-item :key="col.id"
                                            

                                            v-for="col in props.cols"
                                           >
                                        <q-item-section>
                                            <q-item-label  v-if="col.field != 'actions'">{{ col.label }}</q-item-label>
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-item-label caption>{{ col.value }}</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card>
                        </template>

          <template v-slot:body-cell-autodiagnostico="props">
            <q-td :props="props">
              <p v-if="props.row.autodiagnostico == 1">Si</p>
              <p v-if="props.row.autodiagnostico == 0">No</p>
            </q-td>
          </template>

          <template v-slot:body-cell-capacitacionInicial="props">
            <q-td :props="props">
              <p v-if="props.row.capacitacionInicial == 1">Si</p>
              <p v-if="props.row.capacitacionInicial == 0">No</p>
            </q-td>
          </template>

          <template v-slot:loading>
            <q-inner-loading showing color="primary" />
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>

    <!--List expediente -->
    <q-dialog
      v-model="folderExp"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title> Expediente </q-toolbar-title>

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
          <div class="text-h6">
            {{ form_create.numeroRegistro + " - " + nameInstalacion }}
          </div>
        </q-card-section>

        <q-card-section>
          <q-list
            bordered
            v-for="element in expedientes"
            :key="element.nombreElemento"
          >
            <q-item clickable v-ripple>
              <q-item-section>{{ element.nombreElemento }}</q-item-section>
              <q-item-section avatar>
                <q-icon
                  :size="'xs'"
                  @click="downloadFile(element)"
                  color="primary"
                  name="mdi-archive-arrow-down"
                />
                <q-tooltip>Descargar documento</q-tooltip>
              </q-item-section>
              <q-item-section avatar>
                <q-icon
                  :size="'xs'"
                  @click="deleteRowExp(element)"
                  color="negative"
                  name="delete"
                />
                <q-tooltip>Eliminar documento</q-tooltip>
              </q-item-section>
            </q-item>
          </q-list>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cerrar" color="negative" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!--Fin list expediente -->

    <!--List seguimiento-->
    <q-dialog
      v-model="listSeguimientoProcess"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              Proceso
              <b>{{
                form_create.numeroRegistro + " - " + nameInstalacion
              }}</b></q-toolbar-title
            >

            <q-btn
              round
              dense
              flat
              icon="close"
              @click="closeModal()"
              v-close-popup
            >
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>

        <q-card-section>
          <div class="row">
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              gestionar seguimiento
            </div>
            <q-space></q-space>
            <q-input
              dense
              autofocus
              flat
              v-if="filterInput"
              v-model="filterSeguimiento"
              debounce="1000"
              placeholder="Buscar"
              class="q-ml-md"
            />
            <q-btn
              key="filter"
              dense
              s
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_ths')"
              type="reset"
              key="add"
              dense
              color="primary"
              icon="add"
              @click.prevent="openModalSeguimientoNew(true, 'addNext')"
              class="q-mt-sm"
            >
              <q-tooltip>Nuevos datos</q-tooltip>
            </q-btn>
          </div>
        </q-card-section>

        <q-card-section>
          <q-table
            flat
            :data="seguimientosProcess"
            :columns="columnsNext"
            row-key="id"
            :loading="this.getterLoading()"
            loading-label="Cargando elementos"
            :rows-per-page-options="[5, 10, 25, 50]"
            :filter="filterSeguimiento"
            binary-state-sort
            no-data-label="No se encontraron elementos a mostrar"
            @row-click="onRowClick"
            size="xs"
            dense
          >
            <template v-slot:body-cell-actionsNext="props">
              <q-td :props="props">
                <div class="q-gutter-xs">
                 <!-- <q-btn
                    flat
                    key="see"
                    dense
                    size="sm"
                    text-color="primary"
                    icon="remove_red_eye"
                    @click.prevent="
                      openModalSeeSeguimiento(true, 'seeNext', props.row)
                    "
                  >
                    <q-tooltip>Ver detalles</q-tooltip>
                  </q-btn>-->
                  <q-btn
                    v-if="scopes.includes('gestionar_ths')"
                    flat
                    dense
                    size="sm"
                    text-color="primary"
                    icon="edit"
                    @click.prevent="
                      openModalUpdateSeguimiento(true, 'updateNext', props.row)
                    "
                  >
                    <q-tooltip>Editar datos</q-tooltip>
                  </q-btn>
                  <q-btn
                    v-if="scopes.includes('gestionar_ths')"
                    flat
                    dense
                    size="sm"
                    text-color="negative"
                    icon="delete"
                    @click.prevent="deleteRowSeguimiento(props.row)"
                  >
                    <q-tooltip>Eliminar datos</q-tooltip>
                  </q-btn>

                   <!--Menu ver mas-->
                  <q-btn size="sm" dense icon="more_vert" color="grey" flat>
                    <q-tooltip>Ver más</q-tooltip>
                    <q-menu>
                      <q-list dense style="min-width: 100px">
                        <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalDictamenSeguimiento(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-account-voice"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Dictamen 1ra eval</q-item-section
                        >
                      </q-item>
                      <q-separator />
                       <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalDictamenEvalTwoSeguimiento(props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-account-voice"
                          />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Dictamen 2da eval</q-item-section
                        >
                      </q-item>
                        <q-separator />
                        <q-item
                          clickable
                          v-ripple
                           @click.prevent="
                      openModalSeeSeguimiento(true, 'seeNext', props.row)
                    "
                        >
                          <q-item-section avatar>
                            <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                          </q-item-section>
                          <q-item-section
                            class="text-body2"
                            style="margin-left: -25px"
                            >Ver más datos</q-item-section
                          >
                            <q-tooltip>Ver detalles</q-tooltip>
                        </q-item>
                      </q-list>
                    </q-menu>
                  </q-btn>
                  <!--End menu ver mas-->
                </div>
              </q-td>
            </template>

            <template v-slot:loading>
              <q-inner-loading showing color="primary" />
            </template>
          </q-table>
        </q-card-section>
        <!-- /tabla-->
        <q-card-actions align="right">
          <q-btn
            flat
            label="Cerrar"
            color="negative"
            @click="closeModal()"
            v-close-popup
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!--Fin list seguimiento-->

    <!--Form proceso principal-->
    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 730px; max-width: 70vw">
        <q-form
          @reset="reset_form"
          @submit="accion != 'adicionar' ? update() : save()"
          ref="myform"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == "adicionar"
                      ? "Adicionar proceso T+HS"
                      : "Actualizar proceso T+HS"
                  }}
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
              <!--<div class="q-pa-md">-->
              <q-stepper
                class="stepper-custom"
                v-model="step"
                ref="stepper"
                color="primary"
                header-nav
                animated
              >
                <q-step
                  :name="1"
                  title="Registro"
                  icon="settings"
                  :done="step > 1"
                >
                  <div class="row q-gutter-x-md">
                    <div>
                      <span class="text-h6">Autodiagnóstico</span>
                      <q-radio v-model="autodiagnostico" val="Yes" label="Si" />
                      <q-radio v-model="autodiagnostico" val="No" label="No" />
                    </div>

                    <div>
                      <span class="text-h6">Capacitación inicial</span>
                      <q-radio
                        v-model="capacitacionInicial"
                        val="Yes"
                        label="Si"
                      />
                      <q-radio
                        v-model="capacitacionInicial"
                        val="No"
                        label="No"
                      />
                    </div>

                    <q-checkbox
                      class="text-h6"
                      left-label
                      dense
                      v-model="form_create.incluyeAlcance"
                      v-on:click.native="checkBox"
                      label="Incluir alcance"
                      color="teal"
                    />
                    <div class="col-md-4">
                      <q-select
                        v-model="form_create.instalacion_id"
                        :options="instalaciones"
                        label="Instalación"
                        options-dense
                        option-value="id"
                        option-label="nombre"
                        emit-value
                        map-options
                        :rules="[(val) => !!val || 'Campo requerido']"
                      >
                      </q-select>
                    </div>
                  </div>
                </q-step>

                <q-step
                  :name="2"
                  title="Evaluación"
                  icon="add_comment"
                  :done="step > 2"
                >
                  <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4 q-px-sm">
                      <q-input
                        label="Prevista evaluación"
                        hint="Fecha prevista para realizar la evaluación"
                        v-model="form_create.fechaPrevistaEvaluacion"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form_create.fechaPrevistaEvaluacion"
                                mask="YYYY-MM-DD"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div class="col-sm-4 col-md-4 col-lg-4 q-px-sm">
                      <q-input
                        label="Primera evaluación"
                        v-model="form_create.fechaPrimeraEvaluacion"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form_create.fechaPrimeraEvaluacion"
                                mask="YYYY-MM-DD"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div class="col-sm-4 col-md-4 col-lg-4 q-px-sm">
                      <q-input
                        label="Segunda evaluación"
                        v-model="form_create.fechaSegundaEvaluacion"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form_create.fechaSegundaEvaluacion"
                                mask="YYYY-MM-DD"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>
                  </div>
                </q-step>

                <q-step
                  :name="3"
                  title="Certificado"
                  icon="add_comment"
                  :done="step > 3"
                >
                  <div class="row q-gutter-x-sm">
                    <div class="q-mt-sm">
                      <span class="text-h6">Certificar</span>
                      <q-radio v-model="certificar" val="Yes" label="Si" />
                      <q-radio v-model="certificar" val="No" label="No" />
                    </div>

                    <div v-if="certificar == 'Yes'">
                      <q-input
                        label="Otorgado"
                        v-model="form_create.fechaOtorgado"
                        mask="date"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date v-model="form_create.fechaOtorgado">
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div v-if="certificar == 'No'">
                      <q-input
                        label="Denegado"
                        v-model="form_create.fechaDenegado"
                        mask="date"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date v-model="form_create.fechaDenegado">
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div v-show="certificar == 'No'">
                      <q-input
                        v-model="form_create.requisitoIncumplido"
                        label="Requisito incumplido"
                      />
                    </div>
                  </div>
                </q-step>

                <!-- <q-step
                  :name="4"
                  title="Seguimiento"
                  icon="add_comment"
                  :done="step > 4"
                >
                </q-step>-->

                <q-step :name="5" title="Renovación" icon="add_comment">
                  <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4 q-px-sm">
                      <q-input
                        label="Renovar certificado"
                        v-model="form_create.fechaRenovadoCertificado"
                        mask="date"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form_create.fechaRenovadoCertificado"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 q-px-sm">
                      <q-input
                        label="Observación"
                        v-model="form_create.observacion"
                        type="textarea"
                      />
                    </div>
                  </div>
                </q-step>

                <template v-slot:navigation>
                  <q-stepper-navigation>
                    <q-btn
                      @click="$refs.stepper.next()"
                      color="primary"
                      label="Continuar"
                      v-if="step < 5"
                    />
                    <q-btn
                      v-if="step > 1"
                      flat
                      color="primary"
                      @click="$refs.stepper.previous()"
                      label="Atrás"
                      class="q-ml-sm"
                    />
                  </q-stepper-navigation>
                </template>
              </q-stepper>
              <!--</div>-->
            </q-card-section>

            <q-card-actions align="right">
              <q-btn
                type="submit"
                key="guardar"
                label="Guardar"
                flat
                color="primary"
                :icon="accion === 'adicionar' ? 'save' : 'edit'"
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
    <!--Fin form proceso principal-->

    <!--Ver mas datos proceso principal-->
    <q-dialog
      v-model="modalDialogSee"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              <b
                ># de registro: {{ form_create.numeroRegistro }}
                {{ nameInstalacion }}</b
              >
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <div class="q-pl-md q-pb-md">
            <div class="column" style="height: 150px">
              <div class="col text-h6">Registro</div>
              <div class="col">Instalación: {{ nameInstalacion }}</div>
              <div class="col">
                Entrega al mintur: {{ form_create.fechaEntregaMintur }}
              </div>
              <div class="col" v-if="form_create.autodiagnostico == 0">
                Autodiagnóstico: No
              </div>
              <div class="col" v-if="form_create.autodiagnostico == 1">
                Autodiagnóstico: Si
              </div>
              <div class="col" v-if="form_create.capacitacionInicial == 0">
                Capacitación inicial: No
              </div>
              <div class="col" v-if="form_create.capacitacionInicial == 1">
                Capacitación inicial: Si
              </div>
            </div>

            <div class="column" style="height: 150px">
              <div class="col text-h6">Evaluación</div>
              <div class="col">
                Evaluación prevista: {{ form_create.fechaPrevistaEvaluacion }}
              </div>
              <div class="col">
                Primera evaluación: {{ form_create.fechaPrimeraEvaluacion }}
              </div>
              <div class="col">
                Segunda evaluación: {{ form_create.fechaSegundaEvaluacion }}
              </div>
            </div>

            <div class="column" style="height: 150px">
              <div class="col text-h6">Certificado</div>
              <div class="col">Otorgado: {{ form_create.fechaOtorgado }}</div>
              <div class="col">Denegado: {{ form_create.fechaDenegado }}</div>
              <div class="col">
                Requisito incumplido: {{ form_create.requisitoIncumplido }}
              </div>
            </div>

            <div class="column">
              <div class="col text-h6">Renovación</div>
              <div class="col">
                Renovado: {{ form_create.fechaRenovadoCertificado }}
              </div>
            </div>
          </div>
        </q-card-section>
        <q-separator inset />
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
    <!--Fin del ver mas datos proceso principal-->

    <!--Ver mas datos de seguimiento-->
    <q-dialog
      v-model="modalDialogSeeSeguimiento"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title> Datos de seguimiento </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>

        <q-card-section>
          <div class="q-pl-md q-pb-md">
            <div class="column" style="height: 150px">
              <div class="col">Mes: {{ formNext.fechaSeguimientoMensual }}</div>
              <div class="col">
                Semestre: {{ formNext.fechaSeguimientoTrimestral }}
              </div>
              <div class="col">
                Suspensión temporal del certificado:
                {{ formNext.fechaSuspensionTemporalCertificado }}
              </div>
              <div class="col">
                Retiro de la suspensión temporal del certificado:
                {{ formNext.fechaRetiroSuspensionTemporalCertificado }}
              </div>
              <div class="col">
                Cancelación de la certificación:
                {{ formNext.fechaCanceladoCertificado }}
              </div>
              <div class="col">
                Requisito incumplido: {{ formNext.requisitoIncumplido }}
              </div>
            </div>
          </div>
        </q-card-section>
        <q-separator inset />
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="close"
            @click="closeModalSeguimiento()"
            label="Cancelar"
            v-close-popup
            color="negative"
            flat
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!--Fin del ver mas datos del seguimiento-->

    <!-- Dialog form expediente-->
    <q-dialog
      v-model="modalDialogExp"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-form
          @reset="reset_form"
          @submit="accion != 'addExp' ? updateExp() : saveExp()"
          ref="formExp"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == "addExp"
                      ? "Adicionar expediente"
                      : "Actualizar expediente"
                  }}
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
              <div class="text-h6">
                {{ form_create.numeroRegistro + " - " + nameInstalacion }}
              </div>
            </q-card-section>

            <q-card-section>
              <div class="q-pa-md" style="margin-top: -36px">
                <div class="row">
                  <div class="col-sm-12 col-md-12 col-lg-12">
                    <q-uploader
                      :factory="uploadFiles"
                      url="http://localhost:4444/upload"
                      label="Cargar documento"
                      multiple
                      style="max-width: 300px"
                    />
                  </div>
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
          </q-card-section>
        </q-form>
      </q-card>
    </q-dialog>
    <!--Fin dialog form expediente-->

    <!--Dialog form seguimiento-->
    <q-dialog
      v-model="modalDialogNext"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-form
          @reset="reset_form"
          @submit="accion != 'addNext' ? updateNext() : saveNext()"
          ref="formNext"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == "addNext"
                      ? "Adicionar seguimiento"
                      : "Actualizar seguimiento"
                  }}
                </q-toolbar-title>
                <q-btn
                  round
                  dense
                  flat
                  icon="close"
                  @click="closeModalSeguimiento"
                  v-close-popup
                >
                  <q-tooltip>Cerrar</q-tooltip>
                </q-btn>
              </q-toolbar>
            </q-card-section>

            <q-card-section>
              <div class="row q-gutter-y-md">
                
                    <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                      <q-input
                        label="Primera evaluación"
                        v-model="formNext.fechaPrimeraEvaluacion"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="formNext.fechaPrimeraEvaluacion"
                               mask="YYYY-MM-DD"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                      <q-input
                        label="Segunda evaluación"
                        v-model="formNext.fechaSegundaEvaluacion"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                               v-model="formNext.fechaSegundaEvaluacion"
                               mask="YYYY-MM-DD"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>
                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Fecha del mes"
                    v-model="formNext.fechaSeguimientoMensual"
                    mask="date"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date v-model="formNext.fechaSeguimientoMensual">
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Fecha del semestre"
                    v-model="formNext.fechaSeguimientoTrimestral"
                    mask="date"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date v-model="formNext.fechaSeguimientoTrimestral">
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Supensión temporal del certificado"
                    v-model="formNext.fechaSuspensionTemporalCertificado"
                    mask="date"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            v-model="
                              formNext.fechaSuspensionTemporalCertificado
                            "
                          >
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Retiro de la supensión temporal del certificado"
                    v-model="formNext.fechaRetiroSuspensionTemporalCertificado"
                    mask="date"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date
                            v-model="
                              formNext.fechaRetiroSuspensionTemporalCertificado
                            "
                          >
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Cancelación del certificado"
                    v-model="formNext.fechaCanceladoCertificado"
                    mask="date"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date v-model="formNext.fechaCanceladoCertificado">
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Cerrar"
                                color="negative"
                                flat
                              />
                            </div>
                          </q-date>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    v-model="formNext.requisitoIncumplido"
                    label="Requisito incumplido"
                  />
                </div>
              </div>
            </q-card-section>

            <q-card-actions align="right">
              <q-btn
                type="submit"
                key="guardar"
                label="Guardar"
                flat
                color="primary"
                :icon="accion === 'addNext' ? 'save' : 'edit'"
                v-show="add"
              >
              </q-btn>
              <q-btn
                type="button"
                icon="close"
                @click="closeModalSeguimiento()"
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
    <!--Dialog form seguimiento-->

    <!--Dialog form alcance-->
    <q-dialog
      v-model="modalDialogAlcance"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 730px; max-width: 70vw">
        <q-form
          @reset="reset_form"
          @submit="accion != 'addAlcance' ? updateAlcance() : saveAlcance()"
          ref="myform"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  <span v-if="accion == 'addAlcance'">
                    Adicionar alcance, proceso:
                    <b>
                      {{
                        form_create.numeroRegistro + " - " + nameInstalacion
                      }}</b
                    >
                  </span>
                  <span v-else>
                    Actualizar alcance, proceso:
                    <b>
                      {{
                        form_create.numeroRegistro + " - " + nameInstalacion
                      }}</b
                    >
                  </span>
                </q-toolbar-title>
                <q-btn
                  round
                  dense
                  flat
                  icon="close"
                  @click="closeModalAlcance()"
                  v-close-popup
                >
                  <q-tooltip>Cerrar</q-tooltip>
                </q-btn>
              </q-toolbar>
            </q-card-section>

            <q-card-section>
              <!--<div class="q-pa-md">-->
              <q-stepper
                class="stepper-custom"
                v-model="step"
                ref="stepper"
                color="primary"
                header-nav
                animated
              >
                <q-step
                  :name="1"
                  title="Registro"
                  icon="settings"
                  :done="step > 1"
                >
                  <div class="row q-gutter-x-md">
                    <div>
                      <span class="text-h6">Autodiagnóstico</span>
                      <q-radio
                        v-model="autodiagnosticoAlcance"
                        val="Yes"
                        label="Si"
                      />
                      <q-radio
                        v-model="autodiagnosticoAlcance"
                        val="No"
                        label="No"
                      />
                    </div>

                    <div>
                      <span class="text-h6">Capacitación inicial</span>
                      <q-radio
                        v-model="capacitacionInicialAlcance"
                        val="Yes"
                        label="Si"
                      />
                      <q-radio
                        v-model="capacitacionInicialAlcance"
                        val="No"
                        label="No"
                      />
                    </div>

                    <!--<q-checkbox
                      class="text-h6"
                      left-label
                      dense
                      v-model="form_alcance.incluyeAlcance"
                      label="Incluir alcance"
                      color="teal"
                    />-->

                    <div class="col-md-4">
                      <q-select
                        v-model="form_alcance.instalacion_id"
                        :options="instalaciones"
                        label="Instalación"
                        options-dense
                        option-value="id"
                        option-label="nombre"
                        emit-value
                        map-options
                        :rules="[(val) => !!val || 'Campo requerido']"
                      >
                      </q-select>
                    </div>
                  </div>
                </q-step>

                <q-step
                  :name="2"
                  title="Evaluación"
                  icon="add_comment"
                  :done="step > 2"
                >
                  <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-3 q-px-sm">
                      <q-input
                        label="Prevista evaluación"
                        hint="Fecha de prevista para realizar la evaluación"
                        v-model="form_alcance.fechaPrevistaEvaluacion"
                        mask="date"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form_alcance.fechaPrevistaEvaluacion"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div class="col-sm-3 col-md-3 col-lg-3 q-px-sm">
                      <q-input
                        label="Primera evaluación"
                        v-model="form_alcance.fechaPrimeraEvaluacion"
                        mask="date"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form_alcance.fechaPrimeraEvaluacion"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div class="col-sm-3 col-md-3 col-lg-3 q-px-sm">
                      <q-input
                        label="Segunda evaluación"
                        v-model="form_alcance.fechaSegundaEvaluacion"
                        mask="date"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form_alcance.fechaSegundaEvaluacion"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>
                  </div>
                </q-step>

                <q-step
                  :name="3"
                  title="Certificado"
                  icon="add_comment"
                  :done="step > 3"
                >
                  <div class="row q-gutter-x-sm">
                    <div class="q-mt-sm">
                      <span class="text-h6">Certificar</span>
                      <q-radio
                        v-model="certificarAlcance"
                        val="Yes"
                        label="Si"
                      />
                      <q-radio
                        v-model="certificarAlcance"
                        val="No"
                        label="No"
                      />
                    </div>

                    <div v-if="certificarAlcance == 'Yes'">
                      <q-input
                        label="Otorgado"
                        v-model="form_alcance.fechaOtorgado"
                        mask="date"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date v-model="form_alcance.fechaOtorgado">
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div v-if="certificarAlcance == 'No'">
                      <q-input
                        label="Denegado"
                        v-model="form_alcance.fechaDenegado"
                        mask="date"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date v-model="form_alcance.fechaDenegado">
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>

                    <div v-show="certificarAlcance == 'No'">
                      <q-input
                        v-model="form_alcance.requisitoIncumplido"
                        label="Requisito incumplido"
                      />
                    </div>
                  </div>
                </q-step>

                <!-- <q-step
                  :name="4"
                  title="Seguimiento"
                  icon="add_comment"
                  :done="step > 4"
                >
                </q-step>-->

                <q-step :name="5" title="Renovación" icon="add_comment">
                  <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4 q-px-sm">
                      <q-input
                        label="Renovar certificado"
                        v-model="form_alcance.fechaRenovadoCertificado"
                        mask="date"
                      >
                        <template v-slot:append>
                          <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy
                              ref="qDateProxy"
                              transition-show="scale"
                              transition-hide="scale"
                            >
                              <q-date
                                v-model="form_alcance.fechaRenovadoCertificado"
                              >
                                <div class="row items-center justify-end">
                                  <q-btn
                                    v-close-popup
                                    label="Cerrar"
                                    color="negative"
                                    flat
                                  />
                                </div>
                              </q-date>
                            </q-popup-proxy>
                          </q-icon>
                        </template>
                      </q-input>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 q-px-sm">
                      <q-input
                        label="Observación"
                        v-model="form_alcance.observacion"
                        type="textarea"
                      />
                    </div>
                  </div>
                </q-step>

                <template v-slot:navigation>
                  <q-stepper-navigation>
                    <q-btn
                      @click="$refs.stepper.next()"
                      color="primary"
                      label="Continuar"
                      v-if="step < 5"
                    />
                    <q-btn
                      v-if="step > 1"
                      flat
                      color="primary"
                      @click="$refs.stepper.previous()"
                      label="Atrás"
                      class="q-ml-sm"
                    />
                  </q-stepper-navigation>
                </template>
              </q-stepper>
              <!--</div>-->
            </q-card-section>

            <q-card-actions align="right">
              <q-btn
                type="submit"
                key="guardar"
                label="Guardar"
                flat
                color="primary"
                :icon="accion === 'addAlcance' ? 'save' : 'edit'"
                v-show="add"
              >
              </q-btn>
              <q-btn
                type="button"
                icon="close"
                @click="closeModalAlcance()"
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
    <!--Fin dialog form alcance-->

    <!--List alcance-->
    <q-dialog
      v-model="dialogListAlcance"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 800px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              <b
                ># de registro:
                {{ form_create.numeroRegistro + " - " + nameInstalacion }}</b
              ></q-toolbar-title
            >

            <q-btn
              round
              dense
              flat
              icon="close"
              @click="closeModal()"
              v-close-popup
            >
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              gestionar alcance
            </div>
            <q-space></q-space>
            <q-input
              dense
              autofocus
              flat
              v-if="filterInput"
              v-model="filterAlcance"
              debounce="1000"
              placeholder="Buscar"
              class="q-ml-md"
            />
            <q-btn
              key="filter"
              dense
              s
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_ths')"
              type="reset"
              key="add"
              dense
              color="primary"
              icon="add"
              @click.prevent="openModalAlcance(true, 'addAlcance')"
              class="q-mt-sm"
            >
              <q-tooltip>Nuevos datos</q-tooltip>
            </q-btn>
          </div>
        </q-card-section>
        <!-- tabla-->
        <q-card-section>
          <q-table
            flat
            :data="alcances"
            :columns="columnsAlcance"
            row-key="id"
            :loading="this.getterLoading()"
            loading-label="Cargando elementos"
            :rows-per-page-options="[5, 10, 25, 50]"
            :filter="filterAlcance"
            binary-state-sort
            no-data-label="No se encontraron elementos a mostrar"
            @row-click="onRowClick"
            size="xs"
            dense
          >
            <template v-slot:body-cell-actions="props">
              <q-td :props="props">
                <div class="q-gutter-xs">
                  <q-btn
                    v-if="scopes.includes('gestionar_ths')"
                    flat
                    dense
                    size="sm"
                    text-color="primary"
                    icon="edit"
                    @click.prevent="
                      openModalUpdateAlcance(true, 'actualizar', props.row)
                    "
                  >
                    <q-tooltip>Editar datos</q-tooltip>
                  </q-btn>
                  <q-btn
                    v-if="scopes.includes('gestionar_ths')"
                    flat
                    dense
                    size="sm"
                    text-color="negative"
                    icon="delete"
                    @click.prevent="deleteRowAlcance(props.row)"
                  >
                    <q-tooltip>Eliminar datos</q-tooltip>
                  </q-btn>

                  <!--Menu ver mas alcance-->
                  <q-btn size="sm" dense icon="more_vert" color="grey" flat>
                    <q-tooltip>Ver más</q-tooltip>
                    <q-menu>
                      <q-list dense style="min-width: 100px">
                        <!--Seguimiento-->
                        <!--
                        <q-item clickable>
                          <q-item-section avatar>
                            <q-icon
                              :size="'xs'"
                              color="green"
                              name="mdi-page-next"
                            />
                          </q-item-section>
                          <q-item-section
                            class="q-mr-md text-body2"
                            style="margin-left: -25px"
                            >Seguimiento</q-item-section
                          >
                          <q-item-section side>
                            <q-icon :size="'xs'" name="keyboard_arrow_right" />
                          </q-item-section>

                          <q-menu
                            auto-close
                            transition-show="jump-down"
                            transition-hide="jump-up"
                          >
                            <q-list style="min-width: 100px">
                              <q-item
                                clickable
                                v-ripple
                                @click="
                                  openModalSeguimientoAlcance(
                                    true,
                                    'addNext',
                                    props.row
                                  )
                                "
                              >
                                <q-item-section avatar>
                                  <q-icon
                                    :size="'xs'"
                                    color="green"
                                    name="add"
                                  />
                                </q-item-section>
                                <q-item-section
                                  class="text-body2"
                                  style="margin-left: -25px"
                                  >Adicionar</q-item-section
                                >
                              </q-item>

                              <q-separator />
                              <q-item
                                clickable
                                v-ripple
                                @click.prevent="openListSeguimientoAlcance(props.row)"
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
                                  style="margin-left: -25px"
                                  >Listar</q-item-section
                                >
                              </q-item>
                            </q-list>
                          </q-menu>
                        </q-item>
                        -->

                        <q-item
                          clickable
                          v-ripple
                          @click.prevent="openListSeguimientoAlcance(props.row)"
                        >
                          <q-item-section avatar>
                            <q-icon
                            :size="'xs'"
                            color="blue"
                            name="mdi-arrow-up-circle"
                          />
                          </q-item-section>
                          <q-item-section
                            class="text-body2"
                            style="margin-left: -25px"
                            >Seguimiento</q-item-section
                          >
                        </q-item>
                        <q-separator />
                        <q-item
                          clickable
                          v-ripple
                          @click.prevent="
                            openModalSeeAlcance(true, 'seeAlcance', props.row)
                          "
                        >
                          <q-item-section avatar>
                            <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                          </q-item-section>
                          <q-item-section
                            class="text-body2"
                            style="margin-left: -25px"
                            >Ver más datos</q-item-section
                          >
                        </q-item>
                      </q-list>
                    </q-menu>
                  </q-btn>
                  <!--End menu ver mas alcance-->
                </div>
              </q-td>
            </template>

            <template v-slot:body-cell-autodiagnostico="props">
              <q-td :props="props">
                <p v-if="props.row.autodiagnostico == 1">Si</p>
                <p v-if="props.row.autodiagnostico == 0">No</p>
              </q-td>
            </template>

            <template v-slot:body-cell-capacitacionInicial="props">
              <q-td :props="props">
                <p v-if="props.row.capacitacionInicial == 1">Si</p>
                <p v-if="props.row.capacitacionInicial == 0">No</p>
              </q-td>
            </template>

            <template v-slot:loading>
              <q-inner-loading showing color="primary" />
            </template>
          </q-table>
          <!-- /tabla-->
        </q-card-section>
      </q-card>
    </q-dialog>
    <!--List seguimiento alcance-->

    <SeguimientoTurismoMasHS v-show="showForm" :itemSelected="form_alcance" />
    <GrupoEvaluador v-show="showGrupoEvaluador" :itemSelected="form_create" />
    <DictamenTMHS v-show="showDict" :itemSelected="form_create" />
    <DictamenTMHSEvalTwo v-show="showDictEvalTwo" :itemSelected="form_create" />
    <RenovacionTurismoMasHS v-show="showFormRenovar" :itemSelected="form_create" />

    <!--Fin list seguimiento alcance-->

    <!--Ver mas de alcance-->
    <q-dialog
      v-model="modalDialogSeeAlcance"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              <b
                ># de registro: {{ form_alcance.numeroRegistro }}
                {{ nameInstalacionAlcance }}</b
              >
            </q-toolbar-title>
            <q-btn
              dense
              flat
              icon="close"
              @click="closeModalAlcance()"
              v-close-popup
            >
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <div class="q-ma-md">
            <div class="text-subtitle1 text-center">Registro</div>
            <div class="row q-gutter-x-xs">
              <div class="col">
                Entrega al mintur: {{ form_alcance.fechaEntregaMintur }}
              </div>
              <div class="col" v-if="form_alcance.autodiagnostico == 0">
                Autodiagnóstico: No
              </div>
              <div class="col" v-if="form_alcance.autodiagnostico == 1">
                Autodiagnóstico: Si
              </div>
              <div class="col" v-if="form_alcance.capacitacionInicial == 0">
                Capacitación inicial: No
              </div>
              <div class="col" v-if="form_alcance.capacitacionInicial == 1">
                Capacitación inicial: Si
              </div>
            </div>

            <div class="text-subtitle1 text-center">Evaluación</div>
            <div class="row q-gutter-x-xs">
              <div class="col">
                Evaluación prevista: {{ form_alcance.fechaPrevistaEvaluacion }}
              </div>
              <div class="col">
                Primera evaluación: {{ form_alcance.fechaPrimeraEvaluacion }}
              </div>
              <div class="col">
                Segunda evaluación: {{ form_alcance.fechaSegundaEvaluacion }}
              </div>
            </div>

            <div class="text-subtitle1 text-center">Certificado</div>
            <div class="row q-gutter-x-xs">
              <div class="col">Otorgado: {{ form_alcance.fechaOtorgado }}</div>
              <div class="col">Denegado: {{ form_alcance.fechaDenegado }}</div>
              <div class="col">
                Requisito incumplido: {{ form_alcance.requisitoIncumplido }}
              </div>
            </div>

            <div class="text-subtitle1 text-center">Renovación</div>
            <div class="row q-gutter-x-xs">
              <div class="col">
                Renovado: {{ form_alcance.fechaRenovadoCertificado }}
              </div>
            </div>
          </div>
        </q-card-section>

        <q-separator inset />
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="close"
            @click="closeModalAlcance()"
            label="Cancelar"
            v-close-popup
            color="negative"
            flat
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!--Fin del ver mas datos de alcance-->
    <!--RENOVACION-->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { showDialog } from "../../utils/dialogo";
import { infoMessage } from "../../utils/notificaciones";
import * as message from "../../utils/resources";

import axios from "axios";
import { date } from "quasar";
import { errorMessage, successMessage } from "../../utils/notificaciones";
import SeguimientoTurismoMasHS from "../../components/SeguimientoTurismoMasHS";
import GrupoEvaluador from "../../components/GrupoEvaluador";
import DictamenTMHS from "../../components/DictamenTMHS";
import DictamenTMHSEvalTwo from "../../components/DictamenTMHSEvalTwo";
import RenovacionTurismoMasHS from "../../components/RenovacionTurismoMasHS";
import SmallScreen from "../../mixin/SmallScreen";

export default {
  name: "GestionarTHS",
  mixins:[SmallScreen],
  components: {
    SeguimientoTurismoMasHS,
    GrupoEvaluador,
    DictamenTMHS,
    DictamenTMHSEvalTwo,
    RenovacionTurismoMasHS
  },
  data() {
    return {
      scopes: sessionStorage.getItem("scopes"),
      modalDialogAlcance: false,
      modalDialog: false,
      modalDialogSee: false,
      modalDialogSeeSeguimiento: false,
      modalDialogExp: false,
      modalDialogNext: false,
      filterInput: false,
      loading: false,
      filter: "",
      filterSeguimiento: "",
      selected: [],
      val: false,
      add: true,
      accion: "",
      formExp: {
        id: 0,
        nombreElemento: "",
        urlElemento: "",
        proceso_id: null,
      },
      //Declaracion de atributos del proceso principal
      autodiagnostico: null,
      certificar: "",
      capacitacionInicial: null,
      nameInstalacion: "",
      dialogListAlcance: false,
      changeStatus: null,
      //Fin declaracion de atributos del proceso principal

      //Declaracion de atributos de alcance
      autodiagnosticoAlcance: null,
      certificarAlcance: "",
      capacitacionInicialAlcance: null,
      nameInstalacionAlcance: "",
      alcances: [],
      filterAlcance: "",
      modalDialogSeeAlcance: false,
      //Fin declaracion de atributos de alcance
      form_create: {
        id: 0,
        //Registro
        numeroRegistro: "",
        autodiagnostico: null,
        capacitacionInicial: null,
        fechaEntregaMintur: null,
        //instalacion_id: sessionStorage.getItem("instalacion_id"),
        instalacion_id: null,

        agrupacion: "",
        alcance: 0,
        incluyeAlcance: false,
        //Evaluacion
        fechaPrevistaEvaluacion: null,
        fechaPrimeraEvaluacion: null,
        fechaSegundaEvaluacion: null,
        //Certificado
        fechaOtorgado: null,
        fechaDenegado: null,
        requisitoIncumplido: "",
        //Renovacion
        fechaRenovadoCertificado: null,
        observacion: "",
      },
      form_alcance: {
        id: 0,
        //Registro
        numeroRegistro: "",
        autodiagnostico: null,
        capacitacionInicial: null,
        fechaEntregaMintur: null,
        instalacion_id: null,
        proceso_id: null,

        agrupacion: "",
        alcance: 0,
        incluyeAlcance: false,
        //Evaluacion
        fechaPrevistaEvaluacion: null,
        fechaPrimeraEvaluacion: null,
        fechaSegundaEvaluacion: null,
        //Certificado
        fechaOtorgado: null,
        fechaDenegado: null,
        requisitoIncumplido: "",
        //Renovacion
        fechaRenovadoCertificado: null,
        observacion: "",
      },
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
        {
          name: "numeroRegistro",
          align: "left",
          label: "número de registro",
          field: (row) => row.numeroRegistro,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "instalacion",
          align: "left",
          label: "instalación",
          field: (row) => row.instalacion.nombre,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "fechaEntregaMintur",
          align: "left",
          label: "fecha de entrega al mintur",
          //field: (row) => row.fechaEntregaMintur,
          field: 'fechaEntregaMintur',
          format: (val, row) => date.formatDate(val, 'DD-MMM-YYYY'),
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "actions",
          label: "Acciones",
          field: "",
          align: "center",
          headerClasses: "text-uppercase",
        },
      ],
      columnsAlcance: [
        {
          name: "numeroRegistro",
          align: "left",
          label: "número de registro",
          field: (row) => row.numeroRegistro,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        /* {
          name: "instalacion",
          align: "left",
          label: "Instalación pricipal",
          field: (row) => row.proceso_turismo_mas_higienico_seguro.instalacion.nombre,
          sortable: true,
          headerClasses: "text-uppercase",
        },*/
        {
          name: "instalacion",
          align: "left",
          label: "instalación en reprentación",
          field: (row) => row.instalacion.nombre,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "fechaEntregaMintur",
          align: "left",
          label: "fecha de entrega al mintur",
          field: (row) => row.fechaEntregaMintur,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "actions",
          label: "Acciones",
          field: "",
          align: "center",
          headerClasses: "text-uppercase",
        },
      ],
      columnsNext: [
        {
          name: "fechaMensual",
          align: "left",
          label: "Mes",
          field: (row) => row.fechaSeguimientoMensual,
         //  field: 'fechaSeguimientoMensual',
         // format: (val, row) => date.formatDate(val, 'DD/MMM/YYYY'),
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "fechaTrimestral",
          align: "left",
          label: "Semestre",
          field: (row) => row.fechaSeguimientoTrimestral,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "actionsNext",
          label: "Acciones",
          field: "",
          align: "center",
          headerClasses: "text-uppercase",
        },
      ],
      step: 1,
      instalaciones: [],
      data: [],
      dataNext: [],
      today: new Date(),
      folderExp: false,
      listSeguimientoProcess: false,
      DialogSeguimientoAlcance: false,
      expedientes: [],
      expedientesAll: [],
      seguimientosProcess: [],
       evenEmit: {
        proceso_id: null,
        seguimiento_id: null,
        renovacion_id: null,    
      },
      formNext: {
        id: 0,
        fechaSeguimientoMensual: "",
        fechaSeguimientoTrimestral: "",
        fechaSuspensionTemporalCertificado: "",
        fechaRetiroSuspensionTemporalCertificado: "",
        fechaCanceladoCertificado: "",
        requisitoIncumplido: "",
        proceso_id: null,
        fechaPrimeraEvaluacion: null,
        fechaSegundaEvaluacion: null,
      },
      showForm: false,
      showFormRenovar: false,
      showGrupoEvaluador: false,
      showDict: false,
      showDictEvalTwo: false
    };
  },
  created() {
    this.loadData2();
    this.loadDataInstalaciones();
  },
  mounted() {
    this.$root.$on("closeTable", (data) => {
      this.showForm = false;
      this.dialogListAlcance = true;
    });

    this.addToHomeBreadcrumbs([
      { label: "Dirección de Calidad" },
      { label: "Turismo más higiénico y seguro" },
      { label: "Turismo más higiénico y seguro" },
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

    /* "form_create.incluyeAlcance"(newVal, oldVal) {
    },*/
  },
  computed: {
    ...mapState("renglon", ["renglon"]),
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("renglon", [
      "getRenglon",
      "saveRenglon",
      "editRenglon",
      "deleteRenglon",
    ]),
    ...mapActions("utils", ["setLoading"]),
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    checkBox() {
      if (!this.form_create.incluyeAlcance) {
        this.form_create.alcance = this.form_create.alcance - 1;
        console.log("OOhQQ", this.form_create.alcance);
      } else {
        this.form_create.alcance = this.form_create.alcance + 1;
        console.log("OOhWW", this.form_create.alcance);
      }
    },
    reset_form() {
      this.changeStatus = null;
      this.form_create.id = 0;
      //Registro
      this.form_create.numeroRegistro = "";
      this.form_create.autodiagnostico = null;
      this.form_create.capacitacionInicial = null;
      this.form_create.fechaEntregaMintur = null;
      this.form_create.instalacion_id = null;

      this.form_create.agrupacion = "";
      this.form_create.alcance = 0;
      this.form_create.incluyeAlcance = false;
      //Evaluacion
      this.form_create.fechaPrevistaEvaluacion = null;
      this.form_create.fechaPrimeraEvaluacion = null;
      this.form_create.fechaSegundaEvaluacion = null;
      //Certificado
      this.form_create.fechaOtorgado = null;
      this.form_create.fechaDenegado = null;
      this.form_create.requisitoIncumplido = "";
      //Renovacion
      (this.form_create.fechaRenovadoCertificado = null),
        (this.form_create.observacion = "");

      //Radio boton
      this.autodiagnostico = "";
      this.capacitacionInicial = "";

      this.certificar = "";

      this.formExp.id = 0;
      //Registro
      this.formExp.proceso_id = null;
    },
    closeModal() {
      this.modalDialog = false;
      this.modalDialogExp = false;
      this.modalDialogNext = false;
      this.folderExp = false;
      this.reset_form();
      this.selected = [];
    },

    reset_form_seguimiento() {
      this.formNext.id = 0;
      this.formNext.proceso_id = null;
      this.formNext.fechaSeguimientoMensual = "";
      (this.formNext.fechaSeguimientoTrimestral = ""),
        (this.formNext.fechaSuspensionTemporalCertificado = ""),
        (this.formNext.fechaRetiroSuspensionTemporalCertificado = "");
      this.formNext.fechaCanceladoCertificado = "";
      this.formNext.requisitoIncumplido = "";
      this.formNext.fechaPrimeraEvaluacion = "";
      this.formNext.fechaSegundaEvaluacion = "";
    },
    closeModalSeguimiento() {
      this.modalDialogNext = false;
      this.reset_form_seguimiento();
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
      /* await this.getRenglon(data).then(r => {
                this.pagination.rowsNumber = r.total
                this.pagination.page = page
                this.pagination.rowsPerPage = rowsPerPage
                this.pagination.sortBy = sortBy
                this.pagination.descending = descending
                this.setLoading(false)
            })*/

      return await axios
        .get("/api/procesos_turismo_mas_higienico_seguro")
        .then((result) => {
          this.data = result.data.data;
          this.pagination.rowsNumber = result.data.meta.total;
          this.pagination.page = page;
          this.pagination.rowsPerPage = rowsPerPage;
          this.pagination.sortBy = sortBy;
          this.pagination.descending = descending;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadData2() {
      this.setLoading(true);
      return await axios
        .get("/api/procesos_turismo_mas_higienico_seguro")
        .then((result) => {
          this.data = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadDataInstalaciones() {
      return await axios
        .get("/api/get_instalacion")
        .then((result) => {
          this.instalaciones = result.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    typeSelection() {
      return this.scopes.includes("update_renglones") ||
        this.scopes.includes("delete_renglones")
        ? "single"
        : "none";
    },
    filterFn(val, update) {
      update(() => {
        const needle = val.toLowerCase();
        this.options = this.permissions.filter(
          (v) => v.name.toLowerCase().indexOf(needle) > -1
        );
      });
    },
    onRowClick(evt, row) {
      if (this.selected.length === 0) {
        this.selected.push(row);
      } else if (
        this.selected.length === 1 &&
        this.selected[0].nombre !== row.nombre
      ) {
        this.selected = [];
        this.selected.push(row);
      } else if (
        this.selected.length === 1 &&
        this.selected[0].nombre === row.nombre
      ) {
        this.selected = [];
      }
    },

    openModal(data, accion) {
      this.reset_form();
      this.accion = accion;
      if (accion === "adicionar") {
        this.modalDialog = data;
        this.certificar = "";
      }
    },

    openModalUpdate(data, accion, dataRow) {
      this.accion = accion;
      if (accion === "actualizar") {
        this.setFormData(dataRow);
        this.modalDialog = data;
      }
    },
    openModalSee(data, action, dataRow) {
      this.accion = action;
      if (action === "see") {
        this.setFormData(dataRow);
        this.modalDialogSee = data;
      }
    },
    openModalSeeSeguimiento(data, action, dataRow) {
      this.accion = action;
      if (action === "seeNext") {
        this.setFormDataSeguimiento(dataRow);
        this.modalDialogSeeSeguimiento = data;
      }
    },
    openModalExpediente(data, accion, dataRow) {
      this.accion = accion;
      if (accion === "addExp") {
        this.setFormData(dataRow);
        this.formExp.proceso_id = dataRow.id;
        this.modalDialogExp = data;
      }
    },
    openModalSeguimiento(data, accion, dataRow) {
      this.accion = accion;
      if (accion === "addNext") {
        this.formNext.proceso_id = dataRow.id;
        this.modalDialogNext = data;
      }
    },

    openModalSeguimientoNew(data, accion) {
      this.accion = accion;
      if (accion === "addNext") {
        this.formNext.proceso_id = this.form_create.id;
        this.modalDialogNext = data;
      }
    },
    openModalUpdateSeguimiento(data, accion, dataRow) {
      this.accion = accion;

      if (accion === "updateNext") {
        this.setFormDataSeguimiento(dataRow);
        this.modalDialogNext = data;
      }
    },
    setFormDataSeguimiento(data) {
      this.formNext = Object.assign(this.formNext, data);
    },
    async loadDataNext() {
      this.setLoading(true);
      return await axios
        .get("/api/seguimientos")
        .then((result) => {
          this.dataNext = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async uploadFiles(file) {
      //this.uploadPercentage = true
      let files = file;

      /*const found = this.procesos.find(
        (element) => element.id == this.form_create.proceso_id
      );*/

      const data = new FormData();
      for (var i = 0; i < files.length; i++) {
        let file = files[i];
        data.append(`files[${i}]`, file);
      }
      data.append("proceso_id", this.formExp.proceso_id);
      data.append("exp", this.form_create.numeroRegistro);

      return await axios
        .post("/api/expediente/", data, {
          headers: { "content-type": "multipart/form-data" },
          processData: false,
          contentType: false,
        })
        .then((result) => {
          //this.uploadPercentage = false
          this.loadDataExpediente();
          successMessage("Los datos se insertaron satisfactoriamente");
          this.closeModal();
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadDataExpediente() {
      this.setLoading(true);
      return await axios
        .get("/api/expedientes")
        .then((result) => {
          this.expedientesAll = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    setFormDataExp(data) {
      this.formExp = Object.assign(this.formExp, data);
    },
    setFormData(data) {
      Object.assign(this.form_create, data);
      this.nameInstalacion = this.form_create.instalacion.nombre;

      if (data.incluyeAlcance == 1) {
        this.form_create.incluyeAlcance = true;
      } else {
        this.form_create.incluyeAlcance = false;
      }

      if (this.form_create.autodiagnostico == 1) {
        this.form_create.autodiagnostico = true;
        this.autodiagnostico = "Yes";
      }
      if (this.form_create.autodiagnostico == 0) {
        this.form_create.autodiagnostico = false;
        this.autodiagnostico = "No";
      }

      if (this.form_create.capacitacionInicial == 1) {
        this.form_create.capacitacionInicial = true;
        this.capacitacionInicial = "Yes";
      }
      if (this.form_create.capacitacionInicial == 0) {
        this.form_create.capacitacionInicial = false;
        this.capacitacionInicial = "No";
      }

      if (this.form_create.fechaOtorgado != null) {
        this.certificar = "Yes";
      } else {
        this.certificar = "No";
      }
    },
    async save() {
      if (this.form_create.incluyeAlcance) {
        this.form_create.alcance = 1;
      }

      /*this.form_create.instalacion_id = sessionStorage.getItem(
        "instalacion_id"
      );*/
      this.form_create.numeroRegistro = "";
      let timeStamp = Date.now();
      let formattedString = date.formatDate(
        timeStamp,
        "YYYY-MM-DDTHH:mm:ss.SSSZ"
      );
      this.form_create.fechaEntregaMintur = formattedString;
      this.form_create.instalacion_id;
      if (this.autodiagnostico == "Yes") {
        this.form_create.autodiagnostico = true;
      }
      if (this.autodiagnostico == "No") {
        this.form_create.autodiagnostico = false;
      }

      if (this.capacitacionInicial == "Yes") {
        this.form_create.capacitacionInicial = true;
      }
      if (this.capacitacionInicial == "No") {
        this.form_create.capacitacionInicial = false;
      }

      return await axios
        .post("/api/proceso_turismo_mas_higienico_seguro", this.form_create)
        .then((result) => {
          this.loadData2();
          successMessage("Los datos se insertaron satisfactoriamente");
          this.closeModal();
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async update() {
      let id = this.form_create.id;
      if (this.autodiagnostico == "Yes") {
        this.form_create.autodiagnostico = true;
      }
      if (this.autodiagnostico == "No") {
        this.form_create.autodiagnostico = false;
      }

      if (this.capacitacionInicial == "Yes") {
        this.form_create.capacitacionInicial = true;
      }
      if (this.capacitacionInicial == "No") {
        this.form_create.capacitacionInicial = false;
      }

      return await axios
        .put(
          "/api/proceso_turismo_mas_higienico_seguro/" + id,
          this.form_create
        )
        .then((result) => {
          Object.assign(this.form_create, result.data.data);
          this.loadData2();
          successMessage("Los datos se actualizaron satisfactoriamente");
          this.closeModal();
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async deleteRow(dataRow) {
      this.confirmDelete(dataRow);
    },
    async deleteRowRequest(dataRow) {
      this.setFormData(dataRow);
      const id = this.form_create.id;
      this.$q.loading.show();
      return await axios
        .delete("/api/proceso_turismo_mas_higienico_seguro/" + id)
        .then((response) => {
          this.reset_form();
          this.$q.loading.hide();
          this.loadData2();
          successMessage("Los datos se eliminaron satisfactoriamente");
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    confirmDelete(dataRow) {
      this.$q
        .dialog({
          title: "Confirme",
          message: "¿Estás seguro de eliminar este proceso?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRowRequest(dataRow);
        })
        .onCancel(() => {});
    },
    async openExp(dataRow) {
      this.folderExp = true;
      this.loadDataExp(dataRow);
    },
    async openListSeguimientoProcess(dataRow) {
      this.listSeguimientoProcess = true;
      this.setFormData(dataRow);
      this.loadDataSeguimientosProcess(dataRow);
    },

    async loadDataExp(dataRow) {
      this.setFormData(dataRow);
      const id = this.form_create.id;
      return await axios
        .get("/api/expedient_process/" + id)
        .then((result) => {
          this.expedientes = result.data;
          this.setFormData(dataRow);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    async loadDataSeguimientosProcess(dataRow) {
      this.setFormData(dataRow);
      const id = this.form_create.id;
      return await axios
        .get("/api/seguimientos_process/" + id)
        .then((result) => {
          this.seguimientosProcess = result.data;
          this.setFormData(dataRow);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    async downloadFile(element) {
      const id = element.id;

      return await axios
        .get("/api/download_file/" + id, { responseType: "blob" })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", element.nombreElemento);
          document.body.appendChild(link);
          link.click();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    deleteRenglones() {
      if (this.selected.length > 0) {
        this.setFormData(this.selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteRenglon(this.form_data.id);
          this.reset_form();
          this.selected = [];
          await this.loadData({
            pagination: this.pagination,
            filter: this.filter,
          });
          this.setLoading(false);
        });
      } else {
        infoMessage("Debe seleccionar la fila a eliminar.");
      }
    },
    async saveNext() {
      return await axios
        .post("/api/seguimiento", this.formNext)
        .then((result) => {
          this.loadDataSeguimientosProcess(this.form_create);
          successMessage("Los datos se insertaron satisfactoriamente");
          this.closeModalSeguimiento();
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async updateNext() {
      let id = this.formNext.id;

      return await axios
        .put("/api/seguimiento/" + id, this.formNext)
        .then((result) => {
          this.loadDataSeguimientosProcess(this.form_create);
          successMessage("Los datos se actualizaron satisfactoriamente");
          this.closeModalSeguimiento();
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async deleteRowSeguimiento(dataRow) {
      this.confirmDeleteSeguimiento(dataRow);
    },
    async deleteRowRequestSequimiento(dataRow) {
      this.setFormDataSeguimiento(dataRow);
      const id = this.formNext.id;
      this.$q.loading.show();
      return await axios
        .delete("/api/seguimiento/" + id)
        .then((response) => {
          this.reset_form_seguimiento();
          this.$q.loading.hide();
          this.loadDataSeguimientosProcess(this.form_create);
          successMessage("Los datos se eliminaron satisfactoriamente");
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    confirmDeleteSeguimiento(dataRow) {
      this.$q
        .dialog({
          title: "Confirme",
          message: "¿Estás seguro de eliminar este seguimiento?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRowRequestSequimiento(dataRow);
        })
        .onCancel(() => {});
    },
    async deleteRowExp(dataRow) {
      this.confirmDeleteExp(dataRow);
    },
    async deleteRowRequestExp(dataRow) {
      this.setFormDataExp(dataRow);
      const id = this.formExp.id;

      this.$q.loading.show();
      return await axios
        .delete("/api/expediente/" + id)
        .then((response) => {
          this.$q.loading.hide();
          //this.closeModal()
          this.loadDataExp(this.form_create);
          successMessage("Los datos se eliminaron satisfactoriamente");
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    confirmDeleteExp(dataRow) {
      this.$q
        .dialog({
          title: "Confirme",
          message: "¿Estás seguro de eliminar este documento?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRowRequestExp(dataRow);
        })
        .onCancel(() => {});
    },

    //Metodos de alcance
    openModalSeeAlcance(data, action, dataRow) {
      this.accion = action;
      if (action === "seeAlcance") {
        this.setFormDataAlcance(dataRow);
        this.nameInstalacionAlcance = this.form_alcance.instalacion.nombre;
        this.modalDialogSeeAlcance = data;
      }
    },
    async openListSeguimientoAlcance(dataRow) {
      Object.assign(this.form_alcance, dataRow);
      this.dialogListAlcance = false;
      this.$root.$emit("openTable");
      this.showForm = true;
    },
    openModalAlcance(data, accion, dataRow) {
      this.accion = accion;
      if (accion === "addAlcance") {
        if (dataRow) {
          this.setFormData(dataRow);
          this.form_alcance.proceso_id = dataRow.id;
          this.modalDialogAlcance = data;
        } else {
          this.setFormData(this.form_create);
          this.form_alcance.proceso_id = this.form_create.id;
          this.modalDialogAlcance = data;
        }
      }
    },
    async openListAlcance(dataRow) {
      this.dialogListAlcance = true;
      this.loadDataAlcances(dataRow);
    },
    setFormDataAlcance(data) {
      this.form_alcance = Object.assign(this.form_alcance, data);

      if (data.incluyeAlcance == 1) {
        this.form_alcance.incluyeAlcance = true;
      } else {
        this.form_alcance.incluyeAlcance = false;
      }

      if (this.form_alcance.autodiagnostico == 1) {
        this.form_alcance.autodiagnostico = true;
        this.autodiagnosticoAlcance = "Yes";
      }
      if (this.form_alcance.autodiagnostico == 0) {
        this.form_alcance.autodiagnostico = false;
        this.autodiagnosticoAlcance = "No";
      }

      if (this.form_alcance.capacitacionInicial == 1) {
        this.form_alcance.capacitacionInicial = true;
        this.capacitacionInicialAlcance = "Yes";
      }
      if (this.form_alcance.capacitacionInicial == 0) {
        this.form_alcance.capacitacionInicial = false;
        this.capacitacionInicialAlcance = "No";
      }

      if (this.form_alcance.fechaOtorgado != null) {
        this.certificarAlcance = "Yes";
      } else {
        this.certificarAlcance = "No";
      }
    },
    async loadDataAlcances(dataRow) {
      if (dataRow) {
        console.log("NO");
        this.setFormData(dataRow);
      } else {
        console.log("YES");
        this.setFormData(this.form_create);
      }
      const id = this.form_create.id;
      console.log("IDD", id);
      return await axios
        .get("/api/alcances/" + id)
        .then((result) => {
          this.alcances = result.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    async saveAlcance() {
      this.form_alcance.numeroRegistro = "";
      let timeStamp = Date.now();
      let formattedString = date.formatDate(
        timeStamp,
        "YYYY-MM-DDTHH:mm:ss.SSSZ"
      );
      this.form_alcance.fechaEntregaMintur = formattedString;

      if (this.autodiagnosticoAlcance == "Yes") {
        this.form_alcance.autodiagnostico = true;
      }
      if (this.autodiagnosticoAlcance == "No") {
        this.form_alcance.autodiagnostico = false;
      }

      if (this.capacitacionInicialAlcance == "Yes") {
        this.form_alcance.capacitacionInicial = true;
      }
      if (this.capacitacionInicialAlcance == "No") {
        this.form_alcance.capacitacionInicial = false;
      }

      return await axios
        .post("/api/alcancetmhs", this.form_alcance)
        .then((result) => {
          this.loadDataAlcances();
          successMessage("Los datos se insertaron satisfactoriamente");
          this.closeModalAlcance();
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    openModalUpdateAlcance(data, accion, dataRow) {
      this.accion = accion;
      if (accion === "actualizar") {
        this.setFormDataAlcance(dataRow);
        this.modalDialogAlcance = data;
      }
    },
    async updateAlcance() {
      let id = this.form_alcance.id;

      if (this.autodiagnosticoAlcance == "Yes") {
        this.form_alcance.autodiagnostico = true;
      }
      if (this.autodiagnosticoAlcance == "No") {
        this.form_alcance.autodiagnostico = false;
      }

      if (this.capacitacionInicialAlcance == "Yes") {
        this.form_alcance.capacitacionInicial = true;
      }
      if (this.capacitacionInicialAlcance == "No") {
        this.form_alcance.capacitacionInicial = false;
      }

      return await axios
        .put("/api/alcancetmhs/" + id, this.form_alcance)
        .then((result) => {
          this.loadDataAlcances();
          successMessage("Los datos se actualizaron satisfactoriamente");
          this.closeModalAlcance();
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    closeModalAlcance() {
      this.modalDialogAlcance = false;
      this.reset_form_alcance();
    },
    reset_form_alcance() {
      this.form_alcance.id = 0;
      //Registro
      this.form_alcance.numeroRegistro = "";
      this.form_alcance.autodiagnostico = null;
      this.form_alcance.capacitacionInicial = null;
      this.form_alcance.fechaEntregaMintur = null;
      this.form_alcance.instalacion_id = null;

      this.form_alcance.agrupacion = "";
      this.form_alcance.alcance = 0;
      this.form_alcance.incluyeAlcance = false;
      //Evaluacion
      this.form_alcance.fechaPrevistaEvaluacion = null;
      this.form_alcance.fechaPrimeraEvaluacion = null;
      this.form_alcance.fechaSegundaEvaluacion = null;
      //Certificado
      this.form_alcance.fechaOtorgado = null;
      this.form_alcance.fechaDenegado = null;
      this.form_alcance.requisitoIncumplido = "";
      //Renovacion
      (this.form_alcance.fechaRenovadoCertificado = null),
        (this.form_alcance.observacion = "");

      //Radio boton
      this.autodiagnosticoAlcance = "";
      this.capacitacionInicialAlcance = "";

      this.certificarAlcance = "";
      //Registro
      this.form_alcance.proceso_id = null;
    },

    async deleteRowAlcance(dataRow) {
      this.confirmDeleteAlcance(dataRow);
    },
    async deleteRowRequestAlcance(dataRow) {
      this.setFormDataAlcance(dataRow);
      const id = this.form_alcance.id;
      this.$q.loading.show();
      return await axios
        .delete("/api/alcancetmhs/" + id)
        .then((response) => {
          this.reset_form_alcance();
          this.$q.loading.hide();
          this.loadDataAlcances();
          successMessage("Los datos se eliminaron satisfactoriamente");
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    confirmDeleteAlcance(dataRow) {
      this.$q
        .dialog({
          title: "Confirme",
          message: "¿Estás seguro de eliminar este alcance?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRowRequestAlcance(dataRow);
        })
        .onCancel(() => {});
    },
    //Fin metodos de alcance

    async openModalGrupoEvaluador(dataRow) {
      Object.assign(this.form_create, dataRow);
      this.$root.$emit("openTableGV");
      this.showGrupoEvaluador = true;
    },
    async openModalDictamen(dataRow) {
      this.reset_dict()
      Object.assign(this.form_create, dataRow);
      
      this.evenEmit.proceso_id = dataRow.id
      this.$root.$emit("openTableDict", this.evenEmit);
      this.showDict = true;
     // this.reset_dict()
    },
     async openModalDictamenEvalTwo(dataRow) {
       this.reset_dict()
      Object.assign(this.form_create, dataRow);

      this.evenEmit.proceso_id = dataRow.id
      this.$root.$emit("openTableDictEvalTwo", this.evenEmit);
      this.showDictEvalTwo = true;
     // this.reset_dict()
    },

    openModalDictamenSeguimiento(dataRow) {
      this.reset_dict()
      Object.assign(this.formNext, dataRow);
      
      this.evenEmit.seguimiento_id = dataRow.id
      this.$root.$emit("openTableDict", this.evenEmit);
      
      this.showDict = true;
     // this.reset_dict()
      
    },
    reset_dict(){
      this.evenEmit.renovacion_id = null
      this.evenEmit.proceso_id = null
      this.evenEmit.seguimiento_id = null
    },
    
    openModalDictamenEvalTwoSeguimiento(dataRow) {
      this.reset_dict()
      Object.assign(this.formNext, dataRow);
      this.evenEmit.seguimiento_id = this.formNext.id
      this.$root.$emit("openTableDictEvalTwo",  this.evenEmit);
      this.showDictEvalTwo = true;
      //this.reset_dict()
    },
     async openModalRenovacion(dataRow) {
      Object.assign(this.form_create, dataRow);
      this.$root.$emit("openTableRenovar");
     // this.showDict = true;
    },
  },
};
</script>

<style scoped>
.stepper-custom.q-stepper {
  box-shadow: 0 0px 0px rgba(0, 0, 0, 0.2), 0 0px 0px rgba(0, 0, 0, 0.14),
    0 0px 0px -2px rgba(0, 0, 0, 0.12);
  border-radius: 4px;
  background: #fff;
}
</style>
