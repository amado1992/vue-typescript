import { Notify } from 'quasar'

export function successMessage (message) {
  Notify.create({
    timeout: 2000,
    position: 'top-right',
    icon: 'sentiment_very_satisfied',
    message: message,
    color: 'positive',
    textColor: 'white',
    actions: [{
      icon: 'close',
      color: 'white'
    }]
  })
}

export function warningMessage (message) {
  Notify.create({
    timeout: 2000,
    position: 'top-right',
    icon: 'sentiment_dissatisfied',
    message: message,
    color: 'warning',
    textColor: 'white',
    actions: [{
      icon: 'close',
      color: 'white'
    }]
  })
}

export function errorMessage (message, errorMessage = '') {
  Notify.create({
    timeout: 2000,
    position: 'top-right',
    icon: 'sentiment_very_dissatisfied',
    message: `${message}`,
    color: 'negative',
    textColor: 'white',
    actions: [{
      icon: 'close',
      color: 'white'
    }]
  })
  console.log(errorMessage)
}

export function infoMessage (infoMessage = '') {
  Notify.create({
    timeout: 2000,
    position: 'top-right',
    icon: 'info',
    message: `${infoMessage}`,
    color: 'info',
    textColor: 'white',
    actions: [{
      icon: 'close',
      color: 'white'
    }]
  })
}
