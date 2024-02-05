<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Ubicación geográfica de los almacenes
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <div class="row">
        <MapaAlmacenes v-bind:markers="coordenadaAlmacenes"></MapaAlmacenes>
      </div>
    </q-card>
  </div>
</template>

<script>
import {mapState, mapGetters, mapActions} from 'vuex';
import MapaAlmacenes from '../../components/MapaAlmacenes';

export default {
  name: 'mostrar_ubicaciongeografica',
  components: {MapaAlmacenes},
  data() {
    return {
      data: [],
      loading: false,
      coordenadaAlmacenes: [],
    };
  },
  mounted() {
    this.loadData();
    this.addToHomeBreadcrumbs([
      {label: 'Dirección de Logística'},
      {label: 'Almacenes'},
      {label: 'Reportes'},
      {label: 'Ubicación geográfica de los almacenes'},
    ]);
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('almacen', ['mostrar_ubicacion_geografica']),

    loadData() {
      this.setLoading(true);
      this.mostrar_ubicacion_geografica().then((r) => {
        this.data = r;
        this.setLoading(false);
        this.getCoordenadas(r)
      });
    },
    getCoordenadas(data) {
      data.forEach(element => {
        this.coordenadaAlmacenes.push({
          position: {
            lat: element.latitud,
            lng: element.longitud
          },
          tooltip: element.almacen
        })
      })
    }
  },
};
</script>

<style></style>


