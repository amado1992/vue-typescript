import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.indicadoresl210, function (item) {
    return item.created_at
  })
}

export function setIndicadorl210List (state, payload) {
  state.indicadoresl210 = payload
  orderDecs(state.indicadoresl210)
}

export function setIndicadorl210Id (state, payload) {
  state.indicadoresl210Object = payload
}

export function addIndicadorl210(state, payload) {
  state.indicadoresl210.push(payload)
}

export function updateIndicadorl210 (state, payload) {
    setIndicadorl210Id(state, payload)
  const index = _.findIndex(state.indicadoresl210, function (item) {
    return item.id === state.indicadoresl210Object.id
  })
  state.indicadoresl210[index] = state.indicadoresl210Object
}

export function deleteIndicadorl210(state, id) {
  const index = state.indicadoresl210.findIndex(obj => obj.id === id)
  state.indicadoresl210.splice(index, 1)
}

export function setIndicadorl210Total (state, payload) {
  state.indicadoresl210Total = payload
}
