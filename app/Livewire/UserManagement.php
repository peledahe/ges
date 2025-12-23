<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Area;
use Illuminate\Support\Facades\Hash;
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
    public $area_id;
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
            'area_id' => 'nullable|exists:areas,id',
            'can_view_contact_info' => 'boolean',
            'password' => $this->userId ? 'nullable|min:8' : 'required|min:8',
        ];
    }

    public function render()
    {
        $users = User::where('tenant_id', session('tenant_id'))
            ->where(function($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->with('area')
            ->paginate(10);
            
        return view('livewire.user-management', [
            'users' => $users,
            'areas' => Area::all(),
            'roles' => [
                'admin' => 'Administrador',
                'manager' => 'Encargado de Área',
                'reception' => 'Recepción',
                'budget' => 'Presupuestos',
                'client' => 'Cliente',
            ]
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'area_id']);
        $this->role = 'client';
        $this->can_view_contact_info = false;
        $this->managingUserObject = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->area_id = $user->area_id;
        $this->can_view_contact_info = $user->can_view_contact_info;
        $this->password = ''; // Don't show password
        
        $this->managingUserObject = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->userId) {
            $user = User::find($this->userId);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'area_id' => $this->area_id,
                'can_view_contact_info' => $this->can_view_contact_info,
            ];
            
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }
            
            $user->update($data);
            $this->dispatch('saved');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'area_id' => $this->area_id,
                'can_view_contact_info' => $this->can_view_contact_info,
                'tenant_id' => session('tenant_id'), // Ensure tenant assignment
            ]);
            $this->dispatch('saved');
        }

        $this->managingUserObject = false;
        $this->dispatch('notify', 'Usuario guardado correctamente.');
    }

    public function confirmDeletion($id)
    {
        $this->userId = $id;
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        $user = User::find($this->userId);
        if ($user) {
            $user->delete();
            $this->dispatch('notify', 'Usuario eliminado.');
        }
        $this->confirmingUserDeletion = false;
    }
}
