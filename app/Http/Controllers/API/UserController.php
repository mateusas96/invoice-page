<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->type != 'Admin'){
            $message = 'You do not have permission to access this page!';
            return json_encode(['message'=>$message]);
        }else{ return User::latest()->paginate(5); }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'name'          => 'required|string|max:191',
            'email'         => 'required|string|email|max:191|unique:users',
            'accDisabled'   => 'required',
            'companyName'   => 'required',
            'type'          => 'required',
            'password'      => 'required|string|min:8',
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'companyName' => $request['companyName'],
            'accDisabled' => $request['accDisabled'],
            'type' => $request['type'],
            'password' => Hash::make($request['password']),
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request,[
            'name'          => 'required|string|max:191',
            'email'         => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'companyName'   => 'required',
            'accDisabled'   => 'required',
            'type'          => 'required',
        ]);

        $user->update($request->all());

        return $user;
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

    public function search(){
        if($search = \Request::get('q')){
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")->orWhere('email','LIKE',"%$search%")->orWhere('type','LIKE',"%$search%")->orWhere('companyName','LIKE',"%$search%");
            })->latest()->paginate(5);
            return $users;
        }
        
        return $users=User::latest()->paginate(5);
    }
}
