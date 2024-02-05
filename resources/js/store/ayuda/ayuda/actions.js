import Vue from 'vue'
import { errorMessage, successMessage } from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export async function getAyuda (context, data) {
  return Vue.prototype.$axios.post('/api/list_ayudas', data)
    .then((result) => {
      const data = result.data
      context.commit('setAyudaList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
export async function getAyudaId (context, data) {
  return Vue.prototype.$axios.post('/api/show_ayudas', data)
    .then((result) => {
      const data = result.data
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function saveAyuda (context, data) {
  return Vue.prototype.$axios.post('/api/ayudas', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addAyuda', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      errorMessage(message.eCreateRow, err)
    })
}

export async function editAyuda (context, data) {
  return Vue.prototype.$axios.put('/api/ayudas/' + data.id, data)
    .then((result) => {
      context.commit('updateAyuda', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      errorMessage(message.eUpdateRow, err)
    })
}

export async function deleteAyuda (context, id) {
  return Vue.prototype.$axios.delete('/api/ayudas/' + id)
    .then(() => {
      context.commit('deleteAyuda', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
