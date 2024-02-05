import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.unidad_medida, function (item) {
    return item.created_at
  })
}

export function setUnidad_medidaList (state, payload) {
  state.unidad_medida = payload
  orderDecs(state.unidad_medida)
}

export function setUnidad_medidaId (state, payload) {
  state.unidad_medidaObject = payload
}

export function addUnidad_medida (state, payload) {
  state.unidad_medida.push(payload)
}

export function updateUnidad_medida (state, payload) {
  setUnidad_medidaId(state, payload)
  const index = _.findIndex(state.unidad_medida, function (item) {
    return item.id === state.unidad_medidaObject.id
  })
  state.unidad_medida[index] = state.unidad_medidaObject
}

export function deleteUnidad_medida(state, id) {
  const index = state.unidad_medida.findIndex(obj => obj.id === id)
  state.unidad_medida.splice(index, 1)
}

export function setUnidad_medidaTotal (state, payload) {
  state.unidad_medidaTotal = payload
}
