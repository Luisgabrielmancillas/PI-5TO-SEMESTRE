<script>
(() => {
  const sensorSelect = document.getElementById('sensorSelect');
  const rangeSelect  = document.getElementById('rangeSelect');
  const host         = document.getElementById('chartsHost');

  const urlData = "{{ route('history.data') }}";

  // Mantener charts para destruirlos cuando cambien filtros
  let charts = [];

  function prefersDark() {
    return document.documentElement.classList.contains('dark');
  }

  function palette() {
    // Colores acordes al tema (no super saturados para barras)
    const dark = prefersDark();
    return {
      text:       dark ? '#cbd5e1' : '#334155',
      grid:       dark ? 'rgba(148,163,184,.15)' : 'rgba(100,116,139,.15)',
      border:     dark ? 'rgba(148,163,184,.3)'  : 'rgba(30,41,59,.2)',
      fill:       dark ? 'rgba(99,102,241,.15)' : 'rgba(14,165,233,.15)',
      line:       dark ? '#93c5fd' : '#2563eb',
      bar:        dark ? '#a78bfa' : '#6366f1',
      accent:     dark ? '#34d399' : '#10b981'
    }
  }

  function makeCanvas() {
    const wrap = document.createElement('div');
    wrap.className = 'rounded-xl ring-1 ring-slate-200/60 dark:ring-slate-700/60 p-4 bg-white dark:bg-slate-900';
    const c = document.createElement('canvas');
    c.height = 120;
    wrap.appendChild(c);
    host.appendChild(wrap);
    return c.getContext('2d');
  }

  function clearHost() {
    charts.forEach(ch => ch.destroy());
    charts = [];
    host.innerHTML = '';
  }

  function disableAllIfNeeded() {
    // Si sensor ≠ all, “Todos” en range debe deshabilitarse
    const isAllSensor = sensorSelect.value === 'all';
    [...rangeSelect.options].forEach(opt => {
      if (opt.value === 'all') {
        opt.disabled = !isAllSensor;
      }
    });
    if (!isAllSensor && rangeSelect.value === 'all') {
      rangeSelect.value = 'week';
    }
  }

  async function loadData() {
    disableAllIfNeeded();

    const params = new URLSearchParams({
      sensor: sensorSelect.value,
      range:  rangeSelect.value,
    });

    const res = await fetch(`${urlData}?${params.toString()}`, { headers: {'X-Requested-With':'XMLHttpRequest'} });
    const data = await res.json();

    clearHost();
    const colors = palette();

    if (data.type === 'bar') {
      const ctx = makeCanvas();
      charts.push(new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Promedio',
            data: data.series,
            backgroundColor: colors.bar,
            borderColor: colors.border,
            borderWidth: 1,
          }]
        },
        options: {
          plugins: {
            legend: { labels: { color: colors.text } }
          },
          scales: {
            x: { ticks: { color: colors.text }, grid: { color: colors.grid } },
            y: { ticks: { color: colors.text }, grid: { color: colors.grid } }
          }
        }
      }));
      return;
    }

    if (data.type === 'multi-line') {
      // 6 charts, uno por sensor
      data.payload.forEach(item => {
        const ctx = makeCanvas();
        charts.push(new Chart(ctx, {
          type: 'line',
          data: {
            labels: item.labels,
            datasets: [{
              label: item.label,
              data: item.series,
              tension: .35,
              borderColor: colors.line,
              backgroundColor: colors.fill,
              pointBackgroundColor: colors.accent,
              pointRadius: 3
            }]
          },
          options: {
            plugins: { legend: { labels: { color: colors.text } } },
            scales: {
              x: { ticks: { color: colors.text }, grid: { color: colors.grid } },
              y: { ticks: { color: colors.text }, grid: { color: colors.grid } }
            }
          }
        }))
      });
      return;
    }

    // line simple
    const ctx = makeCanvas();
    charts.push(new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [{
          label: data.label,
          data: data.series,
          tension: .35,
          borderColor: colors.line,
          backgroundColor: colors.fill,
          pointBackgroundColor: colors.accent,
          pointRadius: 3
        }]
      },
      options: {
        plugins: { legend: { labels: { color: colors.text } } },
        scales: {
          x: { ticks: { color: colors.text }, grid: { color: colors.grid } },
          y: { ticks: { color: colors.text }, grid: { color: colors.grid } }
        }
      }
    }));
  }

  // Eventos
  sensorSelect.addEventListener('change', loadData);
  rangeSelect.addEventListener('change', loadData);

  // Re-render cuando el tema cambie (si tu toggle agrega/remueve .dark)
  const obs = new MutationObserver(loadData);
  obs.observe(document.documentElement, { attributes:true, attributeFilter:['class'] });

  // Primer render (con valores del servidor)
  sensorSelect.value = @json($initialSensor);
  rangeSelect.value  = @json($initialRange);
  loadData();
})();
</script>