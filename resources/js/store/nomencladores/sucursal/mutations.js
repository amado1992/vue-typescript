import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.sucursal, function (item) {
    return item.created_at
  })
}

export function setSucursalList (state, payload) {
  state.sucursal = payload
  orderDecs(state.sucursal)
}

export function setSucursalId (state, payload) {
  state.sucursalObject = payload
}

export function addSucursal (state, payload) {
  state.sucursal.push(payload)
}

export function updateSucursal (state, payload) {
  setSucursalId(state, payload)
  const index = _.findIndex(state.sucursal, function (item) {
    return item.id === state.sucursalObject.id
  })
  state.sucursal[index] = state.sucursalObject
}

export function deleteSucursal(state, id) {
  const index = state.sucursal.findIndex(obj => obj.id === id)
  state.sucursal.splice(index, 1)
}

export function setSucursalTotal (state, payload) {
  state.sucursalTotal = payload
}
