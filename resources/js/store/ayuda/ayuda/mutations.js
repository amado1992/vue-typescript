import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipoCliente, function (item) {
    return item.created_at
  })
}

export function setAyudaList (state, payload) {
  state.ayuda = payload
  orderDecs(state.ayuda)
}

export function setAyudaId (state, payload) {
  state.ayudaObject = payload
}

export function addAyuda (state, payload) {
  state.ayuda.push(payload)
}

export function updateAyuda (state, payload) {
  setAyudaId(state, payload)
  const index = _.findIndex(state.ayuda, function (item) {
    return item.id === state.ayudaObject.id
  })
  state.ayuda[index] = state.ayudaObject
}

export function deleteAyuda (state, id) {
  const index = state.ayuda.findIndex(obj => obj.id === id)
  state.ayuda.splice(index, 1)
}
