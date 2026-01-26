# API RESTful de Gestión de Cursos - Laravel + Sanctum

Sistema completo de API RESTful segura para la gestión de cursos, con autenticación basada en tokens, roles de usuario y operaciones CRUD, construido con **Laravel 11/12** y **Laravel Sanctum**, siguiendo estrictas prácticas de seguridad y arquitectura moderna.

![API Diagram](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

## Descripción

Esta aplicación implementa un backend robusto para un sistema educativo. Permite a los administradores gestionar el ciclo de vida de **Alumnos, Profesores, Asignaturas y Exámenes**, mientras que los usuarios autenticados pueden consultar información y gestionar su propio perfil de forma segura.

---

## Capturas de Pantalla

*(Espacio reservado para tus capturas de Postman o de la Web de Prueba)*

### 1. Login y Obtención de Token
Autenticación segura devolviendo token Bearer.
![Login Postman](imgReadme/postman_login.png)

### 2. Gestión de Perfil de Usuario
Endpoint protegido donde el usuario modifica sus propios datos.
![User Profile](imgReadme/postman_profile.png)

### 3. CRUD de Exámenes
Operaciones completas de gestión académica.
![CRUD Examen](imgReadme/postman_crud.png)

---

## Arquitectura del Proyecto

El proyecto sigue la arquitectura estándar de **Laravel**, separando claramente rutas, controladores y modelos:

```
api-cursos/
├── app/
│   ├── Http/
│   │   ├── Controllers/           # Lógica de Negocio
│   │   │   ├── Api/
│   │   │   │   ├── AuthController.php      # Login, Registro, Perfil
│   │   │   │   ├── AlumnoController.php    # CRUD Alumnos
│   │   │   │   ├── ProfesorController.php  # CRUD Profesores
│   │   │   │   ├── ExamenController.php    # CRUD Exámenes
│   │   │   │   └── AsignaturaController.php# CRUD Asignaturas
│   │   └── Middleware/            # Filtros de Seguridad (Sanctum)
│   │
│   └── Models/                    # ORM Eloquent
│       ├── User.php               # Usuarios del sistema
│       ├── Alumno.php             # Entidad Académica
│       ├── Examen.php             # Entidad Académica (con relaciones)
│       └── ...
│
├── database/                      # Estructura de Datos
│   ├── migrations/                # Definición de tablas
│   └── seeders/                   # Datos de prueba
│
├── routes/
│   └── api.php                    # Definición de Endpoints Seguros
│
└── public/
    └── test_api.html              # Cliente web ligero para pruebas
```

### Flujo de la Aplicación

```
┌─────────────────────────────┐
│      Petición HTTP          │
│  (Postman / Frontend / App) │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│      routes/api.php         │
│    (Enrutamiento Seguro)    │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐       ┌────────────────────────┐
│    Middleware Sanctum       │ ◀──── │   Base de Datos        │
│  (Verificación de Token)    │       │ (personal_access_tokens)│
└──────────────┬──────────────┘       └────────────────────────┘
               │
               ▼
┌─────────────────────────────┐
│       Controladores         │
│   (Validación y Lógica)     │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐       ┌────────────────────────┐
│      Modelos (Eloquent)     │ ◀───▶ │   Base de Datos        │
│     (Acceso a Datos)        │       │      (MySQL)           │
└─────────────────────────────┘       └────────────────────────┘
```

---

## Características de Seguridad Implementadas

### 1. Autenticación con Laravel Sanctum
Uso de tokens para asegurar la comunicación sin estado (Stateless). Cada petición debe incluir el cabezal `Authorization: Bearer <token>`.

**Archivo:** `routes/api.php`
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('examenes', ExamenController::class);
    // ...
});
```

### 2. Validación Estricta (Backend)
Todos los datos entrantes son validados antes de procesarse para asegurar integridad y evitar inyecciones.

**Archivo:** `app/Http/Controllers/Api/ExamenController.php`
```php
$validator = Validator::make($request->all(), [
    'dia_examen' => 'required|date_format:Y-m-d H:i:s',
    'tema' => 'required|string|max:255',
    'nota' => 'nullable|numeric|min:0|max:10' // Validación de rango
]);
```

### 3. Protección de Datos Sensibles
Los usuarios solo pueden modificar su propio perfil. La lógica impide modificar datos de otros IDs.

**Archivo:** `app/Http/Controllers/Api/AuthController.php`
```php
public function updateProfile(Request $request) {
    $user = $request->user(); // Obtiene el usuario DEL TOKEN, no por ID en URL
    // ... lógica de actualización
}
```

### 4. Relaciones Protegidas (Integridad Referencial)
Uso de claves foráneas y restricciones en base de datos para evitar datos huérfanos.

**Archivo:** `database/migrations/...create_examens_table.php`
```php
$table->foreignId('alumno_id')->constrained()->onDelete('cascade');
```

---

---

## Guía de Pruebas

Este proyecto incluye múltiples formas de verificar su funcionamiento sin necesidad de herramientas externas complejas.

### OPCIÓN A: Interfaz Web de Prueba (Recomendada)
Hemos incluido un cliente web ligero para probar la API desde el navegador.

1.  Asegúrate de que tu servidor esté corriendo (`php artisan serve` o Laragon).
2.  Accede a: `http://api-cursos.test/test_api.html` (o `http://127.0.0.1:8000/test_api.html`).
3.  Ingresa la URL Base (ej. `http://api-cursos.test/api`).
4.  Realiza el **Login** y prueba la actualización de perfil.

