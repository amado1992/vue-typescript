import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTipo_flota(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipo_flota', data)
    .then((result) => {
      const data = result.data
      context.commit('setTipo_flotaList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_flotas(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipo_flotas', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_flota(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipo_flota', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_flota', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_flota(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipo_flota/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_flota', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_flota(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipo_flota/' + id)
    .then(() => {
      context.commit('deleteTipo_flota', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
