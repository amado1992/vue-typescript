<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Colectivos líderes de calidad
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white text-center text-uppercase"
          :data="this.colectivosEstados"
          :columns="columns"
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          row-key="name"
          :filter="filter"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          :visible-columns="visibleColumns"
          flat
          dense
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col-12">
                <q-select
                  outlined
                  id="disp"
                  label="Estados"
                  v-model="estadoColectivo"
                  @input="loadData"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  dense
                  input-debounce="0"
                  :options="optionsEstado"
                  option-label="nombre"
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
import { required, maxLength } from 'vuelidate/lib/validators';

export default {
  name: 'colectivoEstado',
  data() {
    return {
      visibleColumns: [
        'sector',
        'provincia',
        'osde',
        'instalacion',
        'argumentacion',
      ],
      modalDialog: false,
      modalDialogEstado: false,
      permiso: true,
      val: false,
      selected: [],
      roles: [],
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
      estadoColectivo: '',
      permissions: [],
      items_sector: [],
      items_provincia: [],
      items_osde: [],
      items_instalacion: [],
      colectivosEstados: [],
      form_data: {
        sector_id: '',
        provincia_id: '',
        osde_id: '',
        instalacion_id: '',
        argumentacion: '',
        estado: '',
      },
      optionsEstado: ['Aceptada', 'Rechazada'],
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
          name: 'sector',
          align: 'center',
          label: 'Sector',
          field: (row) => (row.sector !== undefined ? row.sector.nombre : ''),
        },
        {
          name: 'provincia',
          align: 'center',
          label: 'Provincia',
          field: (row) =>
            row.provincia !== undefined ? row.provincia.nombre : '',
        },
        {
          name: 'osde',
          align: 'center',
          label: 'Osde',
          field: (row) => (row.osde !== undefined ? row.osde.nombre : ''),
        },
        {
          name: 'instalacion',
          align: 'center',
          label: 'Instalación',
          field: (row) =>
            row.instalacion !== undefined ? row.instalacion.nombre : '',
        },
        {
          name: 'argumentacion',
          label: 'Argumentación',
          align: 'center',
          field: 'argumentacion',
          search: true,
        },
        {
          name: 'estado',
          align: 'center',
          label: 'Estado',
          field: 'estado',
        },
      ],
    };
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: 'Dirección de Calidad' },
      { label: 'Movimientos líderes de calidad' },
      { label: 'Reportes' },
      { label: 'Listado colectivos líderes ' },
    ]);
    this.getOsdes().then((r) => {
      this.items_osde = r;
    });
    this.getProvincias().then((r) => {
      this.items_provincia = r;
    });
    this.getSectores().then((r) => {
      this.items_sector = r;
    });
    this.getInstalaciones().then((r) => {
      this.items_instalacion = r;
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
    ...mapState('colectivo', ['colectivo']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('sector', ['getSectores']),
    ...mapActions('provincia', ['getProvincias']),
    ...mapActions('osde', ['getOsdes']),
    ...mapActions('instalacion', ['getInstalaciones']),
    ...mapActions('colectivo', [
      'getColectivo',
      'saveColectivo',
      'editColectivo',
      'deleteColectivo',
      'getColectivoEstado',
    ]),
    ...mapActions('utils', ['setLoading']),
    ...mapActions('breadcrumbs', ['addToHomeBreadcrumbs']),
    closeModal() {
      this.modalDialog = false;
      this.modalDialogEstado = false;
      this.reset_form();
      this.selected = [];
    },
    setFormData(data) {
      this.form_data = Object.assign(this.form_data, data[0]);
    },
    reset_form() {
      this.form_data.estado = '';
      this.estadoColectivo = '';
      this.colectivosEstados = [];
    },
    async loadData() {
      this.setLoading(true);
      const data = {
        estado: this.estadoColectivo,
      };
      await this.getColectivoEstado(data).then((r) => {
        this.colectivosEstados = r;
        this.setLoading(false);
      });
    },
  },
};
</script>

<style scoped></style>
