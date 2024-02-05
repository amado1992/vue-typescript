import Vue from 'vue'
import { errorMessage, successMessage } from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getPersona (context, data) {
  return Vue.prototype.$axios.post('/api/list_personas', data)
    .then((result) => {
      const data = result.data.data
      context.commit('setPersonaList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getActivoPersona (context, data) {
  return Vue.prototype.$axios.post('/api/persona_activo', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
export function getNombrePersona (context, data) {
    return Vue.prototype.$axios.post('/api/persona_nombre', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function getCorreoPersona (context, data) {
    return Vue.prototype.$axios.post('/api/persona_correo', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function savePersona (context, data) {
  return Vue.prototype.$axios.post('/api/personas', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addPersona', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      errorMessage(message.eCreateRow, err)
    })
}

export function editPersona (context, data) {
  return Vue.prototype.$axios.put('/api/personas/' + data.id, data)
    .then((result) => {
      context.commit('updatePersona', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      errorMessage(message.eUpdateRow, err)
    })
}

export function deletePersona (context, id) {
  return Vue.prototype.$axios.delete('/api/personas/' + id)
    .then(() => {
      context.commit('deletePersona', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
export function getTotalPersona (context, data) {
  return Vue.prototype.$axios.post('/api/persona_total', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
