<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use File;
use DataTables;
use Response;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                           $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }


    public function CompletedProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if(!$user->info){
            $validator = Validator::make($request->all(), [
                'name'          => 'required|string|min:2|max:20',
                'surname'       => 'required|string|min:2|max:20',
                'email'         => 'required|email|unique:users,email,'.$id,
                'phone'         => 'nullable|string|min:11|max:20',
                'description'   => 'nullable|max:2000',
                'file'          => 'nullable|image|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->route('home')->withErrors($validator)->withInput();
            }

            $user->name         = $request->name;
            $user->surname      = $request->surname;
            $user->email        = $request->email;
            $user->phone        = $request->phone;
            $user->description  = $request->description;
            $user->info         = 1;

            if ($request->has('file')) {
                if ($user->img != "default.png") {
                    if (File::exists(public_path('upload/profile/' . $user->img))) {
                        File::delete(public_path('upload/profile/' . $user->img));
                    }
                }
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid() . $user->id . '.' . $extension;
                $user->img = $fileName;
                $file->move(public_path('upload/profile/'), $fileName);
            }
            $user->save();

            session()->flash("label", "success");
            session()->flash("message", "Profile completed, successfully updated");
        }else{
            session()->flash("label", "danger");
            session()->flash("message", "Profile completed, you cannot perform this action");
        }
        return redirect()->route('home');
    }


    public function delete(Request $request, $id)
    {
        $user = User::where('id', $id)->delete();
        return Response::json($user);
    }
}
