import Vue from 'vue'
import {errorMessage, successMessage} from '../../utils/notificaciones'
import * as message from '../../utils/resources'

export function deleteAnexos(context,data) {
  return Vue.prototype.$axios.delete('/api/delete_anexo/' + data.id)
    .then((result) => {
      const data = result.data
      context.commit('setMaintenance', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getAnexosXInstalacion(context,data) {
  return Vue.prototype.$axios.post('/api/get_anexos_xinstalacion/',data)
    .then((result) => {
      const data = result.data
      context.commit('setMaintenance', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getAnexos2All(context) {
  return Vue.prototype.$axios.post('/api/get_anexos2_all')
    .then((result) => {
      const data = result.data
      context.commit('setMaintenance', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getAnexos3All(context) {
  return Vue.prototype.$axios.post('/api/get_anexos3_all')
    .then((result) => {
      const data = result.data
      context.commit('setMaintenance', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getAnexos2Indicador2(context) {
  return Vue.prototype.$axios.post('/api/get_anexos2indicador2_all')
    .then((result) => {
      const data = result.data
      context.commit('setMaintenance', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getAnexos3Indicador1(context) {
  return Vue.prototype.$axios.post('/api/get_anexos3indicador1_all')
    .then((result) => {
      const data = result.data
      context.commit('setMaintenance', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getAnexos3Indicador23(context) {
  return Vue.prototype.$axios.post('/api/get_anexos3indicador23_all')
    .then((result) => {
      const data = result.data
      context.commit('setMaintenance', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function getReportAnexo2(context,data) {
  return Vue.prototype.$axios.post('/api/report_anexo_dos',{
    "mayorigual": data.mayorigual,
    "mes": data.mes,
    "anno": data.anno,
    "menorigual": data.menorigual,
    "entidad_id": data.entidad_id
  })
    .then((result) => {
      const data = result.data
      context.commit('setMaintenance', data.data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

// export function getReportAnexo2(context,data) {
//   return Vue.prototype.$axios.post('/api/report_anexo_dos',{
//     "greater": data.greater,
//     "month": data.month,
//     "year": data.year,
//     "less": data.less,
//     "entity_id": data.entity_id
//   })
//     .then((result) => {
//       const data = result.data
//       context.commit('setMaintenance', data.data)
//       return data
//     }).catch(err => {
//       errorMessage(message.eDataError, err)
//     })
// }
//
// export function getReportAnexo2HFOHDD(context, data) {
//   return Vue.prototype.$axios.post('/api/report_anexodos_hfohdd', {
//     "greater": data.greater,
//     "month": data.month,
//     "year": data.year,
//     "less": data.less,
//     "entity_id": data.entity_id,
//     "indicator": data.indicator
//   })
//     .then((result) => {
//       const data = result.data
//       context.commit('setMaintenance', data.data)
//       return data
//     }).catch(err => {
//       errorMessage(message.eDataError, err)
//     })
// }
//
// export function getIndicadoresAgrupadoxElementos(context, data) {
//     return Vue.prototype.$axios.post('/api/reporte1_anexo_dos', {
//         "mes": data.mes,
//         "anno": data.anno,
//         "porcientoMenor": data.menor,
//         "porcientoMayor": data.mayor,
//         "porcientoIgual": data.igual,
//         "indicador": data.indicador,
//     })
//         .then((result) => {
//
//             const data = result.data
//             context.commit('setMaintenance', data.data)
//             return data
//         }).catch(err => {
//             errorMessage(message.eDataError, err)
//         })
// }
//
//
//
// export function mantenimientoAgrupado(context, data) {
//     return Vue.prototype.$axios.get('/api/reporte1_anexo_tres', {
//
//         "mes": data.mes,
//         "anno": data.anno,
//         "porcientoMenor": data.menor,
//         "porcientoMayor": data.mayor,
//         "porcientoIgual": data.igual,
//         "indicador": data.indicador,
//     })
//         .then((result) => {
//
//             const data = result.data
//             context.commit('setMaintenance', data.data)
//             return data
//         }).catch(err => {
//             errorMessage(message.eDataError, err)
//         })
// }
//
// export function mayorInejecuciónMantenimiento(context, data) {
//     return Vue.prototype.$axios.get('/api/reporte2_anexo_tres', {
//
//         "mes": data.mes,
//         "anno": data.anno,
//         "porcientoMenor": data.menor,
//         "porcientoMayor": data.mayor,
//         "porcientoIgual": data.igual,
//         "indicador": data.indicador,
//     })
//         .then((result) => {
//
//             const data = result.data
//             context.commit('setMaintenance', data.data)
//             return data
//         }).catch(err => {
//             errorMessage(message.eDataError, err)
//         })
// }
//
// export function mayorInejecuciónPresupuesto(context, data) {
//     return Vue.prototype.$axios.get('/api/reporte3_anexo_tres', data)
//         .then((result) => {
//
//             const data = result.data
//             context.commit('setMaintenance', data.data)
//             return data
//         }).catch(err => {
//             errorMessage(message.eDataError, err)
//         })
// }




