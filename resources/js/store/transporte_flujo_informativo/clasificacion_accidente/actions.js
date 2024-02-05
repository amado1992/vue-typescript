import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getClasificacion_accidente(context, data) {
  return Vue.prototype.$axios.post('/api/list_clasificacion_accidente', data)
    .then((result) => {
      const data = result.data
      context.commit('setClasificacion_accidenteList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getClasificacion_accidentes(context, data) {
  return Vue.prototype.$axios.post('/api/get_clasificacion_accidente', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveClasificacion_accidente(context, data) {
  return Vue.prototype.$axios.post('/api/create_clasificacion_accidente', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addClasificacion_accidente', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editClasificacion_accidente(context, data) {
  return Vue.prototype.$axios.put('/api/update_clasificacion_accidente/' + data.id, data)
    .then((result) => {
      context.commit('updateClasificacion_accidente', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteClasificacion_accidente(context, id) {
  return Vue.prototype.$axios.post('/api/delete_clasificacion_accidente/' + id)
    .then(() => {
      context.commit('deleteClasificacion_accidente', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
