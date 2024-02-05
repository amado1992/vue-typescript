<template>
  <div class="q-pa-md">
    <q-toolbar class="bg-primary">
      <transition-group
        appear
        enter-active-class="animated fadeIn"
        leave-active-class="animated fadeOut"
      >

      </transition-group>
      <q-item-label class="text-white">Gestión de Costo de No Conformidades</q-item-label>
      <q-space></q-space>

      <q-input borderless dense debounce="1000" v-model="filter" placeholder="Buscar">
        <template v-slot:append>
          <q-icon name="search"/>
        </template>
      </q-input>
      <!-- Wrapping only one DOM element, defined by QBtn -->
      <q-btn
        round
        @click.prevent="openModal(true,'add')"
        key="add"
        flat
        color="white"
        icon="add"
        :disable="(selected.length===0)?false:true"
      >
        <q-tooltip>Crear</q-tooltip>
      </q-btn>
      <q-btn
        round
        key="updated"
        flat
        color="white"
        icon="edit"
        @click.prevent="openModal(true,'update')"
        :disable="(selected.length!==0)?false:true"
      >
        <q-tooltip>Actualizar</q-tooltip>
      </q-btn>
      <q-btn
        round
        key="see"
        flat
        color="white"
        icon="remove_red_eye"
        @click.prevent="openModalSee(true,'see')"
        :disable="(selected.length!==0)?false:true"
      >
        <q-tooltip>Visualizar</q-tooltip>
      </q-btn>
      <q-btn
        round
        key="delete"
        flat
        color="white"
        icon="delete"
        @click.prevent="eliminar()"
        :disable="(selected.length!==0)?false:true"
      >
        <q-tooltip>Eliminar</q-tooltip>
      </q-btn>
    </q-toolbar>
    <q-table
      :data="data"
      :columns="columns"
      row-key="id"
      :selection="this.typeSelection()"
      :selected.sync="selected"
      :pagination.sync="pagination"
      loading-label="Cargando elementos"
      :rows-per-page-options="[5,10,25,50]"
      :filter="filter"
      binary-state-sort
      no-data-label="No se encontraron elementos a mostrar"
      :visible-columns="visibleColumns"
    >
      <template v-slot:body-cell-enabled="props">
        <q-td class="text-center" key="enabled">
          <q-badge square :color="props.row.enabled === 1 ?'green':'red'">
            {{ props.row.enabled === 1 ? 'Habilitado' : 'NO Habilitado' }}
          </q-badge>
        </q-td>
      </template>
      <template v-slot:body-cell-created_at="props">
        <q-td :props="props">
          <span></span>
        </q-td>
      </template>
    </q-table>

    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 500px">
        <q-card-section class="bg-primary">
          <div class="text-h6 text-white">
            {{ this.action == 'add' ? 'Adicionar Costo de no conformidad' : 'Actualizar Costo de no conformidad' }}
          </div>
        </q-card-section>
        <q-card-section class="q-pa-lg">
          <div class="row">
            <div class="col" style="padding: 0px 20px 20px 0px">
              <q-input v-model="form_sistema.codigo" type="text" label="Código *" dense
                       name="nombre"
              />
            </div>
              <div class="col" style="padding: 0px 20px 20px 0px">
                  <q-input v-model="form_sistema.nombre" type="text" label="Nombre *" dense
                           name="nombre"
                  />
              </div>
          </div>

          <div class="text-red" v-show="form_error">Los campos marcados con (*) son de llenado obligatorio
          </div>
        </q-card-section>
        <q-separator inset/>
        <q-card-actions align="right">
          <q-btn type="button" :icon="action === 'add' ? 'save' : 'edit'"
                 @click="action != 'add' ? update(form_sistema.id, form_sistema) : nuevo(form_sistema)" label="Guardar"
                 color="secondary" flat/>
          <q-btn type="button" icon="close" @click="closeModal()" label="Cancelar" v-close-popup
                 color="secondary" flat/>
        </q-card-actions>
      </q-card>

    </q-dialog>
    <q-dialog
      v-model="modalDialogSee"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 500px">
        <q-card-section class="bg-primary">
          <div class="text-h6 text-white">
            {{ this.action == 'see' ? 'Consultar Costo de no conformidad' : 'Consultar Costo de no conformidad' }}
          </div>
        </q-card-section>
        <q-card-section class="q-pa-lg">
          <div class="row">
            <div class="col" style="padding: 0px 20px 20px 0px">
              <q-item-label>Código: {{ form_sistema.codigo }}</q-item-label>

            </div>

          </div>

          <div class="row">
            <div class="col" style="padding: 0px 20px 20px 0px">
              <q-item-label>Nombre: {{ form_sistema.nombre }}</q-item-label>

            </div>
          </div>
          <div class="text-red" v-show="form_error">Los campos marcados con (*) son de llenado obligatorio
          </div>
        </q-card-section>
        <q-separator inset/>
        <q-card-actions align="right">
          <q-btn type="button" icon="close" @click="closeModal()" label="Cancelar" v-close-popup
                 color="secondary" flat/>
        </q-card-actions>
      </q-card>

    </q-dialog>
  </div>
