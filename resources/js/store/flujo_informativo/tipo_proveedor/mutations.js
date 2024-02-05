import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_proveedor, function (item) {
    return item.created_at
  })
}

export function setTipo_proveedorList (state, payload) {
  state.tipo_proveedor = payload
  orderDecs(state.tipo_proveedor)
}

export function setTipo_proveedorId (state, payload) {
  state.tipo_proveedorObject = payload
}

export function addTipo_proveedor (state, payload) {
  state.tipo_proveedor.push(payload)
}

export function updateTipo_proveedor (state, payload) {
  setTipo_proveedorId(state, payload)
  const index = _.findIndex(state.tipo_proveedor, function (item) {
    return item.id === state.tipo_proveedorObject.id
  })
  state.tipo_proveedor[index] = state.tipo_proveedorObject
}

export function deleteTipo_proveedor(state, id) {
  const index = state.tipo_proveedor.findIndex(obj => obj.id === id)
  state.tipo_proveedor.splice(index, 1)
}

export function setTipo_proveedorTotal (state, payload) {
  state.tipo_proveedorTotal = payload
}
