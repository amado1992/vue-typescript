import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listOrigen_brote(context, data) {
  return Vue.prototype.$axios.post('/api/list_origenbrote', data)
    .then((result) => {
      const data = result.data
      context.commit('setOrigen_broteList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getOrigen_brote(context, data) {
  return Vue.prototype.$axios.post('/api/get_origenbrote', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveOrigen_brote(context, data) {
  return Vue.prototype.$axios.post('/api/create_origenbrote', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addOrigen_brote', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editOrigen_brote(context, data) {
  return Vue.prototype.$axios.put('/api/update_origenbrote/' + data.id, data)
    .then((result) => {
      context.commit('updateOrigen_brote', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteOrigen_brote(context, id) {
  return Vue.prototype.$axios.post('/api/delete_origenbrote/' + id)
    .then(() => {
      context.commit('deleteOrigen_brote', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
