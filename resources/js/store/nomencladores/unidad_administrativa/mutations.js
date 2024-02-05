import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.unidad_administrativa, function (item) {
    return item.created_at
  })
}

export function setUnidad_administrativaList (state, payload) {
  state.unidad_administrativa = payload
  orderDecs(state.unidad_administrativa)
}

export function setUnidad_administrativaId (state, payload) {
  state.unidad_administrativaObject = payload
}

export function addUnidad_administrativa (state, payload) {
  state.unidad_administrativa.push(payload)
}

export function updateUnidad_administrativa (state, payload) {
  setUnidad_administrativaId(state, payload)
  const index = _.findIndex(state.unidad_administrativa, function (item) {
    return item.id === state.unidad_administrativaObject.id
  })
  state.unidad_administrativa[index] = state.unidad_administrativaObject
}

export function deleteUnidad_administrativa(state, id) {
  const index = state.unidad_administrativa.findIndex(obj => obj.id === id)
  state.unidad_administrativa.splice(index, 1)
}

export function setUnidad_administrativaTotal (state, payload) {
  state.unidad_administrativaTotal = payload
}
