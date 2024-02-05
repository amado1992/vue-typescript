import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.clasificacion_accidente, function (item) {
    return item.created_at
  })
}

export function setClasificacion_accidenteList (state, payload) {
  state.clasificacion_accidente = payload
  orderDecs(state.clasificacion_accidente)
}

export function setClasificacion_accidenteId (state, payload) {
  state.clasificacion_accidenteObject = payload
}

export function addClasificacion_accidente (state, payload) {
  state.clasificacion_accidente.push(payload)
}

export function updateClasificacion_accidente (state, payload) {
  setClasificacion_accidenteId(state, payload)
  const index = _.findIndex(state.clasificacion_accidente, function (item) {
    return item.id === state.clasificacion_accidenteObject.id
  })
  state.clasificacion_accidente[index] = state.clasificacion_accidenteObject
}

export function deleteClasificacion_accidente(state, id) {
  const index = state.clasificacion_accidente.findIndex(obj => obj.id === id)
  state.clasificacion_accidente.splice(index, 1)
}

export function setClasificacion_accidenteTotal (state, payload) {
  state.clasificacion_accidenteTotal = payload
}
