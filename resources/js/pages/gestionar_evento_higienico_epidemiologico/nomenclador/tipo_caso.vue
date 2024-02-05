<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          :data="tipo_caso"
          :visible-columns="visibleColumns"
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
          :hide-pagination="false"
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar tipo de caso
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
              :disable="tipo_caso.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
            <q-btn
              v-if="scopes.includes('gestionar_dir_higienico_epidemiologico')"
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
              <div class="q-gutter-xs">
                <q-btn
                  v-if="scopes.includes('gestionar_dir_higienico_epidemiologico')"
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
                  v-if="scopes.includes('gestionar_dir_higienico_epidemiologico')"
                  flat
                  dense
                  size="sm"
                  text-color="negative"
                  icon="delete"
                  @click.prevent="deleteDetecciones(props.row)"
                >
                  <q-tooltip>Eliminar datos</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>
    <!-- Dialog gestionar -->
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
                  ? 'Adicionar tipo de caso'
                  : 'Actualizar tipo de caso'
              }}
            </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        <q-card-section class="q-px-md">
          <div class="row">
            <div class="col-12">
              <q-input
                v-model="form_data.tipo_caso"
                type="text"
                label="Detección *"
                dense
                name="deteccion"
                :error-message="mensajesError('tipo_caso')"
                :error="$v.form_data.tipo_caso.$error"
                debounce="1000"
                autogrow
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
    <!-- End Dialog gestionar -->
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { showDialog } from '../../../../js/utils/dialogo';
import { infoMessage, warningMessage } from '../../../../js/utils/notificaciones';
import * as message from '../../../../js/utils/resources';
import { required, alpha, maxLength } from 'vuelidate/lib/validators';

export default {
  name: 'tipo_caso',
  data() {
    return {
      scopes: sessionStorage.getItem('scopes'),
      add: true,
      form_data: {
        tipo_caso: ''
      },
      modalDialog: false,
      filterInput: false,
      filter: '',
      accion: '',
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      form_error: false,
      required_error: false,
      visibleColumns: [
        'codigo',
        'tipo_caso',
        'action'
      ],
      columns: [
        {
          name: 'codigo',
          align: 'left',
          label: 'Código',
          field: 'codigo',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'tipo_caso',
          align: 'left',
          label: 'Tipo de caso',
          field: 'tipo_caso',
          headerClasses: 'text-uppercase',
        },
        {
          name: 'action',
          align: 'center',
          label: 'acciones',
          field: 'action',
          headerClasses: 'text-uppercase',
        }
      ],
    };
  },
  validations: {
    form_data: {
      tipo_caso: {
        required, alpha
      }
    },
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Evento Higiénico-Epidemiológico' },
      { label: 'Nomencladores' },
      { label: 'Tipo de caso' },
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
    ...mapState('tipo_caso', ['tipo_caso']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('tipo_caso', ['listTipo_caso', 'saveTipo_caso', 'editTipo_caso', 'deleteTipo_caso']),
    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'tipo_caso') {
        if (!this.$v.form_data.tipo_caso.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.tipo_caso.alpha) {
          this.required_error = true;
          return 'Solo se aceptan caracteres alfabéticos';
        }
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
    reset_form() {
      this.form_data.tipo_caso = '';
      this.form_error = false;
      this.required_error = false;
      this.$v.form_data.$reset();
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data);
    },
    openModal(data, accion, rowData) {
      this.accion = accion;
      switch (accion) {
        case 'adicionar':
          this.modalDialog = data;
          break;
        case 'actualizar':
          if (rowData) {
            this.setFormData(rowData);
            this.modalDialog = data;
          } else {
            infoMessage('Debe seleccionar una fila para modificar.');
          }
          break;
        default:
          break;
      }
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
      await this.listTipo_caso(data).then((r) => {
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
        infoMessage('Revise los campos requeridos');
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.setLoading(true);
        await this.saveTipo_caso(this.form_data).then(result => {
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
        await this.editTipo_caso(this.form_data).then(result => {
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
    deleteTipo_casos(selected) {
      if (selected) {
        showDialog(message.lAskForRemove).onOk(async () => {
          this.setLoading(true);
          await this.deleteTipo_caso(selected.id);
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

<style></style>
