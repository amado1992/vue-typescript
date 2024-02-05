import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listTipo_foco(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipo_foco', data)
    .then((result) => {
      const data = result
      context.commit('setTipo_focoList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_foco(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipo_foco', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_foco(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipo_foco', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_foco', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_foco(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipo_foco/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_foco', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_foco(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipo_foco/' + id)
    .then(() => {
      context.commit('deleteTipo_foco', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
