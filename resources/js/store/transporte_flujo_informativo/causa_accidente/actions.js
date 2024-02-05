import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getCausa_accidente(context, data) {
  return Vue.prototype.$axios.post('/api/list_causa_accidente', data)
    .then((result) => {
      const data = result.data
      context.commit('setCausa_accidenteList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getCausa_accidentes(context, data) {
  return Vue.prototype.$axios.post('/api/get_causa_accidente', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveCausa_accidente(context, data) {
  return Vue.prototype.$axios.post('/api/create_causa_accidente', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addCausa_accidente', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editCausa_accidente(context, data) {
  return Vue.prototype.$axios.put('/api/update_causa_accidente/' + data.id, data)
    .then((result) => {
      context.commit('updateCausa_accidente', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteCausa_accidente(context, id) {
  return Vue.prototype.$axios.post('/api/delete_causa_accidente/' + id)
    .then(() => {
      context.commit('deleteCausa_accidente', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
