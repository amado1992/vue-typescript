import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.temperatura, function (item) {
    return item.created_at
  })
}

export function setTemperaturaList (state, payload) {
  state.temperatura = payload
  orderDecs(state.temperatura)
}

export function setTemperaturaId (state, payload) {
  state.temperaturaObject = payload
}

export function addTemperatura (state, payload) {
  state.temperatura.push(payload)
}

export function updateTemperatura (state, payload) {
  setTemperaturaId(state, payload)
  const index = _.findIndex(state.temperatura, function (item) {
    return item.id === state.temperaturaObject.id
  })
  state.temperatura[index] = state.temperaturaObject
}

export function deleteTemperatura(state, id) {
  const index = state.temperatura.findIndex(obj => obj.id === id)
  state.temperatura.splice(index, 1)
}

export function setTemperaturaTotal (state, payload) {
  state.temperaturaTotal = payload
}
