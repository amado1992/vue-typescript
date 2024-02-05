import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.escuela_capacitacion, function (item) {
    return item.created_at
  })
}

export function setEscuela_capacitacionList (state, payload) {
  state.escuela_capacitacion = payload
  orderDecs(state.escuela_capacitacion)
}

export function setEscuela_capacitacionId (state, payload) {
  state.escuela_capacitacionObject = payload
}

export function addEscuela_capacitacion (state, payload) {
  state.escuela_capacitacion.push(payload)
}

export function updateEscuela_capacitacion (state, payload) {
  setEscuela_capacitacionId(state, payload)
  const index = _.findIndex(state.escuela_capacitacion, function (item) {
    return item.id === state.escuela_capacitacionObject.id
  })
  state.escuela_capacitacion[index] = state.escuela_capacitacionObject
}

export function deleteEscuela_capacitacion(state, id) {
  const index = state.escuela_capacitacion.findIndex(obj => obj.id === id)
  state.escuela_capacitacion.splice(index, 1)
}

export function setEscuela_capacitacionTotal (state, payload) {
  state.escuela_capacitacionTotal = payload
}
