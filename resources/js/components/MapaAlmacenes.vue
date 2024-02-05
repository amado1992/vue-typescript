<template>
  <div>
    <l-map
      :zoom="zoom"
      :center="center"
      :bounds="bounds"
      :max-bounds="maxBounds"
      style="height: 360px; width: 790px"
    >
      <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
      <l-marker
        v-for="marker in markers"
        :key="marker.id"
        :visible="marker.visible"
        :draggable="marker.draggable"
        :lat-lng.sync="marker.position"
      >
        <l-tooltip
          :content="marker.tooltip"
        >
        </l-tooltip>
      </l-marker>
    </l-map>
  </div>
</template>

<script>
import { LMap, LTileLayer, LMarker, LTooltip } from 'vue2-leaflet'
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
  components: { LMap, LTileLayer, LMarker, LTooltip },
  props: {
    markers: {
      type: Array
    }
  },
  data() {
    return {
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      // markers: [],
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
  methods: {
    addMarker() {
      const newMarker = {
        position: {
          lat: 22.147602,
          lng: -80.448632
        },
        draggable: true,
        visible: true,
        tooltip: true
      }
      this.markers.push(newMarker)
      this.addmarkerbtn = false
      this.$root.$emit('submitBtn', true)
    }
  }
}
</script>

<style scoped></style>
