<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RoleBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public $user;
    public $isAdmin;
    public $isSuperadmin;

    public function __construct($user = null, $isAdmin = null, $isSuperadmin = null)
    {
        $this->user = $user;
        $this->isAdmin = $user?->is_admin ?? $isAdmin;
        $this->isSuperadmin = $user?->is_superadmin ?? $isSuperadmin;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.role-badge');
    }
}
