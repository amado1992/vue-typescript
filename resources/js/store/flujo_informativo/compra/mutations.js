import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.compra, function (item) {
    return item.created_at
  })
}

export function setCompraList (state, payload) {
  state.compra = payload
  orderDecs(state.compra)
}

export function setCompraId (state, payload) {
  state.compraObject = payload
}

export function addCompra (state, payload) {
  state.compra.push(payload)
}

export function updateCompra (state, payload) {
  setCompraId(state, payload)
  const index = _.findIndex(state.compra, function (item) {
    return item.id === state.compraObject.id
  })
  state.compra[index] = state.compraObject
}

export function deleteCompra(state, id) {
  const index = state.compra.findIndex(obj => obj.id === id)
  state.compra.splice(index, 1)
}

export function setCompraTotal (state, payload) {
  state.compraTotal = payload
}
