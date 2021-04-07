<?php

namespace App\Mail\KPI;

use App\Models\KPI\Evaluate;
use App\Models\User;
use App\Services\IT\Interfaces\UserServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationReviewMail extends Mailable
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
        $manager = User::where('username',$this->evaluate->user->head_id)->firstOrFail();
        return $this->markdown('emails.kpi.evaluate-review')->with(['evaluate' => $this->evaluate,'manager' => $manager]);
    }
}
