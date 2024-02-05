import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.etmtransporte, function (item) {
    return item.created_at
  })
}

export function setEtmtransporteList (state, payload) {
  state.etmtransporte = payload
  orderDecs(state.etmtransporte)
}

export function setEtmtransporteId (state, payload) {
  state.etmtransporteObject = payload
}

export function addEtmtransporte (state, payload) {
  state.etmtransporte.push(payload)
}

export function updateEtmtransporte (state, payload) {
  setEtmtransporteId(state, payload)
  const index = _.findIndex(state.etmtransporte, function (item) {
    return item.id === state.etmtransporteObject.id
  })
  state.etmtransporte[index] = state.etmtransporteObject
}

export function deleteEtmtransporte(state, id) {
  const index = state.etmtransporte.findIndex(obj => obj.id === id)
  state.etmtransporte.splice(index, 1)
}

export function setEtmtransporteTotal (state, payload) {
  state.etmtransporteTotal = payload
}
