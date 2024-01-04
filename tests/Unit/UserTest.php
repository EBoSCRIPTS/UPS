<?php

namespace Tests\Unit;

use App\Models\UserModel;
use Tests\TestCase;


class UserTest extends TestCase
{
    public function test_superadmin_delete_safestop()
    {
        $admin= UserModel::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($admin)
            ->post('/user/delete', ['id' => $admin->id]);

        $admin->delete(); //get rid of fake user

        $response->assertRedirect('/mng/edit');
    }

    public function test_user_access_admin_panel()
    {
        $user = UserModel::factory()->create(['role_id' => 5]);

        $response = $this->actingAs($user)
            ->get('/mng/register');

        $user->delete();

        $response->assertRedirect('/');
    }

    public function user_nonemployee_update_banking()
    {
        $user = UserModel::factory()->create(['role_id' => 5]);

        $response = $this->actingAs($user)
            ->post('/profile/' . $user->id . '/update_banking', ['bank_account' => '123456789']);

        $user->delete();

        $response->assertRedirect('/profile/' . $user->id);
    }
}
