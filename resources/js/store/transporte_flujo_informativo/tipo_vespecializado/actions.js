import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTipo_vespecializado(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipo_vespecializados', data)
    .then((result) => {
      const data = result.data
      context.commit('setTipo_vespecializadoList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_vespecializados(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipo_vespecializados', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_vespecializado(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipo_vespecializados', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_vespecializado', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_vespecializado(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipo_vespecializados/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_vespecializado', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_vespecializado(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipo_vespecializados/' + id)
    .then(() => {
      context.commit('deleteTipo_vespecializado', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
