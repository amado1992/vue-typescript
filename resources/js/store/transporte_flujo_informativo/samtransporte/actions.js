import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getSamtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/list_samtransporte', data)
    .then((result) => {
      const data = result.data
      context.commit('setSamtransporteList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getSamtransportes(context, data) {
  return Vue.prototype.$axios.post('/api/get_samtransporte', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveSamtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/create_samtransporte', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addSamtransporte', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editSamtransporte(context, data) {
  return Vue.prototype.$axios.put('/api/update_samtransporte/' + data.id, data)
    .then((result) => {
      context.commit('updateSamtransporte', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteSamtransporte(context, id) {
  return Vue.prototype.$axios.post('/api/delete_samtransporte/' + id)
    .then(() => {
      context.commit('deleteSamtransporte', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
