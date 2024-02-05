<template>
  <div class="q-pa-md">
    <q-toolbar class="my-toolbars bg-primary text-white">
      <q-toolbar-title>
        Gestionar mantenimiento de entidad
      </q-toolbar-title>
      <q-space></q-space>
      <transition-group
        appear
        enter-active-class="animated fadeIn"
        leave-active-class="animated fadeOut"
      >
        <q-btn
          type="reset"
          key="add2"
          label="Anexo 2"
          flat
          color="white"
          @click.prevent="modalDialog = true"
        >
          <q-tooltip>Anexo 2</q-tooltip>
        </q-btn>
        <q-btn
          type="reset"
          key="add3"
          label="Anexo 3"
          flat
          color="white"
          @click.prevent="modalDialogAnexo3 = true"
        >
          <q-tooltip>Anexo 3</q-tooltip>
        </q-btn>
      </transition-group>
    </q-toolbar>
    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
      maximized
    >
      <q-card>
        <q-card-section class="no-padding">
          <q-card-section class="row no-padding">
            <q-toolbar class="bg-primary text-white">
              <q-toolbar-title>
                Anexo No. 2 de la Resolución No. 150 del 2010
              </q-toolbar-title>
              <q-btn
                round
                dense
                flat
                icon="close"
                @click="closeModalTable"
                v-close-popup
              >
                <q-tooltip>Cerrar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </q-card-section>
          <q-card-section>
            <div class="row">
              <div class="col">
                <div class="q-pa-md" style="max-width: 300px;">
                  <div class="q-gutter-md">
                    <q-input
                      color="blue-12"
                      size="xs"
                      dense
                      v-model="form_mante.mes"
                      label="Acumulado, mes de:"
                    >
                      <template v-slot:prepend>
                        <q-icon name="event" />
                      </template>
                    </q-input>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="q-pa-md" style="max-width: 300px;">
                  <div class="q-gutter-md">
                    <q-input
                      color="blue-12"
                      size="xs"
                      dense
                      v-model="form_mante.anno"
                      label="Año:"
                    >
                      <template v-slot:prepend>
                        <q-icon name="event" />
                      </template>
                    </q-input>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="q-pa-md" style="max-width: 300px;">
                  <div class="q-gutter-md">
                    <q-input
                      color="blue-12"
                      size="xs"
                      dense
                      v-model="entidad"
                      label="Entidad:"
                    >
                    </q-input>
                  </div>
                </div>
              </div>
            </div>
            <div class="q-pa-md">
              <q-table
                style="height: 600px;"
                title="Mantenimiento agrupado por elementos de gastos"
                :data="datas"
                dense
                :rows-per-page-options="[35]"
                :columns="columns"
                row-key="name"
                binary-state-sort
              >
                <template v-slot:body="props">
                  <q-tr :props="props">
                    <q-td key="descMtto" :props="props">
                      {{ props.row.descMtto }}
                      <q-popup-edit v-model="props.row.descMtto">
                        <q-input
                          v-model="props.row.descMtto"
                          dense
                          autofocus
                          counter
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="unidadMedida" :props="props">
                      {{ props.row.unidadMedida }}
                    </q-td>
                    <q-td key="accPrevTPlan" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.accPrevTPlan }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.accPrevTPlan"
                        title="Actualizar Acciones Total Plan"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="props.row.descMtto === 'Total'"
                          v-model="props.row.accPrevTPlan"
                          min="0"
                          dense
                          autofocus
                          @input="porcientoAptpp"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="accPrevTReal" :props="props">
                      {{ props.row.accPrevTReal }}
                      <q-popup-edit
                        v-model="props.row.accPrevTReal"
                        title="Actualizar Acciones Total Real"
                        buttons
                      >
                        <q-input
                          type="number"
                          v-model="props.row.accPrevTReal"
                          :readonly="props.row.descMtto === 'Total'"
                          min="0"
                          dense
                          autofocus
                          @input="porcientoAptpp"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="accPrevTPorCiento" :props="props">
                      {{ props.row.accPrevTPorCiento }}
                    </q-td>
                    <q-td key="accPrevCPlan" :props="props">
                      {{ props.row.accPrevCPlan }}
                      <q-popup-edit
                        v-model="props.row.accPrevCPlan"
                        title="Actualizar Acciones Contratado Plan"
                        buttons
                      >
                        <q-input
                          type="number"
                          v-model="props.row.accPrevCPlan"
                          :readonly="props.row.descMtto === 'Total'"
                          min="0"
                          dense
                          autofocus
                          @input="pocientoAptcc"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="accPrevCReal" :props="props">
                      {{ props.row.accPrevCReal }}
                      <q-popup-edit
                        v-model="props.row.accPrevCReal"
                        title="Actualizar Acciones Contratado Real"
                        buttons
                      >
                        <q-input
                          type="number"
                          v-model="props.row.accPrevCReal"
                          :readonly="props.row.descMtto === 'Total'"
                          min="0"
                          dense
                          autofocus
                          @input="pocientoAptcc"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="accPrevCPorCiento" :props="props">
                      {{ props.row.accPrevCPorCiento }}
                    </q-td>
                    <q-td key="impTotal" :props="props">
                      {{ props.row.impTotal }}
                      <q-popup-edit
                        v-model="props.row.impTotal"
                        title="Imprevisto Total"
                        buttons
                      >
                        <q-input
                          type="number"
                          v-model="props.row.impTotal"
                          :readonly="props.row.descMtto === 'Total'"
                          min="0"
                          dense
                          autofocus
                          @input="porciento"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="impContratado" :props="props">
                      {{ props.row.impContratado }}
                      <q-popup-edit
                        v-model="props.row.impContratado"
                        title="Imprevisto Contratado"
                        buttons
                      >
                        <q-input
                          type="number"
                          v-model="props.row.impContratado"
                          :readonly="props.row.descMtto === 'Total'"
                          min="0"
                          dense
                          autofocus
                          @input="porciento"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="totalAccMtto" :props="props">
                      {{ props.row.totalAccMtto }}
                    </q-td>
                    <q-td key="porCientoImpAcc" :props="props">
                      {{ props.row.porCientoImpAcc }}
                    </q-td>
                  </q-tr>
                </template>
              </q-table>
            </div>
            <div class="q-pa-md row">
              <div class="col-4 q-px-sm">
                <q-input
                  type="number"
                  v-model="form_mante.hdd"
                  label="Habitaciones Dias Disponibles (HDD)"
                  min="0"
                  dense
                  autofocus
                  @input="porcientoHdf"
                />
              </div>
              <div class="col-4 q-px-sm">
                <q-input
                  type="number"
                  v-model="form_mante.hfo"
                  label="Habitaciones Fuera de Orden por Mantenimiento (HFO)"
                  min="0"
                  dense
                  autofocus
                  @input="porcientoHdf"
                />
              </div>
              <div class="col-4 q-px-sm">
                <q-input
                  type="number"
                  v-model="form_mante.porcientoH"
                  label="% HFO / HDD"
                  readonly
                  dense
                  autofocus
                />
              </div>
            </div>
          </q-card-section>
        </q-card-section>
        <q-card-actions class="q-pa-md" align="right">
          <q-btn
            type="button"
            label="Enviar Anexo"
            color="primary"
            icon="save"
            style="margin-bottom: 30px;"
            @click.prevent="enviarAnexoDos()"
          />
          <q-btn
            type="button"
            icon="close"
            @click="closeModalTable()"
            label="Cancelar"
            v-close-popup
            color="red"
            style="margin-right: 10px; margin-bottom: 30px;"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog
      v-model="modalDialogAnexo3"
      persistent
      transition-show="scale"
      transition-hide="scale"
      maximized
    >
      <q-card>
        <q-card-section class="no-padding">
          <q-card-section class="row no-padding">
            <q-toolbar class="bg-primary text-white">
              <q-toolbar-title>
                Anexo No.3 de la Resolución No. 150 de 2010
              </q-toolbar-title>
              <q-btn
                round
                dense
                flat
                icon="close"
                @click="closeModalTableAnexoTres"
                v-close-popup
              >
                <q-tooltip>Cerrar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </q-card-section>
          <q-card-section>
            <div class="row">
              <div class="col">
                <div class="q-pa-md" style="max-width: 300px;">
                  <div class="q-gutter-md">
                    <q-input
                      color="blue-12"
                      size="xs"
                      dense
                      v-model="form_mante.mes"
                      label="Acumulado, mes de:"
                    >
                      <template v-slot:prepend>
                        <q-icon name="event" />
                      </template>
                    </q-input>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="q-pa-md" style="max-width: 300px;">
                  <div class="q-gutter-md">
                    <q-input
                      color="blue-12"
                      size="xs"
                      dense
                      v-model="form_mante.anno"
                      label="Año:"
                    >
                      <template v-slot:prepend>
                        <q-icon name="event" />
                      </template>
                    </q-input>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="q-pa-md" style="max-width: 300px;">
                  <div class="q-gutter-md">
                    <q-input
                      color="blue-12"
                      size="xs"
                      dense
                      v-model="entidad"
                      label="Entidad:"
                    >
                    </q-input>
                  </div>
                </div>
              </div>
            </div>
            <div class="q-pa-md">
              <q-table
                title="Resumen de mantenimiento"
                :data="datasAnexo3"
                dense
                :rows-per-page-options="[35]"
                :columns="columnsAnexo3"
                row-key="name"
                binary-state-sort
              >
                <template v-slot:body="props">
                  <q-tr :props="props">
                    <q-td key="desc" :props="props">
                      {{ props.row.nombre }}
                    </q-td>
                    <q-td key="unidadMedida" :props="props">
                      {{ props.row.unidadMedida }}
                    </q-td>
                    <q-td key="prespMttoTotal" :props="props">
                      {{ props.row.prespMttoTotal }}
                      <q-popup-edit
                        v-model="props.row.prespMttoTotal"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.prespMttoTotal"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="prespMttoT1" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.prespMttoT1 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.prespMttoT1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales'
                          "
                          v-model="props.row.prespMttoT1"
                          min="0"
                          @input="anexoTresDatosUno"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="prespMttoT2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.prespMttoT2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.prespMttoT2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales'
                          "
                          v-model="props.row.prespMttoT2"
                          min="0"
                          @input="anexoTresDatosUno"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="prespMttoContrat" :props="props">
                      {{ props.row.prespMttoContrat }}
                      <q-popup-edit
                        v-model="props.row.prespMttoContrat"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.prespMttoContrat"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="prespMttoC1" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.prespMttoC1 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.prespMttoC1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales'
                          "
                          v-model="props.row.prespMttoC1"
                          min="0"
                          @input="anexoTresDatosDos"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="prespMttoC2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.prespMttoC2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.prespMttoC2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales'
                          "
                          v-model="props.row.prespMttoC2"
                          min="0"
                          @input="anexoTresDatosDos"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoTotal" :props="props">
                      {{ props.row.realMttoTotal }}
                      <q-popup-edit
                        v-model="props.row.realMttoTotal"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.realMttoTotal"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoT1" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.realMttoT1 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.realMttoT1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales'
                          "
                          v-model="props.row.realMttoT1"
                          min="0"
                          @input="anexoTresDatosTres"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoT2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.realMttoT2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.realMttoT2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales'
                          "
                          v-model="props.row.realMttoT2"
                          min="0"
                          @input="anexoTresDatosTres"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoContrat" :props="props">
                      {{ props.row.realMttoContrat }}
                      <q-popup-edit
                        v-model="props.row.realMttoContrat"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.realMttoContrat"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoC1" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.realMttoC1 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.realMttoC1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales'
                          "
                          v-model="props.row.realMttoC1"
                          min="0"
                          @input="anexoTresDatosCuatro"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoC2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.realMttoC2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.realMttoC2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales'
                          "
                          v-model="props.row.realMttoC2"
                          min="0"
                          @input="anexoTresDatosCuatro"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="porCientoMttoTot1" :props="props">
                      {{ props.row.porCientoMttoTot1 }}
                      <q-popup-edit
                        v-model="props.row.porCientoMttoTot1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.porCientoMttoTot1"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="porCientoMttoTot2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.porCientoMttoTot2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.porCientoMttoTot2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.porCientoMttoTot2"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="porCientoMttoTot3" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.porCientoMttoTot3 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.porCientoMttoTot3"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.porCientoMttoTot3"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="porCientoMttoContrat1" :props="props">
                      {{ props.row.porCientoMttoContrat1 }}
                      <q-popup-edit
                        v-model="props.row.porCientoMttoContrat1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.porCientoMttoContrat1"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="porCientoMttoContrat2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.porCientoMttoContrat2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.porCientoMttoContrat2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.porCientoMttoContrat2"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="porCientoMttoContrat3" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.porCientoMttoContrat3 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.porCientoMttoContrat3"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre === 'Gasto de Mantenimiento Total'
                          "
                          v-model="props.row.porCientoMttoContrat3"
                          min="0"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                  </q-tr>
                </template>
              </q-table>
              <q-table
                title="GASTO DE MANTENIMIENTO AGRUPADOS POR SUB-ELEMENTOS DE GASTOS"
                :data="datasAnexoTabla"
                dense
                :rows-per-page-options="[35]"
                :columns="columnsAnexoTabla"
                row-key="name"
                binary-state-sort
              >
                <template v-slot:body="props">
                  <q-tr :props="props">
                    <q-td key="desc" :props="props">
                      {{ props.row.nombre }}
                    </q-td>
                    <q-td key="unidadMedida" :props="props">
                      {{ props.row.unidadMedida }}
                    </q-td>
                    <q-td key="prespMttoTotal" :props="props">
                      {{ props.row.prespMttoTotal }}
                    </q-td>
                    <q-td key="prespMttoT1" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.prespMttoT1 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.prespMttoT1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="props.row.nombre === 'Total'"
                          v-model="props.row.prespMttoT1"
                          min="0"
                          dense
                          autofocus
                          @input="calculoDePmtTablaTres"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="prespMttoT2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.prespMttoT2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.prespMttoT2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="props.row.nombre === 'Total'"
                          v-model="props.row.prespMttoT2"
                          min="0"
                          dense
                          autofocus
                          @input="calculoDePmtTablaTres"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="prespMttoContrat" :props="props">
                      {{ props.row.prespMttoContrat }}
                    </q-td>
                    <q-td key="prespMttoC1" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.prespMttoC1 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.prespMttoC1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="props.row.nombre === 'Total'"
                          v-model="props.row.prespMttoC1"
                          min="0"
                          dense
                          autofocus
                          @input="calculoDePmcTablaTres"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="prespMttoC2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.prespMttoC2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.prespMttoC2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="props.row.nombre === 'Total'"
                          v-model="props.row.prespMttoC2"
                          min="0"
                          dense
                          autofocus
                          @input="calculoDePmcTablaTres"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoTotal" :props="props">
                      {{ props.row.realMttoTotal }}
                    </q-td>
                    <q-td key="realMttoT1" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.realMttoT1 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.realMttoT1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="props.row.nombre === 'Total'"
                          v-model="props.row.realMttoT1"
                          min="0"
                          dense
                          autofocus
                          @input="calculoDeRmtTablaTres"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoT2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.realMttoT2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.realMttoT2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="props.row.nombre === 'Total'"
                          v-model="props.row.realMttoT2"
                          min="0"
                          dense
                          autofocus
                          @input="calculoDeRmtTablaTres"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoContrat" :props="props">
                      {{ props.row.realMttoContrat }}
                    </q-td>
                    <q-td key="realMttoC1" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.realMttoC1 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.realMttoC1"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="
                            props.row.nombre === 'Total' ||
                            props.row.nombre === 'INDICADORES SELECCIONADOS' ||
                            props.row.nombre === 'Ingresos Totales' ||
                            props.row.nombre === 'Costos y Gastos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Ingresos Totales' ||
                            props.row.nombre ===
                              'Gastos de Mtto Total / Costos y Gastos Totales'
                          "
                          v-model="props.row.realMttoC1"
                          min="0"
                          dense
                          autofocus
                          @input="calculoDeRmcTablaTres"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="realMttoC2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.realMttoC2 }}
                      </div>
                      <q-popup-edit
                        v-model="props.row.realMttoC2"
                        title="Actualizar campo"
                        buttons
                      >
                        <q-input
                          type="number"
                          :readonly="props.row.nombre === 'Total'"
                          v-model="props.row.realMttoC2"
                          min="0"
                          dense
                          autofocus
                          @input="calculoDeRmcTablaTres"
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td key="porCientoMttoTot1" :props="props">
                      {{ props.row.porCientoMttoTot1 }}
                    </q-td>
                    <q-td key="porCientoMttoTot2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.porCientoMttoTot2 }}
                      </div>
                    </q-td>
                    <q-td key="porCientoMttoTot3" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.porCientoMttoTot3 }}
                      </div>
                    </q-td>
                    <q-td key="porCientoMttoContrat1" :props="props">
                      {{ props.row.porCientoMttoContrat1 }}
                    </q-td>
                    <q-td key="porCientoMttoContrat2" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.porCientoMttoContrat2 }}
                      </div>
                    </q-td>
                    <q-td key="porCientoMttoContrat3" :props="props">
                      <div class="text-pre-wrap">
                        {{ props.row.porCientoMttoContrat3 }}
                      </div>
                    </q-td>
                  </q-tr>
                </template>
              </q-table>
            </div>
          </q-card-section>
        </q-card-section>
        <q-card-actions class="q-pa-md" align="right">
          <q-btn
            type="button"
            label="Enviar Anexo"
            color="primary"
            icon="save"
            style="margin-bottom: 30px;"
            @click.prevent="enviarAnexoTres()"
          />
          <q-btn
            type="button"
            icon="close"
            @click="closeModalTableAnexoTres()"
            label="Cancelar"
            v-close-popup
            color="red"
            style="margin-right: 10px; margin-bottom: 30px;"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { errorMessage, warningMessage } from '../../utils/notificaciones';
