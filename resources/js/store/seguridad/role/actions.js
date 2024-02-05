import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getRole(context, data) {
    return Vue.prototype.$axios.post('/api/list_roles', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setRoleList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getActivoRole(context, data) {
    return Vue.prototype.$axios.post('/api/role_activo', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getNombreRole(context, data) {
    return Vue.prototype.$axios.post('/api/role_nombre', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getRolePermission(context, data) {
    return Vue.prototype.$axios.post('/api/role_get_permission', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function saveRole(context, data) {
    return Vue.prototype.$axios.post('/api/roles', data)
        .then((result) => {
            // console.log(result)
            // const data = result.data.data
            // context.commit('addRole', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editRole(context, data) {
    return Vue.prototype.$axios.put('/api/roles/' + data.id, data)
        .then((result) => {
            context.commit('updateRole', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteRole(context, id) {
    return Vue.prototype.$axios.delete('/api/roles/' + id)
        .then(() => {
            context.commit('deleteRole', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
