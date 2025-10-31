import Chart from 'chart.js/auto';

/*
 * Script de lógica para la generación de reportes demográficos (Edad).
    Coordinar las dos vistas de reporte (Rangos vs. Edad Exacta), realizar llamadas AJAX, renderizar las gráficas Chart.js 
    (gráficas de barras en ambos casos), y manejar la lógica de exportación a PDF al capturar la imagen del canvas.
*/
document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('reporteRoot');
  if (!root) return;

  const urlRangos = root.dataset.chartRangosUrl;
  const urlExact  = root.dataset.chartExactUrl;

  const selDiplomado = document.getElementById('f_diplomado');
  const btnGenerar   = document.getElementById('btnGenerar');

  let chartRangos = null;
  let chartExacta = null;

  const azulFuerte = '#112543';
  
  /*
   * Genera los parámetros de filtro basados en el diplomado seleccionado.
  */
  function getFilterParams() {
      const params = new URLSearchParams();
      const diplomadoId = selDiplomado ? selDiplomado.value : '';
      if (diplomadoId) {
          params.append('diplomado_id', diplomadoId);
      }
      return params.toString();
  }


  /*
   * Carga la gráfica de alumnos agrupados por rangos de edad.
      Realiza una llamada AJAX a la API de rangos, destruye la gráfica anterior y renderiza la nueva gráfica de barras.
  */
  async function cargarGraficaRangos() {
    const params = getFilterParams();
    const fetchUrl = `${urlRangos}?${params}`;
    
    const res = await fetch(fetchUrl, { credentials: 'same-origin' });
    if (!res.ok) return;
    const json = await res.json();

    const ctx = document.getElementById('chartRangos').getContext('2d');
    if (chartRangos) chartRangos.destroy();

    chartRangos = new Chart(ctx, {
      type: 'bar',
      data: { labels: json.labels, datasets: [{ label: 'Alumnos', data: json.data, backgroundColor: azulFuerte, borderColor: azulFuerte }] },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false },
            title: {
                display: true,
                text: 'Alumnos por rangos de edad'
            }
        },
        scales: { 
            y: { 
                beginAtZero: true,
                title: { display: true, text: 'Número de alumnos' } 
            },
            x: {
                title: { display: true, text: 'Rango de edad (años)' } 
            }
        }
      }
    });
  }

  /*
   * Carga la gráfica de alumnos agrupados por edad exacta.
    Realiza una llamada AJAX a la API de edad exacta, destruye la gráfica anterior y renderiza la nueva gráfica de barras.
  */
  async function cargarGraficaExacta() {
    const params = getFilterParams();
    const fetchUrl = `${urlExact}?${params}`;
    
    const res = await fetch(fetchUrl, { credentials: 'same-origin' });
    if (!res.ok) return;
    const json = await res.json();

    const ctx = document.getElementById('chartExacta').getContext('2d');
    if (chartExacta) chartExacta.destroy();

    chartExacta = new Chart(ctx, {
      type: 'bar',
      data: { labels: json.labels, datasets: [{ label: 'Alumnos', data: json.data, backgroundColor: azulFuerte, borderColor: azulFuerte }] },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false },
            title: {
                display: true,
                text: 'Alumnos por edad exacta'
            }
        },
        scales: { 
            y: { 
                beginAtZero: true,
                title: { display: true, text: 'Número de alumnos' }
            },
            x: {
                title: { display: true, text: 'Edad (años)' }
            }
        }
      }
    });
  }

  /*
   * Captura la imagen de un canvas de Chart.js y prepara el formulario para la exportación a PDF.
  */
  function descargarPDF(canvasId, hiddenInputId, formId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return; 

    /* Obtener el nombre del diplomado seleccionado para usarlo como subtítulo del PDF. */
    const diplomadoNombre = selDiplomado.options[selDiplomado.selectedIndex].text;
    const form = document.getElementById(formId);
    
    let subTituloInput = form.querySelector('input[name="subtitulo"]');
    if (!subTituloInput) {
        subTituloInput = document.createElement('input');
        subTituloInput.type = 'hidden';
        subTituloInput.name = 'subtitulo';
        form.appendChild(subTituloInput);
    }
    subTituloInput.value = diplomadoNombre;


    /* Convertir el canvas a Data URL (base64) y asignarlo al campo oculto. */
    const dataUrl = canvas.toDataURL('image/png');
    document.getElementById(hiddenInputId).value = dataUrl;
    form.submit();
  }

  /* Listener para el botón de descarga PDF (Rangos). */
  document.getElementById('btnPDFRangos')?.addEventListener('click', () => {
    descargarPDF('chartRangos', 'chart_data_url_rangos', 'pdfFormRangos');
  });

  /* Listener para el botón de descarga PDF (Edad Exacta). */
  document.getElementById('btnPDFExacta')?.addEventListener('click', () => {
    descargarPDF('chartExacta', 'chart_data_url_exacta', 'pdfFormExacta');
  });

  btnGenerar?.addEventListener('click', () => {
      /* Determina qué gráfica cargar basándose en la pestaña activa */
      const activeTab = document.querySelector('#tabs .tab.active')?.dataset.tab || 'rangos';
      if (activeTab === 'rangos') {
          cargarGraficaRangos();
      } else {
          cargarGraficaExacta();
      }
  });

  /* Inicialización de la lógica de pestañas */
  document.querySelectorAll('#tabs .tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('#tabs .tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      const target = tab.dataset.tab;
      const isRangos = (target === 'rangos');

      document.getElementById('tab-rangos').style.display = isRangos ? 'block' : 'none';
      document.getElementById('tab-exacta').style.display = !isRangos ? 'block' : 'none';

      if (isRangos) cargarGraficaRangos();
      else cargarGraficaExacta();
    });
  });

  cargarGraficaRangos();
});