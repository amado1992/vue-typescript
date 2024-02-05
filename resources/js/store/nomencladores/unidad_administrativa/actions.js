import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listUnidad_administrativa(context, data) {
  return Vue.prototype.$axios.post('/api/list_unidad_administrativa', data)
    .then((result) => {
      const data = result.data
      context.commit('setUnidad_administrativaList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getUnidad_administrativa(context, data) {
  return Vue.prototype.$axios.post('/api/get_unidad_administrativa', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveUnidad_administrativa(context, data) {
  return Vue.prototype.$axios.post('/api/create_unidad_administrativa', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addUnidad_administrativa', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editUnidad_administrativa(context, data) {
  return Vue.prototype.$axios.put('/api/update_unidad_administrativa/' + data.id, data)
    .then((result) => {
      context.commit('updateUnidad_administrativa', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteUnidad_administrativa(context, id) {
  return Vue.prototype.$axios.post('/api/delete_unidad_administrativa/' + id)
    .then(() => {
      context.commit('deleteUnidad_administrativa', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
