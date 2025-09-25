<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

    class ApiResponseResource extends JsonResource
    {
        public static $wrap = null;
        /**
         * Transform the resource into an array.
         *
         * @return array<string, mixed>
         */
        public function toArray(Request $request): array
        {
        $data = is_array($this->resource)
                ? $this->resource
                : $this->resource->toArray();

            return array_merge($data, [
                'response_date' => now()->toDateTimeString(),
            ]);
        }
    }