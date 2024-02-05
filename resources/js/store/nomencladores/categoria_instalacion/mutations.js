import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.categoria_instalacion, function (item) {
    return item.created_at
  })
}

export function setCategoria_instalacionList (state, payload) {
  state.categoria_instalacion = payload
  orderDecs(state.categoria_instalacion)
}

export function setCategoria_instalacionId (state, payload) {
  state.categoria_instalacionObject = payload
}

export function addCategoria_instalacion (state, payload) {
  state.categoria_instalacion.push(payload)
}

export function updateCategoria_instalacion (state, payload) {
  setCategoria_instalacionId(state, payload)
  const index = _.findIndex(state.categoria_instalacion, function (item) {
    return item.id === state.categoria_instalacionObject.id
  })
  state.categoria_instalacion[index] = state.categoria_instalacionObject
}

export function deleteCategoria_instalacion(state, id) {
  const index = state.categoria_instalacion.findIndex(obj => obj.id === id)
  state.categoria_instalacion.splice(index, 1)
}

export function setCategoria_instalacionTotal (state, payload) {
  state.categoria_instalacionTotal = payload
}
