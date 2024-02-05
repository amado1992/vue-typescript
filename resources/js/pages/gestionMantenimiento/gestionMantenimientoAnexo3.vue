<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section class="no-padding">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              Anexo No.3 de la Resolución No. 150 de 2010
            </q-toolbar-title>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <div class="row">
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
            <div class="col">
              <div class="q-pa-md" style="max-width: 300px">
                <div class="q-gutter-md">
                  <q-select
                    class="col-3"
                    id="entidad"
                    label="Entidades"
                    v-model="form_mante.entidad_id"
                    dense
                    input-debounce="0"
                    :options="entidades"
                    options-dense
                    option-label="nombre" option-value="id"
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
          </div>
          <div class="q-pa-md">
            <q-table
              :data="datas"
              dense
              :rows-per-page-options="[35]"
              :columns="columns"
              row-key="name"
              binary-state-sort
            >
              <template v-slot:header="props" style="border-spacing: 1px">
                <q-tr v-for="(row, index) in headerMaker" :key="index">
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
  </div>
</template>

<script>
import {errorMessage, warningMessage,successMessage} from '../../utils/notificaciones'
import Vue from "vue";
import * as message from "../../utils/resources";
import {mapActions, mapGetters} from "vuex";
import {getListEntidad} from "../../store/gestion_entidades/entidad/actions";
import {numberFormat} from "highcharts";

