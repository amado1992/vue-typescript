import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listClasificacion_evento(context, data) {
  return Vue.prototype.$axios.post('/api/list_clasificacion_evento', data)
    .then((result) => {
      const data = result
      context.commit('setClasificacion_eventoList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getClasificacion_evento(context, data) {
  return Vue.prototype.$axios.post('/api/get_clasificacion_evento', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveClasificacion_evento(context, data) {
  return Vue.prototype.$axios.post('/api/create_clasificacion_evento', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addClasificacion_evento', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editClasificacion_evento(context, data) {
  return Vue.prototype.$axios.put('/api/update_clasificacion_evento/' + data.id, data)
    .then((result) => {
      context.commit('updateClasificacion_evento', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteClasificacion_evento(context, id) {
  return Vue.prototype.$axios.post('/api/delete_clasificacion_evento/' + id)
    .then(() => {
      context.commit('deleteDeteccion', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
