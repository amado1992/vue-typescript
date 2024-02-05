import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_vturistico, function (item) {
    return item.created_at
  })
}

export function setTipo_vturisticoList (state, payload) {
  state.tipo_vturistico = payload
  orderDecs(state.tipo_vturistico)
}

export function setTipo_vturisticoId (state, payload) {
  state.tipo_vturisticoObject = payload
}

export function addTipo_vturistico (state, payload) {
  state.tipo_vturistico.push(payload)
}

export function updateTipo_vturistico (state, payload) {
  setTipo_vturisticoId(state, payload)
  const index = _.findIndex(state.tipo_vturistico, function (item) {
    return item.id === state.tipo_vturisticoObject.id
  })
  state.tipo_vturistico[index] = state.tipo_vturisticoObject
}

export function deleteTipo_vturistico(state, id) {
  const index = state.tipo_vturistico.findIndex(obj => obj.id === id)
  state.tipo_vturistico.splice(index, 1)
}

export function setTipo_vturisticoTotal (state, payload) {
  state.tipo_vturisticoTotal = payload
}
