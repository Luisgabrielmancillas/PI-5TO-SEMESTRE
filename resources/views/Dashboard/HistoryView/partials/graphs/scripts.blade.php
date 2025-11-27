<script>
(() => {
  const sensorSelect = document.getElementById('sensorSelect');
  const rangeSelect  = document.getElementById('rangeSelect');
  const host         = document.getElementById('chartsHost');

  const urlData = "{{ route('history.data') }}";
  let charts = [];

  const CHARTS_REFRESH_MS = 10000; // 10s para auto-refresh

  // === tema y paleta base ===
  function prefersDark() {
    return document.documentElement.classList.contains('dark');
  }
  function basePalette() {
    const dark = prefersDark();
    return {
      text:   dark ? '#cbd5e1' : '#334155',
      grid:   dark ? 'rgba(148,163,184,.15)' : 'rgba(100,116,139,.15)',
      border: dark ? 'rgba(148,163,184,.3)'  : 'rgba(30,41,59,.2)',
      fill:   dark ? 'rgba(99,102,241,.15)'  : 'rgba(14,165,233,.15)',
    };
  }

  // === colores por sensor (ajusta nombres si tus labels cambian) ===
  function sensorColor(label) {
    const key = (label || '').toLowerCase();
    const dark = prefersDark();
    const map = {
      'humedad':        dark ? '#22d3ee' : '#0ea5e9', // cyan/sky
      'temp. ambiente': dark ? '#fbbf24' : '#f59e0b', // amber
      'ph':             dark ? '#818cf8' : '#6366f1', // indigo
      'orp':            dark ? '#34d399' : '#10b981', // emerald
      'temp. agua':     dark ? '#f472b6' : '#ec4899', // pink
      'ultrasonico':    dark ? '#fca5a5' : '#ef4444', // red (por si viene sin acento)
      'ultrasónico':    dark ? '#fca5a5' : '#ef4444', // red
    };
    return map[key] || (dark ? '#93c5fd' : '#2563eb');
  }

  // para barras de “todos”, devolvemos color por etiqueta
  function barColors(labels) {
    return labels.map(l => sensorColor(l));
  }

  // === helpers de UI ===
  function humanRangeLabel(value) {
    switch (value) {
      case 'week':     return 'Última semana';
      case 'month':    return 'Último mes';
      case 'semester': return 'Último semestre';
      case 'year':     return 'Último año';
      case 'all':      return 'Todos';
      default:         return value || '—';
    }
  }
  function makeCard() {
    const wrap = document.createElement('div');
    wrap.className = 'rounded-xl ring-1 ring-slate-200/60 dark:ring-slate-700/60 p-4 bg-white dark:bg-slate-900';
    host.appendChild(wrap);
    return wrap;
  }
  function noDataEl(msg) {
    const d = document.createElement('div');
    d.className = 'w-full rounded-lg border border-dashed border-gray-300 dark:border-gray-700 bg-white/60 dark:bg-gray-800/60 p-8 text-center text-sm text-gray-700 dark:text-gray-300';
    d.textContent = msg;
    return d;
  }
  function hasData(payload) {
    const hasLabels   = Array.isArray(payload.labels) && payload.labels.length > 0;
    const hasSomeData = Array.isArray(payload.series) && payload.series.some(v => v !== null && v !== undefined);
    return hasLabels && hasSomeData;
  }
  function renderOrEmpty(cardEl, payload, rangeLabel, sensorLabel = null) {
    if (!hasData(payload)) {
      const what = sensorLabel ? ` del sensor “${sensorLabel}”` : '';
      cardEl.appendChild(noDataEl(`No se encontraron datos para la gráfica${what} con el Filtro: ${rangeLabel}.`));
      return null;
    }
    const c = document.createElement('canvas');
    c.height = 120;
    cardEl.appendChild(c);
    return c.getContext('2d');
  }

  function clearHost() {
    charts.forEach(ch => ch.destroy());
    charts = [];
    host.innerHTML = '';
  }

  function disableAllIfNeeded() {
    const isAllSensor = sensorSelect.value === 'all';
    [...rangeSelect.options].forEach(opt => {
      if (opt.value === 'all') opt.disabled = !isAllSensor;
    });
    if (!isAllSensor && rangeSelect.value === 'all') {
      // regla lógica que ya tenías: "all" solo cuando sensor=all
      rangeSelect.value = 'week';
    }
  }

  async function loadData() {
    if (!sensorSelect || !rangeSelect || !host) return;

    disableAllIfNeeded();

    const params = new URLSearchParams({
      sensor: sensorSelect.value,
      range:  rangeSelect.value,
    });

    const res  = await fetch(`${urlData}?${params.toString()}`, { headers: {'X-Requested-With':'XMLHttpRequest'} });
    const data = await res.json();

    clearHost();
    const p          = basePalette();
    const rangeLabel = humanRangeLabel(rangeSelect.value);

    // === BARRAS (Todos los sensores + “Todos” en datos) ===
    if (data.type === 'bar') {
      const card = makeCard();
      const ctx  = renderOrEmpty(card, { labels: data.labels, series: data.series }, rangeLabel);
      if (!ctx) return;

      charts.push(new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Promedio',
            data: data.series,
            backgroundColor: barColors(data.labels),
            borderColor: p.border,
            borderWidth: 1,
          }]
        },
        options: {
          plugins: { legend: { labels: { color: p.text } } },
          scales: {
            x: { ticks: { color: p.text }, grid: { color: p.grid } },
            y: { ticks: { color: p.text }, grid: { color: p.grid } }
          }
        }
      }));
      return;
    }

    // === MULTI-LÍNEA (Todos los sensores, rango ≠ “Todos”) ===
    if (data.type === 'multi-line') {
      const noneHasData = data.payload.every(item => !hasData(item));
      if (noneHasData) {
        const card = makeCard();
        card.appendChild(noDataEl(`No se encontraron datos para la gráfica con el Filtro: ${rangeLabel}.`));
        return;
      }

      data.payload.forEach(item => {
        const card = makeCard();
        const ctx  = renderOrEmpty(card, { labels: item.labels, series: item.series }, rangeLabel, item.label);
        if (!ctx) return;

        const c = sensorColor(item.label);
        charts.push(new Chart(ctx, {
          type: 'line',
          data: {
            labels: item.labels,
            datasets: [{
              label: item.label,
              data: item.series,
              tension: .35,
              borderColor: c,
              backgroundColor: prefersDark() ? c + '26' : c + '26',
              pointBackgroundColor: c,
              pointRadius: 3
            }]
          },
          options: {
            plugins: { legend: { labels: { color: p.text } } },
            scales: {
              x: { ticks: { color: p.text }, grid: { color: p.grid } },
              y: { ticks: { color: p.text }, grid: { color: p.grid } }
            }
          }
        }));
      });
      return;
    }

    // === LÍNEA simple (sensor específico) ===
    {
      const card = makeCard();
      const ctx  = renderOrEmpty(card, { labels: data.labels, series: data.series }, rangeLabel, data.label);
      if (!ctx) return;

      const c = sensorColor(data.label);
      charts.push(new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [{
            label: data.label,
            data: data.series,
            tension: .35,
            borderColor: c,
            backgroundColor: prefersDark() ? c + '26' : c + '26',
            pointBackgroundColor: c,
            pointRadius: 3
          }]
        },
        options: {
          plugins: { legend: { labels: { color: p.text } } },
          scales: {
            x: { ticks: { color: p.text }, grid: { color: p.grid } },
            y: { ticks: { color: p.text }, grid: { color: p.grid } }
          }
        }
      }));
    }
  }

  // Eventos
  if (sensorSelect) sensorSelect.addEventListener('change', loadData);
  if (rangeSelect)  rangeSelect.addEventListener('change', loadData);

  // Re-render cuando cambie el tema (.dark en <html>)
  const obs = new MutationObserver(() => loadData());
  obs.observe(document.documentElement, { attributes:true, attributeFilter:['class'] });

  // Primer render (respetando lo que viene del back)
  if (sensorSelect) sensorSelect.value = @json($initialSensor);
  if (rangeSelect)  rangeSelect.value  = @json($initialRange);
  loadData();

  // Auto-refresh de gráficas cada 10 segundos (mantiene los filtros actuales)
  setInterval(() => {
    loadData();
  }, CHARTS_REFRESH_MS);
})();
</script>