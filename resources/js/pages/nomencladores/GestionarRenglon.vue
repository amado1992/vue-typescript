<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
        :grid="xssmallScreen || smallScreen"
          flat
          dense
          table-header-class="text-uppercase"
          :data="renglon"
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
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar renglones
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
              :disable="renglon.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('nomencladores')"
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
                  v-if="scopes.includes('nomencladores')"
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
                  v-if="scopes.includes('nomencladores')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRenglones(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
           <template v-slot:item="props">
                            <q-card class="q-ma-xs">
                                <q-card-section>
                              <q-btn
                  v-if="scopes.includes('nomencladores')"
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
                  v-if="scopes.includes('nomencladores')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteRenglones(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
                                </q-card-section>
                                <q-separator></q-separator>
                                <q-list dense>
                                    <q-item :key="col.id"


                                            v-for="col in props.cols"
                                           >
                                        <q-item-section>
                                            <q-item-label  v-if="col.field != 'action'">{{ col.label }}</q-item-label>
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-item-label caption>{{ col.value }}</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card>
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
      <q-card style="width: 500px; max-width: 70vw;">
        <q-form
          @reset="reset_form"
          @submit="accion != 'adicionar' ? update() : save()"
          ref="formRenglon"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding">
              <q-toolbar class="bg-primary text-white">
                <q-toolbar-title>
                  {{
                    this.accion == 'adicionar'
                      ? 'Adicionar renglón'
                      : 'Actualizar renglón'
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
              <div class="row q-pa-sm">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <q-input
                    v-model="form_data.nombre"
                    type="text"
                    label="Nombre *"
                    dense
                    name="nombre"
                    debounce="1000"
                    autogrow
                    :error-message="mensajesError('nombre')"
                    :error="$v.form_data.nombre.$error"
                    :rules="[val => !!val || 'El campo es requerido', isValidAlphaNum]"
                  />
                </div>
              </div>
            </q-card-section>
            <q-card-actions align="right">
              <q-btn
                type="submit"
                key="guardar"
                label="Guardar"
                flat
                color="primary"
                icon="save"
                v-show="add"
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
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../utils/dialogo';
import { infoMessage, errorMessage, warningMessage } from '../../utils/notificaciones';
import * as message from '../../utils/resources';
import SmallScreen from "../../mixin/SmallScreen";
import { required } from 'vuelidate/lib/validators';
export default {
  name: 'GestionarRenglon',
  mixins:[SmallScreen],
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      modalDialog: false,
      filterInput: false,
      loading: false,
      filter: '',
      selected: [],
      val: false,
      add: true,
      accion: '',
      form_data: {
        nombre: '',
      },
      form_error: false,
      required_error: false,
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
        {
          name: 'nombre',
          align: 'left',
          label: 'nombre',
          field: 'nombre',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'action',
          align: 'center',
          label: 'acciones',
          field: 'action',
          headerClasses: 'text-uppercase',
        },
      ],
    };
  },
  validations: {
    form_data: {
      nombre: {
        required
      },
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Configuraciones' },
      { label: 'Nomencladores' },
      { label: 'Renglones' },
    ]);
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
    ...mapState('renglon', ['renglon']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('renglon', [
      'getRenglon',
      'saveRenglon',
      'editRenglon',
      'deleteRenglon',
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
      }
    },
    isValidAlphaNum (val) {
      const emailPattern = /^[a-zA-Z0-9 ]*$/;
      return emailPattern.test(val) || 'No se aceptan caracteres especiales';
    },
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    reset_form() {
      this.form_data.nombre = '';
      this.form_error = false;
      this.required_error = false;
      this.$v.form_data.$reset();
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
      this.selected = [];
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
      await this.getRenglon(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    filterFn(val, update) {
      update(() => {
        const needle = val.toLowerCase();
        this.options = this.permissions.filter(
          (v) => v.name.toLowerCase().indexOf(needle) > -1
        );
      });
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
    async save() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage('Revise los campos requeridos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.saveRenglon(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
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
        await this.editRenglon(this.form_data).then(result => {
          if (result.error == 500) {
            warningMessage(result.message);
          }else{
            this.reset_form();
            this.closeModal();
          }
        })
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
        this.setLoading(false);
      }
    },
    deleteRenglones(selected) {
      if (selected) {
        this.setFormData(selected);
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteRenglon(this.form_data.id);
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
  },
};
</script>

<style scoped></style>
