<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class
MediaFileCast implements CastsAttributes
{
    /**
     * Cast the given value when retrieving.
     *
     * @param  mixed  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
        // Return stored path as-is (string|null)
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  mixed  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        // If null: delete old media and store null
        if (is_null($value)) {
            $this->deleteOldMedia($model, $key);
            return null;
        }

        // Livewire TemporaryUploadedFile
        if ($value instanceof TemporaryUploadedFile) {
            $this->deleteOldMedia($model, $key);
            return $value->storePublicly($this->getMediaPath($model, $key), $this->getMediaDisk());
        }

        // Standard UploadedFile
        if ($value instanceof UploadedFile) {
            $this->deleteOldMedia($model, $key);
            return $value->store($this->getMediaPath($model, $key), $this->getMediaDisk());
        }

        // Base64 data URI
        if (is_string($value) && str_starts_with($value, 'data:')) {
            $this->deleteOldMedia($model, $key);
            return $this->saveBase64Media($model, $key, $value);
        }

        // Existing path string -> keep as-is
        if (is_string($value)) {
            return $value;
        }

        // Any other unexpected type -> no change
        return $model->{$key};
    }

    protected function getMediaDisk(): string
    {
        // Keep consistent with HasMedia default
        return 'public';
    }

    protected function getMediaPath($model, string $key): string
    {
        $modelName = strtolower(class_basename($model));
        return "$modelName/$key";
    }

    protected function deleteOldMedia($model, string $key): void
    {
        $path = $model->getOriginal($key);
        if ($path && Storage::disk($this->getMediaDisk())->exists($path)) {
            Storage::disk($this->getMediaDisk())->delete($path);
        }
    }

    protected function saveBase64Media($model, string $key, string $imageData): ?string
    {
        if (!str_starts_with($imageData, 'data:')) {
            return null;
        }

        @[$mime, $data] = explode(';', $imageData);
        @[, $data] = explode(',', $data);

        $decodedData = base64_decode($data);
        if ($decodedData === false) {
            return null;
        }

        $ext = match ($mime) {
            'data:image/jpeg' => '.jpg',
            'data:image/png' => '.png',
            'data:image/gif' => '.gif',
            'data:application/pdf' => '.pdf',
            'data:application/msword', 'data:application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx',
            'data:application/vnd.ms-excel', 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '.xlsx',
            default => null,
        };

        if ($ext === null) {
            return null;
        }

        $fileName = uniqid() . $ext;
        $path = $this->getMediaPath($model, $key) . '/' . $fileName;

        Storage::disk($this->getMediaDisk())->put($path, $decodedData);

        return $path;
    }
}
