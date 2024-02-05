import _ from 'lodash';

function orderDecs00(state) {
  _.orderBy(state.costoConformidadCC, function (item) {
    return item.created_at;
  });
}
function orderDecs01(state) {
  _.orderBy(state.costoNoConformidadCC, function (item) {
    return item.created_at;
  });
}

export function setCostoConformidadList(state, payload) {
  state.costoConformidadCC = payload;
  orderDecs00(state.costoConformidadCC);
}
export function setCostoNoConformidadList(state, payload) {
  state.costoNoConformidadCC = payload;
  orderDecs01(state.costoNoConformidadCC);
}

export function setCostoConformidadId(state, payload) {
  state.costoConformidadCCObject = payload;
}
export function setCostoNoConformidadId(state, payload) {
  state.noConformidadCCObject = payload;
}

export function addCostoConformidad(state, payload) {
  state.costoConformidadCC.push(payload);
}
export function addCostoNoConformidad(state, payload) {
  state.costoNoConformidadCC.push(payload);
}
export function updateCostoConformidad(state, payload) {
  setCostoConformidadId(state, payload);
  const index = _.findIndex(state.costoConformidadCC, function (item) {
    return item.id === state.costoConformidadCCObject.id;
  });
  state.costoConformidadCC[index] = state.costoConformidadCCObject;
}
export function updateCostoNoConformidad(state, payload) {
  setCostoNoConformidadId(state, payload);
  const index = _.findIndex(state.costoNoConformidadCC, function (item) {
    return item.id === state.costoNoConformidadCCObject.id;
  });
  state.costoNoConformidadCC[index] = state.costoNoConformidadCCObject;
}
export function deleteCostoConformidad(state, id) {
  const index = state.costoConformidadCC.findIndex((obj) => obj.id === id);
  state.costoConformidadCC.splice(index, 1);
}
export function deleteCostoNoConformidad(state, id) {
  const index = state.costoNoConformidadCC.findIndex((obj) => obj.id === id);
  state.costoNoConformidadCC.splice(index, 1);
}
