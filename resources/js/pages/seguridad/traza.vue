<template>
  <div class="q-pa-md">
    <q-card>
      <q-card-section>
        <q-table
          :grid="xssmallScreen || smallScreen"
          table-header-class="text-uppercase"
          :data="traza"
          :columns="columns"
          row-key="id"
          :pagination.sync="pagination"
          :loading="this.getterLoading()"
          :selected.sync="selected"
          loading-label="Cargando elementos"
          :rows-per-page-options="[25, 50, 75, 100]"
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
              Ver trazas
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
              :disable="traza.length != 0 ? false : true"
              :color="filterInput ? 'blue-grey-3' : 'primary'"
              icon="search"
              @click.prevent="filterStatus()"
              class="q-mr-sm q-ml-sm q-mt-sm"
            >
              <q-tooltip>Buscar</q-tooltip>
            </q-btn>
          </template>
          <template v-slot:item="props">
            <q-card class="q-ma-xs">
              <q-card-section>
                <q-btn
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="mdi-eye"
                  @click.prevent="verDetalles(props.row)"
                >
                  <q-tooltip>Mostrar detalles</q-tooltip>
                </q-btn>
              </q-card-section>
              <q-separator></q-separator>
              <q-list dense>
                <q-item :key="col.id" v-for="col in props.cols">
                  <q-item-section>
                    <q-item-label v-if="col.field != 'action'">{{
                      col.label
                    }}</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-item-label caption>{{ col.value }}</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card>
          </template>
          <template v-slot:body-cell-action="props">
            <q-td :props="props">
              <div class="q-gutter-xs">
                <q-btn
                  flat
                  dense
                  size="sm"
                  text-color="primary"
                  icon="mdi-eye"
                  @click.prevent="verDetalles(props.row)"
                >
                  <q-tooltip>Mostrar detalles</q-tooltip>
                </q-btn>
              </div>
            </q-td>
          </template>
          <template v-slot:body-cell-created_at="props">
            <q-td :props="props">
              <span>{{ moment(props.value).format("LLLL") }}</span>
            </q-td>
          </template>
        </q-table>
      </q-card-section>
    </q-card>

    <q-dialog
      v-model="modalDetalles"
      persistent
      transition-show="scale"
      transition-hide="scale"
    >
      <q-card style="width: 600px; max-width: 70vw">
        <q-card-section class="row no-padding">
          <q-toolbar class="bg-primary text-white">
            <q-toolbar-title> Detalles de la traza </q-toolbar-title>
            <q-btn dense flat icon="close" @click="closeModal" v-close-popup>
              <q-tooltip>Cerrar</q-tooltip>
            </q-btn>
          </q-toolbar>
        </q-card-section>
        
        <q-card-section>
          <!--<q-scroll-area style="height:250px; max-width: 600px;">-->
            <div class="row">
              <div class="col-12">
          {{ jsonDetails }}
            </div>
            </div>
            
          <!--</q-scroll-area>-->
        </q-card-section>
         
        <q-card-actions align="right">
          <q-btn
            type="button"
            icon="close"
            @click="closeModal()"
            label="Cerrar"
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
import { mapState, mapGetters, mapActions } from "vuex";
import moment from "moment";
import SmallScreen from "../../mixin/SmallScreen";

moment.locale("es");
export default {
  name: "traza",
  mixins: [SmallScreen],
  data() {
    return {
      jsonDetails: null,
      modalDetalles: false,
      selected: [],
      scopes: sessionStorage.getItem("scopes"),
      visibleColumns: [
        "ip",
        "accion_id",
        "user_sa_id",
        "modelo",
        "created_at",
        "action",
      ],
      filter: "",
      filterInput: false,
      pagination: {
        page: 1,
        rowsNumber: 0,
      },
      columns: [
        {
          name: "id",
          label: "Id",
          align: "left",
          field: (row) => row.id,
        },
        {
          name: "ip",
          label: "Ip",
          align: "left",
          field: "ip",
          search: true,
        },
        {
          name: "accion_id",
          align: "left",
          label: "Acción",
          field: (row) => (row.accion !== null ? row.accion.accion : ""),
        },
        {
          name: "user_sa_id",
          align: "left",
          label: "Usuario",
          field: (row) =>
            row.usuario_sa !== null ? row.usuario_sa.nombre : "",
        },
        {
          name: "modelo",
          align: "left",
          label: "Modelo",
          field: "modelo",
          search: true,
        },
        {
          name: "descripcion",
          align: "left",
          label: "Descripción",
          field: "descripcion",
          search: true,
        },
        {
          name: "created_at",
          align: "left",
          label: "Fecha de Creación",
          field: "created_at",
          search: true,
        },
        {
          name: "action",
          align: "center",
          label: "acciones",
          field: "action",
        },
      ],
    };
  },
  mounted() {
    this.addToHomeBreadcrumbs([
      { label: "Configuraciones" },
      { label: "Seguridad" },
      { label: "Trazas" },
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
    ...mapState("traza", ["traza"]),
  },
  methods: {
    moment,
    ...mapGetters("utils", ["getterLoading"]),
    ...mapActions("traza", ["getTraza"]),
    ...mapActions("utils", ["setLoading"]),
    ...mapActions("breadcrumbs", ["addToHomeBreadcrumbs"]),
    filterStatus() {
      if (this.filterInput) {
        this.filterInput = false;
        this.filter = "";
      } else {
        this.filterInput = true;
      }
    },
    closeModal() {
      this.modalDetalles = false;
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
      await this.getTraza(data).then((r) => {
        this.pagination.rowsNumber = r.total;
        this.pagination.page = page;
        this.pagination.rowsPerPage = rowsPerPage;
        this.pagination.sortBy = sortBy;
        this.pagination.descending = descending;
        this.setLoading(false);
      });
    },
    showDescripcion(value) {
      return value;
    },
    verDetalles(rowData) {
      this.jsonDetails = this.createJson(rowData);
      this.modalDetalles = true;
    },
    createJson(row) {
      return JSON.parse(row.descripcion);
    },
  },
};
</script>

<style scoped></style>