export default {
  name: 'gestionMantenimiento',
  data() {
    return {
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
      form_data: {
        mes: '',
        anno: '',
        mayor: '',
        menor: '',
        igual: '',
        indicador: '',
      },
      headerMaker: [
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
      columns: [
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
      datas: [
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
      loading: false,
      entidades: [],
      form_mante: {
        entidad_id: '',
        entidades: [],
        mes: '',
        hdd: 0,
        hfo: 0,
        porcientoH: 0,
        anno: ''
      }
    }
  },
  computed: {
    ...mapGetters('entidad', ['getterEntidad']),
    anno() {
      let anno = [];
      let year = new Date().getFullYear()
      let count = 10;
      while (count > 0) {
        anno.push(year - count)
        count--;
      }
      anno.push(year)
      return anno;
    }
  },
  created() {
  },
  mounted() {
    this.loadData();
    this.loadEntidades()
    this.addToHomeBreadcrumbs([
      {label: 'Operaciones y calidad'},
      {label: 'Servicios técnicos'},
      {label: 'Gestión de mantenimiento'},
      {label: 'Anexo 3'}])
  },
  watch: {
    'form_mante.entidades': function () {
      if(this.form_mante.entidades !== '')
        this.loadData()
    },
  },
  methods: {
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('entidad', ['getEntidades']),
    loadData() {
      for (let i = 0; i < this.datas.length; i++) {
        this.datas[i].accPrevTPlan = 0
        this.datas[i].accPrevTReal = 0
        this.datas[i].accPrevTPorCiento = 0
        this.datas[i].accPrevCPlan = 0
        this.datas[i].accPrevCReal = 0
        this.datas[i].accPrevCPorCiento = 0
        this.datas[i].impTotal = 0
        this.datas[i].impContratado = 0
        this.datas[i].totalAccMtto = 0
        this.datas[i].porCientoImpAcc = 0
      }
      this.form_mante.hfo = 0
      this.form_mante.hdd = 0
      this.form_mante.porcientoH = 0
    },
    async loadEntidades() {
      this.loading = true;
      await this.getEntidades(this.form_mante).then(result => {
        this.entidades = result;
      })
      this.loading = false;
    },
    // porcientoAptpp() {
    //   let valorAptp = 0
    //   let valorAptr = 0
    //   let valorTotal = 0
    //   for (let i = 0; i < this.datas.length - 1; i++) {
    //     if (parseInt(this.datas[i].accPrevTReal) > 0 && parseInt(this.datas[i].accPrevTPlan) > 0) {
    //       const valor =(parseInt(this.datas[i].accPrevTReal) * 100) / parseInt(this.datas[i].accPrevTPlan)
    //       this.datas[i].accPrevTPorCiento = numberFormat(valor,2,'.','')
    //       valorAptp = valorAptp + parseInt(this.datas[i].accPrevTPlan)
    //       valorAptr = valorAptr + parseInt(this.datas[i].accPrevTReal)
    //       valorTotal = ((valorAptr * 100) / valorAptp)
    //       this.datas[this.datas.length - 1].accPrevTPlan = valorAptp
    //       this.datas[this.datas.length - 1].accPrevTReal = valorAptr
    //       this.datas[this.datas.length - 1].accPrevTPorCiento = numberFormat(valorTotal,2,'.','')
    //     }
    //   }
    // },
    calculoDePmtTablaTres() {
      let totalPmt = 0
      let totalPmta = 0
      let totalPmtb = 0
      for (let i = 0; i < this.datas.length - 1; i++) {
        if (parseInt(this.datas[i].prespMttoT1) >= 0 && parseInt(this.datas[i].prespMttoT2) >= 0) {
          this.datas[i].prespMttoTotal = parseInt(this.datas[i].prespMttoT1) + parseInt(this.datas[i].prespMttoT2)
          totalPmt = totalPmt + parseInt(this.datas[i].prespMttoTotal)
          totalPmta = totalPmta + parseInt(this.datas[i].prespMttoT1)
          totalPmtb = totalPmtb + parseInt(this.datas[i].prespMttoT2)
          this.datas[this.datas.length - 1].prespMttoTotal = totalPmt
          this.datas[this.datas.length - 1].prespMttoT1 = totalPmta
          this.datas[this.datas.length - 1].prespMttoT2 = totalPmtb
          this.datas[i].porCientoMttoTot1 = parseInt(this.datas[i].realMttoTotal) * 100 / parseInt(this.datas[i].prespMttoTotal)
          this.datas[i].porCientoMttoTot2 = parseInt(this.datas[i].realMttoT1) * 100 / parseInt(this.datas[i].prespMttoT1)
          this.datas[i].porCientoMttoTot3 = parseInt(this.datas[i].realMttoT2) * 100 / parseInt(this.datas[i].prespMttoT2)
          this.datas[this.datas.length - 1].porCientoMttoTot1 = this.datas[this.datas.length - 1].realMttoTotal * 100 / this.datas[this.datas.length - 1].prespMttoTotal
          this.datas[this.datas.length - 1].porCientoMttoTot2 = this.datas[this.datas.length - 1].realMttoT1 * 100 / this.datas[this.datas.length - 1].prespMttoT1
          this.datas[this.datas.length - 1].porCientoMttoTot3 = this.datas[this.datas.length - 1].realMttoT2 * 100 / this.datas[this.datas.length - 1].prespMttoT2
        }
      }
    },
    calculoDePmcTablaTres() {
      let totalPmc = 0
      let totalPmca = 0
      let totalPmcb = 0
      for (let i = 0; i < this.datas.length - 1; i++) {
        if (parseInt(this.datas[i].prespMttoC1) >= 0 && parseInt(this.datas[i].prespMttoC2) >= 0) {
          this.datas[i].prespMttoContrat = parseInt(this.datas[i].prespMttoC1) + parseInt(this.datas[i].prespMttoC2)
          totalPmc = totalPmc + parseInt(this.datas[i].prespMttoContrat)
          totalPmca = totalPmca + parseInt(this.datas[i].prespMttoC1)
          totalPmcb = totalPmcb + parseInt(this.datas[i].prespMttoC2)
          this.datas[this.datas.length - 1].prespMttoContrat = totalPmc
          this.datas[this.datas.length - 1].prespMttoC1 = totalPmca
          this.datas[this.datas.length - 1].prespMttoC2 = totalPmcb
          this.datas[i].porCientoMttoContrat1 = parseInt(this.datas[i].realMttoContrat) * 100 / parseInt(this.datas[i].prespMttoContrat)
          this.datas[i].porCientoMttoContrat2 = parseInt(this.datas[i].realMttoC1) * 100 / parseInt(this.datas[i].prespMttoC1)
          this.datas[i].porCientoMttoContrat3 = parseInt(this.datas[i].realMttoC2) * 100 / parseInt(this.datas[i].prespMttoC2)
          this.datas[this.datas.length - 1].porCientoMttoContrat1 = this.datas[this.datas.length - 1].realMttoContrat * 100 / this.datas[this.datas.length - 1].prespMttoContrat
          this.datas[this.datas.length - 1].porCientoMttoContrat2 = this.datas[this.datas.length - 1].realMttoC1 * 100 / this.datas[this.datas.length - 1].prespMttoC1
          this.datas[this.datas.length - 1].porCientoMttoContrat3 = this.datas[this.datas.length - 1].realMttoC2 * 100 / this.datas[this.datas.length - 1].prespMttoC2
        }
      }
    },
    calculoDeRmtTablaTres() {
      let totalRmt = 0
      let totalRmta = 0
      let totalRmtb = 0
      for (let i = 0; i < this.datas.length - 1; i++) {
        if (parseInt(this.datas[i].realMttoT1) >= 0 && parseInt(this.datas[i].realMttoT2) >= 0) {
          this.datas[i].realMttoTotal = parseInt(this.datas[i].realMttoT1) + parseInt(this.datas[i].realMttoT2)
          totalRmt = totalRmt + parseInt(this.datas[i].realMttoTotal)
          totalRmta = totalRmta + parseInt(this.datas[i].realMttoT1)
          totalRmtb = totalRmtb + parseInt(this.datas[i].realMttoT2)
          this.datas[this.datas.length - 1].realMttoTotal = totalRmt
          this.datas[this.datas.length - 1].realMttoT1 = totalRmta
          this.datas[this.datas.length - 1].realMttoT2 = totalRmtb
          this.datas[i].porCientoMttoTot1 = parseInt(this.datas[i].realMttoTotal) * 100 / parseInt(this.datas[i].prespMttoTotal)
          this.datas[i].porCientoMttoTot2 = parseInt(this.datas[i].realMttoT1) * 100 / parseInt(this.datas[i].prespMttoT1)
          this.datas[i].porCientoMttoTot3 = parseInt(this.datas[i].realMttoT2) * 100 / parseInt(this.datas[i].prespMttoT2)
          this.datas[this.datas.length - 1].porCientoMttoTot1 = this.datas[this.datas.length - 1].realMttoTotal * 100 / this.datas[this.datas.length - 1].prespMttoTotal
          this.datas[this.datas.length - 1].porCientoMttoTot2 = this.datas[this.datas.length - 1].realMttoT1 * 100 / this.datas[this.datas.length - 1].prespMttoT1
          this.datas[this.datas.length - 1].porCientoMttoTot3 = this.datas[this.datas.length - 1].realMttoT2 * 100 / this.datas[this.datas.length - 1].prespMttoT2
        }
      }
    },
    calculoDeRmcTablaTres() {
      let totalRmc = 0
      let totalRmca = 0
      let totalRmcb = 0
      for (let i = 0; i < this.datas.length - 1; i++) {
        if (parseInt(this.datas[i].realMttoC1) >= 0 && parseInt(this.datas[i].realMttoC2) >= 0) {
          this.datas[i].realMttoContrat = parseInt(this.datas[i].realMttoC1) + parseInt(this.datas[i].realMttoC2)
          totalRmc = totalRmc + parseInt(this.datas[i].realMttoContrat)
          totalRmca = totalRmca + parseInt(this.datas[i].realMttoC1)
          totalRmcb = totalRmcb + parseInt(this.datas[i].realMttoC2)
          this.datas[this.datas.length - 1].realMttoContrat = totalRmc
          this.datas[this.datas.length - 1].realMttoC1 = totalRmca
          this.datas[this.datas.length - 1].realMttoC2 = totalRmcb
          this.datas[i].porCientoMttoContrat1 = parseInt(this.datas[i].realMttoContrat) * 100 / parseInt(this.datas[i].prespMttoContrat)
          this.datas[i].porCientoMttoContrat2 = parseInt(this.datas[i].realMttoC1) * 100 / parseInt(this.datas[i].prespMttoC1)
          this.datas[i].porCientoMttoContrat3 = parseInt(this.datas[i].realMttoC2) * 100 / parseInt(this.datas[i].prespMttoC2)
          this.datas[this.datas.length - 1].porCientoMttoContrat1 = this.datas[this.datas.length - 1].realMttoContrat * 100 / this.datas[this.datas.length - 1].prespMttoContrat
          this.datas[this.datas.length - 1].porCientoMttoContrat2 = this.datas[this.datas.length - 1].realMttoC1 * 100 / this.datas[this.datas.length - 1].prespMttoC1
          this.datas[this.datas.length - 1].porCientoMttoContrat3 = this.datas[this.datas.length - 1].realMttoC2 * 100 / this.datas[this.datas.length - 1].prespMttoC2
        }
      }
    },
    // async enviarAnexoDos() {
    //   if (this.entidad === '' || this.form_mante.anno === '' || this.form_mante.mes === '') {
    //     warningMessage('Faltan datos por llenar')
    //   } else {
    //     this.loading = true;
    //     const datos = {
    //       datas: this.datas,
    //       attributos: this.form_mante,
    //       entidad: this.form_mante.entidad_id
    //     }
    //     return Vue.prototype.$axios.post('/api/add_anexo_dos', datos)
    //       .then((result) => {
    //         successMessage('Anexo guardado satisfactoriamente')
    //         this.loadData();
    //       }).catch(err => {
    //         errorMessage(message.eDataError, err)
    //       })
    //     this.loading = false;
    //   }
    // },
    async enviarAnexoTres() {
      if (this.entidad === '' || this.form_mante.anno === '' || this.form_mante.mes === '') {
        warningMessage('Faltan datos por llenar')
      } else {
        this.loading = true;
        const datos = {
          datas: this.datas,
          attributos: this.form_mante,
          entidad: this.form_mante.entidad_id
        }
        return Vue.prototype.$axios.post('/api/add_anexo_tres', datos)
          .then((result) => {
            successMessage('Anexo guardado satisfactoriamente')
          }).catch(err => {
            errorMessage(message.eDataError, err)
          })
        this.loading = false;
      }

    }
  }
}
</script>

<style scoped>

</style>
