<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatingProjectWithExistingNameFails()
    {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson('/projects/add', ['name' => $project->name]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCannotUpdateProjectToNameOtherProjectHas()
    {
        $project = factory(Project::class)->create();
        $otherProject = factory(Project::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson(
            '/projects/update',
            [
                'id' => $project->id,
                'name' => $otherProject->name
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCanUpdateProjectToKeepSameName()
    {
        $project = factory(Project::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson(
            '/projects/update',
            [
                'id' => $project->id,
                'name' => $project->name
            ]
        );
        $response->assertStatus(Response::HTTP_OK);
    }
}
