<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;

    public function setUp(): void
    {
        parent::setUp();

        $this->endpoint = 'api/categories';

    }

    /**
     * @test
     */
    public function index(): void
    {
        Category::factory()->count(3)->create();

        $this->json('get', $this->endpoint)
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name', 'description', 'created_at', 'updated_at']
                ]
            ]);
    }

    /**
     * @test
     */
    public function store(): void
    {
        $category = Category::factory()->make()->toArray();

        $response = $this->json('post', $this->endpoint, $category)
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id', 'name', 'description', 'created_at', 'updated_at'
                ]
            ]);

        $this->assertModelExists(Category::find($response->getOriginalContent()['id']));
    }

    /**
     * @test
     */
    public function store_throws_category_name_exception_already_exists(): void
    {
        Category::factory()->create(['name' => 'testName'])->toArray();
        $category = Category::factory()->make(['name' => 'testName'])->toArray();

        $this->json('post', $this->endpoint, $category)
            ->assertBadRequest()
            ->assertJson([
                'message' => 'Category name Already exists'
            ]);
    }

    /**
     * @test
     */
    public function show(): void
    {
        $category = Category::factory()->create();

        $this->json('get', "{$this->endpoint}/{$category->id}")
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonStructure([
                'data' => [
                    'id', 'name', 'description', 'created_at', 'updated_at'
                ]
            ]);
    }

    /**
     * @test
     */
    public function update(): void
    {
        $category = Category::factory()->create();
        $categoryData = Category::factory()->make();

        $response = $this->json('put', "{$this->endpoint}/{$category->id}", $categoryData->toArray())
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id', 'name', 'description', 'created_at', 'updated_at'
                ]
            ]);


        $this->assertModelExists(Category::find($response->getOriginalContent()['id']));
    }

    /**
     * @test
     */
    public function update_throws_category_name_exception_already_exists(): void
    {
        $category = Category::factory()->create(['name' => 'testName']);
        $categoryData = Category::factory()->make(['name' => 'testName']);

        $this->json('put', "{$this->endpoint}/{$category->id}", $categoryData->toArray())
            ->assertBadRequest()
            ->assertJson([
                'message' => 'Category name Already exists'
            ]);
    }

    /**
     * @test
     */
    public function destroy()
    {
        $category = Category::factory()->create();

        $this->json('delete', "{$this->endpoint}/{$category->id}")
            ->assertNoContent();

        $this->assertModelMissing($category);
    }
}
