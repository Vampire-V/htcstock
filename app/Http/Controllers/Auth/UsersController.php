<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserPost;
use App\Models\User;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\DivisionServiceInterface;
use App\Services\IT\Interfaces\PositionServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    private $userService, $divisionsService, $departmentsService, $positionsService;
    public function __construct(UserServiceInterface $userServiceInterface, DivisionServiceInterface $divisionsServiceInterface, DepartmentServiceInterface $departmentsServiceInterface, PositionServiceInterface $positionsServiceInterface)
    {
        $this->userService = $userServiceInterface;
        $this->divisionsService = $divisionsServiceInterface;
        $this->departmentsService = $departmentsServiceInterface;
        $this->positionsService = $positionsServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            $user = $this->userService->find($id);
            $divisions = $this->divisionsService->dropdown();
            $departments = $this->departmentsService->dropdown();
            $positions = $this->positionsService->dropdown();
            $users = $this->userService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('me.index', \compact('user', 'divisions', 'departments', 'positions', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUserPost $request, $id)
    {
        DB::beginTransaction();
        try {
            $profile = User::find($id);
            $profile->translateOrNew('th')->name = $request['name:th'];
            $profile->translateOrNew('en')->name = $request['name:en'];
            $profile->phone = $request->phone;
            $profile->head_id = $request->head_id;
            $profile->divisions_id = $request->division;
            $profile->department_id = $request->department;
            $profile->positions_id = $request->position;
            $profile->save();
            $request->session()->flash('success', $request['name:th'] .' ('.$request['name:en'].') user has been update');
        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', 'error flash message!');
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
