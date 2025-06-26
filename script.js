// Simulación de inicio de sesión
document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const user = document.getElementById('username').value;
    const pass = document.getElementById('password').value;
    const error = document.getElementById('loginError');

    // Validación simple de usuario y contraseña
    if (user === 'admin' && pass === '1234') {
        // Redirigir a la página de solicitudes
        window.location.href = 'solicitudes.php';
    } else {
        error.classList.remove('d-none');
    }
});

// Cerrar sesión
document.getElementById('logout')?.addEventListener('click', function (e) {
    e.preventDefault();
    window.location.href = 'index.html';
});

// Agregar nueva orden a la tabla
document.getElementById('guardarOrden')?.addEventListener('click', function () {
    const form = document.getElementById('formNuevaOrden');
    const inputs = form.querySelectorAll('input, select, textarea');
    let isValid = true;

    // Validar campos requeridos
    inputs.forEach(input => {
        if (input.hasAttribute('required') && !input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        alert('Por favor complete todos los campos requeridos');
        return;
    }

    // Obtener valores del formulario
    const nuevaOrden = {
        cliente: document.getElementById('cliente').value,
        placa: document.getElementById('placa').value,
        fecha_ingreso: document.getElementById('fecha_ingreso').value,
        tipo_servicio: document.getElementById('tipo_servicio').value,
        observaciones: document.getElementById('observaciones').value,
        estado_pago: document.getElementById('estado_pago').value === 'true',
        fecha_finalizacion: document.getElementById('fecha_finalizacion').value || null
    };

    // Crear nueva fila en la tabla
    const tabla = document.getElementById('tablaOrdenes').getElementsByTagName('tbody')[0];
    const nuevaFila = tabla.insertRow();

    // Determinar clase de la fila según condiciones
    const hoy = new Date();
    const fechaIngreso = new Date(nuevaOrden.fecha_ingreso);
    const diasDemora = Math.floor((hoy - fechaIngreso) / (1000 * 60 * 60 * 24));

    let claseFila = '';
    let estado = 'Normal';

    if (nuevaOrden.fecha_finalizacion === null && diasDemora > 7) {
        claseFila = 'table-warning';
        estado = 'Retraso';
    }

    if (nuevaOrden.fecha_finalizacion !== null && !nuevaOrden.estado_pago) {
        claseFila = 'table-danger';
        estado = 'Pago Pendiente';
    }

    nuevaFila.className = claseFila;

    // Agregar celdas a la fila
    nuevaFila.innerHTML = `
        <td>${nuevaOrden.cliente}</td>
        <td>${nuevaOrden.placa}</td>
        <td>${nuevaOrden.fecha_ingreso}</td>
        <td>${nuevaOrden.tipo_servicio}</td>
        <td>${nuevaOrden.observaciones}</td>
        <td>${nuevaOrden.estado_pago ? 'Pagado' : 'Pendiente'}</td>
        <td>${nuevaOrden.fecha_finalizacion || 'En proceso'}</td>
        <td>${estado}</td>
    `;

    // Cerrar modal y limpiar formulario
    const modal = bootstrap.Modal.getInstance(document.getElementById('nuevaOrdenModal'));
    modal.hide();
    form.reset();

    // Mostrar mensaje de éxito
    alert('Orden agregada correctamente');
});

// Limpiar validación al cerrar el modal
document.getElementById('nuevaOrdenModal')?.addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('formNuevaOrden');
    const inputs = form.querySelectorAll('input, select, textarea');

    inputs.forEach(input => {
        input.classList.remove('is-invalid');
    });
});