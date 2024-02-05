import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getCmtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/list_cmtransporte', data)
    .then((result) => {
      const data = result.data
      context.commit('setCmtransporteList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getCmtransportes(context, data) {
  return Vue.prototype.$axios.post('/api/get_cmtransporte', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveCmtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/create_cmtransporte', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addCmtransporte', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editCmtransporte(context, data) {
  return Vue.prototype.$axios.put('/api/update_cmtransporte/' + data.id, data)
    .then((result) => {
      context.commit('updateCmtransporte', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteCmtransporte(context, id) {
  return Vue.prototype.$axios.post('/api/delete_cmtransporte/' + id)
    .then(() => {
      context.commit('deleteCmtransporte', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
