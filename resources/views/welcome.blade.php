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
    </style>
</head>
<body>
<div id="quote-container"></div>

<script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script>
    var quotes = @json($quotes); // Передаем цитаты в JavaScript

    // Функция для перемешивания массива цитат
    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]]; // Меняем местами элементы
        }
    }

    // Перемешиваем цитаты
    shuffle(quotes);

    var index = 0;

    function nextQuote() {
        // Создаем новый экземпляр Typed для каждой цитаты
        var typed = new Typed("#quote-container", {
            strings: [quotes[index].text], // Используем текст цитаты
            typeSpeed: 100,
            backSpeed: 50,
            backDelay: 3000,
            startDelay: 500,
            showCursor: false,
            onComplete: function() {
                setTimeout(() => {
                    index = (index + 1) % quotes.length;  // Переход к следующей цитате
                    typed.destroy();  // Удаляем текущий экземпляр Typed
                    nextQuote();  // Запускаем процесс для следующей цитаты
                }, 1000);  // Задержка перед выводом следующей цитаты
            }
        });
    }

    nextQuote();  // Запуск функции при загрузке страницы
</script>
</body>
</html>
