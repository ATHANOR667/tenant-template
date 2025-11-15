<?php

namespace Modules\AdminBase\Services;

use App\Models\Ban;
use App\Models\BanLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use InvalidArgumentException;
use Modules\AdminBase\Models\System;
use Twilio\Exceptions\TwilioException;

class BanService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Bannir un utilisateur.
     */
    public function ban(
        string $motif,
        Authenticatable|Model $user,
        BanLevel $banLevel,
        Authenticatable|Model|null $bannedBy = null,
        bool $isSystem = false
    ): Ban {
        if ($bannedBy && $isSystem) {
            throw new InvalidArgumentException('Vous ne pouvez pas définir à la fois $bannedBy et $isSystem.');
        }

        $durationDays = $banLevel->duration_days;
        $expiresAt = $durationDays ? now()->addDays($durationDays) : null;

        if ($isSystem) {
            $bannedBy = System::firstOrFail();
        }

        $ban = Ban::createFor(
            $user,
            [
                'motif' => $motif,
                'ban_level_id' => $banLevel->id,
                'expires_at' => $expiresAt,
            ],
            $bannedBy
        );

        $message = $this->buildMessage('ban', $ban, $banLevel, $bannedBy instanceof System);

        $this->notificationService->send(
            $user,
            'ban',
            $message,
            $banLevel->color
        );

        return $ban;
    }

    /**
     * Débannir un utilisateur.
     * @throws TwilioException
     */
    public function unban(
        Authenticatable|Model       $user,
        Authenticatable|Model|null  $unbannedBy = null,
        bool                        $isSystem   = false
    ): Ban {
        if ($unbannedBy && $isSystem) {
            throw new InvalidArgumentException('Vous ne pouvez pas définir à la fois $unbannedBy et $isSystem.');
        }

        if ($isSystem) {
            $unbannedBy = System::firstOrFail();
        }

        $ban = $user->bans()
            ->whereNull('deleted_at')
            ->latest()
            ->firstOrFail();

        $ban->liftBy($unbannedBy);

        $message = $this->buildMessage('unban', $ban, null, $unbannedBy instanceof System);

        $this->notificationService->send(
            $user,
            'unban',
            $message
        );

        return $ban;
    }

    /**
     * Construire le message de notification ban/unban.
     */
    protected function buildMessage(string $type, Ban $ban, ?BanLevel $banLevel = null, bool $isSystem = false): string
    {
        if ($type === 'ban') {
            $actor = $isSystem ? 'automatiquement (système)' : 'par un administrateur';
            $msg = "Votre compte a été banni {$actor}.\n";
            $msg .= "Motif : {$ban->motif}.\n";

            if ($banLevel) {
                $msg .= "Niveau de ban : {$banLevel->name}.\n";

                if ($banLevel->duration_days) {
                    $msg .= "Durée : {$banLevel->duration_days} jours.\n";
                }
            }

            if ($ban->expires_at) {
                $msg .= "Expiration : {$ban->expires_at->format('d/m/Y H:i')}.\n";
            }

            $msg .= "Veuillez contacter l’assistance si vous souhaitez plus d’informations.";
            return $msg;
        }

        if ($type === 'unban') {
            $actor = $isSystem ? 'automatiquement (expiration du délai)' : 'par un administrateur';
            $msg = "Votre compte a été débanni {$actor}.\n";
            $msg .= "Motif initial du ban : {$ban->motif}.\n";

            if ($ban->ban_level_id) {
                $banLevel = $banLevel ?? BanLevel::find($ban->ban_level_id);
                if ($banLevel) {
                    $msg .= "Niveau de ban initial : {$banLevel->name}.\n";
                }
            }

            $msg .= "Veuillez contacter l’assistance pour toute question.";
            return $msg;
        }

        return '';
    }




    /**
     * Historique des bans et unbans.
     */
    public function history(
        ?Authenticatable $user = null,
        ?Authenticatable $by = null,
        bool $ban = true,
        bool $unban = true
    ): array {
        $query = Ban::withTrashed()->with(['bannable', 'bannedBy', 'unbannedBy']);

        if ($user) {
            $query->where('bannable_id', $user->id)
                ->where('bannable_type', $user->getMorphClass());
        }

        if ($by) {
            $query->where(function ($q) use ($by) {
                $q->where('banned_by_id', $by->id)
                    ->where('banned_by_type', $by->getMorphClass())
                    ->orWhere(function ($q2) use ($by) {
                        $q2->where('unbanned_by_id', $by->id)
                            ->where('unbanned_by_type', $by->getMorphClass());
                    });
            });
        }

        $records = $query->get();

        $history = collect();

        foreach ($records as $record) {
            if ($ban) {
                $history->push([
                    'id' => $record->id,
                    'motif' => $record->motif,
                    'operation' => 'ban',
                    'date' => $record->created_at,
                    'user' => $record->bannable,
                    'by' => $record->bannedBy ,
                ]);
            }

            if ($unban && $record->trashed()) {
                $history->push([
                    'id' => $record->id,
                    'motif' => $record->motif,
                    'operation' => 'unban',
                    'date' => $record->deleted_at,
                    'user' => $record->bannable,
                    'by' => $record->unbannedBy ,
                ]);
            }
        }

        return ['history' => $history->sortByDesc('date')->values()->toArray()];
    }

    /**
     * Débannir automatiquement les bans expirés.
     */
    public function unbanExpired(): void
    {
        $expiredBans = Ban::whereNull('deleted_at')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredBans as $ban) {
            $ban->liftBy(System::firstOrFail());
        }
    }
}
