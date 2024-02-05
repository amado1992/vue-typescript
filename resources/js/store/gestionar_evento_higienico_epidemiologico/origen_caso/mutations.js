import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.origen_caso, function (item) {
    return item.created_at
  })
}

export function setOrigen_casoList (state, payload) {
  state.origen_caso = payload
  orderDecs(state.origen_caso)
}

export function setOrigen_casoId (state, payload) {
  state.origen_casoObject = payload
}

export function addOrigen_caso (state, payload) {
  state.origen_caso.push(payload)
}

export function updateOrigen_caso (state, payload) {
  setOrigen_casoId(state, payload)
  const index = _.findIndex(state.origen_caso, function (item) {
    return item.id === state.origen_casoObject.id
  })
  state.origen_caso[index] = state.origen_casoObject
}

export function deleteOrigen_caso(state, id) {
  const index = state.origen_caso.findIndex(obj => obj.id === id)
  state.origen_caso.splice(index, 1)
}

export function setOrigen_casoTotal (state, payload) {
  state.origen_casoTotal = payload
}
