import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipo_vaservicio, function (item) {
    return item.created_at
  })
}

export function setTipo_vaservicioList (state, payload) {
  state.tipo_vaservicio = payload
  orderDecs(state.tipo_vaservicio)
}

export function setTipo_vaservicioId (state, payload) {
  state.tipo_vaservicioObject = payload
}

export function addTipo_vaservicio (state, payload) {
  state.tipo_vaservicio.push(payload)
}

export function updateTipo_vaservicio (state, payload) {
  setTipo_vaservicioId(state, payload)
  const index = _.findIndex(state.tipo_vaservicio, function (item) {
    return item.id === state.tipo_vaservicioObject.id
  })
  state.tipo_vaservicio[index] = state.tipo_vaservicioObject
}

export function deleteTipo_vaservicio(state, id) {
  const index = state.tipo_vaservicio.findIndex(obj => obj.id === id)
  state.tipo_vaservicio.splice(index, 1)
}

export function setTipo_vaservicioTotal (state, payload) {
  state.tipo_vaservicioTotal = payload
}
