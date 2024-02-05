import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listOsde(context, data) {
    return Vue.prototype.$axios.post('/api/list_osdes', data)
        .then((result) => {
            const data = result.data
            context.commit('setOsdeList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getOsdes (context, data) {
    return Vue.prototype.$axios.post('/api/get_osdes', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveOsde(context, data) {
    return Vue.prototype.$axios.post('/api/osde', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addOsde', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editOsde(context, data) {
    return Vue.prototype.$axios.put('/api/osde/' + data.id, data)
        .then((result) => {
            context.commit('updateOsde', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteOsde(context, id) {
    return Vue.prototype.$axios.delete('/api/osde/' + id)
        .then(() => {
            context.commit('deleteOsde', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
