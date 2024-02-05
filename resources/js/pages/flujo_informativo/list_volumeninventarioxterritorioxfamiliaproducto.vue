<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
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
                Inventario por territorio y familia de productos
              </q-toolbar-title>
              <q-space />
              <div class="col" style="padding: 0px 20px 20px 0px;">
                <q-select
                  v-model="form_data.provincia"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  dense
                  :options="items_provincia"
                  label="Provincia *"
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
  name: 'fi_reporte4',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      monthPicked: [],
      loading: false,
      data: [],
      items_provincia: [],
      form_data: {
        provincia: '',
      },
      visibleColumns: [
        'provincia',
        'proveedor',
        'familia_producto',
        'volumen_inventario',
        'unidad_medida',
      ],
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
          name: 'familia_producto',
          align: 'left',
          label: 'familia de productos',
          field: (row) => row.familia_producto,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'volumen_inventario',
          align: 'left',
          label: 'volumen de inventario',
          field: (row) => row.volumen_inventario,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'unidad_medida',
          align: 'left',
          label: 'unidad de medida',
          field: (row) => row.unidad_medida,
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
      { label: 'Inventario por territorio y familia de productos' },
    ]);
    this.getProvincias().then((r) => {
      this.items_provincia = r;
    });
  },

  computed: {},
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('compra', [
      'listvolumeninventarioxterritorioxfamiliaproducto',
    ]),
    ...mapActions('provincia', ['getProvincias']),

    async loadData() {
      if (this.form_data.provincia === '') {
        warningMessage('Debe seleccionar el criterio de búsqueda');
      } else {
        this.setLoading(true);
        const data = {
          provincia: this.form_data.provincia,
        };
        await this.listvolumeninventarioxterritorioxfamiliaproducto(data).then(
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
