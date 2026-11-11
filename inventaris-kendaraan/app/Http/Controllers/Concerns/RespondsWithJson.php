<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

trait RespondsWithJson
{
    protected function ok(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data ?? new \stdClass(),
        ], $status);
    }

    protected function fail(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        $body = ['success' => false, 'message' => $message];
        if ($errors) {
            $body['errors'] = $errors;
        }

        return response()->json($body, $status);
    }

    /**
     * Jalankan validasi; kembalikan [data, null] jika lolos atau [null, JsonResponse] jika gagal.
     */
    protected function validateInput(Request $request, array $rules, array $messages = []): array
    {
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return [null, $this->fail('Data tidak valid. Periksa kembali isian Anda.', 422, $validator->errors()->toArray())];
        }

        return [$validator->validated(), null];
    }

    /**
     * Response server-side untuk DataTables.
     *
     * @param  array<int,string>  $orderColumns  daftar kolom (index = urutan kolom DataTables), null = tidak bisa diurutkan
     */
    protected function dataTableResponse(Request $request, Builder $query, int $recordsTotal, array $orderColumns, string $defaultOrder, string $defaultDir = 'desc'): JsonResponse
    {
        $recordsFiltered = (clone $query)->count();

        $orderIndex = (int) $request->input('order.0.column', -1);
        $orderDir = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $orderBy = $orderColumns[$orderIndex] ?? null;
        if ($orderBy) {
            $query->orderBy($orderBy, $orderDir);
        } else {
            $query->orderBy($defaultOrder, $defaultDir);
        }

        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $length = $length < 1 ? 10 : min($length, 100);

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $query->offset($start)->limit($length)->get(),
        ]);
    }

    protected function serverError(Throwable $e, string $message): JsonResponse
    {
        Log::error($message, ['exception' => $e->getMessage()]);

        return $this->fail(
            config('app.debug') ? $message.' ('.$e->getMessage().')' : $message,
            500
        );
    }

    protected function escapeLike(string $value): string
    {
        return addcslashes($value, '\\%_');
    }
}
