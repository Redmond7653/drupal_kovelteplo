(function (Drupal, $) {
  Drupal.behaviors.InputValidation = {
    attach: function (context, settings) {

      const $inputField = $('.form-item-message .form-text', context);
      const $form = $('.ktpersonal-ktpersonal', context);

      // Checking input

      $form.on('submit', function () {
        debugger;
        const value = $inputField.val();
        const part = value.split('/');
        const test = 456;

        if ((part.length === 2 && !isNaN(part[0]) && !isNaN(part[1])) || part[0] == '' )  {
            console.log('Правильно');
          // document.querySelector(".form-item-message + .wrong-user-input-message").classList.remove("show");
          // $2(".form-item-message + .wrong-user-input-message").classList.remove("show");

          $(".form-item-message + .wrong-user-input-message").removeClass('show');

        } else {
          // document.querySelector(".form-item-message + .wrong-user-input-message").classList.add("show");

          $(".form-item-message + .wrong-user-input-message").addClass('show');
          return false;
          // $("#someid").addClass('show');
        }



      });
    }
  };
})(Drupal, jQuery);

function $2(selector) {
  return document.querySelector(selector);
}
