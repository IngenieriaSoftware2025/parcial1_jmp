<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Control de prestamos de libros de claudia!</h5>
                        <h3 class="fw-bold text-primary mb-0">ADMINISTRAR LIBROS</h3>
                    </div>
                    <form id="FormLibros" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="id" name="id">
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="titulo" class="form-label">Título del Libro</label>
                                <input type="text" class="form-control form-control-lg" id="titulo" name="titulo" placeholder="Ingrese el título del libro" required>
                            </div>
                            <div class="col-md-6">
                                <label for="autor" class="form-label">Autor</label>
                                <input type="text" class="form-control form-control-lg" id="autor" name="autor" placeholder="Ingrese el nombre del autor" required>
                            </div>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="persona_prestado" class="form-label">Prestado a</label>
                                <input type="text" class="form-control form-control-lg" id="persona_prestado" name="persona_prestado" placeholder="Nombre de la persona (dejar vacío si está disponible)">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Guardar
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Modificar
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5">
        <div class="col-lg-11">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">
                        <i class="bi bi-books me-2"></i>Libros en la Biblioteca
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden" id="TableLibros">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/libros/index.js') ?>"></script>