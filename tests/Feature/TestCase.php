<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected ?User $superAdminUser = null;
    protected ?User $editorUser = null;

    protected function superAdmin(): User
    {
        if (null === $this->superAdminUser) {
            $this->superAdminUser = User::whereEmail('super-admin@example.com')->first();
        }

        return $this->superAdminUser;
    }

    protected function editor(): User
    {
        if (null === $this->editorUser) {
            $this->editorUser = User::whereEmail('editor@example.com')->first();
        }

        return $this->editorUser;
    }
}
