import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.usuarios, function (item) {
    return item.created_at
  })
}

export function setUsuariosList (state, payload) {
  state.usuarios = payload
  orderDecs(state.usuarios)
}

export function setUsuariosId (state, payload) {
  state.usuariosObject = payload
}

export function addUsuarios (state, payload) {
  state.usuarios.push(payload)
}

export function updateUsuarios (state, payload) {
  setUsuariosId(state, payload)
  const index = _.findIndex(state.usuarios, function (item) {
    return item.id === state.usuariosObject.id
  })
  state.usuarios[index] = state.usuariosObject
}

export function deleteUsuarios (state, id) {
  const index = state.usuarios.findIndex(obj => obj.id === id)
  state.usuarios.splice(index, 1)
}

export function setUsuariosTotal (state, payload) {
  state.usuariosTotal = payload
}
