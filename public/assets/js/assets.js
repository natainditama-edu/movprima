(function () {
  "use strict";

  /* Init Lucide Icons */
  function initLucide() {
    if (typeof lucide !== "undefined") {
      lucide.createIcons();
    }
  }

  /* Handle Broken Images inside Owl Carousel */
  function handleBrokenImages() {
    /* Define Fallback Image URL with Theme Colors */
    var fallbackSrc = "https://placehold.co/600x900/1a1a24/606078?font=oswald&text=Not+Found";

    /* Catch Image Load Errors Dynamically */
    document.addEventListener(
      "error",
      function (e) {
        var target = e.target;
        if (target && target.tagName && target.tagName.toLowerCase() === "img") {
          /* Only Target Images Inside Owl Carousel */
          if (target.closest(".owl-carousel")) {
            /* Prevent Infinite Loop */
            if (target.src !== fallbackSrc) {
              target.src = fallbackSrc;
            }
          }
        }
      },
      true,
    );

    /* Check Existing Images Before Script Execution */
    var checkExistingImages = function () {
      var images = document.querySelectorAll(".owl-carousel img");
      images.forEach(function (img) {
        /* Check If Image Failed To Load */
        if (img.complete && img.naturalWidth === 0) {
          if (img.src !== fallbackSrc) {
            img.src = fallbackSrc;
          }
        }
      });
    };

    checkExistingImages();
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", checkExistingImages);
    }
  }

  /* Run Broken Images Handler */
  handleBrokenImages();

  /* Run After DOM Ready */
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
      initLucide();
    });
  } else {
    initLucide();
  }

  /* Run After Owl Carousel Clones Elements */
  setTimeout(function () {
    initLucide();
  }, 600);
})();
