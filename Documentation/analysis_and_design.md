# Análisis y Diseño Conceptual - Sistema CRM Talleres

## Modelo Conceptual

El sistema sigue una arquitectura **Multi-Tenant (Inquilinos Múltiples)** con base de datos compartida y segregación lógica de datos.

### Actores

1.  **Super Admin (Dueño del Software)**: Gestiona la creación de talleres (Tenants).
2.  **Admin de Taller**: Gestiona su propio taller, usuarios y configuraciones.
3.  **Recepcionista**: Recibe vehículos, toma fotos, crea órdenes de trabajo.
4.  **Encargado de Área**: Gestiona los trabajos asignados a su área (Mecánica, Pintura, etc.).
5.  **Cliente (Externo)**: Consulta el estado de su vehículo vía web.

### Módulos Principales

1.  **Gestión de inquilinos (Tenancy)**: Aislamiento de datos.
2.  **Autenticación y Seguridad**: Login, Roles, 2FA.
3.  **Flujo Operativo**:
    -   Recepción -> Diagnóstico -> Presupuesto/Autorización -> Reparación (Áreas) -> Control Calidad -> Entrega.
4.  **Administrativo/Compras**: Gestión de cotizaciones y repuestos.
5.  **Notificaciones**: Emails transaccionales.

## Diseño de Base de Datos (Esquema Lógico)

### Tablas Globales / Sistema

-   **tenants**: `id`, `name`, `slug` (subdomain/path), `admin_name`, `email`, `phone`, `address`, `users_limit`, `is_active`.
-   **users**: `id`, `tenant_id` (FK), `name`, `email`, `password`, `two_factor_secret`, `role` (enum: top_admin, admin, employee).

### Tablas Operativas (Tenant Scoped)

Todas estas tablas incluyen `tenant_id`.

-   **areas**: `id`, `tenant_id`, `name` (e.g. "Recepción", "Pintura"), `description`.
-   **vehicles**:
    -   `id`, `tenant_id`
    -   `plate`, `vin`, `brand`, `model`, `line`, `type`, `color`, `year`, `engine_number`, `chassis_number`, `doors_qty`, `cc`
    -   `owner_name`, `owner_phone`, `owner_email`, `owner_nit`, `owner_address`
-   **work_orders** (Asignaciones):
    -   `id`, `tenant_id`
    -   `vehicle_id` (FK)
    -   `status` (enum: recepcion, presupuesto, autorizado, en_proceso, finalizado, entregado)
    -   `payment_type` (enum: aseguradora, particular)
    -   `insurance_company` (string, nullable)
    -   `current_area_id` (FK -> areas)
    -   `mileage` (int), `fuel_level` (enum: E, 1/4, 1/2, 3/4, F)
    -   `received_at`, `delivered_at`
-   **checklist_templates**: `id`, `tenant_id`, `name` (e.g. "Estándar"), `items` (JSON structure defining sections and items).
-   **work_order_checklists**:
    -   `id`, `work_order_id`
    -   `group_name` (e.g. "Parte Trasera", "Parte Frontal", "Interior")
    -   `item_name` (e.g. "Emblema", "Radio")
    -   `status` (enum: correct, wear, bad, missing)
    -   `notes`
-   **work_order_damages**: `id`, `work_order_id`, `description`, `x_coord`, `y_coord`, `view_angle` (front, rear, left, right).
-   **work_order_details**: `id`, `work_order_id`, `description`, `type` (repuesto, mano_obra), `status`, `cost`, `price`.

*   **work_order_photos**: `id`, `work_order_id`, `path`, `category` (daño_inicial, proceso, final).
*   **work_order_logs**: `id`, `work_order_id`, `action`, `description` (e.g. "Movido de Recepción a Mecánica"), `user_id`.

## Diseño de Software

### Stack Tecnológico

-   **Backend**: Laravel 12 (features: Scopes para Tenancy, Jetstream para Auth/2FA, Notifications).
-   **Frontend**: Blade + Tailwind CSS (Alpine.js para interactividad ligera, Livewire para componentes dinámicos como el tablero Kanban o formularios reactivos).
-   **DB**: MySQL 8.

### Seguridad

-   **TenantScope**: Un `GlobalScope` en Laravel aplicará automáticamente `where('tenant_id', session('tenant_id'))` a todas las consultas.
-   **Middleware**: `CheckTenantStatus` verificará que el usuario pertenezca al tenant activo.
-   **2FA**: Implementado via Laravel Fortify/Jetstream.

### Flujos Clave

1.  **Recepción**:
    -   Usuario busca placa -> Si existe carga datos, si no crea vehículo.
    -   Crea WorkOrder -> Sube fotos (S3/Local) -> Llena Checklist.
    -   Asigna área inicial (e.g. Diagnóstico o Mecánica).
2.  **Seguimiento**:
    -   Dashboard tipo Kanban columnas por Áreas.
    -   Drag & Drop o botón "Mover a..." para cambiar de área.
3.  **Cliente**:
    -   Ruta pública `/tracking/{placa}` -> Muestra Timeline simple.
