<html>
<head>
    <meta charset="utf-8">
    <title>Callback</title>
    <script>

        window.opener.postMessage(
            {
                success: "{{ $success }}",
                token: "{{ $token }}",
                username: "{{ $username }}",
                message: "{{ $message }}"
            },
            "http://laravelandvue.com/callback"
        );
        window.close();
    </script>
</head>
<body>
</body>
</html>
