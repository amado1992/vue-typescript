import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getProducto(context, data) {
  return Vue.prototype.$axios.post('/api/list_productos', data)
    .then((result) => {
      const data = result.data.data
      context.commit('setProductoList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getProductoFamilia(context, data) {
  return Vue.prototype.$axios.post('/api/list_productos_familia', data)
    .then((result) => {
      const data = result.data.data
      context.commit('setProductoList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getProductos(context, data) {
  return Vue.prototype.$axios.post('/api/get_productos', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveProducto(context, data) {
  return Vue.prototype.$axios.post('/api/producto', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addProducto', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editProducto(context, data) {
  return Vue.prototype.$axios.put('/api/producto/' + data.id, data)
    .then((result) => {
      context.commit('updateProducto', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteProducto(context, id) {
  return Vue.prototype.$axios.delete('/api/producto/' + id)
    .then(() => {
      context.commit('deleteProducto', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
