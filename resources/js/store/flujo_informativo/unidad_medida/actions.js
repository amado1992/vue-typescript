import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getUnidad_medida(context, data) {
  return Vue.prototype.$axios.post('/api/list_unidad_medida', data)
    .then((result) => {
      const data = result.data
      context.commit('setUnidad_medidaList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getUnidad_medidas(context, data) {
  return Vue.prototype.$axios.post('/api/get_unidad_medidas', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveUnidad_medida(context, data) {
  return Vue.prototype.$axios.post('/api/create_unidad_medida', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addUnidad_medida', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editUnidad_medida(context, data) {
  return Vue.prototype.$axios.put('/api/update_unidad_medida/' + data.id, data)
    .then((result) => {
      context.commit('updateUnidad_medida', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteUnidad_medida(context, id) {
  return Vue.prototype.$axios.post('/api/delete_unidad_medida/' + id)
    .then(() => {
      context.commit('deleteUnidad_medida', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
