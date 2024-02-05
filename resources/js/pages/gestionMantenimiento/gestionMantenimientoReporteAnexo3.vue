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
          :visible-columns="visibleColumns"
          :columns="columns"
          row-key="id"
          :loading="loading"
          loading-label="Cargando elementos"
          :rows-per-page-options="[10,20,30]"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Reportes para los Anexos 3
            </div>
            <q-space></q-space>
            <div class="col-12">
              <div class="row ">
                <div class="q-pa-md col-3">
                  <q-select
                    class="col-3"
                    id="entity"
                    label="Entidad"
                    v-model="data_filter.entidad_id"
                    dense
                    input-debounce="0"
                    :options="listentity"
                    options-dense
                    option-label="nombre"
                    option-value="id"
                  >
                    <template v-slot:prepend>
                      <q-icon name="mdi-fireplace-off"/>
                    </template>
                  </q-select>
                </div>

                <div class="q-pa-md col-3">
                  <q-select
                    class="col-3"
                    id="anno"
                    label="Año"
                    v-model="data_filter.anno"
                    dense
                    input-debounce="0"
                    :options="anno"
                    options-dense
                    option-label="anno"
                    option-value="anno"
                  >
                    <template v-slot:prepend>
                      <q-icon name="mdi-calendar"/>
                    </template>
                  </q-select>
                </div>
                <div class="q-pa-md col-3">
                  <q-select
                    class="col-3"
                    id="mes"
                    label="Mes"
                    v-model="data_filter.mes"
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
            <div class="col-12">
              <div class="row ">
                <div class="q-pa-md col-4">
                  <q-select
                    class="col-3"
                    id="indicator"
                    label="Indicador"
                    v-model="data_filter.indicador"
                    dense
                    input-debounce="0"
                    :options="indicatorslist"
                    options-dense
                    option-label="indicador" option-value="id"
                    emit-value
                    map-options
                  >
                    <template v-slot:prepend>
                      <q-icon name="mdi-fireplace-off"/>
                    </template>
                  </q-select>
                </div>
                <div  class="q-pa-md col-2">
                  <q-input dense v-model="data_filter.mayorigual"  label="% Mayor igual que">
                  </q-input>
                </div>
                <div  class="q-pa-md col-2">
                  <q-input dense v-model="data_filter.menorigual"  label="% Menor igual que">
                  </q-input>
                </div>
                <div class="q-pa-md col-4">
                  <q-select
                    class="col-3"
                    id="elementos"
                    label="Elementos de gastos"
                    v-model="selectModel"
                    dense
                    input-debounce="0"
                    :options="selectOptions"
                    options-dense
                    multiple
                    emit-value
                    map-options
                  >
                    <template v-slot:option="scope">
                      <q-item
                        v-bind="scope.itemProps"
                        v-on="scope.itemEvents"
                      >
                        <q-item-section>
                          <q-item-label v-html="scope.opt.label" ></q-item-label>
                        </q-item-section>
                        <q-item-section side>
                          <q-checkbox v-model="selectModel" :val="scope.opt.value" />
                        </q-item-section>
                      </q-item>
                    </template>
                  </q-select>
                </div>
                <div  class="q-pa-md col-2">
                  <q-btn label="Buscar" type="submit" color="primary" @click="buscarFiltros"/>
                </div>
              </div>
            </div>
            <div class="col-12" >
              <div class="row">
                <div class="q-pa-md col-6">
                  {{title_toolbar}}
                </div>
              </div>
            </div>
          </template>
          <template v-slot:loading>
            <q-inner-loading showing color="primary" />
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
  </div>
