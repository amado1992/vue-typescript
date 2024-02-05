import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listInfotur(context, data) {
    return Vue.prototype.$axios.post('/api/list_infotur', data)
        .then((result) => {
            const data = result.data
            context.commit('setInfoturList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getInfotur (context, data) {
    return Vue.prototype.$axios.post('/api/get_infotur', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveInfotur(context, data) {
    return Vue.prototype.$axios.post('/api/create_infotur', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addInfotur', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editInfotur(context, data) {
    return Vue.prototype.$axios.put('/api/update_infotur/' + data.id, data)
        .then((result) => {
            context.commit('updateInfotur', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteInfotur(context, id) {
    return Vue.prototype.$axios.post('/api/delete_infotur/' + id)
        .then(() => {
            context.commit('deleteInfotur', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
