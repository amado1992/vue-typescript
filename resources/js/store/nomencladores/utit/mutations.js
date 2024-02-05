import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.utit, function (item) {
    return item.created_at
  })
}

export function setUtitList (state, payload) {
  state.utit = payload
  orderDecs(state.utit)
}

export function setUtitId (state, payload) {
  state.utitObject = payload
}

export function addUtit (state, payload) {
  state.utit.push(payload)
}

export function updateUtit (state, payload) {
  setUtitId(state, payload)
  const index = _.findIndex(state.utit, function (item) {
    return item.id === state.utitObject.id
  })
  state.utit[index] = state.utitObject
}

export function deleteUtit(state, id) {
  const index = state.utit.findIndex(obj => obj.id === id)
  state.utit.splice(index, 1)
}

export function setUtitTotal (state, payload) {
  state.utitTotal = payload
}
