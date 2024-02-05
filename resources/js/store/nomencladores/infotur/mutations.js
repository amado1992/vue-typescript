import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.infotur, function (item) {
    return item.created_at
  })
}

export function setInfoturList (state, payload) {
  state.infotur = payload
  orderDecs(state.infotur)
}

export function setInfoturId (state, payload) {
  state.infoturObject = payload
}

export function addInfotur (state, payload) {
  state.infotur.push(payload)
}

export function updateInfotur (state, payload) {
  setInfoturId(state, payload)
  const index = _.findIndex(state.infotur, function (item) {
    return item.id === state.infoturObject.id
  })
  state.infotur[index] = state.infoturObject
}

export function deleteInfotur(state, id) {
  const index = state.infotur.findIndex(obj => obj.id === id)
  state.infotur.splice(index, 1)
}

export function setInfoturTotal (state, payload) {
  state.infoturTotal = payload
}
