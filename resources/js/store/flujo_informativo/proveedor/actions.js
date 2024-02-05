import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getProveedor(context, data) {
  return Vue.prototype.$axios.post('/api/list_proveedor', data)
    .then((result) => {
      const data = result
      context.commit('setProveedorList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getProveedores(context, data) {
  return Vue.prototype.$axios.post('/api/get_proveedores', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveProveedor(context, data) {
  return Vue.prototype.$axios.post('/api/create_proveedor', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addProveedor', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editProveedor(context, data) {
  return Vue.prototype.$axios.put('/api/update_proveedor/' + data.id, data)
    .then((result) => {
      context.commit('updateProveedor', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteProveedor(context, id) {
  return Vue.prototype.$axios.post('/api/delete_proveedor/' + id)
    .then(() => {
      context.commit('deleteProveedor', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
