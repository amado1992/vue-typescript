import _ from 'lodash'
import {getInstalacion} from "./actions";

function orderDecs(state) {
    _.orderBy(state.instalacion, function (item) {
        return item.created_at
    })
}

export function setInstalacionList(state, payload) {
    state.instalacion = payload
    orderDecs(state.instalacion)
}

export function setInstalacion(state, payload) {
    state.instalacionObject = payload
}

export function addInstalacion(state, payload) {
    state.instalacion.push(payload)
}

export function updateInstalacion(state, payload) {
    setEntidadId(state, payload)
    const index = _.findIndex(state.instalacion, function (item) {
        return item.id === state.instalacionObject.id
    })
    state.instalacion[index] = state.entidadObject
}

export function deleteInstalacion(state, id) {
    const index = state.instalacion.findIndex(obj => obj.id === id)
    state.instalacion.splice(index, 1)
}

export function setInstalacionTotal (state, payload) {
    state.instalacionTotal = payload
}