### OPCIÓN B: Script Automático (PowerShell)
Si estás en Windows, puedes usar el script de validación incluido:

```powershell
./verify_api.ps1
```
Este script realizará automáticamente:
1.  Login con el usuario admin.
2.  Obtención del perfil.
3.  Validación de endpoints.

### OPCIÓN C: Postman
Si prefieres usar Postman:
1.  Importa la colección (si la hubiera) o crea una nueva Request.
2.  **Login**: POST a `/api/login` con Body JSON `{"email": "...", "password": "..."}`.
3.  **Copia el Token** de la respuesta.
4.  **Otras peticiones**: En la pestaña *Authorization*, selecciona **Bearer Token** y pega el código.

### OPCIÓN D: Hoppscotch (Web Gratuita)
Si no quieres instalar programas y prefieres una web (como Postman pero en el navegador):
1.  Ve a [Hoppscotch.io](https://hoppscotch.io/).
2.  (Importante) Instala su extensión de navegador para poder conectar con `localhost`.
3.  Funciona igual: Url, Método y Body JSON.

---


### 1. Requisitos
*   PHP 8.2 o superior.
*   Composer.
*   Servidor MySQL/MariaDB.

### 2. Instalación
```bash
# 1. Instalar dependencias
composer install

# 2. Configurar entorno
cp .env.example .env
# (Configura DB_DATABASE, DB_USERNAME, etc en el archivo .env)

# 3. Generar clave de aplicación
php artisan key:generate

# 4. Migrar base de datos y sembrar datos de prueba
php artisan migrate --seed
```

### 3. Usuarios de Prueba
Al ejecutar los seeders, se crea el siguiente usuario por defecto en `database/seeders/UserSeeder.php`:

| Rol       | Email               | Contraseña    |
| :-------- | :------------------ | :------------ |
| **Admin** | `admin@example.com` | `password123` |

---

## Tecnologías Utilizadas

| Tecnología  | Versión | Uso                      |
| :---------- | :------ | :----------------------- |
| **Laravel** | 11.x    | Framework Backend        |
| **PHP**     | 8.2+    | Lenguaje del Servidor    |
| **Sanctum** | Latest  | Autenticación de API     |
| **MariaDB** | 10.x    | Base de Datos Relacional |
| **Postman** | -       | Herramienta de Testing   |

---

## Referencias

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [API RESTful y CRUD Laravel (Notion)](https://charmed-group-fc8.notion.site/API-RESTful-y-CRUD-Laravel-2ed60ff317c980e49178df5a9969d930)
