<template>
  <div class="q-ma-md">
    <q-dialog
      v-model="modalDialogTable"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>Dictamen 1ra evaluación</q-toolbar-title>

            <q-btn
              round
              dense
              flat
              icon="close"
              v-close-popup
              @click.prevent="closeModalTable()"
            >
              <q-tooltip>CerrarW</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>

        <q-card-section>
          <div class="row">
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar dictamen 1ra evaluación
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
              dense
              key="add"
              color="primary"
              icon="add"
              @click.prevent="openModal(true, 'add')"
              class="q-mt-sm"
            >
              <q-tooltip>Nuevos datos</q-tooltip>
            </q-btn>
          </div>
        </q-card-section>

        <q-card-section>
          <q-table
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
            size="xs"
            dense
          >
            <template v-slot:body-cell-actions="props">
              <q-td :props="props">
                <div class="q-gutter-xs">
                  <q-btn
                    flat
                    key="see"
                    dense
                    size="sm"
                    text-color="primary"
                    icon="remove_red_eye"
                    @click.prevent="openModalSee(true, props.row)"
                  >
                    <q-tooltip>Ver detalles</q-tooltip>
                  </q-btn>
                  <q-btn
                    v-if="scopes.includes('gestionar_ths')"
                    flat
                    dense
                    size="sm"
                    text-color="primary"
                    icon="edit"
                    @click.prevent="openModalUpdate(true, 'update', props.row)"
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
                </div>
              </q-td>
            </template>

            <template v-slot:loading>
              <q-inner-loading showing color="primary" />
            </template>
          </q-table>
        </q-card-section>
        <!-- /tabla-->
      </q-card>
    </q-dialog>

    <!--Dialog form habilidad evaluacion-->
    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 900px; max-width: 70vw">
        <q-form
          @reset="reset_form"
          @submit="accion != 'add' ? update() : save()"
          ref="form"
        >
          <q-card-section class="row no-padding">
            <q-toolbar class="bg-primary text-white">
              <q-toolbar-title>
                {{
                  this.accion == "add"
                    ? "Adicionar dictamen"
                    : "Actualizar dictamen"
                }}
              </q-toolbar-title>
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

          <q-card-section class="no-padding">
            <q-card-section>
              <q-scroll-area style="height: 418px; max-width: 900px">
                <!--<div class="row q-gutter-y-md">-->
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
                    <q-input
                      v-model="numeroRegistro"
                      :dense="dense"
                      readonly
                      label="Número de registro"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
                    <q-input
                      v-model="instalacion"
                      :dense="dense"
                      readonly
                      label="Instalación"
                    />
                  </div>

                  <div
                    v-if="form.evaluacionInicial"
                    class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm"
                  >
                    <q-input
                      v-model="evaluacionInicial"
                      :dense="dense"
                      readonly
                      label="Evaluación para"
                    />
                  </div>
                  <div
                    v-if="form.seguimientoSemestral"
                    class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm"
                  >
                    <q-input
                      v-model="seguimiento"
                      :dense="dense"
                      readonly
                      label="Evaluación para"
                    />
                  </div>

                  <div
                    v-if="form.renovacion"
                    class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm"
                  >
                    <q-input
                      v-model="renovacion"
                      :dense="dense"
                      readonly
                      label="Evaluación para"
                    />
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm">
                    <q-input
                      label="Primera evaluación"
                      v-model="form.fechaPrimeraEvaluacion"
                       @click="$refs.qDatefechaPrimeraEvaluacion.show()"
                    >
                      <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                          <q-popup-proxy
                            ref="qDatefechaPrimeraEvaluacion"
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-date
                              v-model="form.fechaPrimeraEvaluacion"
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
<div class="row q-mt-sm">
                      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"> 
                         <strong>Resultado de la primera evaluación</strong></div>
                </div>
                <div class="row">
                 <!-- <q-field
                    borderless
                    label="Resultado de la primera evaluación"
                  >
                    <template v-slot:control>
                      <div class="row">-->
                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"
                        >
                          <q-input
                            ref="puntuacionObtenida"
                            v-model="form.puntuacionObtenida"
                            label="Puntuación obtenida *"
                            lazy-rules
                            type="number"
                            :rules="[required]"
                          />
                        </div>
                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"
                        >
                          <q-input
                            maxlength="11"
                            type="number"
                            ref="requisitoIncumplido"
                            v-model="form.requisitoIncumplido"
                            label="Requisito incumplido *"
                            lazy-rules
                            :rules="[required]"
                          />
                        </div>
                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"
                        >
                          <q-input
                            label="Aplicación lista chequeo *"
                            v-model="form.aplicacionListaChequeo"
                            type="textarea"
                            lazy-rules
                            :rules="[required]"
                            autogrow
                          />
                        </div>
                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"
                        >
                          <q-input
                            label="Observación"
                            v-model="form.observacion"
                            type="textarea"
                            autogrow
                          />
                        </div>
                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"
                        >
                          <q-input
                            label="Examen escrito *"
                            v-model="form.examenEscrito"
                            type="textarea"
                            lazy-rules
                            autogrow
                            :rules="[required]"
                          />
                        </div>
                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"
                        >
                          <q-input
                            label="Pregunta oral"
                            v-model="form.preguntaOralConocimiento"
                            type="textarea"
                            autogrow
                          />
                        </div>
                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"
                        >
                          <q-input
                            label="Descripción *"
                            hint="Descripción de la instalación"
                            v-model="form.descripcion"
                            type="textarea"
                            lazy-rules
                            autogrow
                            :rules="[required]"
                          />
                        </div>
                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"
                        >
                          <q-input
                            type="number"
                            ref="personalEvaluar"
                            v-model="form.personalEvaluar"
                            label="Personal a evaluar"
                          />
                        </div>

                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"
                        >
                          <q-input
                            type="number"
                            ref="personalEvaluado"
                            v-model="form.personalEvaluado"
                            label="Personal evaluado"
                          />
                        </div>
                        <div
                          class="col-xs-12 col-sm-12 col-md-6 col-lg-6 q-px-sm"
                        >
                          <q-input
                            type="number"
                            ref="aprobado"
                            v-model="form.aprobado"
                            label="Aprobado"
                          />
                        </div>
                        <div
                          class="col-xs-12 col-sm-12 col-md-12 col-lg-6 q-px-sm"
                        >
                          <q-input
                            type="number"
                            ref="suspenso"
                            v-model="form.suspenso"
                            label="Suspenso"
                          />
                        </div>
                      </div>
                    <!--</template>
                  </q-field>
                </div>-->
