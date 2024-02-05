import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.actividad_almacen, function (item) {
    return item.created_at
  })
}

export function setActividad_almacenList (state, payload) {
  state.actividad_almacen = payload
  orderDecs(state.actividad_almacen)
}

export function setActividad_almacenId (state, payload) {
  state.actividad_almacenObject = payload
}

export function addActividad_almacen (state, payload) {
  state.actividad_almacen.push(payload)
}

export function updateActividad_almacen (state, payload) {
  setActividad_almacenId(state, payload)
  const index = _.findIndex(state.actividad_almacen, function (item) {
    return item.id === state.actividad_almacenObject.id
  })
  state.actividad_almacen[index] = state.actividad_almacenObject
}

export function deleteActividad_almacen(state, id) {
  const index = state.actividad_almacen.findIndex(obj => obj.id === id)
  state.actividad_almacen.splice(index, 1)
}

export function setActividad_almacenTotal (state, payload) {
  state.actividad_almacenTotal = payload
}
