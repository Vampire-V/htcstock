<?php

namespace App\Permissions;

use App\Models\Permission;

trait HasPermissionsTrait
{

    public function givePermissionsTo(...$permissions)
    {

        $permissions = $this->getAllPermissions($permissions);
        dd($permissions);
        if ($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    public function withdrawPermissionsTo(...$permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    public function refreshPermissions(...$permissions)
    {

        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

    public function hasPermissionTo($permission)
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    public function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role) {
            // $this->roles คือ roles ของ auth->user
            // ใน $this->roles มี $role ไหม
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole(...$roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    protected function hasPermission($permission)
    {
        // \dd($this->permissions, 'sdf');
        // auth->user มี permission ไหม
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }

    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('slug', $permissions)->get();
    }

    public function permissionsInRole($permission)
    {
        foreach ($this->roles as $key => $value) {
            foreach ($value->permissions as $key => $item) {
                if ($item->slug === $permission->slug) {
                    return \true;
                }
            }
        }
        return \false;
    }
}
