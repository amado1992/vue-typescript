import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listCategoria_instalacion(context, data) {
    return Vue.prototype.$axios.post('/api/list_categoriainstalacion', data)
        .then((result) => {
            const data = result.data
            context.commit('setCategoria_instalacionList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getCategoria_instalacion(context, data) {
    return Vue.prototype.$axios.post('/api/get_categoriainstalacion', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveCategoria_instalacion(context, data) {
    return Vue.prototype.$axios.post('/api/create_categoriainstalacion', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addCategoria_instalacion', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
          return err.response.data
          errorMessage(message.eCreateRow, err)
        })
}

export function editCategoria_instalacion(context, data) {
    return Vue.prototype.$axios.put('/api/update_categoriainstalacion/' + data.id, data)
        .then((result) => {
            context.commit('updateCategoria_instalacion', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
          return err.response.data
          errorMessage(message.eUpdateRow, err)
      })
}

export function deleteCategoria_instalacion(context, id) {
    return Vue.prototype.$axios.post('/api/delete_categoriainstalacion/' + id)
        .then(() => {
            context.commit('deleteCategoria_instalacion', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
