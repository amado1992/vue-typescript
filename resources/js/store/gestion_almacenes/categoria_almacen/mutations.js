import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.categoria_almacen, function (item) {
    return item.created_at
  })
}

export function setCategoria_almacenList (state, payload) {
  state.categoria_almacen = payload
  orderDecs(state.categoria_almacen)
}

export function setCategoria_almacenId (state, payload) {
  state.categoria_almacenObject = payload
}

export function addCategoria_almacen (state, payload) {
  state.categoria_almacen.push(payload)
}

export function updateCategoria_almacen (state, payload) {
  setCategoria_almacenId(state, payload)
  const index = _.findIndex(state.categoria_almacen, function (item) {
    return item.id === state.categoria_almacenObject.id
  })
  state.categoria_almacen[index] = state.categoria_almacenObject
}

export function deleteCategoria_almacen(state, id) {
  const index = state.categoria_almacen.findIndex(obj => obj.id === id)
  state.categoria_almacen.splice(index, 1)
}

export function setCategoria_almacenTotal (state, payload) {
  state.categoria_almacenTotal = payload
}
