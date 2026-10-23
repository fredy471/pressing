<?php

namespace App\Enums;


enum OrderStatus: string
{
    case EN_ATTENTE = 'en_attente';
    case RECUE = 'recue';
    case EN_LAVAGE = 'en_lavage';
    case TERMINEE = 'terminee';
    case LIVREE = 'livree';
    case ANNULEE = 'annulee';

    /**
     * Libellé destiné à l'affichage.
     */
    public function label(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'En attente',
            self::RECUE => 'Reçue',
            self::EN_LAVAGE => 'En lavage',
            self::TERMINEE => 'Terminée',
            self::LIVREE => 'Livrée',
            self::ANNULEE => 'Annulée',
        };
    }

    /**
     * Statuts accessibles depuis le statut actuel.
     */
    public function nextStatuses(): array
    {
        return match ($this) {
            self::EN_ATTENTE => [
                self::RECUE,
                self::ANNULEE,
            ],
            self::RECUE => [
                self::EN_LAVAGE,
                self::ANNULEE,
            ],
            self::EN_LAVAGE => [
                self::TERMINEE,
            ],
            self::TERMINEE => [
                self::LIVREE,
            ],
            self::LIVREE, self::ANNULEE => [],
        };
    }

    /**
     * Vérifie si une transition est autorisée.
     */
    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->nextStatuses(), true);
    }

    /**
     * Libellé de l'action correspondante.
     */
    public function actionLabel(): string
    {
        return match ($this) {
            self::RECUE => 'Marquer comme reçue',
            self::EN_LAVAGE => 'Démarrer le lavage',
            self::TERMINEE => 'Marquer comme terminée',
            self::LIVREE => 'Marquer comme livrée',
            self::ANNULEE => 'Annuler',
            self::EN_ATTENTE => 'En attente',
        };
    }

    /**
     * Classes CSS du badge de statut.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'bg-muted/15 text-muted',
            self::RECUE => 'bg-accent2/10 text-accent2',
            self::EN_LAVAGE => 'bg-accent/10 text-accent',
            self::TERMINEE => 'bg-accent2/15 text-accent2',
            self::LIVREE => 'bg-accent2/20 text-accent2',
            self::ANNULEE => 'bg-danger/10 text-danger',
        };
    }
}
