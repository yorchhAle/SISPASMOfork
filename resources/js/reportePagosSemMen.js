/*
 * Script para inicializar la gráfica de pagos validados agrupados por período.
    Extraer los datos de pagos (agrupados por semana o mes) directamente del DOM, parsear los montos y las etiquetas 
    de fecha, y renderizar una gráfica de barras (Chart.js) que visualice la suma total de pagos por cada período.
*/
document.addEventListener('DOMContentLoaded', function() {
  const dataElement = document.getElementById('pagos-data');
  const canvas = document.getElementById('pagosChart');
  if (!dataElement || !canvas || typeof Chart === 'undefined') return;

  /* Extrae y parsea los datos JSON del elemento oculto en el DOM. */
  let pagosData = JSON.parse(dataElement.textContent || '[]');

  /* Convierte el objeto en un array si los datos vinieron como un objeto (groupBy). */
  if (!Array.isArray(pagosData) && pagosData && typeof pagosData === 'object') {
    pagosData = Object.values(pagosData);
  }
  if (!Array.isArray(pagosData) || pagosData.length === 0) return;

  /* Mapea los datos para obtener las etiquetas del eje X y los montos del eje Y */
  const labels = pagosData.map(p => p.label ?? p.fecha_pago);
  const data   = pagosData.map(p => Number(p.monto || 0));

  const ctx = canvas.getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Monto de Pagos',
        data,
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
              text: 'Total de pagos calidados por período'
          }
      },
      scales: {
          y: { 
              beginAtZero: true,
              title: {
                  display: true,
                  text: 'Monto total recibido (MXN)'
              }
          },
          x: {
              title: {
                  display: true,
                  text: 'Período (semana o mes)'
              }
          }
      } 
    }
  });
});