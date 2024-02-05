import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getVehiculo_ajeno(context, data) {
    return Vue.prototype.$axios.post('/api/list_vajeno', data)
        .then((result) => {
            const data = result.data
            context.commit('setVehiculo_ajenoList', data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export async function getVehiculo_ajenos (context, data) {
    return Vue.prototype.$axios.post('/api/get_vajeno', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function saveVehiculo_ajeno(context, data) {
    return Vue.prototype.$axios.post('/api/create_vajeno', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addVehiculo_ajeno', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editVehiculo_ajeno(context, data) {
    return Vue.prototype.$axios.put('/api/update_vajeno/' + data.id, data)
        .then((result) => {
            context.commit('updateVehiculo_ajeno', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteVehiculo_ajeno(context, id) {
    return Vue.prototype.$axios.post('/api/delete_vajeno/' + id)
        .then(() => {
            context.commit('deleteVehiculo_ajeno', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
