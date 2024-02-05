<template>
  <div class="row justify-around q-gutter-sm">
    <q-card style="width: 300px; max-width: 300px;">
      <q-card-section :class="`${text_color} ${bg_color}`">
        <div class="row">
          <div class="col-10">
            <div class="text-h6">{{ title }}</div>
            <div class="text-h4">{{ data_info }}</div>
          </div>
          <div class="col-2">
            <q-icon size="62px" :name="`${icon_name}`" />
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
export default {
  name: 'CardInfo',
  props: {
    bg_color: {
      type: String,
      default: 'bg-blue-8',
    },
    text_color: {
      type: String,
      default: 'text-white',
    },
    title: {
      type: String,
      default: 'Title',
    },
    data_info: {
      type: Number,
      default: 0,
    },
    icon_name: {
      type: String,
      default: 'trending_up',
    },
  },
  data() {
    return {};
  },
  methods: {},
};
</script>

<style></style>
