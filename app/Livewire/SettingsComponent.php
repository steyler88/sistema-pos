<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class SettingsComponent extends Component
{
    // ========================================
    // PROPIEDADES - TAB 1: GENERAL
    // ========================================
    public $company_name;
    public $company_ruc;
    public $company_address;
    public $company_phone;
    public $company_website;
    public $ticket_footer_message;
    public $timezone;

    // ========================================
    // PROPIEDADES - TAB 2: USUARIOS
    // ========================================
    public $users = [];
    public $showUserModal = false;
    public $editingUserId = null;
    public $user_name;
    public $user_email;
    public $user_password;
    public $user_password_confirmation;

    // ========================================
    // PROPIEDADES - TAB 3: REGLAS DE NEGOCIO
    // ========================================
    public $enable_multi_pricing;
    public $tax_rate;
    public $default_currency;
    public $enable_inventory;
    public $enable_discounts;
    public $auto_print;

    // ========================================
    // PROPIEDADES - UI
    // ========================================
    public $activeTab = 'general'; // general, users, business

    /**
     * Zonas horarias disponibles
     */
    public $timezones = [
        'America/Lima' => 'Lima (GMT-5)',
        'America/New_York' => 'Nueva York (GMT-5)',
        'America/Mexico_City' => 'Ciudad de México (GMT-6)',
        'America/Bogota' => 'Bogotá (GMT-5)',
        'America/Argentina/Buenos_Aires' => 'Buenos Aires (GMT-3)',
        'Europe/Madrid' => 'Madrid (GMT+1)',
        'UTC' => 'UTC (GMT+0)',
    ];

    /**
     * Montar el componente
     */
    public function mount()
    {
        $this->loadSettings();
        $this->loadUsers();
    }

    /**
     * Cargar configuraciones desde la base de datos
     */
    public function loadSettings()
    {
        // TAB 1: GENERAL
        $this->company_name = Setting::get('company_name', 'Mi Negocio');
        $this->company_ruc = Setting::get('company_ruc', '');
        $this->company_address = Setting::get('company_address', '');
        $this->company_phone = Setting::get('company_phone', '');
        $this->company_website = Setting::get('company_website', '');
        $this->ticket_footer_message = Setting::get('ticket_footer_message', 'Gracias por su compra');
        $this->timezone = Setting::get('timezone', 'America/Lima');

        // TAB 3: REGLAS DE NEGOCIO
        $this->enable_multi_pricing = Setting::get('enable_multi_pricing', false);
        $this->tax_rate = Setting::get('tax_rate', '0.18');
        $this->default_currency = Setting::get('default_currency', 'S/');
        $this->enable_inventory = Setting::get('enable_inventory', false);
        $this->enable_discounts = Setting::get('enable_discounts', true);
        $this->auto_print = Setting::get('auto_print', true);
    }

    /**
     * Cargar lista de usuarios
     */
    public function loadUsers()
    {
        $this->users = User::all()->toArray();
    }

    /**
     * Guardar configuraciones generales (TAB 1)
     */
    public function saveGeneralSettings()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'company_ruc' => 'required|string|max:20',
            'company_address' => 'required|string|max:500',
            'company_phone' => 'required|string|max:20',
            'company_website' => 'nullable|string|max:255',
            'ticket_footer_message' => 'required|string|max:255',
            'timezone' => 'required|string',
        ]);

        Setting::set('company_name', $this->company_name, 'general', 'string');
        Setting::set('company_ruc', $this->company_ruc, 'general', 'string');
        Setting::set('company_address', $this->company_address, 'general', 'string');
        Setting::set('company_phone', $this->company_phone, 'general', 'string');
        Setting::set('company_website', $this->company_website, 'general', 'string');
        Setting::set('ticket_footer_message', $this->ticket_footer_message, 'general', 'string');
        Setting::set('timezone', $this->timezone, 'general', 'string');

        // Actualizar timezone en config (requiere reinicio)
        config(['app.timezone' => $this->timezone]);

        $this->dispatch('alert', type: 'success', message: '¡Configuración general guardada!');
    }

    /**
     * Guardar reglas de negocio (TAB 3)
     */
    public function saveBusinessSettings()
    {
        Setting::set('enable_multi_pricing', $this->enable_multi_pricing, 'business', 'boolean');
        Setting::set('tax_rate', $this->tax_rate, 'business', 'string');
        Setting::set('default_currency', $this->default_currency, 'business', 'string');
        Setting::set('enable_inventory', $this->enable_inventory, 'business', 'boolean');
        Setting::set('enable_discounts', $this->enable_discounts, 'business', 'boolean');
        Setting::set('auto_print', $this->auto_print, 'printing', 'boolean');

        $this->dispatch('alert', type: 'success', message: '¡Reglas de negocio actualizadas!');
    }

    /**
     * Abrir modal para nuevo usuario
     */
    public function openNewUserModal()
    {
        $this->reset(['user_name', 'user_email', 'user_password', 'user_password_confirmation', 'editingUserId']);
        $this->showUserModal = true;
    }

    /**
     * Abrir modal para editar usuario
     */
    public function editUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->editingUserId = $user->id;
            $this->user_name = $user->name;
            $this->user_email = $user->email;
            $this->reset(['user_password', 'user_password_confirmation']);
            $this->showUserModal = true;
        }
    }

    /**
     * Guardar usuario (crear o actualizar)
     */
    public function saveUser()
    {
        $rules = [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:users,email,' . ($this->editingUserId ?? 'NULL'),
        ];

        if ($this->editingUserId) {
            // Editando: password es opcional
            if ($this->user_password) {
                $rules['user_password'] = 'min:6|confirmed';
            }
        } else {
            // Creando: password es obligatorio
            $rules['user_password'] = 'required|min:6|confirmed';
        }

        $this->validate($rules);

        if ($this->editingUserId) {
            // Actualizar usuario existente
            $user = User::find($this->editingUserId);
            $user->name = $this->user_name;
            $user->email = $this->user_email;
            if ($this->user_password) {
                $user->password = Hash::make($this->user_password);
            }
            $user->save();

            $message = 'Usuario actualizado correctamente';
        } else {
            // Crear nuevo usuario
            User::create([
                'name' => $this->user_name,
                'email' => $this->user_email,
                'password' => Hash::make($this->user_password),
            ]);

            $message = 'Usuario creado correctamente';
        }

        $this->showUserModal = false;
        $this->loadUsers();
        $this->dispatch('alert', type: 'success', message: $message);
    }

    /**
     * Eliminar usuario
     */
    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user && $user->id !== auth()->id()) {
            $user->delete();
            $this->loadUsers();
            $this->dispatch('alert', type: 'success', message: 'Usuario eliminado');
        } else {
            $this->dispatch('alert', type: 'error', message: 'No puedes eliminar tu propio usuario');
        }
    }

    /**
     * Cerrar modal
     */
    public function closeModal()
    {
        $this->showUserModal = false;
    }

    /**
     * Cambiar de pestaña
     */
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Renderizar componente
     */
    public function render()
    {
        return view('livewire.settings-component');
    }
}
