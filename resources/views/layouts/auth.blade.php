<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Saji Home')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#111111',
                        secondary: '#F5F5F5',
                        accent: '#E8E8E8',
                    },
                    boxShadow: {
                        'soft': '0 2px 10px rgba(0, 0, 0, 0.05)',
                        'medium': '0 4px 20px rgba(0, 0, 0, 0.08)',
                    },
                    fontFamily: {
                        'jakarta': ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    borderRadius: {
                        '8px': '8px',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-secondary font-jakarta">
    <div class="min-h-screen">
        @yield('content')
    </div>
</body>
</html>