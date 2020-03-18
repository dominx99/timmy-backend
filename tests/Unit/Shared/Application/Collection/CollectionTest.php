<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Application\Collection;

use Tests\BaseTestCase;

final class CollectionTest extends BaseTestCase
{
    /** @test */
    public function that_created_collection_from_array_is_valid()
    {
        $expectedArray = [
            "name" => "Work",
            "role" => "engineer",
        ];

        $collection = new DummyCollection($expectedArray);

        $this->assertSame($expectedArray, $collection->toArray());
    }

    /** @test */
    public function that_push_method_adds_item_to_collection()
    {
        $collection = new DummyCollection();

        $this->assertCount(0, $collection->toArray());

        $collection->push(1);
        $this->assertCount(1, $collection->toArray());

        $collection->push("string");
        $this->assertCount(2, $collection->toArray());

        $collection->push(["foo" => "bar"]);
        $this->assertCount(3, $collection->toArray());
    }

    /** @test */
    public function that_empty_method_works()
    {
        $emptyCollection = new DummyCollection([]);
        $notEmptyCollection = new DummyCollection([1, 2, 3]);

        $this->assertTrue($emptyCollection->empty());
        $this->assertFalse($notEmptyCollection->empty());
    }

    /** @test */
    public function that_map_method_works()
    {
        $items = [
            [
                "status" => "completed",
            ],
            [
                "status" => "new",
            ]
        ];

        $tasks = new DummyCollection($items);

        $expectedTasks = $items;
        $expectedTasks[0]["delivery"] = "free";

        $newTasks = $tasks->map(function ($task) {
            if ($task["status"] === "completed") {
                return array_merge($task, ["delivery" => "free"]);
            }

            return $task;
        });

        $this->assertSame($expectedTasks, $newTasks->toArray());
    }

    /** @test */
    public function that_count_method_works()
    {
        $collection = new DummyCollection(["banana", "apple", "strawberry"]);
        $this->assertSame(3, $collection->count());

        $collection = new DummyCollection();
        $this->assertSame(0, $collection->count());
    }

    /** @test */
    public function that_find_method_works()
    {
        $collection = new DummyCollection(["banana", "apple", "orange"]);

        $item = $collection->find(fn(string $item) => $item === "orange");

        $this->assertSame("orange", $item);

        $collection = new DummyCollection([
            [
                "name" => "David",
                "age"  => 13,
            ],
            [
                "name" => "Coronavirus",
                "age"  => 1,
            ],
            [
                "name" => "Dominic",
                "age"  => 20,
            ]
        ]);

        $item = $collection->find(fn($item) => $item["name"] === "David");

        $this->assertSame([
            "name" => "David",
            "age"  => 13,
        ], $item);

        $collection = new DummyCollection();
        $item = $collection->find(fn($item) => $item["a"] === "b");
        $this->assertSame(null, $item);
    }
}
