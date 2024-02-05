<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Histórico por productos
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white text-uppercase"
          :data="dataDeficit"
          :columns="columnsDeficit"
          row-key="name"
          :rows-per-page-options="[15, 30, 60]"
          binary-state-sort
          dense
          flat
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col-3">
                <q-option-group
                  dense
                  :options="options"
                  type="radio"
                  v-model="group"
                  @input="mostrar"
                />
              </div>
              <div class="col-3">
                <q-select
                  v-model="form_data.instalacion_id"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  dense
                  outlined
                  :options="items_instalacion"
                  label="Instalación"
                  option-label="nombre"
                  option-value="id"
                  v-show="instalacion"
                  :error-message="mensajesError('instalacion_id')"
                  :error="$v.form_data.instalacion_id.$error"
                />
              </div>
              <div class="col-2 q-px-sm">
                <q-input
                  dense
                  outlined
                  input-class="cursor-pointer"
                  v-show="annoInicial"
                  :label="group === 'anno' ? 'Año' : 'Año Inicio'"
                  mask="####"
                  :value="annoInicio"
                  @click="$refs.yearPickerini.show()"
                  debounce="1000"
                >
                  <template v-slot:append>
                    <q-icon class="cursor-pointer">
                      <q-popup-proxy
                        ref="yearPickerini"
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date
                          minimal
                          mask="YYYY"
                          emit-immediately
                          default-view="Years"
                          v-model="annoInicio"
                          @input="checkValue"
                        />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>
              <div class="col-2 q-pr-sm">
                <q-input
                  dense
                  outlined
                  input-class="cursor-pointer"
                  v-show="annoFinal"
                  label="Año Fin"
                  mask="####"
                  :value="annoFin"
                  @click="$refs.yearPicker.show()"
                  debounce="1000"
                >
                  <template v-slot:append>
                    <q-icon class="cursor-pointer">
                      <q-popup-proxy
                        ref="yearPicker"
                        transition-show="scale"
                        transition-hide="scale"
                      >
                        <q-date
                          minimal
                          mask="YYYY"
                          emit-immediately
                          default-view="Years"
                          v-model="annoFin"
                          @input="checkValue"
                        />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>
              <div class="col-2">
                <q-btn
                  v-show="group"
                  type="button"
                  icon="search"
                  @click="cargarDemandas()"
                  color="primary"
                  :loading="getterLoading()"
                  dense
                >
                  <q-tooltip>Buscar Demandas</q-tooltip>
                </q-btn>
              </div>
            </div>
          </template>
        </q-table>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../../../resources/js/utils/dialogo';
import {
  infoMessage,
  warningMessage,
} from '../../../../resources/js/utils/notificaciones';
import * as message from '../../../../resources/js/utils/resources';
import {
  required,
  maxLength,
  numeric,
  minValue,
} from 'vuelidate/lib/validators';
import { successMessage } from '../../utils/notificaciones';

