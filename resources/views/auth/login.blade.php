<x-layouts.guest title="تسجيل الدخول — Beforbim">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 font-['Tajawal']">مرحباً بك مجدداً</h2>
        <p class="text-xs text-slate-500 mt-1">سجل الدخول للوصول إلى دورات الـ BIM ومشاريعك الهندسية</p>
    </div>

    @if (session('status'))
        <x-alert type="success" class="mb-4">
            {{ session('status') }}
        </x-alert>
    @endif

    @if ($errors->has('device') || $errors->has('status'))
        <x-alert type="danger" class="mb-4">
            {{ $errors->first('device') ?: $errors->first('status') }}
        </x-alert>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
        @csrf

        <x-input
            label="البريد الإلكتروني المهني"
            name="email"
            type="email"
            value="{{ old('email') }}"
            placeholder="engineer@example.com"
            required
            autofocus
        />

        <x-input
            label="كلمة المرور"
            name="password"
            type="password"
            placeholder="••••••••"
            required
        />

        <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span>تذكر جلستي على هذا الجهاز</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                نسيت كلمة المرور؟
            </a>
        </div>

        <div class="pt-2">
            <x-button type="submit" variant="primary" class="w-full">
                دخول إلى المنصة
            </x-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-600">
        مهندس جديد في المنصة؟
        <a href="{{ route('register') }}" class="text-[#00D2D3] font-semibold hover:text-cyan-600 ms-1">
            إنشاء حساب متدرب جديد
        </a>
    </div>
</x-layouts.guest>
