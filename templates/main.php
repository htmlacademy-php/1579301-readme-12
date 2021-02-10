<?php
var_export($criteria);

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
                        <a class="sorting__link <?= ($criteria['sort']['type'] == 'popularity') ? 'sorting__link--active' : ''?>" href="/?sort=popularity&order=<?= $criteria['sort']['order'] ?><?= ($criteria['contentTypeId'] ? '&id='.$criteria['contentTypeId'] : '') ?>">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link <?= ($criteria['sort']['type'] == 'like') ? 'sorting__link--active' : ''?>" href="/?sort=like&order=<?= $criteria['sort']['order'] ?><?= ($criteria['contentTypeId'] ? '&id='.$criteria['contentTypeId'] : '') ?>">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link <?= ($criteria['sort']['type'] == 'date') ? 'sorting__link--active' : ''?>" href="/?sort=date&order=<?= $criteria['sort']['order'] ?><?= ($criteria['contentTypeId'] ? '&id='.$criteria['contentTypeId'] : '') ?>">
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
                        <a class="filters__button filters__button--ellipse filters__button--all <?= empty($criteria['contentTypeId']) ? 'filters__button--active' : ''?>" href="/">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach ($contentType as $type) : ?>
                    <li class="popular__filters-item filters__item">
                        <?php if ($type['class_icon'] === 'post-photo') : ?>
                        <a class="filters__button filters__button--photo button <?= ($type['id'] == $criteria['contentTypeId']) ? 'filters__button--active' : '' ?>" href="<?= '/?id=' . $type['id'] ?>">
                            <span class="visually-hidden">Фото</span>
                            <svg class="filters__icon" width="<?= $type['width_icon'] ?>" height="<?= $type['height_icon'] ?>">
                                <use xlink:href="#icon-filter-photo"></use>
                            </svg>
                        </a>
                        <?php elseif ($type['class_icon'] === 'post-video') : ?>
                            <a class="filters__button filters__button--video button <?= ($type['id'] == $criteria['contentTypeId']) ? 'filters__button--active' : '' ?>" href="<?= '/?id=' . $type['id'] ?>">
                                <span class="visually-hidden">Видео</span>
                                <svg class="filters__icon" width="<?= $type['width_icon'] ?>" height="<?= $type['height_icon'] ?>">
                                    <use xlink:href="#icon-filter-video"></use>
                                </svg>
                            </a>
                        <?php elseif ($type['class_icon'] === 'post-text') : ?>
                            <a class="filters__button filters__button--text button <?= ($type['id'] == $criteria['contentTypeId']) ? 'filters__button--active' : '' ?>" href="<?= '/?id=' . $type['id'] ?>">
                                <span class="visually-hidden">Текст</span>
                                <svg class="filters__icon" width="<?= $type['width_icon'] ?>" height="<?= $type['height_icon'] ?>">
                                    <use xlink:href="#icon-filter-text"></use>
                                </svg>
                            </a>
                        <?php elseif ($type['class_icon'] === 'post-quote') : ?>
                            <a class="filters__button filters__button--quote button <?= ($type['id'] == $criteria['contentTypeId']) ? 'filters__button--active' : '' ?>" href="<?= '/?id=' . $type['id'] ?>">
                                <span class="visually-hidden">Цитата</span>
                                 <svg class="filters__icon" width="<?= $type['width_icon'] ?>" height="<?= $type['height_icon'] ?>">
                                     <use xlink:href="#icon-filter-quote"></use>
                                 </svg>
                            </a>
                        <?php elseif ($type['class_icon'] === 'post-link') : ?>
                            <a class="filters__button filters__button--link button <?= ($type['id'] == $criteria['contentTypeId']) ? 'filters__button--active' : '' ?>" href="<?= '/?id=' . $type['id'] ?>">
                                <span class="visually-hidden">Ссылка</span>
                                <svg class="filters__icon" width="<?= $type['width_icon'] ?>" height="<?= $type['height_icon'] ?>">
                                    <use xlink:href="#icon-filter-link"></use>
                                </svg>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach ($posts as $post) : ?>
            <article class="popular__post <?= $post['class_icon'] ?> post">
                <header class="post__header">
                    <a href="/post.php?id=<?= $post['id'] ?>"><h2><?= htmlspecialchars($post['header']) ?></h2></a>
                </header>
                <div class="post__main">
                    <?php if ($post['class_icon'] === 'post-quote') : ?>
                        <blockquote>
                            <p>
                                <?= (mb_strlen(cutText($post['content'])) < mb_strlen($post['content'])) ? htmlspecialchars(cutText($post['content'])) . '...' . '<a class="post-text__more-link" href="#">Читать далее</a>' : htmlspecialchars(cutText($post['content'])) ?>
                            </p>
                            <cite>Неизвестный Автор</cite>
                        </blockquote>
                    <?php  elseif ($post['class_icon'] === 'post-text') : ?>
                        <p><?= (mb_strlen(cutText($post['content'])) < mb_strlen($post['content'])) ? htmlspecialchars(cutText($post['content'])) . '...' . '<a class="post-text__more-link" href="#">Читать далее</a>' : htmlspecialchars(cutText($post['content'])) ?></p>
                    <?php  elseif ($post['class_icon'] === 'post-photo') : ?>
                        <div class="post-photo__image-wrapper">
                            <img src="img/<?= htmlspecialchars($post['picture']) ?>" alt="Фото от пользователя" width="360" height="240">
                        </div>
                    <?php  elseif ($post['class_icon'] === 'post-link') : ?>
                        <div class="post-link__wrapper">
                            <a class="post-link__external" href="http://" title="Перейти по ссылке">
                                <div class="post-link__info-wrapper">
                                    <div class="post-link__icon-wrapper">
                                        <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><?= $post['header'] ?></h3>
                                    </div>
                                </div>
                                <span><?= (mb_strlen(cutText($post['link'])) < mb_strlen($post['link'])) ? htmlspecialchars(cutText($post['link'])) . '...' . '<a class="post-text__more-link" href="#">Читать далее</a>' : htmlspecialchars(cutText($post['link'])) ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <footer class="post__footer">
                    <div class="post__author">
                        <a class="post__author-link" href="#" title="Автор">
                            <div class="post__avatar-wrapper">
                                <!--укажите путь к файлу аватара-->
                                <img class="post__author-avatar" src="img/<?= htmlspecialchars($post['avatar']) ?>" alt="Аватар пользователя">
                            </div>
                            <div class="post__info">
                                <b class="post__author-name"><?= htmlspecialchars($post['login']) ?></b>
                                <time class="post__time" datetime="<?= $post['create_time'] ?>">
                                    <?php
                                        echo timePassedAfterPublication($post['create_time']);
                                    ?>
                                </time>
                            </div>
                        </a>
                    </div>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="/templates/do.php?action=like&id=<?= $post['id'] ?>" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span><?= $post['likes_count'] ?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?= $post['comments_count'] ?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                        </div>
                    </div>
                </footer>
            </article>
            <?php endforeach; ?>
        </div>
        <div class="popular__page-links">
            <?php if ($criteria['pagination']['totalPosts'] > 6 && ($criteria['pagination']['currentPage'] > 1) && ($criteria['pagination']['currentPage'] != ($criteria['pagination']['totalPages']))) : ?>
                <a class="popular__page-link popular__page-link--prev button button--gray" href="?page=<?= $criteria['pagination']['currentPage'] - 1 ?><?=($criteria['contentTypeId'] ? '&id='.$criteria['contentTypeId'] : '')?>">Предыдущая страница</a>
                <a class="popular__page-link popular__page-link--next button button--gray" href="?page=<?= $criteria['pagination']['currentPage'] + 1 ?><?=($criteria['contentTypeId'] ? '&id='.$criteria['contentTypeId'] : '')?>">Следующая страница</a>
            <?php elseif (($criteria['pagination']['totalPosts'] > 6) && ($criteria['pagination']['currentPage'] == 1)) :?>
                <a class="" href="?page=<?= $criteria['pagination']['currentPage'] - 1 ?><?=($criteria['contentTypeId'] ? '&id='.$criteria['contentTypeId'] : '')?>"></a>
                <a class="popular__page-link popular__page-link--next button button--gray" href="?page=<?= $criteria['pagination']['currentPage'] + 1 ?><?=($criteria['contentTypeId'] ? '&id='.$criteria['contentTypeId'] : '')?>">Следующая страница</a>
            <?php elseif ($criteria['pagination']['totalPosts'] > 6 && $criteria['pagination']['currentPage'] == $criteria['pagination']['totalPages']) : ?>
                <a class="popular__page-link popular__page-link--prev button button--gray" href="?page=<?= $criteria['pagination']['currentPage'] - 1 ?><?=($criteria['contentTypeId'] ? '&id='.$criteria['contentTypeId'] : '')?>">Предыдущая страница</a>
                <a class="" href="?page=<?= $criteria['pagination']['currentPage'] + 1 ?><?=($criteria['contentTypeId'] ? '&id='.$criteria['contentTypeId'] : '')?>"></a>
            <?php endif ?>
        </div>
    </div>
