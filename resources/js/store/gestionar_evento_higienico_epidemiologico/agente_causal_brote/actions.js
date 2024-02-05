import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listAgente_causal_brote(context, data) {
  return Vue.prototype.$axios.post('/api/list_agentecausalbrote', data)
    .then((result) => {
      const data = result.data
      context.commit('setAgente_causal_broteList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getAgente_causal_brote(context, data) {
  return Vue.prototype.$axios.post('/api/get_agentecausalbrote', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveAgente_causal_brote(context, data) {
  return Vue.prototype.$axios.post('/api/create_agentecausalbrote', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addAgente_causal_brote', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editAgente_causal_brote(context, data) {
  return Vue.prototype.$axios.put('/api/update_agentecausalbrote/' + data.id, data)
    .then((result) => {
      context.commit('updateAgente_causal_brote', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteAgente_causal_brote(context, id) {
  return Vue.prototype.$axios.post('/api/delete_agentecausalbrote/' + id)
    .then(() => {
      context.commit('deleteAgente_causal_brote', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
