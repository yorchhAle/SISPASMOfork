import Chart from 'chart.js/auto';

/*
 * Script de inicialización de gráficas de reporte (Egresados/Estatus de Alumnos).
    Encapsular la lógica de inicialización. Incluye funciones reutilizables para parsear datos del DOM, crear 
    gráficas de barras estandarizadas, e inicializar la navegación de pestañas (tabs) para las secciones del reporte.
*/
(function () {
  /*
   * Extrae y parsea datos de series JSON desde un atributo `data-series` de un elemento Canvas.
  */
  function parseSeriesFromCanvas(id) {
    const el = document.getElementById(id);
    if (!el) return [];
    const raw = el.dataset.series || '[]';
    try { return JSON.parse(raw); } catch { return []; }
  }

  /*
   * Función reutilizable para construir una gráfica de barras Chart.js.
  */
  function makeBarChart(canvasId, { labels, datasets, stacked = false, title = "", axisTitles = { x: "", y: "" } }) {
    const el = document.getElementById(canvasId);
    if (!el || typeof Chart === "undefined") return null;

    const ctx = el.getContext("2d");
    return new Chart(ctx, {
      type: "bar",
      data: { labels, datasets },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: { 
            stacked,
            title: { 
                display: !!axisTitles.x, 
                text: axisTitles.x 
            }
          },
          y: { 
            stacked, 
            beginAtZero: true,
            title: {
                display: !!axisTitles.y, 
                text: axisTitles.y
            }
          }
        },
        plugins: {
          title: { display: !!title, text: title }
        }
      }
    });
  }

  /*
   * Inicializa la gráfica de conteo de alumnos egresados por diplomado/grupo.
      Carga los datos de egresados desde el DOM y los presenta en una gráfica de barras simple.
  */
  function initEgresadosChart() {
    const data = parseSeriesFromCanvas("egresadosAnualChart");
    if (!Array.isArray(data) || data.length === 0) return null;

    const labels = data.map(d => `${d.nombre} (${d.grupo})`);
    const values = data.map(d => Number(d.egresados || 0));

    return makeBarChart("egresadosAnualChart", {
      labels,
      datasets: [{
        label: "Número de egresados",
        data: values,
        backgroundColor: "rgb(17, 37, 67)",
        borderWidth: 1
      }],
      title: "Número de egresados por grupo",
      axisTitles: {
        x: "Diplomado y grupo",
        y: "Total de egresados"
      }
    });
  }

  /*
   * Inicializa la gráfica de comparación entre alumnos activos y egresados.
      Carga los datos de estatus desde el DOM y los presenta en una gráfica de barras apiladas, permitiendo comparar 
      los conteos activo/egresado por cada diplomado/grupo.
  */
  function initComparacionChart() {
    const data = parseSeriesFromCanvas("comparacionEstatusChart");
    if (!Array.isArray(data) || data.length === 0) return null;

    const labels   = data.map(d => `${d.nombre} (${d.grupo})`);
    const activos  = data.map(d => Number(d.activos   || 0));
    const egresados= data.map(d => Number(d.egresados || 0));

    return makeBarChart("comparacionEstatusChart", {
      labels,
      datasets: [
        { label: "Activos",   data: activos,   backgroundColor: "rgb(17, 37, 67)" },
        { label: "Egresados", data: egresados, backgroundColor: "rgb(36, 86, 174)" }
      ],
      stacked: true,
      title: "Alumnos activos vs. egresados por grupo",
      axisTitles: {
        x: "Diplomado y grupo",
        y: "Conteo de alumnos"
      }
    });
  }

  /*
   * Configura la lógica de navegación para las pestañas del reporte. 
      Asigna listeners a los botones de pestaña para alternar la visibilidad  de las secciones (`<section>`) y 
      actualizar la clase 'active'.
  */
  function initTabs() {
    const tabs = document.querySelectorAll(".tab");
    const sections = document.querySelectorAll("section");
    if (!tabs.length || !sections.length) return;

    tabs.forEach(tab => {
      tab.addEventListener("click", () => {
        tabs.forEach(t => t.classList.remove("active"));
        tab.classList.add("active");

        sections.forEach(s => (s.style.display = "none"));
        const target = document.getElementById(`tab-${tab.dataset.tab}`);
        if (target) target.style.display = "block";
      });
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    /* Inicialización de gráficas y pestañas al cargar el DOM */
    initEgresadosChart();
    initComparacionChart();
    initTabs();
  });
})();