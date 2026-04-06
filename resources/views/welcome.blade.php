<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <style>
        body{
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            font-family:Arial;
            background:#0f172a;
            color:white;
        }

        .box{
            text-align:center;
        }

        h1{
            font-size:32px;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>Welcome to the Datacell Portal</h1>
    <p>Loading dashboard...</p>
</div>

<script>
    setTimeout(function(){
        window.location.href = "{{ route('dashboard') }}";
    }, 3000); // 3 seconds
</script>

</body>
</html>
