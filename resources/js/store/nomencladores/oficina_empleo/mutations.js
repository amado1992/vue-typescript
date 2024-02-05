import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.oficina_empleo, function (item) {
    return item.created_at
  })
}

export function setOficina_empleoList (state, payload) {
  state.oficina_empleo = payload
  orderDecs(state.oficina_empleo)
}

export function setOficina_empleoId (state, payload) {
  state.oficina_empleoObject = payload
}

export function addOficina_empleo (state, payload) {
  state.oficina_empleo.push(payload)
}

export function updateOficina_empleo (state, payload) {
  setOficina_empleoId(state, payload)
  const index = _.findIndex(state.oficina_empleo, function (item) {
    return item.id === state.oficina_empleoObject.id
  })
  state.oficina_empleo[index] = state.oficina_empleoObject
}

export function deleteOficina_empleo(state, id) {
  const index = state.oficina_empleo.findIndex(obj => obj.id === id)
  state.oficina_empleo.splice(index, 1)
}

export function setOficina_empleoTotal (state, payload) {
  state.oficina_empleoTotal = payload
}
