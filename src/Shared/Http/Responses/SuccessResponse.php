<?php declare(strict_types=1);

namespace App\Shared\Http\Responses;

final class SuccessResponse
{
    /**
        @param string|array $data
        @param int $status
        @return self
     */
    public static function create($data = [], int $status = 200): JsonResponse
    {
        return JsonResponse::create([
            "status" => "success",
            "data"   => $data,
        ], $status);
    }
}
