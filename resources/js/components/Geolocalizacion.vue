<template>
    <l-map :zoom="zoom" :center="center" :bounds="bounds" :max-bounds="maxBounds" style="height: 260px; width: 590px">
      <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
      <l-control :position="'topleft'">
        <q-btn dense color="white" text-color="black" icon="room" @click="addMarker" v-show="addmarkerbtn">
          <q-tooltip
            anchor="center right"
            self="center left"
            content-class="bg-blue-8"
            transition-show="flip-right"
            transition-hide="flip-left"
            :offset="[10, 10]"
          >Adicionar marcador
          </q-tooltip>
        </q-btn>
      </l-control>
      <l-control
        v-if="markers != 0"
        :position="'bottomleft'"
        class="custom-control-watermark"
      >
        <strong>Lat:</strong>{{ markers[0] && markers[0].position && markers[0].position.lat }}
        <strong>Long:</strong>{{ markers[0] && markers[0].position && markers[0].position.lng }}
      </l-control>
      <l-marker
        v-for="marker in markers"
        :key="marker.id"
        :visible="marker.visible"
        :draggable="marker.draggable"
        :lat-lng.sync="marker.position"
      >
      </l-marker>
    </l-map>
</template>

<script>
import { LMap, LTileLayer, LMarker, LControl } from 'vue2-leaflet'
import 'leaflet/dist/leaflet.css'
import { Icon, latLngBounds } from 'leaflet'

delete Icon.Default.prototype._getIconUrl
Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png')
})

export default {
  name: 'Geolocalizacion',
  components: {
    LMap,
    LTileLayer,
    LMarker,
    LControl
  },
  props: {},
  data () {
    return {
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      markers: [],
      zoom: 18,
      center: [22.147602, -80.448632],
      bounds: latLngBounds([
        [23.765236889758672, -85.84716796875001],
        [18.916679786648565, -72.79541015625001]
      ]),
      maxBounds: latLngBounds([
        [26.31311263768267, -93.95507812500001],
        [15.707662769583518, -65.74218750000001]
      ]),
      addmarkerbtn: true
    }
  },
  watch: {
    markers: {
      handler: function () {
        this.$root.$emit('geoCoordenadas', this.markers)
      },
      deep: true
    }
  },
  methods: {
    addMarker () {
      const newMarker = {
        position: {
          lat: 22.147602,
          lng: -80.448632
        },
        draggable: true,
        visible: true,
        tooltip: 'ssss'
      }
      this.markers.push(newMarker)
      this.addmarkerbtn = false
      this.$root.$emit('submitBtn', true)
    },
    addCoordenadasManual () {
      const newMarker = {
        position: {
          lat: null,
          lng: null
        },
        draggable: false,
        visible: false
      }
      if (this.markers.length === 0) {
        this.markers.push(newMarker)
      } else {
        this.removeMarker(0)
        this.markers.push(newMarker)
      }
      this.$root.$emit('showDialog', this.markers)
      console.log(this.markers[0].position.lat)
      console.log(this.markers[0].position.lng)
    },
    removeMarker: function (index) {
      this.markers.splice(index, this.markers.length)
    }
  }

}
</script>

<style scoped>

</style>
