/* Op. Dr. Alper Burak Uslu — tema etkileşimleri
   Alpine.js menü/akordeon için (HTML'de x-data), burada: scroll reveal, sayaç, Swiper, header. */
(function () {
  "use strict";
  var reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ---- Scroll reveal ---- */
  function initReveal() {
    var els = document.querySelectorAll(".reveal");
    if (reduce || !("IntersectionObserver" in window)) {
      els.forEach(function (e) { e.classList.add("is-visible"); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add("is-visible"); io.unobserve(en.target); }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -8% 0px" });
    var vh = window.innerHeight || 800;
    els.forEach(function (e) {
      // ilk ekranda görünen elemanları hemen göster (IO yarışını bekleme)
      if (e.getBoundingClientRect().top < vh * 0.92) e.classList.add("is-visible");
      else io.observe(e);
    });
  }

  /* ---- Sayaç animasyonu ---- */
  function animateCount(el) {
    var target = parseFloat(el.getAttribute("data-count") || "0");
    if (reduce) { el.textContent = format(target); return; }
    var dur = 1600, start = null;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = format(Math.floor(eased * target));
      if (p < 1) requestAnimationFrame(step);
      else el.textContent = format(target);
    }
    requestAnimationFrame(step);
  }
  function format(n) { return n.toLocaleString("tr-TR"); }
  function initCounters() {
    var nums = document.querySelectorAll("[data-count]");
    if (!nums.length) return;
    if (!("IntersectionObserver" in window)) { nums.forEach(animateCount); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { animateCount(en.target); io.unobserve(en.target); }
      });
    }, { threshold: 0.5 });
    var vh = window.innerHeight || 800;
    nums.forEach(function (n) {
      if (n.getBoundingClientRect().top < vh * 0.9) animateCount(n);
      else io.observe(n);
    });
  }

  /* ---- Header scroll durumu ---- */
  function initHeader() {
    var h = document.querySelector("[data-header]");
    if (!h) return;
    function onScroll() { h.classList.toggle("is-scrolled", window.scrollY > 24); }
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  /* ---- Swiper init ---- */
  function initSwipers() {
    if (typeof Swiper === "undefined") return;
    document.querySelectorAll("[data-swiper='testimonials']").forEach(function (el) {
      new Swiper(el, {
        slidesPerView: 1.1, spaceBetween: 20, loop: el.querySelectorAll(".swiper-slide").length > 2,
        autoplay: reduce ? false : { delay: 4200, disableOnInteraction: false },
        speed: 700, grabCursor: true,
        breakpoints: { 768: { slidesPerView: 2, spaceBetween: 24 }, 1024: { slidesPerView: 3, spaceBetween: 28 } },
        pagination: { el: el.querySelector(".swiper-pagination"), clickable: true },
      });
    });
    document.querySelectorAll("[data-swiper='gallery']").forEach(function (el) {
      new Swiper(el, {
        slidesPerView: 1.2, spaceBetween: 16, loop: false, grabCursor: true, speed: 600,
        breakpoints: { 640: { slidesPerView: 2.2 }, 1024: { slidesPerView: 3.2, spaceBetween: 20 } },
        navigation: { nextEl: el.querySelector(".gal-next"), prevEl: el.querySelector(".gal-prev") },
      });
    });
  }

  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }
  ready(function () { initReveal(); initCounters(); initHeader(); initSwipers(); });
})();
