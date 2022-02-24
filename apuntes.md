# Laravel y OAuth 2: Login con Facebook, Twitter, Google+, etc
+ [URL del curso en Udemy](https://www.udemy.com/course/laravel-y-oauth-2-facebook-twitter-google)
+ [URL del repositorio en GitHub](https://github.com/petrix12/deploy_2022.git)


## Antes de iniciar:
1. Crear proyecto en la página de [GitHub](https://github.com) con el nombre: **oauth2022**.
    + **Description**: Proyecto para seguir los curso de Udemy de 'Laravel y OAuth 2: Login con Facebook, Twitter, Google+, etc' (creado por Juan Ramos).
    + **Public**.
2. En la ubicación raíz del proyecto en la terminal de la máquina local:
    + $ git init
    + $ git add .
    + $ git commit -m "Antes de iniciar"
    + $ git branch -M main
    + $ git remote add origin https://github.com/petrix12/oauth2022.git
    + $ git push -u origin main


## Sección 1: OAuth 2
### 1. Introducción
2 min

### 2. ¿Qué es OAuth 2 y qué roles intervienen?
8 min

### 3. Flujo general descrito por OAuth 2
6 min

### 4. OAuth 2 más de cerca
9 min

### 5. Preguntas frecuentes acerca de OAuth 2
+ [Explicación del protocolo OAuth 2](https://programacionymas.com/blog/protocolo-oauth-2)

### Commit en GitHub
+ $ git add .
+ $ git commit -m "OAuth 2"
+ $ git push -u origin main


## Sección 2: Laravel Socialite y login vía Facebook
### 6. ¿Por qué decimos OAuth 2 y no 1?
3 min

### 7. Configuración del entorno
1. Crear proyecto Laravel con Laragon y nombrarlo **monitor**.

### 8. Sistema de autenticación
1. Crear sistema de autenticación:
    + $ composer require laravel/ui
    + $ php artisan ui vue --auth
2. Verificar valores de las variable de entorno en **.env**.
3. Anexar CDN de Bootstrap en **resources\views\layouts\app.blade.php**.
4. Ejecutar ajustes finales:
    + $ npm install
    + $ npm run dev
    + $ php artisan migrate

### 9. Instalación y registro en Facebook for Devs
1. Instalar dependencia de Laravel Socialite:
    + $ composer require laravel/socialite
2. Ingresar en la página para Desarrolladores de [Facebook](https://developers.facebook.com/?locale=es_ES):
    1. Ir a Mis Aplicaciones y crear una nueva.
    2. Selecciona un tipo de app: Ninguno.
    3. Nombre para mostrar: Monitor de precios
    4. Correo electrónico de contacto de la app: bazo.pedro@gmail.com
    **Nota 1**: al culminar estos pasos obtenemos las credenciales de la aplicación en **Configuración > Básica**:
       + Identificador de la app: ****** (FACEBOOK_CLIENT_ID)
       + Clave secreta de la app: ****** (FACEBOOK_CLIENT_SECRET)
    + **Nota 2**: para terminar de configurar el sitio, seguir los pasos indicados en: **Sección 3: Actualización Laravel 8**.
3. Modificar archivo de configuración **config\services.php** para añadir las credenciales de facebook:
    ```php
    <?php

    return [

        ≡

        'facebook' => [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'redirect' => env('FACEBOOK_REDIRECT_URL'),
        ],

    ];
    ```
4. Modificar archivo de variables de entorno **.env** para añadir las credenciales de facebook:
    ```env
    ≡
    FACEBOOK_CLIENT_ID=*********
    FACEBOOK_CLIENT_SECRET=**********
    FACEBOOK_REDIRECT_URL="https://oauth2022.test/facebook/auth/callback"
    ``` 

### 10. Rutas para gestionar el login vía OAuth
1. Modificar archivo de rutas **routes\web.php**:
    ```php
    ≡
    // Rutas para login con facebook
    Route::get('login/facebook', [App\Http\Controllers\Auth\FacebookLonginController::class, 'login'])
        ->name('login.facebook');
    Route::get('facebook/auth/callback', [App\Http\Controllers\Auth\FacebookLonginController::class, 'loginWithFacebook']);
    ```
2.  Crear controlador **FacebookLonginController**:
    + $ php artisan make:controller Auth\FacebookLonginController
3.  Programar controlador **app\Http\Controllers\Auth\FacebookLonginController.php**:
    ```php
    <?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Laravel\Socialite\Facades\Socialite;

    class FacebookLonginController extends Controller
    {
        public function login(){
            return Socialite::driver('facebook')->redirect();
        }

        public function loginWithFacebook(){
            dd(Socialite::driver('facebook')->user());
        }
    }
    ```

### 11. Importancia de HTTPS
+ **Nota**: porque debemos utilizar https.

### 12. Cómo activar HTTPS de forma local
+ https://forum.laragon.org/topic/106/laragon-and-let-s-encrypt/3
1. Ir a Laragon y activar SSL en **Menú > Apache > SSL > Habilitar**.
    + **Nota**: esta acción generará:
        + Un certificado: **C:\laragon\etc\ssl\laragon.crt**
        + Una key: **C:\laragon\etc\ssl\laragon.key**
        + Una ruta con protocolo https para el proyecto en: **C:\laragon\etc\apache2\sites-enabled\auto.oauth2022.test.conf**.
2. Reiniciar apache.
3. En el navegador Chrome ir a **Configuración > Seguridad y privacidad > Configuración avanzada > Gestionar certificados**.
4. Importar el certificado **C:\laragon\etc\ssl\laragon.crt**.
    1. Clic en **Siguiente**.
    2. En **Almacén de certifacado** seleccionar: **Entidades de certificación raíz de confianza**.
    3. Clic en **Siguiente**.
    4. Reiniciar Chrome.
5. Reconfigurar varible de entorno **FACEBOOK_REDIRECT_URL** en **.env**:

### 13. Autorización y callback vía Facebook
3 min

### 14. Primer registro e inicio de sesión exitoso
1. Modificar plantilla **resources\views\layouts\app.blade.php**:
    ```php
    ≡
    <!-- Authentication Links -->
    @guest
        @if (Route::has('login'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                    Inicio
                </a>
            </li>
        @endif
    @else
    ≡
    ```
2. Modificar vista **resources\views\auth\login.blade.php**:
    ```php
    ≡
    <div class="card">
        <div class="card-header">Inicio de sesión</div>

        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-end">
                        Correo electrónico
                    </label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-end">
                        Contraseña
                    </label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                Recordar sesión
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Ingresar
                        </button>

                        <a href="{{ route('login.facebook') }}" class="btn btn-info">
                            Ingresar por facebook
                        </a>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    ≡
    ```
3. Modificar el método **loginWithFacebook** del controlador **app\Http\Controllers\Auth\FacebookLonginController.php**:
    ```php
    public function loginWithFacebook(){
        $userFacebook = Socialite::driver('facebook')->user();

        $user = User::where('email', $userFacebook->getEmail())->first();

        if(!$user){
            $user = User::create([
                'name' => $userFacebook->getName(),
                'email' => $userFacebook->getEmail(),
                'password' => '',
            ]);
        }

        auth()->login($user);
        return redirect()->route('home');
    }
    ```

### 15. Mostrar avatar (imagen de perfil)
1. Añadir campos a la migración **database\migrations\2014_10_12_000000_create_users_table.php**:
    ```php
    ≡
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');

        $table->string('facebook_id')->nullable();
        $table->string('twitter_id')->nullable();
        $table->string('google_id')->nullable();
        $table->string('avatar')->nullable();
        $table->string('nick')->nullable();

        $table->rememberToken();
        $table->timestamps();
    });
    ≡
    ```
2. Reestablecer base de datos:
    + $ php artisan migrate:fresh
3. Modificar el método **loginWithFacebook** del controlador **app\Http\Controllers\Auth\FacebookLonginController.php**:
    ```php
    public function loginWithFacebook(){
        $userFacebook = Socialite::driver('facebook')->user();

        $user = User::where('email', $userFacebook->getEmail())->first();

        if(!$user){
            $user = User::create([
                'name' => $userFacebook->getName(),
                'email' => $userFacebook->getEmail(),
                'password' => '',
                'facebook_id' => $userFacebook->getId(),
                'avatar'  => $userFacebook->getAvatar(),
                'nick'  => $userFacebook->getNickname()
            ]);
        }

        auth()->login($user);
        return redirect()->route('home');
    }
    ```
4. Modificar la asignación masiva en el modelo **app\Models\User.php**:
    ```php
    ≡
    protected $fillable = [
        'name',
        'email',
        'password',
        'facebook_id',
        'twitter_id',
        'google_id',
        'avatar',
        'nick'
    ];
    ≡
    ```
5. Modificar vista **resources\views\layouts\app.blade.php**:
    ```php
    ≡
    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        @if (Auth::user()->avatar)
            <img src="{{ Auth::user()->avatar }}" alt="Avatar" helght="28">
        @endif
        {{ Auth::user()->name }}
    </a>
    ≡
    ```

### 16. Íconos y botones de login adicionales
1. Modificar vista **resources\views\welcome.blade.php**:
    ```php
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            ≡
            <title>{{ config('app.name') }}</title>
            ≡
        </head>
        <body class="antialiased">
            <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
                @if (Route::has('login'))
                    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                        @auth
                            <a href="{{ url('/home') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">
                                Inicio
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">
                                Ingresar
                            </a>
                        @endauth
                    </div>
                @endif

                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                        <h1>{{ config('app.name') }}</h1>
                    </div>

                    <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                        <div class="text-center text-sm text-gray-500 sm:text-left">
                            <div class="flex items-center">
                                <a href="#" class="ml-1 underline m-10">
                                    Preguntas frecuentes
                                </a>

                                <a href="#" class="ml-1 underline">
                                    Términos y condiciones
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>
    ```
2. Modificar vista **resources\views\auth\login.blade.php**:
    ```php
    @extends('layouts.app')

    @section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Inicio de sesión</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" class="mb-2">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">
                                    Correo electrónico
                                </label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">
                                    Contraseña
                                </label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            Recordar sesión
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-secondary">
                                        Ingresar
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            ¿Olvidaste tu contraseña?
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <a href="{{ route('login.facebook') }}" class="btn btn-primary btn-block">
                            <i class="fa-brands fa-facebook-square"></i>
                            Ingresar por facebook
                        </a>

                        <a href="{{ route('login.twitter') }}" class="btn btn-info btn-block">
                            <i class="fa-brands fa-twitter"></i>
                            Ingresar por Twitter
                        </a>

                        <a href="#" class="btn btn-danger btn-block">
                            <i class="fa-brands fa-google"></i>
                            Ingresar por Google
                        </a>

                        <a href="#" class="btn btn-dark btn-block">
                            <i class="fa-solid fa-envelope"></i>
                            Registro mediante correo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    ```
3. Modificar plantilla **resources\views\layouts\app.blade.php**:
    ```php
    <head>
        ≡
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    </head>
    ≡
    ```

### Commit en GitHub
+ $ git add .
+ $ git commit -m "Laravel Socialite y login vía Facebook"
+ $ git push -u origin main


## Sección 3: Actualización Laravel 8
### 17. Facebook Login y Laravel UI
+ https://socialiteproviders.com/about
1. Crear proyecto Laravel con Laragon y nombrarlo **monitor2**.
2. Instalar dependencia de Laravel Socialite:
    + $ composer require laravel/socialite
3. Modificar archivo de configuración **config\services.php** para añadir las credenciales de facebook:
    ```php
    <?php

    return [

        ≡

        'facebook' => [
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'redirect' => env('FACEBOOK_REDIRECT_URL'),
        ],

    ];
    ```
4. Crear base de datos **monitor2** en MySQL.
5. Crear sistema de autenticación:
    + $ composer require laravel/ui
    + $ php artisan ui bootstrap --auth
6. Verificar valores de las variable de entorno en **.env**.
7. Ejecutar ajustes finales:
    + $ npm install
    + $ npm run dev
    + $ php artisan migrate
8.  Crear controlador **FacebookLonginController**:
    + $ php artisan make:controller Auth\FacebookLonginController
9.  Programar controlador **app\Http\Controllers\Auth\FacebookLonginController.php**:
    ```php
    <?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Laravel\Socialite\Facades\Socialite;

    class FacebookLonginController extends Controller
    {
        public function login(){
            return Socialite::driver('facebook')->redirect();
        }

        public function loginWithFacebook(){
            dd(Socialite::driver('facebook')->user());
        }
    }
    ```
10. Modificar la vista **resources\views\auth\login.blade.php**:
    ```php
    ≡
    <div class="row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Login') }}
            </button>

            <a href="{{ route('login.facebook') }}" class="btn btn-primary">
                Ingresar por facebook
            </a>

            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>
    </div>
    ≡
    ```
11. Modificar archivo de rutas **routes\web.php** para agregar las rutas para login con facebook:
    ```php
    <?php

    use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Rutas para login con facebook
    Route::get('login/facebook', [App\Http\Controllers\Auth\FacebookLonginController::class, 'login'])
        ->name('login.facebook');
    Route::get('facebook/auth/callback', [App\Http\Controllers\Auth\FacebookLonginController::class, 'loginWithFacebook']);
    ```
12. Ingresar en la página para Desarrolladores de [Facebook](https://developers.facebook.com/?locale=es_ES):
    1. Ir a Mis Aplicaciones y crear una nueva.
    2. Selecciona un tipo de app: Ninguno.
    3. Nombre para mostrar: Monitor de precios
    4. Correo electrónico de contacto de la app: bazo.pedro@gmail.com
    **Nota**: al culminar estos pasos obtenemos las credenciales de la aplicación en **Configuración > Básica**:
       + Identificador de la app: ****** (FACEBOOK_CLIENT_ID)
       + Clave secreta de la app: ****** (FACEBOOK_CLIENT_SECRET)
       + Dominios de la app: https://oauth2022.test/
       + Ir al final de la configuración básica y dar clic en **+ Agregar plataforma** y seleccionar **Website**.
       + En el nuevo apartado de **Sitio web** icluir en **URL del sitio**: https://oauth2022.test/
       + Presionar el botón **Guardar cambios**.
    1. Presionar en **Configuración** en la tarjeta **Inicio de sesión con Facebook**.
    2. Seleccionar **www**.
    3. Ir a Productos > Inicio de sesión con facebook > Configuración
       + URI de redireccionamiento de OAuth válidos: https://oauth2022.test/facebook/auth/callback
    4. Ir a **Configuración > Avanzada > Administrador de dominios** y dar clic en **Agragar dominio**:
       + Ingresa la URL: **https://oauth2022.test/**
       + Seleccionar: **Coincidencia exacta**.
       + Seleccionar: **HTML** y **JavaScript y CSS**.
       + Clic en **Aplicar**.
13. Configurar variables de entorno en **.env**:
    ```env
    APP_NAME=Monitor
    ≡
    APP_URL=https://oauth2022.test/
    SESSION_DOMAIN=oauth2022.test

    ≡

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=monitor2
    DB_USERNAME=root
    DB_PASSWORD=

    ≡

    FACEBOOK_CLIENT_ID=*************
    FACEBOOK_CLIENT_SECRET=************
    FACEBOOK_REDIRECT_URL="https://oauth2022.test/facebook/auth/callback"
    ```

+ **Nota**: en caso de ser necesario limpiar la cache:
    + $ php artisan config:cache

### Commit en GitHub
+ $ git add .
+ $ git commit -m "Actualización Laravel 8"
+ $ git push -u origin main


## Sección 4: Más proveedores de OAuth 2
### 18. Registro de app en Twitter
1. Ir a la [plataforma para desarrolladores de Twitter](https://developer.twitter.com) y hacer login.
2. Clic en **+ Create Project**:
    + Project name: Monitor de precios
    + App name: Monitor11639
    + Obtener **API Key**, **API Key Secret** y **Bearer Token**:
        + API Key: **********************
        + API Key Secret: **********************
        + Bearer Token: **********************
    + User authentication settings: OAuth 2
    + Type of App: Web App
    + Callback URI: https://oauth2022.test/twitter/auth/callback
    + Website URL: https://oauth2022.test
    + Obtener OAuth 2.0 Client ID and Client Secret:
        + Client ID: **********************
        + Client Secret: **********************
3. En local modificar el archivo de configuración **config\services.php**:
    ```php
    <?php

    return [

        ≡

        'twitter' => [
            'client_id' => env('TWITTER_CLIENT_ID'),
            'client_secret' => env('TWITTER_CLIENT_SECRET'),
            'redirect' => env('TWITTER_REDIRECT_URL'),
        ],

    ];
    ```
4. Agregar variables de entorno para Twitter en **.env**:
    ```env
    TWITTER_CLIENT_ID=*****************
    TWITTER_CLIENT_SECRET=***********************
    TWITTER_REDIRECT_URL="https://oauth2022.test/twiiter/auth/callback"
    ```

### 19. Registro de app en Google+
+ **Nota**: el proveedor Google+ ya no presta servicios.
1. Ir a la página de [Google Cloud Platform](https://console.cloud.google.com/cloud-resource-manager).
2. Crear un nuevo proyecto: Monitor de precios

### 20. Nuevas rutas para los nuevos proveedores
1.  Crear controlador **TwitterLonginController**:
    + $ php artisan make:controller Auth\TwitterLonginController
2. Modificar archivo de rutas **routes\web.php**:
    ```php
    ≡
    // Rutas para login con twitter
    Route::get('login/twitter', [App\Http\Controllers\Auth\TwitterLonginController::class, 'login'])
        ->name('login.twitter');
    Route::get('twitter/auth/callback', [App\Http\Controllers\Auth\TwitterLonginController::class, 'loginWithTwitter']);
    ```
3.  Programar controlador **app\Http\Controllers\Auth\TwitterLonginController.php**:
    ```php
    <?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Laravel\Socialite\Facades\Socialite;

    class TwitterLonginController extends Controller
    {
        public function login(){
            return Socialite::driver('twitter')->redirect();
        }

        public function loginWithTwitter(){
            $userTwitter = Socialite::driver('twitter')->user();

            $user = User::where('email', $userTwitter->getEmail())->first();

            if(!$user){
                $user = User::create([
                    'name' => $userTwitter->getName(),
                    'email' => $userTwitter->getEmail(),
                    'password' => '',
                    'twitter_id' => $userTwitter->getId(),
                    'avatar'  => $userTwitter->getAvatar(),
                    'nick'  => $userTwitter->getNickname()
                ]);
            }

            auth()->login($user);
            return redirect()->route('home');
        }
    }
    ```

### 21. Evitar fallo por email con valor null
Contenido Desactualizado

### 22. Evitar fallo por dominio no admitido
Contenido Desactualizado

### 23. Actualizando URL de callback (Twitter y Fb)
Contenido Desactualizado

### 24. Solicitando email en Twitter
Contenido Desactualizado

### 25. Actualizando datos en el inicio de sesión
Contenido Desactualizado

### 26. Detalles a tener en cuenta sobre el email
Contenido Desactualizado

### 27. Refactorización de código
Contenido Desactualizado

### 28. Íconos para nuestra aplicación cliente
Contenido Desactualizado

### Commit en GitHub
+ $ git add .
+ $ git commit -m "Más proveedores de OAuth 2"
+ $ git push -u origin main


## Sección 5: Monitor de precios
### 29. Mockups y planteamiento inicial
Contenido Desactualizado

### 30. Gestión de ubicaciones
Contenido Desactualizado

### 31. Gestión de ítems
Contenido Desactualizado

### 32. Middleware y Roles de usuario
Contenido Desactualizado

### 33. Dropdown: Enlaces según rol
Contenido Desactualizado

### 34. Cargar valores de ítems (diseño)
Contenido Desactualizado

### 35. Cargar valores de ítems (funcionalidad)
Contenido Desactualizado

### 36. Relaciones entre modelos
Contenido Desactualizado

### 37. Monitor: Listar, paginar y filtrar ítems
Contenido Desactualizado

### 38. Monitor: Valor más bajo registrado
Contenido Desactualizado

### 39. Monitor: Mostrar detalles (valores cargados)
Contenido Desactualizado

### 40. Monitor: Eliminar valores cargados (admin)
Contenido Desactualizado

### 41. Eliminación lógica (Items y Locations)
Contenido Desactualizado

### 42. Relaciones considerando SoftDeletes
Contenido Desactualizado

### 43. Exportar detalles (generar descarga XLSX)
+ https://laravel-excel.com
+ https://github.com/SpartnerNL/Laravel-Excel

### Commit en GitHub
+ $ git add .
+ $ git commit -m "Monitor de precios"
+ $ git push -u origin main


### 44. Eliminación condicionada (física y lógica)
7 min
### 45. Edición de ubicaciones e ítems
16 min
### 46. Select con búsqueda
16 min




    ≡
    ```php
    ```


DroidCam OBS