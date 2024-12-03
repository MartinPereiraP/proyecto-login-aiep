<!-- resources/views/auth/login.php -->
<?php include \BASE_PATH . '/resources/views/layouts/auth/header.php'; ?>

<main class="form-signin w-100 m-auto">
    <div class="container mx-6">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-4">
                <form action="/auth/authenticate" method="post">
                    <div class="card">
                        <header class="card-header bg-dark d-flex justify-content-center align-items-center">
                            <img class="mb-4" src="<?php include \BASE_PATH . '/public'; ?>/assets/img/aiep-branding.svg" alt="" width="auto" height="50">
                        </header>

                        <div class="card-body">

                            <h1 class="h3 mb-3 fw-normal d-flex justify-content-center">Login</h1>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                <label for="floatingInput">Email address</label>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="floatingPassword">Password</label>
                            </div>

                            <!--  <div class="form-check text-start my-3">
                                <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Remember me
                                </label>
                            </div> -->

                        </div>

                        <footer class="card-footer d-flex justify-content-between">
                            <a href="/auth/register" class="btn btn-link">Register</a>
                            <button class="btn btn-primary py-2" type="submit">Login</button>
                        </footer>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

<?php include \BASE_PATH . '/resources/views/layouts/auth/footer.php'; ?>