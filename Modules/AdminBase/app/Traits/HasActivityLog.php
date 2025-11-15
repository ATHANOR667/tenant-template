<?php

namespace Modules\AdminBase\Traits;

use Illuminate\Database\Eloquent\Model;
use Modules\AdminBase\Models\ModelActivityLog;
use Modules\AdminBase\Services\ActivityLogService;

trait HasActivityLog
{
    protected static function bootHasActivityLog(): void
    {
        static::updated(function (Model $model) {
            // Récupérer les attributs modifiés
            $dirty = $model->getDirty();

            // Ignorer si seul updated_at a changé
            if (array_key_exists('updated_at', $dirty) && count($dirty) === 1) {
                return;
            }

            // Enregistrer le changement si d'autres champs ont été modifiés
            (new ActivityLogService())->logModelChange($model, 'updated');
        });

        static::created(function (Model $model) {
            (new ActivityLogService())->logModelChange($model, 'created');
        });

        static::deleted(function (Model $model) {
            (new ActivityLogService())->logModelChange($model, 'deleted');
        });

        static::restored(function (Model $model) {
            (new ActivityLogService())->logModelChange($model, 'restored');
        });
    }

    /**
     * Relation brute avec les logs
     */
    protected function getActivityLog()
    {
        return $this->morphMany(ModelActivityLog::class, 'logable');
    }

    /**
     * Historique versionné (avec options de filtrage par type d'opération)
     * Reconstruction inverse basée sur l'état actuel et les logs
     */
    public function getActivityVersions(
        int $limit = 0,
        bool $withCreations = true,
        bool $withUpdates = true,
        bool $withDeletions = true,
        bool $withRestorations = true
    ): array
    {
        $query = $this->getActivityLog()
            ->with('changed_by')
            ->orderBy('created_at', 'desc');

        // Types d'opérations à inclure
        $types = [];
        if ($withCreations) {
            $types[] = 'created';
        }
        if ($withUpdates) {
            $types[] = 'updated';
        }
        if ($withDeletions) {
            $types[] = 'deleted';
        }
        if ($withRestorations) {
            $types[] = 'restored';
        }

        if (!empty($types)) {
            $query->whereIn('operation', $types);
        }

        if ($limit > 0) {
            $query->limit($limit);
        }


        $versions = [];

        $currentState = $this->getAttributes();
        $previousState = $this->getAttributes();

        foreach ($query->get() as $log) {

            $changes = $log['changes'];

            if ($changes !== null) {
                foreach ($changes as $field => $change) {
                    $operation = $log->operation;

                    switch ($operation) {
                        case 'updated':
                            $currentState[$field] = $change['new'];
                            $previousState[$field] = $change['old'];
                            break;

                        case 'restored':
                        case 'deleted':
                            $currentState['deleted_at'] = $change['new'];
                            $previousState[$field] = $change['old'];
                            break;

                        case 'created':
                            $currentState['updated_at'] = null;
                            break;
                    }
                }
            }

            $versions[] = [
                'operation' => $log['operation'],
                'changed_fields' => $changes,
                'state' => $currentState,
                'by' => $log['changed_by'],
                'date' => $log['created_at'],
            ];


            $currentState = $previousState;
        }

        return $versions;
    }

}
