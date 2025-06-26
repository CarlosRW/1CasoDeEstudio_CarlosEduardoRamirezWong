<?php
// Array predefinido de órdenes de servicio
$ordenes = array(
    array(
        'cliente' => 'Juan Pérez',
        'placa' => 'ABC123',
        'fecha_ingreso' => '2023-05-10',
        'tipo_servicio' => 'Cambio de aceite',
        'observaciones' => 'Aceite sintético 5W30',
        'estado_pago' => true,
        'fecha_finalizacion' => '2023-05-11'
    ),
    array(
        'cliente' => 'María Gómez',
        'placa' => 'XYZ789',
        'fecha_ingreso' => '2023-05-15',
        'tipo_servicio' => 'Revisión general',
        'observaciones' => 'Frenos desgastados',
        'estado_pago' => false,
        'fecha_finalizacion' => '2023-05-18'
    ),
    array(
        'cliente' => 'Carlos Ruiz',
        'placa' => 'DEF456',
        'fecha_ingreso' => '2023-05-01',
        'tipo_servicio' => 'Reparación motor',
        'observaciones' => 'Sobrecalentamiento',
        'estado_pago' => true,
        'fecha_finalizacion' => '2023-05-10'
    ),
    array(
        'cliente' => 'Ana López',
        'placa' => 'GHI789',
        'fecha_ingreso' => '2023-05-20',
        'tipo_servicio' => 'Alineación y balanceo',
        'observaciones' => 'Vibración a alta velocidad',
        'estado_pago' => false,
        'fecha_finalizacion' => null
    ),
    array(
        'cliente' => 'Pedro Sánchez',
        'placa' => 'JKL012',
        'fecha_ingreso' => '2023-05-05',
        'tipo_servicio' => 'Cambio de llantas',
        'observaciones' => 'Llanta pinchada',
        'estado_pago' => true,
        'fecha_finalizacion' => '2023-05-05'
    )
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller ABC - Órdenes de Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Taller ABC S.A.</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Órdenes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Reportes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="logout">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Órdenes de Servicio</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaOrdenModal">
                <i class="bi bi-plus-circle"></i> Agregar Orden
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover" id="tablaOrdenes">
                <thead class="table-dark">
                    <tr>
                        <th>Cliente</th>
                        <th>Placa</th>
                        <th>Fecha Ingreso</th>
                        <th>Tipo Servicio</th>
                        <th>Observaciones</th>
                        <th>Estado Pago</th>
                        <th>Fecha Finalización</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordenes as $orden): 
                        $hoy = new DateTime();
                        $fecha_ingreso = new DateTime($orden['fecha_ingreso']);
                        $dias_demora = $hoy->diff($fecha_ingreso)->days;
                        
                        $clase_fila = '';
                        $estado = 'Normal';
                        
                        if ($orden['fecha_finalizacion'] === null && $dias_demora > 7) {
                            $clase_fila = 'table-warning';
                            $estado = 'Retraso';
                        }
                        
                        if ($orden['fecha_finalizacion'] !== null && !$orden['estado_pago']) {
                            $clase_fila = 'table-danger';
                            $estado = 'Pago Pendiente';
                        }
                    ?>
                    <tr class="<?php echo $clase_fila; ?>">
                        <td><?php echo htmlspecialchars($orden['cliente']); ?></td>
                        <td><?php echo htmlspecialchars($orden['placa']); ?></td>
                        <td><?php echo htmlspecialchars($orden['fecha_ingreso']); ?></td>
                        <td><?php echo htmlspecialchars($orden['tipo_servicio']); ?></td>
                        <td><?php echo htmlspecialchars($orden['observaciones']); ?></td>
                        <td><?php echo $orden['estado_pago'] ? 'Pagado' : 'Pendiente'; ?></td>
                        <td><?php echo $orden['fecha_finalizacion'] ?? 'En proceso'; ?></td>
                        <td><?php echo $estado; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para nueva orden -->
    <div class="modal fade" id="nuevaOrdenModal" tabindex="-1" aria-labelledby="nuevaOrdenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="nuevaOrdenModalLabel">Nueva Orden de Servicio</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formNuevaOrden">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cliente" class="form-label">Nombre del Cliente</label>
                                <input type="text" class="form-control" id="cliente" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="placa" class="form-label">Número de Placa</label>
                                <input type="text" class="form-control" id="placa" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="date" class="form-control" id="fecha_ingreso" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipo_servicio" class="form-label">Tipo de Servicio</label>
                                <select class="form-select" id="tipo_servicio" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <option value="Cambio de aceite">Cambio de aceite</option>
                                    <option value="Revisión general">Revisión general</option>
                                    <option value="Reparación motor">Reparación motor</option>
                                    <option value="Alineación y balanceo">Alineación y balanceo</option>
                                    <option value="Cambio de llantas">Cambio de llantas</option>
                                    <option value="Reparación eléctrica">Reparación eléctrica</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estado_pago" class="form-label">Estado de Pago</label>
                                <select class="form-select" id="estado_pago" required>
                                    <option value="true">Pagado</option>
                                    <option value="false" selected>Pendiente</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_finalizacion" class="form-label">Fecha de Finalización</label>
                                <input type="date" class="form-control" id="fecha_finalizacion">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardarOrden">Guardar Orden</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts.js"></script>
</body>
</html>