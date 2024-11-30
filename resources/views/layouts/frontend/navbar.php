<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="<?php include \BASE_PATH . '/public'; ?>/assets/img/aiep-branding.svg" width="auto" height="50" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-5">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link">Contact</a>
                </li>
            </ul>
            <div>
                <a href="/auth/login" class="btn btn-primary">Login</a>
                <a href="/auth/register" class="btn btn-secondary">Register</a>
            </div>
        </div>
    </div>
</nav>