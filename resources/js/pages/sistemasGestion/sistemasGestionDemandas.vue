<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <div  class="q-pb-sm text-subtitle1 text-weight-bold text-uppercase">
          Captación de Demanda
        </div>
        <div class="row">
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
                @request="loadGetRegisters"
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

                </template>
                <template v-slot:body-cell-columnestadoregistro="props">
                  <q-td key="columnestadoregistro" :props="props">
                    <q-badge :color="props.row.estado === '' ? 'white' : 'orange'" style="padding: inherit;">
                      {{ props.row.estado }}
                    </q-badge>
                  </q-td>
                </template>
                <template v-slot:body-cell-columnestadodemanda="props">
                  <q-td key="columnestadodemanda" :props="props">
                    <q-badge :color="props.row.estado_demanda === null ? 'white' : 'orange'" style="padding: inherit;">
                      {{ props.row.estado_demanda }}
                    </q-badge>
                  </q-td>
                </template>
                <template v-slot:body-cell-columnactions="props">
                  <q-td :props="props">
                    <div class="q-gutter-sm">
                      <q-btn
                        :color= "props.row.estado_demanda === null ? 'deep-orange' : 'grey'"
                        glossy
                        :label= "props.row.estado_demanda === null ? 'Incluir en la demanda' : 'En progreso'"
                        size="10px"
                        @click.prevent="openModalDemanda(props.row)"
                      >
                      </q-btn>

                    </div>
                  </q-td>
                </template>
              </q-table>
              <!-- /tabla-->
            </q-card-section>
    </q-card>
