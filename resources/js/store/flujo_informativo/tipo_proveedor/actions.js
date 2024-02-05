import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTipo_proveedor(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipo_proveedor', data)
    .then((result) => {
      const data = result
      context.commit('setTipo_proveedorList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_proveedores(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipo_proveedores', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_proveedor(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipo_proveedor', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_proveedor', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_proveedor(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipo_proveedor/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_proveedor', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_proveedor(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipo_proveedor/' + id)
    .then(() => {
      context.commit('deleteTipo_proveedor', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
