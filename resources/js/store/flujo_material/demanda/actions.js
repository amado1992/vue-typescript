import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getDemanda(context, data) {
    return Vue.prototype.$axios.post('/api/list_demandas', data)
        .then((result) => {
            const data = result.data.data
            context.commit('setDemandaList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function getDemandas(context, data) {
    return Vue.prototype.$axios.post('/api/get_demandas', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function getMeses(context, data) {
    return Vue.prototype.$axios.post('/api/get_meses', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function getDemandaInstalacionAnno(context, data) {
    return Vue.prototype.$axios.post('/api/demanda_instalacion_anno', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function buscarDemandas(context, data) {
    return Vue.prototype.$axios.post('/api/demanda_instalacion_ran_anno', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function getAnnos(context, data) {
    return Vue.prototype.$axios.post('/api/demanda_annos', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function procesarDemandaEspecialista(context, data) {
    return Vue.prototype.$axios.post('/api/procesar_demanda', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
export function saveDemanda(context, data) {
    return Vue.prototype.$axios.post('/api/demanda', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addDemanda', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editDemanda(context, data) {
    return Vue.prototype.$axios.put('/api/demanda/' + data['datosGenerales']['instalacion_id'], data)
        .then((result) => {
            context.commit('updateDemanda', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

// export function deleteDemanda(context, data) {
//     return Vue.prototype.$axios.delete('/api/demanda/' + data['datosGenerales'][0]['instalacion_id'], data)
//         .then(() => {
//             context.commit('deleteDemanda', data['datosGenerales'][0]['instalacion_id'])
//             successMessage(message.sRemoveOk)
//             return id
//         }).catch(err => {
//             errorMessage(message.eRemoveError, err)
//         })
// }

export function deleteDemanda(context, data) {
    return Vue.prototype.$axios.post('/api/demanda_delete', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
