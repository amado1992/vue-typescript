import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.distribucion, function (item) {
    return item.created_at
  })
}

export function setDistribucionList (state, payload) {
  state.distribucion = payload
  orderDecs(state.distribucion)
}

export function setDistribucionId (state, payload) {
  state.distribucionObject = payload
}

export function addDistribucion (state, payload) {
  state.distribucion.push(payload)
}

export function updateDistribucion (state, payload) {
  setDistribucionId(state, payload)
  const index = _.findIndex(state.distribucion, function (item) {
    return item.id === state.distribucionObject.id
  })
  state.distribucion[index] = state.distribucionObject
}

export function deleteDistribucion(state, id) {
  const index = state.distribucion.findIndex(obj => obj.id === id)
  state.distribucion.splice(index, 1)
}

export function setDistribucionTotal (state, payload) {
  state.distribucionTotal = payload
}
