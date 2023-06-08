<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;

    public function setUp(): void
    {
        parent::setUp();

        $this->endpoint = 'api/categories';
        Sanctum::actingAs(
            User::factory()->create(),
            ['view-tasks']
        );
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
        Storage::fake('public');

        $category = Category::factory()->make()->toArray();

        //TODO fix issue with intervention package
        $category['image'] = null;

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

        //TODO fix issue with intervention package
        $category['image'] = null;

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
        $categoryData = Category::factory()->make()->toArray();
        $categoryData['image'] = null;

        //TODO fix issue with intervention package

        $response = $this->json('post', "{$this->endpoint}/{$category->id}", $categoryData)
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
        $categoryOther = Category::factory()->create();
        $categoryData = Category::factory()->make(['name' => 'testName'])->toArray();

        $categoryData['image'] = null;

        $this->json('put', "{$this->endpoint}/{$categoryOther->id}", $categoryData)
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

        $response = $this->json('delete', "{$this->endpoint}/{$category->id}")->dump()
            ->assertNoContent();

        $this->assertModelMissing($category);
    }
}
