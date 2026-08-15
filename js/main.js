// EduTrack Pro — main.js

// Live clock
(function clock() {
  const el = document.getElementById('clock');
  if (el) el.textContent = new Date().toLocaleTimeString('en-IN', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
  setTimeout(clock, 1000);
})();

// Sidebar toggle
const sidebar = document.getElementById('sidebar');
const toggler = document.getElementById('toggler');
const overlay = document.getElementById('overlay');

if (toggler && sidebar) {
  toggler.addEventListener('click', () => {
    if (window.innerWidth <= 992) {
      sidebar.classList.toggle('open');
      overlay.classList.toggle('on');
    } else {
      const collapsed = sidebar.style.transform === 'translateX(-100%)';
      sidebar.style.transform = collapsed ? '' : 'translateX(-100%)';
      document.querySelector('.main-wrap').style.marginLeft = collapsed ? 'var(--sidebar-w)' : '0';
    }
  });
  if (overlay) overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('on');
  });
}

// Chart.js global defaults
if (typeof Chart !== 'undefined') {
  Chart.defaults.font.family = 'DM Sans';
  Chart.defaults.color = '#6b7fa8';
  Chart.defaults.plugins.legend.labels.boxWidth  = 12;
  Chart.defaults.plugins.legend.labels.padding   = 16;
  Chart.defaults.plugins.legend.labels.font      = { family: 'DM Sans', size: 12 };
  Chart.defaults.plugins.tooltip.backgroundColor = '#0d1f3c';
  Chart.defaults.plugins.tooltip.borderColor     = '#1e3a6e';
  Chart.defaults.plugins.tooltip.borderWidth     = 1;
  Chart.defaults.plugins.tooltip.titleFont       = { family: 'Poppins', weight: '700', size: 13 };
  Chart.defaults.plugins.tooltip.bodyFont        = { family: 'DM Sans', size: 12 };
  Chart.defaults.plugins.tooltip.padding         = 12;
  Chart.defaults.plugins.tooltip.cornerRadius    = 8;
}

// Auto-dismiss alerts
document.querySelectorAll('.alert').forEach(a => {
  setTimeout(() => { a.style.transition = 'opacity 0.4s'; a.style.opacity = '0'; setTimeout(() => a.remove(), 400); }, 5000);
});

// Confirm delete
function confirmDelete(url, name) {
  if (confirm('Delete "' + name + '"?\nThis cannot be undone.')) window.location.href = url;
}

// Table search
function initSearch(inputId, tableId) {
  const inp = document.getElementById(inputId);
  const tbl = document.getElementById(tableId);
  if (!inp || !tbl) return;
  inp.addEventListener('input', () => {
    const q = inp.value.toLowerCase();
    tbl.querySelectorAll('tbody tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
  });
}

// Progress bars animate
document.querySelectorAll('[data-width]').forEach(el => {
  const w = el.getAttribute('data-width');
  el.style.width = '0';
  setTimeout(() => { el.style.width = w + '%'; }, 300);
});
