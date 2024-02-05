import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.producto, function (item) {
    return item.created_at
  })
}

export function setProductoList (state, payload) {
  state.producto = payload
  orderDecs(state.producto)
}

export function setProductoId (state, payload) {
  state.productoObject = payload
}

export function addProducto (state, payload) {
  state.producto.push(payload)
}

export function updateProducto (state, payload) {
  setProductoId(state, payload)
  const index = _.findIndex(state.producto, function (item) {
    return item.id === state.productoObject.id
  })
  state.producto[index] = state.productoObject
}

export function deleteProducto(state, id) {
  const index = state.producto.findIndex(obj => obj.id === id)
  state.producto.splice(index, 1)
}

export function setProductoTotal (state, payload) {
  state.productoTotal = payload
}
