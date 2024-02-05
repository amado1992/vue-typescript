import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.deteccion, function (item) {
    return item.created_at
  })
}

export function setDeteccionList (state, payload) {
  state.deteccion = payload
  orderDecs(state.deteccion)
}

export function setDeteccionId (state, payload) {
  state.deteccionObject = payload
}

export function addDeteccion (state, payload) {
  state.deteccion.push(payload)
}

export function updateDeteccion (state, payload) {
  setDeteccionId(state, payload)
  const index = _.findIndex(state.deteccion, function (item) {
    return item.id === state.deteccionObject.id
  })
  state.deteccion[index] = state.deteccionObject
}

export function deleteDeteccion(state, id) {
  const index = state.deteccion.findIndex(obj => obj.id === id)
  state.deteccion.splice(index, 1)
}

export function setDeteccionTotal (state, payload) {
  state.deteccionTotal = payload
}
