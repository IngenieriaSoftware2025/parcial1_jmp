import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormPrestamos = document.getElementById('FormPrestamos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnFiltrar = document.getElementById('BtnFiltrar');
const BtnLimpiarFiltros = document.getElementById('BtnLimpiarFiltros');
const selectLibro = document.getElementById('libro_id');

const GuardarPrestamo = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormPrestamos, ['id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos obligatorios",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormPrestamos);
    const url = '/parcial1_jmp/prestamos/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        console.log(datos)
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarPrestamos();
            CargarLibrosDisponibles();

        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error)
    }
    BtnGuardar.disabled = false;
}

const BuscarPrestamos = async (filtros = {}) => {
    let url = `/parcial1_jmp/prestamos/buscarAPI`;
    
    // Construir parámetros de filtro (SOLO FECHAS)
    const params = new URLSearchParams();
    if (filtros.fechaInicio) params.append('fechaInicio', filtros.fechaInicio);
    if (filtros.fechaFin) params.append('fechaFin', filtros.fechaFin);
    if (filtros.estado) params.append('estado', filtros.estado);
    
    if (params.toString()) {
        url += `?${params.toString()}`;
    }
    
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        
        console.log('Datos recibidos:', datos);
        
        const codigo = datos.codigo;
        const mensaje = datos.mensaje;
        const data = datos.data;

        if (codigo == 1) {
            datatable.clear().draw();
            if (data && data.length > 0) {
                datatable.rows.add(data).draw();
            }
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log('Error en BuscarPrestamos:', error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudieron cargar los préstamos",
            showConfirmButton: true,
        });
    }
}

const CargarLibrosDisponibles = async () => {
    const url = `/parcial1_jmp/prestamos/librosDisponiblesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos

        if (codigo == 1) {
            selectLibro.innerHTML = '<option value="">Seleccione un libro</option>';
            
            data.forEach(libro => {
                const option = document.createElement('option');
                option.value = libro.id;
                option.textContent = `${libro.titulo} - ${libro.autor}`;
                selectLibro.appendChild(option);
            });
        }

    } catch (error) {
        console.log(error)
    }
}

const datatable = new DataTable('#TablePrestamos', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    order: [[4, 'desc']],
    columns: [
        {
            title: 'No.',
            data: 'id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Libro', 
            data: 'titulo',
            width: '25%'
        },
        { 
            title: 'Autor', 
            data: 'autor',
            width: '20%'
        },
        { 
            title: 'Prestado a', 
            data: 'persona_prestado',
            width: '15%'
        },
        { 
            title: 'Fecha y Hora de Préstamo', 
            data: 'fecha_prestamo',
            width: '15%',
            render: (data) => {
                const fecha = new Date(data);
                const fechaFormateada = fecha.toLocaleDateString('es-ES');
                const horaFormateada = fecha.toLocaleTimeString('es-ES', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                return `<div class="text-center">
                    <div class="fw-bold">${fechaFormateada}</div>
                    <small class="text-muted">${horaFormateada}</small>
                </div>`;
            }
        },
        {
            title: 'Estado',
            data: 'devuelto',
            width: '15%',
            render: (data, type, row) => {
                if (data === 'S') {
                    const fechaDevolucion = new Date(row.fecha_devolucion);
                    const fechaFormateada = fechaDevolucion.toLocaleDateString('es-ES');
                    const horaFormateada = fechaDevolucion.toLocaleTimeString('es-ES', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                    return `<span class="badge bg-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Devuelto<br>
                        <small>${fechaFormateada} ${horaFormateada}</small>
                    </span>`;
                } else {
                    const fechaPrestamo = new Date(row.fecha_prestamo);
                    const hoy = new Date();
                    const diasPrestado = Math.floor((hoy - fechaPrestamo) / (1000 * 60 * 60 * 24));
                    
                    let badgeClass = 'bg-warning';
                    if (diasPrestado > 30) badgeClass = 'bg-danger';
                    else if (diasPrestado > 15) badgeClass = 'bg-warning';
                    else badgeClass = 'bg-info';
                    
                    return `<span class="badge ${badgeClass}">
                        <i class="bi bi-clock me-1"></i>
                        Prestado<br>
                        <small>(${diasPrestado} días)</small>
                    </span>`;
                }
            }
        },
        {
            title: 'Acciones',
            data: 'id',
            width: '15%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                let botones = '';
                
                if (row.devuelto === 'N') {
                    botones += `
                        <button class='btn btn-success btn-sm devolver mx-1' 
                            data-id="${data}">
                            <i class='bi bi-check-circle me-1'></i> Devolver
                        </button>`;
                }
                
                botones += `
                    <button class='btn btn-danger btn-sm eliminar mx-1' 
                        data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                    </button>`;
                
                return `<div class='d-flex justify-content-center flex-wrap'>${botones}</div>`;
            }
        }
    ]
});

const limpiarTodo = () => {
    FormPrestamos.reset();
}

// Función para aplicar filtros (SOLO FECHAS)
const aplicarFiltros = () => {
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;
    const estado = document.getElementById('estadoFiltro').value;
    
    // Validación: si pones fecha inicio, debe ser menor que fecha fin
    if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Fechas incorrectas",
            text: "La fecha de inicio debe ser menor que la fecha de fin",
            showConfirmButton: true,
        });
        return;
    }
    
    const filtros = {};
    if (fechaInicio) filtros.fechaInicio = fechaInicio;
    if (fechaFin) filtros.fechaFin = fechaFin;
    if (estado) filtros.estado = estado;
    
    BuscarPrestamos(filtros);
}

// Función para limpiar filtros (SOLO FECHAS)
const limpiarFiltros = () => {
    document.getElementById('fechaInicio').value = '';
    document.getElementById('fechaFin').value = '';
    document.getElementById('estadoFiltro').value = '';
    BuscarPrestamos();
}

const DevolverLibro = async (e) => {
    const idPrestamo = e.currentTarget.dataset.id

    const AlertaConfirmarDevolucion = await Swal.fire({
        position: "center",
        icon: "question",
        title: "¿Confirmar devolución?",
        text: 'Se marcará el libro como devuelto',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Devolver',
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarDevolucion.isConfirmed) {
        const body = new FormData();
        body.append('id', idPrestamo);
        
        const url = '/parcial1_jmp/prestamos/devolverAPI';
        const config = {
            method: 'POST',
            body
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarPrestamos();
                CargarLibrosDisponibles();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error)
        }
    }
}

const EliminarPrestamo = async (e) => {
    const idPrestamo = e.currentTarget.dataset.id

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "warning",
        title: "¿Desea eliminar este préstamo?",
        text: 'Esta acción no se puede deshacer',
        showConfirmButton: true,
        confirmButtonText: 'Sí, Eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url =`/parcial1_jmp/prestamos/eliminar?id=${idPrestamo}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Éxito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarPrestamos();
                CargarLibrosDisponibles();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error)
        }
    }
}

// Inicializar
BuscarPrestamos();
CargarLibrosDisponibles();

// Event Listeners
datatable.on('click', '.eliminar', EliminarPrestamo);
datatable.on('click', '.devolver', DevolverLibro);
FormPrestamos.addEventListener('submit', GuardarPrestamo);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnFiltrar.addEventListener('click', aplicarFiltros);
BtnLimpiarFiltros.addEventListener('click', limpiarFiltros);