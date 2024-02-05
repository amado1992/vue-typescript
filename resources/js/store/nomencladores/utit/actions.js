import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listUtit(context, data) {
  return Vue.prototype.$axios.post('/api/list_utit', data)
    .then((result) => {
      const data = result.data
      context.commit('setUtitList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getUtit(context, data) {
  return Vue.prototype.$axios.post('/api/get_utit', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveUtit(context, data) {
  return Vue.prototype.$axios.post('/api/create_utit', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addUtit', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editUtit(context, data) {
  return Vue.prototype.$axios.put('/api/update_utit/' + data.id, data)
    .then((result) => {
      context.commit('updateUtit', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteUtit(context, id) {
  return Vue.prototype.$axios.post('/api/delete_utit/' + id)
    .then(() => {
      context.commit('deleteUtit', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
