import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listOrigen_caso(context, data) {
  return Vue.prototype.$axios.post('/api/list_origencaso', data)
    .then((result) => {
      const data = result.data
      context.commit('setOrigen_casoList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getOrigen_caso(context, data) {
  return Vue.prototype.$axios.post('/api/get_origencaso', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveOrigen_caso(context, data) {
  return Vue.prototype.$axios.post('/api/create_origencaso', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addOrigen_caso', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editOrigen_caso(context, data) {
  return Vue.prototype.$axios.put('/api/update_origencaso/' + data.id, data)
    .then((result) => {
      context.commit('updateOrigen_caso', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteOrigen_caso(context, id) {
  return Vue.prototype.$axios.post('/api/delete_origencaso/' + id)
    .then(() => {
      context.commit('deleteOrigen_caso', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
