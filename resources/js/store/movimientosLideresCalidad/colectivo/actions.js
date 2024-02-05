import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getColectivo(context, data) {
    return Vue.prototype.$axios.post('/api/list_colectivos', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setColectivoList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getColectivos (context, data) {
    return Vue.prototype.$axios.post('/api/get_colectivos', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getColectivoEstado (context, data) {
    return Vue.prototype.$axios.post('/api/get_colectivos_estado', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveColectivo(context, data) {
    return Vue.prototype.$axios.post('/api/colectivo', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addColectivo', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editColectivo(context, data) {
    return Vue.prototype.$axios.put('/api/colectivo/' + data.id, data)
        .then((result) => {
            context.commit('updateColectivo', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteColectivo(context, id) {
    return Vue.prototype.$axios.delete('/api/colectivo/' + id)
        .then(() => {
            context.commit('deleteColectivo', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
export function getListaRelaciones (context, data) {
    return Vue.prototype.$axios.post('/api/lista_relaciones', data)
        .then((result) => {
            return result.data.data
        }).catch()
}
