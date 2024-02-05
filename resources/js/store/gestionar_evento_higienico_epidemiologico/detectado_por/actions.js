import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listDetectado_por(context, data) {
  return Vue.prototype.$axios.post('/api/list_detectadopor', data)
    .then((result) => {
      const data = result.data
      context.commit('setDetectado_porList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getDetectado_por(context, data) {
  return Vue.prototype.$axios.post('/api/get_detectadopor', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveDetectado_por(context, data) {
  return Vue.prototype.$axios.post('/api/create_detectadopor', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addDetectado_por', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editDetectado_por(context, data) {
  return Vue.prototype.$axios.put('/api/update_detectadopor/' + data.id, data)
    .then((result) => {
      context.commit('updateDetectado_por', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteDetectado_por(context, id) {
  return Vue.prototype.$axios.post('/api/delete_detectadopor/' + id)
    .then(() => {
      context.commit('deleteDetectado_por', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
