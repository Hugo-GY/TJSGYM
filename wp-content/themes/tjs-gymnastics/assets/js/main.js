document.addEventListener('DOMContentLoaded', () => {
  // ── Mobile nav toggle ──────────────────────────────────────
  const toggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  const overlay = document.querySelector('.nav-overlay');
  let navBack = null;

  function setMenuOpen(isOpen) {
    if (!navLinks || !toggle) return;

    navLinks.classList.toggle('open', isOpen);
    overlay?.classList.toggle('open', isOpen);
    toggle.setAttribute('aria-expanded', String(isOpen));

    // Prevent layout shift when body scrolling is locked and the scrollbar disappears.
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    document.body.style.overflow = isOpen ? 'hidden' : '';
    document.body.style.paddingRight = isOpen && scrollbarWidth > 0 ? `${scrollbarWidth}px` : '';
  }

  if (toggle && navLinks) {
    if (!navLinks.querySelector('.nav-back')) {
      navBack = document.createElement('button');
      navBack.type = 'button';
      navBack.className = 'nav-back';
      navBack.setAttribute('aria-label', 'Close menu and return to page');
      navBack.innerHTML = '<span aria-hidden="true">←</span><span>Back</span>';
      navLinks.prepend(navBack);
    } else {
      navBack = navLinks.querySelector('.nav-back');
    }

    toggle.addEventListener('click', () => {
      const isOpen = !navLinks.classList.contains('open');
      setMenuOpen(isOpen);
    });

    navBack?.addEventListener('click', closeNav);
    overlay?.addEventListener('click', closeNav);
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeNav();
    });
  }

  function closeNav() {
    setMenuOpen(false);
  }

  // ── Accordion ──────────────────────────────────────────────
  document.querySelectorAll('.accordion-trigger').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.accordion-item');
      const isOpen = item.classList.contains('open');

      item.parentElement.querySelectorAll('.accordion-item.open').forEach(el => {
        el.classList.remove('open');
        el.querySelector('.accordion-trigger').setAttribute('aria-expanded', 'false');
      });

      if (!isOpen) {
        item.classList.add('open');
        trigger.setAttribute('aria-expanded', 'true');
      }
    });
  });

  const setupSelectOnlyDateInput = field => {
    if (!(field instanceof HTMLInputElement) || field.dataset.dateInputLocked === 'true') {
      return;
    }

    const usesFlatpickr = field.matches('[data-type-datepicker], .ff-el-datepicker');
    const usesNativeDatePicker = field.type === 'date';

    if (usesFlatpickr && !field._flatpickr && typeof window.flatpickr === 'function') {
      window.flatpickr(field, {
        allowInput: false,
        clickOpens: true,
        dateFormat: field.dataset.format || 'Y-m-d',
        disableMobile: true,
        monthSelectorType: 'dropdown',
        onChange: () => {
          field.dispatchEvent(new Event('input', { bubbles: true }));
          field.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
    }

    if (!usesFlatpickr && !usesNativeDatePicker) {
      return;
    }

    field.dataset.dateInputLocked = 'true';
    field.setAttribute('inputmode', 'none');
    field.setAttribute('autocomplete', 'off');

    if (usesFlatpickr) {
      const openFlatpickr = () => {
        if (field._flatpickr && typeof field._flatpickr.open === 'function') {
          field._flatpickr.open();
        }
      };

      field.readOnly = true;
      field.addEventListener('focus', openFlatpickr);
      field.addEventListener('click', openFlatpickr);
      field.addEventListener('keydown', event => {
        if (
          event.key === 'Tab'
          || event.key === 'Shift'
          || event.key === 'Escape'
          || event.key.startsWith('F')
          || event.ctrlKey
          || event.metaKey
          || event.altKey
        ) {
          return;
        }

        if (event.key === 'Enter' || event.key === ' ' || event.key === 'ArrowDown') {
          event.preventDefault();
          openFlatpickr();
          return;
        }

        event.preventDefault();
      });

      field.addEventListener('paste', event => {
        event.preventDefault();
      });

      field.addEventListener('drop', event => {
        event.preventDefault();
      });

      return;
    }

    const openNativePicker = () => {
      if (typeof field.showPicker !== 'function') return;

      try {
        field.showPicker();
      } catch {
        // Ignore browsers that block programmatic picker opening.
      }
    };

    field.addEventListener('keydown', event => {
      if (
        event.key === 'Tab'
        || event.key === 'Shift'
        || event.key === 'Escape'
        || event.key.startsWith('F')
        || event.ctrlKey
        || event.metaKey
        || event.altKey
      ) {
        return;
      }

      if (event.key === 'Enter' || event.key === ' ' || event.key === 'ArrowDown') {
        event.preventDefault();
        openNativePicker();
        return;
      }

      event.preventDefault();
    });

    field.addEventListener('beforeinput', event => {
      const inputType = event.inputType || '';
      const blockedInputTypes = new Set([
        'insertText',
        'insertFromPaste',
        'insertFromDrop',
        'insertCompositionText',
        'deleteContentBackward',
        'deleteContentForward',
        'deleteByCut'
      ]);

      if (blockedInputTypes.has(inputType)) {
        event.preventDefault();
      }
    });

    field.addEventListener('paste', event => {
      event.preventDefault();
    });

    field.addEventListener('drop', event => {
      event.preventDefault();
    });
  };

  document.querySelectorAll('input[type="date"], input[data-type-datepicker], input.ff-el-datepicker, input[data-lock-date-input]').forEach(setupSelectOnlyDateInput);

  // ── Competition photo carousel ─────────────────────────────
  document.querySelectorAll('.comp-carousel').forEach(carousel => {
    const imgs    = carousel.querySelectorAll('.comp-carousel-img');
    const thumbs  = carousel.querySelectorAll('.comp-thumb');
    const counter = carousel.querySelector('.comp-carousel-counter');
    const prevBtn = carousel.querySelector('.comp-carousel-prev');
    const nextBtn = carousel.querySelector('.comp-carousel-next');
    const total   = imgs.length;
    let current   = 0;

    if (!imgs.length || !thumbs.length || !prevBtn || !nextBtn) {
      return;
    }

    const thumbsContainer = carousel.querySelector('.comp-carousel-thumbs');

    function goTo(index) {
      imgs[current].classList.remove('is-active');
      thumbs[current].classList.remove('is-active');
      thumbs[current].setAttribute('aria-selected', 'false');
      current = (index + total) % total;
      imgs[current].classList.add('is-active');
      thumbs[current].classList.add('is-active');
      thumbs[current].setAttribute('aria-selected', 'true');
      if (counter) counter.textContent = `${current + 1} / ${total}`;
      if (thumbsContainer) {
        const thumb = thumbs[current];
        const scrollTarget = thumb.offsetLeft - (thumbsContainer.clientWidth - thumb.offsetWidth) / 2;
        thumbsContainer.scrollTo({ left: Math.max(0, scrollTarget), behavior: 'smooth' });
      }
    }

    prevBtn.addEventListener('click', () => goTo(current - 1));
    nextBtn.addEventListener('click', () => goTo(current + 1));
    thumbs.forEach(thumb => {
      thumb.addEventListener('click', () => goTo(parseInt(thumb.dataset.index, 10)));
    });

    carousel.addEventListener('keydown', e => {
      if (e.key === 'ArrowLeft')  { e.preventDefault(); goTo(current - 1); }
      if (e.key === 'ArrowRight') { e.preventDefault(); goTo(current + 1); }
    });
  });

  const toddlerSessionKeys = ['class', 'bookingType', 'term', 'day', 'time', 'price', 'availability'];

  function getBookingTypeLabel(value) {
    return value === 'trial' ? 'Trial lesson' : 'Full-term Booking';
  }

  function getSelectedSessionFromUrl(search = window.location.search) {
    const params = new URLSearchParams(search);
    const session = {};

    toddlerSessionKeys.forEach(key => {
      const rawValue = (params.get(key) || '').trim();
      if (key === 'bookingType') {
        session[key] = rawValue === 'trial' ? 'trial' : 'full';
        return;
      }

      session[key] = rawValue;
    });

    return session;
  }

  function hasSelectedSession(session) {
    return toddlerSessionKeys
      .filter(key => key !== 'bookingType')
      .every(key => Boolean(session?.[key]));
  }

  function setFieldValues(fields, values, fallbackText = '') {
    fields.forEach(field => {
      const key = field.dataset.sessionField || field.dataset.submittedField;
      const value = values?.[key];
      const resolvedValue = key === 'bookingType' && value ? getBookingTypeLabel(value) : value;
      field.textContent = resolvedValue && String(resolvedValue).trim() ? resolvedValue : fallbackText;
    });
  }

  function normalizeBookingText(value) {
    return typeof value === 'string' ? value.trim() : '';
  }

  function getBookingPageConfig(className) {
    switch (className) {
      case 'Tiddler Gym':
        return {
          classLabel: 'Tiddler Gym',
          classHref: '../classes/tiddler.html#book',
          bookingHref: '../classes/toddler-booking.html',
          missingSessionCopy: 'We could not find a selected Tiddler Gym session for this booking page.',
          confirmationSessionCopy: 'We could not find a selected Tiddler Gym session for this confirmation page.',
          confirmationFallbackCopy: 'You can return to the booking form and complete the enquiry again, or go back to the Tiddler Gym timetable.'
        };
      case 'Mini Gym':
        return {
          classLabel: 'Mini Gym',
          classHref: '../classes/mini-gym.html#book',
          bookingHref: '../classes/toddler-booking.html',
          missingSessionCopy: 'We could not find a selected Mini Gym session for this booking page.',
          confirmationSessionCopy: 'We could not find a selected Mini Gym session for this confirmation page.',
          confirmationFallbackCopy: 'You can return to the booking form and complete the enquiry again, or go back to the Mini Gym timetable.'
        };
      default:
        return {
          classLabel: 'Toddler Gym',
          classHref: '../classes/toddler.html#book',
          bookingHref: '../classes/toddler-booking.html',
          missingSessionCopy: 'We could not find a selected Toddler Gym session for this booking page.',
          confirmationSessionCopy: 'We could not find a selected Toddler Gym session for this confirmation page.',
          confirmationFallbackCopy: 'You can return to the booking form and complete the enquiry again, or go back to the Toddler Gym timetable.'
        };
    }
  }

  function validateToddlerBookingForm(rawValue) {
    if (!rawValue || typeof rawValue !== 'object' || Array.isArray(rawValue)) {
      return null;
    }

    const requiredKeys = ['childName', 'childDob', 'parentName', 'email', 'phone'];
    const bookingData = {};

    for (const key of requiredKeys) {
      const value = rawValue[key];
      if (typeof value !== 'string') {
        return null;
      }

      bookingData[key] = value.trim();
      if (!bookingData[key]) {
        return null;
      }
    }

    const messageValue = rawValue.message;
    bookingData.message = typeof messageValue === 'string' ? messageValue.trim() : '';
    bookingData.bookingType = rawValue.bookingType === 'trial' ? 'trial' : 'full';

    return bookingData;
  }

  function readToddlerBookingForm() {
    try {
      const raw = sessionStorage.getItem('toddlerBookingForm');
      if (!raw) return null;

      return validateToddlerBookingForm(JSON.parse(raw));
    } catch {
      return null;
    }
  }

  // ── Toddler booking links ─────────────────────────────────
  function buildToddlerBookingUrl(trigger) {
    const requiredKeys = ['class', 'term', 'day', 'time', 'price', 'availability'];
    const missingKey = requiredKeys.find(key => {
      const value = trigger?.dataset?.[key];
      return !value || !value.trim();
    });

    if (missingKey) {
      return null;
    }

    const params = new URLSearchParams({
      class: trigger.dataset.class,
      bookingType: trigger.dataset.bookingType === 'trial' ? 'trial' : 'full',
      term: trigger.dataset.term,
      day: trigger.dataset.day,
      time: trigger.dataset.time,
      price: trigger.dataset.price,
      availability: trigger.dataset.availability
    });

    const bookingUrl = new URL('toddler-booking.html', new URL('.', window.location.href));
    bookingUrl.search = params.toString();
    return bookingUrl.toString();
  }

  const applyWaitingListState = link => {
    if (!link) return;
    link.classList.remove('is-disabled');
    link.classList.remove('btn-secondary');
    link.classList.add('cd-waitlist-btn');
    link.classList.add('btn-magenta');
    link.removeAttribute('aria-disabled');
    link.removeAttribute('tabindex');
    link.removeAttribute('data-booking-link');
    link.removeAttribute('data-booking-type');
    link.textContent = 'Join the Waiting List';
    link.setAttribute('href', new URL('../contact/', window.location.href).toString());
    link.setAttribute('data-waitlist-link', '');
  };

  document.querySelectorAll('.cd-booking-table tbody tr').forEach(row => {
    const isFullRow = row.querySelector('.cd-avail.is-full');
    if (!isFullRow) return;

    applyWaitingListState(row.querySelector('.cd-book-btn'));
  });

  document.querySelectorAll('.cd-booking-mobile-card').forEach(card => {
    const availabilityValue = card.querySelector('.cd-booking-mobile-label')?.textContent?.trim() === 'Availability'
      ? card.querySelector('.cd-booking-mobile-value')?.textContent?.trim()
      : Array.from(card.querySelectorAll('.cd-booking-mobile-stat')).find(stat =>
          stat.querySelector('.cd-booking-mobile-label')?.textContent?.trim() === 'Availability'
        )?.querySelector('.cd-booking-mobile-value')?.textContent?.trim();

    if (availabilityValue === 'Full') {
      applyWaitingListState(card.querySelector('.cd-book-btn'));
    }
  });

  document.querySelectorAll('a.is-disabled').forEach(applyWaitingListState);

  document.querySelectorAll('[data-booking-link]').forEach(link => {
    const bookingUrl = buildToddlerBookingUrl(link);
    if (bookingUrl) {
      link.setAttribute('href', bookingUrl);
    }
  });

  // ── Dynamic Booking Page (Universal - Server-side rendered) ──
  const dynamicBookingPage = document.querySelector('[data-page-root$="-booking"]:not([data-page-root$="-confirmation"])');

  if (dynamicBookingPage) {
    const priceField = dynamicBookingPage.querySelector('[data-session-field="price"]');
    const bookingTypeField = dynamicBookingPage.querySelector('[data-session-field="bookingType"]');
    const bookingTypeInputs = dynamicBookingPage.querySelectorAll('input[name="booking-type"]');

    if (priceField && bookingTypeInputs.length > 0) {
      const fullPrice = priceField.dataset.priceFull || '';
      const trialPrice = priceField.dataset.priceTrial || '';
      const enableTrial = priceField.dataset.enableTrial === 'true';

      function updateBookingDisplay(type) {
        if (type === 'trial' && trialPrice && enableTrial) {
          if (priceField) priceField.textContent = trialPrice;
          if (bookingTypeField) bookingTypeField.textContent = bookingTypeField?.dataset.labelTrial || 'Trial lesson';
        } else {
          if (priceField) priceField.textContent = fullPrice;
          if (bookingTypeField) bookingTypeField.textContent = bookingTypeField?.dataset.labelFull || 'Full-term Booking';
        }
      }

      bookingTypeInputs.forEach(input => {
        input.addEventListener('change', () => {
          if (input.checked) {
            updateBookingDisplay(input.value);
          }
        });
      });
    }

    const form = dynamicBookingPage.querySelector('[data-booking-form]');

    if (form) {
      const bookingFieldNames = ['child-name', 'child-dob', 'parent-name', 'email', 'phone', 'message'];
      const bookingFields = bookingFieldNames
        .map(fieldName => form.querySelector(`[name="${fieldName}"]`))
        .filter(Boolean);

      const getFieldErrorElement = field => form.querySelector(`[data-field-error-for="${field.name}"]`);

      const setFieldError = (field, message = '') => {
        const errorNode = getFieldErrorElement(field);
        field.setCustomValidity(message);

        if (message) {
          field.setAttribute('aria-invalid', 'true');
        } else {
          field.removeAttribute('aria-invalid');
        }

        if (errorNode) {
          errorNode.textContent = message;
          errorNode.hidden = !message;
        }
      };

      const validateField = field => {
        if (!field) {
          return true;
        }

        const value = typeof field.value === 'string' ? field.value.trim() : '';
        let message = '';

        if (field.required && !value) {
          message = 'This field is required';
        } else if (field.validity.typeMismatch || field.validity.tooLong) {
          message = field.validationMessage;
        }

        setFieldError(field, message);
        return !message;
      };

      bookingFields.forEach(field => {
        const revalidate = () => {
          if (field.value || field.getAttribute('aria-invalid') === 'true') {
            validateField(field);
          }
        };

        field.addEventListener('blur', () => {
          validateField(field);
        });

        field.addEventListener('input', revalidate);
        field.addEventListener('change', revalidate);
      });

      form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const payButton = form.querySelector('.cd-pay-button');
        const payText = form.querySelector('.cd-pay-text');
        const payLoading = form.querySelector('.cd-pay-loading');
        const errorEl = document.getElementById('tjs-booking-stripe-errors');

        const setLoading = (loading) => {
          if (payButton) payButton.disabled = loading;
          if (payText) payText.style.display = loading ? 'none' : 'inline';
          if (payLoading) payLoading.style.display = loading ? 'inline-flex' : 'none';
        };
        const showError = (msg) => {
          const text = msg || 'Payment failed. Please try again.';
          if (errorEl) {
            errorEl.textContent = text;
            errorEl.hidden = !text;
          } else {
            alert(text);
          }
        };
        const clearError = () => {
          if (errorEl) {
            errorEl.textContent = '';
            errorEl.hidden = true;
          }
        };

        clearError();

        const requiredFields = ['child-name', 'child-dob', 'parent-name', 'email', 'phone'];
        const firstInvalidField = requiredFields
          .map(fieldName => form.querySelector(`[name="${fieldName}"]`))
          .find(field => field && !validateField(field));

        if (firstInvalidField) {
          firstInvalidField.focus();
          return;
        }

        const bootstrap = window.tjsBookingStripe;
        if (!bootstrap || !bootstrap.stripe || !bootstrap.cardNumber) {
          showError('Payment form is not ready. Please refresh the page.');
          return;
        }

        if (typeof bootstrap.clearFormError === 'function') {
          bootstrap.clearFormError();
        }

        if (typeof bootstrap.validateRequiredFields === 'function' && !bootstrap.validateRequiredFields()) {
          return;
        }

        setLoading(true);

        const formData = new FormData(form);

        // Save form data for the confirmation page to read from sessionStorage.
        try {
          const bookingData = {};
          for (const [key, value] of formData.entries()) {
            if (value && String(value).trim() !== '') {
              bookingData[key] = String(value).trim();
            }
          }
          sessionStorage.setItem('tjsBookingForm', JSON.stringify(bookingData));
        } catch (err) {
          console.error('Failed to save form data:', err);
        }

        try {
          // Step 1: create pending WC order + Stripe PaymentIntent on the server.
          formData.set('action', 'tjs_create_booking_payment');
          const createRes = await fetch(tjs_ajax_object.ajaxurl, {
            method: 'POST',
            body: formData
          });
          const createJson = await createRes.json();
          if (!createJson.success) {
            throw new Error(createJson.data?.message || 'Failed to initialise payment.');
          }
          const { client_secret, order_id, confirmation_url } = createJson.data;

          // Step 2: confirm the card payment with Stripe.
          const billingDetails = {
            name: formData.get('parent-name') || '',
            email: formData.get('email') || '',
            phone: formData.get('phone') || ''
          };
          const confirmResult = await bootstrap.stripe.confirmCardPayment(client_secret, {
            payment_method: {
              card: bootstrap.cardNumber,
              billing_details: billingDetails
            }
          });
          if (confirmResult.error) {
            throw new Error(confirmResult.error.message || 'Card was declined.');
          }
          if (!confirmResult.paymentIntent || confirmResult.paymentIntent.status !== 'succeeded') {
            throw new Error('Payment could not be completed.');
          }

          // Step 3: tell the server to mark the WC order paid.
          const finalizeData = new FormData();
          finalizeData.set('action', 'tjs_finalize_booking_payment');
          finalizeData.set('booking_nonce', formData.get('booking_nonce'));
          finalizeData.set('order_id', order_id);
          finalizeData.set('payment_intent_id', confirmResult.paymentIntent.id);

          const finalizeRes = await fetch(tjs_ajax_object.ajaxurl, {
            method: 'POST',
            body: finalizeData
          });
          const finalizeJson = await finalizeRes.json();
          if (!finalizeJson.success) {
            throw new Error(finalizeJson.data?.message || 'Unable to confirm payment.');
          }

          window.location.href = confirmation_url;
        } catch (error) {
          console.error('Booking error:', error);
          showError(error.message);
          setLoading(false);
        }
      });
    }
  }

  // ── Dynamic Confirmation Page (Universal - Server + Client) ──
  const dynamicConfirmationPage = document.querySelector('[data-page-root$="-confirmation"]');

  if (dynamicConfirmationPage) {
    const submittedFields = dynamicConfirmationPage.querySelectorAll('[data-submitted-field]');
    let bookingData = null;

    try {
      const stored = sessionStorage.getItem('tjsBookingForm');
      if (stored) {
        bookingData = JSON.parse(stored);
      }
    } catch (err) {
      console.error('Failed to read form data:', err);
    }

    if (submittedFields.length && bookingData) {
      submittedFields.forEach(field => {
        const key = field.dataset.submittedField;
        if (key && bookingData[key]) {
          field.textContent = bookingData[key];
        }
      });

      const bookingTypeField = dynamicConfirmationPage.querySelector('[data-session-field="bookingType"]');
      const priceField = dynamicConfirmationPage.querySelector('[data-session-field="price"]');
      
      if (bookingTypeField && bookingData['booking-type']) {
        const typeLabel = bookingData['booking-type'] === 'trial' ? 'Trial lesson' : 'Full-term Booking';
        bookingTypeField.textContent = typeLabel;
      }

      if (priceField && bookingData['booking-type']) {
        const isTrial = bookingData['booking-type'] === 'trial';
        const trialPrice = priceField.dataset.priceTrial || '';
        
        if (isTrial && trialPrice) {
          priceField.textContent = trialPrice;
        } else {
          priceField.textContent = priceField.dataset.priceFull || '';
        }
      }
    }

    try {
      sessionStorage.removeItem('tjsBookingForm');
    } catch (err) {
      // Ignore cleanup errors
    }
  }

  // ── Class detail term tabs ─────────────────────────────────
  const termCards = document.querySelectorAll('.cd-term-card[aria-controls]');
  if (termCards.length) {
    termCards.forEach(card => {
      card.addEventListener('click', () => {
        const panelId = card.getAttribute('aria-controls');

        // Deactivate all tabs + hide all panels
        termCards.forEach(c => {
          c.classList.remove('is-active');
          c.setAttribute('aria-selected', 'false');
        });
        document.querySelectorAll('.cd-booking-panel').forEach(p => {
          p.classList.remove('is-active');
          p.hidden = true;
        });

        // Activate selected
        card.classList.add('is-active');
        card.setAttribute('aria-selected', 'true');
        const panel = document.getElementById(panelId);
        if (panel) { panel.classList.add('is-active'); panel.hidden = false; }
      });
    });
  }

  // ── T&C Accordion (classes page) ───────────────────────────
  document.querySelectorAll('.tc-trigger').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const isOpen = trigger.getAttribute('aria-expanded') === 'true';
      const panelId = trigger.getAttribute('aria-controls');
      const panel = document.getElementById(panelId);

      // Close all siblings first
      trigger.closest('.tc-accordion').querySelectorAll('.tc-trigger').forEach(t => {
        t.setAttribute('aria-expanded', 'false');
        const p = document.getElementById(t.getAttribute('aria-controls'));
        if (p) p.hidden = true;
      });

      if (!isOpen) {
        trigger.setAttribute('aria-expanded', 'true');
        if (panel) panel.hidden = false;
      }
    });
  });
});
