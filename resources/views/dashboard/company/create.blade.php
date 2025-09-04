@extends('dashboard.layout.app')
@section('title', 'Add Company')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col text-right">
            <button class="btn btn-danger" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <button class="btn btn-warning" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="{{ route('company.list') }}" class="btn btn-success">
                <i class="fas fa-building plus"></i>
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add Company</h3>
                </div>

                <div class="alert alert-success" id="successMessage" style="display:none;margin-top: 10px;
    margin-bottom: 0px;"></div>

                <form id="companyForm" method="POST" action="{{ route('companies.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <!-- Tabs for Insurance Types -->
                           <div class="card card-secondary">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link" href="#garage" data-toggle="tab">Garage Data</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#driver" data-toggle="tab">Driver Data</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#spot" data-toggle="tab">Spot Data</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#meeting" data-toggle="tab">Owner Data</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#accident" data-toggle="tab">Accident Data</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane" id="garage"></div>
                                    <div class="tab-pane" id="driver"></div>
                                    <div class="tab-pane" id="spot"></div>
                                    <div class="tab-pane" id="meeting"></div>
                                    <div class="tab-pane" id="accident"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contact_person">Contact Person <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contact_person" name="contact_person" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                            <span class="text-danger" id="phone-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <span class="text-danger" id="email-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="address">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="selectTemplate">Select Final Report Template <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectTemplate" name="template">
                                <option value="" disabled selected>Please select</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">Template {{ $i }}</option>
                                @endfor
                                <!-- <option value="9">Default Template</option> -->
                            </select>
                            <span class="text-danger" id="template-error"></span>
                        </div>
                    </div>

                    <div class="card-footer text-left">
                        <button type="submit" class="btn btn-success">Add Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>

.card-secondary:not(.card-outline)>.card-header a.active
{
color:#fff;
}
</style>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    const categoryMap = {
        'garage': 'garage_data',
        'driver': 'driver_data',
        'spot': 'spot_data',
        'meeting': 'owner_data',
        'accident': 'accident_person_data',
    };

    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            const tabId = $(e.target).attr('href').substring(1);
            const dataCategory = categoryMap[tabId];
            const $tabPane = $('#' + tabId);

            if ($tabPane.data('loaded')) return;

            fetch(`/questions/${dataCategory}`)
                .then(res => res.json())
                .then(questions => {
                    let html = `<h5 class="mb-3 text-bold">${dataCategory.replace('_', ' ').toUpperCase()} Insurance Questions</h5>`;
                    questions.forEach(q => {
                        const colId = `check_${q.column_name}`;
                        const isFile = q.input_type === 'file';

                        let fileTypeInput = '';
                        if (isFile && q.file_type) {
                            fileTypeInput = `<input type="hidden" name="file_types[${tabId}][${q.column_name}]" value="${q.file_type}">`;
                        }

                        html += `
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="selected_questions[${tabId}][]" value="${q.column_name}" id="${colId}">
                                <input type="hidden" name="question_types[${tabId}][${q.column_name}]" value="${q.input_type}">
                                ${fileTypeInput}
                                <label class="form-check-label" for="${colId}">${q.question}</label>
                            </div>
                        `;
                    });

                    $tabPane.html(html).data('loaded', true);
                })
                .catch(() => {
                    $tabPane.html(`<p class="text-danger">Failed to load questions.</p>`);
                });
        });

        $('#companyForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('companies.store') }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                if (response.success) {
                $('#successMessage').text(response.success).show();
                $('#companyForm')[0].reset();
                $('.text-danger').text('');

                // Reload after 5 seconds (5000 milliseconds)
                // setTimeout(function () {
                // location.reload();
                // }, 5000);

                setTimeout(function() {
                // Redirect to the URL sent from backend
                window.location.href = response.redirect_url;
                }, 3000);
                }
                },

                error: function (xhr) {
                    const errors = xhr.responseJSON.errors;
                    $('.text-danger').text('');
                    $.each(errors, function (key, value) {
                        $('#' + key + '-error').text(value);
                    });
                }
            });
        });
    });
</script>
@endsection
