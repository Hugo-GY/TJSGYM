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

  // ── Competition photo carousel ─────────────────────────────
  const carousel = document.querySelector('.comp-carousel');
  if (carousel) {
    const imgs    = carousel.querySelectorAll('.comp-carousel-img');
    const thumbs  = carousel.querySelectorAll('.comp-thumb');
    const counter = carousel.querySelector('.comp-carousel-counter');
    const total   = imgs.length;
    let current   = 0;

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
      // Scroll only the thumbs strip horizontally — never the page
      if (thumbsContainer) {
        const thumb = thumbs[current];
        const scrollTarget = thumb.offsetLeft - (thumbsContainer.clientWidth - thumb.offsetWidth) / 2;
        thumbsContainer.scrollTo({ left: Math.max(0, scrollTarget), behavior: 'smooth' });
      }
    }

    carousel.querySelector('.comp-carousel-prev').addEventListener('click', () => goTo(current - 1));
    carousel.querySelector('.comp-carousel-next').addEventListener('click', () => goTo(current + 1));
    thumbs.forEach(thumb => {
      thumb.addEventListener('click', () => goTo(parseInt(thumb.dataset.index, 10)));
    });

    // Keyboard left/right when carousel is focused
    carousel.addEventListener('keydown', e => {
      if (e.key === 'ArrowLeft')  { e.preventDefault(); goTo(current - 1); }
      if (e.key === 'ArrowRight') { e.preventDefault(); goTo(current + 1); }
    });
  }

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
    link.classList.add('cd-waitlist-btn');
    link.removeAttribute('aria-disabled');
    link.removeAttribute('tabindex');
    link.removeAttribute('data-booking-link');
    link.removeAttribute('data-booking-type');
    link.textContent = 'Join the Waiting List';
    link.setAttribute('href', '../contact.html');
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

    if (availabilityValue === '10 / 10') {
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

  // ── Toddler booking page ──────────────────────────────────
  const bookingPageRoot = document.querySelector('[data-page-root="toddler-booking"]');
  if (bookingPageRoot) {
    const bookingSessionFields = bookingPageRoot.querySelectorAll('[data-session-field]');
    const bookingForm = bookingPageRoot.querySelector('[data-booking-form]');
    const bookingFormShell = bookingPageRoot.querySelector('[data-booking-form-shell]');
    const bookingSessionShell = bookingPageRoot.querySelector('[data-booking-session-shell]');
    const bookingFallback = bookingPageRoot.querySelector('[data-booking-fallback]');
    const bookingClassLabel = bookingPageRoot.querySelector('[data-booking-class-label]');
    const bookingBackLink = bookingPageRoot.querySelector('[data-booking-back-link]');
    const bookingFallbackCopy = bookingPageRoot.querySelector('[data-booking-fallback-copy]');
    const bookingFallbackLink = bookingPageRoot.querySelector('[data-booking-fallback-link]');
    const selectedSession = getSelectedSessionFromUrl();
    const sessionIsComplete = hasSelectedSession(selectedSession);
    const bookingTypeInputs = bookingPageRoot.querySelectorAll('input[name="booking-type"]');
    const bookingConfig = getBookingPageConfig(selectedSession.class);

    if (bookingClassLabel) {
      bookingClassLabel.textContent = bookingConfig.classLabel;
    }

    if (bookingBackLink) {
      bookingBackLink.href = bookingConfig.classHref;
      bookingBackLink.textContent = `← Back to ${bookingConfig.classLabel}`;
    }

    if (bookingFallbackCopy) {
      bookingFallbackCopy.textContent = bookingConfig.missingSessionCopy;
    }

    if (bookingFallbackLink) {
      bookingFallbackLink.href = bookingConfig.classHref;
      bookingFallbackLink.textContent = `Return to ${bookingConfig.classLabel}`;
    }

    bookingTypeInputs.forEach(input => {
      input.checked = input.value === selectedSession.bookingType;
    });

    const syncBookingType = nextValue => {
      selectedSession.bookingType = nextValue === 'trial' ? 'trial' : 'full';
      if (bookingSessionFields.length) {
        setFieldValues(bookingSessionFields, selectedSession, 'No session selected');
      }
    };

    if (bookingSessionFields.length) {
      setFieldValues(bookingSessionFields, selectedSession, 'No session selected');
    }

    bookingTypeInputs.forEach(input => {
      input.addEventListener('change', () => {
        if (input.checked) {
          syncBookingType(input.value);
        }
      });
    });

    if (bookingFormShell && bookingSessionShell && bookingFallback) {
      if (!sessionIsComplete) {
        bookingSessionShell.hidden = true;
        bookingFormShell.hidden = true;
        bookingFallback.hidden = false;
      } else {
        bookingFallback.hidden = true;
      }
    }

    bookingForm?.addEventListener('submit', event => {
      event.preventDefault();

      if (!sessionIsComplete) {
        return;
      }

      const formData = new FormData(bookingForm);
      const bookingData = {
        bookingType: normalizeBookingText(formData.get('booking-type')),
        childName: normalizeBookingText(formData.get('child-name')),
        childDob: normalizeBookingText(formData.get('child-dob')),
        parentName: normalizeBookingText(formData.get('parent-name')),
        email: normalizeBookingText(formData.get('email')),
        phone: normalizeBookingText(formData.get('phone')),
        message: normalizeBookingText(formData.get('message'))
      };

      try {
        sessionStorage.setItem('toddlerBookingForm', JSON.stringify(bookingData));
      } catch {
        // Continue to confirmation so the page can render its fallback state.
      }

      const nextUrl = new URL('toddler-booking-confirmation.html', new URL('.', window.location.href));
      const nextParams = new URLSearchParams();
      syncBookingType(bookingData.bookingType);
      toddlerSessionKeys.forEach(key => {
        nextParams.set(key, selectedSession[key]);
      });
      nextUrl.search = nextParams.toString();
      window.location.href = nextUrl.toString();
    });
  }

  // ── Toddler booking confirmation page ─────────────────────
  const confirmationPageRoot = document.querySelector('[data-page-root="toddler-booking-confirmation"]');
  if (confirmationPageRoot) {
    const confirmationSessionFields = confirmationPageRoot.querySelectorAll('[data-session-field]');
    const confirmationSubmittedFields = confirmationPageRoot.querySelectorAll('[data-submitted-field]');
    const confirmationFallback = confirmationPageRoot.querySelector('[data-confirmation-fallback]');
    const confirmationFallbackTitle = confirmationPageRoot.querySelector('[data-confirmation-fallback-title]');
    const confirmationFallbackSession = confirmationPageRoot.querySelector('[data-confirmation-fallback-session]');
    const confirmationFallbackBooking = confirmationPageRoot.querySelector('[data-confirmation-fallback-booking]');
    const confirmationClassLabel = confirmationPageRoot.querySelector('[data-confirmation-class-label]');
    const confirmationBackLink = confirmationPageRoot.querySelector('[data-confirmation-back-link]');
    const confirmationFallbackCopy = confirmationPageRoot.querySelector('[data-confirmation-fallback-copy]');
    const confirmationFormLink = confirmationPageRoot.querySelector('[data-confirmation-form-link]');
    const confirmationClassLink = confirmationPageRoot.querySelector('[data-confirmation-class-link]');
    const confirmationSessionShell = confirmationPageRoot.querySelector('[data-confirmation-session-shell]');
    const confirmationSubmittedShell = confirmationPageRoot.querySelector('[data-confirmation-submitted-shell]');
    const selectedSession = getSelectedSessionFromUrl();
    const bookingData = readToddlerBookingForm();
    const hasSession = hasSelectedSession(selectedSession);
    const hasBookingData = Boolean(bookingData);
    const bookingConfig = getBookingPageConfig(selectedSession.class);

    if (confirmationClassLabel) {
      confirmationClassLabel.textContent = bookingConfig.classLabel;
    }

    if (confirmationBackLink) {
      confirmationBackLink.href = bookingConfig.bookingHref;
    }

    if (confirmationFallbackSession) {
      confirmationFallbackSession.textContent = bookingConfig.confirmationSessionCopy;
    }

    if (confirmationFallbackCopy) {
      confirmationFallbackCopy.textContent = bookingConfig.confirmationFallbackCopy;
    }

    if (confirmationFormLink) {
      confirmationFormLink.href = bookingConfig.bookingHref;
    }

    if (confirmationClassLink) {
      confirmationClassLink.href = bookingConfig.classHref;
      confirmationClassLink.textContent = `Return to ${bookingConfig.classLabel}`;
    }

    if (confirmationSessionFields.length) {
      setFieldValues(confirmationSessionFields, selectedSession, 'No session selected');
    }

    if (confirmationSubmittedFields.length) {
      setFieldValues(confirmationSubmittedFields, bookingData, 'No details available');
    }

    if (
      confirmationFallback &&
      confirmationFallbackTitle &&
      confirmationFallbackSession &&
      confirmationFallbackBooking &&
      confirmationSessionShell &&
      confirmationSubmittedShell
    ) {
      confirmationFallback.hidden = hasSession && hasBookingData;
      confirmationSessionShell.hidden = !hasSession;
      confirmationSubmittedShell.hidden = !hasSession || !hasBookingData;

      if (!hasSession && !hasBookingData) {
        confirmationFallbackTitle.textContent = 'Booking details unavailable';
        confirmationFallbackSession.hidden = false;
        confirmationFallbackBooking.hidden = false;
      } else if (!hasSession) {
        confirmationFallbackTitle.textContent = 'No session selected';
        confirmationFallbackSession.hidden = false;
        confirmationFallbackBooking.hidden = true;
      } else if (!hasBookingData) {
        confirmationFallbackTitle.textContent = 'Booking details unavailable';
        confirmationFallbackSession.hidden = true;
        confirmationFallbackBooking.hidden = false;
      } else {
        confirmationFallbackTitle.textContent = 'Booking details unavailable';
        confirmationFallbackSession.hidden = true;
        confirmationFallbackBooking.hidden = true;
      }
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
