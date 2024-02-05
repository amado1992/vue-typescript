import Vue from 'vue';
import { errorMessage, successMessage } from '../../../utils/notificaciones';
import * as message from '../../../utils/resources';

export function getCostoConformidadList(context, data) {
  return Vue.prototype.$axios
    .get('/api/costos_conformidades', data)
    .then((result) => {
      const data = result.data.data.data;
      context.commit('setCostoConformidadList', data);
      return data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}

export function getNoConformidadList(context, data) {
  return Vue.prototype.$axios
    .get('/api/costos_noconformidades', data)
    .then((result) => {
      const data = result.data.data.data;
      context.commit('setCostoNoConformidadList', data);
      return data;
    })
    .catch((err) => {
      errorMessage(message.eDataError, err);
    });
}

export function saveConformidad(context, data) {
  return Vue.prototype.$axios
    .post('/api/costo_conformidad', data)
    .then((result) => {
      const datar = result.data.data;
      if (data.tipo === 'Conformidad') {
        context.commit('addCostoConformidad', DataTransferItem);
      } else {
        context.commit('addCostoNoConformidad', DataTransferItem);
      }
      successMessage(message.sCreateRow);
      return datar;
    })
    .catch((err) => {
      errorMessage(message.eCreateRow, err);
    });
}
export function deleteCostoConformidad(context, data) {
  return Vue.prototype.$axios
    .delete(`/api/costos_conformidades/${data.id}`, { data: data })
    .then(() => {
      if (data.tipo === 'Conformidad') {
        context.commit('deleteCostoConformidad', data.id);
      } else {
        context.commit('deleteCostoNoConformidad', data.id);
      }
      successMessage(message.sRemoveOk);
      return data.id;
    })
    .catch((err) => {
      errorMessage(message.eRemoveError, err);
    });
}
export function editCostoConformidad(context, data) {
  return Vue.prototype.$axios
    .put(`/api/costos_conformidades/${data.id}`, { data: data })
    .then((result) => {
      if (data.tipo === 'Calidad Conformidad') {
        context.commit('updateCostoConformidad', result.data.data);
      } else {
        context.commit('updateCostoNoConformidad', result.data.data);
      }
      successMessage(message.sUpdateRow);
      return result.data.data;
    })
    .catch((err) => {
      errorMessage(message.eUpdateRow, err);
    });
}