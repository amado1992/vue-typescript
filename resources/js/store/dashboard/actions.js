import Vue from "vue";
import {errorMessage} from "../../utils/notificaciones";
import * as message from "../../utils/resources";

export function getDashboard (context, data) {
    return Vue.prototype.$axios.post('/api/dashboard_index', data)
        .then((result) => {
            return result.data.data
        }).catch(err => {
            errorMessage(message.eDataError, err)
        })
}
