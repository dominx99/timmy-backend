<?php declare(strict_types=1);

namespace App\Shared\Http\Responses;

use Slim\Psr7\Response;

class JsonResponse extends Response
{
    public static function create(array $data = [], int $status = 200): self
    {
        $response = new static($status);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
