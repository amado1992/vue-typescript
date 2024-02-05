import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.km_recorridos, function (item) {
    return item.created_at
  })
}

export function setKm_recorridosList (state, payload) {
  state.km_recorridos = payload
  orderDecs(state.km_recorridos)
}

export function setKm_recorridosId (state, payload) {
  state.km_recorridosObject = payload
}

export function addKm_recorridos (state, payload) {
  state.km_recorridos.push(payload)
}

export function updateKm_recorridos (state, payload) {
  setKm_recorridosId(state, payload)
  const index = _.findIndex(state.km_recorridos, function (item) {
    return item.id === state.km_recorridosObject.id
  })
  state.km_recorridos[index] = state.km_recorridosObject
}

export function deleteKm_recorridos(state, id) {
  const index = state.km_recorridos.findIndex(obj => obj.id === id)
  state.km_recorridos.splice(index, 1)
}

export function setKm_recorridosTotal (state, payload) {
  state.km_recorridosTotal = payload
}
