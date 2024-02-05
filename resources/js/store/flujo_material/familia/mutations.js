import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.familia, function (item) {
    return item.created_at
  })
}

export function setFamiliaList (state, payload) {
  state.familia = payload
  orderDecs(state.familia)
}

export function setFamiliaId (state, payload) {
  state.familiaObject = payload
}

export function addFamilia (state, payload) {
  state.familia.push(payload)
}

export function updateFamilia (state, payload) {
  setFamiliaId(state, payload)
  const index = _.findIndex(state.familia, function (item) {
    return item.id === state.familiaObject.id
  })
  state.familia[index] = state.familiaObject
}

export function deleteFamilia(state, id) {
  const index = state.familia.findIndex(obj => obj.id === id)
  state.familia.splice(index, 1)
}

export function setFamiliaTotal (state, payload) {
  state.familiaTotal = payload
}
