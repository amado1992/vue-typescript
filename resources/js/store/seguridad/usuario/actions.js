import Vue from 'vue'
import { errorMessage, successMessage } from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'
import {laCaretSquareUpSolid} from "@quasar/extras/line-awesome";

export function getUsuario (context, data) {
  return Vue.prototype.$axios.post('/api/list_users', data)
    .then((result) => {
      const data = result.data.data
      context.commit('setUsuarioList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveUsuario (context, data) {
  return Vue.prototype.$axios.post('/api/users', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addUsuario', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      errorMessage(message.eCreateRow, err)
    })
}

export function editUsuario (context, data) {
  return Vue.prototype.$axios.put('/api/users/' + data.id, data)
    .then((result) => {
      context.commit('updateUsuario', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      errorMessage(message.eUpdateRow, err)
    })
}

export function deleteUsuario (context, id) {
  return Vue.prototype.$axios.delete('/api/users/' + id)
    .then(() => {
      context.commit('deleteUsuario', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}

export function loginUsuario (context, data) {
  return Vue.prototype.$axios.post('/api/login', data)
    .then((result) => {
      const data = result.data

      return data
    }).catch(err => {
      errorMessage(message.eLogin, err)
    })
}
export function changePass (context, data) {
    return Vue.prototype.$axios.post('/api/change_pass', data)
        .then((result) => {
            const data = result.data
            return data
        }).catch(err => {
            errorMessage(message.eChangePss, err)
        })
}

export function logoutUsuario (context, data) {
  return Vue.prototype.$axios.post('/api/logout', data)
    .then((result) => {
      const data = result.data.data
      return data
    }).catch(err => {
    })
}

export function changePasswordUsuario (context, data) {
  return Vue.prototype.$axios.post('/api/change_password', data)
    .then((result) => {
      const data = result.data.data
      successMessage(message.schangePassword)
      return data
    }).catch(err => {
      errorMessage(message.echangePassword, err)
    })
}
export function resetPasswordUsuario (context, data) {
    return Vue.prototype.$axios.post('/api/reset_password', data)
        .then((result) => {
            const data = result.data
            successMessage(message.schangeResetPassword)
            return data
        }).catch(err => {
            errorMessage(message.echangePassword, err)
            return false
        })
}

export function getTotalUsuarios (context, data) {
  return Vue.prototype.$axios.post('/api/usuarios_total', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
export function getLoadFirstLogin (context, data) {
    return Vue.prototype.$axios.post('/api/load_first_login', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function getNombreUsuarios (context, data) {
    return Vue.prototype.$axios.post('/api/usuarios_nombre', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function getTotalUsuariosRol (context, data) {
  return Vue.prototype.$axios.post('/api/usuarios_rol_total', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
