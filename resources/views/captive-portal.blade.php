<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captive Portal</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; }
        .container { width: 400px; margin: 100px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h1 { color: #333; }
        input[type="email"], input[type="text"] { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px 20px; background: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Captive Portal</h1>
        <form action="{{ route('portal.subscribe') }}" method="POST">
            @csrf
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <br>
            <label for="coupon">Coupon:</label>
            <input type="text" name="coupon">
            <br>
            <input type="hidden" name="mac_address" value="{{ request()->header('X-MAC-Address') }}">
            <button type="submit">Submit</button>
        </form>
        
        @if($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('message'))
            <div style="color: green;">
                <p>{{ session('message') }}</p>
            </div>
        @endif
    </div>
</body>
</html>
