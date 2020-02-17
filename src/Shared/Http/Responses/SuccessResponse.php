<?php declare(strict_types=1);

namespace App\Shared\Http\Responses;

final class SuccessResponse extends JsonResponse
{
    public static function create(array $data = [], int $status = 200): self
    {
        $params = [
            "status" => "success",
        ];

        if (! empty($data)) {
            $params = array_merge($params, ["data" => $data]);
        }

        return parent::create($params, $status);
    }
}
