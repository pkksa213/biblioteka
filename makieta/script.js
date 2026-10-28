'use strict';

document.documentElement.classList.add('js');
const menu = document.querySelector('#menu');
const menuToggle = document.querySelector('.menu-toggle');
const mobileLayout = window.matchMedia('(max-width: 900px)');
const status = document.querySelector('#announcements');

function setMenu(open) {
  menu.dataset.open = String(open);
  menuToggle.setAttribute('aria-expanded', String(open));
}

setMenu(!mobileLayout.matches);
mobileLayout.addEventListener('change', event => {
  const focusWasInMenu = menu.contains(document.activeElement);
  setMenu(!event.matches);
  if (event.matches && focusWasInMenu) menuToggle.focus();
});
menuToggle.addEventListener('click', () => setMenu(menu.dataset.open !== 'true'));
menu.addEventListener('click', event => {
  const link = event.target.closest('a');
  if (!link || !mobileLayout.matches) return;
  setMenu(false);
  const url = new URL(link.href, window.location.href);
  const currentPath = window.location.pathname.replace(/\/$/, '/index.html');
  const samePage = url.origin === window.location.origin && url.pathname === currentPath;
  const target = samePage && url.hash ? document.getElementById(url.hash.slice(1)) : null;
  if (target) {
    target.setAttribute('tabindex', '-1');
    target.focus({ preventScroll: true });
  }
});
document.addEventListener('keydown', event => {
  if (event.key === 'Escape') {
    const openGroup = Array.from(menu.querySelectorAll('details[open]')).find(group => group.contains(document.activeElement));
    if (openGroup) {
      openGroup.open = false;
      openGroup.querySelector('summary').focus();
      return;
    }
  }
  if (event.key === 'Escape' && mobileLayout.matches && menu.dataset.open === 'true') {
    setMenu(false);
    menuToggle.focus();
  }
});

// Natywne details działają także bez JavaScriptu. Wspomagamy zamykanie klawiaturą.
menu.querySelectorAll('details').forEach(group => {
  group.addEventListener('toggle', () => {
    if (!group.open) return;
    menu.querySelectorAll('details').forEach(other => {
      if (other !== group) other.open = false;
    });
  });
  group.addEventListener('focusout', event => {
    if (event.relatedTarget && !group.contains(event.relatedTarget)) group.open = false;
  });
});
document.addEventListener('click', event => {
  if (!menu.contains(event.target)) menu.querySelectorAll('details').forEach(group => { group.open = false; });
});

const filters = document.querySelectorAll('[data-filter]');
if (filters.length) {
  const cards = document.querySelectorAll('[data-category]');
  const count = document.querySelector('#article-count');
  const empty = document.querySelector('#empty-articles');
  const allowed = Array.from(filters, button => button.dataset.filter);
  function filterArticles(category) {
    let visible = 0;
    cards.forEach(card => {
      card.hidden = category !== 'wszystkie' && card.dataset.category !== category;
      if (!card.hidden) visible++;
    });
    filters.forEach(button => button.setAttribute('aria-pressed', String(button.dataset.filter === category)));
    count.textContent = `Liczba przykładowych artykułów: ${visible}.`;
    empty.hidden = visible !== 0;
  }
  const initial = new URLSearchParams(window.location.search).get('kategoria');
  filterArticles(allowed.includes(initial) ? initial : 'wszystkie');
  filters.forEach(button => button.addEventListener('click', () => {
    const category = button.dataset.filter;
    filterArticles(category);
    const url = new URL(window.location.href);
    url.searchParams.set('kategoria', category);
    history.replaceState(null, '', url);
  }));
}

document.querySelector('#font-size').addEventListener('click', event => {
  const button = event.currentTarget;
  const enlarged = button.getAttribute('aria-pressed') !== 'true';
  document.documentElement.style.setProperty('--font-scale', enlarged ? '1.25' : '1');
  button.setAttribute('aria-pressed', String(enlarged));
  button.setAttribute('aria-label', enlarged ? 'Przywróć zwykły rozmiar tekstu' : 'Powiększ tekst');
  button.textContent = enlarged ? 'A−' : 'A+';
  status.textContent = enlarged ? 'Tekst powiększony do 125 procent.' : 'Zwykły rozmiar tekstu.';
});

document.querySelector('#contrast').addEventListener('click', event => {
  const enabled = document.body.classList.toggle('high-contrast');
  event.currentTarget.setAttribute('aria-pressed', String(enabled));
  status.textContent = enabled ? 'Wysoki kontrast włączony.' : 'Wysoki kontrast wyłączony.';
});
