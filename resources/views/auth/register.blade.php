<x-layouts.guest title="إنشاء حساب مهندس متدرب — Beforbim">
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900 font-['Tajawal']">انضم إلى مجتمع مهندسي الـ BIM</h2>
        <p class="text-xs text-slate-500 mt-1">ابدأ مسارك الاحترافي في نمذجة وتنسيق المشروعات الهندسية</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        <x-input
            label="الاسم الكامل"
            name="name"
            value="{{ old('name') }}"
            placeholder="م. محمد علي"
            required
            autofocus
        />

        <x-input
            label="البريد الإلكتروني المهني"
            name="email"
            type="email"
            value="{{ old('email') }}"
            placeholder="engineer@domain.com"
            required
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <x-input
                label="رقم الجوال (اختياري)"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="+966 50 000 0000"
            />

            <x-input
                label="التخصص الهندسي"
                name="engineering_title"
                value="{{ old('engineering_title') }}"
                placeholder="مهندس مدني / معماري"
            />
        </div>

        <x-input
            label="كلمة المرور"
            name="password"
            type="password"
            placeholder="8 خانات على الأقل"
            required
        />

        <x-input
            label="تأكيد كلمة المرور"
            name="password_confirmation"
            type="password"
            placeholder="أعد إدخال كلمة المرور"
            required
        />

        <div class="pt-2">
            <x-button type="submit" variant="cyan" class="w-full">
                تسجيل الحساب والبدء الآن
            </x-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-600">
        لديك حساب بالفعل؟
        <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-700 ms-1">
            تسجيل الدخول
        </a>
    </div>
</x-layouts.guest>
