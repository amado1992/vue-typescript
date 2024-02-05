<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          table-header-class="text-uppercase"
          :data="compra"
          :columns="columns"
          row-key="id"
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
              Gestionar compras
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
              :disable="compra.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_flujo_informativo')"
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
                  flat
                  key="see"
                  dense
                  size="sm"
                  text-color="primary"
                  icon="remove_red_eye"
                  @click.prevent="openModalSee(true, 'see', props.row)"
                >
                  <q-tooltip>Ver detalles</q-tooltip>
                </q-btn>
                <q-btn
                  v-if="scopes.includes('gestionar_flujo_informativo')"
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
                  v-if="scopes.includes('gestionar_flujo_informativo')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteCompras(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
          <template v-slot:body-cell-fecha_cierre="props">
            <q-td rops="props" align="left">
              {{ new Date(props.value).toLocaleDateString() }}
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
    >
      <q-card style="width: 600px; max-width: 70vw;">
        <q-toolbar class="bg-primary text-white">
          <q-toolbar-title>
            {{
              this.accion == 'adicionar'
                ? 'Adicionar compra'
                : 'Actualizar compra'
            }}
          </q-toolbar-title>
          <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
            <q-tooltip>Cerrar</q-tooltip>
          </q-btn>
        </q-toolbar>
        <q-card-section class="q-px-md">
          <div class="row">
            <div class="col-6">
              <q-input
                v-model="form_data.fecha_cierre"
                label="Fecha *"
                name="fecha_cierre"
                mask="####/##/##"
                navigation-min-year-month="min"
                navigation-max-year-month="max"
                :error-message="mensajesError('fecha_cierre')"
                :error="$v.form_data.fecha_cierre.$error"
              >
                <template>
                  <q-popup-proxy
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-date
                      v-model="form_data.fecha_cierre"
                      mask="YYYY-MM-DD"
                      minimal
                    >
                      <div class="row items-center justify-end">
                        <q-btn
                          v-close-popup
                          label="Guardar"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </template>
              </q-input>
            </div>
            <div class="col-6 q-pl-sm">
              <q-select
                v-model="form_data.proveedor_id"
                emit-value
                map-options
                options-dense
                :options="items_proveedor"
                label="Proveedor *"
                option-label="nombre"
                option-value="id"
                :error-message="mensajesError('proveedor_id')"
                :error="$v.form_data.proveedor_id.$error"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-select
                v-model="form_data.producto_id"
                emit-value
                map-options
                options-dense
                :options="items_producto"
                label="Producto *"
                option-label="nombre"
                option-value="id"
                :error-message="mensajesError('producto_id')"
                :error="$v.form_data.producto_id.$error"
              />
            </div>
            <div class="col-6 q-pl-sm">
              <q-select
                v-model="form_data.unidadmedida_id"
                emit-value
                map-options
                options-dense
                :options="items_unidad"
                label="Unidad de medida *"
                option-label="nombre"
                option-value="id"
                :error-message="mensajesError('unidadmedida_id')"
                :error="$v.form_data.unidadmedida_id.$error"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-input
                v-model="form_data.precio"
                label="Precio *"
                name="precio"
                type="number"
                prefix="$"
                suffix="CUP"
                min="0"
                :error-message="mensajesError('precio')"
                :error="$v.form_data.precio.$error"
              />
            </div>
            <div class="col-6 q-pl-sm">
              <q-input
                v-model="form_data.inventario"
                label="Inventario *"
                name="inventario"
                type="number"
                :error-message="mensajesError('inventario')"
                :error="$v.form_data.inventario.$error"
                min="0"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <q-input
                v-model="form_data.cantidad_real"
                label="Cantidad real *"
                name="cantidad_real"
                type="number"
                :error-message="mensajesError('cantidad_real')"
                :error="$v.form_data.cantidad_real.$error"
                min="0"
              />
            </div>
            <div class="col-6 q-pl-sm">
              <q-input
                v-model="form_data.cantidad_plan"
                label="Cantidad plan *"
                name="cantidad_plan"
                type="number"
                :error-message="mensajesError('cantidad_plan')"
                :error="$v.form_data.cantidad_plan.$error"
                min="0"
              />
            </div>
          </div>
          <div class="text-red" v-show="form_error">
            Faltan campos por completar
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="save"
            @reset="reset_form"
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
      v-model="modalDialogSee"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title> Consultar detalles de la compra</q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <div class="q-pl-md q-pb-md">
            <div class="column" style="height: 120px">
              <div class="col">Fecha de cierre: {{ new Date(form_data.fecha_cierre).toLocaleDateString() }}</div>
              <div class="col">Osde: {{ osde }}</div>
              <div class="col">Instalación: {{ instalacion }}</div>
              <div class="col">Provincia: {{ provincia }}</div>
            </div>
            <div class="column" style="height: 120px">
              <div class="col">Familia del producto: {{ familia_producto }}</div>
              <div class="col">Producto: {{ producto }}</div>
              <div class="col">Código: {{ codigo }}</div>
              <div class="col">Proveedor: {{ proveedor }}</div>
              <div class="col">Unidad de medida: {{ unidad_medida }}</div>
            </div>
            <div class="column" style="height: 120px">
              <div class="col">Precio: {{ form_data.precio }}</div>
              <div class="col">Plan: {{ form_data.cantidad_plan }}</div>
              <div class="col">Real: {{ form_data.cantidad_real }}</div>
              <div class="col">Inventario: {{ form_data.inventario }}</div>
            </div>
          </div>

        </q-card-section>
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="check"
            @click="closeModal()"
            label="Aceptar"
            v-close-popup
            color="primary"
            flat
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import {mapState, mapGetters, mapActions} from 'vuex';
import {showDialog} from '../../../../resources/js/utils/dialogo';
import {
  errorMessage,
  infoMessage,
  warningMessage,
} from '../../../../resources/js/utils/notificaciones';
import * as message from '../../../../resources/js/utils/resources';
import {required, numeric, maxValue, minValue} from 'vuelidate/lib/validators';
import {date} from "quasar";


