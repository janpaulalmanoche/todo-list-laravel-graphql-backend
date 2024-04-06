<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

// php artisan test tests/Feature/TodolistTest.php
class TodolistTest extends TestCase
{
    use MakesGraphQLRequests;
    use RefreshesSchemaCache;
    use RefreshDatabase;

    /** @test */
    public function test_fetch_tasks(): void
    {
        $data = [
            'task' => 'walk the dog'
        ];

        $task = Task::factory()->create($data);

        $this->graphQL(
            /** @lang GraphQL */
            '
            {
                tasks {
                    task
                }
            }
            '
        )->assertJson([
            'data' => [
                'tasks' => [
                    [
                        'task' => $task->task,
                    ],
                ],
            ],
        ])->dump();
    }

    /** @test */
    public function test_create_task(): void
    {

        $this->graphQL(
            /** @lang GraphQL */
            '
            mutation ($task: String!) {
                createTask(task: $task) {
                   task
                }
            }
        ',
            [
                'task' => "Testing Creating a task"
            ]
        )->assertStatus(200)->dump();


        $this->assertDatabaseHas('tasks', [
            'task' => "Testing Creating a task"
        ]);
    }

    /** @test */
    public function test_update_tasks(): void
    {

        $task1 = Task::factory()->create();
        $task2 = Task::factory()->create();

        $this->graphQL(
            /** @lang GraphQL */
            '
                 mutation($ids: [ID!]!) {
                     updateTasks(ids: $ids) {
                         id
                         task
                         status
                     }
                 }
             ',
            [
                'ids' => [$task1->id, $task2->id],
            ]
        )->assertStatus(200)->dump();


        $this->assertDatabaseHas('tasks', [
            'task' => $task1->task,
            "status" => true,
            'id' => $task1->id
        ]);
        $this->assertDatabaseHas('tasks', [
            'task' => $task2->task,
            "status" => true,
            'id' => $task2->id
        ]);
    }


      /** @test */
      public function test_delete_tasks(): void
      {
  
          $task1 = Task::factory()->create();
          $task2 = Task::factory()->create();
  
          $this->graphQL(
              /** @lang GraphQL */
              '
                   mutation ($ids: [ID!]!) {
                    deleteTasks(ids: $ids) {
                           id
                           task
                           status
                       }
                   }
               ',
              [
                  'ids' => [$task1->id, $task2->id],
              ]
          )->assertStatus(200)->dump();
  
  
          $this->assertDatabaseMissing('tasks', [
              'task' => $task1->task,
              "status" => true,
              'id' => $task1->id
          ]);
          $this->assertDatabaseMissing('tasks', [
              'task' => $task2->task,
              "status" => true,
              'id' => $task2->id
          ]);
      }
}
