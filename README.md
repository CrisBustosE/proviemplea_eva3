# ProviEmplea API - Evaluación 3

Plataforma digital de búsqueda inversa de empleo para vecinas/os de Providencia. Las empresas buscan a los candidatos, no al revés. Los perfiles se presentan en formato **CV ciego**: sin nombre, edad, género ni comuna, para evitar discriminación arbitraria en el proceso de selección.

## Stack Tecnológico Definido
- **Lenguaje:** PHP 8.2
- **Framework:** Laravel 11
- **Base de Datos:** MySQL
- **Infraestructura:** Docker & Docker Compose
- **Documentación API:** OpenAPI 3.0 / L5-Swagger

## Requerimientos Solicitados y Desarrollados
1. **Operaciones CRUD:** Se implementaron los controladores, modelos y rutas para la gestión completa de Talentos (Personas), Empresas y el proceso de Administración (solicitudes de contacto).
2. **Documentación OpenAPI:** Se generó el contrato `swagger.yaml` utilizando atributos nativos de PHP 8, aislando los esquemas de datos bajo el estándar PSR-4.
3. **Optimización de Rendimiento:** - **Rate Limiting:** Implementación de un límite global de 60 peticiones por minuto por IP para prevenir abusos.
   - **Caché:** Implementación de `Cache::remember` con un TTL de 1 hora en el endpoint de estadísticas para reducir la carga de consultas SQL a la base de datos.

## Guía de Instalación y Ejecución (Docker)

```bash
# 1. Clonar el repositorio
git clone https://github.com/CrisBustosE/proviemplea_eva3
cd proviemplea_eva3/backend

# 2. Copiar el archivo de entorno
cp .env.example .env

# 3. Editar .env con los siguientes valores:
APP_NAME="ProviEmplea API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=proviemplea
DB_USERNAME=proviemplea_user
DB_PASSWORD=proviemplea_pass

# 3. Levantar los contenedores
docker compose up -d --build

# 4. Instalar dependencias PHP
docker compose exec app composer install

# 5. Generar clave de aplicación
docker compose exec app php artisan key:generate

# 6. Ejecutar migraciones y seeders
docker compose exec app php artisan migrate 

# 7. Generar documentación Swagger
docker compose exec app php artisan l5-swagger:generate
```

La API queda disponible en: `http://localhost:8080/api`  
La documentación Swagger UI en: `http://localhost:8080/api/documentation`

Nota: El contrato oficial de la API se encuentra en el archivo swagger.yaml adjunto en la raíz de este proyecto.

---
## Estructura del proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php              # Metadatos OpenAPI (@OA\Info, @OA\Server)
│   │   ├── HealthController.php        # Endpoint de salud
│   │   ├── PersonaController.php       # CRUD de personas/talentos
│   │   ├── EmpresaController.php       # CRUD de empresas
│   │   ├── AdministracionController.php # Gestión de contactos y estadísticas
│   │   └── Schemas/
│   │       ├── PersonaSchema.php        # Schemas OpenAPI de Persona
│   │       ├── EmpresaSchema.php        # Schemas OpenAPI de Empresa
│   │       └── ContactoSolicitadoSchema.php # Schemas OpenAPI de Contacto
│   └── Traits/
│       └── ApiResponse.php             # Respuestas JSON estandarizadas
├── Models/
│   ├── Persona.php
│   ├── Empresa.php
│   └── ContactoSolicitado.php
└── Providers/
    └── AppServiceProvider.php          # Configuración de Rate Limiting
storage/
└── api-docs/
    └── api-docs.json                   # Especificación OpenAPI generada
```

---

## Endpoints disponibles

### Health
| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/health` | Estado del servicio |

### Personas
| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/personas` | Listar talentos en formato CV ciego |
| POST | `/api/personas` | Registrar nuevo talento |
| GET | `/api/personas/{id}` | Obtener perfil completo |
| PUT | `/api/personas/{id}` | Actualizar perfil |
| DELETE | `/api/personas/{id}` | Desactivar perfil (soft delete) |
| PATCH | `/api/personas/{id}/validar` | Validar talento para vitrina |

### Empresas
| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/empresas` | Listar empresas activas |
| POST | `/api/empresas` | Registrar nueva empresa |
| GET | `/api/empresas/{id}` | Obtener empresa |
| PUT | `/api/empresas/{id}` | Actualizar empresa |
| DELETE | `/api/empresas/{id}` | Desactivar empresa (soft delete) |
| PATCH | `/api/empresas/{id}/validar` | Validar empresa |

### Administración
| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/admin/contactos` | Listar solicitudes de contacto |
| POST | `/api/admin/contactos` | Registrar solicitud de contacto |
| PATCH | `/api/admin/contactos/{id}/estado` | Actualizar estado del proceso |
| GET | `/api/admin/estadisticas` | Estadísticas generales de la plataforma |

---

## Documentación Swagger (OpenAPI 3.0)

La especificación OpenAPI se genera automáticamente a partir de las anotaciones en el código PHP usando **swagger-php 6** con sintaxis de atributos PHP 8 (`#[OA\...]`).

El archivo generado se ubica en `storage/api-docs/api-docs.json` y también en la raíz del proyecto como `swagger.yaml` para facilitar la revisión.

Para regenerar la documentación tras cualquier cambio:

```bash
docker compose exec app php artisan l5-swagger:generate
```

---

## Optimizaciones implementadas

### Rate Limiting 
Todas las rutas de la API están protegidas con un límite de **60 peticiones por minuto por IP**, implementado en `AppServiceProvider.php` usando el sistema nativo de Laravel:

```php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->ip());
});
```

Cuando se supera el límite, la API responde con HTTP `429 Too Many Requests`.

### Caché de estadísticas 
El endpoint `GET /api/admin/estadisticas` almacena su resultado en caché durante **60 minutos** usando `Cache::remember`, evitando consultas repetidas a la base de datos:

```php
$datos = Cache::remember('estadisticas_proviemplea', 3600, function () {
    // consultas a la BD...
});
```

Tiempo de respuesta esperado tras la primera carga: **< 50ms**.

---

## Formato de respuestas

Todas las respuestas siguen una estructura JSON estandarizada mediante el trait `ApiResponse`:

**Éxito:**
```json
{
  "data": { ... },
  "status": 200
}
```

**Error:**
```json
{
  "message": "Descripción del error",
  "status": 404
}
```

---

## Variables de entorno principales

| Variable | Descripción | Valor por defecto |
|---|---|---|
| `DB_HOST` | Host de la base de datos | `db` |
| `DB_DATABASE` | Nombre de la base de datos | `proviemplea` |
| `DB_USERNAME` | Usuario MySQL | `proviemplea_user` |
| `DB_PASSWORD` | Contraseña MySQL | `proviemplea_pass` |
| `L5_SWAGGER_GENERATE_ALWAYS` | Regenerar docs en cada request | `false` |

---

## Autor

<!-- Reemplazar con los nombres reales del grupo -->
- Cristóbal Bustos

**Nombre del equipo:** CBustos