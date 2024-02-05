import Vue from 'vue'
import { errorMessage, successMessage } from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listEventoEHE(context, data) {
    return Vue.prototype.$axios.post('/api/list_eventohe', data)
        .then((result) => {
            const data = result.data.data.data
            context.commit('setEventoEHEList', data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function saveEventoEHE(context, data) {
    return Vue.prototype.$axios.post('/api/eventohe', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addEventoEHE', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editEventoEHE(context, data) {
    return Vue.prototype.$axios.put('/api/eventohe/' + data.id, data)
        .then((result) => {
            context.commit('updateEventoEHE', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteEventoEHE(context, id) {
    return Vue.prototype.$axios.delete('/api/eventohe/' + id)
        .then(() => {
            context.commit('deleteEventoEHE', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}