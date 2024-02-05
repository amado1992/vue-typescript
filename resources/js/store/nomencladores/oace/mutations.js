import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.oace, function (item) {
    return item.created_at
  })
}

export function setOaceList (state, payload) {
  state.oace = payload
  orderDecs(state.oace)
}

export function setOaceId (state, payload) {
  state.oaceObject = payload
}

export function addOace (state, payload) {
  state.oace.push(payload)
}

export function updateOace (state, payload) {
  setOaceId(state, payload)
  const index = _.findIndex(state.oace, function (item) {
    return item.id === state.oaceObject.id
  })
  state.oace[index] = state.oaceObject
}

export function deleteOace(state, id) {
  const index = state.oace.findIndex(obj => obj.id === id)
  state.oace.splice(index, 1)
}

export function setOaceTotal (state, payload) {
  state.oaceTotal = payload
}
