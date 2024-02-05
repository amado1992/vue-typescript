import _ from 'lodash'

function orderDecs(state) {
    _.orderBy(state.premio, function (item) {
        return item.created_at
    })
}

export function setPremioList(state, payload) {
    state.premio = payload
    orderDecs(state.premio)
}

export function setPremioId(state, payload) {
    state.premioObject = payload
}

export function addPremio(state, payload) {
    state.premio.push(payload)
}

export function updatePremio(state, payload) {
    setPremioId(state, payload)
    const index = _.findIndex(state.premio, function (item) {
        return item.id === state.premioObject.id
    })
    state.premio[index] = state.premioObject
}

export function deletePremio(state, id) {
    const index = state.premio.findIndex(obj => obj.id === id)
    state.premio.splice(index, 1)
}

export function setPremioTotal (state, payload) {
    state.premioTotal = payload
}

