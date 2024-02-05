<template>
  <div class="q-pa-md q-gutter-sm">
    <q-breadcrumbs>
      <q-breadcrumbs-el
        v-for="(item, index) in breadcrumbs"
        :key="index"
        :icon="item.icon"
        :label="item.label"
        :to="item.to"
      />
      <template v-slot:separator>
        <q-icon size="1.5em" name="chevron_right" color="primary" />
      </template>
    </q-breadcrumbs>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
export default {
  name: 'breadcrumbs',
  data() {
    return {};
  },
  computed: {
    ...mapState('breadcrumbs', ['breadcrumbs']),
  },
  watch: {
    $route(to, from) {
      if (to.name === 'home') {
        this.setBreadcrumbs([]);
      } else {
        this.home_visible = true;
      }
    },
  },
  methods: {
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs', 'setBreadcrumbs']),
  },
};
</script>

<style scoped></style>
