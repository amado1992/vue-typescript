import Vue from 'vue'
import { errorMessage, successMessage } from '../../utils/notificaciones'
import * as message from '../../utils/resources'

export function getAllDisponbilidades(context, data) {
    return Vue.prototype.$axios.post('/api/list_disponibilidad', data)
        .then((result) => {
            const data = result.data
            context.commit('setDisponibilidadesList', data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getPorciento(context,data) {
    return Vue.prototype.$axios.post('/api/list_porciento',data)
        .then((result) => {
            const data = result.data
            context.commit('setDisponibilidadesList', data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getMayorIncidencia(context, data) {
    return Vue.prototype.$axios.post('/api/list_mayorIncidencia', data)
        .then((result) => {
            const data = result.data
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getHabNoDisp(context, data) {
    return Vue.prototype.$axios.post('/api/list_habNoDisp', data)
        .then((result) => {
            const data = result.data
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function editDisponibilidad (context, data) {
    return Vue.prototype.$axios.put('/api/update_disponibilidad/' + data.id, data)
        .then((result) => {
            context.commit('updateDisponibilidades', result.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}
