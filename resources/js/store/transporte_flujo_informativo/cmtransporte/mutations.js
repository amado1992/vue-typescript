import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.cmtransporte, function (item) {
    return item.created_at
  })
}

export function setCmtransporteList (state, payload) {
  state.cmtransporte = payload
  orderDecs(state.cmtransporte)
}

export function setCmtransporteId (state, payload) {
  state.cmtransporteObject = payload
}

export function addCmtransporte (state, payload) {
  state.cmtransporte.push(payload)
}

export function updateCmtransporte (state, payload) {
  setCmtransporteId(state, payload)
  const index = _.findIndex(state.cmtransporte, function (item) {
    return item.id === state.cmtransporteObject.id
  })
  state.cmtransporte[index] = state.cmtransporteObject
}

export function deleteCmtransporte(state, id) {
  const index = state.cmtransporte.findIndex(obj => obj.id === id)
  state.cmtransporte.splice(index, 1)
}

export function setCmtransporteTotal (state, payload) {
  state.cmtransporteTotal = payload
}
