<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

   <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>

    <div class="container my-5">
    
        <div style="border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px;">
            <div style="text-align: center; margin-bottom: 10px;">
                <h1 style="font-size: 32px; color: #2C3E50; font-weight: bold;">{{$finalReport->insurance_com_name}}</h1>
                <p style="font-size: 18px; color: #7F8C8D;">{{$finalReport->insurance_com_email}}</p>
                <p style="font-size: 18px; color: #7F8C8D;">{{$finalReport->insurance_com_address}}</p>
                <p style="font-size: 18px; color: #7F8C8D;">{{$finalReport->insurance_com_contact_person}}</p>
                <p style="font-size: 18px; color: #7F8C8D;">{{$finalReport->insurance_com_phone}}</p>
            </div>
        </div>

  





        <div class="card mb-4">
    <div class="card-header bg-primary text-white text-center" style="text-align: center;">
        <h3>Customer Information</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>Customer Name</th>
                    <td>{{ $finalReport->customer_name ?? 'N/A' }}</td>
                    <th>Father's Name</th>
                    <td>{{ $finalReport->customer_father_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $finalReport->customer_phone ?? 'N/A' }}</td>
                    <th>Emergency Contact</th>
                    <td>{{ $finalReport->customer_emergancy_contact_no ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $finalReport->customer_email ?? 'N/A' }}</td>
                    <th>Present Address</th>
                    <td>{{ $finalReport->customer_present_address ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Permanent Address</th>
                    <td>{{ $finalReport->customer_premanent_address ?? 'N/A' }}</td>
                    <th>Policy No</th>
                    <td>{{ $finalReport->customer_policy_no ?? 'N/A' }}</td>
                </tr>
                <tr>
    <th>Policy Start Date</th>
    <td>
        {{ $finalReport->customer_policy_start ? \Carbon\Carbon::parse($finalReport->customer_policy_start)->format('d-m-Y') : 'N/A' }}
    </td>

    <th>Policy End Date</th>
    <td>
        {{ $finalReport->customer_policy_end ? \Carbon\Carbon::parse($finalReport->customer_policy_end)->format('d-m-Y') : 'N/A' }}
    </td>
</tr>

                <tr>
                    <th>Crime Number</th>
                    <!-- <td colspan="3">{{ $finalReport->customer_insurance_type ?? 'N/A' }}</td> -->
                     <td>{{ $finalReport->crime_number ?? 'N/A' }}</td>

                        <th>Police Station</th>
                    <!-- <td colspan="3">{{ $finalReport->customer_insurance_type ?? 'N/A' }}</td> -->
                     <td>{{ $finalReport->police_station ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


           <div class="card mb-4">
    <div class="card-body">
        @php
            $hasData = false;

            $fileExtensions = [
                'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp',
                'mp3', 'wav', 'ogg', 'aac',
                'mp4', 'avi', 'mov', 'mkv', 'webm',
                'pdf', 'doc', 'docx', 'xls', 'xlsx'
            ];
        @endphp

        @foreach ($validQuestions as $question)
            @php
                $column = $question->column_name;
                $answer = $finalReport->$column ?? null;

                $ext = '';
                $decodedAnswer = $answer;

                if (!is_null($answer) && is_string($answer)) {
                    $decoded = json_decode($answer, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $decodedAnswer = implode(', ', $decoded);
                        $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
                    } else {
                        $decodedAnswer = $answer;
                        $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
                    }
                }

                if (!is_null($decodedAnswer) && trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions)) {
                    $hasData = true;
                }
            @endphp
        @endforeach

        @if ($hasData)
            <div class="card-header bg-success text-white text-center">
                <h3>Garage Information</h3>
            </div>

            <table class="table table-bordered table-striped">
                <tbody>
                @foreach ($validQuestions as $question)
                    @php
                        $column = $question->column_name;
                        $answer = $finalReport->$column ?? null;

                        $ext = '';
                        $decodedAnswer = $answer;

                        if (!is_null($answer) && is_string($answer)) {
                            $decoded = json_decode($answer, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $decodedAnswer = implode(', ', $decoded);
                                $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
                            } else {
                                $decodedAnswer = $answer;
                                $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
                            }
                        }
                    @endphp

                    @if (!is_null($decodedAnswer) && trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions))
                        <tr>
                            <th style="width: 30%;">
                                {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
                            </th>
                            <td style="width: 70%; color: #7F8C8D;">
                                {{ $decodedAnswer }}
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        @else
            <p>No garage related text information available.</p>
        @endif
    </div>
</div>


            <div class="card mb-4">
            <div class="card-header bg-info text-white text-center">
            <h3>Garage Uploaded Files</h3>
            </div>
            <div class="card-body">
            <div class="row">
            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            @endphp

            @foreach ($validQuestions as $question)
            @php
            $column = $question->column_name;
            $answer = $finalReport->$column ?? null;
            $images = [];

            if (!empty($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
            } 
            else 
            {
            $images = [$answer];
            }
            }

            // Filter images by allowed extensions
            $validImages = collect($images)->filter(function ($img) use ($imageExtensions) {
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            return in_array($ext, $imageExtensions);
            });
            @endphp

            @if ($validImages->isNotEmpty())
            <div class="col-md-12 mb-3">
            <strong>{{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}</strong>
            <div class="d-flex flex-wrap gap-3 mt-2">
            @foreach ($validImages as $image)
            @php
            $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $imagePath = storage_path('app/public/' . $image);
            @endphp

            @if (file_exists($imagePath))
            <div style="flex: 1 0 21%; box-sizing: border-box;">
            <img src="data:image/{{ $extension }};base64,{{ base64_encode(file_get_contents($imagePath)) }}"
            alt="Image"
            style="width: 70%; height: auto; border: 1px solid #ccc;">
            </div>
            @endif
            @endforeach
            </div>
            </div>
            @endif
            @endforeach
            </div>
            </div>
            </div>






             <div class="card mb-4">
            <div class="card-header bg-success text-white text-center">
            <h3>Spot Information</h3>
            </div>
            <div class="card-body">
            @php $hasData = false; @endphp

            @php
            $hasData = false;

            // File extensions you want to exclude
            $fileExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', // images
            'mp3', 'wav', 'ogg', 'aac', // audio
            'mp4', 'avi', 'mov', 'mkv', 'webm', // video
            'pdf', 'doc', 'docx', 'xls', 'xlsx' // documents
            ];
            @endphp

            <table class="table table-bordered table-striped">
            <tbody>

            <!-- @foreach ($validQuestions_2 as $question)
            @php
                $column = $question->column_name;
                $answer = $finalReport->$column ?? null;

            
                $ext = '';

                if (!is_null($answer) && is_string($answer)) {
                    $decoded = json_decode($answer, true);
                    $valueToCheck = is_array($decoded) ? ($decoded[0] ?? '') : $answer;
                    $ext = strtolower(pathinfo($valueToCheck, PATHINFO_EXTENSION));
                }
            @endphp

            @if (!is_null($answer) && trim($answer) !== '' && !in_array($ext, $fileExtensions))
                @php $hasData = true; @endphp
                <tr>
                    <th style="width: 30%;">
                        {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
                    </th>
                    <td style="width: 70%; color: #7F8C8D;">
                        {{ $answer }}
                    </td>
                </tr>
            @endif
            @endforeach -->


            @foreach ($validQuestions_2 as $question)
            @php
            $column = $question->column_name;
            $answer = $finalReport->$column ?? null;

            $ext = '';
            $decodedAnswer = $answer;

            if (!is_null($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $decodedAnswer = implode(', ', $decoded); // Converts ["val"] to "val"
            $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
            } else {
            $decodedAnswer = $answer;
            $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
            }
            }
            @endphp

            @if (!is_null($decodedAnswer) && trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions))
            @php $hasData = true; @endphp
            <tr>
            <th style="width: 30%;">
            {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
            </th>
            <td style="width: 70%; color: #7F8C8D;">
            {{ $decodedAnswer }}
            </td>
            </tr>
            @endif
            @endforeach



            </tbody>
            </table>

            @if (!$hasData)
            <p>No spot-related information available.</p>
            @endif
            </div>
            </div>


            <div class="card mb-4">
            <div class="card-header bg-info text-white text-center">
            <h3>Spot Uploaded Files</h3>
            </div>
            <div class="card-body">
            <div class="row">
            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            @endphp

            @foreach ($validQuestions_2 as $question)
            @php
            $column = $question->column_name;
            $answer = $finalReport->$column ?? null;
            $images = [];

            if (!empty($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
            } 
            else 
            {
            $images = [$answer];
            }
            }

            // Filter images by allowed extensions
            $validImages = collect($images)->filter(function ($img) use ($imageExtensions) {
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            return in_array($ext, $imageExtensions);
            });
            @endphp

            @if ($validImages->isNotEmpty())
            <div class="col-md-12 mb-3">
            <strong>{{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}</strong>
            <div class="d-flex flex-wrap gap-3 mt-2">
            @foreach ($validImages as $image)
            @php
            $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $imagePath = storage_path('app/public/' . $image);
            @endphp

            @if (file_exists($imagePath))
            <div style="flex: 1 0 21%; box-sizing: border-box;">
            <img src="data:image/{{ $extension }};base64,{{ base64_encode(file_get_contents($imagePath)) }}"
            alt="Image"
            style="width: 70%; height: auto; border: 1px solid #ccc;">
            </div>
            @endif
            @endforeach
            </div>
            </div>
            @endif
            @endforeach
            </div>
            </div>
            </div>





             <div class="card mb-4">
            <div class="card-header bg-success text-white text-center">
            <h3>Driver Information</h3>
            </div>
            <div class="card-body">
            @php $hasData = false; @endphp

            @php
            $hasData = false;

            // File extensions you want to exclude
            $fileExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', // images
            'mp3', 'wav', 'ogg', 'aac', // audio
            'mp4', 'avi', 'mov', 'mkv', 'webm', // video
            'pdf', 'doc', 'docx', 'xls', 'xlsx' // documents
            ];
            @endphp

            <table class="table table-bordered table-striped">
            <tbody>


             @foreach ($validQuestions_3 as $question)
            @php
            $column = $question->column_name;
            $answer = $finalReport->$column ?? null;

            $ext = '';
            $decodedAnswer = $answer;

            if (!is_null($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $decodedAnswer = implode(', ', $decoded); // Converts ["val"] to "val"
            $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
            } else {
            $decodedAnswer = $answer;
            $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
            }
            }
            @endphp

            @if (!is_null($decodedAnswer) && trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions))
            @php $hasData = true; @endphp
            <tr>
            <th style="width: 30%;">
            {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
            </th>
            <td style="width: 70%; color: #7F8C8D;">
            {{ $decodedAnswer }}
            </td>
            </tr>
            @endif
            @endforeach
            
            </tbody>
            </table>

            @if (!$hasData)
            <p>No driver related information available.</p>
            @endif
            </div>
            </div>


            <div class="card mb-4">
            <div class="card-header bg-info text-white text-center">
            <h3>Driver Uploaded Files</h3>
            </div>
            <div class="card-body">
            <div class="row">
            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            @endphp

            @foreach ($validQuestions_3 as $question)
            @php
            $column = $question->column_name;
            $answer = $finalReport->$column ?? null;
            $images = [];

            if (!empty($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
            } 
            else 
            {
            $images = [$answer];
            }
            }

            // Filter images by allowed extensions
            $validImages = collect($images)->filter(function ($img) use ($imageExtensions) {
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            return in_array($ext, $imageExtensions);
            });
            @endphp

            @if ($validImages->isNotEmpty())
            <div class="col-md-12 mb-3">
            <strong>{{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}</strong>
            <div class="d-flex flex-wrap gap-3 mt-2">
            @foreach ($validImages as $image)
            @php
            $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $imagePath = storage_path('app/public/' . $image);
            @endphp

            @if (file_exists($imagePath))
            <div style="flex: 1 0 21%; box-sizing: border-box;">
            <img src="data:image/{{ $extension }};base64,{{ base64_encode(file_get_contents($imagePath)) }}"
            alt="Image"
            style="width: 70%; height: auto; border: 1px solid #ccc;">
            </div>
            @endif
            @endforeach
            </div>
            </div>
            @endif
            @endforeach
            </div>
            </div>
            </div>




             <div class="card mb-4">
            <div class="card-header bg-success text-white text-center">
            <h3>Owner Information</h3>
            </div>
            <div class="card-body">
            @php $hasData = false; @endphp

            @php
            $hasData = false;

            // File extensions you want to exclude
            $fileExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', // images
            'mp3', 'wav', 'ogg', 'aac', // audio
            'mp4', 'avi', 'mov', 'mkv', 'webm', // video
            'pdf', 'doc', 'docx', 'xls', 'xlsx' // documents
            ];
            @endphp

            <table class="table table-bordered table-striped">
            <tbody>

             @foreach ($validQuestions_4 as $question)
            @php
            $column = $question->column_name;
            $answer = $finalReport->$column ?? null;

            $ext = '';
            $decodedAnswer = $answer;

            if (!is_null($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $decodedAnswer = implode(', ', $decoded); // Converts ["val"] to "val"
            $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
            } else {
            $decodedAnswer = $answer;
            $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
            }
            }
            @endphp

            @if (!is_null($decodedAnswer) && trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions))
            @php $hasData = true; @endphp
            <tr>
            <th style="width: 30%;">
            {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
            </th>
            <td style="width: 70%; color: #7F8C8D;">
            {{ $decodedAnswer }}
            </td>
            </tr>
            @endif
            @endforeach

            
            </tbody>
            </table>

            @if (!$hasData)
            <p>No owner related information available.</p>
            @endif
            </div>
            </div>


            <div class="card mb-4">
            <div class="card-header bg-info text-white text-center">
            <h3>Owner Uploaded Files</h3>
            </div>
            <div class="card-body">
            <div class="row">
            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            @endphp

            @foreach ($validQuestions_4 as $question)
            @php
            $column = $question->column_name;
            $answer = $finalReport->$column ?? null;
            $images = [];

            if (!empty($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
            } 
            else 
            {
            $images = [$answer];
            }
            }

            // Filter images by allowed extensions
            $validImages = collect($images)->filter(function ($img) use ($imageExtensions) {
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            return in_array($ext, $imageExtensions);
            });
            @endphp

            @if ($validImages->isNotEmpty())
            <div class="col-md-12 mb-3">
            <strong>{{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}</strong>
            <div class="d-flex flex-wrap gap-3 mt-2">
            @foreach ($validImages as $image)
            @php
            $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $imagePath = storage_path('app/public/' . $image);
            @endphp

            @if (file_exists($imagePath))
            <div style="flex: 1 0 21%; box-sizing: border-box;">
            <img src="data:image/{{ $extension }};base64,{{ base64_encode(file_get_contents($imagePath)) }}"
            alt="Image"
            style="width: 70%; height: auto; border: 1px solid #ccc;">
            </div>
            @endif
            @endforeach
            </div>
            </div>
            @endif
            @endforeach
            </div>
            </div>
            </div>








              <div class="card mb-4">
            <div class="card-header bg-success text-white text-center">
            <h3>Accident PersonData Information</h3>
            </div>
            <div class="card-body">
            @php $hasData = false; @endphp

            @php
            $hasData = false;

            // File extensions you want to exclude
            $fileExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', // images
            'mp3', 'wav', 'ogg', 'aac', // audio
            'mp4', 'avi', 'mov', 'mkv', 'webm', // video
            'pdf', 'doc', 'docx', 'xls', 'xlsx' // documents
            ];
            @endphp

            <table class="table table-bordered table-striped">
            <tbody>

            
             @foreach ($validQuestions_5 as $question)
            @php
            $column = $question->column_name;
            $answer = $finalReport->$column ?? null;

            $ext = '';
            $decodedAnswer = $answer;

            if (!is_null($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $decodedAnswer = implode(', ', $decoded); // Converts ["val"] to "val"
            $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
            } else {
            $decodedAnswer = $answer;
            $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
            }
            }
            @endphp

            @if (!is_null($decodedAnswer) && trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions))
            @php $hasData = true; @endphp
            <tr>
            <th style="width: 30%;">
            {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
            </th>
            <td style="width: 70%; color: #7F8C8D;">
            {{ $decodedAnswer }}
            </td>
            </tr>
            @endif
            @endforeach

            </tbody>
            </table>

            @if (!$hasData)
            <p>No owner related information available.</p>
            @endif
            </div>
            </div>


            <div class="card mb-4">
            <div class="card-header bg-info text-white text-center">
            <h3>Accident Persons Uploaded Files</h3>
            </div>
            <div class="card-body">
            <div class="row">
            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            @endphp

            @foreach ($validQuestions_5 as $question)
            @php
            $column = $question->column_name;
            $answer = $finalReport->$column ?? null;
            $images = [];

            if (!empty($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
            } 
            else 
            {
            $images = [$answer];
            }
            }

            // Filter images by allowed extensions
            $validImages = collect($images)->filter(function ($img) use ($imageExtensions) {
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            return in_array($ext, $imageExtensions);
            });
            @endphp

            @if ($validImages->isNotEmpty())
            <div class="col-md-12 mb-3">
            <strong>{{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}</strong>
            <div class="d-flex flex-wrap gap-3 mt-2">
            @foreach ($validImages as $image)
            @php
            $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            $imagePath = storage_path('app/public/' . $image);
            @endphp

            @if (file_exists($imagePath))
            <div style="flex: 1 0 21%; box-sizing: border-box;">
            <img src="data:image/{{ $extension }};base64,{{ base64_encode(file_get_contents($imagePath)) }}"
            alt="Image"
            style="width: 70%; height: auto; border: 1px solid #ccc;">
            </div>
            @endif
            @endforeach
            </div>
            </div>
            @endif
            @endforeach
            </div>
            </div>
            </div>




        <!-- Garage Information Section -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3>Executive Information</h3>
            </div>
            <div class="card mb-4">
            <div class="card-body">
                 <div style="margin-bottom: 15px;">
                    <span style="font-size: 16px; color: #2C3E50; font-weight: bold; display: inline-block; min-width: 150px;">Driver Representing Executive:</span>
                    <span style="font-size: 16px; color: #7F8C8D; display: inline-block;">{{ $finalReport->driver_executive ?? 'N/A' }}</span>
                 </div>
                 <div style="margin-bottom: 15px;">
                    <span style="font-size: 16px; color: #2C3E50; font-weight: bold;  display: inline-block; min-width: 150px;">Garage Representing Executive:</span>
                    <span style="font-size: 16px; color: #7F8C8D; display: inline-block;">{{ $finalReport->garage_executive ?? 'N/A' }}</span>
                </div>
                <div style="margin-bottom: 15px;">
                    <span style="font-size: 16px; color: #2C3E50; font-weight: bold;  display: inline-block; min-width: 150px;">Spot Representing Executive:</span>
                    <span style="font-size: 16px; color: #7F8C8D; display: inline-block;">{{ $finalReport->spot_executive ?? 'N/A' }}</span>
                </div>
                <div style="margin-bottom: 15px;">
                    <span style="font-size: 16px; color: #2C3E50; font-weight: bold;  display: inline-block; min-width: 150px;">Meeting Representing Executive:</span>
                    <span style="font-size: 16px; color: #7F8C8D; display: inline-block;">{{ $finalReport->owner_executive ?? 'N/A' }}</span>
                </div>
                <div style="margin-bottom: 15px;">
                    <span style="font-size: 16px; color: #2C3E50; font-weight: bold;  display: inline-block; min-width: 150px;">Accident Representing Executive:</span>
                    <span style="font-size: 16px; color: #7F8C8D; display: inline-block;">{{ $finalReport->accident_executive ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        </div>

        <!-- Images Section -->

        <!-- <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
        <h3>Selected Images</h3>
        </div>
        <div class="card-body">

        <div style="display: flex; flex-wrap: wrap; justify-content: start; gap: 10px;">
        @if($finalReport->questions11)
        @foreach(json_decode($finalReport->questions11) as $image)
        <div style="flex: 1 0 21%; box-sizing: border-box; margin-bottom: 10px;">
        @if (file_exists(storage_path('app/public/' . $image)))
        <img src="data:image/{{ pathinfo($image, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $image))) }}" 
        alt="Image"
        style="width: 100%; height: auto; border: 1px solid #ccc;">
        @else
        <p>Image not found: {{ $image }}</p>
        @endif
        </div>
        @endforeach
        @endif    
        </div>
        </div>
        </div> -->

    </div>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
