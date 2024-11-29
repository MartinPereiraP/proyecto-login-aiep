<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <link rel="stylesheet" href="<?php include \BASE_PATH . '/public'; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php include \BASE_PATH . '/public'; ?>/assets/css/styles.css">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

    <main class="form-signin w-100 m-auto">
        <div class="container mx-10">
            <div class="card">
                <div class="card-body">
                    <form>
                        <img class="mb-4" src="<?php include \BASE_PATH . '/public'; ?>/assets/img/aiep-branding.png" alt="" width="72" height="57">
                        <h1 class="h3 mb-3 fw-normal">Login</h1>

                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>

                        <div class="form-check text-start my-3">
                            <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Remember me
                            </label>
                        </div>
                        <button class="btn btn-primary w-100 py-2" type="submit">Login</button>
                        <p class="mt-5 mb-3 text-body-secondary">© 2017–2024</p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="<?php include \BASE_PATH . '/public'; ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>