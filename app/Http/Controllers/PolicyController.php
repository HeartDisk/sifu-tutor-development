<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    // Show a list of policies
    public function index()
    {
        $policies = Policy::all();
        return view('policies.index', compact('policies'));
    }

    // Show create policy form
    public function create()
    {
        return view('policies.create');
    }

    // Store a new policy
    public function store(Request $request)
    {
        $request->validate([
            'user_role' => 'required|in:tutor,parent',
            'policy_type' => 'required|in:terms_of_service,privacy_statement',
            'content' => 'required',
        ]);

        Policy::create($request->all());

        return redirect()->route('policies.index')->with('success', 'Policy created successfully.');
    }
    
     public function show($id)
    {
        $policy = Policy::find($id);
        return view('policies.show',compact('policy'));
    }

    // Show the edit form for a policy
    public function edit(Policy $policy)
    {
        return view('policies.edit', compact('policy'));
    }

    // Update a policy
    public function update(Request $request, Policy $policy)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $policy->update($request->all());

        return redirect()->route('policies.index')->with('success', 'Policy updated successfully.');
    }

    // Delete a policy
    public function destroy($id)
    {
        Policy::where('id', $id)->delete();
        return redirect()->route('policies.index');

    }
}
