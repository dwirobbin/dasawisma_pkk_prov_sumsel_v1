<?php

namespace App\Livewire\App\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FormLogin extends Component
{
    public ?string $login_id = NULL;
    public ?string $password = NULL;

    public ?string $returnUrl = NULL;

    public function mount(): void
    {
        if (request()->has('return-url') && request()->filled('return-url')) {
            $this->returnUrl = request()->get('return-url');
        }

        if (request()->has('login-id') && request()->filled('login-id')) {
            $this->login_id = request()->get('login-id');
        }
    }

    public function render()
    {
        return view('livewire.app.auth.form-login');
    }

    public function loginHandler()
    {
        $fieldType = filter_var($this->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (empty($this->login_id)) {
            $rules = [
                'login_id'   => 'required',
                'password'  => 'required',
            ];
            $messages = [
                'required'  => ':attribute wajib diisi.',
                'exists'    => ':attribute belum terdaftar.',
            ];
            $attrs = ['login_id'   => 'Email atau Username', 'password'  => 'Kata Sandi'];
        } else if ($fieldType === 'email') {
            $rules = [
                'login_id'   => 'required|email|exists:users,email',
                'password'  => 'required',
            ];
            $messages = [
                'required'  => ':attribute wajib diisi.',
                'email'     => 'Format :attribute tidak valid.',
                'exists'    => ':attribute belum terdaftar.',
            ];
            $attrs = ['login_id'   => 'Email', 'password'  => 'Kata Sandi'];
        } else if ($fieldType === 'username') {
            $rules = [
                'login_id'   => 'required|exists:users,username',
                'password'  => 'required|min:5',
            ];
            $messages = [
                'required'  => ':attribute wajib diisi.',
                'exists'    => ':attribute belum terdaftar.',
            ];
            $attrs = ['login_id'   => 'Username', 'password'  => 'Kata Sandi'];
        }

        $validatedData = Validator::make(
            ['login_id' => $this->login_id, 'password' => $this->password],
            $rules,
            $messages,
            $attrs,
        )->validate();

        $credential = [
            $fieldType => $validatedData['login_id'],
            'password' => $validatedData['password'],
        ];

        if (auth()->attempt($credential)) {
            $user = User::query()
                ->where($fieldType, '=', $validatedData['login_id'])
                ->firstOrFail();

            if (!$user->is_active) {
                session()->flash('message', ['text' => 'Akun anda nonaktif!!', 'type' => 'danger']);
                return;
            }

            $url = $this->returnUrl ?? route('home');
            return $this->redirect($url);
        } else {
            session()->flash('message', ['text' => 'Gagal Masuk!!', 'type' => 'danger']);
        }

        $this->reset();
        $this->resetValidation();
        $this->dispatch('remove-alert');
    }

    public function loginAsAnonymous(): void
    {
        try {
            $user = User::where('email', '=', 'guest@gmail.com')->firstOrFail();
        } catch (ModelNotFoundException) {
            flash_message('User tidak ditemukan', 'fail');
            return;
        } catch (\Throwable) {
            flash_message('Gagal login', 'fail');
            return;
        } finally {
            $this->dispatch('remove-alert');
        }

        Auth::login($user);

        $this->redirect(route('home'), true);
    }
}
