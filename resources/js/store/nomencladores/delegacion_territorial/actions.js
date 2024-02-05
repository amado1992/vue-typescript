import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listDelegacion_territorial(context, data) {
    return Vue.prototype.$axios.post('/api/list_delegacionterritorial', data)
        .then((result) => {
            const data = result.data
            context.commit('setDelegacion_territorialList', data.data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getDelegacion_territorial (context, data) {
    return Vue.prototype.$axios.post('/api/get_delegacionterritorial', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveDelegacion_territorial(context, data) {
    return Vue.prototype.$axios.post('/api/create_delegacionterritorial', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addDelegacion_territorial', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editDelegacion_territorial(context, data) {
    return Vue.prototype.$axios.put('/api/update_delegacionterritorial/' + data.id, data)
        .then((result) => {
            context.commit('updateDelegacion_territorial', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteDelegacion_territorial(context, id) {
    return Vue.prototype.$axios.post('/api/delete_delegacionterritorial/' + id)
        .then(() => {
            context.commit('deleteDelegacion_territorial', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