<!--     Dialog -->
        <q-dialog
          v-model="modalDialogDemanda"
          persistent
          transition-show="scale"
          transition-hide="scale"
        >
          <q-card style="width: 500px">
            <q-form
              @reset="resetFormAdd"
              ref="formDemanda"
            >
              <q-card-section class="no-padding">
                <q-card-section class="row no-padding">
                  <q-toolbar class="bg-primary text-white">
                    <q-toolbar-title>
                      Incluir en la demanda
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
                  <div class="col-6">
                    <div class="q-pa-md" >
                      <div class="q-gutter-md">
                        <q-select
                          class="col"
                          id="demandaservicio"
                          label="Demanda del servicio"
                          v-model="form_detailsregister.demanda_servicio_id"
                          dense
                          input-debounce="0"
                          :options="listdemandaservicio"
                          options-dense
                          option-label="desc_DemandaServicio" option-value="id"
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
                          <q-input
                            filled
                            clearable
                            type="textarea"
                            color="red-12"
                            label="Alcance"
                            v-model="form_detailsregister.alcance"
                          />
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="q-pa-md" >
                      <template>
                        <div class="q-pa-md">
                          <q-input
                            v-model.number="form_detailsregister.cant_trabajadores"
                            type="number"
                            min="1" pattern="^[0-9]+"
                            label="Cantidad de trabajadores"
                          />
                        </div>
                      </template>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="q-pa-md" >
                      <div class="q-gutter-sm">
                        <q-checkbox
                          v-model="gestionado"
                          color="secondary"
                          label="Sistema gestionado de forma integrada"

                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="q-pa-md" >
                      <q-item>
                        <q-item-section>
                          <q-input :dense="dense" v-model="form_detailsregister.fecha_auditoria" label="Fecha Auditoría">
                            <template v-slot:append>
                              <q-icon name="event" class="cursor-pointer">
                                <q-popup-proxy @before-show="updateDate('fecha_auditoria')" transition-show="scale" transition-hide="scale">
                                  <q-date v-model="f_fecha_auditoriadate">
                                    <div class="row items-center justify-end q-gutter-sm">
                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                      <q-btn label="ok" color="primary" flat @click="saveDate('fecha_auditoria')"  v-close-popup/>
                                    </div>
                                  </q-date>
                                </q-popup-proxy>
                              </q-icon>
                            </template>
                          </q-input>
                        </q-item-section>
                      </q-item>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="q-pa-md" >
                      <div class="q-gutter-sm">
                        <q-checkbox
                          v-model="conciliacion"
                          color="secondary"
                          label="Conciliación de la demanda"

                        />
                      </div>
                      <q-item>
                        <q-item-section>
                          <q-input :dense="dense" v-model="form_detailsregister.fecha_conciliacion" label="Fecha Conciliada">
                            <template v-slot:append>
                              <q-icon name="event" class="cursor-pointer">
                                <q-popup-proxy @before-show="updateDate('fecha_conciliacion')" transition-show="scale" transition-hide="scale">
                                  <q-date v-model="f_fecha_conciliaciondate">
                                    <div class="row items-center justify-end q-gutter-sm">
                                      <q-btn label="cancel" color="primary" flat v-close-popup/>
                                      <q-btn label="ok" color="primary" flat @click="saveDate('fecha_conciliacion')"  v-close-popup/>
                                    </div>
                                  </q-date>
                                </q-popup-proxy>
                              </q-icon>
                            </template>
                          </q-input>
                        </q-item-section>
                      </q-item>
                    </div>
                  </div>
                </q-card-section>
                <q-card-actions align="right">
                  <q-btn type="button" :icon="'save'"
                         @click="editElement(form_data)" label="Guardar"
                         color="secondary" flat/>
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
    name: 'gestiondemanda',
    data() {
      return {
         dense: false,
         scopes: sessionStorage.getItem('scopes'),
         data: [],
        filterInput: false,
        form_data: {
          instalacion_name:'',
          osde_name:'',
          id_installation:''
        },
        listinstalaciones:[],
        listtipossistemas:[],
        listestadosreg:[],
        data_filter: {
          estadosreg:'',
          sistgestion:'',
          instalacion:'',
        },
        form_detailsregister: {
          demanda_servicio_id:'',
          alcance:'',
          cant_trabajadores:'',
          gestionado:'',
          fecha_auditoria:'',
          conciliacion:'',
          fecha_conciliacion:'',
          estado_demanda:''
        },
        gestionado:'',
        conciliacion:'',
        columns: [
          {
            name: 'columnid',
            label: 'id',
            align: 'center',
            field: row => row.registro_id
          },
          {
            name: 'columnentidad',
            label: 'Entidad',
            align: 'left',
            field: row => row.nombre_entidad,
            sortable: true,
            headerClasses: 'text-uppercase'
          },
          {
            name: 'columninstalacion',
            label: 'Instalación',
            align: 'left',
            field: row => row.nombre_instalacion,
            sortable: true,
            headerClasses: 'text-uppercase'
          },
          {
            name: 'columntsistgest',
            label: 'Tipo de Sistema de Gestión',
            align: 'left',
            field: row => row.desc_TipoSistGestion,
            sortable: true,
            headerClasses: 'text-uppercase'
          },
          {
            name: 'columnestadoregistro',
            label: 'Estado del registro',
            align: 'center',
            field: row => row.estado,
            sortable: true,
            headerClasses: 'text-uppercase'
          },
          {
            name: 'columnestadodemanda',
            label: 'Estado de la Demanda',
            align: 'center',
            field: row => row.estado_demanda,
            sortable: true,
            headerClasses: 'text-uppercase'
          },
          {
            name: 'columnactions',
            label: 'Acción',
            align: 'center',
            field: 'action',
            headerClasses: 'text-uppercase'
          }

        ],
        visibleColumns: ['columnentidad','columninstalacion','columntsistgest','columnestadoregistro','columnestadodemanda','columnactions'],
         loading: true,
         filter: '',
         modalDialogDemanda: false,
        listdemandaservicio:[],
        fecha_auditoriadate:'',
        fecha_conciliaciondate:'',
         f_fecha_auditoriadate: '',
         f_fecha_conciliaciondate: '',
        element_id:''
      }
    },
    mounted() {
      this.addToHomeBreadcrumbs([
        {label: 'Dirección de Calidad'},
        {label: 'Sistema de Gestión de la Calidad'},
        {label: 'Demandas'}])
      this.getAutenticatedOsde();
      this.loadDataAuthenticated();
      this.loadGetRegisters();
    },
    watch: {
      pagination: {
        handler() {
          this.loadGetRegisters({
            pagination: this.pagination,
            filter: this.filter
          })
        }
      },
    },
    methods: {
      ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
       ...mapActions('managementsystem', ['getDemandaServicio']),
       ...mapActions('managementsystem', ['getRegistersDemandas']),
      ...mapActions('managementsystem', ['editRegister']),
       ...mapActions('osde', ['getOsdeXID']),

      filterStatus() {
        if (this.filterInput) {
          this.filterInput = false;
          this.filter = "";
        } else {
          this.filterInput = true;
        }
      },
      getAutenticatedOsde() {
        this.userAuthenticated = sessionStorage.getItem('osde_id')
      },
      async loadDataAuthenticated() {
        this.loading = true
        await this.getOsdeXID(this.userAuthenticated).then(result => {
          this.data = result;
          this.form_data.osde_name = this.data['nombre']
        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
        this.loading = false
      },
      async loadGetRegisters() {
        this.loading = true
        await this.getRegistersDemandas(this.userAuthenticated).then(result => {
          this.data = result.data
          console.log(this.data)
          var newArray = _.filter (this.data, function(dat) {
            return dat.alcance === 'pepe' ;
            //return dat.price<=1000 && sqft>=500 && num_of_beds>=2 && num_of_baths>=2.5;
          });
          console.log(newArray)

        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
        this.loading = false
      },
      closeModal() {
        this.modalDialogDemanda = false
      },
      openModalDemanda(data) {
        //this.resetFormDetailsRegister()
        //this.loadDataRegister()
        this.getAllDemandaServicio()
        console.log(data.gestionado)
        if(data.estado_demanda !== null || data.alcance !== null || data.cant_trabajadores !== null || data.gestionado !== null || data.fecha_auditoria !== null || data.conciliacion !== null || data.fecha_conciliacion !== null ){
          this.form_detailsregister.alcance = data.alcance,
          this.form_detailsregister.cant_trabajadores = data.cant_trabajadores,
          this.gestionado = data.gestionado === 0 ? false : data.gestionado === 1 ? true : null,
          this.form_detailsregister.fecha_auditoria = data.fecha_auditoria,
            this.conciliacion = data.conciliacion === 0 ? false : data.conciliacion === 1 ? true : null,
          this.form_detailsregister.fecha_conciliacion = data.fecha_conciliacion
        }else {
          this.form_detailsregister = []
          this.gestionado = ''
          this.gestionado = ''
        }
        this.element_id = data
        this.modalDialogDemanda = true
      },
      async getAllDemandaServicio() {
        await this.getDemandaServicio().then(result => {
          this.listdemandaservicio = result.data;
        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
      },
      async editElement(data) {
        this.form_detailsregister.gestionado = this.gestionado
        this.form_detailsregister.conciliacion = this.conciliacion
        if ( this.form_detailsregister.conciliacion === false ){
          this.form_detailsregister.estado_demanda = 'No conciliada'
        }else{
          if ( this.form_detailsregister.fecha_conciliacion !== null ){
            this.form_detailsregister.estado_demanda = 'Conciliada'
          }else{
            this.form_detailsregister.estado_demanda = 'Solicitada'
          }
        }
          const datas = {
            id: this.element_id['registro_id'],
            form_detailsregister: this.form_detailsregister
          }
          await this.editRegister(datas).then(result => {
            if (result.success === true) {
              this.closeModal()

              this.loadGetRegisters()
              //this.loadGetRegister(this.id_element)
              successMessage('Dato editado satisfactoriamente')
            }
          }).catch(err => {
            errorMessage(message.eDataError, err)
          })

      },
      resetFormAdd(){
        // this.form_detailsregister.tipoSistGest_id='',
        //   this.form_detailsregister.operadora_id=''
      },
      updateDate (component) {
        switch (component) {
          case 'fecha_auditoria':
            this.f_fecha_auditoriadate = this.fecha_auditoriadate
            break
          case 'fecha_conciliacion':
            this.f_fecha_conciliaciondate = this.fecha_conciliaciondate
            break
        }
      },
      saveDate (component) {
        switch (component) {
          case 'fecha_auditoria':
            this.fecha_auditoriadate = this.f_fecha_auditoriadate
            this.form_detailsregister.fecha_auditoria =  moment.utc(this.f_fecha_auditoriadate).format('DD/MM/YY')
            break
          case 'fecha_conciliacion':
            this.conciliaciondate = this.f_fecha_conciliaciondate
            this.form_detailsregister.fecha_conciliacion =  moment.utc(this.f_fecha_conciliaciondate).format('DD/MM/YY')
            break
        }
      }
    }
  }

</script>

<style scoped>

</style>
