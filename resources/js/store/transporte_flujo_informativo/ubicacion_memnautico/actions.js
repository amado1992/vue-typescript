import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getUbicacion_memnautico(context, data) {
  return Vue.prototype.$axios.post('/api/list_umemnautico', data)
    .then((result) => {
      const data = result.data
      context.commit('setUbicacion_memnauticoList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getUbicacion_memnauticos(context, data) {
  return Vue.prototype.$axios.post('/api/get_umemnautico', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveUbicacion_memnautico(context, data) {
  return Vue.prototype.$axios.post('/api/create_umemnautico', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addUbicacion_memnautico', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editUbicacion_memnautico(context, data) {
  return Vue.prototype.$axios.put('/api/update_umemnautico/' + data.id, data)
    .then((result) => {
      context.commit('updateUbicacion_memnautico', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteUbicacion_memnautico(context, id) {
  return Vue.prototype.$axios.post('/api/delete_umemnautico/' + id)
    .then(() => {
      context.commit('deleteUbicacion_memnautico', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
