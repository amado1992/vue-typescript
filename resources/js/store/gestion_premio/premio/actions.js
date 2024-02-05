import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getPremio(context, data) {
  return Vue.prototype.$axios.post('/api/list_premio', data)
    .then((result) => {
      const data = result.data
      context.commit('setPremioList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}


export function savePremio(context, data) {
  return Vue.prototype.$axios.post('/api/create_premio', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addPremio', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editPremio(context, data) {
  return Vue.prototype.$axios.put('/api/update_premio/' + data.id, data)
    .then((result) => {
      context.commit('updatePremio', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deletePremio(context, id) {
  return Vue.prototype.$axios.post('/api/delete_premio/' + id)
    .then(() => {
      context.commit('deletePremio', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}

export async function getReporteCantPremioOsde() {
  return Vue.prototype.$axios.post('/api/list_cantPremiosOsde')
    .then((result) => {
      return result.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getReporteCantPremioCategoria() {
  return Vue.prototype.$axios.post('/api/list_cantPremiosCategoriaOsde')
    .then((result) => {
      return result.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getReportePorcientoPremiosEntidad() {
  return Vue.prototype.$axios.post('/api/porcientoPremiosEntidad')
    .then((result) => {
      return result.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
