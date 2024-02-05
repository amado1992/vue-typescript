import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listTipo_caso(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipocaso', data)
    .then((result) => {
      const data = result.data
      context.commit('setTipo_casoList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipocasos(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipocaso', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_caso(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipocaso', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_caso', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_caso(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipocaso/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_caso', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_caso(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipocaso/' + id)
    .then(() => {
      context.commit('deleteTipo_caso', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
