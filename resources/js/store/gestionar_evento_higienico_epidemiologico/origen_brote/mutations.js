import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.origen_brote, function (item) {
    return item.created_at
  })
}

export function setOrigen_broteList (state, payload) {
  state.origen_brote = payload
  orderDecs(state.origen_brote)
}

export function setOrigen_broteId (state, payload) {
  state.origen_broteObject = payload
}

export function addOrigen_brote (state, payload) {
  state.origen_brote.push(payload)
}

export function updateOrigen_brote (state, payload) {
  setOrigen_broteId(state, payload)
  const index = _.findIndex(state.origen_brote, function (item) {
    return item.id === state.origen_broteObject.id
  })
  state.origen_brote[index] = state.origen_broteObject
}

export function deleteOrigen_brote(state, id) {
  const index = state.origen_brote.findIndex(obj => obj.id === id)
  state.origen_brote.splice(index, 1)
}

export function setOrigen_broteTotal (state, payload) {
  state.origen_broteTotal = payload
}
