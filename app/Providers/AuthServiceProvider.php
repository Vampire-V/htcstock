<?php

namespace App\Providers;

use App\Enum\UserEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Legal\LegalContract;
use App\Policies\LegalContractPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        LegalContract::class => LegalContractPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::define(string,callback) กำหนดสิทธิ์ parameter1 คือ ชื่อ parameter2 คือ function return condition 
        // ตอนเรียกใช้ controller Gate::denies('for-superadmin-admin')
        Gate::define('for-superadmin-admin', function ($user) {
            // $user->hasRole('admin','author') True
            return $user->hasRole(UserEnum::SUPERADMIN,UserEnum::ADMIN);
        });

        Gate::define('for-superadmin', function ($user) {
            // Gate::denies('for-superadmin') เรียกใช้ที่ controller จะได้ $user ที่ใช้งานอยู่
            // $user->hasRole('admin') True
            return $user->hasRole(UserEnum::SUPERADMIN);
        });

        Gate::define('for-adminlegal', function ($user) {
            return $user->hasRole(UserEnum::ADMINLEGAL);
        });
        Gate::define('for-userlegal', function ($user) {
            return $user->hasRole(UserEnum::USERLEGAL);
        });
        
        try {
            Role::get()->map(function ($role) {
                Gate::define($role->slug, function ($user) use ($role) {
                    return $user->hasRole($role);
                });
            });
            Permission::get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });

        } catch (\Throwable $th) {
            report($th);
            return false;
        }

        // Legal permission
        
    }
}
