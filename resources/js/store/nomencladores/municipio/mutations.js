import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.municipio, function (item) {
    return item.created_at
  })
}

export function setMunicipioList (state, payload) {
  state.municipio = payload
  orderDecs(state.municipio)
}

export function setMunicipioId (state, payload) {
  state.municipioObject = payload
}

export function addMunicipio (state, payload) {
  state.municipio.push(payload)
}

export function updateMunicipio (state, payload) {
  setMunicipioId(state, payload)
  const index = _.findIndex(state.municipio, function (item) {
    return item.id === state.municipioObject.id
  })
  state.municipio[index] = state.municipioObject
}

export function deleteMunicipio(state, id) {
  const index = state.municipio.findIndex(obj => obj.id === id)
  state.municipio.splice(index, 1)
}

export function setEntidadTotal (state, payload) {
  state.entidadTotal = payload
}
