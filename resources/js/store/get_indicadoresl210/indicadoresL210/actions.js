import Vue from 'vue'
import { errorMessage, successMessage } from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getIndicadorl210(context, data) {
    return Vue.prototype.$axios.post('/api/listpg_lineamientos', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setIndicadorl210List', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getIndicadoresl210(context, data) {
    return Vue.prototype.$axios.post('/api/listpg_lineamientos', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveIndicadorl210(context, data) {
    return Vue.prototype.$axios.post('/api/lineamientos', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addIndicadorl210', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editIndicadorl210(context, data) {
    return Vue.prototype.$axios.put('/api/lineamientos/' + data.id, data)
        .then((result) => {
            context.commit('updateIndicadorl210', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteIndicadorl210(context, id) {
    return Vue.prototype.$axios.delete('/api/lineamientos/' + id)
        .then(() => {
            context.commit('deleteIndicadorl210', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}

export async function getReporteLicAvales(context, data) {
    return Vue.prototype.$axios.post('/api/report_countlicaval', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export async function getReporteListaServitur(context, data) {
    return Vue.prototype.$axios.post('/api/report_listservitur', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export async function getReporteListaCubasol(context, data) {
    return Vue.prototype.$axios.post('/api/report_listcubasol', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}