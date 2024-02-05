import Vue from 'vue'
import {errorMessage, successMessage} from '../../utils/notificaciones'
import * as message from '../../utils/resources'
import axios from "axios";

export function getDemandaServicio(context) {
  return Vue.prototype.$axios.get('/api/get_demandaservicio')
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getOperadoras(context) {
  return Vue.prototype.$axios.get('/api/get_operadoras')
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getEstadosDemanda(context) {
  return Vue.prototype.$axios.get('/api/get_estadosdemanda')
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getTiposSistGestion(context) {
  return Vue.prototype.$axios.get('/api/get_tipossistgestion')
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getRegistersDemandas(context,data) {
  return Vue.prototype.$axios.post('/api/get_registers_demandas',{"osde_id": data})
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function addEstadoDemanda(context,data) {
  return Vue.prototype.$axios.post('/api/add_estadodemanda',data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function addDemandaServicio(context,data) {
  return Vue.prototype.$axios.post('/api/add_demandaservicio',data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function addOperadora(context,data) {
  return Vue.prototype.$axios.post('/api/add_operadora',data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function addTipoSistGestion(context,data) {
  return Vue.prototype.$axios.post('/api/add_tiposistgestion',data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function editEstadoDemanda(context,data) {
  return Vue.prototype.$axios.put('/api/edit_estadodemanda',data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function editDemandaServicio(context,data) {
  return Vue.prototype.$axios.put('/api/edit_demandaservicio',data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function editOperadora(context,data) {
  return Vue.prototype.$axios.put('/api/edit_operadora',data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function editTipoSistGestion(context,data) {
  return Vue.prototype.$axios.put('/api/edit_tiposistgestion',data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function deleteEstadoDemanda(context,data) {
  return Vue.prototype.$axios.delete('/api/delete_estadodemanda/' + data.id)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function deleteDemandaServicio(context,data) {
  return Vue.prototype.$axios.delete('/api/delete_demandaservicio/' + data.id)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function deleteOperadora(context,data) {
  return Vue.prototype.$axios.delete('/api/delete_operadora/' + data.id)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function deleteTipoSistGestion(context,data) {
  return Vue.prototype.$axios.delete('/api/delete_tiposistgestion/' + data.id)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getAuthenticated(context,data) {
  return Vue.prototype.$axios.post('/api/get_registros',{
    "instalacion_id": data
  })
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getAllRegisters(context,data) {
  return Vue.prototype.$axios.post('/api/get_allregistros',{
    "instalacion_id": data
  })
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getRegister(context,data) {
  return Vue.prototype.$axios.post('/api/get_registro',{
    "id": data
  })
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function deleteRegister(context,data) {
  return Vue.prototype.$axios.delete('/api/delete_register/' + data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function addRegister(context,data) {
  return Vue.prototype.$axios.post('/api/add_register',data)
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function editRegister(context,datas) {
  return Vue.prototype.$axios.put('/api/edit_register', datas)

  //return  await axios.put('/api/costos_conformidades/' + id, {data: form_sitema})
    .then((result) => {
      const data = result.data
      context.commit('setManagementSystem', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}





