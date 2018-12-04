<?php

namespace App\Http\Controllers;

use App\UserLog;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

/**
 * Class AdminRolesController
 */
class AdminController extends Controller
{
    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function banUser(User $user)
    {
        $data = $request->validate([
            'reason'   => 'string',
        ]);

        //If user not found
        if (!$user) {
            $error = new \Exception('User not found');
            $error->getMessage();
        }

        //If user not admin
        if ($user->role == 1) {
            $error = new \Exception('Cannot ban an admin');
            $error->getMessage();
        }

            //Set their role and status to banned
            $user->role->(9)->status(0)->save();
       

        //If there was a reason passed in
        if (isset($data['reason'])) {

            UserLog::create([
                'user_id' => $user->id,
                'action' => 'banned',
                'reason' => $data['reason'],
            ]);
        } else {
            UserLog::create([
                'user_id' => $user->id,
                'action' => 'banned',
            ]);
        }

        //Go back with message
        return Redirect::back()->with('Message', 'User has been banned');
    }

}