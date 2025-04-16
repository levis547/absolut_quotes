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
    console.log('Цитаты загружены:', quotes); // Логируем загруженные цитаты

    const container = document.getElementById('quote-container');
    const textElement = document.getElementById('quote-text');
    const cursor = document.querySelector('.cursor');

    const activeQuotes = quotes.filter(q => q.status === 1);
    console.log('Активные цитаты:', activeQuotes); // Логируем активные цитаты

    // Функция для удаления HTML-тегов из строки
    function stripTags(html) {
        const tmp = document.createElement("div");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    }

    // Функция для случайного выбора цитаты и удаления ее из массива
    function getRandomQuote(remainingQuotes) {
        const index = Math.floor(Math.random() * remainingQuotes.length);
        const quote = remainingQuotes.splice(index, 1)[0];  // Убираем выбранную цитату из массива
        console.log('Выбрана цитата:', quote); // Логируем выбранную цитату
        return stripTags(quote.text);
    }

    // Функция задержки
    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // Функция для эффекта печати текста
    async function typeWriterEffect(text, speed = 3000) {
        stopBlink();
        textElement.innerHTML = '';
        console.log('Начало печати текста');
        for (let i = 0; i < text.length; i++) {
            textElement.innerHTML += text[i];
            await new Promise(resolve => setTimeout(resolve, speed));
        }
        console.log('Печать завершена');
        await delay(1000); // Задержка перед следующей операцией
    }

    // Функция для эффекта удаления текста
    async function deleteTextEffect(speed = 2000) {
        stopBlink();
        console.log('Начало удаления текста');
        while (textElement.innerHTML.length > 0) {
            textElement.innerHTML = textElement.innerHTML.slice(0, -1);
            await new Promise(resolve => setTimeout(resolve, speed));
        }
        console.log('Удаление завершено');
        await delay(1000); // Задержка перед следующей операцией
    }

    // Функция для включения мигания курсора
    function startBlink() {
        cursor.classList.add('blink');
    }

    // Функция для остановки мигания курсора
    function stopBlink() {
        cursor.classList.remove('blink');
    }

    // Функция для запуска цикла с цитатами
    async function runQuoteLoop() {
        console.log('Цикл цитат запущен');
        let remainingQuotes = [...activeQuotes];  // Делаем копию массива цитат для текущего круга

        while (remainingQuotes.length > 0) {
            const quote = getRandomQuote(remainingQuotes);
            console.log('Выбрана цитата:', quote);

            // Печатаем цитату
            await typeWriterEffect(quote, 100);

            // Задержка перед миганием курсора
            await delay(1000);

            // Задержка для мигания курсора перед удалением
            await new Promise(resolve => setTimeout(resolve, 500));
            startBlink();

            // Задержка перед удалением
            await delay(1000);

            // Удаляем цитату
            await deleteTextEffect(80);

            // Задержка перед повторным началом цикла
            await delay(1000);
            startBlink();
        }

        console.log('Все цитаты показаны, начинаем новый круг');
        remainingQuotes = [...activeQuotes];  // Обновляем массив для нового круга
        console.log('Перемешиваем цитаты для нового круга', remainingQuotes);

        // Задержка перед запуском нового круга
        await delay(1000);
        runQuoteLoop();  // Запускаем цикл заново с перемешанными цитатами
    }

    // Если есть активные цитаты, запускаем цикл, иначе выводим сообщение
    if (activeQuotes.length > 0) {
        runQuoteLoop();
    } else {
        textElement.innerText = 'Нет доступных цитат.';
        console.log('Не найдено активных цитат');
    }

    // Обработчик ошибок
    window.onerror = function (msg, url, lineNo, columnNo, error) {
        console.error(`Произошла ошибка: ${msg} в ${url}:${lineNo}:${columnNo}`);
        return false; // Блокирует стандартное поведение ошибки
    };

</script>
</body>
</html>
