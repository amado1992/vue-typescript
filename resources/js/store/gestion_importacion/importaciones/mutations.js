import _ from 'lodash'

function orderDecs(state) {
    _.orderBy(state.importacion, function (item) {
        return item.created_at
    })
}

export function setImportacionList(state, payload) {
    state.importacion = payload
    orderDecs(state.importacion)
}

export function setImportacionId(state, payload) {
    state.importacionObject = payload
}

export function addImportacion(state, payload) {
    state.importacion.push(payload)
}

export function updateImportacion(state, payload) {
    setImportacionId(state, payload)
    const index = _.findIndex(state.importacion, function (item) {
        return item.id === state.importacionObject.id
    })
    state.importacion[index] = state.importacionObject
}

export function deleteImportacion(state, id) {
    const index = state.importacion.findIndex(obj => obj.id === id)
    state.importacion.splice(index, 1)
}

export function setImportacionTotal (state, payload) {
    state.importacionTotal = payload
}

