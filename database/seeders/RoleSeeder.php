<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'customer'],
            ['id' => 2,'name' => 'admin'] 
        ];

        foreach ($roles as $data){
            $role = new Role();
            $role->id = $data['id'];
            $role->name = $data['name'];
            $role->save();
        }
    }
}
