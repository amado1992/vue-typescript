import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.encargado, function (item) {
    return item.created_at
  })
}

export function setEncargadoList (state, payload) {
  state.encargado = payload
  orderDecs(state.encargado)
}

export function setEncargadoId (state, payload) {
  state.encargadoObject = payload
}

export function addEncargado (state, payload) {
  state.encargado.push(payload)
}

export function updateEncargado (state, payload) {
  setEncargadoId(state, payload)
  const index = _.findIndex(state.encargado, function (item) {
    return item.id === state.encargadoObject.id
  })
  state.encargado[index] = state.encargadoObject
}

export function deleteEncargado(state, id) {
  const index = state.compra.findIndex(obj => obj.id === id)
  state.encargado.splice(index, 1)
}

export function setEncargadoTotal (state, payload) {
  state.encargadoTotal = payload
}
