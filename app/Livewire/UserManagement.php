<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingUserDeletion = false;
    public $managingUserObject = false;

    // Form fields
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role = 'client';
    public $is_active = true;
    public $area_id; // Área primaria (compatibilidad)
    public array $selectedAreaIds = []; // 1 o múltiples áreas con acceso
    public $can_view_contact_info = false;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|string',
            'is_active' => 'boolean',
            'area_id' => 'nullable|exists:areas,id',
            'selectedAreaIds' => 'array',
            'can_view_contact_info' => 'boolean',
            'password' => $this->userId ? 'nullable|min:8' : 'required|min:8',
        ];
    }

    public function updatedRole($value): void
    {
        // Si es nuevo usuario y se selecciona rol cliente, por defecto inactivo
        if (!$this->userId && $value === 'client') {
            $this->is_active = false;
        }
    }

    public function getIsDemoTenantProperty(): bool
    {
        $tenantId = session('tenant_id') ?: auth()->user()?->tenant_id;
        if ($tenantId) {
            $tenant = \App\Models\Tenant::find($tenantId);
            if ($tenant && $tenant->isDemo()) {
                return true;
            }
        }
        return (bool) auth()->user()?->isDemoTenant();
    }

    public function selectAllAreas(): void
    {
        $tenantId = session('tenant_id') ?: auth()->user()?->tenant_id;
        $this->selectedAreaIds = Area::where('tenant_id', $tenantId)->pluck('id')->map(fn($id) => (int)$id)->toArray();
        $this->area_id = !empty($this->selectedAreaIds) ? $this->selectedAreaIds[0] : null;
    }

    public function clearAreas(): void
    {
        $this->selectedAreaIds = [];
        $this->area_id = null;
    }

    public function create()
    {
        if ($this->isDemoTenant) {
            $this->dispatch('notify', message: 'La empresa de demostración está en modo solo lectura. No es posible crear nuevos usuarios.', type: 'warning');
            return;
        }

        $this->reset(['userId', 'name', 'email', 'password', 'area_id', 'selectedAreaIds']);
        $this->role = 'manager';
        $this->is_active = true;
        $this->can_view_contact_info = false;
        $this->managingUserObject = true;
    }

    public function edit($id)
    {
        $user = User::with('areas')->findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->is_active = (bool) ($user->is_active ?? true);
        $this->area_id = $user->area_id;
        $this->can_view_contact_info = (bool) $user->can_view_contact_info;
        $this->password = '';

        // Cargar áreas asignadas
        $this->selectedAreaIds = $user->areas->pluck('id')->map(fn($val) => (int)$val)->toArray();
        if (empty($this->selectedAreaIds) && $user->area_id) {
            $this->selectedAreaIds = [(int) $user->area_id];
        }

        $this->managingUserObject = true;
    }

    public function save()
    {
        if ($this->isDemoTenant) {
            $this->managingUserObject = false;
            $this->dispatch('notify', message: 'La empresa de demostración está en modo solo lectura. No es posible modificar usuarios.', type: 'warning');
            return;
        }

        $this->validate();

        $primaryAreaId = !empty($this->selectedAreaIds) ? $this->selectedAreaIds[0] : null;

        if ($this->userId) {
            $user = User::find($this->userId);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'is_active' => (bool) $this->is_active,
                'area_id' => $primaryAreaId,
                'can_view_contact_info' => $this->can_view_contact_info,
            ];

            if (!empty($this->password)) {
                if ($user->isDemo()) {
                    $this->dispatch('notify', message: 'La contraseña de esta cuenta demo está protegida y se mantiene intacta.', type: 'warning');
                } else {
                    $data['password'] = Hash::make($this->password);
                }
            }

            $user->update($data);
            $user->areas()->sync($this->selectedAreaIds);
            $this->dispatch('saved');
        } else {
            $tenantId = session('tenant_id') ?: auth()->user()?->tenant_id;
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'is_active' => (bool) $this->is_active,
                'area_id' => $primaryAreaId,
                'can_view_contact_info' => $this->can_view_contact_info,
                'tenant_id' => $tenantId,
            ]);

            $user->areas()->sync($this->selectedAreaIds);
            $this->dispatch('saved');
        }

        $this->managingUserObject = false;
        $this->dispatch('notify', message: 'Usuario y accesos a áreas guardados correctamente.', type: 'success');
    }

    public function confirmDeletion($id)
    {
        if ($this->isDemoTenant) {
            $this->dispatch('notify', message: 'No es posible eliminar usuarios en la empresa de demostración.', type: 'error');
            return;
        }

        $this->userId = $id;
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        if ($this->isDemoTenant) {
            $this->confirmingUserDeletion = false;
            $this->dispatch('notify', message: 'No es posible eliminar usuarios en la empresa de demostración.', type: 'error');
            return;
        }

        $user = User::find($this->userId);
        if ($user) {
            if ($user->isDemo()) {
                $this->dispatch('notify', message: 'Las cuentas de demostración no pueden ser eliminadas.', type: 'error');
                $this->confirmingUserDeletion = false;
                return;
            }
            $user->areas()->detach();
            $user->delete();
            $this->dispatch('notify', message: 'Usuario eliminado.', type: 'warning');
        }
        $this->confirmingUserDeletion = false;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $tenantId = session('tenant_id') ?: auth()->user()?->tenant_id;

        $users = User::where('tenant_id', $tenantId)
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->with(['area', 'areas'])
            ->paginate(10);

        return view('livewire.user-management', [
            'users' => $users,
            'areas' => Area::where('tenant_id', $tenantId)->orderBy('order')->get(),
            'roles' => [
                'admin' => 'Administrador (Acceso Total)',
                'manager' => 'Encargado de Área / Mecánico',
                'recepcion' => 'Recepción y Entrega',
                'presupuestos' => 'Presupuestos y Cotizaciones',
                'client' => 'Cliente',
            ],
        ]);
    }
}
