import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listEscuela_capacitacion(context, data) {
    return Vue.prototype.$axios.post('/api/list_escuelacapacitacion', data)
        .then((result) => {
            const data = result.data
            context.commit('setEscuela_capacitacionList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getEscuela_capacitacion (context, data) {
    return Vue.prototype.$axios.post('/api/get_escuelacapacitacion', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveEscuela_capacitacion(context, data) {
    return Vue.prototype.$axios.post('/api/create_escuelacapacitacion', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addEscuela_capacitacion', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editEscuela_capacitacion(context, data) {
    return Vue.prototype.$axios.put('/api/update_escuelacapacitacion/' + data.id, data)
        .then((result) => {
            context.commit('updateEscuela_capacitacion', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteEscuela_capacitacion(context, id) {
    return Vue.prototype.$axios.post('/api/delete_escuelacapacitacion/' + id)
        .then(() => {
            context.commit('deleteEscuela_capacitacion', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
