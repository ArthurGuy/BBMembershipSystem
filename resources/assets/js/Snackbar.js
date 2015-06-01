class Snackbar {

    constructor() {


        var $ = require('jquery');


        console.log("Snackbar Loading");


        //Replace the fixed html in the flash-message include
        //trigger this js directly somehow and then remove the old timeout



        BB.SnackBar = {};

        BB.SnackBar = {

            onScreen: false,
            elementCreated: false,
            snackBarDiv: false,
            timeoutID: null,

            displayMessage: function(message, details) {

                if (!this.elementCreated) {
                    this.createElement();
                }

                if (this.onScreen) {
                    //remove the old one
                    this.remove();
                }

                //Add the content to the div
                var snackBarContent = document.createElement("div");
                snackBarContent.innerHTML = message;
                this.snackBarDiv.innerHTML = '';
                this.snackBarDiv.appendChild(snackBarContent);

                this.display();

                var self = this;
                this.timeoutID = setTimeout(function () {
                    self.remove();
                }, 3000);
            },
            displayMessages: function(messages) {
                var message = '<ul>';
                for (var i = 0; i < messages.length; i++) {
                    message += '<li>'+messages[i]+'</li>';
                }
                message += '</ul>';

                this.displayMessage(message);
            },
            display: function() {
                //Remove the hidden class
                this.snackBarDiv.className = 'snackBar';

                this.onScreen = true;
            },
            remove: function() {
                window.clearTimeout(this.timeoutID);

                this.snackBarDiv.className = this.snackBarDiv.className + ' snackBarHidden';

                this.onScreen = false;
            },
            createElement: function() {
                //Create the snackbar div
                this.snackBarDiv = document.createElement("div");
                this.snackBarDiv.className = 'snackBar snackBarHidden';

                //Add the div to the page
                document.body.appendChild(this.snackBarDiv);

                this.elementCreated = true;
            },
            boot: function() {
                this.createElement();
            }
        }
        BB.SnackBar.boot();

        //Fetch any existing messages from the dom
        $(document).ready(function() {
            var message = $('#snackbarMessage').val();
            var level = $('#snackbarLevel').val();
            var messages = $('#snackbarMessages').val();
            if (messages) {
                messages = JSON.parse(messages);
            }

            if (level) {
                if (messages) {
                    BB.SnackBar.displayMessages(messages);
                } else {
                    BB.SnackBar.displayMessage(message);
                }
            }
        });

        console.log("Snackbar Loaded");

    }

}

export default Snackbar;