document.addEventListener('DOMContentLoaded', () => {
  // ── Mobile nav toggle ──────────────────────────────────────
  const toggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  const overlay = document.querySelector('.nav-overlay');

  if (toggle && navLinks) {
    toggle.addEventListener('click', () => {
      const isOpen = navLinks.classList.toggle('open');
      overlay?.classList.toggle('open', isOpen);
      toggle.setAttribute('aria-expanded', isOpen);
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    overlay?.addEventListener('click', closeNav);
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeNav();
    });
  }

  function closeNav() {
    navLinks?.classList.remove('open');
    overlay?.classList.remove('open');
    toggle?.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
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

  // ── Class detail term tabs ─────────────────────────────────
  const termCards = document.querySelectorAll('.cd-term-card');
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
