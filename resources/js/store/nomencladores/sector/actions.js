import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getSector(context, data) {
    return Vue.prototype.$axios.post('/api/list_sectores', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setSectorList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getSectores (context, data) {
    return Vue.prototype.$axios.post('/api/get_sectores', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveSectores(context, data) {
    return Vue.prototype.$axios.post('/api/sector', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addSector', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
          return err.response.data
          errorMessage(message.eCreateRow, err)
        })
}

export function editSector(context, data) {
    return Vue.prototype.$axios.put('/api/sector/' + data.id, data)
        .then((result) => {
            context.commit('updateSector', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
          return err.response.data
          errorMessage(message.eUpdateRow, err)
        })
}

export function deleteSector(context, id) {
    return Vue.prototype.$axios.delete('/api/sector/' + id)
        .then(() => {
            context.commit('deleteSector', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            const errormsg = err.response.data.error;
            if (errormsg.includes('SQLSTATE[23000]')) {
              errorMessage(message.usoElementWarningRow, err)
            } else {
            errorMessage(message.eRemoveError, err)
            }
        })
}
