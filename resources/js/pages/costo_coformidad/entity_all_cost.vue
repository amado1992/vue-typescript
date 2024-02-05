<template>
  <q-page padding>
  <div class="q-pa-md">
    
    <q-toolbar class="my-toolbars bg-primary text-white">
      <q-toolbar-title>
         Costos calidad por entidades
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
  name: 'EntityAllCost',
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
          name: 'costo conformidad',
          align: 'center',
          label: 'costo conformidad',
          field: row => row.total_costo_conformidad,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
         {
          name: 'costo no conformidad',
          align: 'center',
          label: 'costo no conformidad',
          field: row => row.total_costo_noconformidad,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'costo de calidad',
          align: 'center',
          label: 'costo calidad',
          field: row => row.total_costo_calidad,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'venta neta',
          align: 'center',
          label: 'venta neta',
          field: row => row.total_venta_neta,
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
      return await axios.get('/api/entidades_all_costos')
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