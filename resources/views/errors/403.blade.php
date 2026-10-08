<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Forbidden - Simple POS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f5f9;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            color: #0f172a;
            padding: 1.5rem;
        }

        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: .75rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
            padding: 2.5rem;
            max-width: 28rem;
            text-align: center;
        }

        .code {
            font-size: 3.5rem;
            font-weight: 700;
            color: #dc2626;
            line-height: 1;
        }

        .title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-top: .75rem;
        }

        .message {
            margin-top: .5rem;
            font-size: .95rem;
            color: #475569;
        }

        .hint {
            margin-top: 1.25rem;
            font-size: .8rem;
            color: #94a3b8;
        }

        .actions {
            margin-top: 1.5rem;
            display: flex;
            gap: .75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .actions a {
            display: inline-block;
            padding: .5rem 1.1rem;
            border-radius: .5rem;
            font-size: .875rem;
            text-decoration: none;
        }

        .btn-primary {
            background: #0f172a;
            color: #fff;
        }

        .btn-secondary {
            background: #fff;
            color: #0f172a;
            border: 1px solid #cbd5e1;
        }
    </style>
</head>

<body>
    <main class="card">
        <p class="code">403</p>
        <h1 class="title">Forbidden</h1>
        <p class="message">Anda tidak memiliki akses untuk halaman ini.</p>

        <div class="actions">
            <a class="btn-secondary" href="{{ url()->previous() }}">Kembali</a>

            @auth
                <a class="btn-primary" href="{{ route('pos.create') }}">Ke Halaman Kasir</a>
            @else
                <a class="btn-primary" href="{{ route('login') }}">Login</a>
            @endauth
        </div>

        <p class="hint">Hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
    </main>
</body>

</html>