</template>

<script>

import { errorMessage, successMessage } from '../../utils/notificaciones'
import axios from 'axios';
// import { required, email, minLength, maxLength, numeric } from 'vuelidate/lib/validators'

export default {
  name: 'costo_no_conformidad',
  data() {
    return {
      visibleColumns: ['codigo', 'nombre'],
      modalDialog: false,
      modalDialogSee: false,
      selected: [],
      add: true,
      data: [],
      // scopes: sessionStorage.getItem('scopes'),
      filter: '',
      action: '',
      pagination: {
        page: 1,
        rowsNumber: 0
      },
      form_sistema: {
        codigo: '',
        nombre: '',
          id: ''
      },
      form_error: false,
      required_error: false,
      columns: [
        {
          name: 'id',
          label: 'Id',
          align: 'center',
          field: row => row.id
        },
        {
          name: 'codigo',
          label: 'Código',
          align: 'center',
          field: 'codigo',
          search: true
        },
          {
              name: 'nombre',
              label: 'NOMBRE',
              align: 'center',
              field: 'nombre',
              search: true
          },

      ]
    }
  },
  mounted() {
    this.loadData()
  },

  methods: {
    typeSelection() {
      // eslint-disable-next-line no-constant-condition
      return (1) ? 'single' : 'none'
    },
    closeModal() {
      this.modalDialog = false
      this.reset_form()
      this.selected = []
    },
    openModal(data, action) {
      this.action = action
      if (action === 'add') {
          this.Ramdon();
        this.modalDialog = data
      } else {
        if (action === 'update') {
          if (this.selected.length > 0) {
            this.setFormData(this.selected)
            this.modalDialog = data
          } else {
             errorMessage('Debe seleccionar la fila a modificar.')
          }
        }
      }
    },
      Ramdon() {
          var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHJKMNPQRTUVWXYZ12346789";
          var id_muestra = "";
          for (var i = 0; i < 10; i++) id_muestra += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
          this.form_sistema.codigo = id_muestra;

      },
    openModalSee(data, action) {
      this.action = action
      if (action === 'see') {
        if (this.selected.length > 0) {
          this.setFormData(this.selected)
          this.modalDialogSee = data
        } else {
           errorMessage('Debe seleccionar la fila a modificar.')
        }
      }
    },
    setFormData(data) {
      this.form_sistema = Object.assign(this.form_sistema, data[0])
    },
    reset_form() {
      this.form_sistema.codigo = ''
      this.form_sistema.nombre = ''
      this.form_error = false
      this.required_error = false
      this.add = true
    },
      async loadData() {
      return  await axios.get('/api/costos_noconformidades')
            .then((result) => {
                this.data = result.data.data
            }).catch(err => {
                console.log(err)
            })
    },
    async nuevo(form_sistema) {
        return  await axios.post('/api/costo_noconformidad', form_sistema)
            .then((result) => {
                if(result.status === 201){
                    this.data = result
                    this.loadData()
                    successMessage('Los datos se insertaron satisfactoriamente')
                    this.closeModal()
                }
            }).catch(err => {
                console.log(err)
            })
    },
    async update(id, form_sitema) {
        return  await axios.put('/api/costos_noconformidades/' + id, {data: form_sitema})
            .then((result) => {
                if(result.status === 200){
                    this.data = result
                    this.loadData()
                    successMessage('Los datos se actualizaron satisfactoriamente')
                    this.closeModal()
                }
            }).catch(err => {
                console.log(err)
            })
    },
      async eliminar() {
          if (this.selected.length > 0) {
              this.setFormData(this.selected)
              const id = this.form_sistema.id;
              return  await axios.delete('/api/costos_noconformidades/' + id)
                  .then((result) => {
                      if(result.status === 200){
                          this.data = result
                           this.loadData()
                          successMessage('Los datos se eliminaron satisfactoriamente')
                          this.closeModal()
                      }
                  }).catch(err => {
                      console.log(err)
                  })
          } else {
              errorMessage('Debe seleccionar la fila a eliminar.')
          }

      }
  }
}

</script>

<style scoped>

</style>
