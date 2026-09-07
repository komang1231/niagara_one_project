<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login - Niagara One</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <main class="login-container">
        <div class="main-content">

            <div class="left-column">
                <div class="form-wrapper">
                    <header>
                        <h2 class="brand-title h2">Welcome to <span>Niagara One</span></h2>
                        <p class="subtitle">Please sign in your account below here!</p>
                    </header>

     
                    <form method="POST" action="#">
                        {{-- @csrf --}}

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label class="form-label" for="emailInput">Email / Username</label>
                            <input class="form-control" id="emailInput" name="email" type="text"
                                autocomplete="username" placeholder="Masukkan email atau username" required />
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label class="form-label" for="passwordInput">Password</label>
                            <div class="position-relative">
                                <input class="form-control pe-5" id="passwordInput" name="password" type="password"
                                    autocomplete="current-password" placeholder="Masukkan password" required />
                                <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3 password-toggle-icon fs-5"
                                    id="togglePassword"></i>
                            </div>
                        </div>

 
                        <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" id="rememberCheck" name="remember" type="checkbox"
                                    checked />
                                <label class="form-check-label" for="rememberCheck">
                                    Remember me
                                </label>
                            </div>
                            <a class="forgot-link" href="#">Lupa Password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button class="btn btn-primary w-100 py-2 mt-3" type="submit">Sign In</button>
                    </form>
                </div>

                <footer class="footer-container">
                    <div>© 2026 Niagara One System</div>
                    <div>Design By Harmonya Indonesia®</div>
                </footer>

            </div>

            <div class="right-column d-none d-lg-block"></div>

        </div>


    </main>



</body>

</html>
