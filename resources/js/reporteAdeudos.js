import Chart from 'chart.js/auto';

/*
 * Script de lógica para la generación de reportes de Adeudos (por mes/diplomado o por alumno).
  Gestionar la interacción de las pestañas (tabs), inicializar las gráficas Chart.js
  (tanto de barras como de dona), realizar llamadas asíncronas a la API de reportes, y
  manejar los enlaces de descarga de Excel basados en el estado actual de los filtros.
*/
document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('reporteAdeudosRoot');
  if (!root) return;

  const urlChartMes = root.dataset.urlChartMes;
  const urlChartAlumno = root.dataset.urlChartAlumno;
  const urlExportar = root.dataset.urlExportar;

  const selMes = document.getElementById('f_mes');
  const selAnio = document.getElementById('f_anio');
  const inpMatricula = document.getElementById('f_matricula');
  const btnGenerar = document.getElementById('btnGenerar');

  const btnDownloadMes = document.getElementById('btnDownloadMes');
  const btnDownloadAlumno = document.getElementById('btnDownloadAlumno');

  let chartMes = null;
  let chartAlumno = null;

  /*
   * Configura la navegación y la visibilidad de las secciones del reporte (Mes vs Alumno).
      Asigna listeners a los botones de pestaña para cambiar la clase 'active'
      y alternar el estilo 'display' de las secciones del contenido.
  */
  function tabsInit() {
    const tabs = document.querySelectorAll('#tabs .tab');
    const sections = {
      mes: document.getElementById('tab-mes'),
      alumno: document.getElementById('tab-alumno')
    };
    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        const target = tab.dataset.tab;
        sections.mes.style.display    = (target === 'mes') ? 'block' : 'none';
        sections.alumno.style.display = (target === 'alumno') ? 'block' : 'none';
      });
    });
  }


  /*
   * Destruye una instancia de Chart.js si existe.
  */
  function destroyIf(chart) {
    if (chart) chart.destroy();
  }


  /*
   * Carga la gráfica de adeudos por mes y diplomado.
      Realiza la llamada AJAX a la API de reporte, destruye la gráfica anterior, y genera una nueva gráfica de barras 
      con los datos devueltos. También gestiona la visibilidad del botón de descarga Excel.
  */
  async function cargarGraficaMes() {
    const mes  = selMes.value;
    const anio = selAnio.value;
    if (!mes || !anio) {
      const ctx = document.getElementById('chartMes').getContext('2d');
      destroyIf(chartMes);
      chartMes = new Chart(ctx, {
        type: 'bar',
        data: { labels: [], datasets: [{ label: 'Adeudos', data: [] }] },
        options: { 
            responsive: true,
            plugins: { title: { display: true, text: 'Selecciona mes y año' } },
            scales: { y: { beginAtZero: true } }
        }
      });
      btnDownloadMes.style.display = 'none';
      return;
    }

    const res = await fetch(`${urlChartMes}?mes=${encodeURIComponent(mes)}&anio=${encodeURIComponent(anio)}`, {
      credentials: 'same-origin'
    });
    if (!res.ok) {
        btnDownloadMes.style.display = 'none';
        return;
    }
    const json = await res.json();

    const ctx = document.getElementById('chartMes').getContext('2d');
    destroyIf(chartMes);
    chartMes = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: Array.isArray(json.labels) ? json.labels : [],
        datasets: [{
          label: 'Alumnos con adeudo',
          data: Array.isArray(json.data) ? json.data.map(n => Number(n || 0)) : [],
          backgroundColor: "rgb(17, 37, 67)",
          borderWidth: 1
        }]
      },
      options: { 
        responsive: true, 
        plugins: {
            legend: { display: false }, 
            title: {
                display: true,
                text: `Alumnos con adeudo en el mes de ${mes} / ${anio}`
            }
        },
        scales: { 
            y: { 
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Número de alumnos'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Diplomado'
                }
            }
        } 
      }
    });

    btnDownloadMes.style.display = (json.data.length > 0) ? 'block' : 'none';
  }

  /*
   * Carga la gráfica de adeudos para un alumno específico.
      Realiza la llamada AJAX a la API de reporte, destruye la gráfica anterior, y genera una nueva gráfica de dona 
      con el estado de adeudo/sin adeudo del alumno. También gestiona la visibilidad del botón de descarga Excel.
  */
  async function cargarGraficaAlumno() {
    const mes       = selMes.value;
    const anio      = selAnio.value;
    const matricula = inpMatricula.value.trim();

    const ctx = document.getElementById('chartAlumno').getContext('2d');
    if (!mes || !anio || !matricula) {
      destroyIf(chartAlumno);
      chartAlumno = new Chart(ctx, {
        type: 'doughnut',
        data: { labels: ['Sin datos'], datasets: [{ data: [0] }] },
        options: { 
            responsive: true, 
            plugins: { title: { display: true, text: 'Selecciona Filtros' } } 
        }
      });
      btnDownloadAlumno.style.display = 'none';
      return;
    }

    const res = await fetch(`${urlChartAlumno}?mes=${encodeURIComponent(mes)}&anio=${encodeURIComponent(anio)}&matricula=${encodeURIComponent(matricula)}`, {
      credentials: 'same-origin'
    });
    if (!res.ok) {
        btnDownloadAlumno.style.display = 'none';
        return;
    }
    const json = await res.json();

    destroyIf(chartAlumno);
    chartAlumno = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: Array.isArray(json.labels) && json.labels.length ? json.labels : ['Sin adeudo'],
        datasets: [{
          data: Array.isArray(json.data) && json.data.length ? json.data.map(n => Number(n || 0)) : [0],
          backgroundColor: ["rgb(17, 37, 67)"],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' },
          title: { 
              display: true, 
              text: `Adeudos para matrícula ${matricula} en ${mes}/${anio}` 
          }
        }
      }
    });

    btnDownloadAlumno.style.display = (json.data.length > 0) ? 'block' : 'none';
  }

  /* Listener para el botón 'Generar Reporte'. */
  btnGenerar?.addEventListener('click', () => {
    const activeTab = document.querySelector('#tabs .tab.active')?.dataset.tab || 'mes';
    if (activeTab === 'mes') {
      cargarGraficaMes();
    } else {
      cargarGraficaAlumno();
    }
  });


  /* Listener para el botón 'Descargar (Reporte Mes)'. */
  btnDownloadMes?.addEventListener('click', () => {
    const mes  = selMes.value;
    const anio = selAnio.value;
    if (mes && anio) {
      window.location.href = `${urlExportar}?mes=${encodeURIComponent(mes)}&anio=${encodeURIComponent(anio)}&tipo=mes`;
    } else {
        alert('Por favor, selecciona un mes y año para descargar.');
    }
  });

  /* Listener para el botón 'Descargar (Reporte Alumno)'. */
  btnDownloadAlumno?.addEventListener('click', () => {
    const mes       = selMes.value;
    const anio      = selAnio.value;
    const matricula = inpMatricula.value.trim();
    if (mes && anio && matricula) {
      window.location.href = `${urlExportar}?mes=${encodeURIComponent(mes)}&anio=${encodeURIComponent(anio)}&tipo=alumno&matricula=${encodeURIComponent(matricula)}`;
    } else {
      alert('Por favor, selecciona un mes, año y matrícula para descargar.');
    }
  });

  /* Inicialización de pestañas y carga de gráfica inicial. */
  tabsInit();
  const activeTab = document.querySelector('#tabs .tab.active')?.dataset.tab || 'mes';
  if (activeTab === 'mes') {
      cargarGraficaMes();
  } else {
      cargarGraficaAlumno();
  }
});