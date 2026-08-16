/**
 * CampusLynk Forms Module
 * Form validation, auto-complete, and async submit helpers.
 */
export const FormHandler = {
  validate(formElement) {
    return formElement ? formElement.checkValidity() : true;
  }
};
