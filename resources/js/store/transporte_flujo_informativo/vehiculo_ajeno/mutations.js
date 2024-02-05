import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.vehiculo_ajeno, function (item) {
    return item.created_at
  })
}

export function setVehiculo_ajenoList (state, payload) {
  state.vehiculo_ajeno = payload
  orderDecs(state.vehiculo_ajeno)
}

export function setVehiculo_ajenoId (state, payload) {
  state.vehiculo_ajenoObject = payload
}

export function addVehiculo_ajeno (state, payload) {
  state.vehiculo_ajeno.push(payload)
}

export function updateVehiculo_ajeno (state, payload) {
  setVehiculo_ajenoId(state, payload)
  const index = _.findIndex(state.vehiculo_ajeno, function (item) {
    return item.id === state.vehiculo_ajenoObject.id
  })
  state.vehiculo_ajeno[index] = state.vehiculo_ajenoObject
}

export function deleteVehiculo_ajeno(state, id) {
  const index = state.vehiculo_ajeno.findIndex(obj => obj.id === id)
  state.vehiculo_ajeno.splice(index, 1)
}

export function setVehiculo_ajenoTotal (state, payload) {
  state.vehiculo_ajenoTotal = payload
}
