<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="text-subtitle1 text-weight-bold text-uppercase">
            gestionar sistema tecnológico
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
            v-if="scopes.includes('gestion_sistemas_tec')"
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
          flat
          :data="data"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          no-data-label="No se encontraron elementos a mostrar"
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
                  @click.prevent="openModalSee(true, 'see', props.row)"
                >
                  <q-tooltip>Ver detalles</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_costo_calidad')"
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
                  v-if="scopes.includes('gestionar_costo_calidad')"
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
        <!-- /tabla-->
      </q-card-section>
    </q-card>
    <!-- Dialog -->
    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 640px; max-width: 70vw;">
        <q-form
          @reset="reset_form"
          @submit="accion != 'adicionar' ? update() : save()"
          ref="myForm"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == 'adicionar'
                      ? 'Adicionar sistema tecnológico'
                      : 'Actualizar sistema tecnológico'
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
              <div class="row q-gutter-y-sm">
                <div class="col-xs-6 col-sm-6 col-md-16 col-lg-6 q-px-sm">
                  <q-input
                    label="Total de equipos instalados *"
                    v-model="form_create.totalEqInstalado"
                    type="number"
                    lazy-rules
                    :rules="[
                      (val) => (val && val > 0) || 'Por favor escriba algo',
                    ]"
                  />
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Reposición"
                    v-model="form_create.reposicion"
                    type="number"
                    lazy-rules
                    :rules="[
                      (val) => val >= 0 || 'Por favor escriba un número válido',
                    ]"
                  />
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Mantemiento reparación"
                    v-model="form_create.mantenimientoReparacion"
                    type="number"
                    lazy-rules
                    :rules="[
                      (val) => val >= 0 || 'Por favor escriba un número válido',
                    ]"
                  />
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Reparación capital"
                    v-model="form_create.reparacionCapital"
                    type="number"
                    :rules="[
                      (val) => val >= 0 || 'Por favor escriba un número válido',
                    ]"
                  />
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-select
                    v-model="form_create.equipo_id"
                    :options="equipos"
                    label="Equipo *"
                    options-dense
                    option-value="id"
                    option-label="nombre"
                    emit-value
                    map-options
                    :rules="[(val) => !!val || 'Por favor seleccione algo']"
                  />
                </div>

                <!--<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-select
                    filled
                    v-model="form_create.instalacion_id"
                    :options="instalaciones"
                    label="Instalación *"
                    options-dense
                    option-value="id"
                    option-label="nombre"
                    emit-value
                    map-options
                    :rules="[(val) => !!val || 'Por favor seleccione algo']"
                  />
                </div>-->

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Fecha de creado *"
                    v-model="form_create.fechaReporte"
                    mask="date"
                    lazy-rules
                    :rules="[(val) => !!val || 'Por favor escriba algo']"
                  >
                    <template v-slot:append>
                      <q-icon name="event" class="cursor-pointer">
                        <q-popup-proxy
                          ref="qDateProxy"
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-date v-model="form_create.fechaReporte">
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

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <q-input
                    label="Observación"
                    v-model="form_create.observacion"
                    type="textarea"
                  />
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 q-px-sm">
                  <span class="text-h6">Contratado en mantenimiento *</span>
                  <q-radio
                    v-model="esContratadoMantenimiento"
                    val="Yes"
                    label="Si"
                  />
                  <q-radio
                    v-model="esContratadoMantenimiento"
                    val="No"
                    label="No"
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
                :icon="accion === 'adicionar' ? 'save' : 'edit'"
                v-show="add"
                :disable="isComplete"
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

    <!--Ver mas datos-->
    <q-dialog
      v-model="modalDialogSee"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 500px;">
        <q-card-section class="bg-primary">
          <div class="text-h6 text-white" v-if="accion == 'see'">Datos</div>
        </q-card-section>
        <q-card-section>
          <div class="column" style="height: 280px;">
            <div class="col">Instalación: {{ instalacion }}</div>
            <div class="col">Sistema: {{ sistema }}</div>
            <div class="col">Equipo: {{ equipo }}</div>
            <div class="col">
              Equipo instalado: {{ form_create.totalEqInstalado }}
            </div>
            <div class="col">
              Hora trabajada: {{ form_create.totalHrTrabajarEqInst }}
            </div>
            <div class="col">
              Hora dejada de trabajar:
              {{ form_create.totalHrDejadaTrabjEqInst }}
            </div>
            <div class="col">Creado: {{ form_create.fechaReporte }}</div>
            <div class="col" v-if="form_create.esContratadoMantenimiento == 0">
              Contratado en mantenimiento: No
            </div>
            <div class="col" v-if="form_create.esContratadoMantenimiento == 1">
              Contratado en mantenimiento: Si
            </div>
            <div class="col">
              Coeficiente de disponibilida técnica:
              {{ form_create.coeficienteDispTecnica }}
            </div>
            <div class="col">
              Mantenimiento reparación:
              {{ form_create.mantenimientoReparacion }}
            </div>
            <div class="col">
              Reparación capital: {{ form_create.reparacionCapital }}
            </div>
            <div class="col">reposición: {{ form_create.reposicion }}</div>
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
    <!--Fin ver mas datos-->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../utils/dialogo';
