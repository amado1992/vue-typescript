import _ from 'lodash';

function orderDecs00(state) {
  _.orderBy(state.conformidadCC, function (item) {
    return item.created_at;
  });
}
function orderDecs01(state) {
  _.orderBy(state.noConformidadCC, function (item) {
    return item.created_at;
  });
}

export function setConformidadList(state, payload) {
  state.conformidadCC = payload;
  orderDecs00(state.conformidadCC);
}
export function setNoConformidadList(state, payload) {
  state.noConformidadCC = payload;
  orderDecs01(state.noConformidadCC);
}

export function setConformidadId(state, payload) {
  state.conformidadCCObject = payload;
}
export function setNoConformidadId(state, payload) {
  state.noConformidadCCObject = payload;
}

export function addConformidad(state, payload) {
  state.conformidadCC.push(payload);
}
export function addNoConformidad(state, payload) {
  state.noConformidadCC.push(payload);
}
export function updateConformidad(state, payload) {
  setConformidadId(state, payload);
  const index = _.findIndex(state.conformidadCC, function (item) {
    return item.id === state.conformidadCCObject.id;
  });
  state.conformidadCC[index] = state.conformidadCCObject;
}
export function updateNoConformidad(state, payload) {
  setNoConformidadId(state, payload);
  const index = _.findIndex(state.noConformidadCC, function (item) {
    return item.id === state.noConformidadCCObject.id;
  });
  state.noConformidadCC[index] = state.noConformidadCCObject;
}
export function deleteConformidad(state, id) {
  const index = state.conformidadCC.findIndex((obj) => obj.id === id);
  state.conformidadCC.splice(index, 1);
}
export function deleteNoConformidad(state, id) {
  const index = state.noConformidadCC.findIndex((obj) => obj.id === id);
  state.noConformidadCC.splice(index, 1);
}
