<?php declare(strict_types=1);

namespace Tests\Integration\Shared\Http\Responses;

use Tests\BaseTestCase;
use App\Shared\Http\Responses\JsonResponse;

final class JsonResponseTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->get("/success", function () {
            return JsonResponse::create(["data" => ["foo" => "bar"]]);
        });

        $this->app->get("/fail", function () {
            return JsonResponse::create(["error" => "Something went wrong"], 400);
        });
    }

    /** @test */
    public function that_returns_valid_response()
    {
        $request = $this->createRequest('GET', "/success");
        $response = $this->app->handle($request);

        $this->assertSame(200, $response->getStatusCode());

        $expectedData = ["data" => ["foo" => "bar"]];

        $this->assertSame($expectedData, json_decode((string) $response->getBody(), true));
    }

    public function that_returns_valid_error_response()
    {
        $request = $this->createRequest('GET', "/fail");
        $response = $this->app->handle($request);

        $this->assertSame(400, $response->getStatusCode());

        $expectedData = ["error" => "Something went wrong"];

        $this->assertSame($expectedData, json_decode((string) $response->getBody(), true));
    }
}
