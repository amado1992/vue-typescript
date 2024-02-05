import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getInstalacion(context, data) {
    return Vue.prototype.$axios.post('/api/list_instalaciones', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setInstalacionList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getInstalaciones (context, data) {
    return Vue.prototype.$axios.post('/api/get_instalaciones', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveInstalacion(context, data) {
    return Vue.prototype.$axios.post('/api/instalaciones', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addInstalacion', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editInstalacion(context, data) {
    return Vue.prototype.$axios.put('/api/instalaciones/' + data.id, data)
        .then((result) => {
            context.commit('updateEInstalacion', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteInstalacion(context, id) {
    return Vue.prototype.$axios.delete('/api/instalaciones/' + id)
        .then(() => {
            context.commit('deleteInstalacion', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