</template>
<script>
  import {mapState, mapGetters, mapActions} from 'vuex'
  import {errorMessage, successMessage} from "../../utils/notificaciones";
  import * as message from "../../utils/resources";
  import Vue from "vue";
  export default {
    name: 'maintenanceManagementReportAnexo2',
    data() {
      return {
        data: [],
        dataIndicador1: [],
        list: [],
        dataOrder: [],
        options: [],
        pagination: {
          page: 1,
          rowsNumber: 0
        },
        data_filter:{
          mes: 0,
          anno: 'Todos',
          menorigual: '',
          mayorigual: '',
          indicador: 0,
          entidad_id:''
        },
        meses: [
          {
            id: 0,
            mes: 'Todos'
          },
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
        visibleColumns: [],
        columns: [
          {
            name: 'columnyear',
            align: 'left',
            label: 'Año',
            field: row => row.anno,
            sortable: true,
            headerClasses: 'text-uppercase bg-primary text-white'
          },
          {
            name: 'columnmonth',
            align: 'left',
            label: 'Mes',
            field: row => row.mes,
            sortable: true,
            headerClasses: 'text-uppercase bg-primary text-white'
          },
          {
            name: 'columnprovince',
            align: 'left',
            label: 'Provincia',
            field: row => row.provincia,
            sortable: true,
            headerClasses: 'text-uppercase bg-primary text-white'
          },
          {
            name: 'columnentity1',
            align: 'left',
            label: 'Entidad',
            field: row => row.entidad,
            sortable: true,
            headerClasses: 'text-uppercase bg-primary text-white'
          },
          {
            name: 'columninstallation',
            align: 'left',
            label: 'Instalación',
            field: row => row.instalacion,
            sortable: true,
            headerClasses: 'text-uppercase bg-primary text-white'
          },
          {
            name: 'columndesc',
            align: 'left',
            label: 'Descripción',
            field: row => row.descripcion,
            sortable: true,
            headerClasses: 'text-uppercase bg-primary text-white'
          },
          {
            name: 'columnhfohdd',
            align: 'left',
            label: '% HFOHDD',
            field: row => row.hfohdd,
            sortable: true,
            headerClasses: 'text-uppercase bg-primary text-white'
          },
        ],
        listentity: [],
        title_toolbar:'',
        filter: '',
        filterInput: false,
        indicatorslist: [
          {
            id: 0,
            indicador: 'Ninguno'
          },
          {
            id: 1,
            indicador: '% de Ejecución de Mantenimiento'
          },
          {
            id: 2,
            indicador: 'Mayor % de Inejecución de Mantenimiento x Osde'
          },
          {
            id: 3,
            indicador: 'Mayor % de Inejecución del Presupuesto x Osde'
          }
        ],
        loading: false,
        selectModel: [],
        selectOptions: [
          {
            label: 'Mantenimiento a edificaciones',
            value: 'Mantenimiento a edificaciones'
          },
          {
            label: 'Mantenimiento a mobiliario',
            value: 'Mantenimiento a mobiliario'
          },
          {
            label: 'Mantenimiento a redes técnicas',
            value: 'Mantenimiento a redes técnicas'
          },
          {
            label: 'Mantenimiento a fachadas y pintura exterior',
            value: 'Mantenimiento a fachadas y pintura exterior'
          },
          {
            label: 'Mantenimiento a accesos y viales',
            value: 'Mantenimiento a accesos y viales'
          },
          {
            label: 'Mantenimiento a áreas verdes',
            value: 'Mantenimiento a áreas verdes'
          },
          {
            label: 'Mantenimiento a ascensores',
            value: 'Mantenimiento a ascensores'
          },
          {
            label: 'Mantenimiento a equipos de refrigeración',
            value: 'Mantenimiento a equipos de refrigeración'
          },
          {
            label: 'Mantenimiento a grupos electrógenos',
            value: 'Mantenimiento a grupos electrógenos'
          },
          {
            label: 'Mantenimiento a equipos gastronómicos',
            value: 'Mantenimiento a equipos gastronómicos'
          },
          {
            label: 'Mantenimiento a equipos de tintorería y lavandería',
            value: 'Mantenimiento a equipos de tintorería y lavandería'
          },
          {
            label: 'Mantenimiento a equipos de computación',
            value: 'Mantenimiento a equipos de computación'
          },
          {
            label: 'Mantenimiento a paneles eléctricos',
            value: 'Mantenimiento a paneles eléctricos'
          },
          {
            label: 'Mantenimiento a sistemas de iluminación',
            value: 'Mantenimiento a sistemas de iluminación'
          },
          {
            label: 'Mantenimiento a sistemas y equipos de piscinas',
            value: 'Mantenimiento a sistemas y equipos de piscinas'
          },
          {
            label: 'Mantenimiento a sistemas y equipos de ventilación y extracción',
            value: 'Mantenimiento a sistemas y equipos de ventilación y extracción'
          },
          {
            label: 'Mantenimiento a sistemas y equipos de tratamiento de residuales',
            value: 'Mantenimiento a sistemas y equipos de tratamiento de residuales'
          },
          {
            label: 'Mantenimiento a sistemas y equipos de bombeo',
            value: 'Mantenimiento a sistemas y equipos de bombeo'
          },
          {
            label: 'Mantenimiento a sistemas y equipos electrónicos y de comunicaciones',
            value: 'Mantenimiento a sistemas y equipos electrónicos y de comunicaciones'
          },
          {
            label: 'Mantenimiento a sistemas y equipos de climatización',
            value: 'Mantenimiento a sistemas y equipos de climatización'
          },
          {
            label: 'Mantenimiento a sistemas y equipos de protección y seguridad',
            value: 'Mantenimiento a sistemas y equipos de protección y seguridad'
          },
          {
            label: 'Mantenimiento a sistemas y equipos de almacenam. de combustible y agua',
            value: 'Mantenimiento a sistemas y equipos de almacenam. de combustible y agua'
          },
          {
            label: 'Mantenimiento a sistemas y equipos de producción de vapor y agua caliente',
            value: 'Mantenimiento a sistemas y equipos de producción de vapor y agua caliente'
          },
          {
            label: 'Mantenimiento a equipamiento náutico',
            value: 'Mantenimiento a equipamiento náutico'
          },
          {
            label: 'Mantenimiento a equipamiento de espectáculo',
            value: 'Mantenimiento a equipamiento de espectáculo'
          },
          {
            label: 'Mantenimiento a equipos de medición',
            value: 'Mantenimiento a equipos de medición'
          },
          {
            label: 'Mantenimiento a equipos de taller',
            value: 'Mantenimiento a equipos de taller'
          },
          {
            label: 'Mantenimiento a equipos de transporte automotor',
            value: 'Mantenimiento a equipos de transporte automotor'
          },
          {
            label: 'Mantenimiento a embarcaciones',
            value: 'Mantenimiento a embarcaciones'
          },
          {
            label: 'Mantenimiento a equipos de transporte de mecanización',
            value: 'Mantenimiento a equipos de transporte de mecanización'
          },
          {
            label: 'Otros servicios de mantenimiento',
            value: 'Otros servicios de mantenimiento'
          },
          {
            label: 'Total',
            value: 'Total'
          }
        ],
      }
    },
    computed: {
      anno() {
        let anno = [];
        let year = new Date().getFullYear()
        let count = 0;
        anno.push('Todos');
        while (count < 5) {
          anno.push(year - count)
          count++;
        }
        return anno;
      }
    },
    watch: {
      pagination: {
        handler() {
          this.loadData({
            pagination: this.pagination,
            filter: this.filter
          })
        }
      }
    },
    mounted() {
      this.loadEntidades();
      this.addToHomeBreadcrumbs([
        { label: 'Dirección de Servicios Técnicos' },
        { label: 'Gestión de mantenimiento' },
        { label: 'Reportes Anexo 2' },
      ]);
      this.loadData()
    },
    methods: {
      ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
      ...mapActions('utils', ['setLoading']),
      ...mapActions('maintenance', ['getAnexos3All','getAnexos3Indicador1','getAnexos3Indicador23']),
      ...mapActions('entidad', ['getEntidades']),
      async loadEntidades() {
        await this.getEntidades(this.data_filter).then(result => {
          this.listentity = result;
        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
      },
      async loadData() {
        this.loading = true;
        this.visibleColumns = ['columnyear','columnmonth','columnprovince','columnentity1','columninstallation','columndesc'],
          await this.getAnexos3All().then(result => {
            this.data = result.data;
            for (let i = 0; i < this.data.length; i++){
              const month = this.meses.find( arr => arr.id === this.data[i]['mes'] );
              this.data[i]['mes'] = month['mes'];
            }
            this.auxa = this.data;
          })

        await this.getAnexos3Indicador1().then(result => {
          this.dataIndicador1 = result.data;
          for (let i = 0; i < this.dataIndicador1.length; i++){
            const month = this.meses.find( arr => arr.id === this.dataIndicador1[i]['mes'] );
            this.dataIndicador1[i]['mes'] = month['mes'];
          }
          this.auxb = this.dataIndicador1;
        })

        await this.getAnexos3Indicador23().then(result => {
          this.dataIndicador1 = result.data;
          for (let i = 0; i < this.dataIndicador1.length; i++){
            const month = this.meses.find( arr => arr.id === this.dataIndicador1[i]['mes'] );
            this.dataIndicador1[i]['mes'] = month['mes'];
          }
          this.auxb = this.dataIndicador1;
        })
        this.loading = false;
      },
      filterStatus() {
        if (this.filterInput) {
          this.filterInput = false;
          this.filter = '';
        } else {
          this.filterInput = true;
        }
      },
      buscarFiltros() {
        this.data = this.auxa;
        this.dataIndicador1 = this.auxb;
        const mes = this.data_filter.mes;
        const anno = this.data_filter.anno;
        const menorigual = this.data_filter.menorigual;
        const mayorigual = this.data_filter.mayorigual;
        const indicador = this.data_filter.indicador;
        const entidad_id = this.data_filter.entidad_id;

        this.list = indicador !== 1 ? this.data : this.dataIndicador1;

        if (entidad_id != ''){
          const newArray1 = _.filter (this.list, function(dat) {
            return dat.entidad === entidad_id.nombre ;
          });
          this.list = newArray1;
        }

        if (anno != 'Todos'){
          const newArray1 = _.filter (this.list, function(dat) {
            return dat.anno === anno ;
          });
          this.list = newArray1;
        }

        if (mes != 0){
          const month = this.meses.find( arr => arr.id === mes );
          const desc_month = month['mes'];
          const newArray1 = _.filter (this.list, function(dat) {
            return dat.mes === desc_month ;
          });
          this.list = newArray1;
        }

        if (indicador === 1){
          if(menorigual !== '' && mayorigual !== ''){
            const newArray1 = _.filter (this.list, function(dat) {
              const search = dat.porCientoMttoTot1 >= parseFloat(mayorigual) || dat.porCientoMttoTot2 >= parseFloat(mayorigual) || dat.porCientoMttoTot3 >= parseFloat(mayorigual) || dat.porCientoMttoContrat1 >= parseFloat(mayorigual) || dat.porCientoMttoContrat2 >= parseFloat(mayorigual) || dat.porCientoMttoContrat3 >= parseFloat(mayorigual);
              return search;
            });
            const newArray2 = _.filter (newArray1, function(dat) {
              const search = dat.porCientoMttoTot1 <= parseFloat(menorigual) || dat.porCientoMttoTot2 <= parseFloat(menorigual) || dat.porCientoMttoTot3 <= parseFloat(menorigual) || dat.porCientoMttoContrat1 <= parseFloat(menorigual) || dat.porCientoMttoContrat2 <= parseFloat(menorigual) || dat.porCientoMttoContrat3 <= parseFloat(menorigual);
              return search;
            });
            if (newArray2[0] != undefined){
              this.list = []
              for (let i = 0; i < newArray2.length; i++){
                this.list.push(newArray2[i])
              }
            }else{
              this.list = []
            }
          }else{
            if (menorigual !== ''){
              const newArray1 = _.filter (this.list, function(dat) {
                const search = dat.porCientoMttoTot1 <= parseFloat(menorigual) || dat.porCientoMttoTot2 <= parseFloat(menorigual) || dat.porCientoMttoTot3 <= parseFloat(menorigual) || dat.porCientoMttoContrat1 <= parseFloat(menorigual) || dat.porCientoMttoContrat2 <= parseFloat(menorigual) || dat.porCientoMttoContrat3 <= parseFloat(menorigual);
                return search;
              });
              this.list = newArray1;
            }else{
              if (mayorigual !== ''){
                const newArray1 = _.filter (this.list, function(dat) {
                  const search = dat.porCientoMttoTot1 >= parseFloat(mayorigual) || dat.porCientoMttoTot2 >= parseFloat(mayorigual) || dat.porCientoMttoTot3 >= parseFloat(mayorigual) || dat.porCientoMttoContrat1 >= parseFloat(mayorigual) || dat.porCientoMttoContrat2 >= parseFloat(mayorigual) || dat.porCientoMttoContrat3 >= parseFloat(mayorigual);
                  return search;
                });
                this.list = newArray1;
              }
            }
          }
          if (this.selectModel.length > 0)
          {
            const newArray2 = this.list;
            this.list = [];
            for (let i = 0; i < this.selectModel.length; i++) {
              let opc = this.selectModel[i];
              const newArray1 = _.filter(newArray2, function (dat) {
                return dat.descMtto === opc;
              });
              for (let i = 0; i < newArray1.length; i++){
                this.list.push(newArray1[i])
              }
            }
          }
          this.columns = [
            {name: 'columnyear',align: 'left',field: row => row.anno,label: 'Año',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'columnmonth',align: 'left',field: row => row.mes,label: 'Mes',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'descMtto',align: 'left',field: row => row.descMtto,label: 'Elementos',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'unidadMedida',align: 'left',field: row => row.unidadMedida,label: 'U.M.',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'prespMttoT1',field: row => row.prespMttoT1,align: 'center',label: 'Presupuesto de Mantenimiento Total1',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'prespMttoT2',field: row => row.prespMttoT2,align: 'center',label: 'Presupuesto de Mantenimiento Total2',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'prespMttoTotal',field: row => row.prespMttoTotal,align: 'center',label: 'Presupuesto de Mantenimiento Total',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'prespMttoC1',field: row => row.prespMttoC1,align: 'center',label: 'Presupuesto de Mantenimiento Contratado1',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'prespMttoC2',align: 'center',field: row => row.prespMttoC2,label: 'Presupuesto de Mantenimiento Contratado2',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'prespMttoContrat',align: 'center',field: row => row.prespMttoContrat,label: 'Presupuesto de Mantenimiento Contratado',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'realMttoT1',field: row => row.realMttoT1,align: 'center',label: 'Real Ejecutado de Mantenimiento Total1',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'realMttoT2',field: row => row.realMttoT2,align: 'center',label: 'Real Ejecutado de Mantenimiento Total2',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'realMttoTotal',field: row => row.realMttoTotal,align: 'center',label: 'Real Ejecutado de Mantenimiento Total',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'realMttoC1',field: row => row.realMttoC1,align: 'center',label: 'Real Ejecutado de Mantenimiento Contratado1',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'realMttoC2',field: row => row.realMttoC2,align: 'center',label: 'Real Ejecutado de Mantenimiento Contratado2',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'realMttoContrat',field: row => row.realMttoContrat,align: 'center',label: 'Real Ejecutado de Mantenimiento Contratado',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'porCientoMttoTot1',field: row => row.porCientoMttoTot1,align: 'center',label: '% de Ejecución de Mantenimiento Total1',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'porCientoMttoTot2',field: row => row.porCientoMttoTot2,align: 'center',label: '% de Ejecución de Mantenimiento Total2',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'porCientoMttoTot3',field: row => row.porCientoMttoTot3,align: 'center',label: '% de Ejecución de Mantenimiento Total3',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'porCientoMttoContrat1',field: row => row.porCientoMttoContrat1,align: 'center',label: '% de Ejecución de Mantenimiento Contratado1',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'porCientoMttoContrat2',field: row => row.porCientoMttoContrat2,align: 'center',label: '% de Ejecución de Mantenimiento Contratado2',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'},
            {name: 'porCientoMttoContrat3',field: row => row.porCientoMttoContrat3,align: 'center',label: '% de Ejecución de Mantenimiento Contratado3',sortable: true,headerClasses: 'text-uppercase bg-primary text-white'}
          ]
          this.visibleColumns = ['columnyear','columnmonth','descMtto','unidadMedida','prespMttoT1','prespMttoT2','prespMttoTotal','prespMttoC1',
            'prespMttoC2','prespMttoContrat','realMttoT1','realMttoT2','realMttoTotal','realMttoC1','realMttoC2','realMttoContrat','porCientoMttoTot1',
            'porCientoMttoTot2','porCientoMttoTot3','porCientoMttoContrat1','porCientoMttoContrat2','porCientoMttoContrat3']
        }

        // if (indicador === 1){
        //   if(menorigual !== '' && mayorigual !== ''){
        //     const newArray1 = _.filter (this.list, function(dat) {
        //       return dat.hfohdd <= parseFloat(menorigual);
        //     });
        //     const newArray2 = _.filter (this.list, function(dat) {
        //       return dat.hfohdd > parseFloat(menorigual);
        //     });
        //     const newArray3 = _.filter (newArray2, function(dat) {
        //       return dat.hfohdd >= parseFloat(mayorigual);
        //     });
        //     if (newArray1[0] != undefined && newArray3[0] != undefined){
        //       this.list = []
        //       for (let i = 0; i < newArray1.length; i++){
        //         this.list.push(newArray1[i])
        //       }
        //       for (let i = 0; i < newArray3.length; i++){
        //         this.list.push(newArray3[i])
        //       }
        //     }else{
        //       this.list = []
        //     }
        //   }else{
        //     if (menorigual !== ''){
        //       const newArray1 = _.filter (this.list, function(dat) {
        //         return dat.hfohdd <= parseFloat(menorigual) ;
        //       });
        //       this.list = newArray1;
        //     }else{
        //       if (mayorigual !== ''){
        //         const newArray1 = _.filter (this.list, function(dat) {
        //           return dat.hfohdd >= parseFloat(mayorigual) ;
        //         });
        //         this.list = newArray1;
        //       }
        //     }
        //   }
        //   this.columns = [
        //     {
        //       name: 'columnyear',
        //       align: 'left',
        //       label: 'Año',
        //       field: row => row.anno,
        //       sortable: true,
        //       headerClasses: 'text-uppercase bg-primary text-white'
        //     },
        //     {
        //       name: 'columnmonth',
        //       align: 'left',
        //       label: 'Mes',
        //       field: row => row.mes,
        //       sortable: true,
        //       headerClasses: 'text-uppercase bg-primary text-white'
        //     },
        //     {
        //       name: 'columnprovince',
        //       align: 'left',
        //       label: 'Provincia',
        //       field: row => row.provincia,
        //       sortable: true,
        //       headerClasses: 'text-uppercase bg-primary text-white'
        //     },
        //     {
        //       name: 'columnentity1',
        //       align: 'left',
        //       label: 'Entidad',
        //       field: row => row.entidad,
        //       sortable: true,
        //       headerClasses: 'text-uppercase bg-primary text-white'
        //     },
        //     {
        //       name: 'columninstallation',
        //       align: 'left',
        //       label: 'Instalación',
        //       field: row => row.instalacion,
        //       sortable: true,
        //       headerClasses: 'text-uppercase bg-primary text-white'
        //     },
        //     {
        //       name: 'columndesc',
        //       align: 'left',
        //       label: 'Descripción',
        //       field: row => row.descripcion,
        //       sortable: true,
        //       headerClasses: 'text-uppercase bg-primary text-white'
        //     },
        //     {
        //       name: 'columnhfohdd',
        //       align: 'left',
        //       label: '% HFOHDD',
        //       field: row => row.hfohdd,
        //       sortable: true,
        //       headerClasses: 'text-uppercase bg-primary text-white'
        //     },
        //   ],
        //     this.visibleColumns = ['columnyear','columnmonth','columnprovince','columnentity1','columninstallation','columndesc','columnhfohdd']
        // }


        this.data = this.list;
      },
    }
  }
</script>


