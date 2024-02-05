import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.gestion_accidente, function (item) {
    return item.created_at
  })
}

export function setGestion_accidenteList (state, payload) {
  state.gestion_accidente = payload
  orderDecs(state.gestion_accidente)
}

export function setGestion_accidenteId (state, payload) {
  state.gestion_accidente = payload
}

export function addGestion_accidente(state, payload) {
  state.gestion_accidente.push(payload)
}

export function updateGestion_accidente (state, payload) {
    setGestion_accidenteId(state, payload)
  const index = _.findIndex(state.gestion_accidente, function (item) {
    return item.id === state.gestion_accidente.id
  })
  state.gestion_accidente[index] = state.gestion_accidenteObject
}

export function deleteGestion_accidente(state, id) {
  const index = state.gestion_accidente.findIndex(obj => obj.id === id)
  state.gestion_accidente.splice(index, 1)
}

