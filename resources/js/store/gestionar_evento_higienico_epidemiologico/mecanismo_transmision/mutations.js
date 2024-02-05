import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.mecanismo_transmision, function (item) {
    return item.created_at
  })
}

export function setMecanismo_transmisionList (state, payload) {
  state.mecanismo_transmision = payload
  orderDecs(state.mecanismo_transmision)
}

export function setMecanismo_transmisionId (state, payload) {
  state.mecanismo_transmisionObject = payload
}

export function addMecanismo_transmision (state, payload) {
  state.mecanismo_transmision.push(payload)
}

export function updateMecanismo_transmision (state, payload) {
  setMecanismo_transmisionId(state, payload)
  const index = _.findIndex(state.mecanismo_transmision, function (item) {
    return item.id === state.mecanismo_transmisionObject.id
  })
  state.mecanismo_transmision[index] = state.mecanismo_transmisionObject
}

export function deleteMecanismo_transmision(state, id) {
  const index = state.mecanismo_transmision.findIndex(obj => obj.id === id)
  state.mecanismo_transmision.splice(index, 1)
}

export function setMecanismo_transmisionTotal (state, payload) {
  state.mecanismo_transmisionTotal = payload
}
