<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kazoku Sushi | Iniciar Sesión</title>

    <link rel="icon" href="{{ asset('kazoku.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        .login-logo img {
            max-width: 120px; /* Ajusta el tamaño según tu logo */
            height: auto;
            margin-bottom: 10px;
            filter: drop-shadow(0px 4px 6px rgba(0,0,0,0.1)); /* Sombra suave para el logo */
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <div>
            <img src="{{ asset('img/kazoku.png') }}" alt="Logo">
        </div>
        <a href="/"><b>Kazuko </b>Sushi</a>
    </div>
    
    <div class="card shadow-lg" style="border-radius: 15px;">
        <div class="card-body login-card-body" style="border-radius: 15px;">
            <p class="login-box-msg">Ingresa tus credenciales para iniciar sesión</p>

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                
                {{-- Campo Login (Username o Email) --}}
                <div class="input-group mb-3">
                    <input type="text" 
                        name="login" 
                        class="form-control @error('login') is-invalid @enderror" 
                        placeholder="Username o Email" 
                        value="{{ old('login') }}" 
                        required 
                        autofocus>
                    
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>

                    @error('login')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Campo Password --}}
                <div class="input-group mb-3">
                    <input type="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Password" 
                        required>
                    
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Recuérdame</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                            Entrar
                        </button>
                    </div>
                    </div>
            </form>
        </div>
        </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>