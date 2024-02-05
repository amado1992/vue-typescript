import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getFamilia(context, data) {
  return Vue.prototype.$axios.post('/api/list_familias', data)
    .then((result) => {
      const data = result.data.data
      context.commit('setFamiliaList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getFamilias(context, data) {
  return Vue.prototype.$axios.post('/api/get_familias', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveFamilia(context, data) {
  return Vue.prototype.$axios.post('/api/familia', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addFamilia', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editFamilia(context, data) {
  return Vue.prototype.$axios.put('/api/familia/' + data.id, data)
    .then((result) => {
      context.commit('updateFamilia', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteFamilia(context, id) {
  return Vue.prototype.$axios.delete('/api/familia/' + id)
    .then(() => {
      context.commit('deleteFamilia', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
