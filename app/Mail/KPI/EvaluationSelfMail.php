<?php

namespace App\Mail\KPI;

use App\Models\KPI\Evaluate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationSelfMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The contract instance.
     *
     * @var \App\Models\KPI\Evaluate
     */
    protected $evaluate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Evaluate $evaluate)
    {
        $this->evaluate = $evaluate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.kpi.evaluate-self')->with(['evaluate' => $this->evaluate]);
    }
}
