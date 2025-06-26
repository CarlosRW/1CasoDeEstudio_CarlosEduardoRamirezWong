// Simulación de inicio de sesión
document.getElementById('loginForm')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const user = document.getElementById('username').value;
    const pass = document.getElementById('password').value;
    const error = document.getElementById('loginError');
    // Validación simple de usuario y contraseña
    if (user === 'admin' && pass === '1234') {
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

// Manejo del modal y formulario
document.addEventListener('DOMContentLoaded', function () {
    const formNuevaOrden = document.getElementById('formNuevaOrden');
    const guardarOrdenBtn = document.getElementById('guardarOrden');

    if (!formNuevaOrden || !guardarOrdenBtn) return;

    const modal = new bootstrap.Modal(document.getElementById('nuevaOrdenModal'));

    // Cambiamos a manejar el evento submit del formulario
    formNuevaOrden.addEventListener('submit', function (e) {
        e.preventDefault(); // Esto evita la recarga de página

        const inputs = this.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        // Validación de campos requeridos
        inputs.forEach(input => {
            const isEmpty = input.value.trim() === '';
            if (isEmpty && input.hasAttribute('required')) {
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

        // Crear objeto con los datos del formulario
        const nuevaOrden = {
            cliente: this.querySelector('#cliente').value,
            placa: this.querySelector('#placa').value,
            fecha_ingreso: this.querySelector('#fecha_ingreso').value,
            tipo_servicio: this.querySelector('#tipo_servicio').value,
            observaciones: this.querySelector('#observaciones').value,
            estado_pago: this.querySelector('#estado_pago').value === 'true',
            fecha_finalizacion: this.querySelector('#fecha_finalizacion').value || null
        };

        // Agregar a la tabla
        agregarOrdenATabla(nuevaOrden);

        // Cerrar modal y limpiar
        modal.hide();
        this.reset();
    });

    // Limpiar validaciones al cerrar el modal
    document.getElementById('nuevaOrdenModal').addEventListener('hidden.bs.modal', function () {
        const invalidInputs = formNuevaOrden.querySelectorAll('.is-invalid');
        invalidInputs.forEach(input => input.classList.remove('is-invalid'));
    });
});

// Función para agregar orden a la tabla
function agregarOrdenATabla(orden) {
    const tabla = document.getElementById('tablaOrdenes').getElementsByTagName('tbody')[0];
    const nuevaFila = tabla.insertRow();

    // Cálculo de días de demora
    const hoy = new Date();
    const fechaIngreso = new Date(orden.fecha_ingreso);
    const diasDemora = Math.floor((hoy - fechaIngreso) / (1000 * 60 * 60 * 24)); // Diferencia en días

    // Determinar clase de fila y estado
    let claseFila = '';
    let estado = 'Normal';

    if (orden.fecha_finalizacion === null && diasDemora > 7) {
        claseFila = 'table-warning';
        estado = 'Retraso';
    } else if (orden.fecha_finalizacion !== null && !orden.estado_pago) {
        claseFila = 'table-danger';
        estado = 'Pago Pendiente';
    }

    // Aplicar clases y crear fila
    nuevaFila.className = claseFila;
    nuevaFila.innerHTML = `
        <td>${escapeHTML(orden.cliente)}</td>
        <td>${escapeHTML(orden.placa)}</td>
        <td>${escapeHTML(orden.fecha_ingreso)}</td>
        <td>${escapeHTML(orden.tipo_servicio)}</td>
        <td>${escapeHTML(orden.observaciones)}</td>
        <td>${orden.estado_pago ? 'Pagado' : 'Pendiente'}</td>
        <td>${orden.fecha_finalizacion || 'En proceso'}</td>
        <td>${estado}</td>
    `;
}

// Función para HTML escape
function escapeHTML(str) {
    if (typeof str !== 'string') return str;
    return str.replace(/[&<>'"]/g, tag => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;'
    }[tag]));
}
