import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_vespecializado, function (item) {
    return item.created_at
  })
}

export function setTipo_vespecializadoList (state, payload) {
  state.tipo_vespecializado = payload
  orderDecs(state.tipo_vespecializado)
}

export function setTipo_vespecializadoId (state, payload) {
  state.tipo_vespecializadoObject = payload
}

export function addTipo_vespecializado (state, payload) {
  state.tipo_vespecializado.push(payload)
}

export function updateTipo_vespecializado (state, payload) {
  setTipo_vespecializadoId(state, payload)
  const index = _.findIndex(state.tipo_vespecializado, function (item) {
    return item.id === state.tipo_vespecializadoObject.id
  })
  state.tipo_vespecializado[index] = state.tipo_vespecializadoObject
}

export function deleteTipo_vespecializado(state, id) {
  const index = state.tipo_vespecializado.findIndex(obj => obj.id === id)
  state.tipo_vespecializado.splice(index, 1)
}

export function setTipo_vespecializadoTotal (state, payload) {
  state.tipo_vespecializadoTotal = payload
}
