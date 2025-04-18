<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AbsolutQuotes</title>
    <meta name="description" content="AbsolutQuotes | Absolut Production">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            overflow: hidden;
            transition: background-color 0.5s, color 0.5s;
        }

        body.light-mode {
            background-color: white;
            color: black;
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
                font-size: 1.5em;
                line-height: 1.5;
            }
        }

        @media (max-width: 480px) {
            #quote-container {
                font-size: 1.2em;
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

        /* Стили для переключателя */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border: 1px solid white;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: rgba(255, 255, 255, 1);
        }

        .theme-toggle i {
            font-size: 20px;
            transition: color 0.3s ease;

        }
        .theme-toggle:hover i{
            transition: color 0.3s ease;
            color: black;
        }
        body.light-mode .theme-toggle i {
            color: black;
            transition: .3s;
        }
        body.light-mode .theme-toggle{
            border: solid 1px black;
            transition: .3s;
        }
        body.light-mode .theme-toggle:hover{
           background: black;
            transition: .3s;
        }
        body.light-mode .theme-toggle:hover i{
           color: white;
            transition: .3s;
        }

        body.light-mode .cursor{
            background: white;
        }
    </style>
</head>
<body>
<div id="quote-container">
    <span id="quote-text"></span><span class="cursor">|</span>
</div>

<!-- Переключатель темы -->
<div class="theme-toggle" id="theme-toggle">
    <i class="fa fa-moon"></i> <!-- Иконка для темной темы -->
</div>

<script>
    const quotes = @json($quotes->toArray());

    const container = document.getElementById('quote-container');
    const textElement = document.getElementById('quote-text');
    const cursor = document.querySelector('.cursor');
    const themeToggle = document.getElementById('theme-toggle');

    const activeQuotes = quotes.filter(q => q.status === 1);

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
        return stripTags(quote.text);
    }

    // Функция для эффекта печати текста
    async function typeWriterEffect(text, speed = 3000) {
        stopBlink();
        textElement.innerHTML = '';
        for (let i = 0; i < text.length; i++) {
            textElement.innerHTML += text[i];
            await new Promise(resolve => setTimeout(resolve, speed));
        }
    }

    // Функция для эффекта удаления текста
    async function deleteTextEffect(speed = 2000) {
        stopBlink();
        while (textElement.innerHTML.length > 0) {
            textElement.innerHTML = textElement.innerHTML.slice(0, -1);
            await new Promise(resolve => setTimeout(resolve, speed));
        }
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
        let remainingQuotes = [...activeQuotes];  // Делаем копию массива цитат для текущего круга

        while (remainingQuotes.length > 0) {
            const quote = getRandomQuote(remainingQuotes);

            // Печатаем цитату
            await typeWriterEffect(quote, 100);

            // Задержка для мигания курсора перед удалением
            await new Promise(resolve => setTimeout(resolve, 500));
            startBlink();

            // Делаем паузу перед удалением
            await new Promise(resolve => setTimeout(resolve, 5000));

            // Удаляем цитату
            await deleteTextEffect(80);

            // Задержка перед повторным началом цикла
            await new Promise(resolve => setTimeout(resolve, 1000));
            startBlink();
        }

        remainingQuotes = [...activeQuotes];  // Обновляем массив для нового круга

        // Задержка перед запуском нового круга
        await new Promise(resolve => setTimeout(resolve, 1000));
        runQuoteLoop();  // Запускаем цикл заново с перемешанными цитатами
    }

    // Если есть активные цитаты, запускаем цикл, иначе выводим сообщение
    if (activeQuotes.length > 0) {
        runQuoteLoop();
    } else {
        textElement.innerText = 'Нет доступных цитат.';
    }

    // Переключение темы и сохранение в LocalStorage
    document.addEventListener('DOMContentLoaded', () => {
        // Проверяем, есть ли сохраненная тема в LocalStorage
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') {
            document.body.classList.add('light-mode');
            const icon = themeToggle.querySelector('i');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    });

    // Переключение темы и сохранение в LocalStorage
    themeToggle.addEventListener('click', () => {
        const currentTheme = document.body.classList.contains('light-mode') ? 'light' : 'dark';

        // Переключаем тему
        document.body.classList.toggle('light-mode');

        // Меняем иконку
        const icon = themeToggle.querySelector('i');
        icon.classList.toggle('fa-moon');
        icon.classList.toggle('fa-sun');

        // Сохраняем тему в LocalStorage
        if (currentTheme === 'light') {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });

    // Обработчик ошибок
    window.onerror = function (msg, url, lineNo, columnNo, error) {
        console.error(`Произошла ошибка: ${msg} в ${url}:${lineNo}:${columnNo}`);
        return false; // Блокирует стандартное поведение ошибки
    };

</script>
</body>
</html>
