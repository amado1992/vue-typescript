<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <div class="row">
          <div class="col-6 text-h6 text-uppercase">
            Propuestas líderes de calidad
          </div>
          <q-space></q-space>
        </div>
      </q-card-section>
      <q-separator inset></q-separator>
      <q-card-section>
        <q-table
          table-header-class="bg-primary text-white text-center text-uppercase"
          :data="this.propuestasEstados"
          :columns="columns"
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          loading-label="Cargando elementos"
          :rows-per-page-options="[5, 10, 25, 50]"
          :filter="filter"
          binary-state-sort
          no-data-label="No se encontraron elementos a mostrar"
          :visible-columns="visibleColumns"
          dense
          flat
        >
          <template v-slot:top>
            <div class="full-width row justify-between">
              <div class="col-6">
                <q-select
                  v-model="form_data.sector_id"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  dense
                  outlined
                  :options="items_sector"
                  label="Sector"
                  option-label="nombre"
                  option-value="id"
                  @input="listarEventos()"
                />
              </div>
              <div class="col-6 q-pl-sm">
                <q-select
                  v-model="form_data.osde_id"
                  hide-bottom-space
                  emit-value
                  map-options
                  options-dense
                  dense
                  outlined
                  :options="items_osde"
                  label="Osde"
                  option-label="nombre"
                  option-value="id"
                  @input="listarEventos()"
                />
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
import {
  successMessage,
  warningMessage,
} from '../../../../resources/js/utils/notificaciones';

export default {
  name: 'filtradoPropuestaEstado',
  data() {
    return {
      sectorShow: false,
      osdeShow: false,
      visibleColumns: [
        'nombre',
        'apellido',
        'cargo',
        'sector',
        'provincia',
        'osde',
        'instalacion',
        'estado',
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
      propuestasEstados: [],
      form_data: {
        nombre: '',
        apellido: '',
        cargo: '',
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
          name: 'nombre',
          label: 'Nombre',
          align: 'center',
          field: 'nombre',
          search: true,
        },
        {
          name: 'apellido',
          label: 'Apellidos',
          align: 'center',
          field: 'apellido',
          search: true,
        },
        {
          name: 'cargo',
          label: 'Cargo',
          align: 'center',
          field: 'cargo',
          search: true,
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
      { label: 'Listado propuestas líderes ' },
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
    ...mapState('propuesta', ['propuesta']),
  },
  methods: {
    ...mapGetters('utils', ['getterLoading']),
    ...mapActions('usuario', ['getTotalUsuariosRol']),
    ...mapActions('sector', ['getSectores']),
    ...mapActions('provincia', ['getProvincias']),
    ...mapActions('osde', ['getOsdes']),
    ...mapActions('instalacion', ['getInstalaciones']),
    ...mapActions('propuesta', [
      'getPropuesta',
      'savePropuesta',
      'editPropuesta',
      'deletePropuesta',
      'getPropuestaEstado',
      'getListaRelacionesPropuesta',
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
      this.propuestasEstados = [];
    },
    async loadData() {
      this.setLoading(true);
      const data = {
        estado: this.estadoColectivo,
      };
      await this.getPropuestaEstado(data).then((r) => {
        this.propuestasEstados = r;
        this.setLoading(false);
      });
    },
    verCancel() {
      if (this.form_data.sector_id !== '') {
        this.sectorShow = true;
      }
      if (this.form_data.osde_id !== '') {
        this.osdeShow = true;
      }
    },
    eliminarSector() {
      this.form_data.sector_id = '';
      this.sectorShow = false;
    },
    eliminarOsde() {
      this.form_data.osde_id = '';
      this.osdeShow = false;
    },
    async listarEventos() {
      if (this.form_data.osde_id === '' && this.form_data.sector_id === '') {
        warningMessage('Debe escoger al menos una opción para poder buscar');
      } else {
        this.selected = [];
        this.setLoading(true);
        await this.getListaRelacionesPropuesta({
          datos: this.form_data,
        }).then((response) => {
          this.propuestasEstados = response;
          this.setLoading(false);
          if (this.propuestasEstados.length === 0) {
            warningMessage(
              'No existen solicitudes con las condiciones seleccionadas'
            );
          } else {
            successMessage('Solicitudes Encontradas');
          }
        });
        this.setLoading(false);
      }
    },
  },
};
</script>

<style scoped></style>
