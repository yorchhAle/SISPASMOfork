import Chart from 'chart.js/auto';


/*
 * Script de lógica para la generación de reportes de Alumnos Inscritos.
    Gestionar las dos visualizaciones del reporte (Total por diplomado y Estatus Activo/Baja), realizar llamadas AJAX 
    para obtener los datos de conteo, renderizar las gráficas de barras apiladas (Chart.js), y manejar la captura de la 
    imagen de la gráfica para la exportación a PDF.
*/
document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('reporteRoot');
    if (!root) return;

    const urlTotales = root.dataset.urlTotales;
    const urlEstatus = root.dataset.urlEstatus;

    const selDiplomado = document.getElementById('f_diplomado');
    const btnGenerar   = document.getElementById('btnGenerar');

    const btnPDFTotales = document.getElementById('btnPDFTotales');
    const btnPDFEstatus = document.getElementById('btnPDFEstatus');

    let chartTotales = null;
    let chartEstatus = null;

    /*
     * Configura la navegación y la visibilidad de las secciones del reporte (Totales vs Estatus).
        Asigna listeners a los botones de pestaña para cambiar la clase 'active' y alternar el estilo 'display' 
        de las secciones del contenido.
    */
    function tabsInit() {
        const tabs = document.querySelectorAll('#tabs .tab');
        const sections = {
            totales: document.getElementById('tab-totales'),
            estatus: document.getElementById('tab-estatus')
        };
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                const target = tab.dataset.tab;
                sections.totales.style.display = (target === 'totales') ? 'block' : 'none';
                sections.estatus.style.display = (target === 'estatus') ? 'block' : 'none';
            });
        });
    }

    /*
     * Destruye una instancia de Chart.js si existe, liberando recursos.
    */
    function destroyIf(chart) {
        if (chart) chart.destroy();
    }


    /*
     * Carga la gráfica de conteo total de alumnos inscritos por diplomado.
        Realiza una llamada AJAX a la API de totales y renderiza una gráfica de barras simple.
    */
    async function cargarGraficaTotales() {
        const diplomadoId = selDiplomado.value;
        const params = new URLSearchParams();
        if (diplomadoId) {
            params.append('diplomados[]', diplomadoId);
        }

        const res = await fetch(`${urlTotales}?${params.toString()}`);
        if (!res.ok) return;
        const json = await res.json();
        const ctx = document.getElementById('chartTotales').getContext('2d');
        destroyIf(chartTotales);
        chartTotales = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: json.labels,
                datasets: [{
                    label: 'Total de alumnos',
                    data: json.data,
                    backgroundColor: 'rgb(17, 37, 67)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Total de alumnos inscritos'
                    },
                    legend: {
                        display: false
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
                            text: 'Diplomados'
                        }
                    }
                }
            }
        });
    }

    /*
     * Carga la gráfica de estatus (Activos vs. Baja) de alumnos por diplomado.
        Realiza una llamada AJAX a la API de estatus y renderiza una gráfica de barras apiladas.
    */
    async function cargarGraficaEstatus() {
        const diplomadoId = selDiplomado.value;
        const params = new URLSearchParams();
        if (diplomadoId) {
            /* Permite filtrar por un diplomado específico */
            params.append('diplomados[]', diplomadoId);
        }

        const res = await fetch(`${urlEstatus}?${params.toString()}`);
        if (!res.ok) return;
        const json = await res.json();

        const ctx = document.getElementById('chartEstatus').getContext('2d');
        destroyIf(chartEstatus);
        chartEstatus = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: json.labels,
                datasets: [
                    {
                        label: 'Alumnos activos',
                        data: json.dataActivos,
                        backgroundColor: 'rgb(17, 37, 67)',
                        borderWidth: 1
                    },
                    {
                        label: 'Alumnos dados de baja',
                        data: json.dataBajas,
                        backgroundColor: 'rgb(36, 86, 174)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Alumnos por estatus (Activos-Baja)'
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: { 
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Diplomados'
                        }
                    },
                    y: { 
                        stacked: true, 
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Número de alumnos'
                        }
                    }
                }
            }
        });
    }

    btnGenerar?.addEventListener('click', () => {
        /* Determina qué gráfica cargar basándose en la pestaña activa */
        const activeTab = document.querySelector('#tabs .tab.active')?.dataset.tab || 'totales';
        if (activeTab === 'totales') {
            cargarGraficaTotales();
        } else {
            cargarGraficaEstatus();
        }
    });

    /*
     * Captura la imagen de la gráfica 'Totales', asigna los datos al formulario oculto para PDF y lo envía.
    */
    btnPDFTotales?.addEventListener('click', () => {
        if (!chartTotales) return;
        const imageData = chartTotales.toBase64Image();
        document.getElementById('chart_data_url_totales').value = imageData;
        
        const diplomadoNombre = selDiplomado.options[selDiplomado.selectedIndex].text;
        document.getElementById('subtituloTotales').value = diplomadoNombre;
        
        document.getElementById('pdfFormTotales').submit();
    });
    
    /*
     * Captura la imagen de la gráfica 'Estatus', asigna los datos al formulario oculto para PDF y lo envía. */
    btnPDFEstatus?.addEventListener('click', () => {
        if (!chartEstatus) return;
        const imageData = chartEstatus.toBase64Image();
        document.getElementById('chart_data_url_estatus').value = imageData;
    
        const diplomadoNombre = selDiplomado.options[selDiplomado.selectedIndex].text;
        document.getElementById('subtituloEstatus').value = diplomadoNombre;
    
        document.getElementById('pdfFormEstatus').submit();
    });

    /* Inicialización de pestañas y carga de la gráfica inicial. */
    tabsInit();
    cargarGraficaTotales();
});