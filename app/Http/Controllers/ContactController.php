<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $posts = Contacts::where('name', 'like', "%{$query}%")
                     ->orWhere('email', 'like', "%{$query}%")
                     ->orWhere('phone', 'like', "%{$query}%")
                     ->orWhere('company', 'like', "%{$query}%")
                     ->get();

        return response()->json($posts);
    }

    public function index()
    {

      
        // Get the currently authenticated user
        $user = Auth::user();

        // Paginate contacts for the authenticated user
        $contacts = $user->contacts()->paginate(2); // Adjust the number of items per page as needed

        // Pass paginated contacts to the view
        return view('home', compact('contacts'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        Contacts::create([
            "user_id"=>Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'phone' =>  $request->phone,

        ]);
      
        return response()->json(['success' => true, 'message' => 'succcess'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $contact = Contacts::findOrFail($id);
        return view('edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $contact = Contacts::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $contact->update($validatedData);

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $contact = Contacts::findOrFail($id);
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }
}
