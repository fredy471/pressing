<?php
namespace App\Enums;

enum PaymentStatus: string
{
    case EN_ATTENTE = 'en_attente';
    case EN_COURS = 'en_cours';
    case REUSSI = 'reussi';
    case ECHOUE = 'echoue';
    case ANNULE = 'annule';

    /**
     * Libellé français pour l'affichage.
     */
    public function label(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'En attente',
            self::EN_COURS => 'En cours',
            self::REUSSI => 'Réussi',
            self::ECHOUE => 'Échoué',
            self::ANNULE => 'Annulé',
        };
    }

    /**
     * Classes CSS du badge.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'bg-muted/15 text-muted',
            self::EN_COURS => 'bg-accent/10 text-accent',
            self::REUSSI => 'bg-accent2/10 text-accent2',
            self::ECHOUE => 'bg-danger/10 text-danger',
            self::ANNULE => 'bg-muted/15 text-muted',
        };
    }
}
