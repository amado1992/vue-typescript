import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listCadena_hotelera(context, data) {
    return Vue.prototype.$axios.post('/api/list_cadenahotelera', data)
        .then((result) => {
            const data = result.data
            context.commit('setCadena_hoteleraList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getCadena_hotelera (context, data) {
    return Vue.prototype.$axios.post('/api/get_cadenahotelera', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveCadena_hotelera(context, data) {
    return Vue.prototype.$axios.post('/api/create_cadenahotelera', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addCadena_hotelera', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editCadena_hotelera(context, data) {
    return Vue.prototype.$axios.put('/api/update_cadenahotelera/' + data.id, data)
        .then((result) => {
            context.commit('updateCadena_hotelera', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteCadena_hotelera(context, id) {
    return Vue.prototype.$axios.post('/api/delete_cadenahotelera/' + id)
        .then(() => {
            context.commit('deleteCadena_hotelera', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
