<template>
  <q-layout view="hHh LpR lFf">
    <q-header
      elevated
      class="header_normal text-white q-py-xs"
      height-hint="58"
    >
      <q-toolbar>
        <q-btn
          flat
          @click="miniStates()"
          dense
          icon="menu"
          color="white"
          >
        </q-btn>

        <q-toolbar-title>
              Procesos
        </q-toolbar-title>

        <q-btn
          v-if="scopes.includes('ver_ayuda')"
          @click.prevent="showHelp"
          dense
          flat
          class="linkcard"
        >
          <q-icon name="mdi-help-circle"></q-icon>
          <q-tooltip>Ayuda</q-tooltip>
        </q-btn>
        <q-btn
          @click.prevent="
            stateDialogChangePassword = !stateDialogChangePassword
          "
          flat
          dense
          class="linkcard"
        >
          <q-icon name="mdi-security"></q-icon>
          <q-tooltip>Cambiar Contraseña</q-tooltip>
        </q-btn>
        <q-btn @click.prevent="logout" flat dense class="linkcard">
          <q-icon name="mdi-logout"></q-icon>
          <q-tooltip>Salir</q-tooltip>
        </q-btn>
      </q-toolbar>
    </q-header>
    <!-- Drawer menu -->
    <q-drawer
      :mini="miniState"
      v-model="leftDrawerOpen"
      @mouseover="miniState = false"
      show-if-above
      :width="280"
      content-class="bg-primary"
      elevated
    >
      <DrawerMenuOptions
        :username="username"
        :email="email"
        :scopes="scopes"
      ></DrawerMenuOptions>
    </q-drawer>
    <!-- End Drawer menu -->
    <!-- Drawer ayuda -->
    <q-drawer side="right" v-model="helpShow" :value="helpShow" elevated>
      <q-scroll-area class="fit">
        <q-toolbar class="bg-primary">
          <q-btn
            v-if="scopes.includes('ver_ayuda')"
            @click.prevent="addAyuda"
            key="add"
            flat
            dense
            color="white"
            icon="add"
          >
            <q-tooltip>Crear</q-tooltip>
          </q-btn>
          <q-space></q-space>
          <span style="color: white;">{{ nameRuta }}</span>
          <q-space></q-space>
          <q-btn
            @click.prevent="helpShow = !helpShow"
            key="close"
            flat
            dense
            color="white"
            icon="close"
          >
            <q-tooltip>Cerrar</q-tooltip>
          </q-btn>
        </q-toolbar>
        <q-list class="text-grey-8 text-caption" dense>
          <q-item v-for="ayuda in this.arrayAyuda" :key="ayuda.id">
            <q-item-section avatar>
              <q-icon name="mdi-help-circle-outline"/>
            </q-item-section>
            <q-item-section
              class="cursor-pointer"
              @click.prevent="showAyuda(ayuda.id)"
            >
              <q-item-label caption :lines="2">{{
                ayuda.subtopico
                }}
              </q-item-label>
            </q-item-section>
            <q-item-section avatar>
              <q-toolbar class="white" dense>
                <transition-group
                  appear
                  enter-active-class="animated fadeIn"
                  leave-active-class="animated fadeOut"
                >
                  <q-btn
                    v-if="scopes.includes('update_ayuda')"
                    key="edit"
                    flat
                    icon="edit"
                    @click.prevent="updateAyuda(ayuda)"
                  >
                    <q-tooltip>Actualizar</q-tooltip>
                  </q-btn>
                  <q-btn
                    v-if="scopes.includes('delete_ayuda')"
                    key="delete"
                    flat
                    icon="delete"
                    @click.prevent="removeAyuda(ayuda.id)"
                  >
                    <q-tooltip>Borrar</q-tooltip>
                  </q-btn>
                </transition-group>
              </q-toolbar>
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>
    </q-drawer>
    <!-- End Drawer ayuda -->
    <q-page-container>
      <q-page class="fondo">
        <div class="q-pa-md">
          <Breadcrumbs :list_breads="breads"></Breadcrumbs>
          <router-view/>
        </div>
      </q-page>
      <q-inner-loading :showing="spi">
        <q-spinner-pie color="green" v-show="spi" size="4.4em"/>
      </q-inner-loading>
    </q-page-container>
    <!-- Dialog para cambiar password -->
    <q-dialog
      v-model="stateDialogChangePassword"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 500px;">
        <q-card-section class="bg-primary">
          <div class="text-h6 text-white">Cambiar Contraseña</div>
        </q-card-section>
        <q-card-section class="q-pa-lg">
          <br/>
          <q-input
            v-model="password"
            filled
            clearable
            label="Escriba su nueva contraseña *"
            :type="isPwd ? 'password' : 'text'"
            :rules="[
              (val) =>
                (val && val.length > 0) || 'Por favor debe llenar el campo',
              (val) =>
                val.length > 5 ||
                'La contrseña debe tener como mínimo 6 caracateres',
            ]"
            lazy-rules
            :error-message="mensajesError('password')"
            :error="$v.password.$error"
          >
            <template v-slot:prepend>
              <q-icon name="security"/>
            </template>
            <template v-slot:append>
              <q-icon
                :name="isPwd ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="isPwd = !isPwd"
              />
            </template>
          </q-input>

          <q-input
            v-model="passwordConfirm"
            filled
            clearable
            label="Confirme su nueva contraseña *"
            :type="isPwdConfirm ? 'password' : 'text'"
            :rules="[
              (val) =>
                (val && val.length > 0) || 'Por favor debe llenar el campo',
              (val) => val === password || 'Las contraseñas no son iguales',
              (val) =>
                val.length > 5 ||
                'La contrseña debe tener como mínimo 6 caracateres',
            ]"
            lazy-rules
            :error-message="mensajesError('passwordConfirm')"
            :error="$v.passwordConfirm.$error"
          >
            <template v-slot:prepend>
              <q-icon name="security"/>
            </template>
            <template v-slot:append>
              <q-icon
                :name="isPwdConfirm ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="isPwdConfirm = !isPwdConfirm"
              />
            </template>
          </q-input>
          <div class="text-red" v-show="form_error">
            Los campos marcados con (*) son de llenado obligatorio
          </div>
        </q-card-section>
        <q-separator inset/>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="save"
            @click="changePassword"
            label="Guardar"
            color="primary"
            :loading="this.getterLoading()"
            flat
          />
          <q-btn
            type="button"
            icon="close"
            @click="onReset"
            label="Cancelar"
            v-show="cancel"
            v-close-popup
            color="negative"
            flat
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <!-- End Dialog para cambiar password -->
    <ayuda ref="ayuda"/>
  </q-layout>
