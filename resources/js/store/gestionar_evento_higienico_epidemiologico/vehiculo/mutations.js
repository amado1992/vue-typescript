import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.vehiculo, function (item) {
    return item.created_at
  })
}

export function setVehiculoList (state, payload) {
  state.vehiculo = payload
  orderDecs(state.vehiculo)
}

export function setVehiculoId (state, payload) {
  state.vehiculoObject = payload
}

export function addVehiculo (state, payload) {
  state.vehiculo.push(payload)
}

export function updateVehiculo (state, payload) {
  setVehiculoId(state, payload)
  const index = _.findIndex(state.vehiculo, function (item) {
    return item.id === state.vehiculoObject.id
  })
  state.vehiculo[index] = state.vehiculoObject
}

export function deleteVehiculo(state, id) {
  const index = state.vehiculo.findIndex(obj => obj.id === id)
  state.vehiculo.splice(index, 1)
}

export function setVehiculoTotal (state, payload) {
  state.vehiculoTotal = payload
}
