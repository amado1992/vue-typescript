import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getEtmtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/list_etmtransporte', data)
    .then((result) => {
      const data = result.data
      context.commit('setEtmtransporteList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getEtmtransportes(context, data) {
  return Vue.prototype.$axios.post('/api/get_etmtransporte', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveEtmtransporte(context, data) {
  return Vue.prototype.$axios.post('/api/create_etmtransporte', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addEtmtransporte', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editEtmtransporte(context, data) {
  return Vue.prototype.$axios.put('/api/update_etmtransporte/' + data.id, data)
    .then((result) => {
      context.commit('updateEtmtransporte', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteEtmtransporte(context, id) {
  return Vue.prototype.$axios.post('/api/delete_etmtransporte/' + id)
    .then(() => {
      context.commit('deleteEtmtransporte', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
