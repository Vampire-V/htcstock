<?php

namespace App\View\Components\Legal;

use App\Enum\ContractEnum;
use Illuminate\View\Component;

class HeadStatusLegal extends Component
{
    public $legalContract;
    public $status;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($legalContract)
    {
        $this->legalContract = $legalContract;
        $this->status = \collect([ContractEnum::R,ContractEnum::CK,ContractEnum::P,ContractEnum::CP]);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.legal.head-status-legal');
    }
}
