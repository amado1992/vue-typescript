<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="text-subtitle1 text-weight-bold text-uppercase">
            gestionar ficha de expertos
          </div>
          <q-space></q-space>
          <q-input
            dense
            autofocus
            flat
            v-if="filterInput"
            v-model="filter"
            debounce="1000"
            placeholder="Buscar"
            class="q-ml-md"
          />
          <q-btn
            key="filter"
            dense
            s
            :color="filterInput ? 'blue-grey-3' : 'primary'"
            icon="search"
            @click.prevent="filterStatus()"
            class="q-mr-sm q-ml-sm q-mt-sm"
          >
            <q-tooltip>Buscar</q-tooltip>
          </q-btn>
          <q-btn
            v-if="scopes.includes('gestionar_ths')"
            type="reset"
            key="add"
            dense
            color="primary"
            icon="add"
            @click.prevent="openModal(true, 'adicionar')"
            class="q-mt-sm"
          >
            <q-tooltip>Nuevos datos</q-tooltip>
          </q-btn>
        </div>
      </q-card-section>
      <!-- tabla-->
      <q-card-section>
        <q-table
          :grid="xssmallScreen || smallScreen"
          :hide-header="$q.screen.xs"
          flat
          :data="data"
          :columns="columns"
          row-key="id"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          no-data-label="No se encontraron elementos a mostrar"
          dense
        >
          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              
              <div class="q-gutter-xs">
                 <q-btn
                    flat
                    key="see"
                    dense
                    size="sm"
                    text-color="primary"
                    icon="remove_red_eye"
                    @click.prevent="openModalSee(true, props.row)"
                  >
                    <q-tooltip>Ver detalles</q-tooltip>
                  </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="
                    openModalUpdate(true, 'actualizar', props.row)
                  "
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRow(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>

                <!--Menu ver mas proceso principal
                <q-btn size="sm" dense icon="more_vert" color="grey" flat>
                  <q-tooltip>Ver más</q-tooltip>
                  <q-menu>
                    <q-list dense style="min-width: 157px">
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="
                          openIdiomaHabilidadEvaluacion(props.row)
                        "
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Idioma-habilidad</q-item-section
                        >
                      </q-item>

                      <q-separator />
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalSee(true, 'see', props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Ver más datos</q-item-section
                        >
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
                Fin menu ver mas proceso principal-->
              </div>
            </q-td>
          </template>

          <template v-slot:item="props">
            <q-card class="q-ma-xs">
              <q-card-section>
                <q-btn
                    flat
                    key="see"
                    dense
                    size="sm"
                    text-color="primary"
                    icon="remove_red_eye"
                    @click.prevent="openModalSee(true, props.row)"
                  >
                    <q-tooltip>Ver detalles</q-tooltip>
                  </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  size="sm"
                  dense
                  text-color="primary"
                  icon="edit"
                  @click.prevent="
                    openModalUpdate(true, 'actualizar', props.row)
                  "
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
                <q-btn
                  size="sm"
                  v-if="scopes.includes('gestionar_ths')"
                  flat
                  dense
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRow(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>

                <!--Menu ver mas proceso principal-->
                <!--<q-btn icon="more_vert" color="grey" flat size="sm" dense>
                  <q-tooltip>Ver más</q-tooltip>
                  <q-menu>
                    <q-list dense style="min-width: 157px">
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="
                          openIdiomaHabilidadEvaluacion(props.row)
                        "
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Idioma-habilidad</q-item-section
                        >
                      </q-item>

                      <q-separator />
                      <q-item
                        clickable
                        v-ripple
                        @click.prevent="openModalSee(true, 'see', props.row)"
                      >
                        <q-item-section avatar>
                          <q-icon :size="'xs'" color="blue" name="mdi-eye" />
                        </q-item-section>
                        <q-item-section
                          class="text-body2"
                          style="margin-left: -25px"
                          >Ver más datos</q-item-section
                        >
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
                Fin menu ver mas proceso principal-->
              </q-card-section>
              <q-separator></q-separator>
              <q-list dense>
                <q-item :key="col.id" v-for="col in props.cols">
                  <q-item-section>
                    <q-item-label v-if="col.field != 'actions'">{{
                      col.label
                    }}</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-item-label caption>{{ col.value }}</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card>
          </template>

          <template v-slot:loading>
            <q-inner-loading showing color="primary" />
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
    <!-- Dialog -->
    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 900px; max-width: 70vw">
        <q-form
          @reset="reset_form"
          @submit="accion != 'adicionar' ? update() : save()"
          ref="myForm"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == "adicionar"
                      ? "Adicionar ficha de experto"
                      : "Actualizar ficha de experto"
                  }}
                </q-toolbar-title>
                <q-btn
                  round
                  dense
                  flat
                  icon="close"
                  @click="closeModal"
                  v-close-popup
                >
                  <q-tooltip>Cerrar</q-tooltip>
                </q-btn>
              </q-toolbar>
            </q-card-section>

            <q-card-section>
              <q-scroll-area style="height: 400px; max-width: 900px">
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      ref="nombreApellidos"
                      v-model="form_create.nombreApellidos"
                      label="Nombre y apellidos *"
                      lazy-rules
                      :rules="[required]"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      maxlength="11"
                      type="number"
                      ref="carnetIdentidad"
                      v-model="form_create.carnetIdentidad"
                      label="Carnet de identidad *"
                      lazy-rules
                      :rules="[required, ci]"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      v-model="form_create.direccion"
                      label="Dirección *"
                      lazy-rules
                      :rules="[required]"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      type="tel"
                      v-model="form_create.phone"
                      label="Teléfono *"
                      lazy-rules
                      :rules="[required]"
                    >
                      <template v-slot:append>
                        <q-icon name="phone" />
                      </template>
                    </q-input>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-select
                      v-model="form_create.mtmunicipio_id"
                      :options="municipios"
                      label="Municipios *"
                      options-dense
                      option-value="id"
                      option-label="nombre"
                      emit-value
                      map-options
                      :rules="[required]"
                    >
                    </q-select>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      type="email"
                      v-model="form_create.email"
                      label="Correo *"
                      lazy-rules
                      :rules="[required]"
                    >
                      <template v-slot:append>
                        <q-icon name="email" />
                      </template>
                    </q-input>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      v-model="form_create.instalacionLaboral"
                      label=" Instalación laboral *"
                      lazy-rules
                      :rules="[required]"
                      hint="Instalacion donde trabaja"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      v-model="form_create.departamentoLaboral"
                      label="Departamento laboral *"
                      lazy-rules
                      :rules="[required]"
                      hint="Departamento donde trabaja"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      type="tel"
                      v-model="form_create.phoneLaboral"
                      label="Teléfono laboral*"
                    >
                      <template v-slot:append>
                        <q-icon name="phone" />
                      </template>
                    </q-input>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      type="email"
                      v-model="form_create.emailLaboral"
                      label="Correo laboral*"
                      lazy-rules
                      :rules="[required]"
                    >
                      <template v-slot:append>
                        <q-icon name="email" />
                      </template>
                    </q-input>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      type="url"
                      v-model="form_create.paginaWebLaboral"
                      label=" Página web (laboral)"
                    >
                      <template v-slot:append>
                        <q-icon name="mdi-web" />
                      </template>
                    </q-input>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      type="number"
                      v-model="form_create.annosExperienciaSectorLaboral"
                      label="Años de experiencia *"
                      hint="Años de experiencia en el sector laboral"
                      lazy-rules
                      :rules="[required]"
                    >
                    </q-input>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      type="text"
                      v-model="form_create.cargoLaboral"
                      label="Cargo *"
                      lazy-rules
                      :rules="[required]"
                    >
                    </q-input>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-select
                      v-model="form_create.mtoace_id"
                      :options="oaces"
                      label="OACEs *"
                      options-dense
                      option-value="id"
                      option-label="nombre"
                      emit-value
                      map-options
                      :rules="[required]"
                    >
                    </q-select>
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      v-model="form_create.tituloAcademico"
                      label="Título académico *"
                      lazy-rules
                      :rules="[required]"
                    />
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      v-model="form_create.nombreInstitucionAcademica"
                      label="Instución académica *"
                      lazy-rules
                      :rules="[required]"
                    />
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-input
                      label="Otorgado título académico"
                      v-model="form_create.fechaAcademica"
                      @click="$refs.qDateProxy.show()"
                    >
                      <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                          <q-popup-proxy
                            ref="qDateProxy"
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-date
                              v-model="form_create.fechaAcademica"
                              mask="YYYY-MM-DD"
                            >
                              <div class="row items-center justify-end">
                                <q-btn
                                  v-close-popup
                                  label="Cerrar"
                                  color="negative"
                                  flat
                                />
                              </div>
                            </q-date>
                          </q-popup-proxy>
                        </q-icon>
                      </template>
                    </q-input>
                  </div>

                  <!--<div class="col-xs-12 col-sm-12 col-md-6 col-lg-5 q-px-sm">-->
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-select
                      v-model="form_create.mtcategdocentecientifica_id"
                      :options="categorias"
                      label="Categoría docente/científica *"
                      options-dense
                      option-value="id"
                      option-label="nombre"
                      emit-value
                      map-options
                      :rules="[required]"
                    >
                    </q-select>
                  </div>
                  <!--<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-md organismo">-->
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 q-px-sm">
                    <q-select
                      v-model="organismo"
                      :options="organismoList"
                      label="Organismo"
                      options-dense
                      option-value="id"
                      option-label="name"
                      emit-value
                      map-options
                    >
                    </q-select>
                    <!-- <q-field
                  borderless
                   label="Organismo" stack-label>
                    <template v-slot:control>
                      <q-radio
                        v-model="organismo"
                        val="mintur"
                        label="Mintur"
                      />
                      <q-radio
                        v-model="organismo"
                        val="minsap"
                        label="Minsap"
                      />
                    </template>
                  </q-field>-->
                  </div>

                  <div
                    v-if="organismo == 'mintur'"
                    class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm"
                  >
                    <q-select
                      @input="onValueChange()"
                      v-model="form_create.entidad_id"
                      :options="entidades"
                      label="Entidad"
                      options-dense
                      option-value="id"
                      option-label="nombre"
                      emit-value
                      map-options
                    >
                    </q-select>
                  </div>
                  <div
                    v-if="isOsde && organismo == 'mintur'"
                    class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm"
                  >
                    <q-select
                      @input="loadEmpresas()"
                      v-model="osde_id"
                      :options="osdes"
                      label="Osdes"
                      options-dense
                      option-value="id"
                      option-label="nombre"
                      emit-value
                      map-options
                    >
                    </q-select>
                  </div>
                  <div
                    v-if="isOsde && organismo == 'mintur'"
                    class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm"
                  >
                    <q-select
                      @input="loadUebs()"
                      v-model="empresa_id"
                      :options="empresas"
                      label="Empresas"
                      options-dense
                      option-value="id"
                      option-label="nombre"
                      emit-value
                      map-options
                    >
                    </q-select>
                  </div>

                  <div
                    v-if="isOsde && organismo == 'mintur'"
                    class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm"
                  >
                    <q-select
                      v-model="form_create.mtueb_id"
                      :options="uebs"
                      label="Uebs"
                      options-dense
                      option-value="id"
                      option-label="nombre"
                      emit-value
                      map-options
                    >
                    </q-select>
                  </div>

                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 q-px-sm">
                    <q-input
                      label="Perfil *"
                      v-model="form_create.perfil"
                      type="textarea"
                      hint="Breve resumen sobre el experto"
                      autogrow
                    />
                  </div>
                </div>
              </q-scroll-area>
            </q-card-section>

            <q-card-actions align="right">
              <q-btn
                type="submit"
                key="guardar"
                label="Guardar"
                flat
                color="primary"
                :icon="accion === 'adicionar' ? 'save' : 'edit'"
                :disable="isComplete"
              >
              </q-btn>
              <q-btn
                type="button"
                icon="close"
                @click="closeModal()"
                label="Cancelar"
                v-close-popup
                color="negative"
                flat
              />
            </q-card-actions>
          </q-card-section>
        </q-form>
      </q-card>
    </q-dialog>
    <ExpertoIdioma v-show="showForm" :itemSelected="form_create" />
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { showDialog } from "../../utils/dialogo";
import { infoMessage } from "../../utils/notificaciones";
import * as message from "../../utils/resources";

import axios from "axios";
import { date } from "quasar";
import { errorMessage, successMessage } from "../../utils/notificaciones";
import ExpertoIdioma from "../../components/ExpertoIdioma";
import SmallScreen from "../../mixin/SmallScreen";
export default {
  name: "FichaExpertoPage",
  components: {
    ExpertoIdioma,
  },
  mixins: [SmallScreen],
  data() {
    return {
      scopes: sessionStorage.getItem("scopes"),
      modalDialog: false,
      filterInput: false,
      //loading: false,
      filter: "",
      accion: "",
      required: (val) => !!val || "Por favor, escriba algo",
      ci: (val) => (val && val.length == 11) || "CI iválido",
      form_create: {
        id: 0,
        perfil: "",
        nombreApellidos: "",
        carnetIdentidad: "",
        direccion: "",
        mtmunicipio_id: null,
        phone: "",
        email: "",
        entidad_id: null,

        instalacionLaboral: "",
        departamentoLaboral: "",
        phoneLaboral: "",
        emailLaboral: "",
        paginaWebLaboral: "",
        annosExperienciaSectorLaboral: null,
        cargoLaboral: "",
        mtoace_id: "",

        tituloAcademico: "",
        nombreInstitucionAcademica: "",
        fechaAcademica: "",
        mtcategdocentecientifica_id: "",

        mtueb_id: null,
      },
      columns: [
        {
          name: "nombreApellidos",
          align: "left",
          label: "Nombre y apellidos",
          field: (row) => row.nombreApellidos,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "oace",
          align: "left",
          label: "OACE",
          field: (row) => row.oace.nombre,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "isntalacion",
          align: "left",
          label: "Instalación de labor",
          field: (row) => row.instalacionLaboral,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        {
          name: "phone",
          align: "left",
          label: "Teléfono",
          field: (row) => row.phone,
          sortable: true,
          headerClasses: "text-uppercase",
        },
        /*{
          name: "email",
          align: "left",
          label: "Correo electrónico",
          field: (row) => row.email,
          sortable: true,
          headerClasses: "text-uppercase",
        },*/
        /*{
          name: "experiencia",
          align: "left",
          label: "Años de experiencia",
          field: (row) => row.annosExperienciaSectorLaboral,
          sortable: true,
          headerClasses: "text-uppercase",
        },*/
        {
          name: "actions",
          label: "Acciones",
          field: "actions",
          align: "center",
          headerClasses: "text-uppercase",
        },
      ],
      data: [],
      municipios: [],
      oaces: [],
      entidades: [],
      categorias: [],
      showForm: false,
      organismo: "",
      organismoList: [
        { name: "Mintur", id: "mintur" },
        { name: "Minsap", id: "minsap" },
      ],
      isOsde: false,
      osdes: [],
      empresas: [],
      uebs: [],
      osde_id: null,
      empresa_id: null,
    };
  },
  created() {
    this.loadData();
    this.loadMunicipios();
    this.loadOaces();
    this.loadCategoriaDocenteCientifica();
    this.loadEntidades();
    this.loadOsdes();
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Dirección de Calidad" },
      { label: "Turismo más higiénico y seguro" },
      { label: "Nomencladores" },
      { label: "Ficha de expertos" },
    ]);
  },
  watch: {},
  computed: {
    isComplete() {
      if (this.form_create.nombreApellidos && this.form_create.mtoace_id) {
        return false;
      }
      return true;
    },
  },
  methods: {
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("utils", ["setLoading"]),
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    onValueChange() {
      // something change
      const found = this.entidades.find(
        (element) => element.id == this.form_create.entidad_id
      );
      if (found.nombre == "OSDE") {
        this.isOsde = true;
      } else {
        this.isOsde = false;
      }
      console.log("something change", found.nombre);
    },
    async loadUebs() {
      let id = this.empresa_id;
      this.setLoading(true);
      return await axios
        .get("/api/uebs_exp/" + id)
        .then((result) => {
          this.uebs = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadEmpresas() {
      let id = this.osde_id;
      console.log("fff");
      this.setLoading(true);
      return await axios
        .get("/api/empresas_exp/" + id)
        .then((result) => {
          //this.empresas = result.data.data;
          console.log(result);
          this.empresas = result.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    reset_form() {
      this.form_create.id = 0;
      this.form_create.perfil = "";
      this.form_create.nombreApellidos = "";
      this.form_create.carnetIdentidad = "";
      this.form_create.direccion = "";
      this.form_create.mtmunicipio_id = null;
      this.form_create.phone = "";
      this.form_create.email = "";

      this.form_create.instalacionLaboral = "";
      this.form_create.departamentoLaboral = "";
      this.form_create.phoneLaboral = "";
      this.form_create.emailLaboral = "";
      this.form_create.paginaWebLaboral = "";
      this.form_create.annosExperienciaSectorLaboral = null;
      this.form_create.cargoLaboral = "";
      this.form_create.mtoace_id = "";
      this.form_create.mtueb_id = null;
      this.form_create.entidad_id = null;

      this.form_create.tituloAcademico = "";
      this.form_create.nombreInstitucionAcademica = "";
      this.form_create.fechaAcademica = "";
      this.form_create.mtcategdocentecientifica_id = "";
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
    async loadData() {
      this.setLoading(true);
      return await axios
        .get("/api/ficha_expertos")
        .then((result) => {
          this.data = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async deleteRow(dataRow) {
      this.confirmDelete(dataRow);
    },
    async deleteRowRequest(dataRow) {
      this.setFormData(dataRow);
      const id = this.form_create.id;
      this.$q.loading.show();
      return await axios
        .delete("/api/ficha_experto/" + id)
        .then((response) => {
          this.reset_form();
          this.$q.loading.hide();
          this.loadData();
          successMessage("Los datos se eliminaron satisfactoriamente");
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    confirmDelete(dataRow) {
      this.$q
        .dialog({
          title: "Confirme",
          message: "¿Estás seguro que desea eliminar esta ficha de experto?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          this.deleteRowRequest(dataRow);
        })
        .onCancel(() => {});
    },
    openModal(data, accion) {
      this.accion = accion;
      if (accion === "adicionar") {
        this.modalDialog = data;
      }
    },

    openModalUpdate(data, accion, dataRow) {
      this.accion = accion;

      if (accion === "actualizar") {
        this.setFormData(dataRow);
        this.modalDialog = data;
      }
    },
    setFormData(data) {
      this.form_create = Object.assign(this.form_create, data);
    },
    async save() {
      this.$refs.myForm.validate().then((success) => {
        if (success) {
          //valid value
        } else {
          //invalid value
        }
      });

      return await axios
        .post("/api/ficha_experto", this.form_create)
        .then((result) => {
          this.loadData();
          successMessage("Los datos se insertaron satisfactoriamente");
          this.closeModal();
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async update() {
      let id = this.form_create.id;
      return await axios
        .put("/api/ficha_experto/" + id, this.form_create)
        .then((result) => {
          this.loadData();
          successMessage("Los datos se actualizaron satisfactoriamente");
          this.closeModal();
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadCategoriaDocenteCientifica() {
      this.setLoading(true);
      return await axios
        .get("/api/categorias_docente_cientifica")
        .then((result) => {
          this.categorias = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadOaces() {
      this.setLoading(true);
      return await axios
        .get("/api/oaces")
        .then((result) => {
          this.oaces = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadEntidades() {
      this.setLoading(true);
      return await axios
        .get("/api/entidades_tmhs")
        .then((result) => {
          this.entidades = result.data.data;
          this.setLoading(false);
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadOsdes() {
      return await axios
        .get("/api/lists_osdes")
        .then((response) => {
          this.osdes = response.data.data;
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async loadMunicipios() {
      return await axios
        .get("/api/municipios")
        .then((response) => {
          this.municipios = response.data.data;
        })
        .catch((err) => {
          this.$q.notify({
            color: "negative",
            position: "top",
            message: "Carga fallida",
            icon: "report_problem",
          });
        });
    },
    async openIdiomaHabilidadEvaluacion(dataRow) {
      Object.assign(this.form_create, dataRow);
      // this.dialogListAlcance = false;
      this.$root.$emit("openTable");
      this.showForm = true;
    },
  },
};
</script>

<style scoped>
.organismo {
  padding-left: 28px;
}
@media screen and (max-width: 1440px) {
  .organismo {
    padding-left: 0px;
  }
}
</style>
