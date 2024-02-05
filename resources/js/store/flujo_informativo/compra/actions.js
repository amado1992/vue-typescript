import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getCompra(context, data) {
    return Vue.prototype.$axios.post('/api/list_compras', data)
        .then((result) => {
            const data = result
            context.commit('setCompraList', data.data)
            return data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}

export function saveCompra(context, data) {
    return Vue.prototype.$axios.post('/api/create_compras', data)
        .then((result) => {
            const data = result.data.data
            context.commit('addCompra', data)
            successMessage(message.sCreateRow)
            return data
        }).catch(err => {
            errorMessage(message.eCreateRow, err)
        })
}

export function editCompra(context, data) {
    return Vue.prototype.$axios.put('/api/update_compras/' + data.id, data)
        .then((result) => {
            context.commit('updateCompra', result.data.data)
            successMessage(message.sUpdateRow)
            return result.data.data
        }).catch(err => {
            errorMessage(message.eUpdateRow, err)
        })
}

export function deleteCompra(context, id) {
    return Vue.prototype.$axios.post('/api/delete_compras/' + id)
        .then(() => {
            context.commit('deleteCompra', id)
            successMessage(message.sRemoveOk)
            return id
        }).catch(err => {
            errorMessage(message.eRemoveError, err)
        })
}

export async function compararvolumenrealsxmesxfamiliaproducto(context, data) {
  return Vue.prototype.$axios.post('/api/comparar_volumenrealsxmesxfamiliaproducto', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function compararprecioxempresaxvolumenreal(context, data) {
  return Vue.prototype.$axios.post('/api/comparar_precioxempresaxvolumenreal', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function compararpreciosxproductoxterritorio(context, data) {
  return Vue.prototype.$axios.post('/api/comparar_preciosxproductoxterritorio', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function listvolumeninventarioxterritorioxfamiliaproducto(context, data) {
  return Vue.prototype.$axios.post('/api/list_volumeninventarioxterritorioxfamiliaproducto', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}


export async function listvolumenrealxosdexfamiliaproducto(context, data) {
  return Vue.prototype.$axios.post('/api/list_volumenrealxosdexfamiliaproducto', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}


