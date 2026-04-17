(function () {
  'use strict';

  if (typeof Stripe === 'undefined' || typeof tjsBookingStripeParams === 'undefined') {
    return;
  }

  var form = document.querySelector('[data-booking-form]');
  var mountRoot = document.getElementById('tjs-booking-stripe-form');

  if (!form || !mountRoot || !tjsBookingStripeParams.key) {
    return;
  }

  var stripe = Stripe(tjsBookingStripeParams.key, {
    locale: tjsBookingStripeParams.stripe_locale || 'auto'
  });

  var elements = stripe.elements();
  var style = {
    base: {
      color: '#4b245f',
      fontFamily: '"DM Sans", sans-serif',
      fontSize: '16px',
      lineHeight: '24px',
      '::placeholder': {
        color: '#9f88a6'
      }
    },
    invalid: {
      color: '#b14646',
      iconColor: '#b14646'
    }
  };

  var classes = {
    focus: 'focused',
    empty: 'empty',
    invalid: 'invalid'
  };

  var cardNumber = elements.create('cardNumber', {
    style: style,
    classes: classes,
    showIcon: true
  });

  var cardExpiry = elements.create('cardExpiry', {
    style: style,
    classes: classes
  });

  var cardCvc = elements.create('cardCvc', {
    style: style,
    classes: classes
  });

  cardNumber.mount('#tjs-booking-stripe-card-element');
  cardExpiry.mount('#tjs-booking-stripe-exp-element');
  cardCvc.mount('#tjs-booking-stripe-cvc-element');

  var errorEl = document.getElementById('tjs-booking-stripe-errors');
  var fieldConfigs = {
    cardNumber: {
      element: cardNumber,
      mount: document.getElementById('tjs-booking-stripe-card-element'),
      error: document.querySelector('[data-stripe-error-for="cardNumber"]')
    },
    cardExpiry: {
      element: cardExpiry,
      mount: document.getElementById('tjs-booking-stripe-exp-element'),
      error: document.querySelector('[data-stripe-error-for="cardExpiry"]')
    },
    cardCvc: {
      element: cardCvc,
      mount: document.getElementById('tjs-booking-stripe-cvc-element'),
      error: document.querySelector('[data-stripe-error-for="cardCvc"]')
    }
  };
  var fieldStates = {
    cardNumber: { complete: false, empty: true, errorMessage: '' },
    cardExpiry: { complete: false, empty: true, errorMessage: '' },
    cardCvc: { complete: false, empty: true, errorMessage: '' }
  };

  function setFormError(message) {
    if (!errorEl) return;
    errorEl.textContent = message || '';
    errorEl.hidden = !message;
  }

  function clearFormError() {
    setFormError('');
  }

  function setFieldError(fieldName, message) {
    var config = fieldConfigs[fieldName];

    if (!config) {
      return;
    }

    if (config.mount) {
      config.mount.classList.toggle('invalid', Boolean(message));
    }

    if (config.error) {
      config.error.textContent = message || '';
      config.error.hidden = !message;
    }
  }

  function clearFieldErrors() {
    Object.keys(fieldConfigs).forEach(function (fieldName) {
      setFieldError(fieldName, '');
    });
  }

  function updateFieldState(fieldName, event) {
    fieldStates[fieldName] = {
      complete: Boolean(event && event.complete),
      empty: Boolean(event && event.empty),
      errorMessage: event && event.error && event.error.message ? event.error.message : ''
    };

    setFieldError(fieldName, fieldStates[fieldName].errorMessage);
  }

  function handleStripeChange(fieldName) {
    return function (event) {
      updateFieldState(fieldName, event);
    };
  }

  function validateRequiredFields() {
    var firstInvalidConfig = null;
    clearFormError();

    Object.keys(fieldConfigs).forEach(function (fieldName) {
      var state = fieldStates[fieldName] || { complete: false, empty: true, errorMessage: '' };
      if (state.complete) {
        setFieldError(fieldName, '');
        return;
      }

      var message = state.errorMessage || 'This field is required';
      setFieldError(fieldName, message);

      if (!firstInvalidConfig) {
        firstInvalidConfig = fieldConfigs[fieldName];
      }
    });

    if (firstInvalidConfig && firstInvalidConfig.element && typeof firstInvalidConfig.element.focus === 'function') {
      firstInvalidConfig.element.focus();
    }

    return !firstInvalidConfig;
  }

  clearFormError();
  cardNumber.on('change', handleStripeChange('cardNumber'));
  cardExpiry.on('change', handleStripeChange('cardExpiry'));
  cardCvc.on('change', handleStripeChange('cardCvc'));

  // Expose to main.js so the form submit handler can call confirmCardPayment.
  window.tjsBookingStripe = {
    stripe: stripe,
    cardNumber: cardNumber,
    cardExpiry: cardExpiry,
    cardCvc: cardCvc,
    clearFieldErrors: clearFieldErrors,
    validateRequiredFields: validateRequiredFields,
    setFormError: setFormError,
    clearFormError: clearFormError
  };
})();
