import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getIndicador(context, data) {
    return Vue.prototype.$axios.post('/api/list_indicadores_importacion', data)
        .then((result) => {
            const data = result.data
            context.commit('setIndicadorList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function saveIndicador(context, data) {
    return Vue.prototype.$axios.post('/api/indicador_importacion', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addIndicador', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editIndicador(context, data) {
    return Vue.prototype.$axios.put('/api/indicador_importacion/' + data.id, data)
        .then((result) => {
            context.commit('updateIndicador', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteIndicador(context, id) {
    return Vue.prototype.$axios.delete('/api/indicador_importacion/' + id)
        .then(() => {
            context.commit('deleteIndicador', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
