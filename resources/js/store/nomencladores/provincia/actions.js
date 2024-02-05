import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getProvincia(context, data) {
    return Vue.prototype.$axios.post('/api/list_provincias', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setProvinciaList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getProvincias (context, data) {
    return Vue.prototype.$axios.post('/api/get_provincias', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveProvincia(context, data) {
    return Vue.prototype.$axios.post('/api/provincia', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addProvincia', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
          return err.response.data
          errorMessage(message.eCreateRow, err)
        })
}

export function editProvincia(context, data) {
    return Vue.prototype.$axios.put('/api/provincia/' + data.id, data)
        .then((result) => {
            context.commit('updateProvincia', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
          return err.response.data
          errorMessage(message.eUpdateRow, err)
        })
}

export function deleteProvincia(context, id) {
    return Vue.prototype.$axios.delete('/api/provincia/' + id)
        .then(() => {
            context.commit('deleteProvincia', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
