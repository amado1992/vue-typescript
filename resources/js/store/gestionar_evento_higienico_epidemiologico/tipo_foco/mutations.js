import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_foco, function (item) {
    return item.created_at
  })
}

export function setTipo_focoList (state, payload) {
  state.tipo_foco = payload
  orderDecs(state.tipo_foco)
}

export function setTipo_focoId (state, payload) {
  state.tipo_focoObject = payload
}

export function addTipo_foco (state, payload) {
  state.tipo_foco.push(payload)
}

export function updateTipo_foco (state, payload) {
  setTipo_focoId(state, payload)
  const index = _.findIndex(state.tipo_foco, function (item) {
    return item.id === state.tipo_focoObject.id
  })
  state.tipo_foco[index] = state.tipo_focoObject
}

export function deleteTipo_foco(state, id) {
  const index = state.tipo_foco.findIndex(obj => obj.id === id)
  state.tipo_foco.splice(index, 1)
}

export function setTipo_focoTotal (state, payload) {
  state.tipo_focoTotal = payload
}
