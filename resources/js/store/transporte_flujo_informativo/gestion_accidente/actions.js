import Vue from 'vue'
import { errorMessage, successMessage } from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listGestion_accidente(context, data) {
    return Vue.prototype.$axios.post('/api/list_gaccidente', data)
        .then((result) => {
            const data = result.data
            context.commit('setGestion_accidenteList', data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export async function getGestion_accidente(context, data) {
    return Vue.prototype.$axios.post('/api/get_gaccidente', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveGestion_accidente(context, data) {
    return Vue.prototype.$axios.post('/api/create_gaccidente', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addGestion_accidente', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editGestion_accidente(context, data) {
    return Vue.prototype.$axios.put('/api/update_gaccidente/' + data.id, data)
        .then((result) => {
            context.commit('updateGestion_accidente', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteGestion_accidente(context, id) {
    return Vue.prototype.$axios.delete('/api/delete_gaccidente/' + id)
        .then(() => {
            context.commit('deleteGestion_accidente', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}

export async function getReporteAccidentalidad(context,data) {
  return Vue.prototype.$axios.post('/api/reporte/accidentalidad',data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getHorarioAccidentes(context,data) {
  return Vue.prototype.$axios.post('/api/reporte/horarioconmasaccidentes',data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
