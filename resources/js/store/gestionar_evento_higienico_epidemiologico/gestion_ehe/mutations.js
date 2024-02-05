import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.eventoEHE, function (item) {
    return item.created_at
  })
}

export function setEventoEHEList (state, payload) {
  state.eventoEHE = payload
  orderDecs(state.eventoEHE)
}

export function setEventoEHEId (state, payload) {
  state.eventoEHE = payload
}

export function addEventoEHE(state, payload) {
  state.eventoEHE.push(payload)
}

export function updateEventoEHE (state, payload) {
    setEventoEHEId(state, payload)
  const index = _.findIndex(state.eventoEHE, function (item) {
    return item.id === state.eventoEHE.id
  })
  state.eventoEHE[index] = state.eventoEHEObject
}

export function deleteEventoEHE(state, id) {
  const index = state.eventoEHE.findIndex(obj => obj.id === id)
  state.eventoEHE.splice(index, 1)
}

