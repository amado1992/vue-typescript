import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listOnit(context, data) {
  return Vue.prototype.$axios.post('/api/list_onit', data)
    .then((result) => {
      const data = result.data
      context.commit('setOnitList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getOnit(context, data) {
  return Vue.prototype.$axios.post('/api/get_onit', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveOnit(context, data) {
  return Vue.prototype.$axios.post('/api/create_onit', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addOnit', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editOnit(context, data) {
  return Vue.prototype.$axios.put('/api/update_onit/' + data.id, data)
    .then((result) => {
      context.commit('updateOnit', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteOnit(context, id) {
  return Vue.prototype.$axios.post('/api/delete_onit/' + id)
    .then(() => {
      context.commit('deleteOnit', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
