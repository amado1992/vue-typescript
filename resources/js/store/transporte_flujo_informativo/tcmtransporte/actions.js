import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTcmtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/list_tcmtransporte', data)
    .then((result) => {
      const data = result.data
      context.commit('setTcmtransporteList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTcmtransportes(context, data) {
  return Vue.prototype.$axios.post('/api/get_tcmtransporte', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTcmtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/create_tcmtransporte', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTcmtransporte', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTcmtransporte(context, data) {
  return Vue.prototype.$axios.put('/api/update_tcmtransporte/' + data.id, data)
    .then((result) => {
      context.commit('updateTcmtransporte', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTcmtransporte(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tcmtransporte/' + id)
    .then(() => {
      context.commit('deleteTcmtransporte', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
