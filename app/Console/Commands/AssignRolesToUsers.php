<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Company;
use App\Models\Admin;

class AssignRolesToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically assign roles to users based on the role field in the users table';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Fetch all users
        $users = User::all();

        foreach ($users as $user) {
            // Check the role of each user and insert them into the respective table
            switch (strtolower($user->role->value)) {
                case 'student':
                    $this->assignToStudent($user);
                    break;
                case 'teacher':
                    $this->assignToTeacher($user);
                    break;
                case 'company':
                    $this->assignToCompany($user);
                    break;
                case 'admin':
                    $this->assignToAdmin($user);
                    break;
                default:
                    $this->info("Role not recognized for user: " . $user->id);
                    break;
            }
        }

        $this->info('Roles assigned successfully!');
    }

    /**
     * Assign user to the Student table.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function assignToStudent(User $user)
    {
        // Check if the student record already exists, if not create a new one
        if (!Student::where('user_id', $user->id)->exists()) {
            Student::create(['user_id' => $user->id]);
            $this->info("User ID {$user->id} assigned as student.");
        }
    }

    /**
     * Assign user to the Teacher table.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function assignToTeacher(User $user)
    {
        // Check if the teacher record already exists, if not create a new one
        if (!Teacher::where('user_id', $user->id)->exists()) {
            Teacher::create(['user_id' => $user->id]);
            $this->info("User ID {$user->id} assigned as teacher.");
        }
    }

    /**
     * Assign user to the Company table.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function assignToCompany(User $user)
    {
        // Check if the company record already exists, if not create a new one
        if (!Company::where('user_id', $user->id)->exists()) {
            Company::create(['user_id' => $user->id]);
            $this->info("User ID {$user->id} assigned as company.");
        }
    }

    /**
     * Assign user to the Admin table.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function assignToAdmin(User $user)
    {
        // Check if the admin record already exists, if not create a new one
        if (!Admin::where('user_id', $user->id)->exists()) {
            Admin::create(['user_id' => $user->id]);
            $this->info("User ID {$user->id} assigned as admin.");
        }
    }
}
