import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tcmtransporte, function (item) {
    return item.created_at
  })
}

export function setTcmtransporteList (state, payload) {
  state.tcmtransporte = payload
  orderDecs(state.tcmtransporte)
}

export function setTcmtransporteId (state, payload) {
  state.tcmtransporteObject = payload
}

export function addTcmtransporte (state, payload) {
  state.tcmtransporte.push(payload)
}

export function updateTcmtransporte (state, payload) {
  setTcmtransporteId(state, payload)
  const index = _.findIndex(state.tcmtransporte, function (item) {
    return item.id === state.tcmtransporteObject.id
  })
  state.tcmtransporte[index] = state.tcmtransporteObject
}

export function deleteTcmtransporte(state, id) {
  const index = state.tcmtransporte.findIndex(obj => obj.id === id)
  state.tcmtransporte.splice(index, 1)
}

export function setTcmtransporteTotal (state, payload) {
  state.tcmtransporteTotal = payload
}
