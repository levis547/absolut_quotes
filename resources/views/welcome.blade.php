<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AbsolutQuotes</title>
    <meta name="description" content="AbsolutQuotes | Absolut Production">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'DmitrievaSP';
            src: url('{{ asset('fonts/DmitrievaSP.otf') }}') format('opentype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: black;
            color: white;
            font-family: 'DmitrievaSP', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden; /* Чтобы избежать полос прокрутки */
        }

        #quote-container {
            font-size: 2em;
            text-align: center;
            width: 80%;
            max-width: 1000px;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            #quote-container {
                font-size: 1.5em; /* Уменьшаем размер шрифта для мобильных устройств */
                line-height: 1.5;
            }
        }

        @media (max-width: 480px) {
            #quote-container {
                font-size: 1.2em; /* Еще меньше размер для маленьких экранов */
                line-height: 1.4;
            }
        }
        .cursor {
            display: inline-block;
            margin-left: 2px;
            width: 1px;
            background-color: black;
        }

        .blink {
            animation: blink 1s step-start infinite;
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
        }
    </style>
</head>
<body>
<div id="quote-container">
    <span id="quote-text"></span><span class="cursor">|</span>
</div>

<script>
    const quotes = @json($quotes->toArray());
    const container = document.getElementById('quote-container');
    const textElement = document.getElementById('quote-text');
    const cursor = document.querySelector('.cursor');

    const activeQuotes = quotes.filter(q => q.status === 1);

    function stripTags(html) {
        const tmp = document.createElement("div");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    }

    function getRandomQuote() {
        const index = Math.floor(Math.random() * activeQuotes.length);
        return stripTags(activeQuotes[index].text);
    }

    function startBlink() {
        cursor.classList.add('blink');
    }

    function stopBlink() {
        cursor.classList.remove('blink');
    }

    async function typeWriterEffect(text, speed = 3000) {
        stopBlink();
        textElement.innerHTML = '';
        for (let i = 0; i < text.length; i++) {
            textElement.innerHTML += text[i];
            await new Promise(resolve => setTimeout(resolve, speed));
        }
    }

    async function deleteTextEffect(speed = 2000) {
        stopBlink();
        while (textElement.innerHTML.length > 0) {
            textElement.innerHTML = textElement.innerHTML.slice(0, -1);
            await new Promise(resolve => setTimeout(resolve, speed));
        }
    }

    async function runQuoteLoop() {
        while (true) {
            const quote = getRandomQuote();
            await typeWriterEffect(quote, 100);
            startBlink();
            await new Promise(resolve => setTimeout(resolve, 5000));
            await deleteTextEffect(80);
            startBlink();
            await new Promise(resolve => setTimeout(resolve, 1000));
        }
    }

    if (activeQuotes.length > 0) {
        runQuoteLoop();
    } else {
        textElement.innerText = 'Нет доступных цитат.';
    }
</script>
</body>
</html>
