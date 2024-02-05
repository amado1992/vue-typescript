import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.onit, function (item) {
    return item.created_at
  })
}

export function setOnitList (state, payload) {
  state.onit = payload
  orderDecs(state.onit)
}

export function setOnitId (state, payload) {
  state.onitObject = payload
}

export function addOnit (state, payload) {
  state.onit.push(payload)
}

export function updateOnit (state, payload) {
  setOnitId(state, payload)
  const index = _.findIndex(state.onit, function (item) {
    return item.id === state.onitObject.id
  })
  state.onit[index] = state.onitObject
}

export function deleteOnit(state, id) {
  const index = state.onit.findIndex(obj => obj.id === id)
  state.onit.splice(index, 1)
}

export function setOnitTotal (state, payload) {
  state.onitTotal = payload
}
