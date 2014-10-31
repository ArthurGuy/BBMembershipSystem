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
        var snackBarContent = document.createTextNode(message);
        this.snackBarDiv.innerHTML = '';
        this.snackBarDiv.appendChild(snackBarContent);

        this.display();

        var self = this;
        this.timeoutID = setTimeout(function () {
            self.remove();
        }, 3000);
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
