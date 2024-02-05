import Vue from 'vue'
import { errorMessage, successMessage } from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'
import {laCaretSquareUpSolid} from "@quasar/extras/line-awesome";

export function getUsuarios (context, data) {
  return Vue.prototype.$axios.post('/api/lista_usuarios', data)
    .then((result) => {
      const data = result.data.data
      context.commit('setUsuariosList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function editUsuarios (context, data) {
    return Vue.prototype.$axios.put('/api/usuarios/' + data.id, data)
        .then((result) => {
            context.commit('updateUsuarios', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function loginUsuarios (context, data) {
  return Vue.prototype.$axios.post('/api/loginSa', data)
    .then((result) => {
      const data = result.data

      return data
    }).catch(err => {
      errorMessage(message.eLogin, err)
    })
}
export function logoutUsuarios (context, data) {
  return Vue.prototype.$axios.post('/api/logoutSa', data)
    .then((result) => {
      const data = result.data.data
      return data
    }).catch(err => {
    })
}
export function getTotalUsuariosSa (context, data) {
  return Vue.prototype.$axios.post('/api/usuarios_total', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
export function getTotalUsuariosRolSa (context, data) {
  return Vue.prototype.$axios.post('/api/usuarios_rol_total', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
