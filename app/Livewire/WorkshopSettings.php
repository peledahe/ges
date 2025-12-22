<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Livewire\Attributes\Layout;

class WorkshopSettings extends Component
{
    use WithFileUploads;

    public $name;
    public $admin_name;
    public $email;
    public $phone;
    public $address;
    
    public $logo; // New Upload
    public $currentLogo; // Existing path

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->tenant) {
            $tenant = $user->tenant;
            $this->name = $tenant->name;
            $this->admin_name = $tenant->admin_name;
            $this->email = $tenant->email;
            $this->phone = $tenant->phone;
            $this->address = $tenant->address;
            $this->currentLogo = $tenant->logo_path;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'admin_name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'logo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $user = Auth::user();
        if ($user && $user->tenant) {
            $tenant = $user->tenant;
            
            $data = [
                'name' => $this->name,
                'admin_name' => $this->admin_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ];

            if ($this->logo) {
                $path = $this->logo->store('logos', 'public');
                $data['logo_path'] = $path;
            }

            $tenant->update($data);
            
            // Re-mount to show updated logo
            if ($this->logo) {
                $this->currentLogo = $data['logo_path'];
                $this->logo = null;
            }

            session()->flash('message', 'Información del taller actualizada correctamente.');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.workshop-settings');
    }
}
