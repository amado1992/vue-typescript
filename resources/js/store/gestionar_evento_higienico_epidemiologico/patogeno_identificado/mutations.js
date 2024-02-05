import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.patogeno_identificado, function (item) {
    return item.created_at
  })
}

export function setPatogeno_identificadoList (state, payload) {
  state.patogeno_identificado = payload
  orderDecs(state.patogeno_identificado)
}

export function setPatogeno_identificadoId (state, payload) {
  state.patogeno_identificadoObject = payload
}

export function addPatogeno_identificado (state, payload) {
  state.patogeno_identificado.push(payload)
}

export function updatePatogeno_identificado (state, payload) {
  setPatogeno_identificadoId(state, payload)
  const index = _.findIndex(state.patogeno_identificado, function (item) {
    return item.id === state.patogeno_identificadoObject.id
  })
  state.patogeno_identificado[index] = state.patogeno_identificadoObject
}

export function deletePatogeno_identificado(state, id) {
  const index = state.patogeno_identificado.findIndex(obj => obj.id === id)
  state.patogeno_identificado.splice(index, 1)
}

export function setPatogeno_identificadoTotal (state, payload) {
  state.patogeno_identificadoTotal = payload
}
