import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.samtransporte, function (item) {
    return item.created_at
  })
}

export function setSamtransporteList (state, payload) {
  state.samtransporte = payload
  orderDecs(state.samtransporte)
}

export function setSamtransporteId (state, payload) {
  state.samtransporteObject = payload
}

export function addSamtransporte (state, payload) {
  state.samtransporte.push(payload)
}

export function updateSamtransporte (state, payload) {
  setSamtransporteId(state, payload)
  const index = _.findIndex(state.samtransporte, function (item) {
    return item.id === state.samtransporteObject.id
  })
  state.samtransporte[index] = state.samtransporteObject
}

export function deleteSamtransporte(state, id) {
  const index = state.samtransporte.findIndex(obj => obj.id === id)
  state.samtransporte.splice(index, 1)
}

export function setSamtransporteTotal (state, payload) {
  state.samtransporteTotal = payload
}
