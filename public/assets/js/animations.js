(function ($) {
  "use strict";

  /* Wait For Condition Helper */
  function waitFor(getter, cb) {
    if (getter()) {
      cb();
      return;
    }
    var id = setInterval(function () {
      if (getter()) {
        clearInterval(id);
        cb();
      }
    }, 50);
  }

  /* Init Scroll Reset */
  function initScrollReset() {
    /* Prevent Browser From Restoring Scroll Position */
    // if ("scrollRestoration" in history) {
    // history.scrollRestoration = "manual";
    // }
    /* Scroll To Top On Unload */
    // $(window).on("beforeunload", function () {
    // $("html, body").scrollTop(0);
    // });
    /* Force Scroll Top On Load */
    // $(window).on("load", function () {
    // $("html, body").animate({ scrollTop: 0 }, 1);
    // });
  }

  /* Init Lenis Smooth Scroll */
  var lenisInstance = null;
  function initLenis() {
    if (typeof Lenis === "undefined") return;

    lenisInstance = new Lenis({
      duration: 1.2,
      easing: function (t) {
        return Math.min(1, 1.001 - Math.pow(2, -10 * t));
      },
      smoothTouch: false,
      touchMultiplier: 2,
    });

    /* Sync Lenis With GSAP Ticker */
    if (typeof gsap !== "undefined") {
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

  /* Init Lazy Load Images */
  function initLazyImages() {
    var $wrappers = $(".moviePoster, .movieDetailPoster, .indexHeroBackground, .backgroundCover");

    $wrappers.each(function () {
      var $wrapper = $(this);
      var $img = $wrapper.find("img").first();
      if (!$img.length) return;

      var img = $img[0];

      /* Reveal Image Animation */
      function revealImage() {
        $wrapper.removeClass("img-skeleton");
        $img.css("opacity", "");
        if (typeof gsap !== "undefined") {
          gsap.fromTo(img, { opacity: 0.2, scale: 1.03 }, { opacity: 1, scale: 1, duration: 0.5, ease: "power2.out" });
        }
      }

      /* Check If Image Already Cached */
      if (img.complete && img.naturalWidth > 0) {
        revealImage();
      } else {
        /* Hide Image And Show Skeleton */
        $img.css("opacity", "0");
        $wrapper.addClass("img-skeleton");

        $img.one("load", revealImage);
        $img.one("error", function () {
          $wrapper.removeClass("img-skeleton");
          $img.css("opacity", "");
        });
      }
    });
  }

  /* Init Scroll Triggered Animations */
  function initScrollAnimations() {
    if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;

    gsap.registerPlugin(ScrollTrigger);

    /* Wire Lenis To ScrollTrigger */
    if (lenisInstance) {
      lenisInstance.on("scroll", ScrollTrigger.update);
    }

    /* Header Slide Down */
    var $header = $("header");
    if ($header.length) {
      gsap.from($header[0], {
        y: -60,
        opacity: 0,
        duration: 0.65,
        ease: "power3.out",
        clearProps: "opacity,transform",
      });
    }

    /* Section Headings Animation */
    gsap.utils.toArray("section h2, section h3, section .containerLink").forEach(function (el) {
      gsap.from(el, {
        scrollTrigger: { trigger: el, toggleActions: "play none none none" },
        y: 35,
        opacity: 0,
        duration: 0.65,
        ease: "power3.out",
        clearProps: "opacity,transform",
      });
    });

    /* Carousel Cards Animation */
    gsap.utils.toArray(".owl-carousel, .wrapMovieList").forEach(function (carousel) {
      /* Exclude Owl Clones From Animation */
      var cards = $(carousel).find(".owl-item:not(.cloned) .movieItem, .wrapMovieItem").toArray();
      if (!cards.length) return;

      gsap.from(cards, {
        scrollTrigger: { trigger: carousel, toggleActions: "play none none none" },
        y: 45,
        opacity: 0,
        duration: 0.55,
        stagger: 0.07,
        ease: "power2.out",
        clearProps: "opacity,transform",
      });
    });

    /* Hero Content Animation */
    var $heroContent = $(".heroContent").first();
    if ($heroContent.length) {
      gsap.from($heroContent.children().toArray(), {
        y: 25,
        opacity: 0,
        duration: 0.75,
        stagger: 0.12,
        ease: "power3.out",
        clearProps: "opacity,transform",
      });
    }

    /* Detail Hero Animation */
    var $detailHero = $(".movieDetailHeroContainer").first();
    if ($detailHero.length) {
      gsap.from($detailHero.children().toArray(), {
        y: 25,
        opacity: 0,
        duration: 0.75,
        stagger: 0.12,
        ease: "power3.out",
        clearProps: "opacity,transform",
      });
    }

    /* Form Page Animation */
    var $formPage = $(".pageLoginSignin, .pageLoginRegister").first();
    if ($formPage.length) {
      gsap.from($formPage.children().toArray(), {
        y: 18,
        opacity: 0,
        duration: 0.45,
        stagger: 0.07,
        ease: "power2.out",
        clearProps: "opacity,transform",
      });
    }

    /* Footer And Plan Blocks Animation */
    gsap.utils.toArray(".planItem, .boxesItem, .footerLinksRow, .socialLinks").forEach(function (el) {
      gsap.from(el, {
        scrollTrigger: { trigger: el, toggleActions: "play none none none" },
        y: 28,
        opacity: 0,
        duration: 0.55,
        ease: "power2.out",
        clearProps: "opacity,transform",
      });
    });

    /* Wrap Movie Grid Animation */
    gsap.utils.toArray(".wrapMovieItem").forEach(function (el) {
      gsap.from(el, {
        scrollTrigger: { trigger: el, toggleActions: "play none none none" },
        y: 35,
        opacity: 0,
        duration: 0.5,
        ease: "power2.out",
        clearProps: "opacity,transform",
      });
    });

    /* Force Fire All Visible ScrollTriggers */
    ScrollTrigger.refresh();
  }

  /* Init Splash Screen */
  function initSplash() {
    var $splash = $("#splash");
    if (!$splash.length) return;

    var splash = $splash[0];
    var bar = document.getElementById("splash-bar");
    var brand = document.getElementById("splash-brand");
    var tagline = document.getElementById("splash-tagline");

    /* Fallback Without GSAP */
    if (typeof gsap === "undefined") {
      setTimeout(function () {
        $splash.css({ opacity: 0, pointerEvents: "none" });
        setTimeout(function () {
          $splash.hide();
          afterSplash();
        }, 500);
      }, 1800);
      return;
    }

    /* Set Initial Hidden State */
    gsap.set([brand, tagline], { autoAlpha: 0, y: 20 });
    gsap.set(bar, { width: "0%" });

    /* Splash Timeline */
    gsap
      .timeline({
        onComplete: function () {
          /* Fire Animations Upon Splash Exit */
          afterSplash();

          gsap.to(splash, {
            yPercent: -100,
            duration: 0.85,
            ease: "power4.inOut",
            onComplete: function () {
              $splash.hide();
            },
          });
        },
      })
      .to(brand, { autoAlpha: 1, y: 0, duration: 0.6, ease: "power3.out" }, 0.2)
      .to(tagline, { autoAlpha: 1, y: 0, duration: 0.5, ease: "power2.out" }, 0.55)
      .to(bar, { width: "100%", duration: 1.1, ease: "power1.inOut" }, 0.4)
      .to({}, { duration: 0.25 });
  }

  /* Run After Splash Screen */
  function afterSplash() {
    initScrollAnimations();
    initLazyImages();
  }

  /* Boot Application */
  function boot() {
    initScrollReset();
    initLenis();

    // var hasSplash = $("#splash").length > 0;
    var hasSplash = false; // Disabled by user request

    if (hasSplash) {
      /* Wait For GSAP Before Splash */
      waitFor(function () {
        return typeof gsap !== "undefined";
      }, initSplash);
    } else {
      /* Run Directly If No Splash */
      waitFor(function () {
        return typeof gsap !== "undefined" && typeof ScrollTrigger !== "undefined";
      }, afterSplash);
    }
  }

  /* Safe Start Entry Point */
  function safeStart() {
    if (typeof jQuery !== "undefined") {
      /* jQuery Is Ready */
      $ = jQuery;
      $(document).ready(boot);
    } else {
      /* Poll For jQuery */
      var jqPoll = setInterval(function () {
        if (typeof jQuery !== "undefined") {
          clearInterval(jqPoll);
          $ = jQuery;
          $(document).ready(boot);
        }
      }, 50);
    }
  }

  safeStart();
})();
