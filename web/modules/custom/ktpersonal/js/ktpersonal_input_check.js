(function (Drupal, $) {
  Drupal.behaviors.InputValidation = {
    attach: function (context, settings) {
      debugger
      const $inputField = $('.form-item-message .form-text', context);

      // Checking input

      $inputField.on('blur change mouseleave', function () {
        const value = $(this).val();
        const part = value.split('/');
        const test = 456;

        if (part.length === 2 && !isNaN(part[0]) && !isNaN(part[1])) {
            console.log('Правильно');
            alert('Правильно');
        }
      });
    }
  };
})(Drupal, jQuery);

