<?php

namespace App\Mail\Legal;

use App\Models\Legal\LegalContract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractApproval extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The contract instance.
     *
     * @var \App\Models\Legal\LegalContract
     */
    protected $contract;
    protected $user;
    protected $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LegalContract $contract, User $user, string $message)
    {
        $this->contract = $contract;
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.legal.contract.approval')->with([
            'contract' => $this->contract,
            'user' => $this->user,
            'message' => $this->message
        ]);
    }
}
