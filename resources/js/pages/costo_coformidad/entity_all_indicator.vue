<template>
  <q-page padding>
  <div class="q-pa-md">
    
    <q-toolbar class="my-toolbars bg-primary text-white">
      <q-toolbar-title>
         Indicadores por entidades
      </q-toolbar-title>
      
        <template>
        <q-input dark borderless dense debounce="300" v-model="filter" placeholder="Filtrar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-toolbar>

    <q-card>
      
      <q-card-section>
    
         <q-table
          flat
          dense
          :data="data"
          :columns="columns"
          row-key="id"
          :filter="filter"
          :loading="loading"
          :rows-per-page-options="[10, 20, 30, 40, 50]"
          no-data-label="No se encontraron elementos a mostrar"
          no-results-label="No se encontraron elementos a mostrar"
        >
        
      <template v-slot:loading>
            <q-inner-loading showing color="primary" />
      </template>
        </q-table>
        
      </q-card-section>
    </q-card>
  </div>
</q-page>
</template>

<script>
import axios from 'axios';
export default {
  name: 'EntityAllIndicator',
  data() {
    return {
      filterInput: false,
      loading: true,
      filter: '',
      data: [],
      pagination: {
        sortBy: 'desc',
        descending: false,
        page: 0,
        rowsPerPage: 1,
        rowsNumber: 10
      },
      columns: [
        {
          name: 'nombre',
          align: 'center',
          label: 'entidad',
          field: row => row.nombre,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'indicador uno',
          align: 'center',
          label: 'I-1',
          field: row => row.indicator_1,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
         ,
        {
          name: 'indicador dos',
          align: 'center',
          label: 'I-2',
          field: row => row.indicator_2,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'indicador tres',
          align: 'center',
          label: 'I-3',
          field: row => row.indicator_3,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'indicador cuatro',
          align: 'center',
          label: 'I-4',
          field: row => row.indicator_4,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
         {
          name: 'indicador cinco',
          align: 'center',
          label: 'I-5',
          field: row => row.indicator_5,
          sortable: true,
          headerClasses: 'text-uppercase'
        }
      ]
    }
  },
  created() {
   this.loadData()
  },
   mounted () {

    this.onRequest({
      pagination: this.pagination,
      filter: undefined
    })
  },
  methods: {
     async loadData() {
      return await axios.get('/api/entidades_all_indicadores')
      .then(response => {
          this.data = response.data
          this.loading = false
        })
        .catch(err => {
         this.$q.notify({
         color: 'negative',
         position: 'top',
         message: 'Carga fallida',
         icon: 'report_problem'
        })
        })
    },
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false
        this.filter = ''
      } else {
        this.filterInput = true
      }
    },
     onRequest (props) {
      const { page } = props.pagination
      this.pagination.page = page
      this.loadData() 
    }
  }
}
</script>

<style scoped>
.my-toolbars {
  border-bottom: 0;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}
</style>