import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listSucursal(context, data) {
    return Vue.prototype.$axios.post('/api/list_sucursal', data)
        .then((result) => {
            const data = result.data
            context.commit('setSucursalList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getSucursal (context, data) {
    return Vue.prototype.$axios.post('/api/get_sucursal', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveSucursal(context, data) {
    return Vue.prototype.$axios.post('/api/create_sucursal', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addSucursal', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editSucursal(context, data) {
    return Vue.prototype.$axios.put('/api/update_sucursal/' + data.id, data)
        .then((result) => {
            context.commit('updateSucursal', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteSucursal(context, id) {
    return Vue.prototype.$axios.post('/api/delete_sucursal/' + id)
        .then(() => {
            context.commit('deleteSucursal', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
