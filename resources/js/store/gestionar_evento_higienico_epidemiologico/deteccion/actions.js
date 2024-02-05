import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listDeteccion(context, data) {
  return Vue.prototype.$axios.post('/api/list_deteccion', data)
    .then((result) => {
      const data = result
      context.commit('setDeteccionList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getDetecciones(context, data) {
  return Vue.prototype.$axios.post('/api/get_deteccion', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveDeteccion(context, data) {
  return Vue.prototype.$axios.post('/api/create_deteccion', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addDeteccion', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editDeteccion(context, data) {
  return Vue.prototype.$axios.put('/api/update_deteccion/' + data.id, data)
    .then((result) => {
      context.commit('updateDeteccion', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteDeteccion(context, id) {
  return Vue.prototype.$axios.post('/api/delete_deteccion/' + id)
    .then(() => {
      context.commit('deleteDeteccion', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
