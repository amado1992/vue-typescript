import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getImportacion(context, data) {
    return Vue.prototype.$axios.post('/api/list_importaciones', data)
        .then((result) => {
            const data = result.data
            context.commit('setImportacionList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function saveImportacion(context, data) {
    return Vue.prototype.$axios.post('/api/importacion', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addImportacion', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editImportacion(context, data) {
    return Vue.prototype.$axios.put('/api/importacion/' + data.id, data)
        .then((result) => {
            context.commit('updateImportacion', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteImportacion(context, id) {
    return Vue.prototype.$axios.delete('/api/importacion/' + id)
        .then(() => {
            context.commit('deleteImportacion', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}

export function getImportacionIndicadorMes(context, data) {
    return Vue.prototype.$axios.post('/api/reporte_importacion_i_m', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setImportacionList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getImportacionEmpresaMes(context, data) {
    return Vue.prototype.$axios.post('/api/reporte_importacion_e_m', data)
        .then((result) => {
            const data = result.data
            context.commit('setImportacionList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getAllImportaciones(context, data) {
    return Vue.prototype.$axios.post('/api/reporte_importaciones_all', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getDataPlanAnteriorvsActual() {
    return Vue.prototype.$axios.post('/api/reporte_importaciones_compararplan')
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
