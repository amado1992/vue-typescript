import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.causa_accidente, function (item) {
    return item.created_at
  })
}

export function setCausa_accidenteList (state, payload) {
  state.causa_accidente = payload
  orderDecs(state.causa_accidente)
}

export function setCausa_accidenteId (state, payload) {
  state.causa_accidenteObject = payload
}

export function addCausa_accidente (state, payload) {
  state.causa_accidente.push(payload)
}

export function updateCausa_accidente (state, payload) {
  setCausa_accidenteId(state, payload)
  const index = _.findIndex(state.causa_accidente, function (item) {
    return item.id === state.causa_accidenteObject.id
  })
  state.causa_accidente[index] = state.causa_accidenteObject
}

export function deleteCausa_accidente(state, id) {
  const index = state.causa_accidente.findIndex(obj => obj.id === id)
  state.causa_accidente.splice(index, 1)
}

export function setCausa_accidenteTotal (state, payload) {
  state.causa_accidenteTotal = payload
}