export default {
  name: 'compras',
  data() {
    return {
      visibleColumns: [
        'fecha_cierre',
        'producto_id',
        'unidadmedida_id',
        'precio',
        'proveedor_id',
        'total_compras',
        'action',
      ],
      modalDialogSee: false,
      modalDialog: false,
      filterInput: false,
      permiso: true,
      // val: false,
      selected: [],
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
      codigo: "",
      osde: "",
      instalacion: "",
      provincia: "",
      proveedor: "",
      producto: "",
      familia_producto: "",
      unidad_medida: "",
      items_producto: [],
      items_unidad: [],
      items_proveedor: [],
      form_data: {
        fecha_cierre: '',
        producto_id: '',
        unidadmedida_id: '',
        proveedor_id: '',
        precio: '',
        cantidad_plan: '',
        cantidad_real: '',
        inventario: '',
        total_compras: '',
        compras_nacionales: '',
      },
      form_error: false,
      required_error: false,
      columns: [
        {
          name: 'fecha_cierre',
          label: 'Fecha',
          align: 'left',
          field: 'fecha_cierre',
          search: true,
        },
        {
          name: 'producto_id',
          align: 'left',
          label: 'Producto',
          field: (row) =>
            row.productos !== undefined ? row.productos.nombre : '',
        },
        {
          name: 'unidadmedida_id',
          align: 'left',
          label: 'Unidad de medida',
          field: (row) =>
            row.unidades !== undefined ? row.unidades.nombre : '',
        },
        {
          name: 'proveedor_id',
          align: 'left',
          label: 'Proveedor',
          field: (row) => row.proveedores !== undefined ? row.proveedores.nombre : '',
        },
        {
          name: 'precio',
          label: 'Precio',
          align: 'left',
          field: 'precio',
          search: true,
        },
        {
          name: 'total_compras',
          label: 'Total de compras',
          align: 'left',
          field: 'total_compras',
          search: true,
        },
        {
          name: 'action',
          align: 'center',
          label: 'acciones',
          field: 'action',
        }
      ],
    };
  },
  validations: {
    form_data: {
      fecha_cierre: {
        required
      },
      producto_id: {
        required,
      },
      unidadmedida_id: {
        required
      },
      proveedor_id: {
        required,
      },
      precio: {
        required
      },
      cantidad_plan: {
        required,
        numeric,
      },
      cantidad_real: {
        required,
        numeric,
      },
      inventario: {
        required,
        numeric,
      }
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      {label: 'Dirección de Logística'},
      {label: 'Flujo informativo'},
      {label: 'Gestión de compras'},
    ]);
    this.getProductos().then((r) => {
      this.items_producto = r;
    });
    this.getProveedores().then((r) => {
      this.items_proveedor = r;
    });
    this.getUnidad_medidas().then((r) => {
      this.items_unidad = r;
    });
  },
  watch: {
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
    ...mapState('compra', ['compra']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('producto', ['getProductos']),
    ...mapActions('unidad_medida', ['getUnidad_medidas']),
    ...mapActions('proveedor', ['getProveedores']),
    ...mapActions('compra', [
      'getCompra',
      'saveCompra',
      'editCompra',
      'deleteCompra',
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
      if (campo === 'fecha_cierre') {
        if (!this.$v.form_data.fecha_cierre.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.fecha_cierre.maxValue) {
          this.required_error = true;
          return 'ab';
        }
      }
      if (campo === 'producto_id') {
        if (!this.$v.form_data.producto_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'proveedor_id') {
        if (!this.$v.form_data.proveedor_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'unidadmedida_id') {
        if (!this.$v.form_data.unidadmedida_id.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'precio') {
        if (!this.$v.form_data.precio.required) {
          this.required_error = true;
          return '';
        }
      }
      if (campo === 'cantidad_plan') {
        if (!this.$v.form_data.cantidad_plan.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.cantidad_plan.numeric) {
          this.required_error = false;
          return 'Solo se aceptan números enteros';
        }
      }
      if (campo === 'cantidad_real') {
        if (!this.$v.form_data.cantidad_real.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.cantidad_real.numeric) {
          this.required_error = false;
          return 'Solo se aceptan números enteros';
        }
      }
      if (campo === 'inventario') {
        if (!this.$v.form_data.inventario.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.inventario.numeric) {
          this.required_error = false;
          return 'Solo se aceptan números enteros';
        }
      }
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
      this.reset_form();
      this.selected = [];
    },

    openModal(data, accion, rowData) {
      this.accion = accion;
      if (accion === 'adicionar') {
        this.modalDialog = data;
      } else {
        if (accion === 'actualizar') {
          if (rowData) {
            this.setFormData(rowData);
            this.modalDialog = data;
          } else {
            infoMessage('Debe seleccionar una fila para modificar.');
          }
        }
      }
    },
    openModalSee(data, action, selected) {
      this.action = action;
      if (action === "see") {
        if (selected) {
          this.setFormData(selected);
          this.modalDialogSee = data;
        } else {
          errorMessage("Debe seleccionar la fila a modificar.");
        }
      }
    },
    setFormData(data) {
      this.codigo = data.productos.codigo
      this.osde = data.instalaciones.osdes.nombre
      this.instalacion = data.instalaciones.nombre
      this.provincia = data.instalaciones.provincia.nombre
      this.producto = data.productos.nombre
      this.familia_producto = data.productos.familia.nombre_familia
      this.proveedor = data.proveedores.nombre
      this.unidad_medida = data.unidades.nombre
      this.form_data = Object.assign(this.form_data, data);
    },
    reset_form() {
      this.form_data.fecha_cierre = '';
      this.form_data.instalacion_id = '';
      this.form_data.producto_id = '';
      this.form_data.proveedor_id = '';
      this.form_data.unidadmedida_id = '';
      this.form_data.precio = '';
      this.form_data.cantidad_plan = '';
      this.form_data.cantidad_real = '';
      this.form_data.inventario = '';
      this.form_data.total_compras = '';
      this.form_data.total_compras_n = '';
      this.form_error = false;
      this.val = false;
      this.required_error = false;
      this.permiso = true;
      this.add = true;
      this.$v.form_data.$reset();
    },
    async loadData(props) {
      const {page, rowsPerPage, sortBy, descending} = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getCompra(data).then((r) => {
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
        await this.saveCompra(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({pagination: this.pagination, filter: this.filter});
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
        await this.editCompra(this.form_data);
        this.reset_form();
        this.closeModal();
        this.loadData({pagination: this.pagination, filter: this.filter});
        this.setLoading(false);
      }
    },
    deleteCompras(selected) {
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteCompra(this.form_data.id);
          this.reset_form();
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
  },
};
</script>

<style scoped></style>
