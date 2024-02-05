import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.fintur, function (item) {
    return item.created_at
  })
}

export function setFinturList (state, payload) {
  state.fintur = payload
  orderDecs(state.fintur)
}

export function setFinturId (state, payload) {
  state.finturObject = payload
}

export function addFintur (state, payload) {
  state.fintur.push(payload)
}

export function updateFintur (state, payload) {
  setFinturId(state, payload)
  const index = _.findIndex(state.fintur, function (item) {
    return item.id === state.finturObject.id
  })
  state.fintur[index] = state.finturObject
}

export function deleteFintur(state, id) {
  const index = state.fintur.findIndex(obj => obj.id === id)
  state.fintur.splice(index, 1)
}

export function setFinturTotal (state, payload) {
  state.finturTotal = payload
}
