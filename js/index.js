// Función de búsqueda en tiempo real con resaltado
class RealTimeSearch {
    constructor(searchInputId, tableId, options = {}) {
        this.searchInput = document.getElementById(searchInputId);
        this.table = document.getElementById(tableId);
        this.tbody = this.table.querySelector('tbody');
        // Importante: 'originalRows' debe capturar las filas iniciales cargadas por PHP
        this.originalRows = Array.from(this.tbody.querySelectorAll('tr'));
        this.highlightClass = options.highlightClass || 'search-highlight';
        this.noResultsClass = options.noResultsClass || 'no-results';
        this.minChars = options.minChars || 1;

        this.init();
    }

    init() {
        // Agregar evento de búsqueda en tiempo real
        this.searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.trim();
            this.performSearch(searchTerm);
        });

        // Limpiar búsqueda al hacer clic en el input (seleccionar todo el texto)
        this.searchInput.addEventListener('focus', () => {
            this.searchInput.select();
        });

        // Agregar indicador de búsqueda (si no existe ya)
        this.createSearchIndicator();
    }

    performSearch(searchTerm) {
        // Limpiar resaltados anteriores
        this.clearHighlights();

        // Si no hay término de búsqueda, mostrar todas las filas
        if (searchTerm.length < this.minChars) {
            this.showAllRows();
            this.hideNoResults();
            this.updateSearchIndicator('');
            return;
        }

        let visibleRows = 0;
        const regex = new RegExp(`(${this.escapeRegex(searchTerm)})`, 'gi');

        this.originalRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let hasMatch = false;

            cells.forEach(cell => {
                // Si la celda es la de acciones, no buscar en ella
                if (cell.classList.contains('actions')) {
                    return;
                }

                const originalText = cell.getAttribute('data-original-text') || cell.textContent;

                // Guardar texto original si no existe
                if (!cell.getAttribute('data-original-text')) {
                    cell.setAttribute('data-original-text', originalText);
                }

                // Buscar coincidencias
                if (regex.test(originalText)) {
                    hasMatch = true;
                    // Resaltar coincidencias
                    const highlightedText = originalText.replace(regex, `<mark class="${this.highlightClass}">$1</mark>`);
                    cell.innerHTML = highlightedText;
                } else {
                    // Restaurar texto original sin resaltado si no hay coincidencia
                    cell.innerHTML = originalText;
                }
            });

            // Mostrar/ocultar fila según coincidencias
            if (hasMatch) {
                row.style.display = '';
                row.classList.add('search-match');
                // Esto podría ser mejor manejado por CSS para el estado :hover o animaciones
                row.style.animation = 'fadeInUp 0.3s ease-out';
                visibleRows++;
            } else {
                row.style.display = 'none';
                row.classList.remove('search-match');
                row.style.animation = ''; // Limpiar animación para filas ocultas
            }
        });

        // Mostrar mensaje si no hay resultados
        if (visibleRows === 0) {
            this.showNoResults(searchTerm);
        } else {
            this.hideNoResults();
        }

        // Actualizar indicador de búsqueda
        this.updateSearchIndicator(searchTerm, visibleRows);
    }

    clearHighlights() {
        this.originalRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach(cell => {
                const originalText = cell.getAttribute('data-original-text');
                if (originalText && !cell.classList.contains('actions')) {
                    cell.innerHTML = originalText;
                }
            });
            row.classList.remove('search-match');
            row.style.animation = ''; // Asegúrate de limpiar la animación
        });
    }

    showAllRows() {
        this.originalRows.forEach(row => {
            row.style.display = '';
            row.classList.remove('search-match');
            row.style.animation = ''; // Limpiar animación
        });
    }

    showNoResults(searchTerm) {
        let noResultsRow = this.tbody.querySelector('.no-results-row');

        // Asegurarse de que no haya múltiples mensajes de "no resultados"
        this.hideNoResults(); // Ocultar el existente antes de crear/mostrar uno nuevo

        noResultsRow = document.createElement('tr');
        noResultsRow.className = 'no-results-row';
        noResultsRow.innerHTML = `
            <td colspan="${this.table.querySelectorAll('th').length}" class="no-results-message">
                <div class="no-results-content">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <h3>No se encontraron resultados</h3>
                    <p>No hay coincidencias para "<strong>${this.escapeHtml(searchTerm)}</strong>"</p>
                    <small>Intenta con otros términos de búsqueda</small>
                </div>
            </td>
        `;
        this.tbody.appendChild(noResultsRow);

        noResultsRow.style.display = '';
        noResultsRow.style.animation = 'fadeInUp 0.4s ease-out';
    }

    hideNoResults() {
        const noResultsRow = this.tbody.querySelector('.no-results-row');
        if (noResultsRow) {
            noResultsRow.remove(); // Elimina la fila si existe
        }
    }

    createSearchIndicator() {
        // Asegurarse de no crear duplicados
        if (document.querySelector('.search-indicator')) {
            return;
        }

        const indicator = document.createElement('div');
        indicator.className = 'search-indicator';
        indicator.innerHTML = `
            <span class="search-status"></span>
            <div class="search-spinner" style="display: none;">
                <div class="spinner"></div>
            </div>
        `;

        // Insertar después del input de búsqueda
        // Asegúrate de que searchInput.parentNode exista y sea el lugar correcto
        if (this.searchInput && this.searchInput.parentNode) {
            this.searchInput.parentNode.insertBefore(indicator, this.searchInput.nextSibling);
        }
    }

    updateSearchIndicator(searchTerm, resultCount = 0) {
        const indicator = document.querySelector('.search-indicator');
        if (!indicator) return; // Asegurarse de que el indicador exista

        const status = indicator.querySelector('.search-status');

        if (searchTerm) {
            if (resultCount > 0) {
                status.textContent = `${resultCount} resultado${resultCount !== 1 ? 's' : ''} encontrado${resultCount !== 1 ? 's' : ''}`;
                status.className = 'search-status success';
            } else {
                status.textContent = 'Sin resultados';
                status.className = 'search-status no-results';
            }
        } else {
            status.textContent = '';
            status.className = 'search-status';
        }
    }

    escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    escapeHtml(string) {
        const div = document.createElement('div');
        div.textContent = string;
        return div.innerHTML;
    }
}

// Función para inicializar la búsqueda
function initRealTimeSearch() {
    // Verificar que existan los elementos necesarios
    const searchInput = document.getElementById('search-input');
    const patientsTable = document.getElementById('patients-table');

    if (searchInput && patientsTable) {
        new RealTimeSearch('search-input', 'patients-table', {
            highlightClass: 'search-highlight', // Clase para el resaltado
            minChars: 1 // Número mínimo de caracteres para iniciar la búsqueda
        });

        console.log('🔍 Búsqueda en tiempo real inicializada');
    } else {
        console.warn('⚠️ No se pudieron encontrar los elementos necesarios para la búsqueda (ID search-input o patients-table faltante).');
    }
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRealTimeSearch);
} else {
    // Si el DOM ya está cargado (por ejemplo, si el script se carga asincrónicamente o después del DOM)
    initRealTimeSearch();
}

// Exportar para uso externo si es necesario
window.RealTimeSearch = RealTimeSearch;