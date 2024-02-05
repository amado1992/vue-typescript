<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          class="my-sticky-column-table"
          flat
          dense
          :data="data"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          no-data-label="No se encontraron elementos a mostrar"
          :visible-columns="visibleColumns"
          hide-pagination
        >
          <template v-slot:top>
            <q-toolbar class="row">
              <q-toolbar-title
                class="col-8 text-subtitle1 text-weight-bold text-uppercase"
              >
                Precios por productos y territorios
              </q-toolbar-title>
              <q-space />
              <div class="col" style="padding: 0px 20px 20px 0px;">
                <q-select
                  v-model="form_data.producto"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  dense
                  :options="items_producto"
                  label="Producto *"
                  option-label="nombre"
                  option-value="id"
                />
              </div>
              <q-btn
                dense
                type="button"
                icon="search"
                @click="loadData()"
                color="primary"
              >
                <q-tooltip>Buscar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import {
  successMessage,
  warningMessage,
} from '../../../../resources/js/utils/notificaciones';

export default {
  name: 'fi_reporte3',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      monthPicked: [],
      loading: false,
      data: [],
      items_producto: [],
      form_data: {
        producto: '',
      },
      visibleColumns: ['provincia', 'proveedor', 'producto', 'precio'],
      columns: [
        {
          name: 'provincia',
          align: 'left',
          label: 'provincia',
          field: (row) => row.provincia,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'proveedor',
          align: 'left',
          label: 'proveedor',
          field: (row) => row.proveedor,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'producto',
          align: 'left',
          label: 'producto',
          field: (row) => row.producto,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'precio',
          align: 'left',
          label: 'precio',
          field: (row) => row.precio,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
      ],
    };
  },
  async mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Logística' },
      { label: 'Flujo informativo' },
      { label: 'Reportes' },
      { label: 'Precios por productos y territorios' },
    ]);
    this.getProductos().then((r) => {
      this.items_producto = r;
    });
  },

  computed: {},
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('compra', ['compararpreciosxproductoxterritorio']),
    ...mapActions('producto', ['getProductos']),

    async loadData() {
      if (this.form_data.producto === '') {
        warningMessage('Debe seleccionar un producto');
      } else {
        this.setLoading(true);
        const data = {
          producto: this.form_data.producto,
        };
        await this.compararpreciosxproductoxterritorio(data).then(
          (response) => {
            this.data = response;
            this.setLoading(false);
          }
        );
      }
    },
  },
};
</script>

<style lang="sass"></style>>
