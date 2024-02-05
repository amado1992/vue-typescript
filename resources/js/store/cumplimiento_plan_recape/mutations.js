import _ from 'lodash'

function orderDecs(state) {
  _.orderBy(state.cumplimientoPlanRecape, function (item) {
    return item.created_at
  })
}

export function setCumplimientoPlanRecapeList(state, payload) {
  state.cumplimientoPlanRecape = payload
  orderDecs(state.cumplimientoPlanRecape)
}

export function setCumplimientoPlanRecapeId(state, payload) {
  state.cumplimientoPlanRecape = payload
}

export function addCumplimientoPlanRecape(state, payload) {
  state.cumplimientoPlanRecape.push(payload)
}

export function updateCumplimientoPlanRecape(state, payload) {
  setCumplimientoPlanRecapeId(state, payload)
  const index = _.findIndex(state.cumplimientoPlanRecape, function (item) {
    return item.id === state.cumplimientoPlanRecape.id
  })
  state.cumplimientoPlanRecape[index] = state.cumplimientoPlanRecapeObject
}

export function deleteCumplimientoPlanRecape(state, id) {
  const index = state.cumplimientoPlanRecape.findIndex(obj => obj.id === id)
  state.cumplimientoPlanRecape.splice(index, 1)
}

