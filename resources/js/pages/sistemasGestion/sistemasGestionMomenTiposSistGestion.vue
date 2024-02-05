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
          :pagination.sync="pagination"
          :loading="loading"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 15]"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar tipos de sistemas de gestión
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
              v-if="scopes.includes('gestionar_indicadores_lin')"
              type="reset"
              key="add"
              dense
              color="primary"
              icon="add"
              @click.prevent="openModalAdd(true, 'add')"
              class="q-mt-sm"
            >
              <q-tooltip>Nuevos datos</q-tooltip>
            </q-btn>
          </template>
          <template v-slot:body-cell-columnaction="props">
            <q-td :props="props">
              <div class="q-gutter-sm">
                <q-btn
                  v-if="scopes.includes('gestionar_indicadores_lin')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="openModalEdit(true, 'edit',props.row)"
                >
                  <q-tooltip>Editar elemento</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_indicadores_lin')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="openModalDelete(true, 'delete',props.row)"
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
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 500px; max-width: 70vw">
        <q-form
          @reset="reset_form_modal"
          @submit="this.action == 'add' ? addElement() : this.action == 'edit' ? editElement() : deleteElement() "
          ref="formTipo"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.action == "add" ? "Crear Tipo de Sistema de Gestión" : this.action == "edit" ? "Editar Tipo de Sistema de Gestión" : "Eliminar Tipo de Sistema de Gestión"
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
              <div class="row">
                <div class="col" style="padding: 0px 20px 20px 0px">
                  <q-input v-if="action != 'delete'" name="desc" v-model="form_data.desc_TipoSistGestion" type="text" label="Descripción" dense/>
                  <label v-if="action == 'delete'"  name="desc1"  type="text"  dense
                  >
                    Está seguro de eliminar el tipos de sistema de gestión: {{this.form_data.desc_TipoSistGestion}}</label>
                </div>
              </div>
              <div class="row">
                <div class="col" style="padding: 0px 20px 20px 0px">
                  <q-input v-if="action != 'delete'" name="desc2" v-model="form_data.norma_Referencia" type="text" label="Norma" dense/>
                </div>
              </div>
              <br/>
              <div class="text-red" v-show="form_error">Los campos marcados con (*) son de llenado obligatorio
              </div>

            </q-card-section>
            <q-card-actions align="right">
              <q-btn type="button" :icon="action === 'add' ? 'save' : 'edit'"
                     @click="action == 'add' ? addElement(form_data) : action == 'edit' ? editElement(form_data) : deleteElement(form_data) " label="Guardar"
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

import {errorMessage, successMessage} from '../../utils/notificaciones'
import axios from 'axios';
import { mapState, mapGetters, mapActions } from "vuex";
import * as message from "../../utils/resources";
import {getTiposSistGestion} from "../../store/sistema_gestion/actions";

export default {
  name: 'sistemagestiontiposistgestion',
  data() {
    return {
      data: [],
      columns: [
        {
          name: 'columnid',
          label: 'id',
          align: 'center',
          field: row => row.id
        },
        {
          name: 'columnname',
          label: 'Descripción',
          align: 'left',
          field: row => row.desc_TipoSistGestion,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'columnreferens',
          label: 'Referencia',
          align: 'left',
          field: row => row.norma_Referencia,
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
      visibleColumns: ['columnname','columnreferens','columnaction'],
      form_data: {
        id:'',
        desc_TipoSistGestion:'',
        norma_Referencia:''
      },
      modalDialog: false,
      modalDialogSee: false,
      selected: [],
      loading: true,
      instalacion: [],
      filterInput: false,
      add: true,
      scopes: sessionStorage.getItem('scopes'),
      filter: '',
      action: '',
      pagination: {
        page: 1,
        rowsNumber: 0
      },
      form_error: false,
    }
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      {label: 'Dirección de Calidad'},
      {label: 'Sistema de Gestión de la Calidad'},
      {label: 'Nomencladores'},
      {label: 'Gestionar Tipos de Sistemas de Gestión'}])
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
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    ...mapActions('managementsystem', ['getTiposSistGestion']),
    ...mapActions('managementsystem', ['addTipoSistGestion']),
    ...mapActions('managementsystem', ['editTipoSistGestion']),
    ...mapActions('managementsystem', ['deleteTipoSistGestion']),
    async loadData() {
      this.loading = true
      await this.getTiposSistGestion().then(result => {
        this.data = result.data;
      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
      this.loading = false
    },
    openModalAdd(data, action) {
      this.action = action
      if (action === 'add') {
        this.modalDialog = data
      }
    },
    async addElement() {
      await this.addTipoSistGestion(this.form_data).then(result => {
        if (result.success === true) {
          this.loadData()
          successMessage('Dato insertado satisfactoriamente')
          this.closeModal()
        }
      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
    },
    openModalEdit(data, action, rowData) {
      this.action = action;
      if (action === 'edit') {
        if (rowData) {
          this.setFormData(rowData)
          this.modalDialog = data;
        } else {
          infoMessage('Debe seleccionar una fila para modificar.');
        }
      }
    },
    async editElement(aux) {
      await this.editTipoSistGestion(this.form_data).then(result => {
        if (result.success === true) {
          this.loadData()
          successMessage('Dato editado satisfactoriamente')
          this.closeModal()
        }
      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
    },
    openModalDelete(data, action, rowData) {
      this.action = action;
      if (action === 'delete') {
        if (rowData) {
          this.setFormData(rowData)
          this.modalDialog = data;
        } else {
          infoMessage('Debe seleccionar una fila para modificar.');
        }
      }
    },
    async deleteElement(aux) {
      await this.deleteTipoSistGestion(this.form_data).then(result => {
        if (result.success === true) {
          this.loadData()
          successMessage('Dato eliminado satisfactoriamente')
          this.closeModal()
        }
      }).catch(err => {
        errorMessage(message.eDataError, err)
      })
    },
    reset_form_modal() {
      this.form_data.desc_TipoSistGestion = '',
      this.form_data.norma_Referencia = '',
      this.action = ''
    },
    closeModal() {
      this.modalDialog = false
      this.reset_form_modal()
      this.selected = []
    },
    openModal(data, accion, rowData) {
      this.accion = accion;
      if (accion === 'update') {
        if (rowData) {
          this.setFormData(rowData)
          this.modalDialog = data;
        } else {
          infoMessage('Debe seleccionar una fila para modificar.');
        }
      }
    },
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data)
    },
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
  }
}

</script>

<style scoped>

</style>

