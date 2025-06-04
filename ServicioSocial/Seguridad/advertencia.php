<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="../img/icono.png" type="image/png">
    
    <title>Acceso Denegado</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: #DFE9F0;
            color: #333;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            position: relative;
            width: 100%;
            max-width: 600px;
            background: #fff;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            text-align: center;
            transform: translateY(-20px);
            opacity: 0;
            animation: fadeInUp 1s forwards;
        }

        /* Animation for container */
        @keyframes fadeInUp {
            0% { transform: translateY(-20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #dc3545;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            animation: slideInLeft 0.8s ease-out forwards;
        }

        /* Animation for h1 */
        @keyframes slideInLeft {
            0% { transform: translateX(-30px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }

        p {
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 25px;
        }

        .btn-custom {
            background-color: #365CB2;
            color: white;
            border: none;
            padding: 12px 30px;
            text-transform: uppercase;
            border-radius: 50px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            letter-spacing: 1.5px;
        }

        .btn-custom:hover {
            background-color: #2c4a8a;
            transform: scale(1.1);
        }

        .btn-custom:active {
            transform: scale(0.95);
        }

        .footer {
            font-size: 1rem;
            color: #6c757d;
            margin-top: 30px;
            transition: opacity 0.3s ease;
            animation: fadeInUp 1s 0.5s forwards;
        }

        .footer a {
            color: #28a745;
            text-decoration: none;
            font-weight: bold;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 30px;
                width: 80%;
            }

            h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
                width: 90%;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Acceso Denegado</h1>
        <p>Este portal es exclusivo para personal autorizado del Instituto Tecnológico de Nuevo León.</p>
        <p class="text-muted">Si consideras que esto es un error, comunícate con el área de soporte institucional.</p>
        <a href="/" class="btn btn-custom mt-4">Volver al inicio</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>