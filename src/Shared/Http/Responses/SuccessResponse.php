<?php declare(strict_types=1);

namespace App\Shared\Http\Responses;

final class SuccessResponse extends JsonResponse
{
    public static function create(array $data = [], int $status = 200): self
    {
        return parent::create([
            "status" => "success",
            "data"   => $data,
        ], $status);
    }
}
