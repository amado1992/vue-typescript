import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.delegacion_territorial, function (item) {
    return item.created_at
  })
}

export function setDelegacion_territorialList (state, payload) {
  state.delegacion_territorial = payload
  orderDecs(state.delegacion_territorial)
}

export function setDelegacion_territorialId (state, payload) {
  state.delegacion_territorialObject = payload
}

export function addDelegacion_territorial (state, payload) {
  state.delegacion_territorial.push(payload)
}

export function updateDelegacion_territorial (state, payload) {
  setDelegacion_territorialId(state, payload)
  const index = _.findIndex(state.delegacion_territorial, function (item) {
    return item.id === state.delegacion_territorialObject.id
  })
  state.delegacion_territorial[index] = state.delegacion_territorialObject
}

export function deleteDelegacion_territorial(state, id) {
  const index = state.delegacion_territorial.findIndex(obj => obj.id === id)
  state.delegacion_territorial.splice(index, 1)
}

export function setDelegacion_territorialTotal (state, payload) {
  state.delegacion_territorialTotal = payload
}
