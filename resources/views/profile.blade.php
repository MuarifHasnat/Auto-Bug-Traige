<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile · AutoBugTriage</title>
    <script src="{{ file_exists(public_path('js/tailwind.js')) ? '/js/tailwind.js' : 'https://cdn.tailwindcss.com' }}"></script>
    <link rel="stylesheet" href="/css/modern-saas.css">
</head>
<body class="min-h-screen bg-[#F8FAFC] text-slate-900 antialiased">
<div class="flex min-h-screen">
    @include('components.app-sidebar', ['active' => 'profile'])

    <main class="min-w-0 flex-1">
        <header class="sticky top-0 z-10 flex min-h-[64px] items-center justify-end gap-3 border-b border-slate-200 bg-white/90 px-5 backdrop-blur-md lg:px-8">
            <a href="/notifications" aria-label="Notifications" title="Notifications" class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50"><svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg>
                @if($unreadNotificationCount > 0)<span class="absolute -right-1 -top-1 rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $unreadNotificationCount }}</span>@endif
            </a>
            @include('components.profile-header')
        </header>

        <div class="w-full space-y-6 px-5 py-6 lg:px-8">
            <section class="border-b border-slate-200 pb-5">
                <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Workspace <span class="mx-2 text-slate-300">/</span><span class="text-indigo-600">Profile</span></p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">My Profile &amp; Settings</h1>
                <p class="mt-1 text-base text-slate-500">Manage your personal details, profile photo, and account security.</p>
            </section>

            @if(session('success'))<p class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800" role="status">{{ session('success') }}</p>@endif
            @if($errors->any())<p class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm font-medium text-rose-800" role="alert">{{ $errors->first() }}</p>@endif

            <div class="grid items-start gap-6 xl:grid-cols-[minmax(300px,0.8fr)_minmax(0,1.7fr)]">
                <div class="space-y-6">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm sm:p-8">
                        <div class="relative mx-auto h-36 w-36">
                            <div class="flex h-full w-full items-center justify-center overflow-hidden rounded-3xl border-4 border-indigo-50 bg-indigo-100 text-5xl font-bold text-indigo-700">
                                @if(auth()->user()->profile_photo_path)<img id="profilePhotoPreview" src="{{ route('profile.photo') }}" class="h-full w-full object-cover" alt="Profile picture">@else<span id="profileInitial">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><img id="profilePhotoPreview" class="hidden h-full w-full object-cover" alt="Selected profile photo preview">@endif
                            </div>
                            <button type="button" id="changePhotoButton" aria-label="Change profile photo" class="absolute -bottom-2 -right-2 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-xl text-white shadow-lg transition hover:bg-indigo-700">📷</button>
                        </div>
                        <h2 class="mt-5 text-2xl font-extrabold text-slate-950">{{ auth()->user()->name }}</h2>
                        <p class="mt-1 break-all text-sm text-slate-500">{{ auth()->user()->email }}</p>
                        <span class="mt-4 inline-flex items-center gap-2 rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-bold text-purple-700"><span aria-hidden="true">♢</span>{{ ucfirst(auth()->user()->role) }}</span>
                        <div class="mt-6 border-t border-slate-100 pt-5">
                            <button type="button" id="changePhotoTextButton" class="rounded-xl bg-indigo-50 px-5 py-3 text-sm font-bold text-indigo-700 hover:bg-indigo-100">📷 Change photo</button>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Account details</h2>
                        <dl class="mt-4 divide-y divide-slate-100 text-sm">
                            <div class="flex items-center justify-between gap-3 py-4"><dt class="text-slate-500">Profile ID</dt><dd class="font-mono font-bold text-slate-800">ABT-{{ str_pad((string) auth()->id(), 6, '0', STR_PAD_LEFT) }}</dd></div>
                            <div class="flex items-center justify-between gap-3 py-4"><dt class="text-slate-500">Assigned projects</dt><dd class="font-semibold text-slate-800">{{ $projects->count() }}</dd></div>
                            <div class="flex items-center justify-between gap-3 py-4"><dt class="text-slate-500">Member since</dt><dd class="text-right font-semibold text-slate-800">{{ auth()->user()->created_at?->format('M j, Y') ?? '—' }}</dd></div>
                        </dl>
                        <button type="button" id="shareProfileButton" class="mt-2 rounded-xl border border-indigo-200 px-4 py-2.5 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">Share profile ID</button>
                        <span id="shareFeedback" class="ml-2 text-sm text-emerald-700" role="status"></span>
                    </section>
                </div>

                <div class="space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="border-b border-slate-100 pb-5">
                        <h2 class="text-xl font-extrabold text-slate-950">Personal Information</h2>
                        <p class="mt-1 text-sm text-slate-500">Update your name and contact details.</p>
                    </div>
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf @method('PUT')
                        <input id="profilePhotoInput" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only">
                        <div class="grid gap-5 md:grid-cols-2">
                            <label class="block text-sm font-bold text-slate-700">Full name <span class="text-rose-500">*</span><input name="name" required maxlength="100" value="{{ old('name', auth()->user()->name) }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base font-medium text-slate-900 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-50"></label>
                            <label class="block text-sm font-bold text-slate-700">Email address<input name="email" type="email" required value="{{ old('email', auth()->user()->email) }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-base font-medium text-slate-700 outline-none focus:border-indigo-500"><span class="mt-1 block text-xs font-normal text-slate-400">Used to sign in to your account.</span></label>
                            <label class="block text-sm font-bold text-slate-700">Phone number<input name="phone" type="tel" maxlength="30" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Add a contact number" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base font-medium text-slate-900 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-50"></label>
                            <div class="text-sm font-bold text-slate-700">Assigned role<div class="mt-2 flex min-h-[50px] items-center justify-between rounded-xl border border-slate-200 bg-slate-100 px-4 text-base font-semibold text-slate-700"><span>{{ ucfirst(auth()->user()->role) }}</span><span class="text-xs font-medium text-slate-400">Managed by your workspace</span></div></div>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-6">
                            <p class="text-xs text-slate-500">Profile photos can be JPG, PNG, or WebP (up to 2 MB).</p>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">✓ Save profile changes</button>
                        </div>
                    </form>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="border-b border-slate-100 pb-5">
                        <h2 class="text-xl font-extrabold text-slate-950">Change password</h2>
                        <p class="mt-1 text-sm text-slate-500">Choose a new password to keep your account secure.</p>
                    </div>
                    @if(session('password_success'))<p class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-800" role="status">{{ session('password_success') }}</p>@endif
                    <form method="POST" action="{{ route('profile.password.update') }}" class="mt-6 space-y-5">
                        @csrf @method('PUT')
                        <label class="block text-sm font-bold text-slate-700">Current password
                            <input name="current_password" type="password" required autocomplete="current-password" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base font-medium text-slate-900 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-50">
                        </label>
                        <div class="grid gap-5 md:grid-cols-2">
                            <label class="block text-sm font-bold text-slate-700">New password
                                <input name="password" type="password" required minlength="8" autocomplete="new-password" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base font-medium text-slate-900 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-50">
                                <span class="mt-1 block text-xs font-normal text-slate-400">Use at least 8 characters.</span>
                            </label>
                            <label class="block text-sm font-bold text-slate-700">Confirm new password
                                <input name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-base font-medium text-slate-900 outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-50">
                            </label>
                        </div>
                        <div class="flex justify-end border-t border-slate-100 pt-5">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700">Update password</button>
                        </div>
                    </form>
                </section>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    const photoInput = document.getElementById('profilePhotoInput');
    const photoPreview = document.getElementById('profilePhotoPreview');
    const openPhotoPicker = () => photoInput?.click();
    document.getElementById('changePhotoButton')?.addEventListener('click', openPhotoPicker);
    document.getElementById('changePhotoTextButton')?.addEventListener('click', openPhotoPicker);
    photoInput?.addEventListener('change', (event) => {
        const file = event.target.files?.[0];
        if (!file || !photoPreview) return;
        photoPreview.src = URL.createObjectURL(file);
        photoPreview.classList.remove('hidden');
        document.getElementById('profileInitial')?.classList.add('hidden');
    });
    document.getElementById('shareProfileButton')?.addEventListener('click', async () => {
        const url = '{{ route('profile.public', auth()->id()) }}';
        try {
            if (navigator.share) await navigator.share({ title: 'My AutoBugTriage profile', url });
            else { await navigator.clipboard.writeText(url); document.getElementById('shareFeedback').textContent = 'Profile link copied'; }
        } catch (error) {
            if (error.name !== 'AbortError') document.getElementById('shareFeedback').textContent = 'Could not share the profile link.';
        }
    });
</script>
</body>
</html>
