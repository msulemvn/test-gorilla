<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $studentRole = Role::create(['name' => 'student']);
        $supervisorRole = Role::create(['name' => 'supervisor']);

        $permissions = ['loginAccess', 'accessDashboard', 'assignManager', 'addStudent', 'manageQuizzes', 'attemptQuizzes', 'scheduleQuizzes', 'gradeQuizzes', 'assignQuiz', 'addStudents', 'managerManagers'];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole->givePermissionTo(['addStudent', 'assignManager']);
        $managerRole->givePermissionTo(['manageQuizzes', 'gradeQuizzes', 'assignQuiz', 'scheduleQuizzes']);
        $studentRole->givePermissionTo(['loginAccess', 'accessDashboard']);
        $supervisorRole->givePermissionTo(['manageQuizzes', 'gradeQuizzes', 'assignQuiz', 'scheduleQuizzes']);

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $manageruser = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
        ]);

        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $supervisorUser = User::create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password'),
        ]);

        $adminUser->assignRole('admin');
        $manageruser->assignRole('manager');
        $testUser->assignRole('student');
        $supervisorUser->assignRole('supervisor');
    }
}
