// Variables globales
let salesChart;

// Initialisation du tableau de bord
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le graphique de ventes
    initializeSalesChart();
    
    // Ajouter un écouteur d'événement pour le bouton de rafraîchissement
    const refreshBtn = document.getElementById('refresh-dashboard');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            refreshDashboardData();
        });
    }
    
    // Ajouter des écouteurs d'événements pour les boutons de période du graphique
    document.querySelectorAll('.chart-period-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Supprimer la classe active de tous les boutons
            document.querySelectorAll('.chart-period-btn').forEach(btn => {
                btn.classList.remove('bg-primary', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            // Ajouter la classe active au bouton cliqué
            this.classList.remove('bg-gray-100', 'text-gray-700');
            this.classList.add('bg-primary', 'text-white');
            
            // Mettre à jour le graphique en fonction de la période sélectionnée
            updateChartPeriod(this.dataset.period);
        });
    });
});

/**
 * Initialise le graphique de ventes
 */
function initializeSalesChart() {
    const salesChartElement = document.getElementById('salesChart');
    if (!salesChartElement) {
        console.error('Sales chart element not found');
        return;
    }
    
    const salesCtx = salesChartElement.getContext('2d');
    if (!salesCtx) {
        console.error('Could not get 2D context for sales chart');
        return;
    }
    
    try {
        // Récupérer les données du graphique depuis l'attribut data
        const salesLabels = JSON.parse(salesChartElement.dataset.labels || '[]');
        const salesValues = JSON.parse(salesChartElement.dataset.values || '[]');
        
        // Créer le graphique
        salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Sales',
                    data: salesValues,
                    borderColor: '#111111',
                    backgroundColor: 'rgba(17, 17, 17, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#111111',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(17, 17, 17, 0.9)',
                        padding: 10,
                        cornerRadius: 4,
                        titleFont: {
                            size: 12,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                return '€' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6B7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6B7280',
                            callback: function(value) {
                                return '€' + value;
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error initializing sales chart:', error);
    }
}

/**
 * Rafraîchit les données du tableau de bord
 */
function refreshDashboardData() {
    // Afficher l'état de chargement
    const refreshBtn = document.getElementById('refresh-dashboard');
    if (!refreshBtn) {
        console.error('Refresh button not found');
        return;
    }
    
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Refreshing...';
    refreshBtn.disabled = true;
    
    // Vérifier si le jeton CSRF est disponible
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found. Add <meta name="csrf-token" content="{{ csrf_token() }}"> to your layout.');
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
        alert('CSRF token not found. Please refresh the page.');
        return;
    }
    
    // Récupérer les données mises à jour du tableau de bord
    fetch('/admin/dashboard/summary', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            return response.json()
                .then(errorData => {
                    throw new Error(errorData.error || `Server responded with ${response.status}`);
                })
                .catch(() => {
                    throw new Error(`Network response was not ok: ${response.status}`);
                });
        }
        return response.json();
    })
    .then(data => {
        console.log('Dashboard data refreshed successfully:', data);
        
        // Mettre à jour les statistiques
        updateElementText('total-products', data.totalProducts);
        updateElementText('low-stock-products', data.lowStockProducts);
        updateElementText('total-orders', data.totalOrders);
        updateElementText('pending-orders', data.pendingOrders);
        updateElementText('total-customers', data.totalCustomers);
        updateElementText('open-tickets', data.openTickets);
        
        // Mettre à jour les indicateurs de croissance
        updateGrowthIndicator('total-products', data.productGrowth);
        updateGrowthIndicator('total-orders', data.orderGrowth);
        updateGrowthIndicator('total-customers', data.customerGrowth);
        updateGrowthIndicator('open-tickets', data.ticketGrowth, true);
        
        // Rafraîchir les autres composants
        fetchTopProducts();
        updateChartPeriod('month'); // Rafraîchir le graphique avec la période actuelle
        
        // Ajouter un effet de surbrillance subtil pour montrer les données mises à jour
        document.querySelectorAll('#stats-container > div').forEach(card => {
            card.classList.add('bg-green-50');
            setTimeout(() => {
                card.classList.remove('bg-green-50');
            }, 1000);
        });
        
        // Réinitialiser le bouton
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
    })
    .catch(error => {
        console.error('Error refreshing dashboard data:', error);
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
        
        // Afficher une notification d'erreur
        alert('Failed to refresh dashboard data: ' + error.message);
    });
}

/**
 * Met à jour le texte d'un élément s'il existe
 */
function updateElementText(elementId, text) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = text;
    } else {
        console.warn(`Element with ID "${elementId}" not found`);
    }
}

/**
 * Met à jour les indicateurs de croissance
 */
