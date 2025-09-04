<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Investigation Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

   <style>
    table 
    {
    border-collapse: collapse;
    width: 100%;
    }

    th,
    td 
    {
    border: 1px solid black;
    padding: 8px;
    text-align: left;
    }

    .custom-th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    text-transform: capitalize;
    padding: 10px;
    }

    .custom-td {
    background-color: #f8f9fa;
    color: #343a40;
    padding: 10px;
    }


    

    /* Table container */
.custom-table {
  border-collapse: collapse;
  width: 100%;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header styles */
.custom-table-header th {
  background-color: #007bff;
  color: white;
  padding: 10px;
  font-weight: bold;
  text-align: center;
}

/* Specific columns */
.col-number {
  width: 5%;
}

.col-description {
  width: 30%;
  text-align: left;
}

.col-details {
  width: 65%;
  text-align: left;
  color: #19191aff;
  font-style: italic;
}

/* Rows */
.custom-row {
  background-color: #f9f9f9;
  transition: background-color 0.3s ease;
}

.custom-row:nth-child(even) {
  background-color: #e9ecef;
}

.custom-row:hover {
  background-color: #d1ecf1;
}

/* For question title cell */
.question-title {
  font-weight: 600;
  font-size: 1.05em;
  color: #333;
}

/* For answer text */
.answer-text {
  color: #19191aff;
  font-size: 1em;
  padding-left: 10px;
}



.question-answer-block 
{
border: 1px solid #ccc;
padding: 12px 15px;
border-radius: 8px;
margin-bottom: 25px;
background-color: #f9f9f9;
display: flex;
gap: 15px;
align-items: center;
flex-wrap: wrap;

}

/* Default question style */
.question-text {
    font-size: 16px;
    color: #2C3E50;
    font-weight: bold;
    min-width: 200px;
    flex-shrink: 0;
}

/* Default answer style */
.answer-text {
    font-size: 16px;
    color: #19191a;
    flex: 1 1 auto;
    word-break: break-word;
}

/* --- Styling variations based on context --- */

/* Highlight important answers */
.question-answer-block.highlight .answer-text {
    background-color: #fff3cd;
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: bold;
    color: #856404;
}

/* For warning or alert-type info */
.question-answer-block.warning {
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.question-answer-block.warning .question-text {
    color: #721c24;
}

.question-answer-block.warning .answer-text {
    color: #721c24;
}

/* For dark theme block */
.question-answer-block.dark {
    background-color: #2c3e50;
    border-color: #34495e;
}

.question-answer-block.dark .question-text,
.question-answer-block.dark .answer-text {
    color: #ecf0f1;
}

/* For grouped section */
.question-answer-block.section-title {
    background-color: #e9ecef;
    font-size: 17px;
    font-weight: bold;
    color: #007bff;
}

    </style>

    <div class="container my-5"> 
        <div style="border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px;">
            <div style="text-align: center; margin-bottom: 10px;">
                <h1 style="font-size: 32px; color: #178bffff; font-weight: bold;">{{$finalReport->insurance_com_name}}</h1>
                <p style="font-size: 18px; color: #7F8C8D;">{{$finalReport->insurance_com_email}}</p>
                <p style="font-size: 18px; color: #7F8C8D;">{{$finalReport->insurance_com_address}}</p>
                <p style="font-size: 18px; color: #7F8C8D;">{{$finalReport->insurance_com_contact_person}}</p>
                <p style="font-size: 18px; color: #7F8C8D;">{{$finalReport->insurance_com_phone}}</p>
            </div>
        </div>


        <div class="card mb-4">
        <div class="card-header bg-primary text-white text-center" style="text-align: center;">
        <h3 style="color: #7d18b8; font-weight: bold;">Customer Information</h3>
        </div>

            <div class="card-body">
            <table class="table table-bordered table-striped">
       
             <tbody>
            <tr>
                <th class="custom-th">Customer Name</th>
                <td class="custom-td">{{ $finalReport->customer_name ?? 'N/A' }}</td>
                <th class="custom-th">Father's Name</th>
                <td class="custom-td">{{ $finalReport->customer_father_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th class="custom-th">Phone</th>
                <td class="custom-td">{{ $finalReport->customer_phone ?? 'N/A' }}</td>
                <th class="custom-th">Emergency Contact</th>
                <td class="custom-td">{{ $finalReport->customer_emergancy_contact_no ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th class="custom-th">Email</th>
                <td class="custom-td">{{ $finalReport->customer_email ?? 'N/A' }}</td>
                <th class="custom-th">Present Address</th>
                <td class="custom-td">{{ $finalReport->customer_present_address ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th class="custom-th">Permanent Address</th>
                <td class="custom-td">{{ $finalReport->customer_premanent_address ?? 'N/A' }}</td>
                <th class="custom-th">Policy No</th>
                <td class="custom-td">{{ $finalReport->customer_policy_no ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th class="custom-th">Policy Start Date</th>
                <td class="custom-td">
                    {{ $finalReport->customer_policy_start ? \Carbon\Carbon::parse($finalReport->customer_policy_start)->format('d-m-Y') : 'N/A' }}
                </td>
                <th class="custom-th">Policy End Date</th>
                <td class="custom-td">
                    {{ $finalReport->customer_policy_end ? \Carbon\Carbon::parse($finalReport->customer_policy_end)->format('d-m-Y') : 'N/A' }}
                </td>
            </tr>
            <tr>
                <th class="custom-th">Crime Number</th>
                <td class="custom-td">{{ $finalReport->crime_number ?? 'N/A' }}</td>
                <th class="custom-th">Police Station</th>
                <td class="custom-td">{{ $finalReport->police_station ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th class="custom-th">Case Type</th>
                <td class="custom-td">{{ $finalReport->customer_insurance_type ?? 'N/A' }}</td>
                <th class="custom-th">Investigation Date</th>
                <td class="custom-td">
                    {{ $finalReport->case_assign_date ? \Carbon\Carbon::parse($finalReport->case_assign_date)->format('d-m-Y') : 'N/A' }}
                </td>
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
                <h3 style="color: #7d18b8; font-weight: bold;">Garage Information</h3>
            </div>

            <div class="container">
            @php $rowId = 1; @endphp
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

      

            <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: nowrap; width: 100%;">

            <!-- Question Box -->
            <div style="width: 50%;border: 2px solid #007bff;
            padding: 15px;
            border-radius: 8px;
            background-color: #eef5ff;
            font-size: 16px;
            color: #2C3E50;
            font-weight: bold;
            box-sizing: border-box;">

            {{ $rowId++ }}. {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
            </div>

            <!-- Answer Box -->
            <div style="width: 50%;
            border: 2px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            background-color: #eafaf1;
            font-size: 16px;
            color: #19191a;
            box-sizing: border-box;
            word-break: break-word;">

            {{ $decodedAnswer }}
            </div>

            </div>


            @endif
            @endforeach
            </div>            
            @else
            @endif
            </div>
            </div>



           @php
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
    $hasGarageImages = false;

    foreach ($validQuestions as $question) {
        $column = $question->column_name;
        $answer = $finalReport->$column ?? null;
        $images = [];

        if (!empty($answer) && is_string($answer)) {
            $decoded = json_decode($answer, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $images = $decoded;
            } else {
                $images = [$answer];
            }
        }

        $validImages = collect($images)->filter(function ($img) use ($imageExtensions) {
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            return in_array($ext, $imageExtensions);
        });

        if ($validImages->isNotEmpty()) 
        {
        $hasGarageImages = true;
        break;
        }
    }
@endphp

        @if ($hasGarageImages)
        <div class="card mb-4">
        <div class="card-header bg-info text-white text-center">
        <h3 style="color: #7d18b8; font-weight: bold;">Garage Uploaded Files</h3>
        </div>
        <div class="card-body">
        <div class="row">
        @foreach ($validQuestions as $question)
        @php
        $column = $question->column_name;
        $answer = $finalReport->$column ?? null;
        $images = [];

        if (!empty($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
        } else {
            $images = [$answer];
        }
        }

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
        @endif



<!-- Spot -->


           @php
$hasData = false;
$fileExtensions = [
    'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', // images
    'mp3', 'wav', 'ogg', 'aac', // audio
    'mp4', 'avi', 'mov', 'mkv', 'webm', // video
    'pdf', 'doc', 'docx', 'xls', 'xlsx' // documents
];

// First loop: check if any valid data exists
foreach ($validQuestions_2 as $question) {
    $column = $question->column_name;
    $answer = $finalReport->$column ?? null;
    $ext = '';

    if (!is_null($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $decodedAnswer = implode(', ', $decoded);
            $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
        } else {
            $decodedAnswer = $answer;
            $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
        }

        if (trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions)) {
            $hasData = true;
            break;
        }
    }
}
@endphp

@if ($hasData)
<div class="card mb-4">
    <div class="card-header bg-success text-white text-center">
        <h3 style="color: #7d18b8; font-weight: bold;">Spot Information</h3>
    </div>

    
            <div class="container">
            @php $rowId = 1; @endphp
            @foreach ($validQuestions_2 as $question)
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

            <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: nowrap; width: 100%;">

            <!-- Question Box -->
            <div style="width: 50%;
            border: 2px solid #007bff;
            padding: 15px;
            border-radius: 8px;
            background-color: #eef5ff;
            font-size: 16px;
            color: #2C3E50;
            font-weight: bold;
            box-sizing: border-box;">
            {{ $rowId++ }}. {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
            </div>

            <!-- Answer Box -->
            <div style="width: 50%;
            border: 2px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            background-color: #eafaf1;
            font-size: 16px;
            color: #19191a;
            box-sizing: border-box;
            word-break: break-word;
            ">
            {{ $decodedAnswer }}
            </div>

            </div>


            @endif
            @endforeach
            </div>            
            @else
            @endif
            </div>
            </div>



          @php
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
$hasSpotImages = false;

// First, check if there's any valid image
foreach ($validQuestions_2 as $question) {
    $column = $question->column_name;
    $answer = $finalReport->$column ?? null;
    $images = [];

    if (!empty($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
        } else {
            $images = [$answer];
        }
    }

    foreach ($images as $img) {
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        if (in_array($ext, $imageExtensions)) {
            $imagePath = storage_path('app/public/' . $img);
            if (file_exists($imagePath)) {
                $hasSpotImages = true;
                break 2; // Stop as soon as one valid image is found
            }
        }
    }
}
@endphp


@if ($hasSpotImages)
<div class="card mb-4">
    <div class="card-header bg-info text-white text-center">
        <h3 style="color: #7d18b8; font-weight: bold;">Spot Uploaded Files</h3>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach ($validQuestions_2 as $question)
                @php
                    $column = $question->column_name;
                    $answer = $finalReport->$column ?? null;
                    $images = [];

                    if (!empty($answer) && is_string($answer)) {
                        $decoded = json_decode($answer, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $images = $decoded;
                        } else {
                            $images = [$answer];
                        }
                    }

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
@endif

<!-- Driver -->




           @php
$hasData = false;
$fileExtensions = [
    'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', // images
    'mp3', 'wav', 'ogg', 'aac', // audio
    'mp4', 'avi', 'mov', 'mkv', 'webm', // video
    'pdf', 'doc', 'docx', 'xls', 'xlsx' // documents
];

// First loop: check if any valid data exists
foreach ($validQuestions_3 as $question) {
    $column = $question->column_name;
    $answer = $finalReport->$column ?? null;
    $ext = '';

    if (!is_null($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $decodedAnswer = implode(', ', $decoded);
            $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
        } else {
            $decodedAnswer = $answer;
            $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
        }

        if (trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions)) {
            $hasData = true;
            break;
        }
    }
}
@endphp

@if ($hasData)
<div class="card mb-4">
    <div class="card-header bg-success text-white text-center">
        <h3 style="color: #7d18b8; font-weight: bold;">Driver Information </h3>
    </div>


            <div class="container">
            @php $rowId = 1; @endphp
            @foreach ($validQuestions_3 as $question)
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

            <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: nowrap; width: 100%;">

            <!-- Question Box -->
            <div style="width: 50%;
            border: 2px solid #007bff;
            padding: 15px;
            border-radius: 8px;
            background-color: #eef5ff;
            font-size: 16px;
            color: #2C3E50;
            font-weight: bold;
            box-sizing: border-box;">
            {{ $rowId++ }}. {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
            </div>

            <!-- Answer Box -->
            <div style="width: 50%;
            border: 2px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            background-color: #eafaf1;
            font-size: 16px;
            color: #19191a;
            box-sizing: border-box;
            word-break: break-word;
            ">
            {{ $decodedAnswer }}
            </div>

            </div>


            @endif
            @endforeach
            </div>            
            @else
            @endif
            </div>
            </div>



          @php
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
$hasSpotImages = false;

// First, check if there's any valid image
foreach ($validQuestions_3 as $question) {
    $column = $question->column_name;
    $answer = $finalReport->$column ?? null;
    $images = [];

    if (!empty($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
        } else {
            $images = [$answer];
        }
    }

    foreach ($images as $img) {
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        if (in_array($ext, $imageExtensions)) {
            $imagePath = storage_path('app/public/' . $img);
            if (file_exists($imagePath)) {
                $hasSpotImages = true;
                break 2; // Stop as soon as one valid image is found
            }
        }
    }
}
@endphp


@if ($hasSpotImages)
<div class="card mb-4">
    <div class="card-header bg-info text-white text-center">
        <h3 style="color: #7d18b8; font-weight: bold;">Driver Uploaded Files</h3>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach ($validQuestions_3 as $question)
                @php
                    $column = $question->column_name;
                    $answer = $finalReport->$column ?? null;
                    $images = [];

                    if (!empty($answer) && is_string($answer)) {
                        $decoded = json_decode($answer, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $images = $decoded;
                        } else {
                            $images = [$answer];
                        }
                    }

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
@endif





<!-- Owner -->

@php
$hasData = false;
$fileExtensions = [
    'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', // images
    'mp3', 'wav', 'ogg', 'aac', // audio
    'mp4', 'avi', 'mov', 'mkv', 'webm', // video
    'pdf', 'doc', 'docx', 'xls', 'xlsx' // documents
];

// First loop: check if any valid data exists
foreach ($validQuestions_4 as $question) {
    $column = $question->column_name;
    $answer = $finalReport->$column ?? null;
    $ext = '';

    if (!is_null($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $decodedAnswer = implode(', ', $decoded);
            $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
        } else {
            $decodedAnswer = $answer;
            $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
        }

        if (trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions)) {
            $hasData = true;
            break;
        }
    }
}
@endphp

@if ($hasData)
<div class="card mb-4">
    <div class="card-header bg-success text-white text-center">
        <h3 style="color: #7d18b8; font-weight: bold;">Owner Information </h3>
    </div>


            <div class="container">
            @php $rowId = 1; @endphp
            @foreach ($validQuestions_4 as $question)
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

             <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: nowrap; width: 100%;">

            <!-- Question Box -->
            <div style="width: 50%;
            border: 2px solid #007bff;
            padding: 15px;
            border-radius: 8px;
            background-color: #eef5ff;
            font-size: 16px;
            color: #2C3E50;
            font-weight: bold;
            box-sizing: border-box;">
            {{ $rowId++ }}. {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
            </div>

            <!-- Answer Box -->
            <div style="width: 50%;
            border: 2px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            background-color: #eafaf1;
            font-size: 16px;
            color: #19191a;
            box-sizing: border-box;
            word-break: break-word;
            ">
            {{ $decodedAnswer }}
            </div>

            </div>


            @endif
            @endforeach
            </div>            
            @else
            @endif
            </div>
            </div>



          @php
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
$hasSpotImages = false;

// First, check if there's any valid image
foreach ($validQuestions_4 as $question) {
    $column = $question->column_name;
    $answer = $finalReport->$column ?? null;
    $images = [];

    if (!empty($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
        } else {
            $images = [$answer];
        }
    }

    foreach ($images as $img) {
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        if (in_array($ext, $imageExtensions)) {
            $imagePath = storage_path('app/public/' . $img);
            if (file_exists($imagePath)) {
                $hasSpotImages = true;
                break 2; // Stop as soon as one valid image is found
            }
        }
    }
}
@endphp


@if ($hasSpotImages)
<div class="card mb-4">
    <div class="card-header bg-info text-white text-center">
        <h3 style="color: #7d18b8; font-weight: bold;">Owner Uploaded Files</h3>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach ($validQuestions_4 as $question)
                @php
                    $column = $question->column_name;
                    $answer = $finalReport->$column ?? null;
                    $images = [];

                    if (!empty($answer) && is_string($answer)) {
                        $decoded = json_decode($answer, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $images = $decoded;
                        } else {
                            $images = [$answer];
                        }
                    }

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
@endif


<!-- Accident PersonData -->

@php
$hasData = false;
$fileExtensions = [
    'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', // images
    'mp3', 'wav', 'ogg', 'aac', // audio
    'mp4', 'avi', 'mov', 'mkv', 'webm', // video
    'pdf', 'doc', 'docx', 'xls', 'xlsx' // documents
];

// First loop: check if any valid data exists
foreach ($validQuestions_5 as $question) {
    $column = $question->column_name;
    $answer = $finalReport->$column ?? null;
    $ext = '';

    if (!is_null($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $decodedAnswer = implode(', ', $decoded);
            $ext = strtolower(pathinfo($decoded[0] ?? '', PATHINFO_EXTENSION));
        } else {
            $decodedAnswer = $answer;
            $ext = strtolower(pathinfo($answer, PATHINFO_EXTENSION));
        }

        if (trim($decodedAnswer) !== '' && !in_array($ext, $fileExtensions)) {
            $hasData = true;
            break;
        }
    }
}
@endphp

@if ($hasData)
<div class="card mb-4">
    <div class="card-header bg-success text-white text-center">
        <h3 style="color: #7d18b8; font-weight: bold;">Accident Person Information </h3>
    </div>


            <div class="container">
            @php $rowId = 1; @endphp
            @foreach ($validQuestions_5 as $question)
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

             <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: nowrap; width: 100%;">

            <!-- Question Box -->
            <div style="width: 50%;
            border: 2px solid #007bff;
            padding: 15px;
            border-radius: 8px;
            background-color: #eef5ff;
            font-size: 16px;
            color: #2C3E50;
            font-weight: bold;
            box-sizing: border-box;">
            {{ $rowId++ }}. {{ $question->question_text ?? ucfirst(str_replace('_', ' ', $column)) }}
            </div>

            <!-- Answer Box -->
            <div style="width: 50%;
            border: 2px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            background-color: #eafaf1;
            font-size: 16px;
            color: #19191a;
            box-sizing: border-box;
            word-break: break-word;
            ">
            {{ $decodedAnswer }}
            </div>

            </div>

            @endif
            @endforeach
            </div>            
            @else
            @endif
            </div>
            </div>





        @php
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $hasSpotImages = false;

        // First, check if there's any valid image
        foreach ($validQuestions_5 as $question) {
        $column = $question->column_name;
        $answer = $finalReport->$column ?? null;
        $images = [];

        if (!empty($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $images = $decoded;
        } else {
        $images = [$answer];
        }
        }

        foreach ($images as $img) {
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        if (in_array($ext, $imageExtensions)) {
        $imagePath = storage_path('app/public/' . $img);
        if (file_exists($imagePath)) {
        $hasSpotImages = true;
        break 2; // Stop as soon as one valid image is found
        }
        }
        }
        }
        @endphp


        @if ($hasSpotImages)
        <div class="card mb-4">
        <div class="card-header bg-info text-white text-center">
        <h3 style="color: #7d18b8; font-weight: bold;">Accident Data Uploaded Files</h3>
        </div>
        <div class="card-body">
        <div class="row">
        @foreach ($validQuestions_5 as $question)
        @php
        $column = $question->column_name;
        $answer = $finalReport->$column ?? null;
        $images = [];

        if (!empty($answer) && is_string($answer)) {
        $decoded = json_decode($answer, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
        } else {
            $images = [$answer];
        }
        }

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
        @endif


            
        <!-- Garage Information Section -->
        <div class="card mb-4">
        <div class="card-header bg-success text-white">
        <h3 style="color: #7d18b8; font-weight: bold;">Executive Information</h3>
        </div>
        <div class="card mb-4">
        <div class="card-body">
        <div style="margin-bottom: 15px;">
        <span style="font-size: 16px; color: #2C3E50; font-weight: bold; display: inline-block; min-width: 150px;">Driver Representing Executive:</span>
        <span style="font-size: 16px; color: #19191aff; display: inline-block;">{{ $finalReport->driver_executive ?? 'N/A' }}</span>
        </div>
        <div style="margin-bottom: 15px;">
        <span style="font-size: 16px; color: #2C3E50; font-weight: bold;  display: inline-block; min-width: 150px;">Garage Representing Executive:</span>
        <span style="font-size: 16px; color: #19191aff; display: inline-block;">{{ $finalReport->garage_executive ?? 'N/A' }}</span>
        </div>
        <div style="margin-bottom: 15px;">
        <span style="font-size: 16px; color: #2C3E50; font-weight: bold;  display: inline-block; min-width: 150px;">Spot Representing Executive:</span>
        <span style="font-size: 16px; color: #19191aff; display: inline-block;">{{ $finalReport->spot_executive ?? 'N/A' }}</span>
        </div>
        <div style="margin-bottom: 15px;">
        <span style="font-size: 16px; color: #2C3E50; font-weight: bold;  display: inline-block; min-width: 150px;">Meeting Representing Executive:</span>
        <span style="font-size: 16px; color: #19191aff; display: inline-block;">{{ $finalReport->owner_executive ?? 'N/A' }}</span>
        </div>
        <div style="margin-bottom: 15px;">
        <span style="font-size: 16px; color: #2C3E50; font-weight: bold;  display: inline-block; min-width: 150px;">Accident Representing Executive:</span>
        <span style="font-size: 16px; color: #19191aff; display: inline-block;">{{ $finalReport->accident_executive ?? 'N/A' }}</span>
        </div>
        </div>
        </div>
        </div>


         <!-- Garage Information Section -->
        <div class="card mb-4">
        <div class="card-header bg-success text-white">
        <h4 style="color: #7d18b8; font-weight: bold;">Submission Date</h4>
        {{ $finalReport->created_at ? \Carbon\Carbon::parse($finalReport->created_at)->format('d-m-Y h:i A') : 'N/A' }}
        </div>
        </div>
        </div>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
