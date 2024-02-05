import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.victima_accidente, function (item) {
    return item.created_at
  })
}

export function setVictima_accidenteList (state, payload) {
  state.victima_accidente = payload
  orderDecs(state.victima_accidente)
}

export function setVictima_accidenteId (state, payload) {
  state.victima_accidenteObject = payload
}

export function addVictima_accidente (state, payload) {
  state.victima_accidente.push(payload)
}

export function updateVictima_accidente (state, payload) {
  setVictima_accidenteId(state, payload)
  const index = _.findIndex(state.victima_accidente, function (item) {
    return item.id === state.victima_accidenteObject.id
  })
  state.victima_accidente[index] = state.victima_accidenteObject
}

export function deleteVictima_accidente(state, id) {
  const index = state.victima_accidente.findIndex(obj => obj.id === id)
  state.victima_accidente.splice(index, 1)
}

export function setVictima_accidenteTotal (state, payload) {
  state.victima_accidenteTotal = payload
}
