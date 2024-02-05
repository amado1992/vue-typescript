import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.detectado_por, function (item) {
    return item.created_at
  })
}

export function setDetectado_porList (state, payload) {
  state.detectado_por = payload
  orderDecs(state.detectado_por)
}

export function setDetectado_porId (state, payload) {
  state.detectado_porObject = payload
}

export function addDetectado_por (state, payload) {
  state.detectado_por.push(payload)
}

export function updateDetectado_por (state, payload) {
  setDetectado_porId(state, payload)
  const index = _.findIndex(state.detectado_por, function (item) {
    return item.id === state.detectado_porObject.id
  })
  state.detectado_por[index] = state.detectado_porObject
}

export function deleteDetectado_por(state, id) {
  const index = state.detectado_por.findIndex(obj => obj.id === id)
  state.detectado_por.splice(index, 1)
}

export function setDetectado_porTotal (state, payload) {
  state.detectado_porTotal = payload
}
