<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.php'; // Подключение к базе данных

// Запрос для получения общей информации о стране
$locationId = 1; // ID страны
$stmt = $pdo->prepare("SELECT official_name, capital, area_km2, description, official_language, religion FROM Locations WHERE location_id = :location_id");
$stmt->execute(['location_id' => $locationId]);
$locationData = $stmt->fetch(PDO::FETCH_ASSOC);

// Запрос для получения крупных городов
$stmt = $pdo->prepare("SELECT name, photo FROM cities WHERE country_id = :country_id");
$stmt->execute(['country_id' => $locationId]);
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Запрос для получения герба
$stmt = $pdo->prepare("SELECT description, photo FROM coatsofarms WHERE country_id = :country_id");
$stmt->execute(['country_id' => $locationId]);
$coatsofarms = $stmt->fetch(PDO::FETCH_ASSOC);

// Запрос для получения флага
$stmt = $pdo->prepare("SELECT information, photo FROM flags WHERE country_id = :country_id");
$stmt->execute(['country_id' => $locationId]);
$flags = $stmt->fetch(PDO::FETCH_ASSOC);

// Запрос для получения исторических событий
$stmt = $pdo->prepare("SELECT event, description FROM history WHERE country_id = :country_id");
$stmt->execute(['country_id' => $locationId]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Запрос для получения исторических личностей
$stmt = $pdo->prepare("SELECT full_name, lifespan, information, photo FROM famouspeople WHERE country_id = :country_id AND type_id = 1");
$stmt->execute(['country_id' => $locationId]);
$historicalFigures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Запрос для получения деятелей искусства
$stmt = $pdo->prepare("SELECT full_name, lifespan, information, photo FROM famouspeople WHERE country_id = :country_id AND type_id = 2");
$stmt->execute(['country_id' => $locationId]);
$artists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Запрос для получения ученых и изобретателей
$stmt = $pdo->prepare("SELECT full_name, lifespan, information, photo FROM famouspeople WHERE country_id = :country_id AND type_id = 3");
$stmt->execute(['country_id' => $locationId]);
$scientists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Запрос для получения современных знаменитостей
$stmt = $pdo->prepare("SELECT full_name, lifespan, information, photo FROM famouspeople WHERE country_id = :country_id AND type_id = 4");
$stmt->execute(['country_id' => $locationId]);
$celebrities = $stmt->fetchAll(PDO::FETCH_ASSOC);

/////////////////////////

// Запрос для получения достопримечательностей
$stmt = $pdo->prepare("SELECT name, photo FROM Landmarks WHERE country_id = :country_id");
$stmt->execute(['country_id' => $locationId]);
$landmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Описание страны</title>
    <style>
        /* Ваши стили */
    </style>
</head>
<body>
    <div class="section">
        <h1 class="section-title">🌍 <?php echo htmlspecialchars($locationData['official_name']); ?></h1>
        <div class="image-gallery">
            <img src="/api/placeholder/400/300" alt="Пейзаж страны">
            <img src="/api/placeholder/400/300" alt="Столица">
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">📋 Общая информация</h2>
        <div class="info-grid">
            <div>
                <h3>Основные данные</h3>
                <table>
                    <tr>
                        <td>Официальное название</td>
                        <td><?php echo htmlspecialchars($locationData['official_name']); ?></td>
                    </tr>
                    <tr>
                        <td>Столица</td>
                        <td><?php echo htmlspecialchars($locationData['capital']); ?></td>
                    </tr>
                    <tr>
                        <td>Площадь</td>
                        <td><?php echo htmlspecialchars($locationData['area_km2']); ?> кв. км</td>
                    </tr>
                    <tr>
                        <td>Население</td>
                        <td><?php echo htmlspecialchars($locationData['description']); ?></td>
                    </tr>
                    <tr>
                        <td>Государственный язык</td>
                        <td><?php echo htmlspecialchars($locationData['official_language']); ?></td>
                    </tr>
                    <tr>
                        <td>Основная религия</td>
                        <td><?php echo htmlspecialchars($locationData['religion']); ?></td>
                    </tr>
                </table>
            </div>
            <div class="section">
                <h2 class="section-title">🏙️ Крупные города</h2>
                <div class="cities">
                    <?php if (!empty($cities['name'])): ?>
                        <img src="<?php echo htmlspecialchars($cities['photo']); ?>">
                        <p><strong><?php echo htmlspecialchars($cities['name']); ?></p>
                    <?php else: ?>
                        <p><strong>Информация о городах отсутствует.</strong></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <h2 class="section-title">🏛️ Государственные символы</h2>
        <div class="symbols">
            <?php if (!empty($flags)): ?>
                <img src="<?php echo htmlspecialchars($flags['photo']); ?>" alt="Флаг страны">
                <p><strong>Флаг:</strong> <?php echo htmlspecialchars($flags['information']); ?></p>
            <?php else: ?>
                <img src="/api/placeholder/400/300" alt="Флаг страны">
                <p><strong>Флаг:</strong> Информация о флаге отсутствует.</p>
            <?php endif; ?>
        </div>
        <div class="symbols">
            <?php if (!empty($coatofarms)): ?>
                <p><strong>Герб:</strong> <?php echo htmlspecialchars($coatofarms['description']); ?></p>
                <img src="<?php echo htmlspecialchars($coatsofarms['photo']); ?>" alt="Герб страны">
            <?php else: ?>
                <p><strong>Герб:</strong> Информация о гербе отсутствует.</p>
                <img src="/api/placeholder/400/300" alt="Герб страны">
            <?php endif; ?>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">📜 История</h2>
        <div class="history">
            <div>
                <h3>Основные даты и события</h3>
                <ul class="fact-list">
                    <?php if (!empty($coatofarms)): ?>
                        <?php foreach ($history as $event): ?>
                            <li>
                                <strong><?php echo htmlspecialchars($history['event']); ?></strong></p>
                                <?php echo htmlspecialchars($history['description']);?></strong></p>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><strong>События:</strong> Информация о событиях отсутствует.</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div>
            <h3>Исторические личности</h3>
            <ul class="fact-list">
                <?php if (!empty($historicalFigures)): ?>
                    <?php foreach ($historicalFigures as $figure): ?>
                        <li>
                            <?php if (!empty($figure['photo'])): ?>
                                <img src="data:image/png;base64,<?php echo base64_encode($figure['photo']); ?>" 
                                    alt="<?php echo htmlspecialchars($figure['full_name']); ?>" 
                                    class="historical-figure-photo">
                            <?php else: ?>
                                <img src="path/to/default/image.png" 
                                    alt="Изображение отсутствует" 
                                    class="historical-figure-photo">
                            <?php endif; ?>
                            <div class="historical-figure-info">
                                <strong><?php echo htmlspecialchars($figure['full_name']); ?></strong>
                                <p><strong>Годы жизни:</strong> <?php echo htmlspecialchars($figure['lifespan']); ?></p>
                                <p><?php echo htmlspecialchars($figure['information']); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Нет данных о исторических личностях.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">🎨 Деятели искусства</h2>
        <ul class="fact-list">
            <?php if (!empty($artists)): ?>
                <?php foreach ($artists as $art): ?>
                    <li>
                        <?php if (!empty($art['photo'])): ?>
                            <img src="data:image/png;base64,<?php echo base64_encode($art['photo']); ?>" 
                                alt="<?php echo htmlspecialchars($art['full_name']); ?>" 
                                class="artists-photo">
                        <?php else: ?>
                            <img src="path/to/default/image.png" 
                                alt="Изображение отсутствует" 
                                class="artists-photo">
                        <?php endif; ?>
                        <div class="artists-info">
                            <strong><?php echo htmlspecialchars($art['full_name']); ?></strong>
                            <p><strong>Годы жизни:</strong> <?php echo htmlspecialchars($art['lifespan']); ?></p>
                            <p><?php echo htmlspecialchars($art['information']); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Нет данных о деятелях искусства.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="section">
        <h2 class="section-title">🔬 Ученые и изобретатели</h2>
        <ul class="fact-list">
            <?php if (!empty($scientists)): ?>
                <?php foreach ($scientists as $sci): ?>
                    <li>
                        <?php if (!empty($sci['photo'])): ?>
                            <img src="data:image/png;base64,<?php echo base64_encode($sci['photo']); ?>" 
                                alt="<?php echo htmlspecialchars($sci['full_name']); ?>" 
                                class="scientists-photo">
                        <?php else: ?>
                            <img src="path/to/default/image.png" 
                                alt="Изображение отсутствует" 
                                class="scientists-photo">
                        <?php endif; ?>
                        <div class="scientists-info">
                            <strong><?php echo htmlspecialchars($sci['full_name']); ?></strong>
                            <p><strong>Годы жизни:</strong> <?php echo htmlspecialchars($sci['lifespan']); ?></p>
                            <p><?php echo htmlspecialchars($sci['information']); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Нет данных об ученых и изобретателях.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="section">
        <h2 class="section-title">🌟 Современные знаменитости</h2>
        <ul class="fact-list">
            <?php if (!empty($celebrities)): ?>
                <?php foreach ($celebrities as $celeb): ?>
                    <li>
                        <?php if (!empty($sci['photo'])): ?>
                            <img src="data:image/png;base64,<?php echo base64_encode($celeb['photo']); ?>" 
                                alt="<?php echo htmlspecialchars($celeb['full_name']); ?>" 
                                class="celebrities-photo">
                        <?php else: ?>
                            <img src="path/to/default/image.png" 
                                alt="Изображение отсутствует" 
                                class="celebrities-photo">
                        <?php endif; ?>
                        <div class="celebrities-info">
                            <strong><?php echo htmlspecialchars($celeb['full_name']); ?></strong>
                            <p><strong>Годы жизни:</strong> <?php echo htmlspecialchars($celeb['lifespan']); ?></p>
                            <p><?php echo htmlspecialchars($celeb['information']); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Нет данных о современных знаменитостях.</li>
            <?php endif; ?>
        </ul>
    </div>


    <!-- Остальные разделы (Государственные символы, История, Деятели искусства и т.д.) -->
</body>
</html>