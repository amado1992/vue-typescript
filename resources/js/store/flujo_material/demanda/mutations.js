import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.demanda, function (item) {
    return item.created_at
  })
}

export function setDemandaList (state, payload) {
  state.demanda = payload
  orderDecs(state.demanda)
}

export function setDemandaId (state, payload) {
  state.demandaObject = payload
}

export function addDemanda (state, payload) {
  state.demanda.push(payload)
}

export function updateDemanda (state, payload) {
  setDemandaId(state, payload)
  const index = _.findIndex(state.demanda, function (item) {
    return item.id === state.demandaObject.id
  })
  state.demanda[index] = state.demandaObject
}

export function deleteDemanda(state, id) {
  const index = state.demanda.findIndex(obj => obj.id === id)
  state.demanda.splice(index, 1)
}

export function setDemandaTotal (state, payload) {
  state.demandaTotal = payload
}
