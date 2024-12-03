let fileInputCounter = 1;
$(document).ready(function () {
  // Initialize jQuery Validation

  $('#upload-form').submit(function (e) {
    let isValid = true;
    const maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

    $('.image-input').each(function () {
      const fileInput = $(this)[0]; // Access raw DOM element
      const errorElement = $(this).siblings('.error');

      if (!fileInput.files.length) {
        errorElement.text('This field is required.').show();
        isValid = false;
      } else {
        const file = fileInput.files[0]; // Get the selected file
        if (file.size > maxFileSize) {
          errorElement.text('File size must not exceed 5MB.').show();
          isValid = false;
        } else {
          errorElement.hide();
        }
      }
    });

    if (!isValid) {
      e.preventDefault(); // Prevent form submission if validation fails
    }
  });

  // Add more file input fields dynamically
  $("#add-more").on("click", function () {
    fileInputCounter++;

    const newInput = `
        <div class="row mb-10">
          <div class="col">
            <input type="file" class="form-control image-input" name="images[]" accept="image/*">
            <span class="error" id="error-${fileInputCounter}">Please select a file.</span>
          </div>
          <div class="col">
            <button type="button" class="btn btn-danger remove-field">Remove</button>
          </div>
        </div>`;

    $(".input-container").append(newInput);
  });

  $(document).on('change', '.file-input', function () {
    const errorElement = $(this).siblings('.error');
    if ($(this).val()) {
      errorElement.hide();
    }
  });

  $(document).on('click', '.remove-field', function () {
    // Remove the closest parent row of the clicked button
    if($('.mb-10').length > 1){
      $(this).closest('.mb-10').remove();
    } else {
      alert('At least one image field is required.');
    }
    
  });
});