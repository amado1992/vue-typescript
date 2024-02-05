import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listOace(context, data) {
    return Vue.prototype.$axios.post('/api/list_oace', data)
        .then((result) => {
            const data = result.data
            context.commit('setOaceList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getOace (context, data) {
    return Vue.prototype.$axios.post('/api/get_oace', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveOace(context, data) {
    return Vue.prototype.$axios.post('/api/create_oace', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addOace', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editOace(context, data) {
    return Vue.prototype.$axios.put('/api/update_oace/' + data.id, data)
        .then((result) => {
            context.commit('updateOace', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteOace(context, id) {
    return Vue.prototype.$axios.post('/api/delete_oace/' + id)
        .then(() => {
            context.commit('deleteOace', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
