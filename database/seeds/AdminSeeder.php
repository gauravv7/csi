<?php

use App\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();

        Admin::create(['email' => 'root@root.com', 'username' => 'root', 'password' => bcrypt('1234'), 'group_memberships' => 1]);
        Admin::create(['email' => 'admin@membership.com', 'username' => 'admin-membership', 'password' => bcrypt('1234'), 'group_memberships' => 10]);
    	Admin::create(['email' => 'user@membership.com', 'username' => 'user-membership', 'password' => bcrypt('1234'), 'group_memberships' => 12]);

    }
}
