import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listPatogeno_identificado(context, data) {
  return Vue.prototype.$axios.post('/api/list_patogeno_identificado', data)
    .then((result) => {
      const data = result
      context.commit('setPatogeno_identificadoList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getPatogeno_identificado(context, data) {
  return Vue.prototype.$axios.post('/api/get_patogeno_identificado', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function savePatogeno_identificado(context, data) {
  return Vue.prototype.$axios.post('/api/create_patogeno_identificado', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addPatogeno_identificado', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editPatogeno_identificado(context, data) {
  return Vue.prototype.$axios.put('/api/update_patogeno_identificado/' + data.id, data)
    .then((result) => {
      context.commit('updatePatogeno_identificado', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deletePatogeno_identificado(context, id) {
  return Vue.prototype.$axios.post('/api/delete_patogeno_identificado/' + id)
    .then(() => {
      context.commit('deletePatogeno_identificado', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
