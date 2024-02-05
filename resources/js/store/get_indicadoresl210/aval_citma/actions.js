import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getAvalesCitma(context, data) {
    return Vue.prototype.$axios.post('/api/get_avalcitma', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
