import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getReportes(context) {
    return Vue.prototype.$axios.get('/api/costo_calidad_reports')
        .then((result) => {
            const data = result.data.data.data
            context.commit('setReportesList', data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function saveReporte(context, data) {
    return Vue.prototype.$axios.post('/api/costo_calidad_report', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addReportes', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editReporte(context, data) {
    return Vue.prototype.$axios.put('/api/costo_calidad_report/' + data.id, data)
        .then((result) => {
            context.commit('updateReportes', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteReporte(context, id) {
    return Vue.prototype.$axios.delete('/api/costo_calidad_report/' + id)
        .then(() => {
            context.commit('deleteReportes', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}