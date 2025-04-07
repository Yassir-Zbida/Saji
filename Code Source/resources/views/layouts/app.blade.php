<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAJI - Premium Home Decor and Furniture</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'], ['defer' => true])
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
                    }
                }
            }
        }
    </script>
    
</head>

</html>