</template>
<script>
    import Breadcrumbs from '../components/breadcrumbs';
    import {mapGetters, mapActions} from 'vuex';
    import {showDialog} from '../utils/dialogo';
    import Ayuda from '../pages/ayuda/ayuda';
    import * as message from '../utils/resources';
    import {required, sameAs, minLength} from 'vuelidate/lib/validators';
    import {infoMessage} from '../utils/notificaciones';
    import DrawerMenuOptions from '../components/DrawerMenuOptions.vue';

    export default {
        name: 'MainLayout',
        components: {
            Breadcrumbs,
            Ayuda,
            DrawerMenuOptions,
        },
        data() {
            return {
                appMenu: false,
                openNomenclador: false,
                nomenclador: false,
                openSeguriad: false,
                miniState: false,
                spi: false,
                mini: true,
                nameState: 'Ocultar Menú',
                leftDrawerOpen: false,
                scopes: '',
                username: '',
                email: '',
                instalacion: '',
                instalacion_id: '',
                dashboard: false,
                cancel: true,
                seguridad: false,
                configuracion: false,
                menu: false,
                reporte: false,
                form_error: false,
                required_error: false,
                stateDialogChangePassword: false,
                password: null,
                isPwd: true,
                passwordConfirm: null,
                isPwdConfirm: true,
                helpShow: false,
                arrayAyuda: [],
                nameRuta: '',
                breads: [
                    {
                        label: 'Menú Navegación',
                    },
                ],
            };
        },
        validations: {
            password: {
                required,
                minLength: minLength(6),
            },
            passwordConfirm: {
                required,
                sameAsPassword: sameAs('password'),
            },
        },
        mounted() {
            this.sidebarPermissions();
            this.checkVerDashboard();
            this.checkVerSeguridad();
            this.checkVerNomencladore();
            this.loadSpi(true);
            this.loadFirstLogin();
        },
        methods: {
            ...mapGetters('utils', ['getterLoading']),
            ...mapActions('usuario', [
                'logoutUsuario',
                'changePasswordUsuario',
                'getLoadFirstLogin',
            ]),
            ...mapActions('breadcrumbs', ['addToBreadcrumbs']),
            ...mapActions('utils', ['setLoading']),
            ...mapActions('ayuda', ['getAyuda', 'getAyudaId']),

            checkVerNomencladore() {
                if (this.scopes.includes('gestionar_nomencladores')) {
                    this.nomenclador = true;
                }
            },
            mensajesError(campo) {
                if (!this.$v.$invalid) {
                    this.required_error = false;
                    this.form_error = false;
                }
                if (campo === 'password') {
                    if (!this.$v.password.required) {
                        this.required_error = true;
                        return '';
                    }
                    if (!this.$v.password.minLength) {
                        this.required_error = false;
                        return 'La contraseña debe tener mas de 6 carácteres';
                    }
                }
                if (campo === 'passwordConfirm') {
                    if (!this.$v.passwordConfirm.required) {
                        this.required_error = true;
                        return '';
                    }
                    if (!this.$v.passwordConfirm.sameAsPassword) {
                        this.required_error = false;
                        return 'Las contraseñas no coinciden';
                    }
                }
            },
            showHelp() {
                this.helpShow = true;
                this.loadAyuda();
                this.nameRuta = this.$router.currentRoute.meta.showName;
            },
            loadAyuda() {
                this.getAyuda({ruta: this.$router.currentRoute.path}).then((r) => {
                    this.arrayAyuda = r.data;
                });
            },
            addAyuda() {
                this.$refs.ayuda.create('adicionar', null);
                this.helpShow = false;
            },
            showAyuda(id) {
                this.getAyudaId({id: id}).then((r) => {
                    this.$refs.ayuda.ver('ver', r.data);
                    this.helpShow = false;
                });
            },
            updateAyuda(obj) {
                this.$refs.ayuda.create('actualizar', obj);
                this.helpShow = false;
            },
            removeAyuda(id) {
                showDialog(message.lAskForRemove).onOk(async () => {
                    this.$refs.ayuda.remove(id);
                    this.loadAyuda();
                    this.helpShow = false;
                });
            },
            onReset() {
                this.password = null;
                this.passwordConfirm = null;
                this.form_error = false;
                this.required_error = false;
            },
            async changePassword() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    infoMessage('Revise los campos inválidos');
                    if (this.required_error) {
                        this.form_error = true;
                    }
                } else {
                    this.setLoading(true);
                    await this.changePasswordUsuario({password: this.password}).then(
                        (r) => {
                            sessionStorage.setItem('token', r);
                            this.setLoading(false);
                            this.stateDialogChangePassword = false;
                            this.onReset();
                        }
                    );
                }
            },
            logout() {
                showDialog('¿Desea salir del sistema?').onOk(async () => {
                    await this.logoutUsuario({}).then(() => {
                        sessionStorage.clear();
                        this.$router.push('/');
                    });
                });
            },
            sidebarPermissions() {
                this.scopes = sessionStorage.getItem('scopes');
                this.username = sessionStorage.getItem('username');
                this.email = sessionStorage.getItem('email');
                this.instalacion = sessionStorage.getItem('instalacion');
                this.instalacion_id = sessionStorage.getItem('instalacion_id');
            },
            checkVerDashboard() {
                if (this.scopes.includes('read_dashboard')) {
                    this.dashboard = true;
                }
            },
            checkVerSeguridad() {
                if (
                    this.scopes.includes('read_persona') ||
                    this.scopes.includes('read_role') ||
                    this.scopes.includes('read_usuario') ||
                    this.scopes.includes('read_traza') ||
                    this.scopes.includes('read_disponibilidad')
                ) {
                    this.seguridad = true;
                }
            },
            loadFirstLogin() {
                if (sessionStorage.getItem('username') !== 'superuser') {
                    this.getLoadFirstLogin({
                        user: sessionStorage.getItem('username'),
                    }).then((r) => {
                        if (r.length > 0) {
                            this.loadSpi(false);
                            this.stateDialogChangePassword = true;
                            this.cancel = false;
                        } else this.loadSpi(false);
                    });
                } else {
                    this.loadSpi(false);
                }
            },
            loadSpi(value) {
                this.spi = value;
            },
            miniStates() {
                this.miniState = !this.miniState;
                this.leftDrawerOpen = true;
            },
            inicio() {
                window.location = '/dashboard'
            },
        },
    };
</script>
<style scoped>
  .fondo {
    background-color: #eeeeee;
  }

  .linkcard:hover {
    font-size: 20px;
  }

  .header_normal {
    background: linear-gradient(120deg, #0b5ab0 0%, #093b70 100%);
  }
</style>
