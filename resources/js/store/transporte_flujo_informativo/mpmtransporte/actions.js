import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getMpmtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/list_mpmtransporte', data)
    .then((result) => {
      const data = result.data
      context.commit('setMpmtransporteList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getMpmtransportes(context, data) {
  return Vue.prototype.$axios.post('/api/get_mpmtransporte', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveMpmtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/create_mpmtransporte', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addMpmtransporte', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editMpmtransporte(context, data) {
  return Vue.prototype.$axios.put('/api/update_mpmtransporte/' + data.id, data)
    .then((result) => {
      context.commit('updateMpmtransporte', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteMpmtransporte(context, id) {
  return Vue.prototype.$axios.post('/api/delete_mpmtransporte/' + id)
    .then(() => {
      context.commit('deleteMpmtransporte', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
