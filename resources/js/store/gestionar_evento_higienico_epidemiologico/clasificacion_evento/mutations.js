import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.clasificacion_evento, function (item) {
    return item.created_at
  })
}

export function setClasificacion_eventoList (state, payload) {
  state.clasificacion_evento = payload
  orderDecs(state.clasificacion_evento)
}

export function setClasificacion_eventoId (state, payload) {
  state.clasificacion_eventoObject = payload
}

export function addClasificacion_evento (state, payload) {
  state.clasificacion_evento.push(payload)
}

export function updateClasificacion_evento (state, payload) {
  setClasificacion_eventoId(state, payload)
  const index = _.findIndex(state.clasificacion_evento, function (item) {
    return item.id === state.clasificacion_eventoObject.id
  })
  state.clasificacion_evento[index] = state.clasificacion_eventoObject
}

export function deleteClasificacion_evento(state, id) {
  const index = state.clasificacion_evento.findIndex(obj => obj.id === id)
  state.clasificacion_evento.splice(index, 1)
}

export function setClasificacion_eventoTotal (state, payload) {
  state.clasificacion_eventoTotal = payload
}
