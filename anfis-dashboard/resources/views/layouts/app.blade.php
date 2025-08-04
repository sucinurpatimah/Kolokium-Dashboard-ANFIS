<!DOCTYPE html>
<html>

<head>
    <title>ANFIS Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        /* Membesarkan navbar */
        .navbar {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        /* Membesarkan teks brand */
        .navbar-brand {
            font-size: 1.5rem;
            /* Ukuran font lebih besar */
        }

        /* Membesarkan logo */
        .navbar-brand img {
            height: 45px;
            /* ukuran logo lebih besar */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="me-2">
                Sustainable GSCOR
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
