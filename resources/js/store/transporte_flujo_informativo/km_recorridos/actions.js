import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getKm_recorridos(context, data) {
    return Vue.prototype.$axios.post('/api/list_km_recorridos', data)
        .then((result) => {
            const data = result.data
            context.commit('setKm_recorridosList', data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function saveKm_recorridos(context, data) {
    return Vue.prototype.$axios.post('/api/create_km_recorridos', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addKm_recorridos', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editKm_recorridos(context, data) {
    return Vue.prototype.$axios.put('/api/update_km_recorridos/' + data.id, data)
        .then((result) => {
            context.commit('updateKm_recorridos', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

