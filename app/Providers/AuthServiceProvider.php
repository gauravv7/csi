<?php

namespace App\Providers;

use App\AvailableAction;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        $gate->define('view-all-memberships', function(){
            return true;
        });
        $gate->define('view-all-admins', function(){
            $admin = Auth::admin()->user();
            $table = 'admins';
            $user_id = $admin->id;
            $user_groups = $admin->group_memberships;
            $action = 'delete';
            $result = AvailableAction::getAllActionableObjects($table, $user_groups, $user_id, $action)->distinct()->get();
            dd($result);
            return $result;
        });
        $gate->define('is-user', function(){
            return (Auth::user()->user())? true: false;
        });
        $gate->define('is-user-payment-verified', function(){
            return Auth::user()->user()->checkMembershipPaymentValidity();
        });
        $gate->define('is-academic-institution', function(){
            $user = Auth::user()->user();
            $result = false;
            if($user){
                if($user->getMembership->membershipType->type == 'academic'){
                    $result = true;
                }
            } else{
                $result = true;
            }
            return $result;
        });
        $gate->define('is-institution', function(){
            $user = Auth::user()->user();
            $result = false;
            if($user){
                if($user->membership->type == 'institutional'){
                    $result = true;
                }
            } else{
                $result = true;
            }
            return $result;
        });
        $gate->define('is-individual', function(){
            $user = Auth::user()->user();
            $result = false;
            if($user){
                if($user->membership->type == 'individual'){
                    $result = true;
                }
            } else{
                $result = true;
            }
            return $result;
        });
    }
}
