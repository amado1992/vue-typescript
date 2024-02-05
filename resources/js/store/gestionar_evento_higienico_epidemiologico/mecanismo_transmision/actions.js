import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function listMecanismo_transmision(context, data) {
  return Vue.prototype.$axios.post('/api/list_mecanismotransmision', data)
    .then((result) => {
      const data = result.data
      context.commit('setMecanismo_transmisionList', data.data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getMecanismo_transmision(context, data) {
  return Vue.prototype.$axios.post('/api/get_mecanismotransmision', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveMecanismo_transmision(context, data) {
  return Vue.prototype.$axios.post('/api/create_mecanismotransmision', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addMecanismo_transmision', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editMecanismo_transmision(context, data) {
  return Vue.prototype.$axios.put('/api/update_mecanismotransmision/' + data.id, data)
    .then((result) => {
      context.commit('updateMecanismo_transmision', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteMecanismo_transmision(context, id) {
  return Vue.prototype.$axios.post('/api/delete_mecanismotransmision/' + id)
    .then(() => {
      context.commit('deleteMecanismo_transmision', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
