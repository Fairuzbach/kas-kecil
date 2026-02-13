{{-- SCRIPT JAVASCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        // console.log("🚀 Dashboard Initialized"); // Debug 1

        let trendChart = null;
        let coaChart = null;

        // --- 1. RENDER CHART TREND ---
        const initTrendChart = (data) => {
            const element = document.querySelector("#trendChart");
            if (!element) return;

            var options = {
                series: [{
                    name: 'Realisasi',
                    data: data.totals
                }],
                chart: {
                    type: 'bar',
                    height: 450,
                    fontFamily: 'Nunito, sans-serif',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '55%',
                        distributed: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: data.labels,
                    labels: {
                        style: {
                            fontSize: '11px',
                            fontWeight: 600
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: (val) => new Intl.NumberFormat('id-ID', {
                            notation: "compact"
                        }).format(val)
                    }
                },
                colors: ['#4F46E5', '#6366F1', '#818CF8', '#A5B4FC'],
                tooltip: {
                    y: {
                        formatter: (val) => "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    },
                    title: {
                        formatter: () => 'Total:'
                    }
                }
            };

            if (trendChart) trendChart.destroy();
            trendChart = new ApexCharts(element, options);
            trendChart.render();
        };

        // --- 2. RENDER CHART COA ---
        const initCoaChart = (data) => {
            const element = document.querySelector("#coaChart");
            if (!element) return;

            var options = {
                series: [{
                    name: 'Total',
                    data: data.totals
                }],
                chart: {
                    type: 'bar',
                    height: 500,
                    fontFamily: 'Nunito, sans-serif',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 4,
                        barHeight: '65%',
                        distributed: true
                    }
                },
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    style: {
                        colors: ['#000'],
                        fontSize: '10px'
                    },
                    formatter: (val) => new Intl.NumberFormat('id-ID', {
                        notation: "compact"
                    }).format(val),
                    offsetX: 0,
                },
                xaxis: {
                    categories: data.labels,
                    labels: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        show: true,
                        style: {
                            fontSize: '11px',
                            fontWeight: 600
                        },
                        maxWidth: 250
                    }
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                legend: {
                    show: false
                },
                colors: ['#2563EB', '#3B82F6', '#60A5FA', '#10B981', '#34D399', '#F59E0B', '#FBBF24',
                    '#EF4444', '#F87171', '#9CA3AF'
                ],
                tooltip: {
                    y: {
                        formatter: (val) => "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    },
                    title: {
                        formatter: () => 'Nominal:'
                    }
                }
            };

            if (coaChart) coaChart.destroy();
            coaChart = new ApexCharts(element, options);
            coaChart.render();
        };

        // --- 3. EKSEKUSI DATA AWAL ---

        try {
            initTrendChart(@json($trendData));
            initCoaChart(@json($coaData));
        } catch (e) {
            // console.error("Gagal render awal:", e);
        }

        // --- 4. LISTENER UPDATE (BAGIAN KRUSIAL) ---
        @this.on('update-chart', (event) => {
            // console.log("📥 Event Diterima:", event); // Cek Console Browser (F12)

            let trendData = event.trend ||
                (event[0] && event[0].trend) ||
                (event.detail && event.detail.trend);

            let coaData = event.coa ||
                (event[0] && event[0].coa) ||
                (event.detail && event.detail.coa);

            // console.log("📊 Trend Data Parsed:", trendData);
            // console.log("🍩 COA Data Parsed:", coaData);

            // Update Chart Trend
            if (trendData && trendChart) {
                trendChart.updateOptions({
                    xaxis: {
                        categories: trendData.labels
                    }
                });
                trendChart.updateSeries([{
                    data: trendData.totals
                }]);
            }

            // Update Chart COA
            if (coaData && coaChart) {
                coaChart.updateOptions({
                    xaxis: {
                        categories: coaData.labels
                    }
                });
                coaChart.updateSeries([{
                    data: coaData.totals
                }]);
            }
        });
    });
</script>
