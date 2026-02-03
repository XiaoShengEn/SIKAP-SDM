<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SIKAP SDM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('login.css') }}" />
</head>

<body>

    <div class="card login-card">
        <div class="text-center">
            <h3 class="mt-2">Hello Admin!</h3>
            <p class="sub-text">Selamat Datang, Silahkan Login untuk melanjutkan.</p>
        </div>

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">NIP :</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input
                        type="text"
                        name="nip"
                        class="form-control"
                        placeholder="Masukkan NIP"
                        maxlength="18"
                        pattern="[0-9]{18}"
                        inputmode="numeric"
                        required>

                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">PASSWORD</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Masukkan Password"
                        required>

                    <button
                        type="button"
                        class="btn btn-outline-secondary toggle-password"
                        onclick="togglePassword()">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>

                </div>
            </div>

            <button type="submit" class="btn btn-login w-100">
                Log In <i class="fas fa-arrow-right ms-2"></i>
            </button>

            @if($errors->any())
            <div class="error-box animate__animated animate__fadeIn">
                <i class="fas fa-info-circle me-2"></i> {{ $errors->first() }}
            </div>
            @endif
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>