export default {
  name: 'historico_demanda',
  data() {
    return {
      group: null,
      visibleColumns: ['instalacion', 'anno'],
      modalDialog: false,
      modalDialogAprobar: false,
      permiso: true,
      mostrarFamilia: true,
      annoFinal: false,
      annoInicial: false,
      instalacion: false,
      val: false,
      selected: [],
      roles: [],
      annoInicio: '',
      annoFin: '',
      items_familia: [],
      items_mes: [],
      items_productos: [],
      items_instalacion: [],
      items_editar: [],
      productosFamilia: [],
      rolesName: [],
      aux: [],
      add: true,
      scopes: sessionStorage.getItem('scopes'),
      filter: '',
      accion: '',
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      options: [
        { label: 'Por año', value: 'anno', color: 'primary' },
        {
          label: 'Por rango de años',
          value: 'ran_anno',
          color: 'primary',
        },
      ],
      permissions: [],
      form_data: {
        instalacion_id: '',
        familia_id: '',
        producto_id: '',
        unidad_medida: '',
        enero: '',
        febrero: '',
        marzo: '',
        abril: '',
        mayo: '',
        junio: '',
        julio: '',
        agosto: '',
        septiembre: '',
        octubre: '',
        noviembre: '',
        diciembre: '',
        anno: '',
        total_demanda_anual: '',
        aprobado: '',
        deficit: '',
        porciento_aprobado: '',
      },
      form_error: false,
      required_error: false,
      columnsDeficit: [
        {
          name: 'nombre',
          required: true,
          label: 'Producto',
          align: 'left',
          field: (row) => row.nombre,
          format: (val) => `${val}`,
        },
        {
          name: 'familia',
          required: true,
          label: 'Familia',
          align: 'left',
          field: 'familia',
        },
        {
          name: 'anno',
          required: true,
          label: 'Año',
          align: 'left',
          field: 'anno',
        },
        {
          name: 'deficit',
          label: 'Déficit',
          align: 'center',
          field: 'deficit',
          search: true,
        },
        {
          name: 'porciento_aprobado',
          label: '% Aprobado',
          align: 'center',
          field: 'porciento_aprobado',
          search: true,
        },
      ],
      dataDeficit: [],
      productosDemandas: [],
      annos: [],
    };
  },
  validations: {
    form_data: {
      anno: {
        required,
        maxLength: maxLength(4),
      },
      instalacion_id: {
        required,
      },
      familia_id: {
        required,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Logística' },
      { label: 'Flujo material' },
      { label: 'Reportes' },
      { label: 'Histórico por productos' },
    ]);
    this.getFamilias().then((r) => {
      this.items_familia = r;
    });
    this.getMeses().then((r) => {
      this.items_mes = r;
    });
    this.getInstalaciones().then((r) => {
      this.items_instalacion = r;
    });
    this.getAnnos().then((r) => {
      this.annos = r;
    });
  },
  watch: {
    selected: {
      handler() {
        const app = this;
        if (this.selected.length > 0) {
          const data = {
            id: this.selected[0].instalacion_id,
            anno: this.selected[0].anno,
          };
          this.getDemandaInstalacionAnno(data).then((r) => {
            this.items_editar = r;
          });
        }
      },
    },
    pagination: {
      handler() {
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
      },
    },
  },
  computed: {
    ...mapState('demanda', ['demanda']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('familia', ['getFamilias']),
    ...mapActions('instalacion', ['getInstalaciones']),
    ...mapActions('producto', ['getProductoFamilia']),
    ...mapActions('demanda', [
      'getDemanda',
      'getAnnos',
      'buscarDemandas',
      'saveDemanda',
      'editDemanda',
      'deleteDemanda',
      'getDemandas',
      'getMeses',
      'getDemandaInstalacionAnno',
      'procesarDemandaEspecialista',
    ]),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'anno') {
        if (!this.$v.form_data.anno.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.anno.maxLength) {
          this.required_error = false;
          return 'El año solo puede tener 4 carácteres';
        }
        if (!this.$v.form_data.anno.numeric) {
          this.required_error = false;
          return 'Solo se aceptan números enteros';
        }
      }
      if (campo === 'instalacion_id') {
        if (!this.$v.form_data.instalacion_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'familia_id') {
        if (!this.$v.form_data.instalacion_id.required) {
          this.required_error = true;
          return '';
        }
      }
    },
    typeSelection() {
      return this.scopes.includes('gestionar_demanda') ||
        this.scopes.includes('gestionar_demanda')
        ? 'single'
        : 'none';
    },
    filterFn(val, update) {
      update(() => {
        const needle = val.toLowerCase();
        this.options = this.permissions.filter(
          (v) => v.name.toLowerCase().indexOf(needle) > -1
        );
      });
    },
    convert(activo) {
      return activo === '1';
    },
    closeModal() {
      this.modalDialog = false;
      this.modalDialogAprobar = false;
      this.mostrarFamilia = true;
      this.reset_form();
      this.selected = [];
      this.data = [];
      this.columnss = [];
    },
    async cargarProductos() {
      this.setLoading(true);
      const data = {
        id: this.form_data.familia_id,
      };
      await this.getProductoFamilia(data).then((r) => {
        this.productosFamilia = r.data;
        this.actualizarTabla(this.productosFamilia);
        this.setLoading(false);
      });
    },
    async cargarDemandas() {
      if (this.group === null) {
        warningMessage('Debe escoger una opción de busqueda');
      } else {
        this.productosDemandas = [];
        if (this.group === 'anno') {
          if (this.annoInicio === '' || this.form_data.instalacion_id === '') {
            warningMessage('Debe llenar todos los campos');
          } else {
            this.setLoading(true);
            const data = {
              id: this.form_data.instalacion_id,
              anno: this.annoInicio,
            };
            await this.getDemandaInstalacionAnno(data).then((p) => {
              this.productosDemandas = p;
              this.setLoading(false);
              if (this.productosDemandas.length === 0) {
                warningMessage('No existen demandas para esos parámetros');
              } else {
                successMessage('Demandas Encontradas');
              }
            });
            this.actualizarTabla(this.productosDemandas);
          }
        } else if (this.group === 'ran_anno') {
          if (
            this.annoInicio === '' ||
            this.form_data.instalacion_id === '' ||
            this.annoFin === ''
          ) {
            warningMessage('Debe llenar todos los campos');
          } else {
            this.setLoading(true);
            const data = {
              id: this.form_data.instalacion_id,
              annoInicial: this.annoInicio,
              annoFinal: this.annoFin,
            };
            await this.buscarDemandas(data).then((r) => {
              this.productosDemandas = r;
              this.setLoading(false);
              if (this.productosDemandas.length === 0) {
                warningMessage('No existen demandas para esos parámetros');
              } else {
                successMessage('Demandas Encontradas');
              }
            });
            this.actualizarTabla(this.productosDemandas);
          }
        }
      }
    },
    calcularDeficit() {
      var flag = true;
      for (var i = 0; i < this.data.length; i++) {
        if (
          this.data[i].aprobado > this.data[i].total_demanda_anual ||
          this.data[i].aprobado === null
        ) {
          warningMessage(
            'El aprobado debe ser meno o igual al total demandado'
          );
          flag = false;
        } else {
          if (this.data.length > 0) {
            this.data[i].deficit =
              parseInt(this.data[i].total_demanda_anual) -
              parseInt(this.data[i].aprobado);
            this.data[i].porciento_aprobado =
              (parseInt(this.data[i].aprobado) /
                parseInt(this.data[i].total_demanda_anual)) *
              100;
          }
        }
      }
      if (flag === false) {
        this.add = false;
      } else {
        this.add = true;
      }
    },
    async procesarDemanda() {
      var flag = true;
      for (var i = 0; i < this.data.length; i++) {
        if (this.data[i].aprobado === '') {
          flag = false;
        }
      }
      if (flag === false) {
        warningMessage('El campo Total aprobado no debe estar vacío');
      } else {
        this.setLoading(true);
        const data = {
          datosGenerales: this.form_data,
          data: this.data,
        };
        await this.procesarDemandaEspecialista(data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    openModal(data, accion) {
      this.accion = accion;
      if (accion === 'adicionar') {
        this.columnss.push(
          {
            name: 'nombre',
            required: true,
            label: 'Productos',
            align: 'left',
            field: (row) => row.nombre,
            format: (val) => `${val}`,
          },
          {
            name: 'unidad_medida',
            align: 'center',
            label: 'Unidad Medida',
            field: 'unidad_medida',
            search: true,
          },
          {
            name: 'enero',
            align: 'center',
            label: 'ENERO',
            search: true,
            field: 'enero',
          },
          {
            name: 'febrero',
            align: 'center',
            label: 'FEBRERO',
            search: true,
            field: 'febrero',
          },
          {
            name: 'marzo',
            align: 'center',
            label: 'MARZO',
            field: 'marzo',
            search: true,
          },
          {
            name: 'abril',
            align: 'center',
            label: 'ABRIL',
            field: 'abril',
            search: true,
          },
          {
            name: 'mayo',
            align: 'center',
            label: 'MAYO',
            field: 'mayo',
            search: true,
          },
          {
            name: 'junio',
            align: 'center',
            label: 'JUNIO',
            field: 'junio',
            search: true,
          },
          {
            name: 'julio',
            align: 'center',
            label: 'JULIO',
            field: 'julio',
            search: true,
          },
          {
            name: 'agosto',
            align: 'center',
            label: 'AGOSTO',
            field: 'agosto',
            search: true,
          },
          {
            name: 'septiembre',
            align: 'center',
            label: 'SEPTIEMBRE',
            field: 'septiembre',
            search: true,
          },
          {
            name: 'octubre',
            align: 'center',
            label: 'OCTUBRE',
            field: 'octubre',
            search: true,
          },
          {
            name: 'noviembre',
            align: 'center',
            label: 'NOVIEMBRE',
            field: 'noviembre',
            search: true,
          },
          {
            name: 'diciembre',
            align: 'center',
            label: 'DICIEMBRE',
            field: 'diciembre',
            search: true,
          },
          {
            name: 'total_demanda_anual',
            label: 'Total Demanda Anual',
            align: 'center',
            field: 'total_demanda_anual',
            search: true,
          }
        );
        this.modalDialog = data;
      } else {
        if (accion === 'actualizar') {
          this.mostrarFamilia = false;
          if (this.selected.length > 0) {
            this.columnss.push(
              {
                name: 'nombre',
                required: true,
                label: 'Producto',
                align: 'left',
                field: (row) => row.nombre,
                format: (val) => `${val}`,
              },
              {
                name: 'familia',
                required: true,
                label: 'Familia',
                align: 'left',
                field: (row) => row.nombre_familia,
                format: (val) => `${val}`,
              },
              {
                name: 'unidad_medida',
                align: 'center',
                label: 'Unidad Medida',
                field: 'unidad_medida',
                search: true,
              },
              {
                name: 'enero',
                align: 'center',
                label: 'ENERO',
                search: true,
                field: 'enero',
              },
              {
                name: 'febrero',
                align: 'center',
                label: 'FEBRERO',
                search: true,
                field: 'febrero',
              },
              {
                name: 'marzo',
                align: 'center',
                label: 'MARZO',
                field: 'marzo',
                search: true,
              },
              {
                name: 'abril',
                align: 'center',
                label: 'ABRIL',
                field: 'abril',
                search: true,
              },
              {
                name: 'mayo',
                align: 'center',
                label: 'MAYO',
                field: 'mayo',
                search: true,
              },
              {
                name: 'junio',
                align: 'center',
                label: 'JUNIO',
                field: 'junio',
                search: true,
              },
              {
                name: 'julio',
                align: 'center',
                label: 'JULIO',
                field: 'julio',
                search: true,
              },
              {
                name: 'agosto',
                align: 'center',
                label: 'AGOSTO',
                field: 'agosto',
                search: true,
              },
              {
                name: 'septiembre',
                align: 'center',
                label: 'SEPTIEMBRE',
                field: 'septiembre',
                search: true,
              },
              {
                name: 'octubre',
                align: 'center',
                label: 'OCTUBRE',
                field: 'octubre',
                search: true,
              },
              {
                name: 'noviembre',
                align: 'center',
                label: 'NOVIEMBRE',
                field: 'noviembre',
                search: true,
              },
              {
                name: 'diciembre',
                align: 'center',
                label: 'DICIEMBRE',
                field: 'diciembre',
                search: true,
              },
              {
                name: 'total_demanda_anual',
                label: 'Total Demanda Anual',
                align: 'center',
                field: 'total_demanda_anual',
                search: true,
              }
            );
            this.setFormData(this.items_editar);
            this.modalDialog = data;
          } else {
            infoMessage('Debe seleccionar una fila para modificar.');
          }
        }
      }
    },
    aprobarDemanda(data) {
      this.modalDialogAprobar = true;
      this.add = false;
      if (this.selected.length > 0) {
        this.columnss.push(
          {
            name: 'nombre',
            required: true,
            label: 'Producto',
            align: 'left',
            field: (row) => row.nombre,
            format: (val) => `${val}`,
          },
          {
            name: 'familia',
            required: true,
            label: 'Familia',
            align: 'left',
            field: (row) => row.nombre_familia,
            format: (val) => `${val}`,
          },
          {
            name: 'unidad_medida',
            align: 'center',
            label: 'Unidad Medida',
            field: 'unidad_medida',
            search: true,
          },
          {
            name: 'total_demanda_anual',
            label: 'Total Demanda Anual',
            align: 'center',
            field: 'total_demanda_anual',
            search: true,
          },
          {
            name: 'aprobado',
            label: 'Total Aprobado',
            align: 'center',
            field: 'aprobado',
            search: true,
          },
          {
            name: 'deficit',
            label: 'Déficit',
            align: 'center',
            field: 'deficit',
            search: true,
          },
          {
            name: 'porciento_aprobado',
            label: '% Aprobado',
            align: 'center',
            field: 'porciento_aprobado',
            search: true,
          }
        );
        this.setFormDataAprobar(this.items_editar);
        this.modalDialog = data;
      } else {
        infoMessage('Debe seleccionar una fila para modificar.');
      }
    },
    setFormData(data) {
      this.form_data.anno = data[0].anno;
      this.form_data.instalacion_id = data[0].instalacion_id;
      this.form_data.familia_id = data[0].familia_id;
      for (var i = 0; i < data.length; i++) {
        this.data.push({
          nombre: data[i].producto.nombre,
          familia: data[i].familia.nombre_familia,
          unidad_medida: data[i].unidad_medida,
          enero: data[i].enero,
          febrero: data[i].febrero,
          marzo: data[i].marzo,
          abril: data[i].abril,
          mayo: data[i].mayo,
          junio: data[i].junio,
          julio: data[i].julio,
          agosto: data[i].agosto,
          septiembre: data[i].septiembre,
          octubre: data[i].octubre,
          noviembre: data[i].noviembre,
          diciembre: data[i].diciembre,
          total_demanda_anual: data[i].total_demanda_anual,
        });
      }
    },
    setFormDataAprobar(data) {
      this.form_data.anno = data[0].anno;
      this.form_data.instalacion_id = data[0].instalacion_id;
      this.form_data.familia_id = data[0].familia_id;
      for (var i = 0; i < data.length; i++) {
        this.data.push({
          nombre: data[i].producto.nombre,
          familia: data[i].familia.nombre_familia,
          unidad_medida: data[i].unidad_medida,
          enero: data[i].enero,
          febrero: data[i].febrero,
          marzo: data[i].marzo,
          abril: data[i].abril,
          mayo: data[i].mayo,
          junio: data[i].junio,
          julio: data[i].julio,
          agosto: data[i].agosto,
          septiembre: data[i].septiembre,
          octubre: data[i].octubre,
          noviembre: data[i].noviembre,
          diciembre: data[i].diciembre,
          total_demanda_anual: data[i].total_demanda_anual,
          aprobado: data[i].aprobado,
          deficit: data[i].deficit,
          porciento_aprobado: data[i].porciento_aprobado,
        });
      }
    },
    reset_form() {
      this.form_data.anno = '';
      this.form_data.instalacion_id = '';
      this.form_data.familia_id = '';
      this.form_data.mes_id = '';
      this.form_data.producto_id = '';
      this.form_data.total_demanda_anual = '';
      this.form_data.porciento_aprobado = '';
      this.form_data.deficit = '';
      this.form_data.aprobado = '';
      this.form_data.unidad_medida = '';
      this.form_error = false;
      this.val = false;
      this.required_error = false;
      this.permiso = true;
      this.add = true;
      this.$v.form_data.$reset();
    },
    async loadData(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getDemanda(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    async save() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos inválidos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        const data = {
          datosGenerales: this.form_data,
          data: this.data,
        };
        await this.saveDemanda(data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    async update() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos inválidos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        const data = {
          datosGenerales: this.form_data,
          data: this.data,
        };
        await this.editDemanda(data);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    deleteDemandas() {
      if (this.selected.length > 0) {
        this.setFormData(this.selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          const data = {
            datosGenerales: this.selected,
          };
          await this.deleteDemanda(data);
          this.reset_form();
          this.selected = [];
          await this.loadData({
            pagination: this.pagination,
            filter: this.filter,
          });
          this.setLoading(false);
        });
      } else {
        infoMessage('Debe seleccionar la fila a eliminar.');
      }
    },
    sumar() {
      for (var i = 0; i < this.data.length; i++) {
        if (this.data.length > 0) {
          this.data[i].total_demanda_anual =
            parseInt(this.data[i].enero) +
            parseInt(this.data[i].febrero) +
            parseInt(this.data[i].marzo) +
            parseInt(this.data[i].abril) +
            parseInt(this.data[i].mayo) +
            parseInt(this.data[i].junio) +
            parseInt(this.data[i].julio) +
            parseInt(this.data[i].agosto) +
            parseInt(this.data[i].septiembre) +
            parseInt(this.data[i].octubre) +
            parseInt(this.data[i].noviembre) +
            parseInt(this.data[i].diciembre);
        }
      }
    },
    actualizarTabla(data) {
      this.dataDeficit = [];
      for (var i = 0; i < data.length; i++) {
        if (data.length > 0) {
          this.dataDeficit.push({
            nombre: data[i].producto.nombre,
            familia: data[i].familia.nombre_familia,
            anno: data[i].anno,
            deficit: data[i].deficit,
            porciento_aprobado: data[i].porciento_aprobado,
          });
        }
      }
    },
    mostrar() {
      for (var i = 0; i < this.options.length; i++) {
        if (this.group === 'anno') {
          this.annoFinal = false;
          this.annoInicial = true;
          this.instalacion = true;
          this.annoFin = '';
          this.reset();
        } else if (this.group === 'ran_anno') {
          this.annoFinal = true;
          this.annoInicial = true;
          this.instalacion = true;
          this.reset();
        }
      }
    },
    reset() {
      this.form_data.instalacion_id = '';
      this.annoFin = '';
      this.annoInicio = '';
    },
    checkValue(reason) {
      if (reason === 'year') {
        this.$refs.yearPickerini.hide();
        this.$refs.yearPicker.hide();
      }
    },
  },
};
</script>

<style scoped></style>
