import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getEncargado(context, data) {
  return Vue.prototype.$axios.post('/api/list_encargado_almacen', data)
    .then((result) => {
      const data = result
      context.commit('setEncargadoList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveEncargado(context, data) {
  return Vue.prototype.$axios.post('/api/create_encargado_almacen', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addEncargado', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editEncargado(context, data) {
  return Vue.prototype.$axios.put('/api/update_encargado_almacen/' + data.id, data)
    .then((result) => {
      context.commit('updateEncargado', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteEncargado(context, id) {
  return Vue.prototype.$axios.post('/api/delete_encargado_almacen/' + id)
    .then(() => {
      context.commit('deleteEncargado', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}

