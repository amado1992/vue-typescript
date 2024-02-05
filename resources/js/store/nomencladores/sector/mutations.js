import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.sector, function (item) {
    return item.created_at
  })
}

export function setSectorList (state, payload) {
  state.sector = payload
  orderDecs(state.sector)
}

export function setSectorId (state, payload) {
  state.sectorObject = payload
}

export function addSector (state, payload) {
  state.sector.push(payload)
}

export function updateSector (state, payload) {
  setSectorId(state, payload)
  const index = _.findIndex(state.sector, function (item) {
    return item.id === state.sectorObject.id
  })
  state.sector[index] = state.sectorObject
}

export function deleteSector(state, id) {
  const index = state.sector.findIndex(obj => obj.id === id)
  state.sector.splice(index, 1)
}

