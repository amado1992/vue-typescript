import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.cadena_hotelera, function (item) {
    return item.created_at
  })
}

export function setCadena_hoteleraList (state, payload) {
  state.cadena_hotelera = payload
  orderDecs(state.cadena_hotelera)
}

export function setCadena_hoteleraId (state, payload) {
  state.cadena_hoteleraObject = payload
}

export function addCadena_hotelera (state, payload) {
  state.cadena_hotelera.push(payload)
}

export function updateCadena_hotelera (state, payload) {
  setCadena_hoteleraId(state, payload)
  const index = _.findIndex(state.cadena_hotelera, function (item) {
    return item.id === state.cadena_hoteleraObject.id
  })
  state.cadena_hotelera[index] = state.cadena_hoteleraObject
}

export function deleteCadena_hotelera(state, id) {
  const index = state.cadena_hotelera.findIndex(obj => obj.id === id)
  state.cadena_hotelera.splice(index, 1)
}

export function setCadena_hoteleraTotal (state, payload) {
  state.cadena_hoteleraTotal = payload
}
