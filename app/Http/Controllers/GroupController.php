<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
}
