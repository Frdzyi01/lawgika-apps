/**
 * Lawgika i18n Engine
 * ============================================================
 * Sistem Multilingual Frontend-Only
 * Mendukung: id (default), en, zh
 * Fallback chain: zh → en → id → key literal
 * Penyimpanan: localStorage['lw_language']
 * Penggunaan HTML: <span data-i18n="key"></span>
 *                  <input data-i18n-placeholder="key">
 *                  <element data-i18n-title="key">
 * ============================================================
 */
(function () {
  'use strict';

  /* ── Konfigurasi ── */
  var SUPPORTED = ['id', 'en', 'zh'];
  var DEFAULT_LANG = 'id';
  var STORAGE_KEY = 'lw_language';
  /* Path ke translation files — harus di dalam public/ agar bisa diakses browser */
  var BASE_PATH = '/lang/';

  /* ── State ── */
  var translations = {};   /* { id: {...}, en: {...}, zh: {...} } */
  var currentLang = DEFAULT_LANG;
  var loadedLangs = {};    /* cache flag: { id: true } */

  /* ── Ambil bahasa tersimpan / default ── */
  function getSavedLang() {
    try {
      var saved = localStorage.getItem(STORAGE_KEY);
      if (saved && SUPPORTED.indexOf(saved) !== -1) return saved;
    } catch (e) {}
    return DEFAULT_LANG;
  }

  /* ── Load JSON translation — gunakan inline data dari Blade @json ── */
  function loadLang(lang, callback) {
    if (loadedLangs[lang]) {
      if (callback) callback();
      return;
    }
    /* Prioritas 1: data inline dari window.__lwTranslations (diset oleh Blade @json) */
    if (window.__lwTranslations && window.__lwTranslations[lang]) {
      translations[lang] = window.__lwTranslations[lang];
      loadedLangs[lang] = true;
      if (callback) callback();
      return;
    }
    /* Fallback: XHR (digunakan jika inline tidak tersedia, misal: AJAX partial load) */
    var xhr = new XMLHttpRequest();
    xhr.open('GET', BASE_PATH + lang + '.json?v=1', true);
    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        try {
          translations[lang] = JSON.parse(xhr.responseText);
          loadedLangs[lang] = true;
        } catch (e) {
          console.warn('[i18n] Failed to parse ' + lang + '.json');
        }
      }
      if (callback) callback();
    };
    xhr.onerror = function () {
      console.warn('[i18n] Failed to load ' + lang + '.json');
      if (callback) callback();
    };
    xhr.send();
  }

  /* ── Ambil terjemahan dengan fallback chain ── */
  function t(key, lang) {
    lang = lang || currentLang;
    /* Coba bahasa aktif */
    if (translations[lang] && translations[lang][key] !== undefined) {
      return translations[lang][key];
    }
    /* Fallback: zh → en → id */
    var fallbacks = ['zh', 'en', 'id'];
    for (var i = 0; i < fallbacks.length; i++) {
      var fb = fallbacks[i];
      if (fb !== lang && translations[fb] && translations[fb][key] !== undefined) {
        return translations[fb][key];
      }
    }
    /* Fallback terakhir: kembalikan key literal */
    return key;
  }

  /* ── Terapkan terjemahan ke semua elemen DOM ── */
  function applyTranslations() {
    /* data-i18n → innerHTML (support tag HTML seperti <span>) */
    var elems = document.querySelectorAll('[data-i18n]');
    for (var i = 0; i < elems.length; i++) {
      var key = elems[i].getAttribute('data-i18n');
      elems[i].innerHTML = t(key);
    }

    /* data-i18n-text → textContent (aman dari XSS untuk input biasa) */
    var textElems = document.querySelectorAll('[data-i18n-text]');
    for (var j = 0; j < textElems.length; j++) {
      var tkey = textElems[j].getAttribute('data-i18n-text');
      textElems[j].textContent = t(tkey);
    }

    /* data-i18n-placeholder → placeholder attribute */
    var placeholders = document.querySelectorAll('[data-i18n-placeholder]');
    for (var k = 0; k < placeholders.length; k++) {
      var pkey = placeholders[k].getAttribute('data-i18n-placeholder');
      placeholders[k].setAttribute('placeholder', t(pkey));
    }

    /* data-i18n-title → title attribute */
    var titles = document.querySelectorAll('[data-i18n-title]');
    for (var l = 0; l < titles.length; l++) {
      var tikey = titles[l].getAttribute('data-i18n-title');
      titles[l].setAttribute('title', t(tikey));
    }

    /* data-i18n-aria-label → aria-label attribute */
    var ariaLabels = document.querySelectorAll('[data-i18n-aria]');
    for (var m = 0; m < ariaLabels.length; m++) {
      var akey = ariaLabels[m].getAttribute('data-i18n-aria');
      ariaLabels[m].setAttribute('aria-label', t(akey));
    }

    /* Update document lang attribute */
    document.documentElement.setAttribute('lang', currentLang);

    /* Update meta title, description & keywords */
    var pageKey = document.body.getAttribute('data-page') || 'home';
    var metaTitleKey = 'meta.' + pageKey + '.title';
    var metaDescKey = 'meta.' + pageKey + '.desc';
    var metaKeywordsKey = 'meta.' + pageKey + '.keywords';

    var metaTitle = translations[currentLang] && translations[currentLang][metaTitleKey];
    var metaDesc = translations[currentLang] && translations[currentLang][metaDescKey];
    var metaKeywords = translations[currentLang] && translations[currentLang][metaKeywordsKey];

    if (metaTitle) {
      document.title = metaTitle;
      
      // Update Open Graph & Twitter Titles
      var ogTitle = document.querySelector('meta[property="og:title"]');
      if (ogTitle) ogTitle.setAttribute('content', metaTitle);
      var twitterTitle = document.querySelector('meta[property="twitter:title"]');
      if (twitterTitle) twitterTitle.setAttribute('content', metaTitle);
    }

    if (metaDesc) {
      var descTag = document.querySelector('meta[name="description"]');
      if (descTag) descTag.setAttribute('content', metaDesc);
      
      // Update Open Graph & Twitter Descriptions
      var ogDesc = document.querySelector('meta[property="og:description"]');
      if (ogDesc) ogDesc.setAttribute('content', metaDesc);
      var twitterDesc = document.querySelector('meta[property="twitter:description"]');
      if (twitterDesc) twitterDesc.setAttribute('content', metaDesc);
    }

    if (metaKeywords) {
      var keywordsTag = document.querySelector('meta[name="keywords"]');
      if (keywordsTag) keywordsTag.setAttribute('content', metaKeywords);
    }

    /* Update language switcher active state */
    updateSwitcherUI();
  }

  /* ── Update tampilan language switcher ── */
  function updateSwitcherUI() {
    /* Desktop switcher */
    var currentFlag = document.getElementById('lw-lang-current-flag');
    var currentName = document.getElementById('lw-lang-current-name');
    var langMap = {
      id: { flag: '🇮🇩', name: 'Indonesia' },
      en: { flag: '🇺🇸', name: 'English' },
      zh: { flag: '🇨🇳', name: '中文' }
    };
    if (currentFlag && langMap[currentLang]) {
      currentFlag.textContent = langMap[currentLang].flag;
    }
    if (currentName && langMap[currentLang]) {
      currentName.textContent = langMap[currentLang].name;
    }

    /* Mobile switcher sync */
    var mobileFlag = document.getElementById('lw-lang-mobile-flag');
    var mobileCurrent = document.getElementById('lw-lang-mobile-current');
    if (mobileFlag && langMap[currentLang]) {
      mobileFlag.textContent = langMap[currentLang].flag;
    }
    if (mobileCurrent && langMap[currentLang]) {
      mobileCurrent.textContent = langMap[currentLang].name;
    }

    /* Tandai item aktif di dropdown */
    var items = document.querySelectorAll('[data-lw-lang]');
    for (var i = 0; i < items.length; i++) {
      var itemLang = items[i].getAttribute('data-lw-lang');
      if (itemLang === currentLang) {
        items[i].classList.add('lw-lang-active');
      } else {
        items[i].classList.remove('lw-lang-active');
      }
    }
  }

  /* ── Ganti bahasa ── */
  function switchLanguage(lang) {
    if (SUPPORTED.indexOf(lang) === -1) return;
    if (lang === currentLang && loadedLangs[lang]) return;
    currentLang = lang;

    /* Simpan ke localStorage & Cookie */
    try {
      localStorage.setItem(STORAGE_KEY, lang);
      document.cookie = "lw_language=" + lang + ";path=/;max-age=31536000";
    } catch (e) {}

    /* Tutup dropdown jika terbuka */
    var dropdown = document.getElementById('lw-lang-dropdown');
    if (dropdown) dropdown.classList.remove('lw-lang-open');
    var mobileDropdown = document.getElementById('lw-lang-mobile-dropdown');
    if (mobileDropdown) mobileDropdown.classList.remove('lw-lang-open');

    /* Load jika belum di-cache, lalu apply */
    loadLang(lang, function () {
      applyTranslations();
    });
  }

  /* ── Inisialisasi dropdown toggle ── */
  function initSwitcherEvents() {
    /* Desktop toggle */
    var trigger = document.getElementById('lw-lang-trigger');
    var dropdown = document.getElementById('lw-lang-dropdown');
    if (trigger && dropdown) {
      trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('lw-lang-open');
        /* Tutup mobile jika terbuka */
        var mbd = document.getElementById('lw-lang-mobile-dropdown');
        if (mbd) mbd.classList.remove('lw-lang-open');
      });
    }

    /* Mobile toggle */
    var mobileTrigger = document.getElementById('lw-lang-mobile-trigger');
    var mobileDropdown = document.getElementById('lw-lang-mobile-dropdown');
    if (mobileTrigger && mobileDropdown) {
      mobileTrigger.addEventListener('click', function (e) {
        e.stopPropagation();
        mobileDropdown.classList.toggle('lw-lang-open');
      });
    }

    /* Klik di luar → tutup dropdown */
    document.addEventListener('click', function () {
      var dd = document.getElementById('lw-lang-dropdown');
      var mdd = document.getElementById('lw-lang-mobile-dropdown');
      if (dd) dd.classList.remove('lw-lang-open');
      if (mdd) mdd.classList.remove('lw-lang-open');
    });

    /* Klik item bahasa */
    var langItems = document.querySelectorAll('[data-lw-lang]');
    for (var i = 0; i < langItems.length; i++) {
      langItems[i].addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        switchLanguage(this.getAttribute('data-lw-lang'));
      });
    }
  }

  /* ── Bootstrap: load bahasa aktif + fallback en & id, lalu apply ── */
  function init() {
    currentLang = getSavedLang();
    try {
      document.cookie = "lw_language=" + currentLang + ";path=/;max-age=31536000";
    } catch (e) {}

    /* Pre-load default + bahasa aktif */
    var toLoad = [DEFAULT_LANG];
    if (currentLang !== DEFAULT_LANG) toLoad.push(currentLang);
    /* Selalu preload en sebagai fallback tengah */
    if (toLoad.indexOf('en') === -1) toLoad.push('en');

    var pending = toLoad.length;
    function onLoaded() {
      pending--;
      if (pending <= 0) {
        applyTranslations();
        initSwitcherEvents();
      }
    }
    for (var i = 0; i < toLoad.length; i++) {
      loadLang(toLoad[i], onLoaded);
    }
  }

  /* ── Expose API global ── */
  window.LwI18n = {
    t: t,
    switch: switchLanguage,
    current: function () { return currentLang; },
    apply: applyTranslations
  };

  /* ── Start ── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
