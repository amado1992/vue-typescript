import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.agente_causal_brote, function (item) {
    return item.created_at
  })
}

export function setAgente_causal_broteList (state, payload) {
  state.agente_causal_brote = payload
  orderDecs(state.agente_causal_brote)
}

export function setAgente_causal_broteId (state, payload) {
  state.agente_causal_broteObject = payload
}

export function addAgente_causal_brote (state, payload) {
  state.agente_causal_brote.push(payload)
}

export function updateAgente_causal_brote (state, payload) {
  setAgente_causal_broteId(state, payload)
  const index = _.findIndex(state.agente_causal_brote, function (item) {
    return item.id === state.agente_causal_broteObject.id
  })
  state.agente_causal_brote[index] = state.agente_causal_broteObject
}

export function deleteAgente_causal_brote(state, id) {
  const index = state.agente_causal_brote.findIndex(obj => obj.id === id)
  state.agente_causal_brote.splice(index, 1)
}

export function setAgente_causal_broteTotal (state, payload) {
  state.agente_causal_broteTotal = payload
}
