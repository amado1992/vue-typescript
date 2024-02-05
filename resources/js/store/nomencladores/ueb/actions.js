import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listUeb(context, data) {
    return Vue.prototype.$axios.post('/api/list_ueb', data)
        .then((result) => {
            const data = result.data
            context.commit('setUebList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getUeb (context, data) {
    return Vue.prototype.$axios.post('/api/get_ueb', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveUeb(context, data) {
    return Vue.prototype.$axios.post('/api/create_ueb', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addUeb', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editUeb(context, data) {
    return Vue.prototype.$axios.put('/api/update_ueb/' + data.id, data)
        .then((result) => {
            context.commit('updateUeb', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
      })
}

export function deleteUeb(context, id) {
    return Vue.prototype.$axios.post('/api/delete_ueb/' + id)
        .then(() => {
            context.commit('deleteUeb', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
