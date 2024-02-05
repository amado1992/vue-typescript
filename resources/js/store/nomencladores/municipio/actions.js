import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getMunicipio(context, data) {
    return Vue.prototype.$axios.post('/api/list_municipios', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setMunicipioList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getMunicipios (context, data) {
    return Vue.prototype.$axios.post('/api/get_municipios', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveMunicipio(context, data) {
    return Vue.prototype.$axios.post('/api/municipio', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addMunicipio', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eCreateRow, err)
        })
}

export function editMunicipio(context, data) {
    return Vue.prototype.$axios.put('/api/municipio/' + data.id, data)
        .then((result) => {
            context.commit('updateMunicipio', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            return err.response.data
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteMunicipio(context, id) {
    return Vue.prototype.$axios.delete('/api/municipio/' + id)
        .then(() => {
            context.commit('deleteMunicipio', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
