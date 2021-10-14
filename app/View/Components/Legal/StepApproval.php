<?php

namespace App\View\Components\Legal;

use App\Models\Legal\LegalContract;
use Illuminate\View\Component;

class StepApproval extends Component
{
    public $contract;
    public $permission;
    public $formapprove;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(LegalContract $contract, $permission, $formapprove)
    {
        $this->contract = $contract;
        $this->permission = $permission;
        $this->formapprove = $formapprove;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.legal.step-approval');
    }
}
