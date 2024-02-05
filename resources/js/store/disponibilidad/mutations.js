import _ from 'lodash'

function orderDecs (state) {
    _.orderBy(state.disponibilidades, function (item) {
        return item.created_at;
    })
}

export function setDisponibilidadesList (state, payload) {
    state.disponibilidades = payload;
    orderDecs(state.disponibilidades);
}

export function setDisponibilidadesId (state, payload) {
    state.disponibilidadObject = payload;
}

export function addDisponibilidades (state, payload) {
    state.disponibilidades.push(payload);
}

export function updateDisponibilidades (state, payload) {
    setDisponibilidadesId(state, payload);
    const index = _.findIndex(state.disponibilidades, function (item) {
        return item.id === state.disponibilidadObject.id;
    });
    state.disponibilidades[index] = state.disponibilidadObject;
}

export function removeDisponibilidades (state, id) {
    const index = state.disponibilidades.findIndex(obj => obj.id === id);
    state.disponibilidades.splice(index, 1);
}
