import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listTipo_brote(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipobrote', data)
    .then((result) => {
      const data = result.data
      context.commit('setTipo_broteList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_brote(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipobrote', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_brote(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipobrote', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_brote', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_brote(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipobrote/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_brote', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_brote(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipobrote/' + id)
    .then(() => {
      context.commit('deleteTipo_brote', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
