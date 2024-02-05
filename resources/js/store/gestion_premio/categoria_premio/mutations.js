import _ from 'lodash'

function orderDecs(state) {
  _.orderBy(state.categoria_premio, function (item) {
    return item.created_at
  })
}

export function setCategoria_premioList(state, payload) {
  state.categoria_premio = payload
  orderDecs(state.categoria_premio)
}

export function setCategoria_premioId(state, payload) {
  state.categoria_premioObject = payload
}

export function addCategoria_premio(state, payload) {
  state.categoria_premio.push(payload)
}

export function updateCategoria_premio(state, payload) {
  setCategoria_premioId(state, payload)
  const index = _.findIndex(state.categoria_premio, function (item) {
    return item.id === state.categoria_premioObject.id
  })
  state.categoria_premio[index] = state.categoria_premioObject
}

export function deleteCategoria_premio(state, id) {
  const index = state.categoria_premio.findIndex(obj => obj.id === id)
  state.categoria_premio.splice(index, 1)
}

export function setCategoria_premioTotal (state, payload) {
  state.categoria_premioTotal = payload
}

