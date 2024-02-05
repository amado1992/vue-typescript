import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_muestra, function (item) {
    return item.created_at
  })
}

export function setTipo_muestraList (state, payload) {
  state.tipo_muestra = payload
  orderDecs(state.tipo_muestra)
}

export function setTipo_muestraId (state, payload) {
  state.tipo_muestraObject = payload
}

export function addTipo_muestra (state, payload) {
  state.tipo_muestra.push(payload)
}

export function updateTipo_muestra (state, payload) {
  setTipo_muestraId(state, payload)
  const index = _.findIndex(state.tipo_muestra, function (item) {
    return item.id === state.tipo_muestraObject.id
  })
  state.tipo_muestra[index] = state.tipo_muestraObject
}

export function deleteTipo_muestra(state, id) {
  const index = state.deteccion.findIndex(obj => obj.id === id)
  state.tipo_muestra.splice(index, 1)
}

export function setTipo_muestraTotal (state, payload) {
  state.tipo_muestraTotal = payload
}
