import Vue from 'vue'
import { errorMessage, successMessage } from '../../utils/notificaciones'
import * as message from '../../utils/resources'

export async function getCumplimientoPlanRecape() {
    return Vue.prototype.$axios.post('/api/listcumplimiento_planrecape')
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveCumplimientoPlanRecape(context, data) {
    return Vue.prototype.$axios.post('/api/cumplimiento_planrecape', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addCumplimientoPlanRecape', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}
