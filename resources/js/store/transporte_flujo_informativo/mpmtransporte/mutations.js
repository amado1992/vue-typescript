import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.mpmtransporte, function (item) {
    return item.created_at
  })
}

export function setMpmtransporteList (state, payload) {
  state.mpmtransporte = payload
  orderDecs(state.mpmtransporte)
}

export function setMpmtransporteId (state, payload) {
  state.mpmtransporteObject = payload
}

export function addMpmtransporte (state, payload) {
  state.mpmtransporte.push(payload)
}

export function updateMpmtransporte (state, payload) {
  setMpmtransporteId(state, payload)
  const index = _.findIndex(state.mpmtransporte, function (item) {
    return item.id === state.mpmtransporteObject.id
  })
  state.mpmtransporte[index] = state.mpmtransporteObject
}

export function deleteMpmtransporte(state, id) {
  const index = state.mpmtransporte.findIndex(obj => obj.id === id)
  state.mpmtransporte.splice(index, 1)
}

export function setMpmtransporteTotal (state, payload) {
  state.mpmtransporteTotal = payload
}
