import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTipoInst(context, data) {
    return Vue.prototype.$axios.post('/api/list_tipos_inst', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setTipoInstList', data.data)
            return data
        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
}
export async function getTipoInsts (context, data) {
    return Vue.prototype.$axios.post('/api/get_tipo_inst', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
          errorMessage(message.eDataError, err)
        })
}
export function saveTipoInst(context, data) {
    return Vue.prototype.$axios.post('/api/tipos_inst', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addTipoInst', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
          return err.response.data
          errorMessage(message.eCreateRow, err)
        })
}

export function editTipoInst(context, data) {
    return Vue.prototype.$axios.put('/api/tipos_inst/' + data.id, data)
        .then((result) => {
            context.commit('updateTipoInst', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
          return err.response.data
          errorMessage(message.eUpdateRow, err)
      })
}

export function deleteTipoInst(context, id) {
    return Vue.prototype.$axios.delete('/api/tipos_inst/' + id)
        .then(() => {
            context.commit('deleteTipoInst', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
