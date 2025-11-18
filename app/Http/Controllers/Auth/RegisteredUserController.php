<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;      // <-- agrega esto
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar vista de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'estado'   => 'Solicitado', 
            ]);

            event(new Registered($user));

            DB::commit();

            return redirect('/')
                ->with('success', 'Tu solicitud ha sido enviada con éxito. Te notificaremos el estatus posteriormente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()
                ->with('error', 'Ha ocurrido un problema al procesar el registro, por favor intenta más tarde.')
                ->withInput();
        }
    }
}
