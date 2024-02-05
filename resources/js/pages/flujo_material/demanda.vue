<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          table-header-class="text-uppercase"
          :data="demanda"
          :columns="columns"
          row-key="id"
          :selection="this.typeSelection()"
          :selected.sync="selected"
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          @request="loadData"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          :visible-columns="visibleColumns"
          flat
          dense
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar demanda
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
              :disable="demanda.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_flujo_material')"
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
          </template>
          <template v-slot:body-cell-action="props">
            <q-td :props="props">
              <div class="q-gutter-sm">
                <q-btn
                  v-if="scopes.includes('gestionar_flujo_material')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="mdi-check"
                  @click.prevent="aprobarDemanda(props.row)"
                >
                  <q-tooltip>Aprobar demanda</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_flujo_material')"
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="edit"
                  @click.prevent="openModal(true, 'actualizar', props.row)"
                >
                  <q-tooltip>Editar datos</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_flujo_material')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteProductos(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
        </q-table>
      </q-card-section>
    </q-card>

    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
      maximized
    >
      <q-card>
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              {{
                this.accion == 'adicionar'
                  ? 'Adicionar demanda'
                  : 'Actualizar demanda'
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <div class="row justify-around q-px-sm">
            <div class="col-3">
              <q-input
                v-model="form_data.anno"
                type="text"
                label="Año *"
                dense
                name="anno"
                :error-message="mensajesError('anno')"
                :error="$v.form_data.anno.$error"
                debounce="1000"
                autogrow
              />
            </div>

            <div class="col-3">
              <q-select
                v-model="form_data.familia_id"
                hide-bottom-space
                emit-value
                map-options
                options-dense
                dense
                :options="items_familia"
                label="Familia Producto *"
                option-label="nombre_familia"
                option-value="id"
                @input="cargarProductos"
                v-show="mostrarFamilia"
                :error-message="mensajesError('familia_id')"
                :error="$v.form_data.familia_id.$error"
              />
            </div>
          </div>
          <div class="row justify-center q-pa-md">
            <q-table
              table-header-class="text-uppercase"
              flat
              bordered
              title="Demanda"
              :data="data"
              :columns="columnss"
              row-key="name"
              binary-state-sort
              style="max-width: 95vw;"
            >
              <template v-slot:body="props">
                <q-tr :props="props">
                  <q-td key="nombre" :props="props">
                    {{ props.row.nombre }}
                  </q-td>
                  <q-td key="familia" :props="props">
                    {{ props.row.familia }}
                  </q-td>
                  <q-td key="unidad_medida" :props="props">
                    {{ props.row.unidad_medida }}
                    <q-popup-edit
                      v-model="props.row.unidad_medida"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="string"
                        v-model="props.row.unidad_medida"
                        dense
                        autofocus
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="enero" :props="props">
                    {{ props.row.enero }}
                    <q-popup-edit
                      v-model="props.row.enero"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.enero"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="febrero" :props="props">
                    {{ props.row.febrero }}
                    <q-popup-edit
                      v-model="props.row.febrero"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.febrero"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="marzo" :props="props">
                    {{ props.row.marzo }}
                    <q-popup-edit
                      v-model="props.row.marzo"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.marzo"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="abril" :props="props">
                    {{ props.row.abril }}
                    <q-popup-edit
                      v-model="props.row.abril"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.abril"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="mayo" :props="props">
                    {{ props.row.mayo }}
                    <q-popup-edit
                      v-model="props.row.mayo"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.mayo"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="junio" :props="props">
                    {{ props.row.junio }}
                    <q-popup-edit
                      v-model="props.row.junio"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.junio"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="julio" :props="props">
                    {{ props.row.julio }}
                    <q-popup-edit
                      v-model="props.row.julio"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.julio"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="agosto" :props="props">
                    {{ props.row.agosto }}
                    <q-popup-edit
                      v-model="props.row.agosto"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.agosto"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="septiembre" :props="props">
                    {{ props.row.septiembre }}
                    <q-popup-edit
                      v-model="props.row.septiembre"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.septiembre"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="octubre" :props="props">
                    {{ props.row.octubre }}
                    <q-popup-edit
                      v-model="props.row.octubre"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.octubre"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="noviembre" :props="props">
                    {{ props.row.noviembre }}
                    <q-popup-edit
                      v-model="props.row.noviembre"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.noviembre"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="diciembre" :props="props">
                    {{ props.row.diciembre }}
                    <q-popup-edit
                      v-model="props.row.diciembre"
                      title="Actualizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.diciembre"
                        dense
                        autofocus
                        @input="sumar"
                      />
                    </q-popup-edit>
                  </q-td>

                  <q-td key="total_demanda_anual" :props="props"
                    >{{ props.row.total_demanda_anual }}
                  </q-td>
                </q-tr>
              </template>
            </q-table>
          </div>
          <div class="text-red" v-show="form_error">
            Faltan campos por completar
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="save"
            @click="accion != 'adicionar' ? update() : save()"
            label="Guardar"
            color="primary"
            flat
            :loading="this.getterLoading()"
            v-show="add"
          />
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
      </q-card>
    </q-dialog>
    <q-dialog
      v-model="modalDialogAprobar"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 700px; max-width: 100vw;">
        <q-card-section class="bg-primary">
          <div class="text-h6 text-white">
            Aprobar demanda
          </div>
        </q-card-section>
        <q-card-section class="q-pa-lg">
          <div class="row">
            <div class="col-3">
              <q-input
                v-model="form_data.anno"
                type="text"
                label="Año"
                dense
                name="anno"
                readonly
                :error-message="mensajesError('anno')"
                :error="$v.form_data.anno.$error"
                debounce="1000"
                autogrow
              />
            </div>
            <div class="col-3 q-px-sm">
              <q-select
                v-model="form_data.instalacion_id"
                hide-bottom-space
                emit-value
                map-options
                options-dense
                dense
                readonly
                :options="items_instalacion"
                label="Instalación"
                option-label="nombre"
                option-value="id"
                :error-message="mensajesError('instalacion_id')"
                :error="$v.form_data.instalacion_id.$error"
              />
            </div>
          </div>
          <div class="row q-pa-md">
            <q-table
              title="Demanda"
              :data="data"
              :columns="columnss"
              row-key="name"
              binary-state-sort
              flat
              dense
            >
              <template v-slot:body="props">
                <q-tr :props="props">
                  <q-td key="nombre" :props="props">
                    {{ props.row.nombre }}
                  </q-td>
                  <q-td key="familia" :props="props">
                    {{ props.row.familia }}
                  </q-td>
                  <q-td key="unidad_medida" :props="props">
                    {{ props.row.unidad_medida }}
                  </q-td>
                  <q-td key="total_demanda_anual" :props="props"
                    >{{ props.row.total_demanda_anual }}
                  </q-td>
                  <q-td key="aprobado" :props="props">
                    {{ props.row.aprobado }}
                    <q-popup-edit
                      v-model="props.row.aprobado"
                      title="Actulizar"
                      buttons
                    >
                      <q-input
                        type="number"
                        v-model="props.row.aprobado"
                        dense
                        @input="calcularDeficit"
                        autofocus
                      />
                    </q-popup-edit>
                  </q-td>
                  <q-td key="deficit" :props="props"
                    >{{ props.row.deficit }}
                  </q-td>
                  <q-td key="porciento_aprobado" :props="props"
                    >{{ props.row.porciento_aprobado }}
                  </q-td>
                </q-tr>
              </template>
            </q-table>
          </div>
          <div class="text-red" v-show="form_error">
            Faltan campos por completar
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="settings"
            @click="procesarDemanda()"
            label="Aprobar"
            color="primary"
            flat
            :loading="this.getterLoading()"
            v-show="add"
          />
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
      </q-card>
    </q-dialog>
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
import { required, maxLength } from 'vuelidate/lib/validators';

export default {
  name: 'demanda',
  data() {
    return {
      visibleColumns: ['instalacion', 'anno', 'action'],
      modalDialog: false,
      filterInput: false,
      modalDialogAprobar: false,
      permiso: true,
      mostrarFamilia: true,
      val: false,
      selected: [],
      roles: [],
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
      options: [],
      permissions: [],
      form_data: {
        instalacion_id: sessionStorage.getItem('instalacion_id'),
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
      columns: [
        {
          name: 'id',
          label: 'Id',
          align: 'center',
          field: (row) => row.id,
        },
        {
          name: 'instalacion',
          align: 'center',
          label: 'Instalación',
          field: (row) =>
            row.instalacion !== undefined ? row.instalacion.nombre : '',
        },
        {
          name: 'anno',
          label: 'Año',
          align: 'center',
          field: 'anno',
          search: true,
        },
        {
          name: 'action',
          align: 'center',
          label: 'acciones',
          field: 'action',
        },
      ],
      columnss: [],
      data: [],
    };
  },
  validations: {
    form_data: {
      anno: {
        required,
        maxLength: maxLength(4),
      },
      familia_id: {
        required,
      },
      instalacion_id: {
        required,
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Logística' },
      { label: 'Flujo material' },
      { label: 'Demanda' },
    ]);
    this.getFamilias().then((r) => {
      this.items_familia = r;
    });
    this.getMeses().then((r) => {
      this.items_mes = r;
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
    ...mapActions('producto', ['getProductoFamilia']),
    ...mapActions('demanda', [
      'getDemanda',
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
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
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
      if (campo === 'familia_id') {
        if (!this.$v.form_data.familia_id.required) {
          this.required_error = true;
          return '';
        }
      }
    },
    typeSelection() {
      return this.scopes.includes('gestionar_demanda') ? 'single' : 'none';
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
    openModal(data, accion, rowData) {
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
          if (rowData) {
            this.selected.push(rowData);
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
    aprobarDemanda(rowData) {
      this.modalDialogAprobar = true;
      this.add = false;
      if (rowData) {
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
        this.modalDialog = rowData;
      } else {
        infoMessage('Debe seleccionar una fila para modificar.');
      }
    },
    setFormData(data) {
      this.form_data.anno = data[0] && data[0].anno;
      this.form_data.instalacion_id = data[0] && data[0].instalacion_id;
      this.form_data.familia_id = data[0] && data[0].familia_id;
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
        infoMessage(message.inputInvalid);
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
    async update(selected) {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage(message.inputInvalid);
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
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          const data = {
            datosGenerales: selected,
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
      this.data = [];
      for (var i = 0; i < data.length; i++) {
        if (data.length > 0) {
          this.data.push({
            nombre: data[i].nombre,
            enero: 0,
            febrero: 0,
            marzo: 0,
            abril: 0,
            mayo: 0,
            junio: 0,
            julio: 0,
            agosto: 0,
            septiembre: 0,
            octubre: 0,
            noviembre: 0,
            diciembre: 0,
            total_demanda_anual: 0,
          });
        }
      }
    },
  },
};
</script>

<style scoped></style>
