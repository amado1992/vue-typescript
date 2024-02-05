import _ from 'lodash'

function orderDecs (state) {
  _.orderBy(state.role, function (item) {
    return item.created_at
  })
}

export function setRoleList (state, payload) {
  state.role = payload
  orderDecs(state.role)
}

export function setRoleId (state, payload) {
  state.roleObject = payload
}

export function addRole (state, payload) {
  state.role.push(payload)
}

export function updateRole (state, payload) {
  setRoleId(state, payload)
  const index = _.findIndex(state.role, function (item) {
    return item.id === state.roleObject.id
  })
  state.role[index] = state.roleObject
}

export function deleteRole (state, id) {
  const index = state.role.findIndex(obj => obj.id === id)
  state.role.splice(index, 1)
}

export function setRoleTotal (state, payload) {
  state.roleTotal = payload
}
