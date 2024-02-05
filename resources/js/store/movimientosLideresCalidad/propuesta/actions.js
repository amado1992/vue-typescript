import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getPropuesta(context, data) {
    return Vue.prototype.$axios.post('/api/list_propuestas', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setPropuestaList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getPropuestas (context, data) {
    return Vue.prototype.$axios.post('/api/get_propuestas', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function savePropuesta(context, data) {
    return Vue.prototype.$axios.post('/api/propuesta', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addPropuesta', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editPropuesta(context, data) {
    return Vue.prototype.$axios.put('/api/propuesta/' + data.id, data)
        .then((result) => {
            context.commit('updatePropuesta', result.data.data)
            if (result.data.data.estado === 'Sin Procesar') {
                successMessage(message.sUpdateRow)
            } else {
                successMessage(message.sProcesadoRow)                
            }            
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deletePropuesta(context, id) {
    return Vue.prototype.$axios.delete('/api/propuesta/' + id)
        .then(() => {
            context.commit('deletePropuesta', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}

export async function getPropuestaEstado (context, data) {
    return Vue.prototype.$axios.post('/api/get_propuestas_estado', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getListaRelacionesPropuesta (context, data) {
    return Vue.prototype.$axios.post('/api/lista_relaciones_propuesta', data)
        .then((result) => {
            return result.data.data
        }).catch()
}
