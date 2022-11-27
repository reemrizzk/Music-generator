audioCtx = new (window.AudioContext || window.webkitAudioContext)();

function beep(volume, frequency, type, duration) {
    var oscillator = audioCtx.createOscillator();
    var gainNode = audioCtx.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(audioCtx.destination);

    gainNode.gain.value = volume;
    oscillator.frequency.value = frequency;
    oscillator.type = type;

    oscillator.start();

    setTimeout(function () {
        oscillator.stop();
    }, duration);
}
/*
Source: https://stackoverflow.com/questions/879152/how-do-i-make-javascript-beep
Answer by https://stackoverflow.com/users/2698948/houshalter on April 15, 2015
and edited by https://stackoverflow.com/users/5632412/bimo on April 17, 2022
*/
