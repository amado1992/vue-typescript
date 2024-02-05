<template>
  <q-page padding>
  <div class="q-pa-md">

    <q-toolbar class="my-toolbars bg-primary text-white">
      <q-toolbar-title>
        <q-icon
            style="font-size: 1.5em"
            name="mdi-chart-line"
        />
      Coeficiente menor o igual que noventa y cinco
      </q-toolbar-title>


        <q-input
            dark
            dense
            standout
            autofocus
            v-if="filterInput"
            v-model="filter"
            debounce="1000"
            placeholder="Buscar"
            class="q-ml-md"
        ></q-input>
        <q-btn
            key="filter"
            flat
            :color="filterInput ? 'blue-grey-3' : ''"
            icon="search"
            @click.prevent="filterStatus()"
        >
            <q-tooltip>Buscar</q-tooltip>
        </q-btn>
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
import { mapState, mapGetters, mapActions } from "vuex";
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
          name: 'instalacion',
          align: 'left',
          label: 'Instalacion',
          field: row => row.instalacion,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'sistema',
          align: 'left',
          label: 'Sistema',
          field: row => row.sistema,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
         ,
        {
          name: 'equipo',
          align: 'left',
          label: 'Equipo',
          field: row => row.equipo,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'indicador tres',
          align: 'left',
          label: 'Coeficiente disponibilidad técnica',
          field: row => row.coeficienteDispTecnica,
          sortable: true,
          headerClasses: 'text-uppercase'
        },
        {
          name: 'creado',
          align: 'left',
          label: 'Creado',
          field: row => row.fechaReporte,
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
     this.addToHomeBreadcrumbs([{ label: "Coeficiente menor o igual que noventa y cinco" }]);

    this.onRequest({
      pagination: this.pagination,
      filter: undefined
    })
  },
  methods: {
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
     async loadData() {
      return await axios.get('/api/coefdisptec_menor_igual_noventa_y_cinco')
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
