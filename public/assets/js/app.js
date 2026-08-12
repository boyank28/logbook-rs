document.addEventListener('DOMContentLoaded', function () {
  // Line Chart: Logbook 7 Hari Terakhir
  const lineCtx = document.getElementById('lineChart7Days');
  if (lineCtx) {
    new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: ['06-08', '07-08', '08-08', '09-08', '10-08', '11-08', '12-08'],
        datasets: [
          {
            label: 'Open',
            data: [15, 12, 28, 14, 18, 12, 14],
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.3,
            fill: false
          },
          {
            label: 'Proses',
            data: [22, 25, 38, 22, 35, 24, 36],
            borderColor: '#eab308',
            backgroundColor: 'rgba(234, 179, 8, 0.1)',
            tension: 0.3,
            fill: false
          },
          {
            label: 'Selesai',
            data: [42, 45, 48, 30, 42, 30, 45],
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.3,
            fill: false
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 6 } }
        },
        scales: {
          y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
          x: { grid: { display: false } }
        }
      }
    });
  }

  // Doughnut Chart: Berdasarkan Kategori
  const doughnutCtx = document.getElementById('doughnutCategory');
  if (doughnutCtx) {
    new Chart(doughnutCtx, {
      type: 'doughnut',
      data: {
        labels: ['SIMRS', 'Jaringan', 'Server', 'Maintenance', 'Lainnya'],
        datasets: [{
          data: [40, 25, 15, 10, 10],
          backgroundColor: ['#2563eb', '#38bdf8', '#22c55e', '#f97316', '#e2e8f0'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } }
        },
        cutout: '70%'
      }
    });
  }

  // Pie Chart: Rekap Laporan
  const rekapCtx = document.getElementById('rekapPieChart');
  if (rekapCtx) {
    new Chart(rekapCtx, {
      type: 'pie',
      data: {
        labels: ['Gangguan SIMRS (49.7%)', 'Jaringan (25.0%)', 'Server (12.5%)', 'Maintenance (7.8%)', 'Insiden (5.0%)'],
        datasets: [{
          data: [620, 312, 156, 98, 62],
          backgroundColor: ['#2563eb', '#22c55e', '#f97316', '#a855f7', '#06b6d4']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'right', labels: { usePointStyle: true } }
        }
      }
    });
  }
});
