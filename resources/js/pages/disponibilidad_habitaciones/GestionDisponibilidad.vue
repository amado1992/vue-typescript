<template>
  <div class="q-pa-md">
    <q-card>
      <!-- tabla-->
      <q-card-section>
        <q-table
          flat
          dense
          :data="data"
          :columns="columns"
          :visible-columns="visibleColumns"
          row-key="id"
          :filter="filter"
          :selected.sync="selected"
          @request="loadData"
          :pagination.sync="pagination"
          @row-click="onRowClick"
          :loading="this.getterLoading()"
          :rows-per-page-options="[10, 20, 30, 40, 50]"
          no-data-label="No se encontraron elementos a mostrar"
          no-results-label="No se encontraron elementos a mostrar"
        >
          <template v-slot:top>
            <div class="text-subtitle1 text-weight-bold text-uppercase">
              Gestionar no disponibilidad
            </div>
            <q-space></q-space>
            <q-select
              v-if="filterInput"
              id="dispo"
              class="col-3"
              autofocus
              dense
              label="Entidades"
              v-model="form_data.entidades"
              input-debounce="0"
              :options="options"
              options-dense
              option-label="nombre"
              option-value="id"
            />
            <q-btn
              key="filter"
              dense
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="mdi-filter"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Filtrar</q-tooltip>
            </q-btn>
          </template>
        </q-table>
        <!-- /tabla-->
      </q-card-section>
    </q-card>

    <q-dialog
      v-model="modalDialog"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 300px; max-width: 40vw;">
        <q-form
          @reset="reset_form"
          @submit="updateCant()"
          ref="nuevaDisponibilidad"
        >
          <q-card-section class="no-padding">
            <q-card-section class="row no-padding"> </q-card-section>
            <q-card-section>
              <div class="row q-pt-sm">
                <div class="col-xs-12">
                  <q-input
                    type="number"
                    min="0"
                    v-model="form_data.cant_habitaciones_nd"
                    dense
                    autofocus
                    sytle="width:80%"
                    :error-message="mensajesError('cant_habitaciones_nd')"
                    :error="$v.form_data.cant_habitaciones_nd.$error"
                    debounce="1000"
                  />
                </div>
              </div>
              <div class="text-red" v-show="form_error">
                Faltan campos por completar
              </div>
            </q-card-section>
          </q-card-section>
          <q-card-actions align="right">
            <q-btn
              type="submit"
              key="guardar"
              label="Guardar"
              flat
              dense
              color="primary"
              icon="save"
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
              dense
            />
          </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import SmallScreen from '../../mixin/SmallScreen';
import { required, numeric } from 'vuelidate/lib/validators';
import { infoMessage } from '../../utils/notificaciones';
import * as message from '../../utils/resources';

export default {
  name: 'GestionDisponibilidad',
  mixins: [SmallScreen],
  data() {
    return {
      modalDialog: false,
      filterInput: false,
      filter: '',
      options: [],
      loading: false,
      selected: [],
      options1: [],
      data: [],
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      clasificaciones: [],
      causas: [],
      form_data: {
        id_osde: '',
        id_clasificacion: '',
        id_causa: '',
        cant_habitaciones_nd: '',
        fecha: '',
        entidades: '',
      },
      visibleColumns: ['causa_id', 'clasificacion_id', 'cant_habitaciones_nd'],
      columns: [
        {
          name: 'entidad_id',
          align: 'left',
          label: 'Osde',
          field: (row) => row.entidades.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'causa_id',
          align: 'left',
          label: 'Causa',
          field: (row) => row.causas.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'clasificacion_id',
          align: 'left',
          label: 'Clasificación',
          field: (row) => row.clasificaciones.nombre,
          sortable: true,
          headerClasses: 'text-uppercase',
        },
        {
          name: 'cant_habitaciones_nd',
          align: 'center',
          label: 'Cant. de Habitaciones',
          field: 'cant_habitaciones_nd',
          sortable: true,
          headerClasses: 'text-uppercase',
        },
      ],
      form_error: false,
      required_error: false,
    };
  },
  validations: {
    form_data: {
      cant_habitaciones_nd: {
        required,
        numeric,
      },
    },
  },
  mounted() {
    this.loadEntidades();
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Servicios Técnicos' },
      { label: 'Disponibilidad de habitaciones' },
      { label: 'No Disponibilidad' },
    ]);
  },
  watch: {
    selected: function () {
      if (this.selected.length !== 0) {
        this.form_data.cant_habitaciones_nd = this.selected[0].cant_habitaciones_nd;
        this.modalDialog = true;
      }
    },
    'form_data.entidades': function () {
      if (this.form_data.entidades !== '')
        this.loadData({
          pagination: this.pagination,
          filter: this.filter,
        });
    },
  },
  computed: {
    ...mapGetters('disponibilidad', [
      'getterDisponibilidades',
      'getterDisponibilidadesObject',
    ]),
    ...mapGetters('entidad', ['getterEntidad']),
  },
  methods: {
    ...mapActions('disponibilidad', [
      'getAllDisponbilidades',
      'editDisponibilidad',
    ]),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    ...mapActions('usuario', ['getTotalUsuarios']),
    ...mapActions('entidad', ['getEntidad']),
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('utils', ['setLoading']),

    mensajesError(campo) {
      if (!this.$v.form_data.$invalid) {
        this.required_error = false;
        this.form_error = false;
      }
      if (campo === 'cant_habitaciones_nd') {
        if (!this.$v.form_data.cant_habitaciones_nd.required) {
          this.required_error = true;
          return '';
        }
        if (!this.$v.form_data.cant_habitaciones_nd.numeric) {
          this.required_error = false;
          return 'Solo se pueden entrar números';
        }
      }
    },

    async loadEntidades() {
      this.setLoading(true);
      await this.getEntidad(this.form_data);
      this.options = this.getterEntidad;
      this.setLoading(false);
    },

    onRowClick(evt, row) {
      if (this.selected.length === 0) {
        this.selected.push(row);
      } else if (this.selected.length === 1 && this.selected[0].id !== row.id) {
        this.selected = [];
        this.selected.push(row);
      } else if (this.selected.length === 1 && this.selected[0].id === row.id) {
        this.selected = [];
      }
    },

    async loadData(props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      const filter = props.filter;
      this.setLoading(true);
      const arraySearch = [];
      this.columns.forEach(function (item) {
        if (item.search) {
          arraySearch.push({
            name: item.field,
            value: filter,
          });
        }
      });
      const data = {
        itemsPerPage: rowsPerPage,
        page: page,
        search: arraySearch,
      };
      await this.getAllDisponbilidades(this.form_data, data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.data = r;
        this.setLoading(false);
      });
    },

    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = '';
      } else {
        this.filterInput = true;
      }
    },
    async updateCant() {
      this.$v.form_data.$touch();
      if (this.$v.form_data.$invalid) {
        infoMessage(message.inputInvalid);
        if (this.required_error) {
          this.form_error = true;
        }
      } else {
        this.selected[0].cant_habitaciones_nd = this.form_data.cant_habitaciones_nd;
        this.setLoading(true);
        await this.editDisponibilidad(this.selected[0]);
        this.reset_form();
        this.closeModal();
        this.loadData({ pagination: this.pagination, filter: this.filter });
      }
      this.setLoading(false);
    },

    reset_form() {
      this.form_data.cant_habitaciones_nd = '';
      this.selected = [];
      this.form_error = false;
      this.required_error = false;
      this.$v.form_data.$reset();
    },
    closeModal() {
      this.modalDialog = false;
      this.reset_form();
    },
  },
};
</script>

<style></style>
