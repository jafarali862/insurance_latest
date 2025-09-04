<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Salary Table</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

    <div class="container mt-5">
        <button class="btn btn-outline-primary mb-3" onclick="history.back()">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <h2 class="text-center">Salary Table</h2>
        <table class="table table-striped table-responsive">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Basic</th>
                    <th>Allowance</th>
                    <th>Bonus</th>
                    <th>Total</th>
                    <th>Month/Year</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($salaries as $item)
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td>{{$item->basic}}</td>
                        <td>{{$item->allowance}}</td>
                        <td>{{$item->bonus}}</td>
                        <td>{{$item->total}}</td>
                        <td>{{$item->month_year}}</td>
                        <td>{{$item->date}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
