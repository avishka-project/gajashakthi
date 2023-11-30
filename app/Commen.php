<?php

namespace App;


use Illuminate\Support\Facades\Auth;

class Commen  
{
    public function Allpermission()
    {
         // Retrieve the user instance
         $user = Auth::user();
         // Get all permissions for the user
         $permissions = $user->getAllPermissions()->pluck('name')->toArray();
         $userPermissions = [];
         
         foreach ($permissions as $permission) {
             // Store permission name or details in an array
             $userPermissions[] = $permission;
         }

         return $userPermissions;
         
    }
}
