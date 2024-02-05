import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.traza, function (item) {
    return item.created_at
  })
}

export function setTrazaList (state, payload) {
  state.traza = payload
  orderDecs(state.traza)
}
