<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - LCMWorld</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0D0F14;
            color: #ffffff;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glow-btn {
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            box-shadow: 0 4px 15px rgba(0, 242, 254, 0.3);
            transition: all 0.3s ease;
        }
        .glow-btn:hover {
            box-shadow: 0 6px 20px rgba(0, 242, 254, 0.5);
            transform: translateY(-1px);
        }
        .input-field {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #00f2fe;
            box-shadow: 0 0 0 2px rgba(0, 242, 254, 0.2);
            outline: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Background Accents -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-cyan-500/20 blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-purple-500/20 blur-[120px]"></div>

    <div class="glass-panel w-full max-w-md p-8 rounded-2xl relative z-10">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500">LCMWorld</h1>
            <p class="text-gray-400 mt-2 text-sm">Secure Admin Authentication</p>
        </div>

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/admin/login') }}">
            @csrf
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="input-field w-full px-4 py-3 rounded-lg text-sm" placeholder="admin@lcmworld.com">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                <input type="password" name="password" required class="input-field w-full px-4 py-3 rounded-lg text-sm" placeholder="••••••••">
            </div>

            <button type="submit" class="glow-btn w-full py-3 rounded-lg font-semibold text-white">
                Sign In to Dashboard
            </button>
        </form>
    </div>

</body>
</html>
