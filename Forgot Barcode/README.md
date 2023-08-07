Using PHP and jQuery, this webpage will attempt to send a user their barcode by querying the Sierra API.

It is necessary to use encoded Sierra API credentials. Please refer to Sierra documentation "Getting Started with the Sierra API" for more information.

The user will receive one of three messages based on the status responses from the Sierra API. Individualized messages can be sent for each status.

- The barcode will be sent to the specified email address if that email address is associated with only one barcode.

- Status 404: The barcode was not found. Sierra sends this status if the user's email address doesn't exist. It will also be sent in the instance that the user's email address does exist, but the user has multiple addresses in their email field.

- Status 409: This status will be sent if multiple patrons are found for the specified email address.

Triple Xs (xxx) appear before and after text (such as location and phone number) that should be customized. 

Comments have been placed throughout to assist in updating the code with library-specific information such as Sierra API credentials and catalog URLs.

Use at your own risk. Not supported by the Toledo Lucas County Public Library.
