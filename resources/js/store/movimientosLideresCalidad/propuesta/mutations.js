import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.propuesta, function (item) {
    return item.created_at
  })
}

export function setPropuestaList (state, payload) {
  state.propuesta = payload
  orderDecs(state.propuesta)
}

export function setPropuestaId (state, payload) {
  state.propuestaObject = payload
}

export function addPropuesta(state, payload) {
  state.propuesta.push(payload)
}

export function updatePropuesta (state, payload) {
  setPropuestaId(state, payload)
  const index = _.findIndex(state.propuesta, function (item) {
    return item.id === state.propuestaObject.id
  })
  state.propuesta[index] = state.propuestaObject
}

export function deletePropuesta(state, id) {
  const index = state.propuesta.findIndex(obj => obj.id === id)
  state.propuesta.splice(index, 1)
}

export function setPropuestaTotal (state, payload) {
  state.propuestaTotal = payload
}
