import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.ubicacion_memnautico, function (item) {
    return item.created_at
  })
}

export function setUbicacion_memnauticoList (state, payload) {
  state.ubicacion_memnautico = payload
  orderDecs(state.ubicacion_memnautico)
}

export function setUbicacion_memnauticoId (state, payload) {
  state.ubicacion_memnauticoObject = payload
}

export function addUbicacion_memnautico (state, payload) {
  state.ubicacion_memnautico.push(payload)
}

export function updateUbicacion_memnautico (state, payload) {
  setUbicacion_memnauticoId(state, payload)
  const index = _.findIndex(state.ubicacion_memnautico, function (item) {
    return item.id === state.ubicacion_memnauticoObject.id
  })
  state.ubicacion_memnautico[index] = state.ubicacion_memnauticoObject
}

export function deleteUbicacion_memnautico(state, id) {
  const index = state.ubicacion_memnautico.findIndex(obj => obj.id === id)
  state.ubicacion_memnautico.splice(index, 1)
}

export function setUbicacion_memnauticoTotal (state, payload) {
  state.ubicacion_memnauticoTotal = payload
}