<div class="row q-mt-sm">
                      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm"> 
                         <strong>Propuesta</strong></div>
                </div>
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-select
                      hint="Propuesta del equipo evaluador"
                      v-model="propuestaEvaluacionInicial"
                      :options="propuestaEvaluacionInicialList"
                      label="Propuesta"
                      options-dense
                      option-value="id"
                      option-label="name"
                      emit-value
                      map-options
                    >
                    </q-select>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-input
                      ref="nombreApellidos"
                      hint="Nombre y apellidos del usuario que elabora el dictamen"
                      v-model="form.nombreApellidos"
                      label="Nombre y apellidos *"
                      lazy-rules
                      :rules="[required]"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-input
                      ref="elavoradoPor"
                      hint="Grupo evaluador por el que fue elaborado"
                      v-model="form.elavoradoPor"
                      label="Elaborado por *"
                      lazy-rules
                      :rules="[required]"
                    />
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-input
                      label="Cierre del dictamen"
                      v-model="form.fechaCierreDictamen"
                       @click="$refs.qDatefechaCierreDictamen.show()"
                    >
                      <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                          <q-popup-proxy
                            ref="qDatefechaCierreDictamen"
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-date
                              v-model="form.fechaCierreDictamen"
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

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-select
                      hint="Revisado por el delegado territorial del mintur"
                      v-model="aprobadoDelegadoTerritorio"
                      :options="aprobadoDelegadoTerritorioList"
                      label="Revisado por"
                      options-dense
                      option-value="id"
                      option-label="name"
                      emit-value
                      map-options
                    >
                    </q-select>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-input
                      label="Aprobado"
                      hint="Fecha de aprobación por el delegado del territorio"
                      v-model="form.fechaAprobacionDelegadoTerritorio"
                      @click="$refs.qDatefechaAprobacionDelegadoTerritorio.show()"
                    >
                      <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                          <q-popup-proxy
                            ref="qDatefechaAprobacionDelegadoTerritorio"
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-date
                              v-model="form.fechaAprobacionDelegadoTerritorio"
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
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-input
                      ref="nombreApellidosDelegadoTerritorio"
                      hint="Nombre y apellidos del delegado del territorio"
                      v-model="form.nombreApellidosDelegadoTerritorio"
                      label="Delegado"
                    />
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-select
                      hint="Revisado por la autoridad sanitaria del territorio"
                      v-model="aprobadoAutoridadSanitaria"
                      :options="aprobadoAutoridadSanitariaList"
                      label="Revisado por"
                      options-dense
                      option-value="id"
                      option-label="name"
                      emit-value
                      map-options
                    >
                    </q-select>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-input
                      label="Aprobado"
                      hint="Fecha de aprobación de la autoridad sanitaria del territorio"
                      v-model="
                        form.fechaAprobacionAutoridadadSanitariaTerritorio
                      "
                       @click="$refs.qDatefechaAprobacionAutoridadadSanitariaTerritorio.show()"
                    >
                      <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                          <q-popup-proxy
                            ref="qDatefechaAprobacionAutoridadadSanitariaTerritorio"
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-date
                              v-model="
                                form.fechaAprobacionAutoridadadSanitariaTerritorio
                              "
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
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-input
                      ref="nombreApellidosDelegadoTerritorio"
                      v-model="
                        form.nombreApellidosAutoridadadSanitariaTerritorio
                      "
                      label="Sanitario"
                      hint="Nombre y apellidos del sanitario del territorio"
                    />
                  </div>
                  <!--
                <div class="col-xs-5 col-sm-5 col-md-12 col-lg-12 q-pa-md">
                  <strong>Propuesta del equipo evaluador</strong>
                  <q-radio v-model="propuestaSeguimiento" val="mc" label="Mantener certificado" />
                  <q-radio v-model="propuestaSeguimiento" val="stc" label="Suspender temporalmente el certificado" />
                  <q-radio v-model="propuestaSeguimiento" val="cc" label="Cancelar certificado" />
                </div>

                <div class="col-xs-5 col-sm-5 col-md-12 col-lg-12 q-pa-md">
                  <strong>Propuesta del equipo evaluador</strong>
                  <q-radio v-model="propuestaRenovar" val="stc" label="Suspender temporalmente el certificado" />
                  <q-radio v-model="propuestaRenovar" val="cc" label="Cancelar certificado" />
                  <q-radio v-model="propuestaRenovar" val="rc" label="Renovar certificado" />
                </div>
                --></div>
              </q-scroll-area>
            </q-card-section>

            <q-card-actions align="right">
              <q-btn
                type="submit"
                key="guardar"
                label="Guardar"
                flat
                color="primary"
                :icon="accion === 'add' ? 'save' : 'edit'"
              >
              </q-btn>
              <q-btn
                type="button"
                icon="close"
                @click.prevent="closeModal()"
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
    <!--Dialog form habilidad evaluacion-->
    <!--Ver mas datos del habilidad evaluacion-->
    <q-dialog
      v-model="modalDialogSee"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>Datos</q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal()" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>

        <q-card-section>
          <div class="q-pl-md q-pb-md">
            <div class="column" style="height: 150px">
              <div class="col">Mes: {{ form.fechaSeguimientoMensual }}</div>
              <div class="col">
                Semestre: {{ form.fechaSeguimientoSemestral }}
              </div>
              <div class="col">
                Suspensión temporal del certificado:
                {{ form.fechaSuspensionTemporalCertificado }}
              </div>
              <div class="col">
                Retiro de la suspensión temporal del certificado:
                {{ form.fechaRetiroSuspensionTemporalCertificado }}
              </div>
              <div class="col">
                Cancelación de la certificación:
                {{ form.fechaCanceladoCertificado }}
              </div>
              <div class="col">
                Requisito incumplido: {{ form.requisitoIncumplido }}
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
    <!--Fin del ver mas datos del habilidad evaluacion-->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { showDialog } from "../utils/dialogo";
