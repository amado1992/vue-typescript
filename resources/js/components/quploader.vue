<template>
  <div class="col" style="padding-right: 15px">
    <q-uploader
      :label="label"
      :color="color"
      :text-color="text_color"
      :field-name="file_name"
      :multiple="multiple"
      :factory="factory"
      :accept="accept"
      square
      flat
      bordered
      style="padding: 15px"
      @uploaded="uploadFile"
    />
  </div>
</template>

<script>
export default {
  name: 'quploader',
  props: {
    accept: { type: String },
    factory: { type: Function },
    label: { type: String },
    multiple: { type: Boolean },
    file_name: { type: String },
    color: { type: String },
    text_color: { type: String }

  },
  methods: {
    uploadFile (xhr) {
      this.$emit('uploaded', xhr)
    }

  }
}
</script>

<style scoped>

</style>
