<?php

namespace App\Http\Controllers\Admin;

use App\Enum\UserEnum;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Division;
use App\Models\GroupDivision;
use App\Models\Role;
use App\Models\System;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\RoleServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Services\IT\Interfaces\DivisionServiceInterface;
use App\Services\IT\Interfaces\PositionServiceInterface;
use App\Services\IT\Interfaces\SystemServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UsersController extends Controller
{
    protected $userService;
    protected $rolesService;
    protected $departmentService;
    protected $systemService;
    protected $divisionService;
    protected $positionService;
    public function __construct(
        UserServiceInterface $userServiceInterface,
        RoleServiceInterface $rolesServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        SystemServiceInterface $systemServiceInterface,
        DivisionServiceInterface $divisionServiceInterface,
        PositionServiceInterface $positionServiceInterface
    ) {
        $this->userService = $userServiceInterface;
        $this->rolesService = $rolesServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->systemService = $systemServiceInterface;
        $this->divisionService = $divisionServiceInterface;
        $this->positionService = $positionServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $search = $request->search;
        $selectedDivision = \collect($request->division);
        $selectedDept = \collect($request->department);
        $selectedPosition = \collect($request->position);
        $selectedRole = \collect($request->user_role);
        try {
            $users = $this->userService->filter($request);
            $divisions = $this->divisionService->dropdown();
            $departments = $this->departmentService->dropdown();
            $positions = $this->positionService->dropdown();
            $roles = $this->rolesService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('admin.users.index', \compact('users', 'divisions', 'departments', 'positions', 'roles', 'search', 'selectedDivision', 'selectedDept', 'selectedPosition', 'selectedRole', 'query'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if (Gate::denies('for-superadmin-admin')) {
                return \redirect()->route('admin.users.index');
            }
            $user = $this->userService->find($id);
            $userRoles = $user->roles()->get();
            $userSystems = $user->systems()->get();

            $roles = $this->rolesService->dropdown($user->roles->pluck('slug')->toArray());
            $systems = $this->systemService->dropdown($user->systems()->pluck('slug')->toArray());
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('admin.users.edit', \compact('user', 'roles', 'systems', 'userRoles', 'userSystems'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'roles' => 'required|nullable',
        ]);
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            if ($this->userService->update(['name' => $request->name], $id)) {
                $user->roles()->sync($request->roles);
                $request->session()->flash('success', $user->name . ' user has been update');
            } else {
                $request->session()->flash('error', 'error flash message!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // denies คือ !=
            // allows คือ ==
            // ตรวจสอบ Role Gate::denies(UserEnum::SUPERADMIN) จาก AuthServiceProvider ถ้าไม่ใช้ Admin 
            if (Gate::denies(UserEnum::SUPERADMIN)) {
                return \redirect()->route('admin.users.index');
            }
            if ($this->userService->delete($id)) {
                $request->session()->flash('success', ' has been delete');
            } else {
                $request->session()->flash('error', 'error flash message!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('admin.users.index');
    }

    public function updateusers(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Gate::denies('for-superadmin-admin')) {
                return back();
            }

            $response = Http::get(ENV('USERS_UPDATE'))->json();
            if (!\is_null($response)) {
                $list_users = [];
                foreach ($response as $value) {
                    $user = User::where('username', $value['username'])->first();
                    if (\is_null($user)) {
                        $user = new User;
                        $user->password = Hash::make(\substr($value['email'], 0, 1) . $value['username']);
                    }

                    $user->username = $value['username'];
                    $user->translateOrNew('th')->name = $value['name'];
                    $user->name_th = $value['name'];
                    $user->email = $value['email'];

                    $department = Department::where('process_id', $value['department_id'])->first();
                    
                    // if (!$user->department_id) {
                        if (\is_null($department)) {
                            $dept = new Department();
                            $dept->name = $value['department'];
                            $dept->process_id = $value['department_id'];
                            $dept->save();
                            $user->department_id = $dept->id;
                        } else {
                            $user->department_id = $department->id;
                        }
                    // }
                    $division = Division::where('division_id', $value['division_id'])->first();
                    // if (!$user->divisions_id) {
                        if (\is_null($division)) {
                            $div = new Division();
                            $div->name = $value['division'];
                            $div->division_id = $value['division_id'];
                            $group = GroupDivision::where('GDivisionID', $value['division_group_id'])->first();
                            if (\is_null($group)) {
                                $group = new GroupDivision();
                                $group->GDivisionDesc = $value['division_group'];
                                $group->GDivisionID = $value['division_group_id'];
                                $group->save();
                            }
                            $div->group_division_id = $group->GDivisionID;
                            $div->save();
                            $user->divisions_id = $div->id;
                        } else {
                            $user->divisions_id = $division->id;
                        }
                    // }
                    $user->head_id = $user->head_id ?? $value['leader'];
                    $user->save();
                    $list_users[] = $value['username'];
                }
                User::whereNotIn('username', [...$list_users])->update(['resigned' => 1]); //update user ที่ออกไปแล้ว
                $all_user = User::where('resigned', false)->get();
                $systems = System::whereNotIn('slug', ['legal','it'])->get();
                $roles = Role::whereNotIn('slug', [UserEnum::SUPERADMIN, UserEnum::ADMINIT, UserEnum::ADMINLEGAL, UserEnum::USERLEGAL, UserEnum::ADMINKPI])->get();

                foreach ($all_user as $staff) {
                    // $staff->roles()->detach($roles->filter(fn($q) => $q->slug === UserEnum::MANAGERKPI)->first());
                    foreach ($systems as $system) {
                        if (!$staff->systems->contains('slug', $system->slug)) {
                            $staff->systems()->attach($system);
                        }
                    }
                    foreach ($roles as $role) {
                        if (!$staff->roles->contains('slug', $role->slug)) {
                            if ($role->slug === UserEnum::MANAGERKPI && $staff->username === strval($staff->head_id)) {
                                $staff->roles()->attach($role);
                            }
                            if ($role->slug !== UserEnum::MANAGERKPI) {
                                $staff->roles()->attach($role);
                            }
                        }
                    }
                }
                $request->session()->flash('success', 'has been update user');
            } else {
                $request->session()->flash('error', 'ติดต่อ กับ ' . ENV('USERS_UPDATE') . "ไม่ได้");
            }
            /** make update user : name en 
            $ids = [
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                8,
                9,
                10,
                11,
                12,
                13,
                14,
                15,
                16,
                17,
                18,
                19,
                20,
                21,
                22,
                23,
                24,
                25,
                27,
                28,
                29,
                30,
                31,
                32,
                33,
                34,
                35,
                36,
                38,
                39,
                40,
                41,
                42,
                43,
                44,
                45,
                47,
                48,
                50,
                51,
                52,
                53,
                55,
                56,
                57,
                58,
                60,
                62,
                63,
                64,
                67,
                68,
                70,
                71,
                72,
                75,
                76,
                77,
                78,
                79,
                80,
                81,
                82,
                83,
                84,
                85,
                86,
                87,
                88,
                89,
                90,
                91,
                92,
                93,
                94,
                95,
                96,
                97,
                98,
                99,
                100,
                101,
                103,
                104,
                105,
                106,
                107,
                108,
                109,
                110,
                111,
                112,
                113,
                380,
                381,
                382,
                383,
                384,
                385,
                386,
                387,
                388,
                389,
                390,
                391,
                392,
                393,
                394,
                395,
                396,
                397,
                398,
                399,
                400,
                401,
                402,
                403,
                404,
                405,
                406,
                407,
                408,
                409,
                410,
                411,
                412,
                413,
                414,
                415,
                416,
                417,
                418,
                419,
                420,
                421,
                422,
                423,
                424,
                425,
                426,
                427,
                428,
                429,
                430,
                431,
                432,
                433,
                434,
                435,
                436,
                437,
                438,
                439,
                440,
                441,
                442,
                443,
                444,
                445,
                446,
                447,
                448,
                449,
                450,
                451,
                452,
                453,
                454,
                455,
                456,
                457,
                458,
                459,
                460,
                461,
                462,
                463,
                464,
                465,
                466,
                467,
                468,
                469,
                470,
                471,
                472,
                473,
                474,
                475,
                476,
                477,
                478,
                479,
                480,
                481,
                482,
                483,
                484,
                485,
                486,
                487,
                488,
                489,
                490,
                491,
                492,
                493,
                494,
                495,
                496,
                497,
                498,
                499,
                500,
                501,
                502,
                503,
                504,
                505,
                506,
                507,
                508,
                509,
                510,
                511,
                512,
                513,
                514,
                515,
                516,
                517,
                518,
                519,
                520,
                525,
                526,
                527,
                528,
                529,
                530,
                531,
                532,
                533,
                534,
                535,
                536,
                537,
                538,
                539,
                540,
                541,
                542,
                543,
                544,
                545,
                546,
                547,
                548,
                549,
                550,
                551,
                552,
                553,
                554,
                555,
                556
            ];
            $datas = [
                [
                    'name:en' => 'Mr.Pramual Boripork'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Wichai Naknoi'
                ],
                [
                    'name:en' => 'Mr.Rattanasak Keawchaipan'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Tanapat Kaowpong'
                ],
                [
                    'name:en' => 'Mr.Suchai Montaku'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Jakkit Noyraksa'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mrs.Suwan Kaewsan'
                ],
                [
                    'name:en' => 'Mr. Ong-Ard Sansri'
                ],
                [
                    'name:en' => 'Mr.Thanat Dampawanwong'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Kraingsak Chugrin'
                ],
                [
                    'name:en' => 'Mrs.Orawan Prommanok'
                ],
                [
                    'name:en' => 'Mr.Kasitithon Jandieo'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Chollatit Thongdoung'
                ],
                [
                    'name:en' => 'Ms.Pailin Winyapongphan'
                ],
                [
                    'name:en' => 'Ms.Supattra Aunkaew'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Kridsanapong Wangsalun'
                ],
                [
                    'name:en' => 'Ms.Wacharee Puchpian'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Apisara Sarapap'
                ],
                [
                    'name:en' => 'Mr.Kriangsak Klubchai'
                ],
                [
                    'name:en' => 'Mr.Khomkrit Khaemchaiyaphom'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Parami Hatong'
                ],
                [
                    'name:en' => 'Ms.Charassri Khopuangklang'
                ],
                [
                    'name:en' => 'Ms.Chotika Aomsap'
                ],
                [
                    'name:en' => 'Ms.Nisachon Pinitmontree'
                ],
                [
                    'name:en' => 'Ms.Prissana Kuddaeng'
                ],
                [
                    'name:en' => 'Ms.Arisa Sommit'
                ],
                [
                    'name:en' => 'Ms.Pimtawan Boonnil'
                ],
                [
                    'name:en' => 'Ms.Kanokwan Soda'
                ],
                [
                    'name:en' => 'Mr.Pratchaya Gatong'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Arnuphap Wongpracha'
                ],
                [
                    'name:en' => 'Mr.Apisit Trakunthum'
                ],
                [
                    'name:en' => 'Mr.Pipat Paonoy'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Jassada Wanchooserm'
                ],
                [
                    'name:en' => 'Ms.Napatchaporn Bussadeewong'
                ],
                [
                    'name:en' => 'Ms.Linada Kraithong'
                ],
                [
                    'name:en' => 'Ms.Nutthaya Jarernchawalit'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Omjan Chotjumrat'
                ],
                [
                    'name:en' => 'Ms.Ticumporn Ngoenla'
                ],
                [
                    'name:en' => 'Mr.Amondech Inkaew'
                ],
                [
                    'name:en' => 'Mr.Rammiti Samrit'
                ],
                [
                    'name:en' => 'Ms.Sukanya Chiangpa'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Mintra Chapheng'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Panida Paenla'
                ],
                [
                    'name:en' => 'Ms.Sarinthip Keawsawang'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Yothin Bowornthanaporn'
                ],
                [
                    'name:en' => 'Ms.Taechinee Khueankhuab'
                ],
                [
                    'name:en' => 'Ms.Artittaya Thongbai'
                ],
                [
                    'name:en' => 'Mr.Anuwat Mhongkiew'
                ],
                [
                    'name:en' => 'Ms.Somchanok Wongtapa'
                ],
                [
                    'name:en' => 'Ms.Kanyarat Tunnufaeng'
                ],
                [
                    'name:en' => 'Mr.Chawanat Pancharakuaorakun'
                ],
                [
                    'name:en' => 'Mr.Chakkri Wongphan'
                ],
                [
                    'name:en' => 'Ms.Sasitorn Panthanu'
                ],
                [
                    'name:en' => 'Ms.Natchaya Rattanakhom'
                ],
                [
                    'name:en' => 'Ms.Kamonchanok Promsiri'
                ],
                [
                    'name:en' => 'Mr.Manop Bangthumband'
                ],
                [
                    'name:en' => 'Ms.Soisuda Sresodapol'
                ],
                [
                    'name:en' => 'Ms.Kamonchanok Nakchom'
                ],
                [
                    'name:en' => 'Mr.Pongpipat Duangsawang'
                ],
                [
                    'name:en' => 'Mr.Mawin Krittawetin'
                ],
                [
                    'name:en' => 'Ms.Papawarin Phumilun'
                ],
                [
                    'name:en' => 'Ms.Siriorn Jitnom'
                ],
                [
                    'name:en' => 'Ms.Thanyasiri Saiyasombut'
                ],
                [
                    'name:en' => 'Ms.Phanida Tippawan'
                ],
                [
                    'name:en' => 'Ms.Kannika Chueamkrathok'
                ],
                [
                    'name:en' => 'Ms.Sonphinya Srihamek'
                ],
                [
                    'name:en' => 'Mr.Peerasak Chuekamdee'
                ],
                [
                    'name:en' => 'Mr.Narin Kunchamorin'
                ],
                [
                    'name:en' => 'Mrs.Wanida Hancom'
                ],
                [
                    'name:en' => 'Mrs.Wanida Mipol'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Wen Changzhen'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Tanasak Rangsri'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Chaiyasit Kosolmetakul'
                ],
                [
                    'name:en' => 'Mr.Yongyuth Rokeudom'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Tanyawat Nakjue'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Phiset Pongkhamsri'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Pongsak Jaiman'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Snit Wongwaeng'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mrs.Naremol Singto'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mrs.Ratree Hongkum'
                ],
                [
                    'name:en' => 'Mr.Prakob Kanang'
                ],
                [
                    'name:en' => 'Ms.Sontaya Unkhaw'
                ],
                [
                    'name:en' => 'Mr.Narong Ruankham'
                ],
                [
                    'name:en' => 'Mr.Saichol Guyaolo'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Noppadol Nakpong'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Thippawan Supsom'
                ],
                [
                    'name:en' => 'Mr.Keattichai Jongsarot'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Damnern Thongpanlek'
                ],
                [
                    'name:en' => 'Mr.Pruksa Sommit'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Pornpratan Sinsib'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mrs.Pornsawan Momwongsuwan'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Somsit Mahitthayaphorn'
                ],
                [
                    'name:en' => 'Mrs.Prapada Ongard'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Santi Ongard'
                ],
                [
                    'name:en' => 'Mrs.Areerat Wongyai'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Warayut Insuk'
                ],
                [
                    'name:en' => 'Ms. Pattama Rattanamanee'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Krishna Krasaesom'
                ],
                [
                    'name:en' => 'Mr.Chaichan Kabao'
                ],
                [
                    'name:en' => 'Mr.Adisak Thongnut'
                ],
                [
                    'name:en' => 'Mr.Nontachai Phacnoi'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Rungratree Arriyaying'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Dutkomsan Suwimanjan'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Ekkapong Mipol'
                ],
                [
                    'name:en' => 'Ms.Pairin Chantarati'
                ],
                [
                    'name:en' => 'Ms.Piyaporn Songsin'
                ],
                [
                    'name:en' => 'Mr.Kraivich Limjittrakorn'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mrs.Saowalak Inthasroi'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Khwanchai Suebjanta'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Natthapon Kitsamak'
                ],
                [
                    'name:en' => 'Ms.Kanyamuk Songsin'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Chalermrut Kanya'
                ],
                [
                    'name:en' => 'Ms.Piyawan Phuangphila'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Sowadee Khampueng'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Titima Chaiyawan'
                ],
                [
                    'name:en' => 'Ms.Navaporn Kuersuk'
                ],
                [
                    'name:en' => 'Ms.Pattraporn Horpiancharoen'
                ],
                [
                    'name:en' => 'Ms.Pornpan Jaroen'
                ],
                [
                    'name:en' => 'Mr.Patipon Sangprash'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Monthon Srivaratta'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Nattapong Phoyen'
                ],
                [
                    'name:en' => 'Ms.Thanaporn Dokken'
                ],
                [
                    'name:en' => 'Ms.Jutarat Bootsabong'
                ],
                [
                    'name:en' => 'Ms.Kitchaya Lamduan'
                ],
                [
                    'name:en' => 'Ms.Wilai Kaeodee'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Wimonrat Niyomsang'
                ],
                [
                    'name:en' => 'Mr.Chalong Budda'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Sudarat Pummora'
                ],
                [
                    'name:en' => 'Mr.Nitipoj Chaovakij'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Isara Phakdeethum'
                ],
                [
                    'name:en' => 'Mr.Lee Liangyu'
                ],
                [
                    'name:en' => 'Ms.Duanroong Makseamsong'
                ],
                [
                    'name:en' => 'Ms.Siwimol Thonkert'
                ],
                [
                    'name:en' => 'Ms.Aunpika Polwiang'
                ],
                [
                    'name:en' => 'Ms.Soitip Boonrit'
                ],
                [
                    'name:en' => 'Ms.Ammara Budda'
                ],
                [
                    'name:en' => 'Ms.Sarintip Srihamat'
                ],
                [
                    'name:en' => 'Ms.Warangkorn Lawong'
                ],
                [
                    'name:en' => 'Ms.Teetida Tonsungin'
                ],
                [
                    'name:en' => 'Mr.Chanin Kherkhan'
                ],
                [
                    'name:en' => 'Mr.Suwut Sangjan'
                ],
                [
                    'name:en' => 'Mr.Ariyatach Permphol'
                ],
                [
                    'name:en' => 'Ms.Chadaporn Sathaisong'
                ],
                [
                    'name:en' => 'Ms.Chalita Thaweesri'
                ],
                [
                    'name:en' => 'Ms.Ponrampa Kokamlang'
                ],
                [
                    'name:en' => 'Ms.Panwipa Singklin'
                ],
                [
                    'name:en' => 'Ms.Thittaya Sukan'
                ],
                [
                    'name:en' => 'Mr.Kongkeat Khumdee'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Pongsakon Chaiburud'
                ],
                [
                    'name:en' => 'Ms.Thidarat Phoongam'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Ms.Rungtiwa Chaiyuen'
                ],
                [
                    'name:en' => 'Ms.Arunrut Jutano'
                ],
                [
                    'name:en' => 'Ms.Chitsophin Ninlika'
                ],
                [
                    'name:en' => 'Mrs.Thanawan Sirisook'
                ],
                [
                    'name:en' => 'Ms.Kochamon Kuising'
                ],
                [
                    'name:en' => 'Ms.Monta Sirakantanatada'
                ],
                [
                    'name:en' => 'Ms.Benjamard Klaysuwanno'
                ],
                [
                    'name:en' => 'Mrs.Thanamon Karoon'
                ],
                [
                    'name:en' => 'Mr.Worawit Manator'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Channarong Kongthonglhang'
                ],
                [
                    'name:en' => 'Mr.Painet Ngoedsong'
                ],
                [
                    'name:en' => 'Mr.Amon Mungjuntuek'
                ],
                [
                    'name:en' => 'Mr.Prahyat Wanthum'
                ],
                [
                    'name:en' => 'Mr.Prayan Sriphakdee'
                ],
                [
                    'name:en' => 'Ms.Namfon Eimtod'
                ],
                [
                    'name:en' => 'Mr.Tawat Singkan'
                ],
                [
                    'name:en' => 'Mr.Supad Phumpana'
                ],
                [
                    'name:en' => 'Ms.Thida Sutthimun'
                ],
                [
                    'name:en' => 'Mr.Phongsatorn Puangtong'
                ],
                [
                    'name:en' => 'Mr.Pongpan Naumnim'
                ],
                [
                    'name:en' => 'Ms.Thitichaya Thaweesap'
                ],
                [
                    'name:en' => ''
                ],
                [
                    'name:en' => 'Mr.Gu Wenhai'
                ]

            ];
            foreach ($datas as $key => $value) {
                $this->userService->update($value, $ids[$key]);
            }
             */
        } catch (\Exception $e) {
            // dd($user->department_id, $department);
            // throw $e;
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return back();
    }

    public function addrole(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $roles = $this->rolesService->roleIn($request->roles);
            foreach ($roles as $value) {
                $user->roles()->attach($value);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \response($user->roles->toJson());
    }

    public function removerole(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $user->roles()->detach($request->role);
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \response($user->roles->toJson());
    }

    public function addsystem(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $systems = $this->systemService->systemIn($request->system);
            foreach ($systems as $value) {
                $user->systems()->attach($value);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \response($user->systems->toJson());
    }

    public function removesystem(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->find($id);
            $user->systems()->detach($request->system);
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \response($user->systems->toJson());
    }

    public function operations()
    {
        try {
            $users = User::whereHas('roles',fn($query) => $query->whereIn('role_id',[6]))->get();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),500);
        }
        return $this->successResponse($users,'get operation all',200);
    }
}
