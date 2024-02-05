<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          table-header-class="text-uppercase"
          :data="data"
          :columns="columns"
          :visible-columns="visibleColumns"
          row-key="id"
          :loading="loading"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 15]"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          no-results-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar anexos
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
            <q-btn-dropdown
              v-if="scopes.includes('nomencladores')"
              type="reset"
              key="add"
              dense
              color="primary"
              icon="add"
              @click.prevent=""
              class="q-mt-sm"
            >
              <q-list>
                <q-item clickable v-close-popup @click="modalDialogAnexo2 = true">
                  <q-item-section avatar>
                    <q-avatar icon="assignment" color="white" text-color="secondary" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Anexo 2</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item clickable v-close-popup @click="modalDialogAnexo3 = true">
                  <q-item-section avatar>
                    <q-avatar icon="assignment" color="white" text-color="secondary" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Anexo 3</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-btn-dropdown>
          </template>
          <template v-slot:loading>
            <q-inner-loading showing color="primary" />
          </template>
          <template v-slot:body-cell-columnaction="props">
            <q-td :props="props">
              <div class="q-gutter-sm">
                <q-btn
                  round
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
    <q-dialog
      v-model="modalDialogAnexo2"
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
                  @click="closeModal"
                  v-close-popup
                >
                  <q-tooltip>Cerrar</q-tooltip>
                </q-btn>
              </q-toolbar>
            </q-card-section>
            <q-card-section>
              <div class="row">
                <div class="col">
                  <div class="q-pa-md" style="max-width: 300px">
                    <div class="q-gutter-md">
                      <q-select
                        class="col-3"
                        id="entidad"
                        label="Instalación"
                        v-model="form_mante.instalacion_desc"
                        dense
                        input-debounce="0"
                        options-dense
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
                <div class="col">
                  <div class="q-pa-md" style="max-width: 300px">
                    <div class="q-gutter-md">
                      <q-select
                        class="col-3"
                        id="mes"
                        label="Mes"
                        v-model="form_mante.mes"
                        dense
                        input-debounce="0"
                        :options="meses"
                        options-dense
                        option-label="mes" option-value="id"
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
                <div class="col">
                  <div class="q-pa-md" style="max-width: 300px">
                    <div class="q-gutter-md">
                      <q-select
                        class="col-3"
                        id="anno"
                        label="Año"
                        v-model="form_mante.anno"
                        dense
                        input-debounce="0"
                        :options="anno"
                        options-dense
                        option-label="anno" option-value="anno"
                      >
                        <template v-slot:prepend>
                          <q-icon name="mdi-calendar"/>
                        </template>
                      </q-select>
                    </div>
                  </div>
                </div>

              </div>
              <div class="q-pa-md">
                <q-table
                  :data="datasAnexoDos"
                  dense
                  :rows-per-page-options="[35]"
                  :columns="columnsAnexoDos"
                  row-key="name"
                  binary-state-sort
                >
                  <template v-slot:header="props" style="border-spacing: 1px">
                    <q-tr v-for="(row, index) in headerMakerAnexoDos" :key="index">
                      <q-th
                        :auto-width="cell.colspan === '0'"
                        v-for="(cell, cellCount) in row" :key="cellCount"
                        :colspan="cell.colspan"
                        :rowspan="cell.rowspan"
                        class="bg-blue-6 text-white"
                        style="font-size: 13px"
                      >
                        {{cell.label}}
                      </q-th>
                    </q-tr>
                  </template>
                  <template v-slot:body="props">
                    <q-tr :props="props">
                      <q-td key="descMtto" :props="props" >
                        {{ props.row.descMtto }}
                      </q-td>
                      <q-td key="unidadMedida" :props="props">
                        {{ props.row.unidadMedida }}
                      </q-td>
                      <q-td key="accPrevTPlan" :props="props">
                        <q-input v-model="props.row.accPrevTPlan"
                                 :readonly="props.row.descMtto === 'Total'"
                                 min="0"
                                 dense autofocus @input="porcientoAptpp"/>
                      </q-td>
                      <q-td key="accPrevTReal" :props="props">
                        <q-input v-model="props.row.accPrevTReal"
                                 :readonly="props.row.descMtto === 'Total'"
                                 min="0"
                                 dense autofocus @input="porcientoAptpp" />
                      </q-td>
                      <q-td key="accPrevTPorCiento" :props="props">
                        {{ props.row.accPrevTPorCiento }}
                      </q-td>
                      <q-td key="accPrevCPlan" :props="props">
                        <q-input v-model="props.row.accPrevCPlan"
                                 :readonly="props.row.descMtto === 'Total'"
                                 min="0"
                                 dense autofocus @input="pocientoAptcc"/>
                      </q-td>
                      <q-td key="accPrevCReal" :props="props">
                        <q-input v-model="props.row.accPrevCReal"
                                 :readonly="props.row.descMtto === 'Total'"
                                 min="0"
                                 dense autofocus @input="pocientoAptcc"/>
                      </q-td>
                      <q-td key="accPrevCPorCiento" :props="props">
                        {{ props.row.accPrevCPorCiento }}
                      </q-td>
                      <q-td key="impTotal" :props="props">
                        <q-input v-model="props.row.impTotal"
                                 :readonly="props.row.descMtto === 'Total'"
                                 min="0"
                                 dense autofocus @input="porciento"/>
                      </q-td>
                      <q-td key="impContratado" :props="props">
                        <q-input v-model="props.row.impContratado"
                                 :readonly="props.row.descMtto === 'Total'"
                                 min="0"
                                 dense autofocus @input="porciento"/>
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
                  <q-input type="number" v-model="form_mante.hdd"
                           label="Habitaciones Dias Disponibles (HDD)" min="0"
                           dense
                           @input="porcientoHdf"/>
                </div>
                <div class="col-4 q-px-sm">
                  <q-input type="number" v-model="form_mante.hfo"
                           label="Habitaciones Fuera de Orden por Mantenimiento (HFO)" min="0" dense

                           @input="porcientoHdf"/>
                </div>
                <div class="col-4 q-px-sm">
                  <q-input type="number" v-model="form_mante.porcientoH" label="% HFO / HDD" readonly
                           dense />
                </div>
              </div>
            </q-card-section>
          </q-card-section>
          <q-card-actions class="q-pa-md" align="right">
            <q-btn
              type="button"
              label="Guardar"
              color="primary"
              icon="save"
              style="margin-bottom: 30px"
              @click.prevent="enviarAnexoDos()"
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
                @click="closeModal"
                v-close-popup
              >
                <q-tooltip>Cerrar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </q-card-section>
          <q-card-section>
            <div class="row">
              <div class="col">
                <div class="q-pa-md" style="max-width: 300px">
                  <div class="q-gutter-md">
                    <q-select
                      class="col-3"
                      id="entidads"
                      label="Instalación"
                      v-model="form_mante.instalacion_desc"
                      dense
                      input-debounce="0"
                      options-dense
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
              <div class="col">
                <div class="q-pa-md" style="max-width: 300px">
                  <div class="q-gutter-md">
                    <q-select
                      class="col-3"
                      id="mess"
                      label="Mes"
                      v-model="form_mante.mes"
                      dense
                      input-debounce="0"
                      :options="meses"
                      options-dense
                      option-label="mes" option-value="id"
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
              <div class="col">
                <div class="q-pa-md" style="max-width: 300px">
                  <div class="q-gutter-md">
                    <q-select
                      class="col-3"
                      id="annos"
                      label="Año"
                      v-model="form_mante.anno"
                      dense
                      input-debounce="0"
                      :options="anno"
                      options-dense
                      option-label="anno" option-value="anno"
                    >
                      <template v-slot:prepend>
                        <q-icon name="mdi-calendar"/>
                      </template>
                    </q-select>
                  </div>
                </div>
              </div>
            </div>
            <div class="q-pa-md">
              <q-table
                :data="datasAnexoTres"
                dense
                :rows-per-page-options="[35]"
                :columns="columnsAnexoTres"
                row-key="name"
                binary-state-sort
              >
                <template v-slot:header="props" style="border-spacing: 1px">
                  <q-tr v-for="(row, index) in headerMakerAnexoTres" :key="index">
                    <q-th
                      :auto-width="cell.colspan === '0'"
                      v-for="(cell, cellCount) in row" :key="cellCount"
                      :colspan="cell.colspan"
                      :rowspan="cell.rowspan"
                      class="bg-blue-6 text-white"
                      style="font-size: 13px"
                    >
                      {{cell.label}}
                    </q-th>
                  </q-tr>
                </template>
                <template v-slot:body="props">
                  <q-tr :props="props">
                    <q-td key="descMtto" :props="props" >
                      {{ props.row.descMtto}}
                    </q-td>
                    <q-td key="unidadMedida" :props="props">
                      {{ props.row.unidadMedida}}
                    </q-td>

                    <q-td key="prespMttoT1" :props="props">
                      <q-input v-model="props.row.prespMttoT1"
                               :readonly="props.row.descMtto === 'Total'"
                               min="0"
                               dense autofocus @input="calculoDePmtTablaTres"/>
                    </q-td>
                    <q-td key="prespMttoT2" :props="props">
                      <q-input v-model="props.row.prespMttoT2"
                               :readonly="props.row.descMtto === 'Total'"
                               min="0"
                               dense autofocus @input="calculoDePmtTablaTres"/>
                    </q-td>
                    <q-td key="prespMttoTotal" :props="props">
                      {{ props.row.prespMttoTotal}}
                    </q-td>
                    <q-td key="prespMttoC1" :props="props">
                      <q-input v-model="props.row.prespMttoC1"
                               :readonly="props.row.descMtto === 'Total'"
                               min="0"
                               dense autofocus @input="calculoDePmcTablaTres"/>
                    </q-td>
                    <q-td key="prespMttoC2" :props="props">
                      <q-input v-model="props.row.prespMttoC2"
                               :readonly="props.row.descMtto === 'Total'"
                               min="0"
                               dense autofocus @input="calculoDePmcTablaTres"/>
                    </q-td>
                    <q-td key="prespMttoContrat" :props="props">
                      {{ props.row.prespMttoContrat}}
                    </q-td>
                    <q-td key="realMttoT1" :props="props">
                      <q-input v-model="props.row.realMttoT1"
                               :readonly="props.row.descMtto === 'Total'"
                               min="0"
                               dense autofocus @input="calculoDeRmtTablaTres"/>
                    </q-td>
                    <q-td key="realMttoT2" :props="props">
                      <q-input v-model="props.row.realMttoT2"
                               :readonly="props.row.descMtto === 'Total'"
                               min="0"
                               dense autofocus @input="calculoDeRmtTablaTres"/>
                    </q-td>
                    <q-td key="realMttoTotal" :props="props">
                      {{ props.row.realMttoTotal}}
                    </q-td>
                    <q-td key="realMttoC1" :props="props">
                      <q-input v-model="props.row.realMttoC1"
                               :readonly="props.row.descMtto === 'Total'"
                               min="0"
                               dense autofocus @input="calculoDeRmcTablaTres"/>
                    </q-td>
                    <q-td key="realMttoC2" :props="props">
                      <q-input v-model="props.row.realMttoC2"
                               :readonly="props.row.descMtto === 'Total'"
                               min="0"
                               dense autofocus @input="calculoDeRmcTablaTres"/>
                    </q-td>
                    <q-td key="realMttoContrat" :props="props">
                      {{ props.row.realMttoContrat}}
                    </q-td>

                    <q-td key="porCientoMttoTot1" :props="props">
                      {{ props.row.porCientoMttoTot1}}
                    </q-td>
                    <q-td key="porCientoMttoTot2" :props="props">
                      {{ props.row.porCientoMttoTot2 }}
                    </q-td>
                    <q-td key="porCientoMttoTot3" :props="props">
                      {{ props.row.porCientoMttoTot3 }}
                    </q-td>
                    <q-td key="porCientoMttoContrat1" :props="props">
                      {{ props.row.porCientoMttoContrat1}}
                    </q-td>
                    <q-td key="porCientoMttoContrat2" :props="props">
                      {{ props.row.porCientoMttoContrat2 }}
                    </q-td>
                    <q-td key="porCientoMttoContrat3" :props="props">
                      {{ props.row.porCientoMttoContrat3 }}
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
            label="Guardar"
            color="primary"
            icon="save"
            style="margin-bottom: 30px"
            @click.prevent="enviarAnexoTres()"
          />
        </q-card-actions>
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
          ref="formTipo"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{"Eliminar Anexo"}}
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
                  <label name="desc1"  type="text"  dense
                  >
                    Está seguro de eliminar el {{this.form_data.descripcion}} de {{this.form_data.mes}}/{{this.form_data.anno}}</label>
                </div>
              </div>
            </q-card-section>
            <q-card-actions align="right">
              <q-btn type="button" :icon="'save'"
                     @click="deleteElement()" label="Guardar"
                     color="secondary" flat/>
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
  import { errorMessage, warningMessage, successMessage } from '../../utils/notificaciones';
  import Vue from 'vue';
  import * as message from '../../utils/resources';
  import { mapActions } from 'vuex';
  import {numberFormat} from "highcharts";

  export default {
    name: 'gestionMantenimiento',
    data() {
      return {
        scopes: sessionStorage.getItem('scopes'),
        data: [],
        columns: [
          {
            name: 'columnid',
            label: 'id',
            align: 'center',
            field: row => row.id
          },
          {
            name: 'columnyear',
            label: 'Año',
            align: 'left',
            field: row => row.anno,
            sortable: true,
            headerClasses: 'text-uppercase'
          },
          {
            name: 'columnmonth',
            label: 'Mes',
            align: 'left',
            field: row => row.mes,
            sortable: true,
            headerClasses: 'text-uppercase'
          },
          {
            name: 'columnname',
            label: 'Descripción',
            align: 'left',
            field: row => row.descripcion,
            sortable: true,
            headerClasses: 'text-uppercase'
          },
          {
            name: 'columnaction',
            label: 'Acciones',
            align: 'center',
            field: 'action',
            headerClasses: 'text-uppercase'
          }

        ],
        visibleColumns: ['columnyear','columnmonth','columnname','columnaction'],
        modalDialogAnexo2: false,
        modalDialogAnexo3: false,
        modalDialogDelete: false,
        form_mante: {
          instalacion_id: sessionStorage.getItem('instalacion_id'),
          instalacion_desc: sessionStorage.getItem('instalacion'),
          mes: '',
          hdd: 0,
          hfo: 0,
          porcientoH: 0,
          anno: ''
        },
        meses: [
          {
            id: 1,
            mes: 'Enero'
          },
          {
            id: 2,
            mes: 'Febrero'
          },
          {
            id: 3,
            mes: 'Marzo'
          },
          {
            id: 4,
            mes: 'Abril'
          },
          {
            id: 5,
            mes: 'Mayo'
          },
          {
            id: 6,
            mes: 'Junio'
          },
          {
            id: 7,
            mes: 'Julio'
          },
          {
            id: 8,
            mes: 'Agosto'
          },
          {
            id: 9,
            mes: 'Septiembre'
          },
          {
            id: 10,
            mes: 'Octubre'
          },
          {
            id: 11,
            mes: 'Noviembre'
          },
          {
            id: 12,
            mes: 'Diciembre'
          }
        ],
        datasAnexoDos: [
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de ventilación y extracción' ,
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
            porCientoImpAcc: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de tratamiento de residuales',
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
            porCientoImpAcc: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de bombeo' ,
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
            porCientoImpAcc: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos electrónicos y de comunicaciones',
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de protección y seguridad',
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
            porCientoImpAcc: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de almacenam. de combustible y agua',
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
            porCientoImpAcc: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de producción de vapor y agua caliente',
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
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
            porCientoImpAcc: 0
          }
        ],
        datasAnexoTres: [
          {
            descMtto: 'Mantenimiento a edificaciones',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a mobiliario',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a redes técnicas',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a fachadas y pintura exterior',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a accesos y viales',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a áreas verdes',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a ascensores',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipos de refrigeración',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a grupos electrógenos',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipos gastronómicos',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipos de tintorería y lavandería',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipos de computación',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a paneles eléctricos',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas de iluminación',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de piscinas',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de ventilación y extracción',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de tratamiento de residuales',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de bombeo',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos electrónicos y de comunicaciones',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de climatización',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de protección y seguridad',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de almacenam. de combustible y agua',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a sistemas y equipos de producción de vapor y agua caliente',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipamiento náutico',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipamiento de espectáculo',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipos de medición',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipos de taller',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipos de transporte automotor',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a embarcaciones',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Mantenimiento a equipos de transporte de mecanización',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Otros servicios de mantenimiento',
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
            porCientoMttoContrat3: 0
          },
          {
            descMtto: 'Total',
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
            porCientoMttoContrat3: 0
          }
        ],
        columnsAnexoDos: [
          {name: 'descMtto',required: true,align: 'left',field: row => row.descMtto,format: val => `${val}`,sortable: true},
          {name: 'unidadMedida',align: 'left',field: 'unidadMedida',sortable: true,headerStyle: 'width: 500px',headerClasses: 'my-special-class'},
          {name: 'accPrevTPlan',field: 'accPrevTPlan',sortable: true,align: 'center'},
          {name: 'accPrevTReal',field: 'accPrevTReal',align: 'center'},
          {name: 'accPrevTPorCiento',field: 'accPrevTPorCiento',align: 'center',classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'accPrevCPlan',field: 'accPrevCPlan',align: 'center'},
          {name: 'accPrevCReal',align: 'center',field: 'accPrevCReal',sortable: true},
          {name: 'accPrevCPorCiento',align: 'center',field: 'accPrevCPorCiento',sortable: true,classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'impTotal',field: 'impTotal',align: 'center',sortable: true},
          {name: 'impContratado',field: 'impContratado',align: 'center',sortable: true},
          {name: 'totalAccMtto',field: 'totalAccMtto',align: 'center',sortable: true,classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'porCientoImpAcc',field: 'porCientoImpAcc',align: 'center',sortable: true,classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'}
        ],
        columnsAnexoTres: [
          {name: 'descMtto',required: true,align: 'left',field: row => row.descMtto,format: val => `${val}`,sortable: true},
          {name: 'unidadMedida', align: 'left', field: 'unidadMedida', sortable: true,headerStyle: 'width: 500px',headerClasses: 'my-special-class'},
          {name: 'prespMttoTotal',field: 'prespMttoTotal',align: 'center',classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'prespMttoT1',field: 'prespMttoT1',sortable: true, align: 'center'},
          {name: 'prespMttoT2', field: 'prespMttoT2',sortable: true, align: 'center'},
          {name: 'prespMttoContrat',field: 'prespMttoContrat',align: 'center',classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'prespMttoC1', field: 'prespMttoC1',sortable: true, align: 'center'},
          {name: 'prespMttoC2', field: 'prespMttoC2',sortable: true, align: 'center'},
          {name: 'realMttoTotal',field: 'realMttoTotal', align: 'center', classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'realMttoT1', field: 'realMttoT1',sortable: true, align: 'center'},
          {name: 'realMttoT2', field: 'realMttoT2',sortable: true, align: 'center'},
          {name: 'realMttoContrat',field: 'realMttoContrat', align: 'center',classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'realMttoC1',  field: 'realMttoC1',sortable: true, align: 'center'},
          {name: 'realMttoC2', field: 'realMttoC2',sortable: true, align: 'center'},
          {name: 'porCientoMttoTot1',field: 'porCientoMttoTot1', align: 'center', classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'porCientoMttoTot2',field: 'porCientoMttoTot2', align: 'center', classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'porCientoMttoTot3',field: 'porCientoMttoTot3', align: 'center', classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'porCientoMttoContrat1',field: 'porCientoMttoContrat1', align: 'center', classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'porCientoMttoContrat2',field: 'porCientoMttoContrat2', align: 'center', classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'},
          {name: 'porCientoMttoContrat3',field: 'porCientoMttoContrat3', align: 'center', classes: 'bg-grey-2 ellipsis',style: 'max-width: 100px',headerClasses: 'bg-primary text-white'}
        ],
        headerMakerAnexoDos: [
          [
            {
              label: 'Mantenimiento agrupado por elementos de gastos',
              colspan: '1',
              rowspan: '3',
            },
            {
              label: 'UM',
              colspan: '1',
              rowspan: '3'
            },
            {
              label: 'Acciones Preventivas',
              colspan: '6'
            },
            {
              label: 'Imprevistos',
              colspan: '2'
            },
            {
              label: 'Total',
              colspan: '1'
            },
            {
              label: '% (Imprev/',
              colspan: '1'
            }
          ],
          [
            {
              label: 'Total',
              colspan: '3'
            },
            {
              label: 'De ello: Contratado ',
              colspan: '3'
            },
            {
              label: 'Total',
              colspan: '1',
              rowspan: '2'
            },
            {
              label: 'Contratado',
              colspan: '1',
              rowspan: '2'
            },
            {
              label: 'Acciones',
              colspan: '1'
            },
            {
              label: 'Total Acc.',
              colspan: '1'
            }
          ],
          [
            {
              label: 'Plan',
              colspan: '1'
            },
            {
              label: 'Real',
              colspan: '1'
            },
            {
              label: '%',
              colspan: '1'
            },
            {
              label: 'Plan',
              colspan: '1'
            },
            {
              label: 'Real',
              colspan: '1'
            },
            {
              label: '%',
              colspan: '1'
            },
            {
              label: 'Mantenimiento',
              colspan: '1'
            },
            {
              label: 'Mtto.)',
              colspan: '1'
            }
          ]
        ],
        headerMakerAnexoTres: [
          [
            {
              label: 'Mantenimiento agrupado por elementos de gastos',
              colspan: '1',
              rowspan: '2',
            },
            {
              label: 'UM',
              colspan: '1',
              rowspan: '2'
            },
            {
              label: 'Presupuesto de Mantenimiento',
              colspan: '6'
            },
            {
              label: 'Real Ejecutado de Mantenimiento',
              colspan: '6'
            },
            {
              label: '% de Ejecución de Mantenimiento',
              colspan: '6'
            }
          ],
          [
            {
              label: 'Total',
              colspan: '3'
            },
            {
              label: 'De ello: Contratado',
              colspan: '3'
            },
            {
              label: 'Total',
              colspan: '3'
            },
            {
              label: 'De ello: Contratado',
              colspan: '3'
            },
            {
              label: 'Total',
              colspan: '3'
            },
            {
              label: 'De ello: Contratado',
              colspan: '3'
            },
          ]
        ],
        form_data:[],
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
        filterInput: false,
        loading: false,
        filter: '',
        mapa: false,
        instalaciones: '',
        entidad: '',
        pagination: {
          page: 1,
          rowsNumber: 0
        },
      };
    },
    computed: {
      anno() {
        let anno = [];
        let year = new Date().getFullYear()
        let count = 0;
        while (count < 5) {
          anno.push(year - count)
          count++;
        }
        return anno;
      }
    },
    mounted() {
      this.addToHomeBreadcrumbs([
        { label: 'Dirección de Servicios Técnicos' },
        { label: 'Gestión de mantenimiento' },
        { label: 'Anexos' },
      ]);
      this.loadData()
    },
    watch: {
      pagination: {
        handler() {
          this.loadData({
            pagination: this.pagination,
            filter: this.filter
          })
        }
      },
    },
    methods: {
      ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
      ...mapActions('maintenance', ['deleteAnexos']),
      ...mapActions('maintenance', ['getAnexosXInstalacion']),
      async loadData() {
        this.loading = true
        const datos = {
          id_instalacion: this.form_mante.instalacion_id,
        }
        await this.getAnexosXInstalacion(datos).then(result => {
          this.data = result.data;
          if(this.data != undefined){
            for (let i = 0; i < this.data.length; i++){
              const month = this.meses.find( arr => arr.id === this.data[i]['mes'] );
              this.data[i]['mes'] = month['mes'];
            }
          }
        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
        this.loading = false
      },
      filterStatus() {
        if (this.filterInput) {
          this.filterInput = false;
          this.filter = '';
        } else {
          this.filterInput = true;
        }
      },
      closeModal() {
        this.modalDialogAnexo2 = false;
        this.modalDialogDelete = false;
        // this.reset_form();
        // this.selected = [];
      },
      porcientoAptpp() {
        let valorAptp = 0
        let valorAptr = 0
        let valorTotal = 0
        for (let i = 0; i < this.datasAnexoDos.length - 1; i++) {
          if (parseInt(this.datasAnexoDos[i].accPrevTReal) >= 0 && parseInt(this.datasAnexoDos[i].accPrevTPlan) >= 0) {
            const valor =(parseInt(this.datasAnexoDos[i].accPrevTReal) * 100) / parseInt(this.datasAnexoDos[i].accPrevTPlan)
            this.datasAnexoDos[i].accPrevTPorCiento = numberFormat(valor,2,'.','')
            valorAptp = valorAptp + parseInt(this.datasAnexoDos[i].accPrevTPlan)
            valorAptr = valorAptr + parseInt(this.datasAnexoDos[i].accPrevTReal)
            valorTotal = ((valorAptr * 100) / valorAptp)
            this.datasAnexoDos[this.datasAnexoDos.length - 1].accPrevTPlan = valorAptp
            this.datasAnexoDos[this.datasAnexoDos.length - 1].accPrevTReal = valorAptr
            this.datasAnexoDos[this.datasAnexoDos.length - 1].accPrevTPorCiento = numberFormat(valorTotal,2,'.','')
          }
        }
      },
      pocientoAptcc() {
        let valorApcp = 0
        let valorApcr = 0
        let valorTotal = 0
        for (let i = 0; i < this.datasAnexoDos.length - 1; i++) {
          if (parseInt(this.datasAnexoDos[i].accPrevCPlan) >= 0 && parseInt(this.datasAnexoDos[i].accPrevCReal) >= 0) {
            const valor =(parseInt(this.datasAnexoDos[i].accPrevCReal) * 100) / parseInt(this.datasAnexoDos[i].accPrevCPlan)
            this.datasAnexoDos[i].accPrevCPorCiento = numberFormat(valor,2,'.','')
            valorApcp = valorApcp + parseInt(this.datasAnexoDos[i].accPrevCPlan)
            valorApcr = valorApcr + parseInt(this.datasAnexoDos[i].accPrevCReal)
            valorTotal = ((valorApcr * 100) / valorApcp)
            this.datasAnexoDos[this.datasAnexoDos.length - 1].accPrevCPlan = valorApcp
            this.datasAnexoDos[this.datasAnexoDos.length - 1].accPrevCReal = valorApcr
            this.datasAnexoDos[this.datasAnexoDos.length - 1].accPrevCPorCiento = numberFormat(valorTotal,2,'.','')
          }
        }
      },
      porciento() {
        let valorIt = 0
        let valorIc = 0
        let valorTotal = 0
        let valortotalAccMtto = 0
        for (let i = 0; i < this.datasAnexoDos.length - 1; i++) {
          if (parseInt(this.datasAnexoDos[i].accPrevTReal) >= 0 && parseInt(this.datasAnexoDos[i].impTotal) >= 0) {
            const valorsuma = (parseInt(this.datasAnexoDos[i].accPrevTReal) + parseInt(this.datasAnexoDos[i].impTotal))
            this.datasAnexoDos[i].totalAccMtto = numberFormat(valorsuma,2,'.','')
            const valor = (parseInt(this.datasAnexoDos[i].impTotal) * 100) / parseInt(this.datasAnexoDos[i].totalAccMtto)
            this.datasAnexoDos[i].porCientoImpAcc = numberFormat(valor,2,'.','')

            valorIt = valorIt + parseInt(this.datasAnexoDos[i].impTotal)
            valorIc = valorIc + parseInt(this.datasAnexoDos[i].impContratado)
            valortotalAccMtto = valortotalAccMtto + parseInt(this.datasAnexoDos[i].totalAccMtto)
            this.datasAnexoDos[this.datasAnexoDos.length - 1].impTotal = valorIt
            this.datasAnexoDos[this.datasAnexoDos.length - 1].impContratado = valorIc
            this.datasAnexoDos[this.datasAnexoDos.length - 1].totalAccMtto = valortotalAccMtto
            valorTotal = ((valorIt * 100) / valortotalAccMtto)
            this.datasAnexoDos[this.datasAnexoDos.length - 1].porCientoImpAcc = numberFormat(valorTotal,2,'.','')
          }
        }
      },
      porcientoHdf() {
        if (this.form_mante.hfo >= 0 && this.form_mante.hdd >= 0) {
          const valor = this.form_mante.hfo / this.form_mante.hdd
          this.form_mante.porcientoH = numberFormat(valor,2,'.','')
        } else {
          this.form_mante.porcientoH = 0
        }
      },
      async enviarAnexoDos() {
        if (this.instalacion_id === '' || this.form_mante.anno === '' || this.form_mante.mes === '') {
          warningMessage('Faltan datos por llenar')
        } else {
          this.loading = true;
          const datos = {
            datas: this.datasAnexoDos,
            mantenimiento: {
              descripcion: 'Anexo 2',
              instalacion_id: this.form_mante.instalacion_id,
              anno: this.form_mante.anno,
              mes: this.form_mante.mes
            },
            attributos: {
              hdd: this.form_mante.hdd,
              hfo: this.form_mante.hfo,
              porcientoH: this.form_mante.porcientoH,
            },
            anexo: 2
          }

          return Vue.prototype.$axios.post('/api/add_anexo', datos)
            .then((result) => {
              if (result.data.data == 500) {
                errorMessage(result.data.message, 'error')
              }else {
                successMessage('Anexo guardado satisfactoriamente')
                this.modalDialogAnexo2 = false;
                this.loadData();
              }
              this.loading = false;
            }).catch(err => {
              errorMessage(message.eDataError, err)
            })

        }
      },
      calculoDePmtTablaTres() {
        let totalPmt = 0
        let totalPmta = 0
        let totalPmtb = 0
        for (let i = 0; i < this.datasAnexoTres.length - 1; i++) {
          if (parseInt(this.datasAnexoTres[i].prespMttoT1) >= 0 && parseInt(this.datasAnexoTres[i].prespMttoT2) >= 0) {
            const valor =(parseInt(this.datasAnexoTres[i].prespMttoT1) + parseInt(this.datasAnexoTres[i].prespMttoT2))
            this.datasAnexoTres[i].prespMttoTotal = numberFormat(valor,2,'.','')
            totalPmt = totalPmt + parseInt(this.datasAnexoTres[i].prespMttoTotal)
            totalPmta = totalPmta + parseInt(this.datasAnexoTres[i].prespMttoT1)
            totalPmtb = totalPmtb + parseInt(this.datasAnexoTres[i].prespMttoT2)
            this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoTotal = totalPmt
            this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoT1 = totalPmta
            this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoT2 = totalPmtb
            const valorporCientoMttoTot1 =(parseInt(this.datasAnexoTres[i].realMttoTotal) * 100 / parseInt(this.datasAnexoTres[i].prespMttoTotal))
            const valorporCientoMttoTot2 =(parseInt(this.datasAnexoTres[i].realMttoT1) * 100 / parseInt(this.datasAnexoTres[i].prespMttoT1))
            const valorporCientoMttoTot3 =(parseInt(this.datasAnexoTres[i].realMttoT2) * 100 / parseInt(this.datasAnexoTres[i].prespMttoT2))
            this.datasAnexoTres[i].porCientoMttoTot1 = numberFormat(valorporCientoMttoTot1,2,'.','')
            this.datasAnexoTres[i].porCientoMttoTot2 = numberFormat(valorporCientoMttoTot2,2,'.','')
            this.datasAnexoTres[i].porCientoMttoTot3 = numberFormat(valorporCientoMttoTot3,2,'.','')
            const valorporCientoMttoTot1T =(parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoTotal) * 100 / totalPmt)
            const valorporCientoMttoTot2T =(parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoT1) * 100 / totalPmta)
            const valorporCientoMttoTot3T =(parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoT2) * 100 / totalPmtb)
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoTot1 = numberFormat(valorporCientoMttoTot1T,2,'.','')
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoTot2 = numberFormat(valorporCientoMttoTot2T,2,'.','')
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoTot3 = numberFormat(valorporCientoMttoTot3T,2,'.','')
          }
        }
      },
      calculoDePmcTablaTres() {
        let totalPmc = 0
        let totalPmca = 0
        let totalPmcb = 0
        for (let i = 0; i < this.datasAnexoTres.length - 1; i++) {
          if (parseInt(this.datasAnexoTres[i].prespMttoC1) >= 0 && parseInt(this.datasAnexoTres[i].prespMttoC2) >= 0) {
            const valor =(parseInt(this.datasAnexoTres[i].prespMttoC1) + parseInt(this.datasAnexoTres[i].prespMttoC2))
            this.datasAnexoTres[i].prespMttoContrat = numberFormat(valor,2,'.','')
            totalPmc = totalPmc + parseInt(this.datasAnexoTres[i].prespMttoContrat)
            totalPmca = totalPmca + parseInt(this.datasAnexoTres[i].prespMttoC1)
            totalPmcb = totalPmcb + parseInt(this.datasAnexoTres[i].prespMttoC2)
            this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoContrat = totalPmc
            this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoC1 = totalPmca
            this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoC2 = totalPmcb
            const valorporCientoMttoContrat1 =(parseInt(this.datasAnexoTres[i].realMttoContrat) * 100 / parseInt(this.datasAnexoTres[i].prespMttoContrat))
            const valorporCientoMttoContrat2 =(parseInt(this.datasAnexoTres[i].realMttoC1) * 100 / parseInt(this.datasAnexoTres[i].prespMttoC1))
            const valorporCientoMttoContrat3 =(parseInt(this.datasAnexoTres[i].realMttoC2) * 100 / parseInt(this.datasAnexoTres[i].prespMttoC2))
            this.datasAnexoTres[i].porCientoMttoContrat1 = numberFormat(valorporCientoMttoContrat1,2,'.','')
            this.datasAnexoTres[i].porCientoMttoContrat2 = numberFormat(valorporCientoMttoContrat2,2,'.','')
            this.datasAnexoTres[i].porCientoMttoContrat3 = numberFormat(valorporCientoMttoContrat3,2,'.','')
            const valorporCientoMttoContrat1T =(parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoContrat) * 100 / totalPmc)
            const valorporCientoMttoContrat2T =(parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoC1) * 100 / totalPmca)
            const valorporCientoMttoContrat3T =(parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoC2) * 100 / totalPmcb)
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoContrat1 = numberFormat(valorporCientoMttoContrat1T,2,'.','')
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoContrat2 = numberFormat(valorporCientoMttoContrat2T,2,'.','')
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoContrat3 = numberFormat(valorporCientoMttoContrat3T,2,'.','')
          }
        }
      },
      calculoDeRmtTablaTres() {
        let totalRmt = 0
        let totalRmta = 0
        let totalRmtb = 0
        for (let i = 0; i < this.datasAnexoTres.length - 1; i++) {
          if (parseInt(this.datasAnexoTres[i].realMttoT1) >= 0 && parseInt(this.datasAnexoTres[i].realMttoT2) >= 0) {
            const valor =(parseInt(this.datasAnexoTres[i].realMttoT1) + parseInt(this.datasAnexoTres[i].realMttoT2))
            this.datasAnexoTres[i].realMttoTotal = numberFormat(valor,2,'.','')
            totalRmt = totalRmt + parseInt(this.datasAnexoTres[i].realMttoTotal)
            totalRmta = totalRmta + parseInt(this.datasAnexoTres[i].realMttoT1)
            totalRmtb = totalRmtb + parseInt(this.datasAnexoTres[i].realMttoT2)
            this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoTotal = totalRmt
            this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoT1 = totalRmta
            this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoT2 = totalRmtb
            const valorporCientoMttoTot1 =(parseInt(this.datasAnexoTres[i].realMttoTotal) * 100 / parseInt(this.datasAnexoTres[i].prespMttoTotal))
            const valorporCientoMttoTot2 =(parseInt(this.datasAnexoTres[i].realMttoT1) * 100 / parseInt(this.datasAnexoTres[i].prespMttoT1))
            const valorporCientoMttoTot3 =(parseInt(this.datasAnexoTres[i].realMttoT2) * 100 / parseInt(this.datasAnexoTres[i].prespMttoT2))
            this.datasAnexoTres[i].porCientoMttoTot1 = numberFormat(valorporCientoMttoTot1,2,'.','')
            this.datasAnexoTres[i].porCientoMttoTot2 = numberFormat(valorporCientoMttoTot2,2,'.','')
            this.datasAnexoTres[i].porCientoMttoTot3 = numberFormat(valorporCientoMttoTot3,2,'.','')
            const valorporCientoMttoTot1T =(totalRmt * 100 / parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoTotal))
            const valorporCientoMttoTot2T =(totalRmta * 100 / parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoT1))
            const valorporCientoMttoTot3T =(totalRmtb * 100 / parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoT2))
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoTot1 = numberFormat(valorporCientoMttoTot1T,2,'.','')
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoTot2 = numberFormat(valorporCientoMttoTot2T,2,'.','')
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoTot3 = numberFormat(valorporCientoMttoTot3T,2,'.','')
          }
        }
      },
      calculoDeRmcTablaTres() {
        let totalRmc = 0
        let totalRmca = 0
        let totalRmcb = 0
        for (let i = 0; i < this.datasAnexoTres.length - 1; i++) {
          if (parseInt(this.datasAnexoTres[i].realMttoC1) >= 0 && parseInt(this.datasAnexoTres[i].realMttoC2) >= 0) {
            const valor =(parseInt(this.datasAnexoTres[i].realMttoC1) + parseInt(this.datasAnexoTres[i].realMttoC2))
            this.datasAnexoTres[i].realMttoContrat = numberFormat(valor,2,'.','')
            totalRmc = totalRmc + parseInt(this.datasAnexoTres[i].realMttoContrat)
            totalRmca = totalRmca + parseInt(this.datasAnexoTres[i].realMttoC1)
            totalRmcb = totalRmcb + parseInt(this.datasAnexoTres[i].realMttoC2)
            this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoContrat = totalRmc
            this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoC1 = totalRmca
            this.datasAnexoTres[this.datasAnexoTres.length - 1].realMttoC2 = totalRmcb
            const valorporCientoMttoContrat1 =(parseInt(this.datasAnexoTres[i].realMttoContrat) * 100 / parseInt(this.datasAnexoTres[i].prespMttoContrat))
            const valorporCientoMttoContrat2 =(parseInt(this.datasAnexoTres[i].realMttoC1) * 100 / parseInt(this.datasAnexoTres[i].prespMttoC1))
            const valorporCientoMttoContrat3 =(parseInt(this.datasAnexoTres[i].realMttoC2) * 100 / parseInt(this.datasAnexoTres[i].prespMttoC2))
            this.datasAnexoTres[i].porCientoMttoContrat1 = numberFormat(valorporCientoMttoContrat1,2,'.','')
            this.datasAnexoTres[i].porCientoMttoContrat2 = numberFormat(valorporCientoMttoContrat2,2,'.','')
            this.datasAnexoTres[i].porCientoMttoContrat3 = numberFormat(valorporCientoMttoContrat3,2,'.','')
            const valorporCientoMttoContrat1T =(totalRmc * 100 / parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoContrat))
            const valorporCientoMttoContrat2T =(totalRmca * 100 / parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoC1))
            const valorporCientoMttoContrat3T =(totalRmcb * 100 / parseInt(this.datasAnexoTres[this.datasAnexoTres.length - 1].prespMttoC2))
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoContrat1 = numberFormat(valorporCientoMttoContrat1T,2,'.','')
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoContrat2 = numberFormat(valorporCientoMttoContrat2T,2,'.','')
            this.datasAnexoTres[this.datasAnexoTres.length - 1].porCientoMttoContrat3 = numberFormat(valorporCientoMttoContrat3T,2,'.','')
          }
        }
      },
      async enviarAnexoTres() {
        if (this.instalacion_id === '' || this.form_mante.anno === '' || this.form_mante.mes === '') {
          warningMessage('Faltan datos por llenar')
        } else {
          this.loading = true;
          const datos = {
            datas: this.datasAnexoTres,
            mantenimiento: {
              descripcion: 'Anexo 3',
              instalacion_id: this.form_mante.instalacion_id,
              anno: this.form_mante.anno,
              mes: this.form_mante.mes
            },
            anexo: 3
          }
          return Vue.prototype.$axios.post('/api/add_anexo', datos)
            .then((result) => {
              if (result.data.data == 500) {
                errorMessage(result.data.message, 'error')
              }else {
                successMessage('Anexo guardado satisfactoriamente')
                this.modalDialogAnexo3 = false;
                this.loadData();
              }
              this.loading = false;
            }).catch(err => {
              errorMessage(message.eDataError, err)
            })

        }
      },
      openModalDelete(rowData) {
          if (rowData) {
            this.setFormData(rowData)
            this.modalDialogDelete = true;
          } else {
            infoMessage('Debe seleccionar una fila para modificar.');
          }
      },
      setFormData(data) {
        this.form_data = Object.assign(this.form_data, data)
      },
      async deleteElement() {
        await this.deleteAnexos(this.form_data).then(result => {
          if (result.success === true) {
            this.loadData()
            successMessage('Dato eliminado satisfactoriamente')
            this.closeModal()
          }
        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
      },
    },
  };
</script>

<style scoped></style>
