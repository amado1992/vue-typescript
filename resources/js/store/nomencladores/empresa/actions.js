import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listEmpresa(context, data) {
    return Vue.prototype.$axios.post('/api/list_empresa', data)
        .then((result) => {
            const data = result.data
            context.commit('setEmpresaList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getEmpresa (context, data) {
    return Vue.prototype.$axios.post('/api/get_empresa', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getEmpresaImportadora (context, data) {
    return Vue.prototype.$axios.post('/api/get_empresa_importadora', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveEmpresa(context, data) {
    return Vue.prototype.$axios.post('/api/create_empresa', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addEmpresa', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
      })
}

export function editEmpresa(context, data) {
    return Vue.prototype.$axios.put('/api/update_empresa/' + data.id, data)
        .then((result) => {
            context.commit('updateEmpresa', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteEmpresa(context, id) {
    return Vue.prototype.$axios.post('/api/delete_empresa/' + id)
        .then(() => {
            context.commit('deleteEmpresa', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
