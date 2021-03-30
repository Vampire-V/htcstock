<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $evaluateService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EvaluateServiceInterface $evaluateServiceInterface)
    {
        $this->middleware('guest')->except('logout');
        $this->evaluateService = $evaluateServiceInterface;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function username()
    {
        return 'username';
    }
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function login(Request $request)
    {
        $input = $request->except(['_token']);
        $this->validate($request, [
            'username'    => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = array($fieldType => $input['username'], 'password' => $input['password']);
        if (Auth::attempt($credentials)) {
            return \redirect()->route('welcome')->with('alert-success', 'You are now logged in.');
        }

        $errors = new MessageBag(['password' => ['Email and/or password invalid.'], 'username' => ['']]);
        return \redirect()->back()->withErrors($errors)->withInput($input);
    }

    public function authenticatedLegalById($id, $contract)
    {
        Auth::loginUsingId($id);
        return \redirect()->route('legal.contract-request.show', $contract);
    }

    public function authKpiEvaluation($id)
    {
        try {
            $evaluate = $this->evaluateService->find($id);
            Auth::loginUsingId($evaluate->user->id);
        } catch (\Throwable $th) {
            throw $th;
        }
        return \redirect()->route('kpi.self-evaluation.edit', $evaluate->id);
    }
}
