import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getCategoria_almacen(context, data) {
    return Vue.prototype.$axios.post('/api/list_categoria_almacen', data)
        .then((result) => {
            const data = result
            context.commit('setCategoria_almacenList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getCategoria_almacenes (context, data) {
    return Vue.prototype.$axios.post('/api/get_categoria_almacenes', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveCategoria_almacen(context, data) {
    return Vue.prototype.$axios.post('/api/create_categoria_almacen', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addCategoria_almacen', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
        })
}

export function editCategoria_almacen(context, data) {
    return Vue.prototype.$axios.put('/api/update_categoria_almacen/' + data.id, data)
        .then((result) => {
            context.commit('updateCategoria_almacen', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
        return err.response.data
        })
}

export function deleteCategoria_almacen(context, id) {
    return Vue.prototype.$axios.post('/api/delete_categoria_almacen/' + id)
        .then(() => {
            context.commit('deleteCategoria_almacen', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
