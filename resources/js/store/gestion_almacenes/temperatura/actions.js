import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTemperatura(context, data) {
  return Vue.prototype.$axios.post('/api/list_temperatura', data)
    .then((result) => {
      const data = result
      context.commit('setTemperaturaList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTemperaturas(context, data) {
  return Vue.prototype.$axios.post('/api/get_temperaturas', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTemperatura(context, data) {
  return Vue.prototype.$axios.post('/api/create_temperatura', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTemperatura', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTemperatura(context, data) {
  return Vue.prototype.$axios.put('/api/update_temperatura/' + data.id, data)
    .then((result) => {
      context.commit('updateTemperatura', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTemperatura(context, id) {
  return Vue.prototype.$axios.post('/api/delete_temperatura/' + id)
    .then(() => {
      context.commit('deleteTemperatura', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
