# ARQ — Brief

## Qué es
Aplicación web de gestión de proyectos de arquitectura/obra: presupuestos, costos reales, flujo de caja, clientes, proveedores y documentos.

## Stack
- PHP ^8.2, Laravel 12 (migrado desde Laravel 6)
- MySQL 8.4
- Auth: `laravel/ui`
- Formularios: `rdx/laravelcollective-html`
- Exportes Excel: `maatwebsite/excel`
- Frontend: Laravel Mix 5 (webpack)
- Entorno de desarrollo: Laravel Sail (Docker)

## Módulos (rutas autenticadas)
| Ruta | Descripción |
|------|-------------|
| `/projects` | Proyectos (presupuesto, costos reales, proveedores) |
| `/project_documents` | Documentos de proyecto |
| `/project_cash_flow` | Flujo de caja |
| `/clients` | Clientes |
| `/providers` | Proveedores |
| `/items`, `/sub_items` | Rubros y subrubros del presupuesto |
| `/user_files` | Archivos de usuario |
| `/users`, `/roles` | Usuarios y roles |
| `/json/*` | Endpoints AJAX (borrado de items/subitems de presupuesto) |

`/` y `/home` redirigen a `/projects`.

## Modelos
`Project`, `ProjectBudget`, `ProjectRealCost`, `ProjectCashFlow`, `ProjectDocument`, `Client`, `Provider`, `Item`, `SubItem`, `User`, `UserFile`.

## Exportes (`app/Exports`)
Clientes, proveedores, presupuesto de proyecto, costos reales de proyecto, proveedores de proyecto (con logo).

## Estructura
```
src/
├── app/            # modelos, controllers (Backend/), exports
├── routes/web.php
├── database/       # migrations, seeders
├── resources/      # views, assets
├── compose.yaml    # Sail
└── Docs/           # documentación
```

## Entorno
Ver `README.md` para levantar con Sail.
