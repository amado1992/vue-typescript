import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.provincia, function (item) {
    return item.created_at
  })
}

export function setProvinciaList (state, payload) {
  state.provincia = payload
  orderDecs(state.provincia)
}

export function setProvinciaId (state, payload) {
  state.provinciaObject = payload
}

export function addProvincia (state, payload) {
  state.provincia.push(payload)
}

export function updateProvincia (state, payload) {
  setProvinciaId(state, payload)
  const index = _.findIndex(state.provincia, function (item) {
    return item.id === state.provinciaObject.id
  })
  state.provincia[index] = state.provinciaObject
}

export function deleteProvincia(state, id) {
  const index = state.provincia.findIndex(obj => obj.id === id)
  state.provincia.splice(index, 1)
}

export function setProvinciaTotal (state, payload) {
  state.provinciaTotal = payload
}
