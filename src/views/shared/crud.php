<?php

/**
 * Vista base para operaciones CRUD
 * Parámetros esperados:
 * - $entityName: Nombre de la entidad (ej: 'Producto', 'Cliente')
 * - $columns: Array con la configuración de columnas a mostrar
 * - $formFields: Array con la configuración de campos del formulario
 */
?>

<div class="container-fluid">
    <!-- Cabecera -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-list"></i> Gestión de <?php echo $entityName; ?>s</h2>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" onclick="showCreateForm()">
                <i class="fas fa-plus"></i> Nuevo <?php echo $entityName; ?>
            </button>
        </div>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                        <button class="btn btn-outline-secondary" type="button" onclick="search()">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="orderBy" class="form-select" onchange="reloadData()">
                        <?php foreach ($columns as $key => $column): ?>
                            <?php if ($column['sortable'] ?? false): ?>
                                <option value="<?php echo $key; ?>"><?php echo $column['label']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de datos -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <?php foreach ($columns as $column): ?>
                                <th><?php echo $column['label']; ?></th>
                            <?php endforeach; ?>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="dataTableBody">
                        <!-- Los datos se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
            <div id="pagination" class="d-flex justify-content-between align-items-center mt-3">
                <!-- La paginación se cargará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar -->
<div class="modal fade" id="entityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo <?php echo $entityName; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="entityForm">
                    <input type="hidden" id="entityId">
                    <?php foreach ($formFields as $field): ?>
                        <div class="mb-3">
                            <label for="<?php echo $field['name']; ?>" class="form-label">
                                <?php echo $field['label']; ?>
                            </label>
                            <?php if ($field['type'] === 'select'): ?>
                                <select class="form-select" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>"
                                    <?php echo $field['required'] ? 'required' : ''; ?>>
                                    <?php foreach ($field['options'] as $option): ?>
                                        <option value="<?php echo $option['value']; ?>"><?php echo $option['label']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="<?php echo $field['type']; ?>"
                                    class="form-control"
                                    id="<?php echo $field['name']; ?>"
                                    name="<?php echo $field['name']; ?>"
                                    <?php echo $field['required'] ? 'required' : ''; ?>
                                    <?php echo isset($field['min']) ? 'min="' . $field['min'] . '"' : ''; ?>
                                    <?php echo isset($field['max']) ? 'max="' . $field['max'] . '"' : ''; ?>
                                    <?php echo isset($field['pattern']) ? 'pattern="' . $field['pattern'] . '"' : ''; ?>>
                            <?php endif; ?>
                            <?php if (isset($field['help'])): ?>
                                <div class="form-text"><?php echo $field['help']; ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveEntity()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar este <?php echo strtolower($entityName); ?>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales para la configuración
    const entityName = '<?php echo $entityName; ?>';
    const entityEndpoint = '<?php echo $entityEndpoint; ?>';
    let currentPage = 1;
    let itemsPerPage = 10;
    let currentId = null;
    let entityData = [];

    // Función para cargar los datos
    async function loadData(page = 1) {
        try {
            console.log('Intentando cargar datos desde:', `/GitHub/ticketscompra/index.php/api/${entityEndpoint}`);
            const response = await fetch(`/GitHub/ticketscompra/index.php/api/${entityEndpoint}?page=${page}&limit=${itemsPerPage}`);
            console.log('Respuesta recibida:', response);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Datos recibidos:', data);

            if (data.success) {
                entityData = data.data;
                renderTable(data.data);
                renderPagination(data.total, page);
            } else {
                showError(data.error || 'Error desconocido al cargar los datos');
            }
        } catch (error) {
            console.error('Error detallado:', error);
            showError(`Error al cargar los datos: ${error.message}`);
        }
    }

    // Función para renderizar la tabla
    function renderTable(data) {
        const tbody = document.getElementById('dataTableBody');
        tbody.innerHTML = '';

        data.forEach(item => {
            const tr = document.createElement('tr');

            <?php foreach ($columns as $key => $column): ?>
                tr.innerHTML += `<td>${formatValue('<?php echo $key; ?>', item['<?php echo $key; ?>'], '<?php echo $column['type'] ?? 'text'; ?>')}</td>`;
            <?php endforeach; ?>

            tr.innerHTML += `
            <td>
                <button class="btn btn-sm btn-info" onclick="editEntity(${item.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteEntity(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

            tbody.appendChild(tr);
        });
    }

    // Función para formatear valores según su tipo
    function formatValue(key, value, type) {
        if (value === null || value === undefined) return '';

        switch (type) {
            case 'currency':
                return new Intl.NumberFormat('es-ES', {
                    style: 'currency',
                    currency: 'EUR'
                }).format(value);
            case 'date':
                return new Date(value).toLocaleDateString('es-ES');
            case 'datetime':
                return new Date(value).toLocaleString('es-ES');
            case 'boolean':
                return value ? 'Sí' : 'No';
            default:
                return value;
        }
    }

    // Función para mostrar el formulario de creación
    function showCreateForm() {
        currentId = null;
        document.getElementById('modalTitle').textContent = `Nuevo ${entityName}`;
        document.getElementById('entityForm').reset();
        new bootstrap.Modal(document.getElementById('entityModal')).show();
    }

    // Función para editar una entidad
    async function editEntity(id) {
        try {
            const response = await fetch(`/GitHub/ticketscompra/index.php/api/${entityEndpoint}/${id}`);
            const data = await response.json();

            if (data.success) {
                currentId = id;
                document.getElementById('modalTitle').textContent = `Editar ${entityName}`;

                const form = document.getElementById('entityForm');
                Object.keys(data.data).forEach(key => {
                    const input = form.elements[key];
                    if (input) {
                        input.value = data.data[key];
                    }
                });

                new bootstrap.Modal(document.getElementById('entityModal')).show();
            } else {
                showError(data.error || 'Error desconocido al cargar los datos');
            }
        } catch (error) {
            console.error('Error detallado:', error);
            showError(`Error al cargar los datos: ${error.message}`);
        }
    }

    // Función para guardar una entidad
    async function saveEntity() {
        const form = document.getElementById('entityForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const url = `/GitHub/ticketscompra/index.php/api/${entityEndpoint}${currentId ? `/${currentId}` : ''}`;
            const method = currentId ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            let result;
            const text = await response.text();
            try {
                result = JSON.parse(text);
            } catch (e) {
                showError(`Respuesta inválida del servidor: ${text}`);
                return;
            }

            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('entityModal')).hide();
                showSuccess(`${entityName} ${currentId ? 'actualizado' : 'creado'} correctamente`);
                loadData(currentPage);
            } else {
                showError(result.error || 'Error desconocido al guardar los datos');
            }
        } catch (error) {
            console.error('Error detallado:', error);
            showError(`Error al guardar los datos: ${error.message}`);
        }
    }

    // Función para eliminar una entidad
    function deleteEntity(id) {
        currentId = id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Función para confirmar la eliminación
    async function confirmDelete() {
        try {
            const response = await fetch(`/GitHub/ticketscompra/index.php/api/${entityEndpoint}/${currentId}`, {
                method: 'DELETE'
            });

            const result = await response.json();

            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                showSuccess(`${entityName} eliminado correctamente`);
                loadData(currentPage);
            } else {
                showError(result.error || 'Error desconocido al eliminar');
            }
        } catch (error) {
            console.error('Error detallado:', error);
            showError(`Error al eliminar: ${error.message}`);
        }
    }

    // Función para mostrar mensajes de error
    function showError(message) {
        console.error('Error:', message);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger alert-dismissible fade show';
        errorDiv.innerHTML = `
        <strong>Error:</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
        const container = document.querySelector('.container-fluid');
        container.prepend(errorDiv);
    }

    // Función para mostrar mensajes de éxito
    function showSuccess(message) {
        // Implementar según el sistema de notificaciones que prefieras
        alert(message);
    }

    // Función para buscar
    function search() {
        const searchTerm = document.getElementById('searchInput').value;
        // Implementar la lógica de búsqueda
        loadData(1, searchTerm);
    }

    // Función para renderizar la paginación
    function renderPagination(total, currentPage) {
        const totalPages = Math.ceil(total / itemsPerPage);
        const pagination = document.getElementById('pagination');

        let html = `
        <div>
            Mostrando ${(currentPage - 1) * itemsPerPage + 1} - ${Math.min(currentPage * itemsPerPage, total)} de ${total}
        </div>
        <ul class="pagination mb-0">
    `;

        // Botón anterior
        html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="loadData(${currentPage - 1})">Anterior</a>
        </li>
    `;

        // Páginas
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                html += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="loadData(${i})">${i}</a>
                </li>
            `;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                html += '<li class="page-item disabled"><a class="page-link">...</a></li>';
            }
        }

        // Botón siguiente
        html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="loadData(${currentPage + 1})">Siguiente</a>
        </li>
    `;

        html += '</ul>';
        pagination.innerHTML = html;
    }

    // Inicialización
    document.addEventListener('DOMContentLoaded', () => {
        loadData();
    });
</script>