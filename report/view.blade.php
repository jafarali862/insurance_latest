$('input.select-answer-checkbox-garage').on('change', function () {
    const columnName = $(this).data('column');
    const value = $(this).data('value');
    const caseId = $(this).data('case');

    // Highlight checkbox when checked
    if ($(this).is(':checked')) {
        $(this).closest('.form-check').addClass('bg-success text-white'); // green background
    } else {
        $(this).closest('.form-check').removeClass('bg-success text-white');
    }

    // AJAX call to update database
    $.ajax({
        url: "{{ route('save.selected') }}",
        method: 'POST',
        data: {
            column_name: columnName,
            value: value,
            case_id: caseId,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            console.log('Answer updated:', response.data.message);
        },
        error: function (xhr, status, error) {
            console.error('Error updating answer:', error);
        }
    });
});


    @if (!empty($value) && strtolower(trim($value)) !== 'n/a')
            <div class="form-check mb-1">
            <input type="checkbox" class="form-check-input select-answer-checkbox-garage"
            name="selected_field[{{ $columnName }}]"
            value="{{ $garage->id }}"
            data-column="{{ $columnName }}"
            data-value="{{ $value }}"
            data-case="{{ $report->case_id }}"
            id="{{ $radioId }}">
            Select this garage
            </div>
            @endif








      Route::get('/templates/create', [CompanyController::class, 'create_templates'])->name('templates.create_templates');
        Route::post('/templates', [CompanyController::class, 'store_templates'])->name('templates.store_templates');
        Route::get('/templates', [CompanyController::class, 'list_templates'])->name('templates.list_templates');
        Route::get('/templates/{template}/edit', [CompanyController::class, 'edit_templates'])->name('templates.edit_templates');
        Route::post('/templates/{template}/update', [CompanyController::class, 'update_templates'])->name('templates.update_templates');
        Route::delete('/templates/{template}', [CompanyController::class, 'destroy_templates'])->name('templates.destroy_templates');
        Route::get('/templates/{id}/preview', [CompanyController::class, 'preview'])->name('templates.preview');


<li class="nav-item">
                            <a href="{{ route('templates.list_templates') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Templates</p>
                            </a>
                        </li>




<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('question_template', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                  ->constrained('questions')
                  ->onDelete('cascade');

            $table->foreignId('template_id')
                  ->constrained('templates')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_template');
    }
};

