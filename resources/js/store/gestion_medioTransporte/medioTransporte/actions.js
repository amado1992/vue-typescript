import Vue from 'vue'
import { errorMessage, successMessage } from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listMedioTransporte(context, data) {
    return Vue.prototype.$axios.post('/api/list_mediotransporte', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setMedioTransporteList', data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getMedioTransporte(context, data) {
    return Vue.prototype.$axios.post('/api/listpg_lineamientos', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveMedioTransporte(context, data) {
    return Vue.prototype.$axios.post('/api/mediotransporte_fi', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addMedioTransporte', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editMedioTransporte(context, data) {
    return Vue.prototype.$axios.put('/api/mediotransporte_fi/' + data.id, data)
        .then((result) => {
            context.commit('updateMedioTransporte', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteMedioTransporte(context, id) {
    return Vue.prototype.$axios.delete('/api/mediotransporte_fi/' + id)
        .then(() => {
            context.commit('deleteMedioTransporte', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}

export async function reporteCDC(context,data) {
    return Vue.prototype.$axios.post('/api/reporte_CDT', data)
        .then((result) => {
            context.commit('setMedioTransporteList', data)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function parqueTotal(context) {
    return Vue.prototype.$axios.post('/api/reporte_parque_total')
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}