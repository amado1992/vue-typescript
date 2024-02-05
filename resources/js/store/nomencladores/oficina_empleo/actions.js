import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listOficina_empleo(context, data) {
    return Vue.prototype.$axios.post('/api/list_oficinaempleo', data)
        .then((result) => {
            const data = result.data
            context.commit('setOficina_empleoList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getOficina_empleo (context, data) {
    return Vue.prototype.$axios.post('/api/get_oficinaempleo', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveOficina_empleo(context, data) {
    return Vue.prototype.$axios.post('/api/create_oficinaempleo', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addOficina_empleo', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editOficina_empleo(context, data) {
    return Vue.prototype.$axios.put('/api/update_oficinaempleo/' + data.id, data)
        .then((result) => {
            context.commit('updateOficina_empleo', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteOficina_empleo(context, id) {
    return Vue.prototype.$axios.post('/api/delete_oficinaempleo/' + id)
        .then(() => {
            context.commit('deleteOficina_empleo', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
