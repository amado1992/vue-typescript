import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_brote, function (item) {
    return item.created_at
  })
}

export function setTipo_broteList (state, payload) {
  state.tipo_brote = payload
  orderDecs(state.tipo_brote)
}

export function setTipo_broteId (state, payload) {
  state.tipo_broteObject = payload
}

export function addTipo_brote (state, payload) {
  state.tipo_brote.push(payload)
}

export function updateTipo_brote (state, payload) {
  setTipo_broteId(state, payload)
  const index = _.findIndex(state.tipo_brote, function (item) {
    return item.id === state.tipo_broteObject.id
  })
  state.tipo_brote[index] = state.tipo_broteObject
}

export function deleteTipo_brote(state, id) {
  const index = state.tipo_brote.findIndex(obj => obj.id === id)
  state.tipo_brote.splice(index, 1)
}

export function setTipo_broteTotal (state, payload) {
  state.tipo_broteTotal = payload
}