import Vue from 'vue';
import * as message from '../../utils/resources';
import { mapActions } from 'vuex';

export default {
  name: 'gestionMantenimiento',
  data() {
    return {
      columns: [
        {
          name: 'descMtto',
          required: true,
          align: 'left',
          field: (row) => row.descMtto,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: 'unidadMedida',
          align: 'left',
          label: 'Unidad de Medida',
          field: 'unidadMedida',
          sortable: true,
          headerStyle: 'width: 500px',
          headerClasses: 'my-special-class',
        },
        {
          name: 'accPrevTPlan',
          label: 'Acciones Preventivas-Total-Plan',
          field: 'accPrevTPlan',
          sortable: true,
          style: 'width: 5px',
          align: 'center',
        },
        {
          name: 'accPrevTReal',
          label: 'Acciones Preventivas-Total-Real',
          field: 'accPrevTReal',
          align: 'center',
        },
        {
          name: 'accPrevTPorCiento',
          label: 'Acciones Preventivas-Total- %',
          field: 'accPrevTPorCiento',
          align: 'center',
          classes: 'bg-grey-2 ellipsis',
          style: 'max-width: 100px',
          headerClasses: 'bg-primary text-white',
        },
        {
          name: 'accPrevCPlan',
          label: 'Acciones Preventivas-Contratado-Plan',
          field: 'accPrevCPlan',
          align: 'center',
        },
        {
          name: 'accPrevCReal',
          label: 'Acciones Preventivas-Contratado-Real',
          align: 'center',
          field: 'accPrevCReal',
          sortable: true,
        },
        {
          name: 'accPrevCPorCiento',
          label: 'Acciones Preventivas-Contratado- %',
          align: 'center',
          field: 'accPrevCPorCiento',
          sortable: true,
          classes: 'bg-grey-2 ellipsis',
          style: 'max-width: 100px',
          headerClasses: 'bg-primary text-white',
        },
        {
          name: 'impTotal',
          label: 'Imprevisto-Total',
          field: 'impTotal',
          align: 'center',
          sortable: true,
        },
        {
          name: 'impContratado',
          label: 'Imprevisto-Contratado',
          field: 'impContratado',
          align: 'center',
          sortable: true,
        },
        {
          name: 'totalAccMtto',
          label: 'Total-Acciones-Mtto',
          field: 'totalAccMtto',
          align: 'center',
          sortable: true,
          classes: 'bg-grey-2 ellipsis',
          style: 'max-width: 100px',
          headerClasses: 'bg-primary text-white',
        },
        {
          name: 'porCientoImpAcc',
          label: '% (Impre/Total-Acc. Mttp.)',
          field: 'porCientoImpAcc',
          align: 'center',
          sortable: true,
          classes: 'bg-grey-2 ellipsis',
          style: 'max-width: 100px',
          headerClasses: 'bg-primary text-white',
        },
      ],
      datas: [
        {
          descMtto: 'Mantenimiento a edificaciones',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a mobiliario',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a redes técnicas',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a fachadas y pintura exterior',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a accesos y viales',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a áreas verdes',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a ascensores',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipos de refrigeración',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a grupos electrógenos',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipos gastronómicos',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipos de tintorería y lavandería',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipos de computación',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a paneles eléctricos',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a sistemas de iluminación',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a sistemas y equipos de piscinas',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto:
            'Mantenimiento a sistemas y equipos de ventilación y extracción',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto:
            'Mantenimiento a sistemas y equipos de tratamiento de residuales',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a sistemas y equipos de bombeo',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto:
            'Mantenimiento a sistemas y equipos electrónicos y de comunicaciones',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a sistemas y equipos de climatización',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto:
            'Mantenimiento a sistemas y equipos de protección y seguridad',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto:
            'Mantenimiento a sistemas y equipos de almacenam. de combustible y agua',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto:
            'Mantenimiento a sistemas y equipos de producción de vapor y agua caliente',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipamiento náutico',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipamiento de espectáculo',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipos de medición',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipos de taller',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipos de transporte automotor',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a embarcaciones',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Mantenimiento a equipos de transporte de mecanización',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Otros servicios de mantenimiento',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
        {
          descMtto: 'Total',
          unidadMedida: 'Unidad',
          accPrevTPlan: 0,
          accPrevTReal: 0,
          accPrevTPorCiento: 0,
          accPrevCPlan: 0,
          accPrevCReal: 0,
          accPrevCPorCiento: 0,
          impTotal: 0,
          impContratado: 0,
          totalAccMtto: 0,
          porCientoImpAcc: 0,
        },
      ],
      datasAnexo3: [
        {
          descMtto: 'Ingresos Totales',
          unidadMedida: 'Miles',
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoTotal: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          prespMttoContrat: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoTotal: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          realMttoContrat: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          descMtto: 'Costos y Gastos Totales',
          unidadMedida: 'Miles',
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoTotal: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          prespMttoContrat: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoTotal: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          realMttoContrat: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Gasto de Mantenimiento Total',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'INDICADORES SELECCIONADOS',
          unidadMedida: '-',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Gastos de Mtto Total / Ingresos Totales',
          unidadMedida: '%',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Gastos de Mtto Total / Costos y Gastos Totales',
          unidadMedida: '%',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Gastos de Mtto Total / Hab. Dias Disponibles',
          unidadMedida: 'UM/hab',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
      ],
      datasAnexoTabla: [
        {
          nombre: 'Mantenimiento a edificaciones',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a mobiliario',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a redes técnicas',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a fachadas y pintura exterior',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a accesos y viales',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a áreas verdes',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a ascensores',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipos de refrigeración',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a grupos electrógenos',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipos gastronómicos',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipos de tintorería y lavandería',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipos de computación',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a paneles eléctricos',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a sistemas de iluminación',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a sistemas y equipos de piscinas',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre:
            'Mantenimiento a sistemas y equipos de ventilación y extracción',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre:
            'Mantenimiento a sistemas y equipos de tratamiento de residuales',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a sistemas y equipos de bombeo',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre:
            'Mantenimiento a sistemas y equipos electrónicos y de comunicaciones',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a sistemas y equipos de climatización',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre:
            'Mantenimiento a sistemas y equipos de protección y seguridad',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre:
            'Mantenimiento a sistemas y equipos de almacenam. de combustible y agua',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre:
            'Mantenimiento a sistemas y equipos de producción de vapor y agua caliente',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipamiento náutico',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipamiento de espectáculo',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipos de medición',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipos de taller',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipos de transporte automotor',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a embarcaciones',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Mantenimiento a equipos de transporte de mecanización',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Otros servicios de mantenimiento',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
        {
          nombre: 'Total',
          unidadMedida: 'Miles',
          prespMttoTotal: 0,
          prespMttoT1: 0,
          prespMttoT2: 0,
          prespMttoContrat: 0,
          prespMttoC1: 0,
          prespMttoC2: 0,
          realMttoTotal: 0,
          realMttoT1: 0,
          realMttoT2: 0,
          realMttoContrat: 0,
          realMttoC1: 0,
          realMttoC2: 0,
          porCientoMttoTot1: 0,
          porCientoMttoTot2: 0,
          porCientoMttoTot3: 0,
          porCientoMttoContrat1: 0,
          porCientoMttoContrat2: 0,
          porCientoMttoContrat3: 0,
        },
      ],
      columnsAnexo3: [
        {
          name: 'desc',
          required: true,
          align: 'left',
          field: (row) => row.nombre,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: 'unidadMedida',
          align: 'center',
          label: 'Unidad de medida',
          field: 'unidadMedida',
          sortable: true,
        },
        {
          name: 'prespMttoTotal',
          align: 'center',
          label: 'Presupuesto Mant-Total',
          field: 'prespMttoTotal',
          sortable: true,
        },
        {
          name: 'prespMttoT1',
          align: 'center',
          label: '',
          field: 'prespMttoT1',
          sortable: true,
        },
        {
          name: 'prespMttoT2',
          align: 'center',
          label: '',
          field: 'prespMttoT2',
          sortable: true,
        },
        {
          name: 'prespMttoContrat',
          align: 'center',
          label: 'Presupuesto Mant-Contratado',
          field: 'prespMttoContrat',
          sortable: true,
        },
        {
          name: 'prespMttoC1',
          align: 'center',
          label: '',
          field: 'prespMttoC1',
          sortable: true,
        },
        {
          name: 'prespMttoC2',
          align: 'center',
          label: '',
          field: 'prespMttoC2',
          sortable: true,
        },
        {
          name: 'realMttoTotal',
          align: 'center',
          label: 'Real Mant-Total',
          field: 'realMttoTotal',
          sortable: true,
        },
        {
          name: 'realMttoT1',
          align: 'center',
          label: '',
          field: 'realMttoT1',
          sortable: true,
        },
        {
          name: 'realMttoT2',
          align: 'center',
          label: '',
          field: 'realMttoT2',
          sortable: true,
        },
        {
          name: 'realMttoContrat',
          align: 'center',
          label: 'Real Mant-Contratado',
          field: 'realMttoContrat',
          sortable: true,
        },
        {
          name: 'realMttoC1',
          align: 'center',
          label: '',
          field: 'realMttoC1',
          sortable: true,
        },
        {
          name: 'realMttoC2',
          align: 'center',
          label: '',
          field: 'realMttoC2',
          sortable: true,
        },
        {
          name: 'porCientoMttoTot1',
          align: 'center',
          label: '% Ejecución-Total',
          field: 'porCientoMttoTot1',
          sortable: true,
        },
        {
          name: 'porCientoMttoTot2',
          align: 'center',
          label: '',
          field: 'porCientoMttoTot2',
          sortable: true,
        },
        {
          name: 'porCientoMttoTot3',
          align: 'center',
          label: '',
          field: 'porCientoMttoTot3',
          sortable: true,
        },
        {
          name: 'porCientoMttoContrat1',
          align: 'center',
          label: '% Ejecución-Contratado',
          field: 'porCientoMttoContrat1',
          sortable: true,
        },
        {
          name: 'porCientoMttoContrat2',
          align: 'center',
          label: '',
          field: 'porCientoMttoContrat2',
          sortable: true,
        },
        {
          name: 'porCientoMttoContrat3',
          align: 'center',
          label: '',
          field: 'porCientoMttoContrat3',
          sortable: true,
        },
      ],
      columnsAnexoTabla: [
        {
          name: 'desc',
          required: true,
          align: 'left',
          field: (row) => row.nombre,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: 'unidadMedida',
          align: 'center',
          label: 'Unidad de medida',
          field: 'unidadMedida',
          sortable: true,
        },
        {
          name: 'prespMttoTotal',
          align: 'center',
          label: 'Presupuesto Mant-Total',
          field: 'prespMttoTotal',
          sortable: true,
        },
        {
          name: 'prespMttoT1',
          align: 'center',
          label: '',
          field: 'prespMttoT1',
          sortable: true,
        },
        {
          name: 'prespMttoT2',
          align: 'center',
          label: '',
          field: 'prespMttoT2',
          sortable: true,
        },
        {
          name: 'prespMttoContrat',
          align: 'center',
          label: 'Presupuesto Mant-Contratado',
          field: 'prespMttoContrat',
          sortable: true,
        },
        {
          name: 'prespMttoC1',
          align: 'center',
          label: '',
          field: 'prespMttoC1',
          sortable: true,
        },
        {
          name: 'prespMttoC2',
          align: 'center',
          label: '',
          field: 'prespMttoC2',
          sortable: true,
        },
        {
          name: 'realMttoTotal',
          align: 'center',
          label: 'Real Mant-Total',
          field: 'realMttoTotal',
          sortable: true,
        },
        {
          name: 'realMttoT1',
          align: 'center',
          label: '',
          field: 'realMttoT1',
          sortable: true,
        },
        {
          name: 'realMttoT2',
          align: 'center',
          label: '',
          field: 'realMttoT2',
          sortable: true,
        },
        {
          name: 'realMttoContrat',
          align: 'center',
          label: 'Real Mant-Contratado',
          field: 'realMttoContrat',
          sortable: true,
        },
        {
          name: 'realMttoC1',
          align: 'center',
          label: '',
          field: 'realMttoC1',
          sortable: true,
        },
        {
          name: 'realMttoC2',
          align: 'center',
          label: '',
          field: 'realMttoC2',
          sortable: true,
        },
        {
          name: 'porCientoMttoTot1',
          align: 'center',
          label: '% Ejecución-Total',
          field: 'porCientoMttoTot1',
          sortable: true,
        },
        {
          name: 'porCientoMttoTot2',
          align: 'center',
          label: '',
          field: 'porCientoMttoTot2',
          sortable: true,
        },
        {
          name: 'porCientoMttoTot3',
          align: 'center',
          label: '',
          field: 'porCientoMttoTot3',
          sortable: true,
        },
        {
          name: 'porCientoMttoContrat1',
          align: 'center',
          label: '% Ejecución-Contratado',
          field: 'porCientoMttoContrat1',
          sortable: true,
        },
        {
          name: 'porCientoMttoContrat2',
          align: 'center',
          label: '',
          field: 'porCientoMttoContrat2',
          sortable: true,
        },
        {
          name: 'porCientoMttoContrat3',
          align: 'center',
          label: '',
          field: 'porCientoMttoContrat3',
          sortable: true,
        },
      ],
      modalDialog: false,
      modalDialogAnexo3: false,
      filterInput: false,
      loading: false,
      filter: '',
      mapa: false,
      instalaciones: '',
      entidad: '',
      form_mante: {
        entidad_id: '',
        entidades: [],
        mes: '',
        hdd: 0,
        hfo: 0,
        porcientoH: 0,
        anno: '',
      },
    };
  },
  created() {},
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Servicios Técnicos' },
      { label: 'Gestión de mantenimiento' },
      { label: 'Gestionar mantenimiento' },
    ]);
  },
  methods: {
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    anexoTresDatosUno() {
      for (var i = 0; i < this.datasAnexo3.length; i++) {
        this.datasAnexo3[i].prespMttoTotal =
          parseInt(this.datasAnexo3[i].prespMttoT1) +
          parseInt(this.datasAnexo3[i].prespMttoT2);
        if (
          this.datasAnexo3[i].nombre ===
          'Gastos de Mtto Total / Ingresos Totales'
        ) {
          this.datasAnexo3[i].prespMttoTotal =
            (this.datasAnexo3[2].prespMttoTotal /
              this.datasAnexo3[0].prespMttoTotal) *
            100;
          this.datasAnexo3[i].prespMttoT1 =
            (this.datasAnexo3[2].prespMttoT1 /
              this.datasAnexo3[0].prespMttoT1) *
            100;
          this.datasAnexo3[i].prespMttoT2 =
            (this.datasAnexo3[2].prespMttoT2 /
              this.datasAnexo3[0].prespMttoT2) *
            100;
        }
        if (
          this.datasAnexo3[i].nombre ===
          'Gastos de Mtto Total / Costos y Gastos Totales'
        ) {
          this.datasAnexo3[i].prespMttoTotal =
            (this.datasAnexo3[2].prespMttoTotal /
              this.datasAnexo3[1].prespMttoTotal) *
            100;
          this.datasAnexo3[i].prespMttoT1 =
            (this.datasAnexo3[2].prespMttoT1 /
              this.datasAnexo3[1].prespMttoT1) *
            100;
          this.datasAnexo3[i].prespMttoT2 =
            (this.datasAnexo3[2].prespMttoT2 /
              this.datasAnexo3[1].prespMttoT2) *
            100;
        }
      }
    },
    anexoTresDatosDos() {
      for (var i = 0; i < this.datasAnexo3.length; i++) {
        this.datasAnexo3[i].prespMttoContrat =
          parseInt(this.datasAnexo3[i].prespMttoC1) +
          parseInt(this.datasAnexo3[i].prespMttoC2);
        if (
          this.datasAnexo3[i].nombre ===
          'Gastos de Mtto Total / Ingresos Totales'
        ) {
          this.datasAnexo3[i].prespMttoContrat =
            (this.datasAnexo3[2].prespMttoContrat /
              this.datasAnexo3[0].prespMttoTotal) *
            100;
          this.datasAnexo3[i].prespMttoC1 =
            (this.datasAnexo3[2].prespMttoC1 /
              this.datasAnexo3[0].prespMttoT1) *
            100;
          this.datasAnexo3[i].prespMttoC2 =
            (this.datasAnexo3[2].prespMttoC2 /
              this.datasAnexo3[0].prespMttoT2) *
            100;
        }
        if (
          this.datasAnexo3[i].nombre ===
          'Gastos de Mtto Total / Costos y Gastos Totales'
        ) {
          this.datasAnexo3[i].prespMttoContrat =
            (this.datasAnexo3[2].prespMttoContrat /
              this.datasAnexo3[1].prespMttoTotal) *
            100;
          this.datasAnexo3[i].prespMttoC1 =
            (this.datasAnexo3[2].prespMttoC1 /
              this.datasAnexo3[1].prespMttoT1) *
            100;
          this.datasAnexo3[i].prespMttoC2 =
            (this.datasAnexo3[2].prespMttoC2 /
              this.datasAnexo3[1].prespMttoT2) *
            100;
        }
      }
    },
    anexoTresDatosTres() {
      for (var i = 0; i < this.datasAnexo3.length; i++) {
        this.datasAnexo3[i].realMttoTotal =
          parseInt(this.datasAnexo3[i].realMttoT1) +
          parseInt(this.datasAnexo3[i].realMttoT2);
        this.datasAnexo3[i].porCientoMttoTot1 =
          (this.datasAnexo3[i].realMttoTotal * 100) /
          this.datasAnexo3[i].prespMttoTotal;
        this.datasAnexo3[i].porCientoMttoTot2 =
          (this.datasAnexo3[i].realMttoT1 * 100) /
          this.datasAnexo3[i].prespMttoT1;
        this.datasAnexo3[i].porCientoMttoTot3 =
          (this.datasAnexo3[i].realMttoT2 * 100) /
          this.datasAnexo3[i].prespMttoT2;
        if (
          this.datasAnexo3[i].nombre ===
          'Gastos de Mtto Total / Ingresos Totales'
        ) {
          this.datasAnexo3[i].realMttoTotal =
            (this.datasAnexo3[2].realMttoTotal /
              this.datasAnexo3[0].realMttoTotal) *
            100;
          this.datasAnexo3[i].realMttoT1 =
            (this.datasAnexo3[2].realMttoT1 / this.datasAnexo3[0].realMttoT1) *
            100;
          this.datasAnexo3[i].realMttoT2 =
            (this.datasAnexo3[2].realMttoT2 / this.datasAnexo3[0].realMttoT2) *
            100;
          this.datasAnexo3[i].porCientoMttoTot1 =
            (this.datasAnexo3[i].realMttoTotal * 100) /
            this.datasAnexo3[i].prespMttoTotal;
          this.datasAnexo3[i].porCientoMttoTot2 =
            (this.datasAnexo3[i].realMttoT1 * 100) /
            this.datasAnexo3[i].prespMttoT1;
          this.datasAnexo3[i].porCientoMttoTot3 =
            (this.datasAnexo3[i].realMttoT2 * 100) /
            this.datasAnexo3[i].prespMttoT2;
        }
        if (
          this.datasAnexo3[i].nombre ===
          'Gastos de Mtto Total / Costos y Gastos Totales'
        ) {
          this.datasAnexo3[i].realMttoTotal =
            (this.datasAnexo3[2].realMttoTotal /
              this.datasAnexo3[1].realMttoTotal) *
            100;
          this.datasAnexo3[i].realMttoT1 =
            (this.datasAnexo3[2].realMttoT1 / this.datasAnexo3[1].realMttoT1) *
            100;
          this.datasAnexo3[i].realMttoT2 =
            (this.datasAnexo3[2].realMttoT2 / this.datasAnexo3[1].realMttoT2) *
            100;
          this.datasAnexo3[i].porCientoMttoTot1 =
            (this.datasAnexo3[i].realMttoTotal * 100) /
            this.datasAnexo3[i].prespMttoTotal;
          this.datasAnexo3[i].porCientoMttoTot2 =
            (this.datasAnexo3[i].realMttoT1 * 100) /
            this.datasAnexo3[i].prespMttoT1;
          this.datasAnexo3[i].porCientoMttoTot3 =
            (this.datasAnexo3[i].realMttoT2 * 100) /
            this.datasAnexo3[i].prespMttoT2;
        }
      }
    },
    anexoTresDatosCuatro() {
      for (var i = 0; i < this.datasAnexo3.length; i++) {
        this.datasAnexo3[i].realMttoContrat =
          parseInt(this.datasAnexo3[i].realMttoC1) +
          parseInt(this.datasAnexo3[i].realMttoC2);
        this.datasAnexo3[i].porCientoMttoContrat1 =
          (this.datasAnexo3[i].realMttoContrat * 100) /
          this.datasAnexo3[i].prespMttoContrat;
        this.datasAnexo3[i].porCientoMttoContrat2 =
          (this.datasAnexo3[i].realMttoC1 * 100) /
          this.datasAnexo3[i].prespMttoC1;
        this.datasAnexo3[i].porCientoMttoContrat3 =
          (this.datasAnexo3[i].realMttoC2 * 100) /
          this.datasAnexo3[i].prespMttoC2;
        if (
          this.datasAnexo3[i].nombre ===
          'Gastos de Mtto Total / Ingresos Totales'
        ) {
          this.datasAnexo3[i].realMttoContrat =
            (this.datasAnexo3[2].realMttoContrat /
              this.datasAnexo3[0].realMttoTotal) *
            100;
          this.datasAnexo3[i].realMttoC1 =
            (this.datasAnexo3[2].realMttoC1 / this.datasAnexo3[0].realMttoT1) *
            100;
          this.datasAnexo3[i].realMttoC2 =
            (this.datasAnexo3[2].realMttoC2 / this.datasAnexo3[0].realMttoT2) *
            100;
          this.datasAnexo3[i].porCientoMttoContrat1 =
            (this.datasAnexo3[i].realMttoContrat * 100) /
            this.datasAnexo3[i].prespMttoContrat;
          this.datasAnexo3[i].porCientoMttoContrat2 =
            (this.datasAnexo3[i].realMttoC1 * 100) /
            this.datasAnexo3[i].prespMttoC1;
          this.datasAnexo3[i].porCientoMttoContrat3 =
            (this.datasAnexo3[i].realMttoC2 * 100) /
            this.datasAnexo3[i].prespMttoC2;
        }
        if (
          this.datasAnexo3[i].nombre ===
          'Gastos de Mtto Total / Costos y Gastos Totales'
        ) {
          this.datasAnexo3[i].realMttoContrat =
            (this.datasAnexo3[2].realMttoContrat /
              this.datasAnexo3[1].realMttoTotal) *
            100;
          this.datasAnexo3[i].realMttoC1 =
            (this.datasAnexo3[2].realMttoC1 / this.datasAnexo3[1].realMttoT1) *
            100;
          this.datasAnexo3[i].realMttoC2 =
            (this.datasAnexo3[2].realMttoC2 / this.datasAnexo3[1].realMttoT2) *
            100;
          this.datasAnexo3[i].porCientoMttoContrat1 =
            (this.datasAnexo3[i].realMttoContrat * 100) /
            this.datasAnexo3[i].prespMttoContrat;
          this.datasAnexo3[i].porCientoMttoContrat2 =
            (this.datasAnexo3[i].realMttoC1 * 100) /
            this.datasAnexo3[i].prespMttoC1;
          this.datasAnexo3[i].porCientoMttoContrat3 =
            (this.datasAnexo3[i].realMttoC2 * 100) /
            this.datasAnexo3[i].prespMttoC2;
        }
      }
    },
    calculoDePmtTablaTres() {
      var totalPmt = 0;
      var totalPmta = 0;
      var totalPmtb = 0;
      for (var i = 0; i < this.datasAnexoTabla.length - 1; i++) {
        this.datasAnexoTabla[i].prespMttoTotal =
          parseInt(this.datasAnexoTabla[i].prespMttoT1) +
          parseInt(this.datasAnexoTabla[i].prespMttoT2);
        totalPmt = totalPmt + parseInt(this.datasAnexoTabla[i].prespMttoTotal);
        totalPmta = totalPmta + parseInt(this.datasAnexoTabla[i].prespMttoT1);
        totalPmtb = totalPmtb + parseInt(this.datasAnexoTabla[i].prespMttoT2);
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].prespMttoTotal = totalPmt;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].prespMttoT1 = totalPmta;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].prespMttoT2 = totalPmtb;
        this.datasAnexoTabla[i].porCientoMttoTot1 =
          (parseInt(this.datasAnexoTabla[i].realMttoTotal) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoTotal);
        this.datasAnexoTabla[i].porCientoMttoTot2 =
          (parseInt(this.datasAnexoTabla[i].realMttoT1) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoT1);
        this.datasAnexoTabla[i].porCientoMttoTot3 =
          (parseInt(this.datasAnexoTabla[i].realMttoT2) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoT2);
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoTot1 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoTotal *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoTotal;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoTot2 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoT1 *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoT1;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoTot3 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoT2 *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoT2;
      }
    },
    calculoDePmcTablaTres() {
      var totalPmc = 0;
      var totalPmca = 0;
      var totalPmcb = 0;
      for (var i = 0; i < this.datasAnexoTabla.length - 1; i++) {
        this.datasAnexoTabla[i].prespMttoContrat =
          parseInt(this.datasAnexoTabla[i].prespMttoC1) +
          parseInt(this.datasAnexoTabla[i].prespMttoC2);
        totalPmc =
          totalPmc + parseInt(this.datasAnexoTabla[i].prespMttoContrat);
        totalPmca = totalPmca + parseInt(this.datasAnexoTabla[i].prespMttoC1);
        totalPmcb = totalPmcb + parseInt(this.datasAnexoTabla[i].prespMttoC2);
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].prespMttoContrat = totalPmc;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].prespMttoC1 = totalPmca;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].prespMttoC2 = totalPmcb;
        this.datasAnexoTabla[i].porCientoMttoContrat1 =
          (parseInt(this.datasAnexoTabla[i].realMttoContrat) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoContrat);
        this.datasAnexoTabla[i].porCientoMttoContrat2 =
          (parseInt(this.datasAnexoTabla[i].realMttoC1) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoC1);
        this.datasAnexoTabla[i].porCientoMttoContrat3 =
          (parseInt(this.datasAnexoTabla[i].realMttoC2) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoC2);
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoContrat1 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1]
            .realMttoContrat *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1]
            .prespMttoContrat;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoContrat2 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoC1 *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoC1;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoContrat3 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoC2 *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoC2;
      }
    },
    calculoDeRmtTablaTres() {
      var totalRmt = 0;
      var totalRmta = 0;
      var totalRmtb = 0;
      for (var i = 0; i < this.datasAnexoTabla.length - 1; i++) {
        this.datasAnexoTabla[i].realMttoTotal =
          parseInt(this.datasAnexoTabla[i].realMttoT1) +
          parseInt(this.datasAnexoTabla[i].realMttoT2);
        totalRmt = totalRmt + parseInt(this.datasAnexoTabla[i].realMttoTotal);
        totalRmta = totalRmta + parseInt(this.datasAnexoTabla[i].realMttoT1);
        totalRmtb = totalRmtb + parseInt(this.datasAnexoTabla[i].realMttoT2);
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].realMttoTotal = totalRmt;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].realMttoT1 = totalRmta;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].realMttoT2 = totalRmtb;
        this.datasAnexoTabla[i].porCientoMttoTot1 =
          (parseInt(this.datasAnexoTabla[i].realMttoTotal) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoTotal);
        this.datasAnexoTabla[i].porCientoMttoTot2 =
          (parseInt(this.datasAnexoTabla[i].realMttoT1) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoT1);
        this.datasAnexoTabla[i].porCientoMttoTot3 =
          (parseInt(this.datasAnexoTabla[i].realMttoT2) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoT2);
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoTot1 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoTotal *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoTotal;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoTot2 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoT1 *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoT1;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoTot3 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoT2 *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoT2;
      }
    },
    calculoDeRmcTablaTres() {
      var totalRmc = 0;
      var totalRmca = 0;
      var totalRmcb = 0;
      for (var i = 0; i < this.datasAnexoTabla.length - 1; i++) {
        this.datasAnexoTabla[i].realMttoContrat =
          parseInt(this.datasAnexoTabla[i].realMttoC1) +
          parseInt(this.datasAnexoTabla[i].realMttoC2);
        totalRmc = totalRmc + parseInt(this.datasAnexoTabla[i].realMttoContrat);
        totalRmca = totalRmca + parseInt(this.datasAnexoTabla[i].realMttoC1);
        totalRmcb = totalRmcb + parseInt(this.datasAnexoTabla[i].realMttoC2);
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].realMttoContrat = totalRmc;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].realMttoC1 = totalRmca;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].realMttoC2 = totalRmcb;
        this.datasAnexoTabla[i].porCientoMttoContrat1 =
          (parseInt(this.datasAnexoTabla[i].realMttoContrat) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoContrat);
        this.datasAnexoTabla[i].porCientoMttoContrat2 =
          (parseInt(this.datasAnexoTabla[i].realMttoC1) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoC1);
        this.datasAnexoTabla[i].porCientoMttoContrat3 =
          (parseInt(this.datasAnexoTabla[i].realMttoC2) * 100) /
          parseInt(this.datasAnexoTabla[i].prespMttoC2);
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoContrat1 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1]
            .realMttoContrat *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1]
            .prespMttoContrat;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoContrat2 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoC1 *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoC1;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoContrat3 =
          (this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoC2 *
            100) /
          this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoC2;
      }
    },
    porcientoAptpp() {
      var valorAptp = 0;
      var valorAptr = 0;
      for (var i = 0; i < this.datas.length - 1; i++) {
        this.datas[i].accPrevTPorCiento =
          (parseInt(this.datas[i].accPrevTReal) * 100) /
          parseInt(this.datas[i].accPrevTPlan);
        valorAptp = valorAptp + parseInt(this.datas[i].accPrevTPlan);
        valorAptr = valorAptr + parseInt(this.datas[i].accPrevTReal);
        this.datas[this.datas.length - 1].accPrevTPlan = valorAptp;
        this.datas[this.datas.length - 1].accPrevTReal = valorAptr;
        this.datas[this.datas.length - 1].accPrevTPorCiento =
          (parseInt(this.datas[this.datas.length - 1].accPrevTReal) * 100) /
          parseInt(this.datas[this.datas.length - 1].accPrevTPlan);
      }
    },
    pocientoAptcc() {
      var valorApcp = 0;
      var valorApcr = 0;
      for (var i = 0; i < this.datas.length - 1; i++) {
        this.datas[i].accPrevCPorCiento =
          (parseInt(this.datas[i].accPrevCPlan) * 100) /
          parseInt(this.datas[i].accPrevCReal);
        valorApcp = valorApcp + parseInt(this.datas[i].accPrevCPlan);
        valorApcr = valorApcr + parseInt(this.datas[i].accPrevCReal);
        this.datas[this.datas.length - 1].accPrevCPlan = valorApcp;
        this.datas[this.datas.length - 1].accPrevCReal = valorApcr;
        this.datas[this.datas.length - 1].accPrevCPorCiento =
          (parseInt(this.datas[this.datas.length - 1].accPrevCReal) * 100) /
          parseInt(this.datas[this.datas.length - 1].accPrevCPlan);
      }
    },
    porciento() {
      var valorIt = 0;
      var valorIc = 0;
      for (var i = 0; i < this.datas.length - 1; i++) {
        this.datas[i].totalAccMtto =
          parseInt(this.datas[i].impTotal) +
          parseInt(this.datas[i].impContratado);
        this.datas[i].porCientoImpAcc =
          (parseInt(this.datas[i].impTotal) * 100) /
          parseInt(this.datas[i].totalAccMtto);
        valorIt = valorIt + parseInt(this.datas[i].impTotal);
        valorIc = valorIc + parseInt(this.datas[i].impContratado);
        this.datas[this.datas.length - 1].impTotal = valorIt;
        this.datas[this.datas.length - 1].impContratado = valorIc;
        this.datas[this.datas.length - 1].totalAccMtto =
          valorIt + this.datas[this.datas.length - 1].accPrevCReal;
        this.datas[this.datas.length - 1].porCientoImpAcc =
          (parseInt(this.datas[this.datas.length - 1].impTotal) * 100) /
          parseInt(this.datas[this.datas.length - 1].totalAccMtto);
      }
    },
    porcientoHdf() {
      if (this.form_mante.hfo > 0 && this.form_mante.hdd > 0) {
        this.form_mante.porcientoH =
          (this.form_mante.hfo * 100) / this.form_mante.hdd;
      } else {
        this.form_mante.porcientoH = 0;
      }
    },
    closeModalTable() {
      for (var i = 0; i < this.datas.length - 1; i++) {
        this.datas[i].accPrevTPorCiento = 0;
        this.datas[i].accPrevTPlan = 0;
        this.datas[i].accPrevTReal = 0;
        this.datas[i].accPrevCPorCiento = 0;
        this.datas[i].accPrevCPlan = 0;
        this.datas[i].accPrevCReal = 0;
        this.datas[i].totalAccMtto = 0;
        this.datas[i].impTotal = 0;
        this.datas[i].impContratado = 0;
        this.datas[i].porCientoImpAcc = 0;
        this.datas[this.datas.length - 1].accPrevTPlan = 0;
        this.datas[this.datas.length - 1].accPrevTReal = 0;
        this.datas[this.datas.length - 1].accPrevTPorCiento = 0;
        this.datas[this.datas.length - 1].accPrevCPorCiento = 0;
        this.datas[this.datas.length - 1].accPrevCPlan = 0;
        this.datas[this.datas.length - 1].accPrevCReal = 0;
        this.datas[this.datas.length - 1].totalAccMtto = 0;
        this.datas[this.datas.length - 1].impTotal = 0;
        this.datas[this.datas.length - 1].impContratado = 0;
        this.datas[this.datas.length - 1].porCientoImpAcc = 0;
        this.form_mante.entidad_id = '';
        this.form_mante.mes = '';
        this.form_mante.anno = '';
        this.form_mante.hdd = 0;
        this.form_mante.hfo = 0;
        this.form_mante.porcientoH = 0;
      }
    },
    closeModalTableAnexoTres() {
      for (var i = 0; i < this.datasAnexoTabla.length - 1; i++) {
        this.datasAnexoTabla[i].prespMttoTotal = 0;
        this.datasAnexoTabla[i].prespMttoT1 = 0;
        this.datasAnexoTabla[i].prespMttoT2 = 0;
        this.datasAnexoTabla[i].prespMttoContrat = 0;
        this.datasAnexoTabla[i].prespMttoC1 = 0;
        this.datasAnexoTabla[i].prespMttoC2 = 0;
        this.datasAnexoTabla[i].realMttoTotal = 0;
        this.datasAnexoTabla[i].realMttoT1 = 0;
        this.datasAnexoTabla[i].realMttoT2 = 0;
        this.datasAnexoTabla[i].realMttoContrat = 0;
        this.datasAnexoTabla[i].realMttoC1 = 0;
        this.datasAnexoTabla[i].realMttoC2 = 0;
        this.datasAnexoTabla[i].porCientoMttoTot1 = 0;
        this.datasAnexoTabla[i].porCientoMttoTot2 = 0;
        this.datasAnexoTabla[i].porCientoMttoTot3 = 0;
        this.datasAnexoTabla[i].porCientoMttoContrat1 = 0;
        this.datasAnexoTabla[i].porCientoMttoContrat2 = 0;
        this.datasAnexoTabla[i].porCientoMttoContrat3 = 0;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].prespMttoTotal = 0;
        this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoT1 = 0;
        this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoT2 = 0;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].prespMttoContrat = 0;
        this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoC1 = 0;
        this.datasAnexoTabla[this.datasAnexoTabla.length - 1].prespMttoC2 = 0;
        this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoTotal = 0;
        this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoT1 = 0;
        this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoT2 = 0;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].realMttoContrat = 0;
        this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoC1 = 0;
        this.datasAnexoTabla[this.datasAnexoTabla.length - 1].realMttoC2 = 0;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoTot1 = 0;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoTot2 = 0;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoTot3 = 0;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoContrat1 = 0;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoContrat2 = 0;
        this.datasAnexoTabla[
          this.datasAnexoTabla.length - 1
        ].porCientoMttoContrat3 = 0;
        this.datasAnexo3 = [
          {
            nombre: 'Ingresos Totales',
            unidadMedida: 'Miles',
            prespMttoTotal: 0,
            prespMttoT1: 0,
            prespMttoT2: 0,
            prespMttoContrat: '-',
            prespMttoC1: '-',
            prespMttoC2: '-',
            realMttoTotal: 0,
            realMttoT1: 0,
            realMttoT2: 0,
            realMttoContrat: '-',
            realMttoC1: '-',
            realMttoC2: '-',
            porCientoMttoTot1: 0,
            porCientoMttoTot2: 0,
            porCientoMttoTot3: 0,
            porCientoMttoContrat1: '-',
            porCientoMttoContrat2: '-',
            porCientoMttoContrat3: '-',
          },
          {
            nombre: 'Costos y Gastos Totales',
            unidadMedida: 'Miles',
            prespMttoTotal: 0,
            prespMttoT1: 0,
            prespMttoT2: 0,
            prespMttoContrat: '-',
            prespMttoC1: '-',
            prespMttoC2: '-',
            realMttoTotal: 0,
            realMttoT1: 0,
            realMttoT2: 0,
            realMttoContrat: '-',
            realMttoC1: '-',
            realMttoC2: '-',
            porCientoMttoTot1: 0,
            porCientoMttoTot2: 0,
            porCientoMttoTot3: 0,
            porCientoMttoContrat1: '-',
            porCientoMttoContrat2: '-',
            porCientoMttoContrat3: '-',
          },
          {
            nombre: 'Gasto de Mantenimiento Total',
            unidadMedida: 'Miles',
            prespMttoTotal: 0,
            prespMttoT1: 0,
            prespMttoT2: 0,
            prespMttoContrat: 0,
            prespMttoC1: 0,
            prespMttoC2: 0,
            realMttoTotal: 0,
            realMttoT1: 0,
            realMttoT2: 0,
            realMttoContrat: 0,
            realMttoC1: 0,
            realMttoC2: 0,
            porCientoMttoTot1: 0,
            porCientoMttoTot2: 0,
            porCientoMttoTot3: 0,
            porCientoMttoContrat1: 0,
            porCientoMttoContrat2: 0,
            porCientoMttoContrat3: 0,
          },
          {
            nombre: 'INDICADORES SELECCIONADOS',
            unidadMedida: '-',
            prespMttoTotal: '-',
            prespMttoT1: '-',
            prespMttoT2: '-',
            prespMttoContrat: '-',
            prespMttoC1: '-',
            prespMttoC2: '-',
            realMttoTotal: '-',
            realMttoT1: '-',
            realMttoT2: '-',
            realMttoContrat: '-',
            realMttoC1: '-',
            realMttoC2: '-',
            porCientoMttoTot1: '-',
            porCientoMttoTot2: '-',
            porCientoMttoTot3: '-',
            porCientoMttoContrat1: '-',
            porCientoMttoContrat2: '-',
            porCientoMttoContrat3: '-',
          },
          {
            nombre: 'Gastos de Mtto Total / Ingresos Totales',
            unidadMedida: '%',
            prespMttoTotal: 0,
            prespMttoT1: 0,
            prespMttoT2: 0,
            prespMttoContrat: 0,
            prespMttoC1: 0,
            prespMttoC2: 0,
            realMttoTotal: 0,
            realMttoT1: 0,
            realMttoT2: 0,
            realMttoContrat: 0,
            realMttoC1: 0,
            realMttoC2: 0,
            porCientoMttoTot1: 0,
            porCientoMttoTot2: 0,
            porCientoMttoTot3: 0,
            porCientoMttoContrat1: 0,
            porCientoMttoContrat2: 0,
            porCientoMttoContrat3: 0,
          },
          {
            nombre: 'Gastos de Mtto Total / Costos y Gastos Totales',
            unidadMedida: '%',
            prespMttoTotal: 0,
            prespMttoT1: 0,
            prespMttoT2: 0,
            prespMttoContrat: 0,
            prespMttoC1: 0,
            prespMttoC2: 0,
            realMttoTotal: 0,
            realMttoT1: 0,
            realMttoT2: 0,
            realMttoContrat: 0,
            realMttoC1: 0,
            realMttoC2: 0,
            porCientoMttoTot1: 0,
            porCientoMttoTot2: 0,
            porCientoMttoTot3: 0,
            porCientoMttoContrat1: 0,
            porCientoMttoContrat2: 0,
            porCientoMttoContrat3: 0,
          },
          {
            nombre: 'Gastos de Mtto Total / Hab. Dias Disponibles',
            unidadMedida: 'UM/hab',
            prespMttoTotal: 0,
            prespMttoT1: 0,
            prespMttoT2: 0,
            prespMttoContrat: 0,
            prespMttoC1: 0,
            prespMttoC2: 0,
            realMttoTotal: 0,
            realMttoT1: 0,
            realMttoT2: 0,
            realMttoContrat: 0,
            realMttoC1: 0,
            realMttoC2: 0,
            porCientoMttoTot1: 0,
            porCientoMttoTot2: 0,
            porCientoMttoTot3: 0,
            porCientoMttoContrat1: 0,
            porCientoMttoContrat2: 0,
            porCientoMttoContrat3: 0,
          },
        ];
        this.form_mante.entidad_id = '';
        this.form_mante.mes = '';
        this.form_mante.anno = '';
      }
    },
    async enviarAnexoDos() {
      if (
        this.entidad === '' ||
        this.form_mante.anno === '' ||
        this.form_mante.mes === ''
      ) {
        warningMessage('Faltan datos por llenar');
      } else {
        const datos = {
          datas: this.datas,
          attributos: this.form_mante,
          entidad: this.entidad,
        };
        return Vue.prototype.$axios
          .post('/api/add_anexo_dos', datos)
          .then((result) => {
            console.log(result);
          })
          .catch((err) => {
            errorMessage(message.eDataError, err);
          });
      }
    },
    async enviarAnexoTres() {
      if (
        this.entidad === '' ||
        this.form_mante.anno === '' ||
        this.form_mante.mes === ''
      ) {
        warningMessage('Faltan datos por llenar');
      } else {
        const datos = {
          datas: this.datas,
          attributos: this.form_mante,
          entidad: this.entidad,
        };
        return Vue.prototype.$axios
          .post('/api/add_anexo_tres', datos)
          .then((result) => {
            console.log(result);
          })
          .catch((err) => {
            errorMessage(message.eDataError, err);
          });
      }
    },
  },
};
</script>

<style scoped></style>
