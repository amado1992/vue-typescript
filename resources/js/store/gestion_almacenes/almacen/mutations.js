import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.almacen, function (item) {
    return item.created_at
  })
}

export function setAlmacenList (state, payload) {
  state.almacen = payload
  orderDecs(state.almacen)
}

export function setAlmacenId (state, payload) {
  state.almacenObject = payload
}

export function addAlmacen (state, payload) {
  state.almacen.push(payload)
}

export function updateAlmacen (state, payload) {
  setAlmacenId(state, payload)
  const index = _.findIndex(state.almacen, function (item) {
    return item.id === state.almacenObject.id
  })
  state.almacen[index] = state.almacenObject
}

export function deleteAlmacen(state, id) {
  const index = state.almacen.findIndex(obj => obj.id === id)
  state.almacen.splice(index, 1)
}

export function setAlmacenTotal (state, payload) {
  state.almacenTotal = payload
}
