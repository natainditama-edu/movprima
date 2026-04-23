/* PAGE LOADER */
document.onreadystatechange = function () {
  var state = document.readyState;
  if (state == "complete") {
    setTimeout(function () {
      document.getElementById("interactive");
      document.getElementById("load").classList.add("loaded");
    }, 500);
  }
};

/* MOVIE HOVER VIDEO */
$(".vid").attr("disablePictureInPicture", "true");
let videoPlaying = $(".video").hover(hoverVideo, hideVideo);

function hoverVideo(e) {
  $(".vid", this).get(0).muted = true;
  $(".vid", this).get(0).play();
  $(".vid", this).get(0).volume = 1;
  $(".vid", this).get(0).currentTime = 0;
  $(".vid").prop("muted", true);
  $(".audio-control", this).removeClass("unmute");
}

function hideVideo(e) {
  $(".vid", this).get(0).muted = true;
  $(".vid", this).get(0).pause();
  $(".vid", this).get(0).volume = 0;
  $(".vid", this).get(0).currentTime = 0;
  $(".audio-control", this).removeClass("unmute").addClass("muted");
  $(".vid").prop("muted", false);
}

/* CHANGE PROFILE POP-UP FUNC */
$("#changeprofilebttn").click(function () {
  $(".changeProfileWindow").fadeIn();
});

$(".changeprofileclosebttn").click(function () {
  $(".changeProfileWindow").fadeOut();
});

/* SIDE MENU (ACCOUNT MENU) */
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

/* MAIN NAV FUNC FOR MOBILE RESOLUTION */
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

/* LOAD MORE MOVIE FOR WRAPLIST & RELOAD TO FIX LOAD MORE MOVIE */
$(window).resize(function () {
  location.reload();
});

$(document).ready(function () {
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
});

/* SHOW/HIDE PASSWORD FIELD */
function showPassword() {
  var x = document.getElementById("password");
  var y = document.getElementById("passwordtoggle");
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

/* OWL CAROUSEL SLIDER SETTINGS */
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

$(".owlPopular").owlCarousel({
  stagePadding: 10,
  margin: 10,
  nav: false,
  dots: false,
  responsive: {
    0: {
      items: 3,
    },
    576: {
      items: 4,
    },
    768: {
      items: 5,
    },
    992: {
      items: 6,
    },
    1420: {
      items: 7,
    },
  },
});

$(".owlContinue").owlCarousel({
  stagePadding: 10,
  margin: 10,
  nav: false,
  dots: false,
  responsive: {
    0: {
      items: 2,
    },
    576: {
      items: 3,
    },
    768: {
      items: 4,
    },
    992: {
      items: 5,
    },
  },
});

$(".owlMovies").owlCarousel({
  stagePadding: 10,
  margin: 10,
  nav: false,
  dots: false,
  responsive: {
    0: {
      items: 2,
    },
    576: {
      items: 3,
    },
    768: {
      items: 4,
    },
    992: {
      items: 5,
    },
  },
});

$(".owlSeries").owlCarousel({
  stagePadding: 10,
  margin: 10,
  nav: false,
  dots: false,
  responsive: {
    0: {
      items: 3,
    },
    576: {
      items: 4,
    },
    768: {
      items: 5,
    },
    992: {
      items: 6,
    },
    1420: {
      items: 7,
    },
  },
});

/* OWL CAROUSEL SLIDER FIXER */
$(window).ready(function () {
  if ($(window).width() % 2) {
    $(".owlfix .owl-stage-outer").css("width", "-=2");
    $(".owlfix .owl-stage-outer").css("margin", "auto");
  } else {
    $(".owlfix .owl-stage-outer").css("width", "-=1");
    $(".owlfix .owl-stage-outer").css("margin", "0");
  }
});

/* MOVIE DETAIL MAIN TAB & SEASONS TAB & MOVIE COLLECTION AND SEASONS TAB OWL CAROUSEL SLIDER */
$(".movieDetailTab").click(function (e) {
  $(this).addClass("active").siblings().removeClass("active");
  $($(this).attr("href")).addClass("active").siblings().removeClass("active");
  e.preventDefault();

  $(window).ready(function () {
    if ($(".owlCollection").width() % 2) {
      $(".owlCollection").owlCarousel({
        stagePadding: 10,
        loop: false,
        margin: 10,
        nav: false,
        dots: false,
        responsive: {
          0: {
            items: 2,
          },
          768: {
            items: 2,
          },
          992: {
            items: 3,
          },
        },
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
        responsive: {
          0: {
            items: 2,
          },
          768: {
            items: 2,
          },
          992: {
            items: 3,
          },
        },
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
});

/* MOVIE DETAIL SEASONS TAB */
$(".owlSeasonTab").click(function (e) {
  $(".owlSeasonTab").removeClass("active");
  $(this).addClass("active");
  $($(this).attr("href")).addClass("active").siblings().removeClass("active");
  event.preventDefault();
});

/* PRICE PLAN RESPONSIVE */
$(".newPlan").click(function () {
  $(".newPlan").each(function () {
    $(this).parent().removeClass("active");
    $(this).removeClass("active");
  });
  $(this).parent().addClass("active");
  $(this).addClass("active");
});

/* FOOTER LINKS */
$(".footerLinks .title").click(function () {
  $(this).parent(".nav").toggleClass("open");
});
