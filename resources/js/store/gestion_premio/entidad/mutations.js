import _ from 'lodash'

function orderDecs(state) {
    _.orderBy(state.entidad, function (item) {
        return item.created_at
    })
}

export function setEntidadList(state, payload) {
    state.entidad = payload
    orderDecs(state.entidad)
}

export function setEntidadId(state, payload) {
    state.entidadObject = payload
}

export function addEntidad(state, payload) {
    state.entidad.push(payload)
}

export function updateEntidad(state, payload) {
    setEntidadId(state, payload)
    const index = _.findIndex(state.entidad, function (item) {
        return item.id === state.entidadObject.id
    })
    state.entidad[index] = state.entidadObject
}

export function deleteEntidad(state, id) {
    const index = state.entidad.findIndex(obj => obj.id === id)
    state.entidad.splice(index, 1)
}

export function setEntidadTotal (state, payload) {
    state.entidadTotal = payload
}

