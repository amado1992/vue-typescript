<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          table-header-class="text-uppercase"
          :data="producto"
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
          dense
          flat
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar productos
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
              :disable="producto.length != 0 ? false : true"
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
    >
      <q-card style="width: 600px; max-width: 70vw;">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title>
              {{
                this.accion == 'adicionar'
                  ? 'Adicionar producto'
                  : 'Actualizar producto'
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section>
          <div class="row q-px-sm">
            <div class="col-12">
              <q-input
                v-model="form_data.nombre"
                type="text"
                label="Nombre *"
                name="nombre"
                :error-message="mensajesError('nombre')"
                :error="$v.form_data.nombre.$error"
                debounce="1000"
              />
            </div>
          </div>
          <div class="row q-px-sm">
            <div class="col-6">
              <q-select
                v-model="form_data.familia_id"
                hide-bottom-space
                emit-value
                map-options
                options-dense
                :options="items_familia"
                label="Familia de productos *"
                option-label="nombre_familia"
                option-value="id"
                :error-message="mensajesError('familia_id')"
                :error="$v.form_data.familia_id.$error"
              />
            </div>
            <div class="col-6 q-pl-sm">
              <q-input
                v-model="form_data.cantidad"
                label="Cantidad *"
                name="cantidad"
                type="number"
                :error-message="mensajesError('cantidad')"
                :error="$v.form_data.cantidad.$error"
                min="0"
              />
            </div>
          </div>
          <div class="text-red" v-show="form_error">
            Faltan campos por llenar
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
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../../../resources/js/utils/dialogo';
import {infoMessage, warningMessage} from '../../../../resources/js/utils/notificaciones';
import * as message from '../../../../resources/js/utils/resources';
import {required, alpha, maxLength, numeric, minValue} from 'vuelidate/lib/validators';

export default {
  name: 'familia',
  data() {
    return {
      visibleColumns: [
        'nombre',
        'cantidad',
        'familia_id',
        'descripcion',
        'action',
      ],
      modalDialog: false,
      filterInput: false,
      permiso: true,
      val: false,
      selected: [],
      roles: [],
      items_familia: [],
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
          align: 'left',
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
        {
          name: 'familia_id',
          align: 'left',
          label: 'Familia',
          field: (row) =>
            row.familia !== undefined ? row.familia.nombre_familia : '',
        },
        {
          name: 'action',
          align: 'center',
          label: 'acciones',
          field: 'action',
        },
      ],
    };
  },
  validations: {
    form_data: {
      nombre: {
        required,alpha, maxLength: maxLength(30),
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
      { label: 'Productos' },
    ]);
    this.getFamilias().then((r) => {
      this.items_familia = r;
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
    ...mapState('producto', ['producto']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('familia', ['getFamilias']),
    ...mapActions('producto', [
      'getProducto',
      'saveProducto',
      'editProducto',
      'deleteProducto',
    ]),
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
        if (!this.$v.form_data.nombre.alpha) {
          this.required_error = true;
          return 'Solo se aceptan caracteres alfabéticos';
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
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data);
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
    async loadData(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: filter,
      };
      await this.getProducto(data).then((r) => {
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
        await this.saveProducto(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({ pagination: this.pagination, filter: this.filter });
        this.setLoading(false);
      }
    },
    async update() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos requeridos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.editProducto(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({pagination: this.pagination, filter: this.filter});
        this.setLoading(false);
      }
    },
    deleteProductos(selected) {
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteProducto(this.form_data.id);
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
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    // nombre() {
    //     var mio = false
    //     var mio1 = false
    //     for (var i = 0; i < this.rolesNombre.length; i++) {
    //         if (this.form_data.name === this.rolesNombre[i].name) {
    //             mio = true
    //         } else {
    //             mio1 = true
    //         }
    //     }
    //     if (mio === true && mio1 === false) {
    //         warningMessage('Ya existe un registro con dicho nombre, escriba otro')
    //         this.add = false
    //     } else {
    //         if (mio === false && mio1 === true) {
    //             this.add = true
    //         } else {
    //             if (mio === true && mio1 === true) {
    //                 warningMessage('Ya existe un registro con dicho nombre, escriba otro')
    //                 this.add = false
    //             }
    //         }
    //     }
    // }
  },
};
</script>

<style scoped></style>
