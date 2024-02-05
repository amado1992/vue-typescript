import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getInstalacion(context, data) {
    return Vue.prototype.$axios.post('/api/list_entidades', data)
        .then((result) => {
            const data = result.data
            context.commit('setInstalacionList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}


export function saveInstalacion(context, data) {
    return Vue.prototype.$axios.post('/api/entidad', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addInstalacion', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editInstalacion(context, data) {
    return Vue.prototype.$axios.put('/api/entidad/' + data.id, data)
        .then((result) => {
            context.commit('updateInstalacion', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteInstalacion(context, id) {
    return Vue.prototype.$axios.delete('/api/entidad/' + id)
        .then(() => {
            context.commit('deleteInstalacion', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}

export function getInstalacionOsde(context, data) {
  return Vue.prototype.$axios.post('/api/get_instalacionosde',{
    "instalacion_id": data
  })
    .then((result) => {
      const data = result.data
      context.commit('setInstalacion', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
