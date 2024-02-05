import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getVictima_accidente(context, data) {
  return Vue.prototype.$axios.post('/api/list_victima_accidente', data)
    .then((result) => {
      const data = result.data
      context.commit('setVictima_accidenteList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getVictima_accidentes(context, data) {
  return Vue.prototype.$axios.post('/api/get_victima_accidente', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveVictima_accidente(context, data) {
  return Vue.prototype.$axios.post('/api/create_victima_accidente', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addVictima_accidente', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editVictima_accidente(context, data) {
  return Vue.prototype.$axios.put('/api/update_victima_accidente/' + data.id, data)
    .then((result) => {
      context.commit('updateVictima_accidente', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteVictima_accidente(context, id) {
  return Vue.prototype.$axios.post('/api/delete_victima_accidente/' + id)
    .then(() => {
      context.commit('deleteVictima_accidente', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
