<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfessorController extends Controller
{
    /**
     * Lista todos os professores.
     */
    public function index(Request $request)
    {
        $busca = $request->busca;

        $professores = User::where('role', 'professor')
            ->when($busca, function ($query) use ($busca) {
                $query->where(function ($q) use ($busca) {
                    $q->where('name', 'like', "%{$busca}%")
                        ->orWhere('email', 'like', "%{$busca}%");
                });
            })
            ->orderBy('name')
            ->get();

        return view('professores.index', compact('professores', 'busca'));
    }

    /**
     * Formulário de cadastro.
     */
    public function create()
    {
        return view('professores.create');
    }

    /**
     * Salva um professor.
     */
    public function store(Request $request)
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(6), 'confirmed'],
        ]);

        User::create([
            'name' => trim($request->name),
            'email' => Str::lower(trim($request->email)),
            'password' => $request->password,
            'role' => 'professor',
        ]);

        return redirect()->route('direcao.professores.index')
            ->with('sucesso', 'Professor cadastrado com sucesso!');
    }

    /**
     * Formulário de edição.
     */
    public function edit(User $professor)
    {
        $this->garantirProfessor($professor);

        return view('professores.edit', [
            'professor' => $professor,
        ]);
    }

    /**
     * Atualiza professor.
     */
    public function update(Request $request, User $professor)
    {
        $this->garantirProfessor($professor);

        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($professor->id),
            ],
            'password' => ['nullable', Password::min(6), 'confirmed'],
        ]);

        $professor->name = trim($request->name);
        $professor->email = Str::lower(trim($request->email));

        if ($request->filled('password')) {
            $professor->password = $request->password;
        }

        $professor->save();

        return redirect('/direcao/professores')
            ->with('sucesso', 'Professor atualizado com sucesso!');
    }

    /**
     * Excluir professor.
     */
    public function destroy(User $professor)
    {
        $this->garantirProfessor($professor);

        if ($professor->haes()->exists()) {
            return back()->with(
                'error',
                'Não é possível excluir um professor que possui HAEs cadastradas.'
            );
        }

        $professor->delete();

        return back()->with(
            'sucesso',
            'Professor excluído com sucesso.'
        );
    }

    private function garantirProfessor(User $user): void
    {
        abort_unless($user->role === User::ROLE_PROFESSOR, 404);
    }
}
