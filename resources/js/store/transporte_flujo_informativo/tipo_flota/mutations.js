import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_flota, function (item) {
    return item.created_at
  })
}

export function setTipo_flotaList (state, payload) {
  state.tipo_flota = payload
  orderDecs(state.tipo_flota)
}

export function setTipo_flotaId (state, payload) {
  state.tipo_flotaObject = payload
}

export function addTipo_flota (state, payload) {
  state.tipo_flota.push(payload)
}

export function updateTipo_flota (state, payload) {
  setTipo_flotaId(state, payload)
  const index = _.findIndex(state.tipo_flota, function (item) {
    return item.id === state.tipo_flotaObject.id
  })
  state.tipo_flota[index] = state.tipo_flotaObject
}

export function deleteTipo_flota(state, id) {
  const index = state.tipo_flota.findIndex(obj => obj.id === id)
  state.tipo_flota.splice(index, 1)
}

export function setTipo_flotaTotal (state, payload) {
  state.tipo_flotaTotal = payload
}
