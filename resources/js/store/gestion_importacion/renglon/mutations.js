import _ from 'lodash'

function orderDecs(state) {
    _.orderBy(state.renglon, function (item) {
        return item.created_at
    })
}

export function setRenglonList(state, payload) {
    state.renglon = payload
    orderDecs(state.renglon)
}

export function setRenglonId(state, payload) {
    state.renglonObject = payload
}

export function addRenglon(state, payload) {
    state.renglon.push(payload)
}

export function updateRenglon(state, payload) {
    setRenglonId(state, payload)
    const index = _.findIndex(state.renglon, function (item) {
        return item.id === state.renglonObject.id
    })
    state.renglon[index] = state.renglonObject
}

export function deleteRenglon(state, id) {
    const index = state.renglon.findIndex(obj => obj.id === id)
    state.renglon.splice(index, 1)
}

export function setRenglonTotal (state, payload) {
    state.renglonTotal = payload
}

