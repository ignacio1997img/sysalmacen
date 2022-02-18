<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $role = Role::firstOrNew(['name' => 'admin']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('voyager::seeders.roles.admin'),
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'user']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('user'),
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'Almacen']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('Responsable Almacen'),
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'Solicitante']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('Unidades Solicitante'),
            ])->save();
        }

        $role = Role::firstOrNew(['name' => 'Contrataciones']);
        if (!$role->exists) {
            $role->fill([
                'display_name' => __('Contrataciones'),
            ])->save();
        }
    }
}

