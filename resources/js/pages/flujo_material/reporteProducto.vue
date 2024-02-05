<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Lista de productos por familia
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <!-- tabla-->
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white"
          :data="this.productosFamilia"
          :columns="columns"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          :visible-columns="visibleColumns"
          flat
          dense
          row-key="name"
          :filter="filter"
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col-4">
                <q-select
                  label="Familia"
                  v-model="form_data.familia_id"
                  input-debounce="0"
                  :options="items_familia"
                  @input="loadData"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  dense
                  outlined
                  :error-message="mensajesError('familia_id')"
                  :error="$v.form_data.familia_id.$error"
                  option-label="nombre_familia"
                  option-value="id"
                >
                </q-select>
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

export default {
  name: 'ReporteProducto',
  data() {
    return {
      visibleColumns: ['nombre', 'cantidad'],
      modalDialog: false,
      permiso: true,
      val: false,
      selected: [],
      roles: [],
      items_familia: [],
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
        nombre: '',
        familia_id: '',
        cantidad: '',
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
          name: 'nombre',
          label: 'Nombre',
          align: 'center',
          field: 'nombre',
          search: true,
        },
        {
          name: 'cantidad',
          label: 'Cantidad',
          align: 'center',
          field: 'cantidad',
          search: true,
        },
      ],
    };
  },
  validations: {
    form_data: {
      nombre: {
        required,
        maxLength: maxLength(30),
      },
      cantidad: {
        required,
        numeric,
        minValue: minValue(1),
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
      { label: 'Productos por familia' },
    ]);
    this.getFamilias().then((r) => {
      this.items_familia = r;
    });
  },
  watch: {},
  computed: {
    ...mapState('producto', ['producto']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('familia', ['getFamilias']),
    ...mapActions('producto', ['getProductoFamilia']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'nombre') {
        if (!this.$v.form_data.nombre.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.nombre.maxLength) {
          this.required_error = false;
          return 'El nombre solo puede tener 30 carácteres';
        }
      }
      if (campo === 'familia_id') {
        if (!this.$v.form_data.familia_id.required) {
          this.required_error = true;
          return '';
        }
      }

      if (campo === 'cantidad') {
        if (!this.$v.form_data.cantidad.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.cantidad.numeric) {
          this.required_error = false;
          return 'Solo se aceptan números enteros';
        }
        if (!this.$v.form_data.cantidad.minValue) {
          this.required_error = false;
          return 'La cantidad debe ser mayor que cero';
        }
      }
    },
    typeSelection() {
      return this.scopes.includes('update_producto') ||
        this.scopes.includes('delete_producto')
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
      this.reset_form();
      this.selected = [];
    },
    onRowClick(evt, row) {
      if (this.selected.length === 0) {
        this.selected.push(row);
      } else if (
        this.selected.length === 1 &&
        this.selected[0].nombre !== row.nombre
      ) {
        this.selected = [];
        this.selected.push(row);
      } else if (
        this.selected.length === 1 &&
        this.selected[0].nombre === row.nombre
      ) {
        this.selected = [];
      }
    },
    openModal(data, accion) {
      this.accion = accion;
      if (accion === 'adicionar') {
        this.modalDialog = data;
      } else {
        if (accion === 'actualizar') {
          if (this.selected.length > 0) {
            this.setFormData(this.selected);
            this.modalDialog = data;
          } else {
            infoMessage('Debe seleccionar una fila para modificar.');
          }
        }
      }
    },
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data[0]);
    },
    reset_form() {
      this.form_data.nombre = '';
      this.form_data.descripcion = '';
      this.form_data.familia_id = '';
      this.form_data.cantidad = '';
      this.form_error = false;
      this.val = false;
      this.required_error = false;
      this.permiso = true;
      this.add = true;
      this.$v.form_data.$reset();
    },
    async loadData() {
      this.setLoading(true);
      const data = {
        id: this.form_data.familia_id,
      };
      await this.getProductoFamilia(data).then((r) => {
        this.productosFamilia = r.data;
        this.setLoading(false);
      });
    },
  },
};
</script>

<style scoped></style>
