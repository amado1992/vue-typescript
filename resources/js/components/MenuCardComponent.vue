<template>
  <q-card class="menu-card">
    <!-- serv tecnicos -->
    <q-card-section>
      <div class="row justify-between">
        <div class="text-h5">Servicios técnicos</div>
        <q-btn
          dense
          flat
          color="primary"
          :icon="this.showst ? 'mdi-chevron-down' : 'mdi-chevron-up'"
          @click="showst = !showst"
        />
      </div>
    </q-card-section>
    <transition name="fade">
      <q-card-section class="row q-gutter-md" v-if="showst">
        <q-card
          v-for="(item, index) in servtecn"
          :key="index"
          class="item-card bg-primary text-white"
          @click="hola()"
        >
          <q-card-section class="text-center">
            <q-icon :name="item.icon" class="" style="font-size: 3rem" />
          </q-card-section>
          <q-tooltip
            transition-show="rotate"
            transition-hide="rotate"
            anchor="center middle"
            self="center middle"
          >
            {{ item.label }}
          </q-tooltip>
        </q-card>
      </q-card-section>
    </transition>

    <!-- end serv tecnicos -->
    <q-separator inset />
    <!-- calidad -->
    <q-card-section>
      <div class="row justify-between">
        <div class="text-h5">Calidad</div>
        <q-btn
          dense
          flat
          color="primary"
          :icon="this.showca ? 'mdi-chevron-down' : 'mdi-chevron-up'"
          @click="showca = !showca"
        />
      </div>
    </q-card-section>
    <transition name="fade">
      <q-card-section class="row q-gutter-md" v-if="showca">
        <q-card
          v-for="(item, index) in calidad"
          :key="index"
          class="item-card bg-primary text-white"
          @click="hola()"
        >
          <q-card-section class="text-center">
            <q-icon :name="item.icon" class="" style="font-size: 3rem" />
          </q-card-section>
          <q-tooltip
            transition-show="rotate"
            transition-hide="rotate"
            anchor="center middle"
            self="center middle"
          >
            {{ item.label }}
          </q-tooltip>
        </q-card>
      </q-card-section>
    </transition>
    <!-- end calidad -->
    <q-separator inset />

    <!-- logistica -->
    <q-card-section>
      <div class="row justify-between">
        <div class="text-h5">Logística</div>
        <q-btn
          flat
          dense
          color="primary"
          @click="showlg = !showlg"
          :icon="this.showlg ? 'mdi-chevron-down' : 'mdi-chevron-up'"
        />
      </div>
    </q-card-section>
    <transition name="fade">
      <q-card-section class="row q-gutter-md" v-if="showlg">
        <q-card
          v-for="(item, index) in logistica"
          :key="index"
          class="item-card bg-primary text-white"
          @click="hola()"
        >
          <q-card-section class="text-center">
            <q-icon :name="item.icon" class="" style="font-size: 3rem" />
          </q-card-section>
          <q-tooltip
            transition-show="rotate"
            transition-hide="rotate"
            anchor="center middle"
            self="center middle"
          >
            {{ item.label }}
          </q-tooltip>
        </q-card>
      </q-card-section>
    </transition>
    <!-- end logistica -->
  </q-card>
</template>

<script>
export default {
  name: "MenuCardComponent",
  data() {
    return {
      showst: true,
      showca: true,
      showlg: true,
      servtecn: [
        {
          icon: "mdi-home-modern",
          label: "Disponibilidad de habitaciones",
          action: "",
        },
      ],
      calidad: [
        {
          icon: "mdi-home-modern",
          label: "Costo de calidad",
          action: "",
        },
        {
          icon: "mdi-medical-bag",
          label: "Turismo + H + S",
          action: "Disponibilidad de habitaciones",
        },
        {
          icon: "mdi-book-information-variant",
          label: "Indicadores L210",
          action: "",
        },
      ],
      logistica: [
        {
          icon: "mdi-resistor-nodes",
          label: "Flujo Material",
          action: "",
        },
        {
          icon: "mdi-resistor-nodes",
          label: "Flujo Informativo",
          action: "",
        },
        {
          icon: "mdi-basket-fill",
          label: "Gest. de importaciones",
          action: "",
        },
        {
          icon: "mdi-warehouse",
          label: "Gest. de almacenes",
          action: "",
        },
      ],
    };
  },
};
</script>

<style lang="sass"scoped>
.menu-card
  width: 100%
  max-width: 50vw

.item-card
  width: 80px
  height: 80px

.fade-enter-active, .fade-leave-active
  transition: opacity .5s

.fade-enter, .fade-leave-to
  opacity: 0

</style>