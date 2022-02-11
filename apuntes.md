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
9 min





    ≡
    ```php
    ```



### 16. Íconos y botones de login adicionales
10 min

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
5 min


    ≡
    ```php
    ```


### 19. Registro de app en Google+
7 min
### 20. Nuevas rutas para los nuevos proveedores
12 min
### 21. Evitar fallo por email con valor null
4 min
### 22. Evitar fallo por dominio no admitido
9 min
### 23. Actualizando URL de callback (Twitter y Fb)
5 min
### 24. Solicitando email en Twitter
5 min
### 25. Actualizando datos en el inicio de sesión
7 min
### 26. Detalles a tener en cuenta sobre el email
14 min
### 27. Refactorización de código
9 min
### 28. Íconos para nuestra aplicación cliente
4 min
### 29. Mockups y planteamiento inicial
6 min
### 30. Gestión de ubicaciones
18 min
### 31. Gestión de ítems
20 min
### 32. Middleware y Roles de usuario
13 min
### 33. Dropdown: Enlaces según rol
3 min
### 34. Cargar valores de ítems (diseño)
9 min
### 35. Cargar valores de ítems (funcionalidad)
13 min
### 36. Relaciones entre modelos
10 min
### 37. Monitor: Listar, paginar y filtrar ítems
15 min
### 38. Monitor: Valor más bajo registrado
5 min
### 39. Monitor: Mostrar detalles (valores cargados)
11 min
### 40. Monitor: Eliminar valores cargados (admin)
4 min
### 41. Eliminación lógica (Items y Locations)
10 min
### 42. Relaciones considerando SoftDeletes
3 min
### 43. Exportar detalles (generar descarga XLSX)
26 min
### 44. Eliminación condicionada (física y lógica)
7 min
### 45. Edición de ubicaciones e ítems
16 min
### 46. Select con búsqueda
16 min



DroidCam OBS