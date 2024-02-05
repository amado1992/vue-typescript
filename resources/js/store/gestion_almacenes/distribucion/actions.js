import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getDistribucion(context, data) {
  return Vue.prototype.$axios.post('/api/list_distribucion', data)
    .then((result) => {
      const data = result
      context.commit('setDistribucionList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getDistribuciones(context, data) {
  return Vue.prototype.$axios.post('/api/get_distribuciones', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveDistribucion(context, data) {
  return Vue.prototype.$axios.post('/api/create_distribucion', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addDistribucion', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editDistribucion(context, data) {
  return Vue.prototype.$axios.put('/api/update_distribucion/' + data.id, data)
    .then((result) => {
      context.commit('updateDistribucion', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteDistribucion(context, id) {
  return Vue.prototype.$axios.post('/api/delete_distribucion/' + id)
    .then(() => {
      context.commit('deleteDistribucion', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