function updateGrowthIndicator(elementId, growthValue, inverse = false) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.warn(`Element with ID "${elementId}" not found`);
        return;
    }
    
    const growthElement = element.nextElementSibling;
    if (!growthElement) {
        console.warn(`Growth indicator for "${elementId}" not found`);
        return;
    }
    
    // Pour les tickets, la croissance est inversée (l'augmentation est mauvaise, la diminution est bonne)
    const isPositive = inverse ? growthValue < 0 : growthValue >= 0;
    
    // Mettre à jour l'icône et la couleur
    growthElement.className = `ml-2 text-xs ${isPositive ? 'text-green-600' : 'text-red-600'} flex items-center`;
    
    // Mettre à jour l'icône
    const iconElement = growthElement.querySelector('i');
    if (iconElement) {
        iconElement.className = isPositive ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line';
    } else {
        console.warn(`Icon element for "${elementId}" growth indicator not found`);
    }
    
    // Mettre à jour le texte
    const textNode = growthElement.childNodes[1];
    if (textNode) {
        textNode.nodeValue = ` ${Math.abs(growthValue)}% `;
    } else {
        console.warn(`Text node for "${elementId}" growth indicator not found`);
    }
}

/**
 * Met à jour le graphique en fonction de la période sélectionnée
 */
function updateChartPeriod(period) {
    // Afficher l'état de chargement sur le graphique
    const chartContainer = document.getElementById('salesChart');
    if (!chartContainer) {
        console.error('Sales chart container not found');
        return;
    }
    
    const chartParent = chartContainer.parentNode;
    if (chartParent) {
        chartParent.classList.add('opacity-50');
    }
    
    // Vérifier si le jeton CSRF est disponible
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found. Add <meta name="csrf-token" content="{{ csrf_token() }}"> to your layout.');
        if (chartParent) {
            chartParent.classList.remove('opacity-50');
        }
        alert('CSRF token not found. Please refresh the page.');
        return;
    }
    
    // Préparer l'URL de la requête en fonction de la période
    const url = `/admin/dashboard/sales-data?period=${period}`;
    
    // Récupérer les données pour la période sélectionnée
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            return response.json()
                .then(errorData => {
                    throw new Error(errorData.error || `Server responded with ${response.status}`);
                })
                .catch(() => {
                    throw new Error(`Network response was not ok: ${response.status}`);
                });
        }
        return response.json();
    })
    .then(data => {
        console.log('Chart data updated successfully:', data);
        
        // Mettre à jour le graphique avec les nouvelles données
        if (salesChart) {
            salesChart.data.labels = data.labels;
            salesChart.data.datasets[0].data = data.data;
            salesChart.update();
        } else {
            console.error('Sales chart not initialized');
        }
        
        // Supprimer l'état de chargement
        if (chartParent) {
            chartParent.classList.remove('opacity-50');
        }
    })
    .catch(error => {
        console.error('Error updating chart data:', error);
        if (chartParent) {
            chartParent.classList.remove('opacity-50');
        }
        
        // Afficher une notification d'erreur
        alert('Failed to update chart data: ' + error.message);
    });
}

/**
 * Récupère les produits les plus vendus avec AJAX
 */
function fetchTopProducts() {
    const productsList = document.getElementById('top-products-list');
    if (!productsList) {
        console.error('Top products list container not found');
        return;
    }
    
    // Vérifier si le jeton CSRF est disponible
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found. Add <meta name="csrf-token" content="{{ csrf_token() }}"> to your layout.');
        productsList.innerHTML = '<div class="text-center py-4"><p class="text-sm text-gray-500">Failed to load products: CSRF token missing</p></div>';
        return;
    }
    
    productsList.innerHTML = '<div class="flex justify-center items-center py-6"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
    
    fetch('/admin/dashboard/top-products', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            return response.json()
                .then(errorData => {
                    throw new Error(errorData.error || `Server responded with ${response.status}`);
                })
                .catch(() => {
                    throw new Error(`Network response was not ok: ${response.status}`);
                });
        }
        return response.json();
    })
    .then(data => {
        console.log('Top products fetched successfully:', data);
        
        if (data.length === 0) {
            productsList.innerHTML = '<div class="text-center py-4"><p class="text-sm text-gray-500">No products found</p></div>';
            return;
        }
        
        let html = '';
        data.forEach(product => {
            html += `
            <div class="flex items-center space-x-3 p-3 border border-gray-100 rounded-md hover:bg-gray-50">
                <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-md overflow-hidden">
                    ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">` : '<div class="flex items-center justify-center h-full w-full text-gray-400"><i class="ri-image-line"></i></div>'}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${product.name}</p>
                    <p class="text-xs text-gray-500">Sales: ${product.total_quantity}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">€${parseFloat(product.total_sales).toFixed(2)}</p>
                </div>
            </div>
            `;
        });
        
        productsList.innerHTML = html;
    })
    .catch(error => {
        console.error('Error fetching top products:', error);
        productsList.innerHTML = '<div class="text-center py-4"><p class="text-sm text-gray-500">Failed to load products: ' + error.message + '</p></div>';
    });
}