import { infoMessage } from '../../utils/notificaciones';
import * as message from '../../utils/resources';

import axios from 'axios';
import { date } from 'quasar';
import { errorMessage, successMessage } from '../../utils/notificaciones';

export default {
  name: 'SistemaTecnologicoPage',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      modalDialog: false,
      filterInput: false,
      loading: false,
      filter: '',
      val: false,
      add: true,
      accion: '',
      esContratadoMantenimiento: '',
      form_create: {
        id: 0,
        equipo_id: null,
        esContratadoMantenimiento: '', //boolean
        totalEqInstalado: null, //integer
        totalHrTrabajarEqInst: 0, //calculate double
        totalHrDejadaTrabjEqInst: 0, //calculate double
        coeficienteDispTecnica: 0, //calculate double
        mantenimientoReparacion: null, //integer
        reparacionCapital: null, //integer
        reposicion: null, //integer

        /*mantenimientoReparacion: 0, //integer
        reparacionCapital: 0, //integer
        reposicion: 0, //integer*/
        observacion: '',
        instalacion_id: sessionStorage.getItem('instalacion_id'),
        fechaReporte: '',
      },
      equipo: '',
      sistema: '',
      instalacion: '',
      columns: [
        {
          name: 'instalacion',
          align: 'left',
          label: 'Instalación',
          field: (row) => row.instalacion.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'sistema',
          align: 'left',
          label: 'Sistema',
          field: (row) => row.equipo.sistema.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'equipo',
          align: 'left',
          label: 'Equipo',
          field: (row) => row.equipo.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'totalEqInstalado',
          align: 'left',
          label: 'Total de equipo instalado',
          field: (row) => row.totalEqInstalado,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'totalHrTrabajarEqInst',
          align: 'left',
          label: 'Total de hora trabajada',
          field: (row) => row.totalHrTrabajarEqInst,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'creado',
          align: 'left',
          label: 'Creado',
          field: (row) => row.fechaReporte,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'actions',
          label: 'Acciones',
          field: '',
          align: 'center',
          headerClasses: 'text-uppercase',
        },
      ],
      data: [],
      equipos: [],
      instalaciones: [],
      modalDialogSee: false,
    };
  },
  created() {
    this.loadData();
    this.loadDataEquipos();
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Servicios Técnicos' },
      { label: 'Disponibilidad de los sitemas tecnológicos' },
      { label: 'Sistema tecnológico' },
    ]);
  },
  watch: {},
  computed: {
    isComplete() {
      if (
        this.form_create.equipo_id != null &&
        this.esContratadoMantenimiento != '' &&
        // this.form_create.instalacion_id &&
        this.form_create.totalEqInstalado != null
      ) {
        return false;
      }
      return true;
    },
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    reset_form() {
      this.form_create.id = 0;
      this.form_create.equipo_id = null;
      this.form_create.esContratadoMantenimiento = ''; //boolean
      this.form_create.totalEqInstalado = null; //integer
      this.form_create.totalHrTrabajarEqInst = 0; //calculate double
      this.form_create.totalHrDejadaTrabjEqInst = 0; //calculate double
      this.form_create.coeficienteDispTecnica = 0; //calculate double
      this.form_create.mantenimientoReparacion = null; //integer
      this.form_create.reparacionCapital = null; //integer
      this.form_create.reposicion = null; //integer

      /* this.form_create.mantenimientoReparacion = 0; //integer
      this.form_create.reparacionCapital = 0; //integer
      this.form_create.reposicion = 0; //integer*/

      this.form_create.observacion = '';
      this.form_create.instalacion_id = null;

      this.form_create.fechaReporte = '';

      this.esContratadoMantenimiento = '';
    },
    closeModal() {
      this.modalDialog = false;
      this.modalDialogSee = false;
      this.reset_form();
    },
    async loadData() {
      this.setLoading(true);
      return await axios
        .get('/api/sistemas_tecnologicos')
        .then((result) => {
          this.data = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: 'Carga fallida',
            icon: 'report_problem',
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
        .delete('/api/sistema_tecnologico/' + id)
        .then((response) => {
          this.$q.loading.hide();
          this.reset_form();
          this.loadData();
          successMessage('Los datos se eliminaron satisfactoriamente');
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    confirmDelete(dataRow) {
      this.$q
        .dialog({
          title: 'Confirme',
          message: '¿Estás seguro que desea eliminar este sistema tecnológico?',
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRowRequest(dataRow);
        })
        .onCancel(() => {});
    },
    openModal(data, accion) {
      console.log('WWW', this.form_create);
      this.accion = accion;
      if (accion === 'adicionar') {
        this.modalDialog = data;
      }
    },

    openModalUpdate(data, accion, dataRow) {
      console.log('test data222', dataRow.fechaReporte);
      this.setFormData(dataRow);
      this.modalDialog = data;
      this.accion = accion;

      /* if (accion === "actualizar") {
        this.setFormData(dataRow);
        this.modalDialog = data;
      }*/
    },
    setFormData(data) {
      this.equipo = data.equipo.nombre;
      this.sistema = data.equipo.sistema.nombre;
      this.instalacion = data.instalacion.nombre;
      console.log('hola', this.equipo);
      // data.fechaReporte = date.formatDate(data.fechaReporte, "YYYY/MM/DD");
      this.form_create = Object.assign(this.form_create, data);

      if (this.form_create.esContratadoMantenimiento == 1) {
        this.form_create.esContratadoMantenimiento = true;
        this.esContratadoMantenimiento = 'Yes';
      }
      if (this.form_create.esContratadoMantenimiento == 0) {
        this.form_create.esContratadoMantenimiento = false;
        this.esContratadoMantenimiento = 'No';
      }
    },
    async save() {
      if (this.esContratadoMantenimiento == 'Yes') {
        this.form_create.esContratadoMantenimiento = true;
      }
      if (this.esContratadoMantenimiento == 'No') {
        this.form_create.esContratadoMantenimiento = false;
      }

      /*this.form_create.fechaReporte = date.formatDate(
        this.form_create.fechaReporte,
        "YYYY-MM-DD"
      );*/

      this.$refs.myForm.validate().then((success) => {
        if (success) {
          //valid value
        } else {
          //invalid value
        }
      });
      this.form_create.coeficienteDispTecnica = 60;
      this.form_create.totalHrTrabajarEqInst =
        this.form_create.totalEqInstalado * 24 * 30;

      return await axios
        .post('/api/sistema_tecnologico', this.form_create)
        .then((result) => {
          this.loadData();
          successMessage('Los datos se insertaron satisfactoriamente');
          this.closeModal();
        })
        .catch((err) => {
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: 'Carga fallida',
            icon: 'report_problem',
          });
        });
    },
    async update() {
      if (this.esContratadoMantenimiento == 'Yes') {
        this.form_create.esContratadoMantenimiento = true;
      }
      if (this.esContratadoMantenimiento == 'No') {
        this.form_create.esContratadoMantenimiento = false;
      }
      /* this.form_create.fechaReporte = date.formatDate(
        this.form_create.fechaReporte,
        "YYYY-MM-DD"
      );*/
      let id = this.form_create.id;
      return await axios
        .put('/api/sistema_tecnologico/' + id, this.form_create)
        .then((result) => {
          this.loadData();
          successMessage('Los datos se actualizaron satisfactoriamente');
          this.closeModal();
        })
        .catch((err) => {
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: 'Carga fallida',
            icon: 'report_problem',
          });
        });
    },
    async loadDataEquipos() {
      return await axios
        .get('/api/equipos')
        .then((result) => {
          this.equipos = result.data.data;
        })
        .catch((err) => {
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: 'Carga fallida',
            icon: 'report_problem',
          });
          console.log(err);
        });
    },
    openModalSee(data, action, dataRow) {
      this.accion = action;
      if (action === 'see') {
        this.setFormData(dataRow);
        this.modalDialogSee = data;
      }
    },
  },
};
</script>

<style scoped></style>
