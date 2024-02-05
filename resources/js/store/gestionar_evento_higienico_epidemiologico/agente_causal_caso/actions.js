import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listAgente_causal_caso(context, data) {
  return Vue.prototype.$axios.post('/api/list_agentecausalcaso', data)
    .then((result) => {
      const data = result.data
      context.commit('setAgente_causal_casoList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getAgente_causal_caso(context, data) {
  return Vue.prototype.$axios.post('/api/get_agentecausalcaso', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveAgente_causal_caso(context, data) {
  return Vue.prototype.$axios.post('/api/create_agentecausalcaso', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addAgente_causal_caso', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editAgente_causal_caso(context, data) {
  return Vue.prototype.$axios.put('/api/update_agentecausalcaso/' + data.id, data)
    .then((result) => {
      context.commit('updateAgente_causal_caso', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteAgente_causal_caso(context, id) {
  return Vue.prototype.$axios.post('/api/delete_agentecausalcaso/' + id)
    .then(() => {
      context.commit('deleteAgente_causal_caso', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
