import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.colectivo, function (item) {
    return item.created_at
  })
}

export function setColectivoList (state, payload) {
  state.colectivo = payload
  orderDecs(state.colectivo)
}

export function setColectivoId (state, payload) {
  state.colectivoObject = payload
}

export function addColectivo(state, payload) {
  state.colectivo.push(payload)
}

export function updateColectivo (state, payload) {
  setColectivoId(state, payload)
  const index = _.findIndex(state.colectivo, function (item) {
    return item.id === state.colectivoObject.id
  })
  state.colectivo[index] = state.colectivoObject
}

export function deleteColectivo(state, id) {
  const index = state.colectivo.findIndex(obj => obj.id === id)
  state.colectivo.splice(index, 1)
}

export function setColectivoTotal (state, payload) {
  state.colectivoTotal = payload
}
