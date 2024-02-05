<template>
  <q-dialog
    ref="dialog"
    persistent
    transition-show="scale"
    transition-hide="scale"
  >
    <q-card
      style="width: 600px; max-width: 70vw;"
      v-if="this.accion == 'adicionar' || this.accion == 'actualizar'"
    >
      <q-card-section class="row no-padding">
        <q-toolbar class="bg-primary text-white">
          <q-toolbar-title>
            {{
              this.accion == 'adicionar'
                ? 'Adicionar ayuda'
                : 'Actualizar ayuda'
            }}
          </q-toolbar-title>
          <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
            <q-tooltip>Cerrar</q-tooltip>
          </q-btn>
        </q-toolbar>
      </q-card-section>
      <q-card-section class="q-px-xd">
        <div class="row">
          <div class="col-4">
            <q-input
              v-model="form_data.consecutivo"
              label="Consecutivo"
              dense
              name="consecutivo"
              :error-message="mensajesError('consecutivo')"
              :error="$v.form_data.consecutivo.$error"
              autogrow
            />
          </div>
          <div class="col-4 q-px-sm">
            <q-input
              v-model="form_data.topico"
              label="Tópico"
              dense
              name="topico"
              :error-message="mensajesError('topico')"
              :error="$v.form_data.topico.$error"
              autogrow
            />
          </div>
          <div class="col-4">
            <q-input
              v-model="form_data.subtopico"
              label="Título"
              dense
              name="subtopico"
              :error-message="mensajesError('subtopico')"
              :error="$v.form_data.subtopico.$error"
              autogrow
            />
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col">
            <q-editor
              ref="editor"
              :definitions="definitions"
              v-model="form_data.contenido"
              :toolbar="toolbar"
              name="contenido"
              placeholder="Contenido"
            />
          </div>
        </div>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn
          color="primary"
          flat
          :icon="accion === 'adicionar' ? 'save' : 'edit'"
          label="Guardar"
          @click="accion === 'adicionar' ? save() : update()"
          :loading="this.getterLoading()"
        />
        <q-btn
          color="negative"
          flat
          icon="close"
          label="Cerrar"
          @click="closeModal"
        />
      </q-card-actions>
    </q-card>

    <q-card style="width: 600px; max-width: 70vw;" v-if="this.accion == 'ver'">
      <q-card-section class="row no-padding">
        <q-toolbar class="bg-primary text-white">
          <q-toolbar-title>
            Ver ayuda
          </q-toolbar-title>
          <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
            <q-tooltip>Cerrar</q-tooltip>
          </q-btn>
        </q-toolbar>
      </q-card-section>
      <q-card-section class="q-pa-md">
        <div class="row">
          <div class="col">
            {{ 'Tópico: ' + this.form_data.topico }}
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col">
            {{ 'Subtópico: ' + this.form_data.subtopico }}
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col">
            {{ 'Contenido: ' }}
            <div v-html="this.form_data.contenido"></div>
          </div>
        </div>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn
          color="primary"
          flat
          icon="close"
          label="Cerrar"
          @click="closeModal"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { mapGetters, mapActions, mapState } from 'vuex';
import { infoMessage, warningMessage } from '../../utils/notificaciones';
import { required } from 'vuelidate/lib/validators';

export default {
  name: 'ayuda',
  data() {
    return {
      form_data: {
        topico: '',
        subtopico: '',
        consecutivo: '',
        ruta: '',
        contenido: '',
      },
      accion: '',
      definitions: {
        insert_img: {
          tip: 'Insertar Imagen',
          icon: 'image',
          handler: this.insertImage,
        },
      },
      toolbar: [
        [
          {
            label: this.$q.lang.editor.align,
            icon: this.$q.iconSet.editor.align,
            fixedLabel: true,
            list: 'only-icons',
            options: ['left', 'center', 'right', 'justify'],
          },
        ],
        ['bold', 'italic', 'strike', 'underline'],
        ['insert_img'],
      ],
    };
  },
  validations: {
    form_data: {
      topico: {
        required,
      },
      subtopico: {
        required,
      },
      consecutivo: {
        required,
      },
    },
  },
  computed: {
    ...mapState('ayuda', ['ayuda']),
  },
  methods: {
    ...mapActions('ayuda', ['saveAyuda', 'editAyuda', 'deleteAyuda']),
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    mensajesError(campo) {
      if (campo === 'topico') {
        if (!this.$v.form_data.topico.required) return 'Campo requerido';
      }
      if (campo === 'subtopico') {
        if (!this.$v.form_data.subtopico.required) return 'Campo requerido';
      }
      if (campo === 'consecutivo') {
        if (!this.$v.form_data.consecutivo.required) return 'Campo requerido';
      }
    },
    reset_form() {
      this.form_data.contenido = '';
      this.form_data.topico = '';
      this.form_data.subtopico = '';
      this.form_data.ruta = '';
      this.form_data.consecutivo = '';
      this.accion = '';
      this.$v.form_data.$reset();
    },

    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data);
    },
    create(accion, obj) {
      this.accion = accion;
      this.form_data.ruta = this.$router.currentRoute.path;
      if (this.accion === 'actualizar') {
        this.setFormData(obj);
      }
      this.$refs.dialog.show();
    },
    ver(accion, obj) {
      this.accion = accion;
      this.setFormData(obj);
      this.$refs.dialog.show();
    },
    closeModal() {
      this.reset_form();
      this.$refs.dialog.hide();
    },
    async save() {
      if (this.form_data.contenido === '') {
        warningMessage('El campo contenido no puede estar vacio');
      } else {
        this.$v.form_data.$touch();
        if (this.$v.form_data.$invalid) {
          infoMessage('Revise los campos inválidos');
        } else {
          this.setLoading(true);
          await this.saveAyuda(this.form_data);
          this.close();
          this.setLoading(false);
        }
      }
    },
    async update() {
      if (this.form_data.contenido === '') {
        warningMessage('El campo contenido no puede estar vacío');
      } else {
        this.$v.form_data.$touch();
        if (this.$v.form_data.$invalid) {
          infoMessage('Revise los campos inválidos');
        } else {
          this.setLoading(true);
          await this.editAyuda(this.form_data);
          this.close();
          this.setLoading(false);
        }
      }
    },
    async remove(id) {
      await this.deleteAyuda(id);
    },
    insertImage() {
      const input = document.createElement('input');
      const self = this;
      input.type = 'file';
      input.accept = '.png, .jpg';
      let file;
      input.onchange = (_) => {
        const files = Array.from(input.files);
        file = files[0];

        const reader = new FileReader();
        let dataUrl = '';
        reader.onloadend = function () {
          dataUrl = reader.result;
          self.$refs.editor.runCmd('insertHTML', `<img src="${dataUrl}"/>`);
        };
        reader.readAsDataURL(file);
      };
      input.click();
    },
  },
};
</script>

<style scoped></style>
