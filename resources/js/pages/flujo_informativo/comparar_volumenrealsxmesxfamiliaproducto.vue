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
                class="col-4 text-subtitle1 text-weight-bold text-uppercase"
              >
                Volumen real por fechas y osdes
              </q-toolbar-title>
              <q-space />
              <div class="col-3">
                <q-select
                  v-model="form_data.osde"
                  clearable
                  transition-show="jump-up"
                  transition-hide="jump-up"
                  emit-value
                  map-options
                  options-dense
                  dense
                  :options="items_osde"
                  label="Osde *"
                  option-label="nombre"
                  option-value="id"
                  options-cover
                  :rules="[(val) => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-2 q-px-xs">
                <q-input
                  dense
                  v-model="form_data.fecha_inicio"
                  label="Fecha inicial *"
                  name="fecha_inicio"
                  :rules="[(val) => !!val || 'Campo requerido']"
                >
                  <template>
                    <q-popup-proxy
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date
                        dense
                        v-model="form_data.fecha_inicio"
                        mask="YYYY-MM-DD"
                        today-btn
                      >
                        <div class="row items-center justify-end">
                          <q-btn
                            v-close-popup
                            label="Cerrar"
                            color="primary"
                            flat
                          />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </template>
                </q-input>
              </div>
              <div class="col-2">
                <q-input
                  dense
                  v-model="form_data.fecha_fin"
                  label="Fecha final *"
                  name="fecha_fin"
                  :disable="!form_data.fecha_inicio"
                  :rules="[(val) => !!val || 'Campo requerido']"
                >
                  <template>
                    <q-popup-proxy
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date
                        v-model="form_data.fecha_fin"
                        mask="YYYY-MM-DD"
                        today-btn
                      >
                        <div class="row items-center justify-end">
                          <q-btn
                            v-close-popup
                            label="Cerrar"
                            color="primary"
                            flat
                          />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </template>
                </q-input>
              </div>
              <div class="col-1 q-px-xs">
                <q-btn
                  dense
                  type="button"
                  icon="search"
                  color="primary"
                  :disable="!form_data.fecha_fin"
                  @click="loadData()"
                >
                  <q-tooltip>Buscar</q-tooltip>
                </q-btn>
                <q-tooltip>Buscar</q-tooltip>
              </div>
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
  name: 'fi_reporte1',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      monthPicked: [],
      loading: false,
      data: [],
      items_familia_producto: [],
      items_osde: [],
      form_data: {
        fecha_inicio: '',
        fecha_fin: '',
        osde: '',
      },
      visibleColumns: [
        'osde',
        'proveedor',
        'familia_producto',
        'cantidad_real',
        'unidad_medida',
      ],
      columns: [
        {
          name: 'osde',
          align: 'left',
          label: 'osde',
          field: 'osde',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'proveedor',
          align: 'left',
          label: 'proveedor',
          field: 'proveedor',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'familia_producto',
          align: 'left',
          label: 'familia de productos',
          field: 'familia_producto',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'cantidad_real',
          align: 'left',
          label: 'volumen real',
          field: 'cantidad_real',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'unidad_medida',
          align: 'left',
          label: 'unidad de medida',
          field: 'unidad_medida',
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
      { label: 'Volumen real por fechas y familia de productos' },
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
    ...mapActions('compra', ['compararvolumenrealsxmesxfamiliaproducto']),
    ...mapActions('osde', ['getOsdes']),

    async loadData() {
      if (this.form_data.osde) {
        if (this.form_data.fecha_inicio < this.form_data.fecha_fin){
          this.setLoading(true);
          const data = {
            fecha_inicio: this.form_data.fecha_inicio,
            fecha_fin: this.form_data.fecha_fin,
            osde: this.form_data.osde,
          };
          await this.compararvolumenrealsxmesxfamiliaproducto(data).then(
            (response) => {
              this.data = response;
              this.setLoading(false);
            }
          );
        }else {
          return warningMessage('Verifique las fechas')
        }
      } else {
        return warningMessage('Faltan datos por insertar')
      }
    },
  },
};
</script>

<style lang="sass"></style>>
