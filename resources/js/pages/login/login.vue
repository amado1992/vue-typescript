<template>
  <q-layout>
    <q-page-container>
      <div class="bg-img">
        <q-page class="flex bg-image flex-center">
          <q-card
            :style="$q.screen.lt.sm ? { width: '75%' } : { width: '25%' }"
          >
            <q-card-section>
              <div class="text-center q-pt-lg">
                <div class="col text-h5">Bienvenido</div>
              </div>
            </q-card-section>
            <q-card-section class="q-px-md">
              <q-form @submit="onSubmit" ref="loginForm">
                <q-input
                  dense
                  outlined
                  v-model="username"
                  label="Usuario"
                  lazy-rules
                  :rules="[
                    (val) =>
                      (val && val.length > 0) ||
                      'Por favor debe escribir un usuario',
                  ]"
                />

                <q-input
                  dense
                  outlined
                  :type="isPwd ? 'password' : 'text'"
                  v-model="password"
                  label="Contraseña"
                  lazy-rules
                  :rules="[
                    (val) =>
                      (val && val.length > 0) ||
                      'Por favor debe escribir una contraseña',
                  ]"
                >
                  <template v-slot:append>
                    <q-icon
                      :name="isPwd ? 'visibility_off' : 'visibility'"
                      class="cursor-pointer"
                      @click="isPwd = !isPwd"
                    />
                  </template>
                </q-input>

                <div>
                  <q-btn
                    unelevated
                    :loading="this.getterLoading()"
                    color="primary"
                    class="full-width"
                    label="Iniciar Sesión"
                    type="submit"
                  >
                    <template v-slot:loading>
                      <q-spinner-facebook />
                    </template>
                  </q-btn>
                </div>
                <div class="q-pt-xs">
                  <q-btn
                    flat
                    text-color="primary"
                    class="full-width"
                    label="Recuperar contraseña"
                    @click="openModal"
                  >
                  </q-btn>
                </div>
              </q-form>
            </q-card-section>
          </q-card>
        </q-page>
      </div>
      <!-- Modal para recuperar el password -->
      <q-dialog
        v-model="modalPass"
        persistent
        transition-show="scale"
        transition-hide="scale"
      >
        <q-card style="width: 550px; max-width: 70vw;">
          <q-card-section class="row no-padding">
            <q-toolbar class="bg-primary text-white">
              <q-toolbar-title> Actualizar contraseña </q-toolbar-title>
              <q-btn
                round
                dense
                flat
                icon="close"
                @click="closeModal()"
                v-close-popup
              >
                <q-tooltip>Cerrar</q-tooltip>
              </q-btn>
            </q-toolbar>
          </q-card-section>
          <q-card-section class="q-px-sm">
            <p class="text-center text-body2 q-px-md">
              Por favor, introduzca su correo electrónico para recibir la nueva
              contraseña.
            </p>
            <div class="col q-px-md">
              <q-input
                v-model="correo"
                type="text"
                label="Correo"
                dense
                name="correo"
                :error-message="mensajesError('correo')"
                :error="$v.correo.$error"
                debounce="1000"
              >
                <template v-slot:prepend>
                  <q-icon name="email" />
                </template>
              </q-input>
            </div>
          </q-card-section>
          <q-card-actions align="right">
            <div class="q-px-md">
              <q-btn
                type="button"
                icon="save"
                @click="enviar()"
                label="Aceptar"
                color="primary"
                flat
                :loading="this.getterLoading()"
              />
              <q-btn
                type="button"
                @click="closeModal()"
                icon="close"
                label="Cancelar"
                v-close-popup
                color="negative"
                flat
              />
            </div>
          </q-card-actions>
        </q-card>
      </q-dialog>
      <!-- End Modal para recuperar el password -->
    </q-page-container>
  </q-layout>
</template>
<script>
import { date } from 'quasar';
import { mapGetters, mapActions } from 'vuex';
import { successMessage, infoMessage } from '../../utils/notificaciones';
import { required, email } from 'vuelidate/lib/validators';
import * as message from '../../utils/resources';

export default {
  name: 'login',
  data() {
    return {
      dateYear: date.formatDate(Date.now(), 'YYYY'),
      username: null,
      password: null,
      isPwd: true,
      modalPass: false,
      correo: '',
    };
  },
  validations: {
    correo: {
      required,
      email,
    },
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['loginUsuario', 'changePass']),
    ...mapActions('utils', ['setLoading']),
    mensajesError(campo) {
      if (!this.$v.$invalid) {
        this.form_error = false;
      }
      if (campo === 'correo') {
        if (!this.$v.correo.required) {
          return 'El correo es obligatorio';
        }
        if (!this.$v.correo.email) {
          return 'Formato de correo invalido';
        }
      }
    },
    async onSubmit() {
      this.setLoading(true);
      this.loginUsuario({
        username: this.username,
        password: this.password,
      }).then((r) => {
        this.setLoading(false);
        this.validateResult(r);
      });
    },
    validateResult(result) {
      switch (result.success) {
        case false:
          return this.$q.notify({
            timeout: 2000,
            position: 'top-right',
            icon: 'sentiment_very_dissatisfied',
            message: result.message,
            color: 'negative',
            textColor: 'white',
            actions: [{ icon: 'close', color: 'white' }],
          });
        case true:
          sessionStorage.setItem('token', result.data.token);
          sessionStorage.setItem('username', result.data.username);
          sessionStorage.setItem('scopes', result.data.scopes);
          sessionStorage.setItem('email', result.data.email);
          sessionStorage.setItem('instalacion', result.data.instalacion);
          sessionStorage.setItem('instalacion_id', result.data.instalacion_id);
          sessionStorage.setItem('osde_id', result.data.osde_id);
          sessionStorage.setItem('municipio_id', result.data.municipio_id);
          successMessage(message.sLogin + ': ' + result.data.username);
          if (sessionStorage.getItem('scopes').includes('ver_dashboard')) {
            return this.$router.push({ name: 'dashboard' });
          } else {
            return this.$router.push({ name: 'notAuthorized' });
          }
        default:
          return this.$q.notify({
            timeout: 2000,
            position: 'top-right',
            icon: 'sentiment_very_dissatisfied',
            message:
              'Estamos presentando problemas con el servidor, por favor inténtelo más tarde.',
            color: 'negative',
            textColor: 'white',
            actions: [{ icon: 'close', color: 'white' }],
          });
      }
    },
    openModal() {
      this.modalPass = true;
    },
    closeModal() {
      this.modalPass = false;
      this.correo = '';
      this.$v.$reset();
    },
    enviar() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        infoMessage('Revise los campos inválidos');
      } else {
        this.setLoading(true);
        this.changePass({
          correo: this.correo,
        }).then((r) => {
          if (r !== undefined) {
            this.setLoading(false);
            successMessage(message.passChange);
            this.modalPass = false;
          } else {
            this.setLoading(false);
          }
        });
      }
    },
  },
};
</script>

<style scoped>
.span-copyright {
  color: #3e506b;
  font-size: 10px;
}
.bg-image {
  background-image: linear-gradient(135deg, #1776dc 50%, #04346a 200%);
}</style
>s
