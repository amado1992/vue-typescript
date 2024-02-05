import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_almacen, function (item) {
    return item.created_at
  })
}

export function setTipo_almacenList (state, payload) {
  state.tipo_almacen = payload
  orderDecs(state.tipo_almacen)
}

export function setTipo_almacenId (state, payload) {
  state.tipo_almacenObject = payload
}

export function addTipo_almacen (state, payload) {
  state.tipo_almacen.push(payload)
}

export function updateTipo_almacen (state, payload) {
  setTipo_almacenId(state, payload)
  const index = _.findIndex(state.tipo_almacen, function (item) {
    return item.id === state.tipo_almacenObject.id
  })
  state.tipo_almacen[index] = state.tipo_almacenObject
}

export function deleteTipo_almacen(state, id) {
  const index = state.tipo_almacen.findIndex(obj => obj.id === id)
  state.tipo_almacen.splice(index, 1)
}

export function setTipo_almacenTotal (state, payload) {
  state.tipo_almacenTotal = payload
}
