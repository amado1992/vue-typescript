import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listTipo_muestra(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipo_muestra', data)
    .then((result) => {
      const data = result
      context.commit('setTipo_muestraList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_muestra(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipo_muestra', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_muestra(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipo_muestra', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_muestra', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_muestra(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipo_muestra/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_muestra', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_muestra(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipo_muestra/' + id)
    .then(() => {
      context.commit('deleteTipo_muestra', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
