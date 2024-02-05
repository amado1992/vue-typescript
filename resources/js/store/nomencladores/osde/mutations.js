import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.osde, function (item) {
    return item.created_at
  })
}

export function setOsdeList (state, payload) {
  state.osde = payload
  orderDecs(state.osde)
}

export function setOsdeId (state, payload) {
  state.osdeObject = payload
}

export function addOsde (state, payload) {
  state.osde.push(payload)
}

export function updateOsde (state, payload) {
  setOsdeId(state, payload)
  const index = _.findIndex(state.osde, function (item) {
    return item.id === state.osdeObject.id
  })
  state.osde[index] = state.osdeObject
}

export function deleteOsde(state, id) {
  const index = state.osde.findIndex(obj => obj.id === id)
  state.osde.splice(index, 1)
}

export function setOsdeTotal (state, payload) {
  state.osdeTotal = payload
}
