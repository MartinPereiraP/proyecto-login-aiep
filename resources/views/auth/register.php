<?php include \BASE_PATH . '/resources/views/layouts/auth/header.php'; ?>

<main class="form-signin w-100 m-auto">
    <div class="container mx-6">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-4">
                <form>
                    <div class="card">
                        <header class="card-header bg-dark d-flex justify-content-center align-items-center">
                            <img class="mb-4" src="<?php include \BASE_PATH . '/public'; ?>/assets/img/aiep-branding.svg" alt="" width="auto" height="50">
                        </header>
                        <div class="card-body">

                            <h1 class="h3 mb-3 fw-normal d-flex justify-content-center">Register</h1>

                            <div class="form-floating mb-3">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" placeholder="Ingrese su nombre">
                            </div>

                            <div class="form-floating mb-3">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" placeholder="Ingrese su correo electrónico">
                            </div>

                            <div class="form-floating mb-3">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" placeholder="Ingrese su contraseña">
                            </div>

                            <div class="form-floating mb-3">
                                <label for="password_confirmation">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation" placeholder="Confirme su contraseña">
                            </div>

                        </div>
                        <footer class="card-footer d-flex justify-content-between">
                            <a href="/auth/login" class="btn btn-link">Login</a>
                            <button class="btn btn-primary py-2" type="submit">Register</button>
                        </footer>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

<?php include \BASE_PATH . '/resources/views/layouts/auth/footer.php'; ?>