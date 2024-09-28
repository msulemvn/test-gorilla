<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Application;
use App\Models\Student;
use App\Models\User;

class StudentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pendingApplications = Application::where('status', 'pending')->take(20)->get();
        foreach ($pendingApplications as $application) {
            $application->update(['status' => 'accepted']);
        }
        foreach ($pendingApplications as $application) {
            $student = Student::create([
                'name' => $application->name,
                'email' => $application->email,
                'phone' => $application->phone,
            ]);

            $user = User::create([
                'name' => $application->name,
                'email' => $application->email,
                'password' => Hash::make('password'), // Default password for students
            ]);

            // Assign student role
            $user->assignRole('student');
        }
    }
}
