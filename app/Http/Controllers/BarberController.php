<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barbers = Barber::all();
        return view('barbers.index', compact('barbers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barbers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'working_hours' => 'nullable|array'
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('barbers', 'public');
        }

        if (isset($validated['working_hours'])) {
            $validated['working_hours'] = json_encode($validated['working_hours']);
        }

        Barber::create($validated);

        return redirect()->route('barbers.index')
            ->with('success', 'Мастер успешно добавлен');
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
    public function edit(Barber $barber)
    {
        return view('barbers.edit', compact('barber'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barber $barber)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'working_hours' => 'nullable|array'
        ]);

        if ($request->hasFile('photo')) {
            if ($barber->photo) {
                Storage::disk('public')->delete($barber->photo);
            }
            $validated['photo'] = $request->file('photo')->store('barbers', 'public');
        }

        if (isset($validated['working_hours'])) {
            $validated['working_hours'] = json_encode($validated['working_hours']);
        }

        $barber->update($validated);

        return redirect()->route('barbers.index')
            ->with('success', 'Информация о мастере успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barber $barber)
    {
        if ($barber->photo) {
            Storage::disk('public')->delete($barber->photo);
        }
        
        $barber->delete();

        return redirect()->route('barbers.index')
            ->with('success', 'Мастер успешно удален');
    }
}
