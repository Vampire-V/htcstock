<?php

namespace App\Models;

use App\Relations\SystemTrait;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use SystemTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system';


}
