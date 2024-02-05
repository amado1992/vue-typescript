import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.agente_causal_caso, function (item) {
    return item.created_at
  })
}

export function setAgente_causal_casoList (state, payload) {
  state.agente_causal_caso = payload
  orderDecs(state.agente_causal_caso)
}

export function setAgente_causal_casoId (state, payload) {
  state.agente_causal_casoObject = payload
}

export function addAgente_causal_caso (state, payload) {
  state.agente_causal_caso.push(payload)
}

export function updateAgente_causal_caso (state, payload) {
  setAgente_causal_casoId(state, payload)
  const index = _.findIndex(state.agente_causal_caso, function (item) {
    return item.id === state.agente_causal_casoObject.id
  })
  state.agente_causal_caso[index] = state.agente_causal_casoObject
}

export function deleteAgente_causal_caso(state, id) {
  const index = state.agente_causal_caso.findIndex(obj => obj.id === id)
  state.agente_causal_caso.splice(index, 1)
}

export function setAgente_causal_casoTotal (state, payload) {
  state.agente_causal_casoTotal = payload
}
