import { Dialog } from 'quasar'

export function showDialog (message, title = 'Confirmar', cancel = true) {
  return Dialog.create({
    title: title,
    message: message,
    ok: {
      flat:true,
      color: 'primary',
      label: 'aceptar'
    },
    cancel: {
      flat:true,
      color: 'negative'
    },
    persistent: true
  })
}
