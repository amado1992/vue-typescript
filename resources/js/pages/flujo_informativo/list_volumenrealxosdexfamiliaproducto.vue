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
                Volumen real por osdes y familia de productos
              </q-toolbar-title>
              <q-space />
              <div class="col" style="padding: 0px 20px 20px 0px;">
                <q-select
                  v-model="form_data.osde"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  dense
                  :options="items_osde"
                  label="Osde *"
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
  name: 'fi_reporte2',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      monthPicked: [],
      loading: false,
      data: [],
      items_osde: [],
      form_data: {
        osde: '',
      },
      visibleColumns: ['familia_producto', 'cantidad_real'],
      columns: [
        {
          name: 'familia_producto',
          align: 'center',
          label: 'familia de productos',
          field: 'familia_producto',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'cantidad_real',
          align: 'center',
          label: 'volumen real',
          field: (row) => row.cantidad_real,
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
      { label: 'Volumen real por osdes y familia de productos' },
    ]);
    this.getOsdes().then((r) => {
      this.items_osde = r;
    });
  },

  computed: {},
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('compra', ['listvolumenrealxosdexfamiliaproducto']),
    ...mapActions('osde', ['getOsdes']),

    async loadData() {
      if (this.form_data.osde === '') {
        warningMessage('Debe seleccionar el criterio de búsqueda');
      } else {
        this.setLoading(true);
        const data = {
          osde: this.form_data.osde,
        };
        await this.listvolumenrealxosdexfamiliaproducto(data).then(
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
