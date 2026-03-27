<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Queue Status - Public Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            color: white;
        }
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .card-gradient {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
        }
        .badge {
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-success { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
        .badge-warning { background: rgba(234, 179, 8, 0.2); color: #facc15; }
        .badge-danger { background: rgba(239, 68, 68, 0.2); color: #f87171; }
    </style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <header class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                    Fuel Queue Status
                </h1>
                <p class="text-slate-400 mt-2">Real-time availability for {{ now()->format('d-m-Y') }}</p>
            </div>
            <div class="hidden md:flex gap-4">
                <a href="{{ route('login') }}" class="glass px-6 py-2 rounded-full hover:bg-white/10 transition">Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 px-6 py-2 rounded-full hover:bg-blue-700 transition">Book Slot</a>
            </div>
        </header>

        @if(count($stats) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($stats as $stat)
                    <div class="glass p-6 rounded-3xl card-gradient relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="text-xs uppercase tracking-widest text-blue-400 font-bold">{{ $stat['fuel'] }}</span>
                                <h2 class="text-2xl font-bold mt-1 text-white">{{ $stat['vehicle'] }}</h2>
                            </div>
                            @if($stat['remaining'] > 10)
                                <span class="badge badge-success">High Availability</span>
                            @elseif($stat['remaining'] > 0)
                                <span class="badge badge-warning">Limited</span>
                            @else
                                <span class="badge badge-danger">Sold Out</span>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">Current Bookings</span>
                                <span class="font-bold text-white">{{ $stat['booked'] }} / {{ $stat['capacity'] }}</span>
                            </div>
                            <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-1000" style="width: {{ ($stat['booked'] / $stat['capacity']) * 100 }}%"></div>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-slate-400 text-xs">Remaining Spots</p>
                                    <p class="text-3xl font-bold text-white">{{ $stat['remaining'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-slate-400 text-xs text-blue-300">Completed</p>
                                    <p class="text-lg font-semibold text-blue-400">{{ $stat['completed'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-24 glass rounded-3xl border-dashed border-2 border-white/20">
                <svg class="w-16 h-16 text-slate-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-slate-300">No queues configured for today yet</h2>
                <p class="text-slate-500 mt-2">Please check back later once the admin sets the daily capacity.</p>
            </div>
        @endif

        <footer class="mt-20 text-center text-slate-500 text-sm">
            <p>&copy; {{ date('Y') }} Jatri Fuel Management System. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
