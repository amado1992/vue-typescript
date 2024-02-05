import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTipo_almacen(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipo_almacen', data)
    .then((result) => {
      const data = result
      context.commit('setTipo_almacenList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_almacenes(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipo_almacenes', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_almacen(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipo_almacen', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_almacen', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_almacen(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipo_almacen/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_almacen', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_almacen(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipo_almacen/' + id)
    .then(() => {
      context.commit('deleteTipo_almacen', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
