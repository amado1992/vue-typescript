import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getActividad_almacen(context, data) {
  return Vue.prototype.$axios.post('/api/list_actividad_almacen', data)
    .then((result) => {
      const data = result
      context.commit('setActividad_almacenList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getActividad_almacenes(context, data) {
  return Vue.prototype.$axios.post('/api/get_actividad_almacenes', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveActividad_almacen(context, data) {
  return Vue.prototype.$axios.post('/api/create_actividad_almacen', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addActividad_almacen', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editActividad_almacen(context, data) {
  return Vue.prototype.$axios.put('/api/update_actividad_almacen/' + data.id, data)
    .then((result) => {
      context.commit('updateActividad_almacen', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteActividad_almacen(context, id) {
  return Vue.prototype.$axios.post('/api/delete_actividad_almacen/' + id)
    .then(() => {
      context.commit('deleteActividad_almacen', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
