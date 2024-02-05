import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.ueb, function (item) {
    return item.created_at
  })
}

export function setUebList (state, payload) {
  state.ueb = payload
  orderDecs(state.ueb)
}

export function setUebId (state, payload) {
  state.uebObject = payload
}

export function addUeb (state, payload) {
  state.ueb.push(payload)
}

export function updateUeb (state, payload) {
  setUebId(state, payload)
  const index = _.findIndex(state.ueb, function (item) {
    return item.id === state.uebObject.id
  })
  state.ueb[index] = state.uebObject
}

export function deleteUeb(state, id) {
  const index = state.ueb.findIndex(obj => obj.id === id)
  state.ueb.splice(index, 1)
}

export function setUebTotal (state, payload) {
  state.uebTotal = payload
}
