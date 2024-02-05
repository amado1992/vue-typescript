import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.escuela_ramal, function (item) {
    return item.created_at
  })
}

export function setEscuela_ramalList (state, payload) {
  state.escuela_ramal = payload
  orderDecs(state.escuela_ramal)
}

export function setEscuela_ramalId (state, payload) {
  state.escuela_ramalObject = payload
}

export function addEscuela_ramal (state, payload) {
  state.escuela_ramal.push(payload)
}

export function updateEscuela_ramal (state, payload) {
  setEscuela_ramalId(state, payload)
  const index = _.findIndex(state.escuela_ramal, function (item) {
    return item.id === state.escuela_ramalObject.id
  })
  state.escuela_ramal[index] = state.escuela_ramalObject
}

export function deleteEscuela_ramal(state, id) {
  const index = state.escuela_ramal.findIndex(obj => obj.id === id)
  state.escuela_ramal.splice(index, 1)
}

export function setEscuela_ramalTotal (state, payload) {
  state.escuela_ramalTotal = payload
}
