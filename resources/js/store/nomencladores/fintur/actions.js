import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listFintur(context, data) {
  return Vue.prototype.$axios.post('/api/list_fintur', data)
    .then((result) => {
      const data = result.data
      context.commit('setFinturList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getFintur(context, data) {
  return Vue.prototype.$axios.post('/api/get_fintur', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveFintur(context, data) {
  return Vue.prototype.$axios.post('/api/create_fintur', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addFintur', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editFintur(context, data) {
  return Vue.prototype.$axios.put('/api/update_fintur/' + data.id, data)
    .then((result) => {
      context.commit('updateFintur', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteFintur(context, id) {
  return Vue.prototype.$axios.post('/api/delete_fintur/' + id)
    .then(() => {
      context.commit('deleteFintur', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
