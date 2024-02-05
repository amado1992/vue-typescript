import Vue from 'vue'
import {errorMessage, infoMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getCategoria_premio(context, data) {
  return Vue.prototype.$axios.post('/api/list_categoria_premio', data)
    .then((result) => {
      const data = result.data
      context.commit('setCategoria_premioList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}


export function saveCategoria_premio(context, data) {
  return Vue.prototype.$axios.post('/api/create_categoria_premio', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addCategoria_premio', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export async function getCategoria_premios(context, data) {
  return Vue.prototype.$axios.post('/api/get_categoria_premios', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function editCategoria_premio(context, data) {
  return Vue.prototype.$axios.put('/api/update_categoria_premio/' + data.id, data)
    .then((result) => {
      context.commit('updateCategoria_premio', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteCategoria_premio(context, id) {
  return Vue.prototype.$axios.post('/api/delete_categoria_premio/' + id)
    .then(() => {
      context.commit('deleteCategoria_premio', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      infoMessage("El nomenclador está activo")
      errorMessage(message.eRemoveError, err)
    })
}
