import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listEscuela_ramal(context, data) {
  return Vue.prototype.$axios.post('/api/list_escuela_ramal', data)
    .then((result) => {
      const data = result.data
      context.commit('setEscuela_ramalList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getEscuela_ramal(context, data) {
  return Vue.prototype.$axios.post('/api/get_escuela_ramal', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveEscuela_ramal(context, data) {
  return Vue.prototype.$axios.post('/api/create_escuela_ramal', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addEscuela_ramal', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editEscuela_ramal(context, data) {
  return Vue.prototype.$axios.put('/api/update_escuela_ramal/' + data.id, data)
    .then((result) => {
      context.commit('updateEscuela_ramal', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteEscuela_ramal(context, id) {
  return Vue.prototype.$axios.post('/api/delete_escuela_ramal/' + id)
    .then(() => {
      context.commit('deleteEscuela_ramal', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
