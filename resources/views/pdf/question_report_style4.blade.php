<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .label { font-weight: bold; margin-bottom: 8px; display: block; }
        .file-placeholder {
            width: 300px; height: 150px; border: 2px dashed #888;
            display: flex; align-items: center; justify-content: center;
            color: #888; margin-top: 8px;
        }
    </style>
</head>
<body>
    <p class="label">Question:</p>
    <p>{{ $question->question }}</p>
    <span class="label">Attach File:</span>
    <div class="file-placeholder">[Upload File Here]</div>
</body>
</html>
