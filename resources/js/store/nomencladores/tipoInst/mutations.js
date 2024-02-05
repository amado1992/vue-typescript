import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.tipoInst, function (item) {
    return item.created_at
  })
}

export function setTipoInstList (state, payload) {
  state.tipoInst = payload
  orderDecs(state.tipoInst)
}

export function setTipoInstId (state, payload) {
  state.tipoInstObject = payload
}

export function addTipoInst (state, payload) {
  state.tipoInst.push(payload)
}

export function updateTipoInst (state, payload) {
  setTipoInstId(state, payload)
  const index = _.findIndex(state.tipoInst, function (item) {
    return item.id === state.tipoInstObject.id
  })
  state.tipoInst[index] = state.tipoInstObject
}

export function deleteTipoInst(state, id) {
  const index = state.tipoInst.findIndex(obj => obj.id === id)
  state.tipoInst.splice(index, 1)
}

export function setTipoInstTotal (state, payload) {
  state.tipoInstTotal = payload
}
