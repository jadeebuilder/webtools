<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'phone',
        'email',
        'password',
        'newsletter_subscribed',
        'profile_photo_path',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'newsletter_subscribed' => 'boolean',
        'is_admin' => 'boolean',
    ];

    /**
     * Get the user's full name.
     */
    public function getNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * Check if the user is an administrator.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Obtenir les abonnements de l'utilisateur.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Obtenir l'abonnement actif de l'utilisateur.
     *
     * @return Subscription|null
     */
    public function getActiveSubscription(): ?Subscription
    {
        return Subscription::getActiveSubscription($this->id);
    }

    /**
     * Vérifier si l'utilisateur a un abonnement actif.
     *
     * @return bool
     */
    public function hasActiveSubscription(): bool
    {
        return $this->getActiveSubscription() !== null;
    }

    /**
     * Obtenir le plan actif de l'utilisateur.
     *
     * @return Plan|null
     */
    public function getActivePlan(): ?Plan
    {
        $subscription = $this->getActiveSubscription();
        
        if ($subscription) {
            return $subscription->plan;
        }
        
        // Si l'utilisateur n'a pas d'abonnement, retourner le plan par défaut (gratuit)
        return Plan::getDefault();
    }

    /**
     * Vérifier si l'utilisateur a accès à un outil spécifique.
     *
     * @param Tool $tool
     * @return bool
     */
    public function canAccessTool(Tool $tool): bool
    {
        if (!$tool->is_active) {
            return false;
        }

        if (!$tool->is_premium) {
            return true;
        }

        $plan = $this->getActivePlan();
        
        if (!$plan) {
            return false;
        }

        return $tool->isAccessibleWithPlan($plan->id);
    }
}
