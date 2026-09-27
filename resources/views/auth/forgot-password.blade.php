<x-layouts.guest title="استعادة كلمة المرور — Beforbim">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 font-['Tajawal']">استعادة كلمة المرور</h2>
        <p class="text-xs text-slate-500 mt-1">أدخل بريدك الإلكتروني المسجل وسنرسل لك رابط إعادة تعيين كلمة المرور</p>
    </div>

    @if (session('status'))
        <x-alert type="success" class="mb-4">
            {{ session('status') }}
        </x-alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <x-input
            label="البريد الإلكتروني"
            name="email"
            type="email"
            value="{{ old('email') }}"
            placeholder="engineer@domain.com"
            required
            autofocus
        />

        <div class="pt-2">
            <x-button type="submit" variant="primary" class="w-full">
                إرسال رابط الاستعادة
            </x-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-600">
        تذكرت كلمة المرور؟
        <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-700 ms-1">
            العودة لتسجيل الدخول
        </a>
    </div>
</x-layouts.guest>
