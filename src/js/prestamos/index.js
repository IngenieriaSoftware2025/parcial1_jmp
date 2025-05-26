import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormPrestamos = document.getElementById('FormPrestamos');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
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

const BuscarPrestamos = async () => {
    const url = `/parcial1_jmp/prestamos/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            datatable.clear().draw();
            datatable.rows.add(data).draw();
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
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Libro', data: 'titulo' },
        { title: 'Autor', data: 'autor' },
        { title: 'Prestado a', data: 'persona_prestado' },
        { 
            title: 'Fecha Préstamo', 
            data: 'fecha_prestamo',
            render: (data) => {
                const fecha = new Date(data);
                return fecha.toLocaleDateString('es-ES');
            }
        },
        {
            title: 'Estado',
            data: 'devuelto',
            render: (data, type, row) => {
                if (data === 'S') {
                    const fechaDevolucion = new Date(row.fecha_devolucion);
                    return `<span class="badge bg-success">Devuelto (${fechaDevolucion.toLocaleDateString('es-ES')})</span>`;
                } else {
                    return `<span class="badge bg-warning">Prestado</span>`;
                }
            }
        },
        {
            title: 'Acciones',
            data: 'id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                let botones = '';
                
                if (row.devuelto === 'N') {
                    botones += `
                        <button class='btn btn-success devolver mx-1' 
                            data-id="${data}">
                            <i class='bi bi-check-circle me-1'></i> Devolver
                        </button>`;
                }
                
                botones += `
                    <button class='btn btn-danger eliminar mx-1' 
                        data-id="${data}">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                    </button>`;
                
                return `<div class='d-flex justify-content-center'>${botones}</div>`;
            }
        }
    ]
});

const limpiarTodo = () => {
    FormPrestamos.reset();
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