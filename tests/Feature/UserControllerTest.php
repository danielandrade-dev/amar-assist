<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_displays_users()
    {
        // Arrange
        $users = User::factory(3)->create();

        // Act
        $response = $this->get(route('users.index'));

        // Assert
        $response->assertInertia(fn (Assert $page) => $page
            ->component('users/index')
            ->has('users', 3)
        );
    }

    public function test_create_displays_form()
    {
        $response = $this->get(route('users.create'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('users/create')
        );
    }

    public function test_store_creates_new_user()
    {
        // Arrange
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        // Act
        $response = $this->post(route('users.store'), $userData);

        // Assert
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);
        $response->assertSessionHas('success', 'Usuário criado com sucesso');

        // Verifica se a senha foi hasheada
        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_store_validates_required_fields()
    {
        // Act
        $response = $this->post(route('users.store'), []);

        // Assert
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_store_validates_unique_email()
    {
        // Arrange
        $existingUser = User::factory()->create();
        $userData = [
            'name' => $this->faker->name,
            'email' => $existingUser->email,
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        // Act
        $response = $this->post(route('users.store'), $userData);

        // Assert
        $response->assertSessionHasErrors('email');
    }

    public function test_edit_displays_form_with_user()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->get(route('users.edit', $user));

        // Assert
        $response->assertInertia(fn (Assert $page) => $page
            ->component('users/edit')
            ->has('user')
            ->where('user.id', $user->id)
            ->where('user.name', $user->name)
            ->where('user.email', $user->email)
        );
    }

    public function test_update_modifies_user()
    {
        // Arrange
        $user = User::factory()->create();
        $updatedData = [
            'name' => 'Nome Atualizado',
            'email' => 'novo@email.com',
            'current_password' => 'password',
            'password' => 'novasenha123',
            'password_confirmation' => 'novasenha123'
        ];

        // Act
        $response = $this->put(route('users.update', $user), $updatedData);

        // Assert
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome Atualizado',
            'email' => 'novo@email.com'
        ]);
        $response->assertSessionHas('success', 'Usuário atualizado com sucesso');
    }

    public function test_update_validates_unique_email_except_self()
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $updatedData = [
            'name' => 'Nome Atualizado',
            'email' => $user2->email,
            'current_password' => 'password'
        ];

        // Act
        $response = $this->put(route('users.update', $user1), $updatedData);

        // Assert
        $response->assertSessionHasErrors('email');
    }

    public function test_destroy_removes_user()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->delete(route('users.destroy', $user));

        // Assert
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $response->assertSessionHas('success', 'Usuário deletado com sucesso');
    }

    public function test_cannot_delete_self()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user); // Simula usuário logado

        // Act
        $response = $this->delete(route('users.destroy', $user));

        // Assert
        $response->assertStatus(403); // Forbidden
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
} 