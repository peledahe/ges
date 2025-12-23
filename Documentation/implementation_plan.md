# Plan de Implementación - CRM Taller Mecánico

# Objetivo

Desarrollar un sistema CRM Multi-tenant en Laravel 12 para gestionar múltiples talleres mecánicos, cubriendo recepción, reparación, inventario y atención al cliente.

## User Review Required

> [!IMPORTANT] > **Versión de Laravel**: Se instalará la última versión estable disponible (Laravel 11.x) si la versión 12 no ha sido liberada oficialmente.
> **Configuración Web**: Se asume que Nginx apuntará a `/home/pdaniels/desarrollo/ges/public`. Se requiere configuración del servidor web si no está pre-configurada para este directorio.

## Cambios Propuestos

### Fase 1: Inicialización y Configuración

#### [NEW] [Proyecto Laravel](file:///home/pdaniels/desarrollo/ges/)

- Instalación vía Composer.
- Configuración de `.env` (DB: `ges`, User: `perry`, Pass: `password`).
- Instalación de Jetstream (con Livewire) para Auth y Teams (adaptado para Tenancy) o implementación custom de Tenancy.
- Configuración de Tailwind CSS.

### Fase 2: Capa de Datos y Multi-tenancy

#### [NEW] [Tenancy Core]

- Migración `tenants`.
- Trait `BelongsToTenant`.
- Scope `TenantScope`.
- Middleware `CheckTenant`.

#### [NEW] [Modelos Principales]

- Migraciones para: `areas`, `vehicles`, `work_orders`, `work_order_items`, `work_order_photos`.

### Fase 3: Lógica de Negocio y Módulos

#### [NEW] [Módulo de Recepción Livewire]

- Formulario de varios pasos: Datos Cliente -> Datos Vehículo -> Checklist -> Fotos.
- Subida de imágenes.

#### [NEW] [Tablero de Trabajo (Workshop)]

- Vista Kanban o Listado agrupado por Áreas.
- Acciones para mover tickets entre estados/áreas.

#### [NEW] [Administración y Reportes]

- ABM de Áreas (CRUD).
- Gestión de Usuarios (Invitaciones al Tiller).
- Autorizaciones de presupuestos.

### Fase 4: Portal Público y Notificaciones

#### [NEW] [Tracking Público]

- Página simple `/rastreo` con input de placa.
- Muestra línea de tiempo del vehículo.

#### [NEW] [Notificaciones]

- Listeners para eventos `WorkOrderStatusUpdated`.
- Envío de correos al cliente.

## Plan de Verificación

### Pruebas Automatizadas

- `php artisan test`: Pruebas de modelos y scopes de tenancy.
- Pruebas de flujo de crear orden.

### Verificación Manual

- Crear un Tenant "Taller A".
- Crear un Tenant "Taller B".
- Loguearse en A, crear vehículo.
- Loguearse en B, verificar que NO se ve el vehículo de A.
- Simular recepción completa con carga de fotos.
