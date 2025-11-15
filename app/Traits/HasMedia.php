<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Database\Eloquent\Model;

trait HasMedia
{
    /**
     * Boot the trait to add our observer.
     *
     * @return void
     */
    protected static function bootHasMedia(): void
    {
        // Supprime les fichiers associés lorsque le modèle est supprimé.
        static::deleting(function (Model $model) {
            foreach ($model->getMediaFields() as $field) {
                $path = $model->getOriginal($field);
                if ($path && Storage::disk($model->getMediaDisk())->exists($path)) {
                    return;
                    //Storage::disk($model->getMediaDisk())->delete($path);
                }
            }
        });
    }

    /**
     * Get the fields that contain media.
     *
     * @return array
     */
    abstract protected function getMediaFields(): array;

    /**
     * Handle the saving of media attributes.
     * This method is called by the `mutator` for each media field.
     *
     * @param mixed $value
     * @param string $field
     * @return string|null
     */
    protected function handleMediaUpload($value, string $field): ?string
    {
        // Si la valeur est null, on supprime l'ancien fichier.
        if (is_null($value)) {
            //$this->deleteOldMedia($field);
            return null;
        }

        // Si la valeur est un fichier Livewire.
        if ($value instanceof TemporaryUploadedFile) {
            $this->deleteOldMedia($field);
            return $value->storePublicly($this->getMediaPath($field), $this->getMediaDisk());
        }

        // Si la valeur est un fichier UploadedFile.
        if ($value instanceof UploadedFile) {
            $this->deleteOldMedia($field);
            return $value->store($this->getMediaPath($field), $this->getMediaDisk());
        }

        // Si la valeur est une chaîne de caractères (potentiellement un encodage Base64).
        if (is_string($value) && str_starts_with($value, 'data:')) {
            $this->deleteOldMedia($field);
            return $this->saveBase64Media($field, $value);
        }

        // Si la valeur est déjà une chaîne de caractères (chemin de fichier),
        // nous supposons qu'il s'agit d'un chemin de fichier existant et ne faisons rien.
        if (is_string($value)) {
            return $value;
        }

        // Pour tout autre type inattendu (objet non gérable), on retourne null pour ne pas modifier l'attribut
        // du modèle et éviter l'erreur de conversion.
        return null;
    }

    /**
     * Save a Base64 encoded string as a file.
     *
     * @param string $field
     * @param string $imageData
     * @return string|null
     */
    protected function saveBase64Media(string $field, string $imageData): ?string
    {
        if (!str_starts_with($imageData, 'data:')) {
            return null;
        }

        @list($mime, $data) = explode(';', $imageData);
        @list(, $data) = explode(',', $data);

        $decodedData = base64_decode($data);

        if ($decodedData === false) {
            return null;
        }

        // On détermine l'extension du fichier à partir du MIME type.
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
        $path = $this->getMediaPath($field) . '/' . $fileName;

        Storage::disk($this->getMediaDisk())->put($path, $decodedData);

        return $path;
    }

    /**
     * Delete the old media file for a given field.
     *
     * @param string $field
     * @return void
     */
    protected function deleteOldMedia(string $field): void
    {
        $path = $this->getOriginal($field);

        if ($path && Storage::disk($this->getMediaDisk())->exists($path)) {
            Storage::disk($this->getMediaDisk())->delete($path);
        }
    }

    /**
     * Get the directory to store the media in.
     *
     * @param string $field
     * @return string
     */
    protected function getMediaPath(string $field): string
    {
        $modelName = strtolower(class_basename($this));
        return "{$modelName}/{$field}";
    }

    /**
     * Get the disk to store the media on.
     *
     * @return string
     */
    protected function getMediaDisk(): string
    {
        return 'public';
    }

    /**
     * Initialize the HasMedia trait by registering casts for media fields.
     * This uses a dedicated CastsAttributes class to conform to Laravel's expectations.
     */
    protected function initializeHasMedia(): void
    {
        // Ensure we can merge into existing $casts property on the model
        foreach ($this->getMediaFields() as $field) {
            // If the model already defines a cast for this field, don't override it
            if (!isset($this->casts[$field])) {
                $this->casts[$field] = \App\Casts\MediaFileCast::class;
            }
        }
    }
}
