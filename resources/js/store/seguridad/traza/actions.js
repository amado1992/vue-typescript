import Vue from 'vue'
import { errorMessage } from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTraza (context, data) {
  return Vue.prototype.$axios.post('/api/list_trazas', data)
    .then((result) => {
      const data = result.data.data
      context.commit('setTrazaList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
export function getTotalTraza (context, data) {
  return Vue.prototype.$axios.post('/api/traza_total', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
