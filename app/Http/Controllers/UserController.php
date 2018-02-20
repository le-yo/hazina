<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Mail;
use Sentinel;
use App\Http\Requests;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Users\IlluminateUserRepository;

class UserController extends Controller
{
    /** @var Cartalyst\Sentinel\Users\IlluminateUserRepository */
    protected $userRepository;

    /** @var Centaur\AuthManager */
    protected $authManager;

    public function __construct()
    {
        // Middleware
        $this->middleware('auth');
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(15);

        return view('centaur.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        return view('centaur.users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the form data
        $result = $this->validate($request, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        // Assemble registration credentials and attributes
        $credentials = [
            'email' => trim($request->get('email')),
            'password' => $request->get('password'),
            'first_name' => $request->get('first_name', null),
            'last_name' => $request->get('last_name', null)
        ];
        $activate = (bool)$request->get('activate', false);


        $user = User::create([
            'name'     => $credentials['first_name']." ".$credentials['first_name'],
            'email'    => $credentials['email'],
            'password' => bcrypt($credentials['password']),
        ]);
        foreach ($request->get('roles', []) as $slug => $id) {
            $user
                ->roles()
                ->attach(Role::find($id));
        }

//        // Do we need to send an activation email?
//        if (!$activate) {
//            $code = $result->activation->getCode();
//            $email = $result->user->email;
//            Mail::queue(
//                'centaur.email.welcome',
//                ['code' => $code, 'email' => $email],
//                function ($message) use ($email) {
//                    $message->to($email)
//                        ->subject('Your account has been created');
//                }
//            );
//        }

//        $result->setMessage("User ".$user->email." has been created.");
        return redirect(url('users/index'))->withSuccess('"User ".$user->email." has been created."');
    }

    /**
     * Display the specified user.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // The user detail page has not been included for the sake of brevity.
        // Change this to point to the appropriate view for your project.
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Fetch the user object
        // $id = $this->decode($hash);
        $user = $this->userRepository->findById($id);

        // Fetch the available roles
        $roles = app()->make('sentinel.roles')->createModel()->all();

        if ($user) {
            return view('centaur.users.edit', [
                'user' => $user,
                'roles' => $roles
            ]);
        }

        session()->flash('error', 'Invalid user.');
        return redirect()->back();
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Decode the user id
        // $id = $this->decode($hash);

        // Validate the form data
        $result = $this->validate($request, [
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'password' => 'confirmed|min:6',
        ]);

        // Assemble the updated attributes
        $attributes = [
            'email' => trim($request->get('email')),
            'first_name' => $request->get('first_name', null),
            'last_name' => $request->get('last_name', null)
        ];

        // Do we need to update the password as well?
        if ($request->has('password')) {
            $attributes['password'] = $request->get('password');
        }

        // Fetch the user object
        $user = $this->userRepository->findById($id);
        if (!$user) {
            if ($request->ajax()) {
                return response()->json("Invalid user.", 422);
            }
            session()->flash('error', 'Invalid user.');
            return redirect()->back()->withInput();
        }

        // Update the user
        $user = $this->userRepository->update($user, $attributes);

        // Update role assignments
        $roleIds = array_values($request->get('roles', []));
        $user->roles()->sync($roleIds);

        // All done
        if ($request->ajax()) {
            return response()->json(['user' => $user], 200);
        }

        session()->flash('success', "{$user->email} has been updated.");
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  string  $hash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Fetch the user object
        //$id = $this->decode($hash);
        $user = $this->userRepository->findById($id);

        // Check to be sure user cannot delete himself
        if (Sentinel::getUser()->id == $user->id) {
            $message = "You cannot remove yourself!";

            if ($request->ajax()) {
                return response()->json($message, 422);
            }
            session()->flash('error', $message);
            return redirect()->route('users.index');
        }
        

        // Remove the user
        $user->delete();

        // All done
        $message = "{$user->email} has been removed.";
        if ($request->ajax()) {
            return response()->json([$message], 200);
        }

        session()->flash('success', $message);
        return redirect()->route('users.index');
    }

    /**
     * Decode a hashid
     * @param  string $hash
     * @return integer|null
     */
    // protected function decode($hash)
    // {
    //     $decoded = $this->hashids->decode($hash);

    //     if (!empty($decoded)) {
    //         return $decoded[0];
    //     } else {
    //         return null;
    //     }
    // }
}
