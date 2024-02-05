import Vue from 'vue'
import {errorMessage, infoMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getAlmacen(context, data) {
  return Vue.prototype.$axios.post('/api/list_almacen', data)
    .then((result) => {
      const data = result
      context.commit('setAlmacenList', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getAlmacenes(context, data) {
  return Vue.prototype.$axios.post('/api/get_almacenes', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveAlmacen(context, data) {
  return Vue.prototype.$axios.post('/api/create_almacen', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addAlmacen', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editAlmacen(context, data) {
  return Vue.prototype.$axios.put('/api/update_almacen/' + data.id, data)
    .then((result) => {
      context.commit('updateAlmacen', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteAlmacen(context, id) {
  return Vue.prototype.$axios.post('/api/delete_almacen/' + id)
    .then(() => {
      context.commit('deleteAlmacen', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      infoMessage("El almacen está activo")
      errorMessage(message.eRemoveError, err)
    })
}

export async function mostrar_ubicacion_geografica(context, data) {
  return Vue.prototype.$axios.post('/api/mostrar_ubicacion_geografica', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function mostrar_almacenes_categoria(context, data) {
  return Vue.prototype.$axios.post('/api/mostrar_almacenes_categoria', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function mostrar_encargados_capacitados(context, data) {
  return Vue.prototype.$axios.post('/api/mostrar_encargados_capacitados', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function mostrar_almacenes_inversion_mantenimiento(context, data) {
  return Vue.prototype.$axios.post('/api/mostrar_almacenes_inversion_mantenimiento', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function mostrar_almacenes_estadoconstructivo(context, data) {
  return Vue.prototype.$axios.post('/api/mostrar_almacenes_estadoconstructivo', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}
