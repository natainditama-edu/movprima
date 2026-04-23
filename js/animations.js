/**
 * animations.js — MovPrima GSAP Animation Suite
 *
 * Phases:
 *  1. Branded Splash / Preloader
 *  2. Scroll Reset (via jQuery)
 *  3. Lenis Smooth Scroll
 *  4. Scroll-Triggered Animations (default threshold)      ← runs AFTER splash
 *  5. Lazy Image Loading with Skeleton shimmer → Fade-in    ← runs AFTER splash
 */

(function ($) {
  'use strict';

  /* ─────────────────────────────────────────
   *  HELPERS
   * ───────────────────────────────────────── */

  /** Poll until getter() returns truthy, then call cb. */
  function waitFor(getter, cb) {
    var id = setInterval(function () {
      if (getter()) {
        clearInterval(id);
        cb();
      }
    }, 50);
  }

  /* ─────────────────────────────────────────
   *  PHASE 2 — SCROLL RESET (jQuery)
   *  Ensures every page-load starts at top.
   * ───────────────────────────────────────── */
  function initScrollReset() {
    // Prevent browser from restoring scroll position
    if ('scrollRestoration' in history) {
      history.scrollRestoration = 'manual';
    }
    $(window).on('beforeunload', function () {
      $('html, body').scrollTop(0);
    });
    // Force scroll top on load
    $(window).on('load', function () {
      $('html, body').animate({ scrollTop: 0 }, 1);
    });
  }

  /* ─────────────────────────────────────────
   *  PHASE 3 — LENIS SMOOTH SCROLL
   * ───────────────────────────────────────── */
  var lenisInstance = null;

  function initLenis() {
    if (typeof Lenis === 'undefined') return;

    lenisInstance = new Lenis({
      duration: 1.2,
      easing: function (t) {
        return Math.min(1, 1.001 - Math.pow(2, -10 * t));
      },
      smoothTouch: false,
      touchMultiplier: 2,
    });

    if (typeof gsap !== 'undefined') {
      gsap.ticker.add(function (time) {
        lenisInstance.raf(time * 1000);
      });
      gsap.ticker.lagSmoothing(0);
    } else {
      (function raf(time) {
        lenisInstance.raf(time);
        requestAnimationFrame(raf);
      })(0);
    }
  }

  /* ─────────────────────────────────────────
   *  PHASE 5 — LAZY IMAGE LOADING
   *  Images still loading  → opacity:0 + shimmer skeleton.
   *  Images already cached → instant reveal, no skeleton.
   * ───────────────────────────────────────── */
  function initLazyImages() {
    var $wrappers = $('.moviePoster, .movieDetailPoster, .indexHeroBackground, .backgroundCover');

    $wrappers.each(function () {
      var $wrapper = $(this);
      var $img     = $wrapper.find('img').first();
      if (!$img.length) return;

      var img = $img[0];

      function revealImage() {
        $wrapper.removeClass('img-skeleton');
        $img.css('opacity', ''); // clear inline opacity
        if (typeof gsap !== 'undefined') {
          gsap.fromTo(img,
            { opacity: 0.2, scale: 1.03 },
            { opacity: 1,   scale: 1,    duration: 0.5, ease: 'power2.out' }
          );
        }
      }

      if (img.complete && img.naturalWidth > 0) {
        // Already in browser cache — no skeleton, just reveal
        revealImage();
      } else {
        // Image is still loading: hide it and show skeleton shimmer
        $img.css('opacity', '0');
        $wrapper.addClass('img-skeleton');

        $img.one('load', revealImage);
        $img.one('error', function () {
          $wrapper.removeClass('img-skeleton');
          $img.css('opacity', '');
        });
      }
    });
  }

  /* ─────────────────────────────────────────
   *  PHASE 4 — SCROLL-TRIGGERED ANIMATIONS
   *  Called ONLY after splash screen exits.
   *  Threshold: element at 40% from bottom.
   * ───────────────────────────────────────── */
  function initScrollAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    // Wire Lenis → ScrollTrigger
    if (lenisInstance) {
      lenisInstance.on('scroll', ScrollTrigger.update);
    }
    // Default ScrollTrigger threshold (top of element hits bottom of viewport)

    /* ── Header slide-down (immediate, no scroll trigger) ── */
    var $header = $('header');
    if ($header.length) {
      gsap.from($header[0], {
        y: -60,
        opacity: 0,
        duration: 0.65,
        ease: 'power3.out',
        clearProps: 'opacity,transform',
      });
    }

    /* ── Section headings & containerLink ── */
    gsap.utils.toArray('section h2, section h3, section .containerLink').forEach(function (el) {
      gsap.from(el, {
        scrollTrigger: { trigger: el, toggleActions: 'play none none none' },
        y: 35,
        opacity: 0,
        duration: 0.65,
        ease: 'power3.out',
        clearProps: 'opacity,transform',
      });
    });

    /* ── Carousel cards (staggered, originals only — skip owl clones) ── */
    gsap.utils.toArray('.owl-carousel, .wrapMovieList').forEach(function (carousel) {
      // Exclude .owl-item.cloned children — owl clones lack ScrollTrigger
      var cards = $(carousel)
        .find('.owl-item:not(.cloned) .movieItem, .wrapMovieItem')
        .toArray();
      if (!cards.length) return;

      gsap.from(cards, {
        scrollTrigger: { trigger: carousel, toggleActions: 'play none none none' },
        y: 45,
        opacity: 0,        // only opacity, NOT autoAlpha (avoids visibility:hidden on clones)
        duration: 0.55,
        stagger: 0.07,
        ease: 'power2.out',
        clearProps: 'opacity,transform', // restore after animation so owl can manage layout
      });
    });

    /* ── Hero content — plays instantly on splash exit ── */
    var $heroContent = $('.heroContent').first();
    if ($heroContent.length) {
      gsap.from($heroContent.children().toArray(), {
        y: 25,
        opacity: 0,
        duration: 0.75,
        stagger: 0.12,
        ease: 'power3.out',
        clearProps: 'opacity,transform',
      });
    }

    /* ── Movie detail hero — plays instantly on splash exit ── */
    var $detailHero = $('.movieDetailHeroContainer').first();
    if ($detailHero.length) {
      gsap.from($detailHero.children().toArray(), {
        y: 25,
        opacity: 0,
        duration: 0.75,
        stagger: 0.12,
        ease: 'power3.out',
        clearProps: 'opacity,transform',
      });
    }

    /* ── Login / Register form rows — plays instantly on splash exit ── */
    var $formPage = $('.pageLoginSignin, .pageLoginRegister').first();
    if ($formPage.length) {
      gsap.from($formPage.children().toArray(), {
        y: 18,
        opacity: 0,
        duration: 0.45,
        stagger: 0.07,
        ease: 'power2.out',
        clearProps: 'opacity,transform',
      });
    }

    /* ── Footer / plan blocks ── */
    gsap.utils.toArray('.planItem, .boxesItem, .footerLinksRow, .socialLinks').forEach(function (el) {
      gsap.from(el, {
        scrollTrigger: { trigger: el, toggleActions: 'play none none none' },
        y: 28,
        opacity: 0,
        duration: 0.55,
        ease: 'power2.out',
        clearProps: 'opacity,transform',
      });
    });

    /* ── Wrap movie grid (staggered, no delay) ── */
    gsap.utils.toArray('.wrapMovieItem').forEach(function (el) {
      gsap.from(el, {
        scrollTrigger: { trigger: el, toggleActions: 'play none none none' },
        y: 35,
        opacity: 0,
        duration: 0.5,
        ease: 'power2.out',
        clearProps: 'opacity,transform',
      });
    });

    // Force-fire all ScrollTriggers that are already in the viewport
    // This ensures elements visible right after splash animate immediately
    // without requiring the user to scroll first.
    ScrollTrigger.refresh();
  }

  /* ─────────────────────────────────────────
   *  PHASE 1 — BRANDED SPLASH / PRELOADER
   *  Phases 4 & 5 are called INSIDE onComplete.
   * ───────────────────────────────────────── */
  function initSplash() {
    var $splash  = $('#splash');
    if (!$splash.length) return;

    var splash   = $splash[0];
    var bar      = document.getElementById('splash-bar');
    var brand    = document.getElementById('splash-brand');
    var tagline  = document.getElementById('splash-tagline');

    if (typeof gsap === 'undefined') {
      // Graceful fallback without GSAP
      setTimeout(function () {
        $splash.css({ opacity: 0, pointerEvents: 'none' });
        setTimeout(function () { $splash.hide(); afterSplash(); }, 500);
      }, 1800);
      return;
    }

    // Set initial hidden state for animated elements
    gsap.set([brand, tagline], { autoAlpha: 0, y: 20 });
    gsap.set(bar, { width: '0%' });

    gsap.timeline({
      onComplete: function () {
        // ─── Fire page animations the instant splash starts sliding up ───
        // afterSplash() and the slide-up run simultaneously, so content
        // is already animating in while the splash is still leaving.

        // setTimeout(() => {
          afterSplash();
        // }, 290);

        gsap.to(splash, {
          yPercent: -100,
          duration: 0.85,
          ease: 'power4.inOut',
          onComplete: function () {
            $splash.hide(); // clean up DOM after slide-up finishes
          },
        });
      },
    })
    .to(brand,   { autoAlpha: 1, y: 0, duration: 0.6,  ease: 'power3.out'  }, 0.2)
    .to(tagline, { autoAlpha: 1, y: 0, duration: 0.5,  ease: 'power2.out'  }, 0.55)
    .to(bar,     { width: '100%',      duration: 1.1,  ease: 'power1.inOut' }, 0.4)
    .to({},      { duration: 0.25 }); // brief hold before exit
  }

  /* Called as splash begins its exit slide */
  function afterSplash() {
    initScrollAnimations(); // Phase 4
    initLazyImages();       // Phase 5
  }

  /* ─────────────────────────────────────────
   *  BOOT
   * ───────────────────────────────────────── */
  function boot() {
    initScrollReset(); // Phase 2 — immediate, no deps
    initLenis();       // Phase 3 — start smooth scroll early

    var hasSplash = $('#splash').length > 0;

    if (hasSplash) {
      // Wait for GSAP then run the splash; phases 4 & 5 fire inside afterSplash()
      waitFor(
        function () { return typeof gsap !== 'undefined'; },
        initSplash
      );
    } else {
      // No splash: run phases 4 & 5 directly once GSAP is ready
      waitFor(
        function () { return typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined'; },
        afterSplash
      );
    }
  }

  // ── Safe entry point ──────────────────────────────────────────
  // All scripts are `defer` so jQuery may not be defined yet when
  // this file first executes. Poll until $ is available, then boot.
  function safeStart() {
    if (typeof jQuery !== 'undefined') {
      // jQuery is ready — alias it and boot immediately
      /* jshint ignore:start */
      $ = jQuery; // reassign the closed-over $ to the real jQuery
      /* jshint ignore:end */
      $(document).ready(boot);
    } else {
      // Poll every 50ms until jQuery arrives
      var jqPoll = setInterval(function () {
        if (typeof jQuery !== 'undefined') {
          clearInterval(jqPoll);
          $ = jQuery;
          $(document).ready(boot);
        }
      }, 50);
    }
  }

  safeStart();

}()); // plain IIFE — $ is acquired at runtime inside safeStart()
