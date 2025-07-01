<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'group_members' => 'required|array',
            'group_members.*' => 'exists:users,id',
        ]);

        $group = Group::create([
            'name' => $request->group_name,
        ]);

      $allMembers = array_unique(array_merge($request->group_members, [Auth::id()]));

    $group->members()->attach($allMembers);

        return response()->json(['message' => 'Group created successfully!', 'group' => $group], 200);
    }



    public function members(Group $group)
{
    $members = $group->members()->with('profilePicture')->get()->map(function ($member) {
        return [
            'id' => $member->id,
            'name' => $member->name,
            'profile_picture' => $member->profilePicture
                ? Storage::url($member->profilePicture->file_path)
                : asset('images/template/user/Noprofile.jpg'),
        ];
    });

    return response()->json(['members' => $members]);
}

public function leave(Group $group)
{
    $group->members()->detach(Auth::id());
    return response()->json(['message' => 'You have left the group.']);
}
}
