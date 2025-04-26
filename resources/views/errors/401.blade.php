<x-app-layout>
    <div class="max-w-4xl mx-auto mt-10 text-center">
        <h1 class="text-4xl font-semibold text-yellow-600 mb-4">401 - Unauthorized</h1>
        <p class="text-lg text-gray-600 mb-6">You need to log in to access this page.</p>

        <div class="mt-6">
            <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700 font-medium">Login</a>
            <p class="mt-4">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700">Sign up</a>.
            </p>
        </div>
    </div>
</x-app-layout>