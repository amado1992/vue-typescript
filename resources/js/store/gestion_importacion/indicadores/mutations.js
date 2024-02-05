import _ from 'lodash'

function orderDecs(state) {
    _.orderBy(state.indicador, function (item) {
        return item.created_at
    })
}

export function setIndicadorList(state, payload) {
    state.indicador = payload
    orderDecs(state.indicador)
}

export function setIndicadorId(state, payload) {
    state.indicadorObject = payload
}

export function addIndicador(state, payload) {
    state.indicador.push(payload)
}

export function updateIndicador(state, payload) {
    setIndicadorId(state, payload)
    const index = _.findIndex(state.indicador, function (item) {
        return item.id === state.indicadorObject.id
    })
    state.indicador[index] = state.indicadorObject
}

export function deleteIndicador(state, id) {
    const index = state.indicador.findIndex(obj => obj.id === id)
    state.indicador.splice(index, 1)
}

export function setIndicadorTotal (state, payload) {
    state.indicadorTotal = payload
}

