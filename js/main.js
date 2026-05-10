(() => {
  const nav = document.getElementById('nav');
  const progress = document.getElementById('progress');
  const dot = document.getElementById('cur-dot');
  const ring = document.getElementById('cur-ring');
  const year = document.getElementById('yr');

  if (year) {
    year.textContent = new Date().getFullYear();
  }

  const updateScrollState = () => {
    const max = document.documentElement.scrollHeight - window.innerHeight;
    const pct = max > 0 ? (window.scrollY / max) * 100 : 0;
    if (progress) progress.style.width = `${pct}%`;
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 60);
  };

  updateScrollState();
  window.addEventListener('scroll', updateScrollState, { passive: true });
  window.addEventListener('resize', updateScrollState);

  document.querySelectorAll('[data-target]').forEach((link) => {
    link.addEventListener('click', (event) => {
      const target = document.querySelector(link.dataset.target);
      if (!target) return;
      event.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  const track = document.querySelector('.band-track');
  if (track && !track.dataset.duplicated) {
    track.innerHTML += track.innerHTML;
    track.dataset.duplicated = 'true';
  }

  const prefersFinePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  if (prefersFinePointer && dot && ring) {
    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let ringX = mouseX;
    let ringY = mouseY;

    const setCursorVisible = () => {
      dot.style.opacity = '1';
      ring.style.opacity = '1';
    };

    window.addEventListener('mousemove', (event) => {
      mouseX = event.clientX;
      mouseY = event.clientY;
      dot.style.left = `${mouseX}px`;
      dot.style.top = `${mouseY}px`;
      setCursorVisible();
    }, { passive: true });

    const animateCursor = () => {
      ringX += (mouseX - ringX) * 0.12;
      ringY += (mouseY - ringY) * 0.12;
      ring.style.left = `${ringX}px`;
      ring.style.top = `${ringY}px`;
      requestAnimationFrame(animateCursor);
    };

    animateCursor();

    document.querySelectorAll('a, button, .feature-card, .testimonial-card').forEach((el) => {
      el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
      el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
    });
  }

  const countStats = (stat) => {
    if (stat.dataset.done) return;
    stat.dataset.done = 'true';
    const target = Number(stat.dataset.target || 0);
    const duration = 1300;
    const interval = 24;
    const steps = Math.ceil(duration / interval);
    let tick = 0;

    const timer = window.setInterval(() => {
      tick += 1;
      const progressValue = Math.min(tick / steps, 1);
      const eased = 1 - Math.pow(1 - progressValue, 3);
      stat.textContent = Math.round(target * eased);
      if (progressValue >= 1) {
        window.clearInterval(timer);
        stat.textContent = target;
      }
    }, interval);
  };

  const makeObserver = (selector, options, onShow) => {
    const items = document.querySelectorAll(selector);
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
      items.forEach((item, index) => onShow(item, index));
      return;
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const index = Array.from(items).indexOf(entry.target);
        onShow(entry.target, index);
        observer.unobserve(entry.target);
      });
    }, options);

    items.forEach((item) => observer.observe(item));
  };

  const statsBar = document.querySelector('.stats-bar');
  if (statsBar) {
    if ('IntersectionObserver' in window) {
      const barObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          statsBar.querySelectorAll('.stat-n').forEach(countStats);
          barObserver.unobserve(statsBar);
        });
      }, { threshold: 0.45 });
      barObserver.observe(statsBar);
    } else {
      statsBar.querySelectorAll('.stat-n').forEach(countStats);
    }
  }

  makeObserver('.fu', { threshold: 0.18, rootMargin: '0px 0px -8% 0px' }, (item, index) => {
    item.style.transitionDelay = `${index * 80}ms`;
    item.classList.add('visible');
  });

  makeObserver('.fc', { threshold: 0.16, rootMargin: '0px 0px -8% 0px' }, (item, index) => {
    item.style.transitionDelay = `${index * 80}ms`;
    item.classList.add('visible');
  });

  makeObserver('.tcard', { threshold: 0.18, rootMargin: '0px 0px -8% 0px' }, (item, index) => {
    item.style.transitionDelay = `${index * 100}ms`;
    item.classList.add('visible');
  });

  makeObserver('.step', { threshold: 0.28, rootMargin: '0px 0px -8% 0px' }, (item, index) => {
    item.style.transitionDelay = `${index * 180}ms`;
    item.style.animationDelay = `${index * 180}ms`;
    item.classList.add('visible');
  });
})();
