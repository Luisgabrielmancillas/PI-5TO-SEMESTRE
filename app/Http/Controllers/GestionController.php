<?php

namespace App\Http\Controllers;

use App\Mail\UserStatusChangedMail;
use App\Models\SeleccionHortalizas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class GestionController extends Controller
{
    /** Estados válidos en la DB */
    private array $validStates = ['Activo', 'Inactivo', 'Solicitado'];

    // ===================== Vista principal =====================
    public function index(Request $request)
    {
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        [$items, $filters] = $this->query($request);

        return view('Dashboard.GestionUsuariosView.gestion', compact('items', 'filters', 'selectedCrop'));
    }

    // ===================== Partial de tabla (AJAX) =====================
    public function table(Request $request)
    {
        [$items] = $this->query($request);

        return response()->json([
            'tbody'      => view('Dashboard.GestionUsuariosView.partials.tbody', compact('items'))->render(),
            'pagination' => view('Dashboard.GestionUsuariosView.partials.pagination', compact('items'))->render(),
        ]);
    }

    // ===================== Acciones =====================
    public function accept(Request $request, User $user)
    {
        $this->authorizeUser($user);
        $user->estado = 'Activo';
        $user->save();

        $this->sendStatusMail($user, 'accepted');

        return $request->expectsJson()
            ? response()->json(['ok' => true])
            : back()->with('status', 'Solicitud aceptada');
    }

    public function activate(Request $request, User $user)
    {
        $this->authorizeUser($user);
        $user->estado = 'Activo';
        $user->save();

        $this->sendStatusMail($user, 'activated');

        return $request->expectsJson()
            ? response()->json(['ok' => true])
            : back()->with('status', 'Usuario activado');
    }

    public function suspend(Request $request, User $user)
    {
        $this->authorizeUser($user);
        $user->estado = 'Inactivo';
        $user->save();

        $this->sendStatusMail($user, 'suspended');

        return $request->expectsJson()
            ? response()->json(['ok' => true])
            : back()->with('status', 'Usuario suspendido');
    }

    public function reject(Request $request, User $user)
    {
        $this->authorizeUser($user);

        // Enviar correo y luego borrar
        $this->sendStatusMail($user, 'rejected');
        $user->delete();

        return $request->expectsJson()
            ? response()->json(['ok' => true])
            : back()->with('status', 'Solicitud rechazada');
    }

    // ===================== Helpers =====================
    private function query(Request $request): array
    {
        $perPage = 15;

        // “Todos” en UI = 'all' (no existe en DB)
        $estado = (string) $request->input('estado', 'all'); // 'all' | Activo | Inactivo | Solicitado
        $q      = trim((string) $request->input('q', ''));

        $query = User::query()->where('role', 'usuario');

        if ($estado !== 'all' && in_array($estado, $this->validStates, true)) {
            $query->where('estado', $estado);
        }

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
            // Si quieres que también busque por email:
            // $query->where(function($qq) use ($q) {
            //     $qq->where('name', 'like', "%{$q}%")
            //        ->orWhere('email','like', "%{$q}%");
            // });
        }

        $items = $query->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        // Devolver los filtros actuales para la vista inicial
        return [$items, ['estado' => $estado, 'q' => $q]];
    }

    private function authorizeUser(User $user): void
    {
        abort_unless($user->role === 'usuario', 403, 'Sólo usuarios con rol "usuario".');
    }

    private function sendStatusMail(User $user, string $type): void
    {
        $contact = config('mail.from.address');
        $site    = 'https://hydrobox.pi.jademajesty.com/';

        switch ($type) {
            case 'accepted':
                $title = 'HydroBox: Solicitud aceptada';
                $lines = [
                    "¡Estamos felices de informarle que su solicitud con el correo <b>{$user->email}</b> ha sido aprobada!",
                    "Ya puede acceder a nuestra página: <a href=\"{$site}\">{$site}</a> e iniciar sesión.",
                    "Cualquier duda, contáctenos: {$contact}."
                ];
                break;
            case 'rejected':
                $title = 'HydroBox: Solicitud rechazada';
                $lines = [
                    "Lamentamos informarle que su solicitud de registro en HydroBox ha sido rechazada.",
                    "Puede contactarse con nosotros para asistirle en un nuevo proceso de registro.",
                    "Correo: {$contact}."
                ];
                break;
            case 'suspended':
                $title = 'HydroBox: Cuenta suspendida';
                $lines = [
                    "Su cuenta asociada a <b>{$user->email}</b> ha sido suspendida temporalmente.",
                    "Puede comunicarse con nosotros ({$contact}) y podremos ayudarle a encontrar una solución."
                ];
                break;
            case 'activated':
                $title = 'HydroBox: Cuenta reactivada';
                $lines = [
                    "Su cuenta ha sido reactivada con éxito.",
                    "Ya puede volver a acceder a <a href=\"{$site}\">{$site}</a>.",
                    "Si necesita soporte, escríbanos a {$contact}."
                ];
                break;
            default:
                return;
        }

        try {
            Mail::to($user->email)->send(new UserStatusChangedMail($title, $lines));
        } catch (\Throwable $e) {
            Log::warning('Fallo al enviar correo de estado', [
                'msg'     => $e->getMessage(),
                'user_id' => $user->id ?? null,
                'type'    => $type,
            ]);
        }

    }

}
