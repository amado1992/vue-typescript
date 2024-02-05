import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getEntidad(context, data) {
    return Vue.prototype.$axios.post('/api/list_entidades', data)
        .then((result) => {
            const data = result.data
            context.commit('setEntidadList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}


export function saveEntidad(context, data) {
    return Vue.prototype.$axios.post('/api/entidad', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addEntidad', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editEntidad(context, data) {
    return Vue.prototype.$axios.put('/api/entidad/' + data.id, data)
        .then((result) => {
            context.commit('updateEntidad', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteEntidad(context, id) {
    return Vue.prototype.$axios.delete('/api/entidad/' + id)
        .then(() => {
            context.commit('deleteEntidad', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
