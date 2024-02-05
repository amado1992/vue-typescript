import _ from 'lodash'

function orderDecs(state) {
    _.orderBy(state.reportes, function (item) {
        return item.created_at
    })
}

export function setReportesList(state, payload) {
    state.reportes = payload
    orderDecs(state.reportes)
}

export function setReportesId(state, payload) {
    state.reportesObject = payload
}

export function addReportes(state, payload) {
    state.reportes.push(payload)
}

export function updateReportes(state, payload) {
    setReportesId(state, payload)
    const index = _.findIndex(state.reportes, function (item) {
        return item.id === state.reportesObject.id
    })
    state.reportes[index] = state.reportesObject
}

export function deleteReportes(state, id) {
    const index = state.reportes.findIndex(obj => obj.id === id)
    state.reportes.splice(index, 1)
}

export function setReportesTotal (state, payload) {
    state.reportesTotal = payload
}

