import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.empresa, function (item) {
    return item.created_at
  })
}

export function setEmpresaList (state, payload) {
  state.empresa = payload
  orderDecs(state.empresa)
}

export function setEmpresaId (state, payload) {
  state.empresaObject = payload
}

export function addEmpresa (state, payload) {
  state.empresa.push(payload)
}

export function updateEmpresa (state, payload) {
  setEmpresaId(state, payload)
  const index = _.findIndex(state.empresa, function (item) {
    return item.id === state.empresaObject.id
  })
  state.empresa[index] = state.empresaObject
}

export function deleteEmpresa(state, id) {
  const index = state.empresa.findIndex(obj => obj.id === id)
  state.empresa.splice(index, 1)
}

export function setEmpresaTotal (state, payload) {
  state.empresaTotal = payload
}
