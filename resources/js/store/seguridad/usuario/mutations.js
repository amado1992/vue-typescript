import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.usuario, function (item) {
    return item.created_at
  })
}

export function setUsuarioList (state, payload) {
  state.usuario = payload
  orderDecs(state.usuario)
}

export function setUsuarioId (state, payload) {
  state.usuarioObject = payload
}

export function addUsuario (state, payload) {
  state.usuario.push(payload)
}

export function updateUsuario (state, payload) {
  setUsuarioId(state, payload)
  const index = _.findIndex(state.usuario, function (item) {
    return item.id === state.usuarioObject.id
  })
  state.usuario[index] = state.usuarioObject
}

export function deleteUsuario (state, id) {
  const index = state.usuario.findIndex(obj => obj.id === id)
  state.usuario.splice(index, 1)
}

export function setUsuarioTotal (state, payload) {
  state.usuarioTotal = payload
}
