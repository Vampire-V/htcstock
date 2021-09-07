<?php

namespace App\Models;

use App\Relations\DepartmentTrait;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use DepartmentTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Get the comments for the blog post.
     */
    public function cloneLegalApprove($depts)
    {
        $clone = $this->replicate();
        $clone->push();
        foreach ($this->legalApprove as $approve) {
            foreach ($depts as $key => $item) {
                $temp = $approve->toArray();
                $temp['department_id'] = \intval($item);
                $clone->legalApprove()->create($temp);
                $clone->save();
            }
        }
    }
}
