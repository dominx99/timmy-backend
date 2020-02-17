<?php declare(strict_types=1);

namespace Tests\Unit\Shared\Http\Responses;

use Tests\BaseTestCase;
use App\Shared\Http\Responses\JsonResponse;

final class JsonResponseTest extends BaseTestCase
{
    /** @test */
    public function that_json_response_creates_with_valid_status_code()
    {
        $response = JsonResponse::create();

        $this->assertSame(200, $response->getStatusCode());

        $response = JsonResponse::create([], 400);

        $this->assertSame(400, $response->getStatusCode());
    }

    /** @test */
    public function that_json_response_has_valid_data()
    {
        $response = JsonResponse::create(["foo" => "bar", "test" => ["test1" => "test2"]]);

        $expectedData = json_encode(["foo" => "bar", "test" => ["test1" => "test2"]]);

        $this->assertSame($expectedData, (string) $response->getBody());
    }
}
