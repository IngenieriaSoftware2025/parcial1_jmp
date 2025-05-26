<div class="container py-5">
    <!-- Formulario de Préstamos -->
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Control de prestamos de libros de claudia!</h5>
                        <h3 class="fw-bold text-primary mb-0">ADMINISTRAR PRÉSTAMOS</h3>
                    </div>
                    <form id="FormPrestamos" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="id" name="id">
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="libro_id" class="form-label">Libro a Prestar</label>
                                <select class="form-control form-control-lg" id="libro_id" name="libro_id" required>
                                    <option value="">Seleccione un libro</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="persona_prestado" class="form-label">Persona que recibe el préstamo</label>
                                <input type="text" class="form-control form-control-lg" id="persona_prestado" name="persona_prestado" placeholder="Nombre completo de la persona" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Prestar Libro
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

    <!-- Filtros de Fecha -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-11">
            <div class="card shadow border-info rounded-3">
                <div class="card-body">
                    <h5 class="card-title text-info mb-3">
                        <i class="bi bi-funnel me-2"></i>Filtros de Búsqueda por Fecha
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="fechaInicio" class="form-label">Fecha Inicio <small class="text-muted">(opcional)</small></label>
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                        </div>
                        <div class="col-md-3">
                            <label for="fechaFin" class="form-label">Fecha Fin <small class="text-muted">(opcional)</small></label>
                            <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                        </div>
                        <div class="col-md-3">
                            <label for="estadoFiltro" class="form-label">Estado <small class="text-muted">(opcional)</small></label>
                            <select class="form-control" id="estadoFiltro" name="estadoFiltro">
                                <option value="">Todos</option>
                                <option value="N">Prestados</option>
                                <option value="S">Devueltos</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-grid gap-2 w-100">
                                <button class="btn btn-info" id="BtnFiltrar">
                                    <i class="bi bi-search me-1"></i>Filtrar
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" id="BtnLimpiarFiltros">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Préstamos -->
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow-lg border-primary rounded-4">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">
                        <i class="bi bi-person-check me-2"></i>Préstamos Registrados
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden" id="TablePrestamos">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/prestamos/index.js') ?>"></script>