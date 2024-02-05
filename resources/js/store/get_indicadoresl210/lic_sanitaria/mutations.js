import _ from 'lodash'
import {getLicSanitaria} from './actions';

function orderDecs (state) {
  _.orderBy(state.licencia, function (item) {
    return item.created_at
  })
}

export function setLicSanitariaList (state, payload) {
  state.licencia = payload
  orderDecs(state.licencia)
}

