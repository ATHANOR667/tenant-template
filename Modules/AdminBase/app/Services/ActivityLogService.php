<?php

namespace Modules\AdminBase\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Modules\AdminBase\Models\ModelActivityLog;
use Modules\AdminBase\Models\System;

class ActivityLogService
{
    /**
     * Tente de déterminer l'utilisateur actuellement authentifié.
     */
    protected function getModifier(): Authenticatable|Model
    {
        foreach (config('auth.guards') as $guardName => $guard) {
            if (Auth::guard($guardName)->check()) {
                return Auth::guard($guardName)->user();
            }
        }
        // Retourne le modèle Système si aucun utilisateur n'est authentifié
        return System::query()->first();
    }

    /**
     * Enregistre les opérations effectuées sur un Models.
     */
    public function logModelChange(Model $model, string $operation): void
    {
        if ($model instanceof ModelActivityLog) {
            return;
        }

        $modifier = $this->getModifier();
        $logData = [
            'logable_id'      => $model->getKey(),
            'logable_type'    => $model->getMorphClass(),
            'changed_by_id'   => $modifier->getKey(),
            'changed_by_type' => $modifier->getMorphClass(),
            'operation'       => $operation,
            'changes'         => null, // Le champ JSON qui stocke les détails
        ];

        if ($operation === 'updated') {
            // Avant la sauvegarde (événement 'updating'), les nouvelles valeurs sont dans getDirty().
            // Après la sauvegarde (événement 'updated'), elles sont dans getChanges().
            // On privilégie getDirty(), avec repli sur getChanges().
            $dirty = $model->getDirty();
            $changes = !empty($dirty) ? $dirty : $model->getChanges();

            $logChanges = [];

            foreach ($changes as $fieldName => $newValue) {

                // Exclure created_at et updated_at si c'est la seule modification
                if ($fieldName === 'created_at' || $fieldName === 'deleted_at'  || ($fieldName === 'updated_at' && count($changes) === 1)) {
                    continue;
                }

                /** @var  $changeKeys
                 * une restauration est en réalité un update des champs updated_at et deleted_at
                 * */
                $changeKeys = array_keys($changes);
                if ($changeKeys === ['updated_at', 'deleted_at'] || $changeKeys === ['deleted_at', 'updated_at']) {
                    $logData['operation'] = 'restored';
                }


                $oldValue = $model->getOriginal($fieldName);

                $logChanges[$fieldName] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }

            if (!empty($logChanges)) {
                $logData['changes'] = $logChanges;
                ModelActivityLog::create($logData);
            }

        } elseif ($operation === 'deleted') {
            // Log de la valeur de deleted_at pour la suppression
            $logData['changes'] = [
                'deleted_at' => [
                    'old' => null,
                    'new' => $model->{$model->getDeletedAtColumn()},
                ]
            ];
            ModelActivityLog::create($logData);

        } elseif ($operation === 'restored') {

            /** UNE RESTAURATION EST EN REALITE UN UPDATE PARTICULIER */
            // Log de la valeur de deleted_at pour la restauration
           /* $logData['changes'] = [
                'deleted_at' => [
                    'old' => $model->getOriginal($model->getDeletedAtColumn()),
                    'new' => null,
                ]
            ];
            ModelActivityLog::create($logData);*/

        } elseif ($operation === 'created') {
            // Pour 'created', on log l'opération et l'auteur. Le champ 'changes' reste null.
            ModelActivityLog::create($logData);
        }
    }
}
