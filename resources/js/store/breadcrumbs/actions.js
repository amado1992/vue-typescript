export function setBreadcrumbs (context, data) {
  context.commit('SET_BREADCRUMBS', data)
}

export function addToHomeBreadcrumbs (context, data) {
  context.commit('ADD_TO_HOME_BREADCRUMBS', data)
}

export function addBreadcrumbs (context, data) {
  context.commit('ADD_BREADCRUMBS', data)
}

