<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .label { font-weight: bold; }
        select { width: 200px; padding: 4px; }
    </style>
</head>
<body>
    <p class="label">Question:</p>
    <p>{{ $question->question }}</p>
    <p class="label">Options:</p>
    <select>
        <option>Option 1</option>
        <option>Option 2</option>
        <option>Option 3</option>
    </select>
</body>
</html>
