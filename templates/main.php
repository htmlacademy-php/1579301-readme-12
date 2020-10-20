<?php

function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

?>

<div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all filters__button--active" href="#">
                            <span>Все</span>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--photo button" href="#">
                            <span class="visually-hidden">Фото</span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-photo"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--video button" href="#">
                            <span class="visually-hidden">Видео</span>
                            <svg class="filters__icon" width="24" height="16">
                                <use xlink:href="#icon-filter-video"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--text button" href="#">
                            <span class="visually-hidden">Текст</span>
                            <svg class="filters__icon" width="20" height="21">
                                <use xlink:href="#icon-filter-text"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--quote button" href="#">
                            <span class="visually-hidden">Цитата</span>
                            <svg class="filters__icon" width="21" height="20">
                                <use xlink:href="#icon-filter-quote"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--link button" href="#">
                            <span class="visually-hidden">Ссылка</span>
                            <svg class="filters__icon" width="21" height="18">
                                <use xlink:href="#icon-filter-link"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach ($cards as $i => $card) : ?>
            <article class="popular__post <?= $card['type'] ?> post">
                <header class="post__header">
                    <h2><?= htmlspecialchars($card['header']) ?></h2>
                </header>
                <div class="post__main">
                    <?php if ($card['type'] === 'post-quote') : ?>
                        <blockquote>
                            <p>
                                <?= (mb_strlen(cutText($card['content'])) < mb_strlen($card['content'])) ? htmlspecialchars(cutText($card['content'])) . '...' . '<a class="post-text__more-link" href="#">Читать далее</a>' : htmlspecialchars(cutText($card['content'])) ?>
                            </p>
                            <cite>Неизвестный Автор</cite>
                        </blockquote>
                    <?php  elseif ($card['type'] === 'post-text') : ?>
                        <p><?= (mb_strlen(cutText($card['content'])) < mb_strlen($card['content'])) ? htmlspecialchars(cutText($card['content'])) . '...' . '<a class="post-text__more-link" href="#">Читать далее</a>' : htmlspecialchars(cutText($card['content'])) ?></p>
                    <?php  elseif ($card['type'] === 'post-photo') : ?>
                        <div class="post-photo__image-wrapper">
                            <img src="img/<?= htmlspecialchars($card['content']) ?>" alt="Фото от пользователя" width="360" height="240">
                        </div>
                    <?php  elseif ($card['type'] === 'post-link') : ?>
                        <div class="post-link__wrapper">
                            <a class="post-link__external" href="http://" title="Перейти по ссылке">
                                <div class="post-link__info-wrapper">
                                    <div class="post-link__icon-wrapper">
                                        <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><?= $card['header'] ?>></h3>
                                    </div>
                                </div>
                                <span><?= (mb_strlen(cutText($card['content'])) < mb_strlen($card['content'])) ? htmlspecialchars(cutText($card['content'])) . '...' . '<a class="post-text__more-link" href="#">Читать далее</a>' : htmlspecialchars(cutText($card['content'])) ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <footer class="post__footer">
                    <div class="post__author">
                        <a class="post__author-link" href="#" title="Автор">
                            <div class="post__avatar-wrapper">
                                <!--укажите путь к файлу аватара-->
                                <img class="post__author-avatar" src="img/<?= htmlspecialchars($card['userPic']) ?>" alt="Аватар пользователя">
                            </div>
                            <div class="post__info">
                                <b class="post__author-name"><?= htmlspecialchars($card['userName']) ?></b>
                                <?php
                                $currentTime = time();
                                $randomDate = generate_random_date($i);
                                $postTime = strtotime($randomDate);
                                $relativeTimeSec = $currentTime - $postTime;
                                $relativeTimeMin = $relativeTimeSec / 60;
                                ?>
                                <time class="post__time" datetime="<?= $randomDate ?>">
                                    <?php
                                        if ($relativeTimeMin < 60) : echo $relativeTimeMin . get_noun_plural_form($relativeTimeMin, ' минута', ' минуты', ' минут') . ' назад';
                                            elseif (60 <= $relativeTimeMin && $relativeTimeMin < 1440) : echo $relativeTimeMin / 60 . get_noun_plural_form($relativeTimeMin / 60, ' час', ' часа', ' часов') . ' назад';
                                            elseif (1440 <= $relativeTimeMin && $relativeTimeMin < 34560) : echo $relativeTimeMin / 1440 . get_noun_plural_form($relativeTimeMin / 1440, ' день', ' дня', ' дней') . ' назад';
                                            elseif (34560 <= $relativeTimeMin && $relativeTimeMin < 172800) : echo floor($relativeTimeMin / 34560) . get_noun_plural_form($relativeTimeMin / 34560, ' неделю', ' недели', ' недель') . ' назад';
                                            elseif (172800 <= $relativeTimeMin) : echo floor($relativeTimeMin / 172800) . get_noun_plural_form($relativeTimeMin / 172800, ' месяц', ' месяца', ' месяцев') . ' назад';
                                        endif;
                                    ?>
                                </time>
                            </div>
                        </a>
                    </div>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span>0</span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span>0</span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                        </div>
                    </div>
                </footer>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
