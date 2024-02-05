import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_emnautico, function (item) {
    return item.created_at
  })
}

export function setTipo_emnauticoList (state, payload) {
  state.tipo_emnautico = payload
  orderDecs(state.tipo_emnautico)
}

export function setTipo_emnauticoId (state, payload) {
  state.tipo_emnauticoObject = payload
}

export function addTipo_emnautico (state, payload) {
  state.tipo_emnautico.push(payload)
}

export function updateTipo_emnautico (state, payload) {
  setTipo_emnauticoId(state, payload)
  const index = _.findIndex(state.tipo_emnautico, function (item) {
    return item.id === state.tipo_emnauticoObject.id
  })
  state.tipo_emnautico[index] = state.tipo_emnauticoObject
}

export function deleteTipo_emnautico(state, id) {
  const index = state.tipo_emnautico.findIndex(obj => obj.id === id)
  state.tipo_emnautico.splice(index, 1)
}

export function setTipo_emnauticoTotal (state, payload) {
  state.tipo_emnauticoTotal = payload
}
