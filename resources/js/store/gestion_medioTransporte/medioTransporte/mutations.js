import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.medioTransporte, function (item) {
    return item.created_at
  })
}

export function setMedioTransporteList (state, payload) {
  state.medioTransporte = payload
  orderDecs(state.medioTransporte)
}

export function setMedioTransporteId (state, payload) {
  state.medioTransporte = payload
}

export function addMedioTransporte(state, payload) {
  state.medioTransporte.push(payload)
}

export function updateMedioTransporte (state, payload) {
    setMedioTransporteId(state, payload)
  const index = _.findIndex(state.medioTransporte, function (item) {
    return item.id === state.medioTransporte.id
  })
  state.medioTransporte[index] = state.medioTransporteObject
}

export function deleteMedioTransporte(state, id) {
  const index = state.medioTransporte.findIndex(obj => obj.id === id)
  state.medioTransporte.splice(index, 1)
}

