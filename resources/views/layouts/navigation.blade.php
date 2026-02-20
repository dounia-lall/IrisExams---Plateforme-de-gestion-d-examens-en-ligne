<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center h-16
                    {{ Auth::user()->role === 'student' ? 'justify-between' : 'justify-center gap-4' }}">

            {{-- LOGO (TOUJOURS VISIBLE) --}}
            <div class="flex items-center gap-2">
                <img
                    src="{{ asset('images/myexam-logo.png') }}"
                    alt="MyExam"
                    class="h-7 w-auto"
                >

                @if(Auth::user()->role === 'teacher')
                    <a href="{{ route('dashboard') }}"
                       class="nav-item {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
                        MyExam
                    </a>
                @endif
            </div>

            {{-- 🔹 NAV PROF UNIQUEMENT --}}
            @if(Auth::user()->role === 'teacher')

                <a href="{{ url('/teacher/exams') }}"
                   class="nav-item {{ request()->is('teacher/exams*') ? 'nav-active' : '' }}">
                    Mes examens
                </a>

               @php
    $firstExam = \App\Models\Exam::where('created_by', Auth::id())->first();
@endphp

@if($firstExam)
   <a href="{{ route('teacher.students.list') }}">
    Étudiants
</a>

@endif

            @endif

            {{-- PROFIL (PROF + ÉTUDIANT) --}}
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="nav-item flex items-center gap-2">
                        {{ Auth::user()->name }}

                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        Mon profil
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link
                            :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Déconnexion
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>

        </div>
    </div>
</nav>
