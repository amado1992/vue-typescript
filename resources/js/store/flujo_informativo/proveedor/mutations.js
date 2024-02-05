import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.proveedor, function (item) {
    return item.created_at
  })
}

export function setProveedorList (state, payload) {
  state.proveedor = payload
  orderDecs(state.proveedor)
}

export function setProveedorId (state, payload) {
  state.proveedorObject = payload
}

export function addProveedor (state, payload) {
  state.proveedor.push(payload)
}

export function updateProveedor (state, payload) {
  setProveedorId(state, payload)
  const index = _.findIndex(state.proveedor, function (item) {
    return item.id === state.proveedorObject.id
  })
  state.proveedor[index] = state.proveedorObject
}

export function deleteProveedor(state, id) {
  const index = state.proveedor.findIndex(obj => obj.id === id)
  state.proveedor.splice(index, 1)
}

export function setProveedorTotal (state, payload) {
  state.proveedorTotal = payload
}
