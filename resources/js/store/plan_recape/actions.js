import Vue from 'vue'
import { errorMessage, successMessage } from '../../utils/notificaciones'
import * as message from '../../utils/resources'

export function listPlanRecape(context, data) {
    return Vue.prototype.$axios.post('/api/list_planrecape_fi_paginate', data)
        .then((result) => {
            const data = result.data.data.data
            context.commit('setPlanRecapeList', data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getPlanRecape() {
    return Vue.prototype.$axios.post('/api/list_planrecape_fi')
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function savePlanRecape(context, data) {
    return Vue.prototype.$axios.post('/api/planrecape_fi', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addPlanRecape', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editPlanRecape(context, data) {
    return Vue.prototype.$axios.put('/api/planrecape_fi/' + data.id, data)
        .then((result) => {
            context.commit('updatePlanRecape', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deletePlanRecape(context, id) {
    return Vue.prototype.$axios.delete('/api/planrecape_fi/' + id)
        .then(() => {
            context.commit('deletePlanRecape', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}
