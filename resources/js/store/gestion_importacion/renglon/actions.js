import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getRenglon(context, data) {
    return Vue.prototype.$axios.post('/api/list_renglones', data)
        .then((result) => {
            const data = result.data
            context.commit('setRenglonList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function saveRenglon(context, data) {
    return Vue.prototype.$axios.post('/api/renglon', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addRenglon', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editRenglon(context, data) {
    return Vue.prototype.$axios.put('/api/renglon/' + data.id, data)
        .then((result) => {
            context.commit('updateRenglon', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteRenglon(context, id) {
    return Vue.prototype.$axios.delete('/api/renglon/' + id)
        .then(() => {
            context.commit('deleteRenglon', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
