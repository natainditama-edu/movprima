(function () {
  /* Real TMDB Poster URLs (2:3 portrait) */
  var posters23 = [
    "https://image.tmdb.org/t/p/w500/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg", // Oppenheimer
    "https://image.tmdb.org/t/p/w500/vZloFAK7NmvMGKE7VkF5UHaz0I.jpg", // John Wick 4
    "https://image.tmdb.org/t/p/w500/odJ4hx6g6vBt4lBWKFD1tI8WS4x.jpg", // Top Gun Maverick
    "https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg", // The Batman
    // 'https://image.tmdb.org/t/p/w500/b2C8P1B3LS9RVDqkqJ0IKN77d7g.jpg', // The Lost City
    // 'https://image.tmdb.org/t/p/w500/kWf7GeDHhD0RKTRjh36nNNQfxcm.jpg', // Elvis
    "https://image.tmdb.org/t/p/w500/9Gtg2DzBhmYamXBS1hKAhiwbBKS.jpg", // Dr Strange MOM
    "https://image.tmdb.org/t/p/w500/pIkRyD18kl4FhoCNQuWxWu5cBLM.jpg", // Thor Love Thunder
    "https://image.tmdb.org/t/p/w500/qVygtf2vU15L2yKS4Ke44U4oMdD.jpg", // Bullet Train
    // 'https://image.tmdb.org/t/p/w500/nOr4LMSwJGLMlm9V5zpH3MlGyyh.jpg', // Ambulance
    // 'https://image.tmdb.org/t/p/w500/zhLKlkGX7UQtGm0fMIXBjAkILpr.jpg', // The Northman
    "https://image.tmdb.org/t/p/w500/t6HIqrRAclMCA60NsSmeqe9RmNV.jpg", // Avatar 2
    // 'https://image.tmdb.org/t/p/w500/8bRIfPGDnmWKKmHpRMpTRST6HpD.jpg', // Another Round
    // 'https://image.tmdb.org/t/p/w500/sv1xJUazXoQuIDTqhjAsTagIsGo.jpg', // Black Panther Wakanda
    "https://image.tmdb.org/t/p/w500/r2J02Z2OpNTctfOSN1Ydgii51I3.jpg", // Guardians 3
    // 'https://image.tmdb.org/t/p/w500/iuFNMS8vlJG1YkFD4cLMLJOzQwX.jpg', // Barbie
    "https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg", // Dune Part 2
    "https://image.tmdb.org/t/p/w500/NNxYkU70HPurnNCSiCjYAmacwm.jpg", // Mission Impossible
    "https://image.tmdb.org/t/p/w500/7lTnXOy0iNtBAdRP3TZvaKJ77F6.jpg", // Aquaman 2
    "https://image.tmdb.org/t/p/w500/w3LxiVYdWWRvEVdn5RYq6jIqkb1.jpg", // Everything Everywhere
  ];

  /* Real TMDB 16:9 Backdrops (for 169 & hero) */
  var backdrops = [
    "https://image.tmdb.org/t/p/w780/rLb2cwF3Pazuxaj0sRXQ037tGI1.jpg", // Oppenheimer
    // 'https://image.tmdb.org/t/p/w780/l41gFfLd8yKsJL2B9sSWFJIgK27.jpg', // John Wick 4
    "https://image.tmdb.org/t/p/w780/14QbnygCuTO0vl7CAFmPf1fgZfV.jpg", // Top Gun Maverick
    // 'https://image.tmdb.org/t/p/w780/c4tNS1lGhATBBFJsGP6M4OeHkTv.jpg', // The Lost City
    "https://image.tmdb.org/t/p/w780/xOMo8BRK7PfcJv9JCnx7s5hj0PX.jpg", // Dune Part 2
    "https://image.tmdb.org/t/p/w780/t5zCBSB5xMDKcDqe91qahCOUYVV.jpg", // Avatar 2
    "https://image.tmdb.org/t/p/w780/hZkgoQYus5vegHoetLkCJzb17zJ.jpg", // The Batman
    // 'https://image.tmdb.org/t/p/w780/7gKI9hpEMcZUraUNwR1olgFCaE7.jpg'  // Bullet Train
  ];

  /* Real Series Posters */
  var seriesPosters = [
    "https://image.tmdb.org/t/p/w500/ggFHVNu6YYI5L9pCfOacjizRGt.jpg", // Breaking Bad
    "https://image.tmdb.org/t/p/w500/hlLXt2tOPT6RRnjiUmoxyG1LTFi.jpg", // Chernobyl
    "https://image.tmdb.org/t/p/w500/dDlEmu3EZ0Pgg93K2SVNLCjCSvE.jpg", // Squid Game
    "https://image.tmdb.org/t/p/w500/49WJfeN0moxb9IPfGn8AIqMGskD.jpg", // Stranger Things
    "https://image.tmdb.org/t/p/w500/uKvVjHNqB5VmOrdxqAt2F7J78ED.jpg", // The Last of Us
    "https://image.tmdb.org/t/p/w500/2OMB0ynKlyIenMJWI2Dy9IWT4c.jpg", // Peaky Blinders
    "https://image.tmdb.org/t/p/w500/clnyhPqj1SNgpAdeSS6a6fwE6Bo.jpg", // Game of Thrones
  ];

  /* Real Actor Avatars */
  var avatars = [
    "https://i.pravatar.cc/150?img=11",
    "https://i.pravatar.cc/150?img=25",
    "https://i.pravatar.cc/150?img=33",
    "https://i.pravatar.cc/150?img=47",
    "https://i.pravatar.cc/150?img=52",
  ];

  /* Hero Backdrops (original quality) */
  var heroBackdrops = [
    "https://image.tmdb.org/t/p/original/rLb2cwF3Pazuxaj0sRXQ037tGI1.jpg",
    // 'https://image.tmdb.org/t/p/original/l41gFfLd8yKsJL2B9sSWFJIgK27.jpg',
    "https://image.tmdb.org/t/p/original/14QbnygCuTO0vl7CAFmPf1fgZfV.jpg",
  ];

  function replaceImages() {
    /* Portrait 2:3 movie posters */
    var p23 = document.querySelectorAll('img[src*="poster-23"]');
    p23.forEach(function (img, i) {
      img.src = posters23[i % posters23.length];
      img.style.objectFit = "cover";
    });

    /* 16:9 card thumbnails */
    var p169 = document.querySelectorAll('img[src*="poster-169"]');
    p169.forEach(function (img, i) {
      img.src = backdrops[i % backdrops.length];
      img.style.objectFit = "cover";
    });

    /* Series posters (poster-11) */
    var p11 = document.querySelectorAll('img[src*="poster-11"]');
    p11.forEach(function (img, i) {
      img.src = seriesPosters[i % seriesPosters.length];
      img.style.objectFit = "cover";
    });

    /* Hero backgrounds (1920x1080) */
    var heroes = document.querySelectorAll('img[src*="1920x1080"]');
    heroes.forEach(function (img, i) {
      img.src = heroBackdrops[i % heroBackdrops.length];
    });

    /* Account/avatar images */
    var accounts = document.querySelectorAll('img[src*="account.jpg"]');
    accounts.forEach(function (img, i) {
      img.src = avatars[i % avatars.length];
    });

    /* Footer background */
    var footerBg = document.querySelectorAll('img[src*="footer-bg"]');
    footerBg.forEach(function (img) {
      img.style.display = "none";
    });
  }

  /* Init Lucide Icons */
  function initLucide() {
    if (typeof lucide !== "undefined") {
      lucide.createIcons();
    }
  }

  /* Run after DOM ready */
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", function () {
      initLucide();
      replaceImages();
    });
  } else {
    initLucide();
    replaceImages();
  }

  /* Also run after Owl Carousel clones elements (short delay) */
  setTimeout(function () {
    replaceImages();
    initLucide();
  }, 600);
})();
