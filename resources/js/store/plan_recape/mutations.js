import _ from 'lodash'

function orderDecs(state) {
  _.orderBy(state.planRecape, function (item) {
    return item.created_at
  })
}

export function setPlanRecapeList(state, payload) {
  state.planRecape = payload
  orderDecs(state.planRecape)
}

export function setPlanRecapeId(state, payload) {
  state.planRecape = payload
}

export function addPlanRecape(state, payload) {
  state.planRecape.push(payload)
}

export function updatePlanRecape(state, payload) {
  setPlanRecapeId(state, payload)
  const index = _.findIndex(state.planRecape, function (item) {
    return item.id === state.planRecape.id
  })
  state.planRecape[index] = state.planRecapeObject
}

export function deletePlanRecape(state, id) {
  const index = state.planRecape.findIndex(obj => obj.id === id)
  state.planRecape.splice(index, 1)
}

