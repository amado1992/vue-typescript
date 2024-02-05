import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_caso, function (item) {
    return item.created_at
  })
}

export function setTipo_casoList (state, payload) {
  state.tipo_caso = payload
  orderDecs(state.tipo_caso)
}

export function setTipo_casoId (state, payload) {
  state.tipo_casoObject = payload
}

export function addTipo_caso (state, payload) {
  state.tipo_caso.push(payload)
}

export function updateTipo_caso (state, payload) {
  setTipo_casoId(state, payload)
  const index = _.findIndex(state.tipo_caso, function (item) {
    return item.id === state.tipo_casoObject.id
  })
  state.tipo_caso[index] = state.tipo_casoObject
}

export function deleteTipo_caso(state, id) {
  const index = state.tipo_caso.findIndex(obj => obj.id === id)
  state.tipo_caso.splice(index, 1)
}

export function setTipo_casoTotal (state, payload) {
  state.tipo_casoTotal = payload
}