import { infoMessage } from "../utils/notificaciones";
import * as message from "../utils/resources";
import axios from "axios";
import { date } from "quasar";
import { errorMessage, successMessage } from "../utils/notificaciones";

export default {
  name: "DictamenTMHS",
  props: {
    itemSelected: Object,
  },
  data() {
    return {
      scopes: sessionStorage.getItem("scopes"),
      nombreApellidos: "",
      idioma: "",
      instalacion: "",
      numeroRegistro: "",
      filterInput: false,
      loading: false,
      filter: "",
      evaluacionInicial: "Evaluación inicial",
      seguimiento: "Seguimiento semestral",
      renovacion: "Renovación",
      data: [],
      expertos: [],
      idiomas: [],
      tiposEvals: [],
      habilidades: [],
      evaluaciones: [],
      propuestaEvaluacionInicialList: [
        { name: "Otorgado", id: "si" },
        { name: "Denegado", id: "no" },
      ],
      aprobadoDelegadoTerritorioList: [
        { name: "Aprobado (Sí)", id: "si" },
        { name: "Aprobado (No)", id: "no" },
      ],
      aprobadoAutoridadSanitariaList: [
        { name: "Aprobado (Sí)", id: "si" },
        { name: "Aprobado (No)", id: "no" },
      ],
      dense: false,
      modalDialog: false,
      modalDialogTable: false,
      modalDialogSee: false,
      accion: "",
      expanded: false,
      expandedTwo: false,
      propuestaEvaluacionInicial: "",
      propuestaSeguimiento: "",
      propuestaRenovar: "",
      aprobadoDelegadoTerritorio: "",
      aprobadoAutoridadSanitaria: "",

      url: "",
      form: {
        id: 0,
        proceso_id: null,
        seguimiento_id: null,
        renovacion_id: null,
        descripcion: null,
        aplicacionListaChequeo: "", // textarea
        puntuacionObtenida: null, //number
        requisitoIncumplido: "",
        cantidadPuntosCritico: 0, //number optional
        observacion: "", // textarea
        examenEscrito: "", // textarea
        preguntaOralConocimiento: "", // textarea

        evaluacionInicial: false,
        seguimientoSemestral: false,
        renovacion: false,

        fechaPrimeraEvaluacion: "",
        fechaSegundaEvaluacion: "",

        personalEvaluar: null,
        personalEvaluado: null,
        aprobado: null,
        suspenso: null,

        puntuacionObtenidaSegundaEval: null,
        requisitoIncumplidoSegundaEval: "",
        cantidadPuntosCriticoSegundaEval: 0,
        observacionSegundaEval: "",

        otorgadoCertificado: null,

        mantenerCertificado: null,
        suspenderTemporalCertificado: null,
        cancelarCertificado: null,

        renovarCertificado: null,

        elavoradoPor: "",
        nombreApellidos: "",
        fechaCierreDictamen: "",

        aprobadoDelegadoTerritorialMintur: null,
        nombreApellidosDelegadoTerritorio: "",
        fechaAprobacionDelegadoTerritorio: "",

        aprobadoAutoridadadSanitariaTerritorio: null,
        nombreApellidosAutoridadadSanitariaTerritorio: "",
        fechaAprobacionAutoridadadSanitariaTerritorio: "",
        mttipoeval_id: null,
      },
      esJefe: "",
      eval: "",
      columns: [
        {
          name: "numeroRegistro",
          align: "left",
          label: "Número de registro",
          field: (row) => row.proceso.numeroRegistro,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "instalacion",
          align: "left",
          label: "Instalación",
          field: (row) => row.proceso.instalacion.nombre,
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
      required: (val) => !!val || "Por favor, escriba algo",
    };
  },
  created() {
    this.loadTiposEvals();
  },
  mounted() {
    this.$root.$on("openTableDict", (data) => {
      console.log("TEST2", data);
      this.form.proceso_id = data.proceso_id;
      this.form.seguimiento_id = data.seguimiento_id;
      this.form.renovacion_id = data.renovacion_id;

      this.modalDialogTable = true;
      this.loadData();
      this.loadExpertos();
      this.loadIdiomas();
      this.loadHabilidades();
      this.loadEvaluaciones();
    });
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    closeModal() {
      this.$root.$emit("closeTableDict");
      this.reset_form();
      this.modalDialog = false;
    },
    closeModalTable() {
      this.$root.$emit("closeTableDict");
      this.form.proceso_id = null;
      this.form.seguimiento_id = null;
      this.form.renovacion_id = null;

      this.modalDialog = false;
    },
    reset_form() {
      this.form.id = 0;
      //this.form.proceso_id = null;
      this.form.descripcion = null;
      this.form.aplicacionListaChequeo = ""; // textarea
      this.form.puntuacionObtenida = null; //number
      this.form.requisitoIncumplido = "";
      (this.form.cantidadPuntosCritico = 0), //number optional
        (this.form.observacion = ""); // textarea
      this.form.examenEscrito = ""; // textarea
      this.form.preguntaOralConocimiento = ""; // textarea

      this.form.evaluacionInicial = false;
      this.form.seguimientoSemestral = false;
      this.form.renovacion = false;

      this.form.fechaPrimeraEvaluacion = "";
      this.form.fechaSegundaEvaluacion = "";

      this.form.personalEvaluar = null;
      this.form.personalEvaluado = null;
      this.form.aprobado = null;
      this.form.suspenso = null;

      this.form.puntuacionObtenidaSegundaEval = null;
      this.form.requisitoIncumplidoSegundaEval = "";
      this.form.cantidadPuntosCriticoSegundaEval = 0;
      this.form.observacionSegundaEval = "";

      this.form.otorgadoCertificado = null;

      this.form.mantenerCertificado = null;
      this.form.suspenderTemporalCertificado = null;
      this.form.cancelarCertificado = null;

      this.form.renovarCertificado = null;

      this.form.elavoradoPor = "";
      this.form.nombreApellidos = "";
      this.form.fechaCierreDictamen = "";

      this.form.aprobadoDelegadoTerritorialMintur = null;
      this.form.nombreApellidosDelegadoTerritorio = "";
      this.form.fechaAprobacionDelegadoTerritorio = "";

      this.form.aprobadoAutoridadadSanitariaTerritorio = null;
      this.form.nombreApellidosAutoridadadSanitariaTerritorio = "";
      this.form.fechaAprobacionAutoridadadSanitariaTerritorio = "";

      this.aprobadoDelegadoTerritorio = "";
      this.aprobadoAutoridadadSanitariaTerritorio = "";
      this.propuestaEvaluacionInicial = "";
    },
    setFormData(data) {
      Object.assign(this.form, data);
    },
    async save() {
      let evalObj = this.tiposEvals.find((value) => value.codigo == "primera");
      this.form.mttipoeval_id = evalObj.id;
      if (this.aprobadoDelegadoTerritorio == "si") {
        this.form.aprobadoDelegadoTerritorialMintur = true;
      }
      if (this.aprobadoDelegadoTerritorio == "no") {
        this.form.aprobadoDelegadoTerritorialMintur = false;
      }

      if (this.aprobadoAutoridadSanitaria == "si") {
        this.form.aprobadoAutoridadadSanitariaTerritorio = true;
      }
      if (this.aprobadoAutoridadSanitaria == "no") {
        this.form.aprobadoAutoridadadSanitariaTerritorio = false;
      }

      if (this.propuestaEvaluacionInicial == "si") {
        this.form.otorgadoCertificado = true;
      }
      if (this.propuestaEvaluacionInicial == "no") {
        this.form.otorgadoCertificado = false;
      }
      return await axios
        .post("/api/dictamen_tmhs", this.form)
        .then((result) => {
          this.loadData();
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
      if (this.aprobadoDelegadoTerritorio == "si") {
        this.form.aprobadoDelegadoTerritorialMintur = true;
      }
      if (this.aprobadoDelegadoTerritorio == "no") {
        this.form.aprobadoDelegadoTerritorialMintur = false;
      }

      if (this.aprobadoAutoridadSanitaria == "si") {
        this.form.aprobadoAutoridadadSanitariaTerritorio = true;
      }
      if (this.aprobadoAutoridadSanitaria == "no") {
        this.form.aprobadoAutoridadadSanitariaTerritorio = false;
      }

      if (this.propuestaEvaluacionInicial == "si") {
        this.form.otorgadoCertificado = true;
      }
      if (this.propuestaEvaluacionInicial == "no") {
        this.form.otorgadoCertificado = false;
      }
      let id = this.form.id;

      return await axios
        .put("/api/dictamen_tmhs/" + id, this.form)
        .then((result) => {
          this.loadData();
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
      const id = this.form.id;
      this.$q.loading.show();
      return await axios
        .delete("/api/dictamen_tmhs/" + id)
        .then((response) => {
          this.reset_form();
          this.$q.loading.hide();
          this.loadData();
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
          message: "¿Estás seguro de eliminar este dictamen?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRowRequest(dataRow);
        })
        .onCancel(() => {});
    },
    openModal(data, accion) {
      this.estadoPoceso();
      this.accion = accion;
      if (accion === "add") {
        this.modalDialog = data;
      }
    },
    openModalUpdate(data, accion, dataRow) {
      this.estadoPoceso();
      this.accion = accion;

      if (accion === "update") {
        this.setFormData(dataRow);

        console.log("WWW", this.form);
        if (this.form.evaluacionInicial == 1 || this.form.evaluacionInicial) {
          this.form.evaluacionInicial = true;
        } else {
          this.form.evaluacionInicial = false;
        }
        if (
          this.form.seguimientoSemestral == 1 ||
          this.form.seguimientoSemestral
        ) {
          this.form.seguimientoSemestral = true;
        } else {
          this.form.seguimientoSemestral = false;
        }

        if (this.form.renovacion == 1 || this.form.renovacion) {
          this.form.renovacion = true;
        } else {
          this.form.renovacion = false;
        }

        if (
          this.form.otorgadoCertificado == 1 ||
          this.form.otorgadoCertificado
        ) {
          this.form.otorgadoCertificado = true;
          this.propuestaEvaluacionInicial = "si";
        }
        if (
          this.form.otorgadoCertificado == 0 ||
          !this.form.otorgadoCertificado
        ) {
          this.form.otorgadoCertificado = false;
          this.propuestaEvaluacionInicial = "no";
        }

        //Aprobado delegado del territorio
        if (
          this.form.aprobadoDelegadoTerritorialMintur == 1 ||
          this.form.aprobadoDelegadoTerritorialMintur
        ) {
          this.form.aprobadoDelegadoTerritorialMintur = true;
          this.aprobadoDelegadoTerritorio = "si";
        }
        if (
          this.form.aprobadoDelegadoTerritorialMintur == 0 ||
          !this.form.aprobadoDelegadoTerritorialMintur
        ) {
          this.form.aprobadoDelegadoTerritorialMintur = false;
          this.aprobadoDelegadoTerritorio = "no";
        }
        //Autoridad sanitaria
        if (
          this.form.aprobadoAutoridadadSanitariaTerritorio == 1 ||
          this.form.aprobadoAutoridadadSanitariaTerritorio
        ) {
          this.form.aprobadoAutoridadadSanitariaTerritorio = true;
          this.aprobadoAutoridadSanitaria = "si";
        }
        if (
          this.form.aprobadoAutoridadadSanitariaTerritorio == 0 ||
          !this.form.aprobadoAutoridadadSanitariaTerritorio
        ) {
          this.form.aprobadoAutoridadadSanitariaTerritorio = false;
          this.aprobadoAutoridadSanitaria = "no";
        }
        this.modalDialog = data;
      }
    },
    async loadData() {
      this.numeroRegistro = this.itemSelected.numeroRegistro;
      this.instalacion = this.itemSelected.instalacion.nombre;
      // this.form.proceso_id = this.itemSelected.id;
      let evalObj = this.tiposEvals.find((value) => value.codigo == "primera");
      this.form.mttipoeval_id = evalObj.id;

      console.log("wwww", this.form.proceso_id);
      if (this.form.proceso_id != null) {
        this.url = "/api/dictamen_evaluacion/";
      }
      if (this.form.seguimiento_id != null) {
        this.url = "/api/dictamen_seguimiento/";
      }
      if (this.form.renovacion_id != null) {
        this.url = "/api/dictamen_renovacion/";
      }
      this.form.proceso_id = this.itemSelected.id;
      this.estadoPoceso();
      return await axios
        .post(this.url, this.form)
        .then((result) => {
          this.data = result.data;
          console.log("EER", this.data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    async loadExpertos() {
      return await axios
        .get("/api/ficha_expertos")
        .then((response) => {
          this.expertos = response.data.data;
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
    async loadIdiomas() {
      return await axios
        .get("/api/idiomas")
        .then((response) => {
          this.idiomas = response.data.data;
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
    async loadTiposEvals() {
      return await axios
        .get("/api/tipos_evals")
        .then((response) => {
          this.tiposEvals = response.data.data;
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
    async loadHabilidades() {
      return await axios
        .get("/api/habilidades")
        .then((response) => {
          this.habilidades = response.data.data;
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
    async loadEvaluaciones() {
      return await axios
        .get("/api/evaluaciones")
        .then((response) => {
          this.evaluaciones = response.data.data;
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
    openModalSee(data, dataRow) {
      this.setFormData(dataRow);
      this.modalDialogSee = data;
    },
    estadoPoceso() {
      if (this.itemSelected.id != 0 || this.itemSelected.id != null) {
        this.form.evaluacionInicial = true;
      }
      if (
        this.itemSelected.fechaOtorgado ||
        this.itemSelected.fechaRenovadoCertificado
      ) {
        this.form.seguimientoSemestral = true;
      }
      if (this.itemSelected.fechaOtorgado) {
        this.form.renovacion = true;
      }
    },
  },
};
</script>
