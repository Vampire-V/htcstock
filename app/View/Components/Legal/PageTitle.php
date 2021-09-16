<?php

namespace App\View\Components\Legal;

use App\Models\Legal\LegalContract;
use Illuminate\View\Component;

class PageTitle extends Component
{
    public $contract;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(LegalContract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.legal.page-title');
    }
}
