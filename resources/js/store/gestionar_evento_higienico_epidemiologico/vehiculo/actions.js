import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listVehiculo(context, data) {
  return Vue.prototype.$axios.post('/api/list_vehiculo', data)
    .then((result) => {
      const data = result.data
      context.commit('setVehiculoList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getVehiculo(context, data) {
  return Vue.prototype.$axios.post('/api/get_vehiculo', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveVehiculo(context, data) {
  return Vue.prototype.$axios.post('/api/create_vehiculo', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addVehiculo', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editVehiculo(context, data) {
  return Vue.prototype.$axios.put('/api/update_vehiculo/' + data.id, data)
    .then((result) => {
      context.commit('updateVehiculo', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteVehiculo(context, id) {
  return Vue.prototype.$axios.post('/api/delete_vehiculo/' + id)
    .then(() => {
      context.commit('deleteVehiculo', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
