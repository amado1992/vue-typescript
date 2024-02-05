import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.persona, function (item) {
    return item.created_at
  })
}

export function setPersonaList (state, payload) {
  state.persona = payload
  orderDecs(state.persona)
}

export function setPersonaId (state, payload) {
  state.personaObject = payload
}

export function addPersona (state, payload) {
  state.persona.push(payload)
}

export function updatePersona (state, payload) {
  setPersonaId(state, payload)
  const index = _.findIndex(state.persona, function (item) {
    return item.id === state.personaObject.id
  })
  state.persona[index] = state.personaObject
}

export function deletePersona (state, id) {
  const index = state.persona.findIndex(obj => obj.id === id)
  state.persona.splice(index, 1)
}

export function setPersonaTotal (state, payload) {
  state.personaTotal = payload
}
