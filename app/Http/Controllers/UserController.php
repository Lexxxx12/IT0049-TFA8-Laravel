<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use League\Flysystem\FilesystemException;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $query = User::query();

        if (filled($filters['q'] ?? null)) {
            $query->where(function ($builder) use ($filters) {
                $builder->where('full_name', 'like', '%'.$filters['q'].'%')
                    ->orWhere('username', 'like', '%'.$filters['q'].'%');
            });
        }

        return view('records', [
            'kind' => 'staff',
            'fields' => ['username', 'full_name', 'password'],
            'image' => 'avatar',
            'filters' => $filters,
            'status' => 'active',
            'records' => $query->latest('id')->paginate(12)->withQueryString(),
            'total' => User::count(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new User);
    }

    public function store(SaveUserRequest $request): RedirectResponse
    {
        $user = new User;
        $this->saveUser($user, $request);

        return redirect()->route('users.index')->with('status', 'Staff member saved.');
    }

    public function show(User $user): RedirectResponse
    {
        return redirect()->route('users.edit', $user);
    }

    public function edit(User $user): View
    {
        return $this->form($user);
    }

    public function update(SaveUserRequest $request, User $user): RedirectResponse
    {
        $this->saveUser($user, $request);

        return redirect()->route('users.index')->with('status', 'Staff member saved.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        DB::transaction(function () use ($user) {
            $staff = User::query()->orderBy('id')->lockForUpdate()->get();
            if ($staff->count() <= 1) {
                throw ValidationException::withMessages([
                    'delete' => 'The last staff account cannot be deleted.',
                ]);
            }

            User::query()->lockForUpdate()->findOrFail($user->id)->delete();
        });

        if ($request->user('web')?->is($user)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        return back()->with('status', 'Staff member deleted.');
    }

    private function form(User $user): View
    {
        return view('form', [
            'kind' => 'staff',
            'fields' => ['username', 'full_name', 'password'],
            'image' => 'avatar',
            'record' => $user,
        ]);
    }

    private function saveUser(User $user, SaveUserRequest $request): void
    {
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $newPath = null;
        $oldPath = $user->avatar;

        try {
            if ($request->hasFile('avatar')) {
                $newPath = $request->image('avatar')
                    ->cover(400, 400)
                    ->toWebp()
                    ->storePublicly('staff', 'public');
                $data['avatar'] = $newPath;
            }

            $user->fill($data)->save();
        } catch (\Throwable $exception) {
            if ($newPath) {
                rescue(fn () => Storage::disk('public')->delete($newPath));
            }

            if ($exception instanceof UniqueConstraintViolationException) {
                throw ValidationException::withMessages([
                    'username' => 'That login identifier is already in use.',
                ]);
            }

            if ($exception instanceof ImageException || $exception instanceof FilesystemException) {
                report($exception);

                throw ValidationException::withMessages([
                    'avatar' => 'The image could not be saved. Please try a smaller image or contact staff.',
                ]);
            }

            throw $exception;
        }

        if ($newPath && $oldPath) {
            rescue(fn () => Storage::disk('public')->delete($oldPath));
        }
    }
}
