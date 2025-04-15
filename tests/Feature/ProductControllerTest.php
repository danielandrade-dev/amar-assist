<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_displays_products()
    {
        // Arrange
        $products = Product::factory(3)->create();

        // Act
        $response = $this->get(route('products.index'));

        // Assert
        $response->assertInertia(fn (Assert $page) => $page
            ->component('products/index')
            ->has('products', 3)
        );
    }

    public function test_create_displays_form()
    {
        $response = $this->get(route('products.create'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('products/create')
        );
    }

    public function test_store_creates_new_product()
    {
        // Arrange
        $productData = [
            'name' => $this->faker->name,
            'description' => '<p>' . $this->faker->paragraph . '</p>',
            'price' => 100,
            'cost' => 80, // Garante que o preço é 20% maior que o custo
            'stock' => 10,
            'status' => true
        ];

        // Act
        $response = $this->post(route('products.store'), $productData);

        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => $productData['name'],
            'stock' => $productData['stock']
        ]);
        $response->assertSessionHas('success', 'Produto criado com sucesso');
    }

    public function test_store_validates_product_price_above_cost()
    {
        // Arrange
        $productData = [
            'name' => $this->faker->name,
            'description' => '<p>' . $this->faker->paragraph . '</p>',
            'price' => 85, // Preço menor que custo + 10%
            'cost' => 80,
            'stock' => 10,
            'status' => true
        ];

        // Act
        $response = $this->post(route('products.store'), $productData);

        // Assert
        $response->assertSessionHasErrors('price');
    }

    public function test_edit_displays_form_with_product()
    {
        // Arrange
        $product = Product::factory()->create();

        // Act
        $response = $this->get(route('products.edit', $product));

        // Assert
        $response->assertInertia(fn (Assert $page) => $page
            ->component('products/edit')
            ->has('product')
            ->where('product.id', $product->id)
        );
    }

    public function test_update_modifies_product()
    {
        // Arrange
        $product = Product::factory()->create();
        $updatedData = [
            'name' => 'Produto Atualizado',
            'description' => '<p>Nova descrição</p>',
            'price' => 120,
            'cost' => 100,
            'stock' => 15,
            'status' => true
        ];

        // Act
        $response = $this->put(route('products.update', $product), $updatedData);

        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Produto Atualizado'
        ]);
        $response->assertSessionHas('success', 'Produto atualizado com sucesso');
    }

    public function test_destroy_removes_product()
    {
        // Arrange
        $product = Product::factory()->create();

        // Act
        $response = $this->delete(route('products.destroy', $product));

        // Assert
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $response->assertSessionHas('success', 'Produto deletado com sucesso');
    }

    public function test_store_validates_required_fields()
    {
        // Act
        $response = $this->post(route('products.store'), []);

        // Assert
        $response->assertSessionHasErrors(['name', 'description', 'price', 'cost', 'stock', 'status']);
    }

    public function test_store_validates_html_tags_in_description()
    {
        // Arrange
        $productData = [
            'name' => $this->faker->name,
            'description' => '<script>alert("teste")</script>', // Tag não permitida
            'price' => 100,
            'cost' => 80,
            'stock' => 10,
            'status' => true
        ];

        // Act
        $response = $this->post(route('products.store'), $productData);

        // Assert
        $response->assertSessionHasErrors('description');
    }
} 