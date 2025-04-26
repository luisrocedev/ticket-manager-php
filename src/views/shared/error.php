<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-body">
                    <h1 class="text-danger"><i class="fas fa-exclamation-triangle"></i></h1>
                    <h2 class="card-title">Error</h2>
                    <p class="card-text"><?php echo $mensaje ?? 'Ha ocurrido un error inesperado.'; ?></p>
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver atrás
                    </a>
                    <a href="/GitHub/ticketscompra" class="btn btn-primary">
                        <i class="fas fa-home"></i> Ir al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>