if ("speechSynthesis" in window) {
    readEntry = function (id) {
        var utterance,
            entryTitle = jQuery("#post_" + id + " h2:first-child").text(),
            entryBody = jQuery("#post_" + id + " .content").text(),
            textToRead = entryTitle + entryBody;
        utterance = new SpeechSynthesisUtterance(textToRead);
        utterance.lang = "de-DE";
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(utterance);
    };
    getVoices = function () {
        return new Promise(function (resolve, reject) {
            window.speechSynthesis.onvoiceschanged = function (e) {
                var voices = this.getVoices();
                if (voices.length > 0) {
                    resolve(voices); // Array with Voices
                } else {
                    reject(voices); // Empty Array
                }
            };
            window.speechSynthesis.getVoices();
        });
    };
    getVoices().then(function () {
        var buttonDiv = jQuery(".speech-button"),
            label = buttonDiv.text(),
            button = jQuery("<button>");
        buttonDiv.text("");
        button.appendTo(buttonDiv);
        button.text(label);
        button.on("click", function () {
            console.log("Requested speech output.");
            readEntry(entryId)
        });
    }, function () {
        console.log("Voices rejected.");
    });
}
