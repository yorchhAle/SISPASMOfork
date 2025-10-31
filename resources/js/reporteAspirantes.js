import Chart from 'chart.js/auto';
/*
 * Script de lógica para la generación de reportes de Aspirantes interesados.
    Coordinar las dos vistas de reporte (Total por tipo vs. Comparación entre tipos), realizar llamadas AJAX a 
    las APIs correspondientes y renderizar las gráficas Chart.js. También controla la visibilidad de los filtros según 
    la pestaña activa.
 */
document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('reporteRoot');
    if (!root) return;

    const urlTotal       = root.dataset.urlTotal;
    const urlComparacion = root.dataset.urlComparacion;

    const selTipoDiplomado = document.getElementById('f_tipo_diplomado');
    const btnGenerarTotal  = document.getElementById('btnGenerarTotal');
    const filtroTotalDiv   = document.getElementById('filtroTotal');

    let chartTotal = null;
    let chartComparacion = null;

    /*
     * Configura la navegación y la visibilidad de las secciones del reporte (Total vs Comparación).
        Alternar entre las pestañas, ocultar/mostrar los filtros específicos y asegurar que la gráfica de comparación 
        se cargue al cambiar a esa pestaña.
    */
    function tabsInit() {
        const tabs = document.querySelectorAll('#tabs .tab');
        const sections = {
            total: document.getElementById('tab-total'),
            comparacion: document.getElementById('tab-comparacion')
        };
        const activeTab = document.querySelector('#tabs .tab.active')?.dataset.tab || 'total';

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const target = tab.dataset.tab;
                sections.total.style.display = (target === 'total') ? 'block' : 'none';
                sections.comparacion.style.display = (target === 'comparacion') ? 'block' : 'none';

                filtroTotalDiv.style.display = (target === 'total') ? 'flex' : 'none'; 
                
                if (target === 'comparacion') {
                    /* Cargar automáticamente la gráfica de comparación al cambiar de pestaña */
                    cargarGraficaComparacion();
                }
            });
        });
        
        if (activeTab === 'comparacion') {
            cargarGraficaComparacion();
        } else {
            filtroTotalDiv.style.display = 'flex'; 
        }
    }

    /*
     * Destruye una instancia de Chart.js si existe, liberando recursos.
    */
    function destroyIf(chart) {
        if (chart) chart.destroy();
    }

    /*
     * Carga la gráfica de total de aspirantes interesados en un tipo de diplomado específico.
        Realiza una llamada AJAX usando el filtro del select (`selTipoDiplomado`), y renderiza una gráfica de barras 
        mostrando el total.
    */
    async function cargarGraficaTotal() {
        const tipoDiplomado = selTipoDiplomado.value;
        if (!tipoDiplomado) {
            alert('Por favor, selecciona un tipo de diplomado.');
            return;
        }

        const params = new URLSearchParams({ tipo: tipoDiplomado });
        const res = await fetch(`${urlTotal}?${params.toString()}`); 
        if (!res.ok) {
            console.error('Error al cargar datos del total:', res.status, res.statusText);
            return;
        }
        const json = await res.json();
        
        const dataToShow = json.data.length > 0 ? json.data : [0];

        const ctx = document.getElementById('chartTotal').getContext('2d');
        destroyIf(chartTotal);
        
        const labelTexto = selTipoDiplomado.options[selTipoDiplomado.selectedIndex].text;

        chartTotal = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [labelTexto], 
                datasets: [{
                    label: 'Total de aspirantes interesados',
                    data: dataToShow,
                    backgroundColor: 'rgb(17, 37, 67)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: `Total de aspirantes: ${labelTexto}` 
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Número de aspirantes'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tipo de diplomado seleccionado'
                        }
                    }
                }
            }
        });
    }

    /*
     * Carga la gráfica de comparación entre los diferentes tipos de diplomado.
        Realiza una llamada AJAX a la API de comparación (sin filtros), y renderiza una gráfica de barras que muestra 
        el conteo de aspirantes para cada tipo.
    */
    async function cargarGraficaComparacion() {
        const res = await fetch(`${urlComparacion}`);
        if (!res.ok) {
            console.error('Error al cargar datos de comparación:', res.status, res.statusText);
            return;
        }
        const json = await res.json();

        const dataToShow = json.data.length > 0 ? json.data : [0, 0];

        const ctx = document.getElementById('chartComparacion').getContext('2d');
        destroyIf(chartComparacion);
        chartComparacion = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: json.labels,
                datasets: [{
                    label: 'Total de aspirantes',
                    data: dataToShow,
                    backgroundColor: [
                        'rgb(17, 37, 67)',
                        'rgba(54, 162, 235, 0.5)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }, 
                    title: {
                        display: true,
                        text: 'Comparación de aspirantes por tipo de diplomado'
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Número de aspirantes'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tipo de diplomado'
                        }
                    }
                }
            }
        });
    }

    /* Listener del botón para generar el reporte de Total (pestaña activa por defecto). */
    btnGenerarTotal?.addEventListener('click', cargarGraficaTotal);

    tabsInit();
});