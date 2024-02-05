export function SET_BREADCRUMBS (state, payload) {
  state.breadcrumbs = payload
}

export function ADD_TO_HOME_BREADCRUMBS (state, payload) {
  state.breadcrumbs = [...[{ name: 'home', to: '/dashboard', label: 'Inicio', icon: 'home' }], ...payload]
}

export function ADD_BREADCRUMBS (state, payload) {
  state.breadcrumbs.push(payload)
}
