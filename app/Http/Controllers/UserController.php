<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        /// user policy
        $this->authorizeResource(User::class, 'user');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data =  User::query();
            return datatables()->of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '<a href="' .route('user.edit',['user'=>$row->id]).'" data-edit="'.$row->id.'" class="edit-user" title="Click here for edit content" ><i class="fas fa-edit" aria-hidden="true"></i></a>';
                        $btn.= '<a href="' .route('user.destroy',['user'=>$row->id]).'" data-delete="'.$row->id.'" class="delete-user" title="Click here for delete content" >&nbsp;<i class="fas fa-trash-alt" aria-hidden="true"></i></a>';
                         return $btn;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('user.user');
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
        abort_unless($request->ajax(),404);
        $validator =Validator::make($request->all(),[
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'max:255', 'min:8', 'confirmed'],
            'avatar'=>  ['nullable','image', 'mimes:jpg,jpeg,png','max:6000'], ///kb
        ]);

        if($validator->fails()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{

            $user = new User();
            $user->email= $request->input('email');
            $user->name= $request->input('name');
            $user->password= Hash::make($request->input('password'));
            if($request->hasFile('avatar')){
                $path = $request->file('avatar')->store('avatar');
                $user->avatar= $path;
            }
            $query = $user->save();
            // event(new Registered($user));
            if(!$query){
                return response()->json(['code'=>0,'msg'=>'Something went Wrong']);
            }else{
                return response()->json(['code'=>1,'msg'=>'New User has been successfully saved']);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('user.profile',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        abort_unless($request->ajax(),404);
        // $user = User::findOrFail($id);

        return view('user.edit',['user'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        abort_unless($request->ajax(),404);
        $validator =Validator::make($request->all(),[
            'email' => ['required', 'string', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'max:255', 'min:8', 'confirmed'],
            'avatar'=>  ['nullable','image', 'mimes:jpg,jpeg,png','max:6000'], ///kb
        ]);

        if($validator->fails()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else{

            // $user = User::findOrFail($id);
            $user->email= $request->input('email');
            $user->name= $request->input('name');
            if($request->has('password')){
                $user->password= Hash::make($request->input('password'));

            }
            if($request->hasFile('avatar')){
                $path = $request->file('avatar')->store('avatar');
                if($user->avatar){
                    Storage::delete($user->avatar);
                    $user->avatar= $path;
                }else{
                    $user->avatar= $path;
                }
            }
            $query = $user->save();
            if(!$query){
                return response()->json(['code'=>0,'msg'=>'Something went Wrong']);
            }else{
                return response()->json(['code'=>1,'msg'=>'User has been successfully Updated']);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        abort_if(!$request->ajax(),404);
        $user->delete();
        return true;
    }

    public function updateProfile(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'max:255', 'min:8', 'confirmed'],
            'avatar'=>  ['nullable','image', 'mimes:jpg,jpeg,png','max:6000'], ///kb
        ]);
        $user->name= $request->input('name');
        if($request->has('password')){
            $user->password= Hash::make($request->input('password'));

        }
        if($request->hasFile('avatar')){
            $path = $request->file('avatar')->store('avatar');
            if($user->avatar){
                Storage::delete($user->avatar);
                $user->avatar= $path;
            }else{
                $user->avatar= $path;
            }
        }
         $user->save();
         return redirect()->route('home');
    }


}
