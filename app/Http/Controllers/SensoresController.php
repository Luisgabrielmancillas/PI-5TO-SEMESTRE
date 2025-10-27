<?php

namespace App\Http\Controllers;

use App\Models\Sensores;
use Illuminate\Http\Request;

class SensoresController extends Controller
{
    public function index()
    {
        $sensores = Sensores::with(['config_sensores', 'mediciones'])->get();

        return view('Dashboard.SensoresView.sensores', compact('sensores'));
    }

    public function show($id)
    {
        $sensor = Sensores::with(['config_sensores', 'mediciones'])->findOrFail($id);

        return view('Dashboard.SensoresView.show', compact('sensor'));
    }

    public function create()
    {
        return view('Dashboard.SensoresView.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'bus' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tasa_flujo' => 'nullable|string|max:255',
            'modo_salida' => 'nullable|string|max:255',
        ]);

        Sensores::create($validated);

        return redirect()->route('sensores.index')->with('success', 'Sensor creado correctamente.');
    }

    public function edit($id)
    {
        $sensor = Sensores::findOrFail($id);
        return view('Dashboard.SensoresView.edit', compact('sensor'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'bus' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tasa_flujo' => 'nullable|string|max:255',
            'modo_salida' => 'nullable|string|max:255',
        ]);

        $sensor = Sensores::findOrFail($id);
        $sensor->update($validated);

        return redirect()->route('sensores.index')->with('success', 'Sensor actualizado correctamente.');
    }

    public function destroy($id)
    {
        $sensor = Sensores::findOrFail($id);
        $sensor->delete();

        return redirect()->route('sensores.index')->with('success', 'Sensor eliminado correctamente.');
    }
}
