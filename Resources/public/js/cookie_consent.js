document.addEventListener('DOMContentLoaded', function () {
  var cookieConsentForm = document.querySelector('.cb-cookie-consent__form')
  var cookieConsentFormBtn = document.querySelectorAll('.cb-cookie-consent__btn')

  if (cookieConsentForm) {
    // Submit form via ajax
    for (var i = 0; i < cookieConsentFormBtn.length; i++) {
      var btn = cookieConsentFormBtn[i]
      btn.addEventListener(
        'click',
        function (event) {
          event.preventDefault()

          var formAction = cookieConsentForm.action ? cookieConsentForm.action : location.href
          var xhr = new XMLHttpRequest()

          xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
              var buttonEvent = new CustomEvent('cookie-consent-form-submit-successful', {
                detail: event.target,
              })
              document.dispatchEvent(buttonEvent)
            }
          }
          xhr.open('POST', formAction)
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
          xhr.send(serializeForm(cookieConsentForm, event.target))

          // Clear all styles from body to prevent the white margin at the end of the page
          document.body.style.marginBottom = null
          document.body.style.marginTop = null
        },
        false,
      )
    }
  }
})

function serializeForm(form, clickedButton) {
  var serialized = []

  for (var i = 0; i < form.elements.length; i++) {
    var field = form.elements[i]

    if ((field.type !== 'checkbox' && field.type !== 'radio' && field.type !== 'button') || field.checked) {
      serialized.push(encodeURIComponent(field.name) + '=' + encodeURIComponent(field.value))
    }
  }

  serialized.push(encodeURIComponent(clickedButton.getAttribute('name')) + '=')

  return serialized.join('&')
}

if (typeof window.CustomEvent !== 'function') {
  function CustomEvent(event, params) {
    params = params || { bubbles: false, cancelable: false, detail: undefined }
    var evt = document.createEvent('CustomEvent')
    evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail)
    return evt
  }

  CustomEvent.prototype = window.Event.prototype

  window.CustomEvent = CustomEvent
}
