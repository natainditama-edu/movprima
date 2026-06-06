(function () {
  "use strict";

  /* Console Guard */
  var isLocal = ["localhost", "127.0.0.1"].indexOf(window.location.hostname) !== -1;
  if (!isLocal) {
    var noop = function () {};
    ["log", "warn", "error", "info", "debug", "assert", "table", "trace"].forEach(function (m) {
      try {
        console[m] = noop;
      } catch (e) {}
    });
  }

  /* Initialize All App Logic */
  function initApp() {
    /* Movie Hover Video */
    $(".vid").attr("disablePictureInPicture", "true");
    let videoPlaying = $(".video").hover(hoverVideo, hideVideo);

    function hoverVideo(e) {
      var vid = $(".vid", this).get(0);
      if (!vid) return;
      vid.muted = true;
      var p = vid.play();
      if (p !== undefined) p.catch(function () {});
      vid.volume = 1;
      vid.currentTime = 0;
      $(".vid").prop("muted", true);
      $(".audio-control", this).removeClass("unmute");
    }

    function hideVideo(e) {
      var vid = $(".vid", this).get(0);
      if (!vid) return;
      vid.muted = true;
      vid.pause();
      vid.volume = 0;
      vid.currentTime = 0;
      $(".audio-control", this).removeClass("unmute").addClass("muted");
      $(".vid").prop("muted", false);
    }

    /* Change Profile Pop-Up Func */
    $("#changeprofilebttn").click(function () {
      $(".changeProfileWindow").fadeIn();
    });

    $(".changeprofileclosebttn").click(function () {
      $(".changeProfileWindow").fadeOut();
    });

    /* Side Menu (Account Menu) */
    $(".sideMenuBttn").click(function () {
      $(".sideMenuWindow").fadeToggle();
      $(".accountIcon").toggleClass("off");
      $(".closeSideMenu").toggleClass("on");
    });

    $(".closeSideMenu").click(function () {
      $(".sideMenuWindow").fadeToggle();
      $(".accountIcon").toggleClass("off");
      $(".closeSideMenu").toggleClass("on");
    });

    $(document).on("click", function (event) {
      var $trigger = $(".sideMenuCon");
      if ($trigger !== event.target && !$trigger.has(event.target).length) {
        $(".sideMenuWindow").fadeOut();
        $(".accountIcon").removeClass("off");
        $(".closeSideMenu").removeClass("on");
      }
    });

    /* Main Nav Func For Mobile Resolution */
    $(".mobileNavIcon").click(function () {
      $(".mobileNavWindow").fadeToggle();
      $(".mobileNavIcon").toggleClass("fa-xmark");
    });

    $(document).on("click", function (event) {
      var $trigger = $(".mobileNavCon");
      if ($trigger !== event.target && !$trigger.has(event.target).length) {
        $(".mobileNavWindow").fadeOut();
        $(".mobileNavIcon").last().removeClass("fa-xmark");
      }
    });

    /* Load More Movie For Wraplist & Reload To Fix Load More Movie */
    if ($(window).width() <= 576) {
      $(".hideMovie").slice(0, 8).show();
      $(".loadMoreMovies a").click(function () {
        $(".hideMovie:hidden").slice(0, 4).slideDown("fast");
        if ($(".hideMovie:hidden").length == 0) {
          $(".loadMoreMovies a").fadeOut();
        }
      });
    } else if ($(window).width() > 576 && $(window).width() < 768) {
      $(".hideMovie").slice(0, 12).show();
      $(".loadMoreMovies a").click(function () {
        $(".hideMovie:hidden").slice(0, 6).slideDown("fast");
        if ($(".hideMovie:hidden").length == 0) {
          $(".loadMoreMovies a").fadeOut();
        }
      });
    } else if ($(window).width() >= 768) {
      $(".hideMovie").slice(0, 16).show();
      $(".loadMoreMovies a").click(function () {
        $(".hideMovie:hidden").slice(0, 8).slideDown("fast");
        if ($(".hideMovie:hidden").length == 0) {
          $(".loadMoreMovies a").fadeOut();
        }
      });
    }

    /* Show/Hide Password Field */
    window.showPassword = function () {
      var x = document.getElementById("password");
      var y = document.getElementById("passwordtoggle");
      if (x && y) {
        if (x.type === "password") {
          x.type = "text";
          y.classList.remove("fa-eye");
          y.classList.add("fa-eye-slash");
        } else {
          x.type = "password";
          y.classList.remove("fa-eye-slash");
          y.classList.add("fa-eye");
        }
      }
    };

    /* Owl Carousel Slider Settings */
    $(".owlHero").owlCarousel({
      stagePadding: 0,
      mouseDrag: false,
      touchDrag: false,
      nav: false,
      dots: false,
      items: 1,
      animateIn: "fadeIn",
      animateOut: "fadeOut",
      autoplay: true,
      autoplayTimeout: 5000,
      autoplayHoverPause: false,
      loop: true,
    });

    $(".owlPopular, .owlContinue, .owlMovies, .owlSeries").owlCarousel({
      stagePadding: 10,
      margin: 10,
      nav: true,
      navText: ['<i class="fa-solid fa-chevron-left"></i>', '<i class="fa-solid fa-chevron-right"></i>'],
      dots: false,
      autoWidth: true,
    });

    /* Owl Carousel Slider Fixer */
    if ($(window).width() % 2) {
      $(".owlfix .owl-stage-outer").css("width", "-=2");
      $(".owlfix .owl-stage-outer").css("margin", "auto");
    } else {
      $(".owlfix .owl-stage-outer").css("width", "-=1");
      $(".owlfix .owl-stage-outer").css("margin", "0");
    }

    /* Movie Detail Main Tab & Seasons Tab & Movie Collection And Seasons Tab Owl Carousel Slider */
    $(".movieDetailTab").click(function (e) {
      $(this).addClass("active").siblings().removeClass("active");
      $($(this).attr("href")).addClass("active").siblings().removeClass("active");
      e.preventDefault();

      if ($(".owlCollection").width() % 2) {
        $(".owlCollection").owlCarousel({
          stagePadding: 10,
          loop: false,
          margin: 10,
          nav: false,
          dots: false,
          autoWidth: true,
        });

        $(".owlSeasonTabCon").owlCarousel({
          loop: false,
          autoWidth: true,
          items: 22,
          slideBy: 1,
          nav: false,
        });

        const owl = $(".owlSeasonTabCon");
        $(".next").click(() => owl.trigger("next.owl.carousel"));
        $(".prev").click(() => owl.trigger("prev.owl.carousel"));
        $(".owlCollection .owl-stage-outer").removeAttr("style");
        $(".owlCollection .owl-stage-outer").css("width", "-=2");
        $(".owlCollection .owl-stage-outer").css("margin", "auto");
      } else {
        $(".owlCollection").owlCarousel({
          stagePadding: 10,
          loop: false,
          margin: 10,
          nav: false,
          dots: false,
          autoWidth: true,
        });
        $(".owlSeasonTabCon").owlCarousel({
          loop: false,
          autoWidth: true,
          items: 22,
          slideBy: 1,
          nav: false,
        });

        const owl = $(".owlSeasonTabCon");
        $(".next").click(() => owl.trigger("next.owl.carousel"));
        $(".prev").click(() => owl.trigger("prev.owl.carousel"));
        $(".owlCollection .owl-stage-outer").removeAttr("style");
        $(".owlCollection .owl-stage-outer").css("width", "-=1");
        $(".owlCollection .owl-stage-outer").css("margin", "0");
      }
    });

    /* Movie Detail Seasons Tab */
    $(".owlSeasonTab").click(function (e) {
      $(".owlSeasonTab").removeClass("active");
      $(this).addClass("active");
      $($(this).attr("href")).addClass("active").siblings().removeClass("active");
      e.preventDefault();
    });

    /* Price Plan Responsive */
    $(".newPlan").click(function () {
      $(".newPlan").each(function () {
        $(this).parent().removeClass("active");
        $(this).removeClass("active");
      });
      $(this).parent().addClass("active");
      $(this).addClass("active");
    });

    /* Footer Links */
    $(".footerLinks .title").click(function () {
      $(this).parent(".nav").toggleClass("open");
    });

    /* Owl Carousel Responsive CSS Refresh */
    let resizeTimer;
    $(window).on("resize", function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        $(".owl-carousel").trigger("refresh.owl.carousel");
      }, 250);
    });
  }

  /* Safe Entry Point */
  function safeStart() {
    if (typeof window.jQuery !== "undefined") {
      window.jQuery(document).ready(initApp);
    } else {
      /* Poll For jQuery */
      var jqPoll = setInterval(function () {
        if (typeof window.jQuery !== "undefined") {
          clearInterval(jqPoll);
          window.jQuery(document).ready(initApp);
        }
      }, 50);
    }
  }

  safeStart();
})();
