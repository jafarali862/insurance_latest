<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .label { font-weight: bold; }
        input[type="date"] { padding: 4px; margin-top: 4px; }
    </style>
</head>
<body>
    <p class="label">Question:</p>
    <p>{{ $question->question }}</p>
    <p class="label">Select Date:</p>
    <input type="date" />
</body>
</html>
