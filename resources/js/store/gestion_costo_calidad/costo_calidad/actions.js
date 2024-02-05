import Vue from 'vue';
import { errorMessage, successMessage } from '../../../utils/notificaciones';
import * as message from '../../../utils/resources';

export function getConformidadList(context, data) {
  return Vue.prototype.$axios
    .get('/api/costo_calidad_conformidades', data)
    .then((result) => {
      const data = result.data.data.original;
      context.commit('setConformidadList', data);
      return data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}

export function getNoConformidadList(context, data) {
  return Vue.prototype.$axios
    .get('/api/costo_calidad_noconformidades', data)
    .then((result) => {
      const data = result.data.data.original;
      context.commit('setNoConformidadList', data);
      return data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}

export function saveConformidad(context, data) {
  return Vue.prototype.$axios
    .post('/api/costo_calidad_conformidad', data)
    .then((result) => {
      const datar = result.data.data;
      if (data.tipo === 'Calidad No Conformidad') {
        context.commit('addNoConformidad', DataTransferItem);
      } else {
        context.commit('addConformidad', DataTransferItem);
      }
      successMessage(message.sCreateRow);
      return datar;
    })
    .catch((err) => {
      errorMessage(message.eCreateRow, err);
    });
}
export function deleteConformidad(context, data) {
  return Vue.prototype.$axios
    .delete(`/api/costo_calidad_conformidad/${data.id}`, { data: data })
    .then(() => {
      if (data.tipo === 'Calidad Conformidad') {
        context.commit('deleteConformidad', data.id);
      } else {
        context.commit('deleteNoConformidad', data.id);
      }
      successMessage(message.sRemoveOk);
      return data.id;
    })
    .catch((err) => {
      errorMessage(message.eRemoveError, err);
    });
}
export function editConformidad(context, data) {
  return Vue.prototype.$axios
    //.put(`/api/costo_calidad_conformidad/${data.id}`, { data: data })
    .put(`/api/costo_calidad_conformidad/${data.id}`, data)
    .then((result) => {
      if (data.tipo === 'Calidad Conformidad') {
        context.commit('updateConformidad', result.data.data);
      } else {
        context.commit('updateNoConformidad', result.data.data);
      }
      successMessage(message.sUpdateRow);
      return result.data.data;
    })
    .catch((err) => {
      errorMessage(message.eUpdateRow, err);
    });
}

//He cambiado aquí return result.data.data.data antes return result.data.data
export function getDataCalidadReporte() {
  return Vue.prototype.$axios
    .get('/api/costo_calidad_reports')
    .then((result) => {
      return result.data.data.data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}
//He cambiado aquí return result.data.data.data antes return result.data.data
export function getDataConformidad() {
  return Vue.prototype.$axios
    .get('/api/costos_conformidades')
    .then((result) => {
      return result.data.data.data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}
//He cambiado aquí return result.data.data.data antes return result.data.data
export function getDataNoConformidad() {
  return Vue.prototype.$axios
    .get('/api/costos_noconformidades')
    .then((result) => {
      return result.data.data.data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}

export function loadDataYear_report(data) {
  return Vue.prototype.$axios
    .post('/api/entidades_all_costos_trimestre', data)
    .then((response) => {
      return response.data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}
export function loadDataLastYear_report(data) {
  return Vue.prototype.$axios
    .post('/api/entidades_all_costos_trimestre', data)
    .then((response) => {
      return response.data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}
export function getDataEntiyAllCost_report() {
  return Vue.prototype.$axios
    .get('/api/entidades_all_costos')
    .then((response) => {
      return response.data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}
export function getYearEntiyAllCostTotalPage_report(data) {
  return Vue.prototype.$axios
    .post('/api/entidades_all_costos_total', data)
    .then((response) => {
      return response.data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}
export function getLastYearEntiyAllCostTotalPage_report(data) {
  return Vue.prototype.$axios
    .post('/api/entidades_all_costos_total', data)
    .then((response) => {
      return response.data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}
export function getDataEntityAllIndicator_report() {
  return Vue.prototype.$axios
    .get('/api/entidades_all_indicadores')
    .then((response) => {
      return response.data.data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}
