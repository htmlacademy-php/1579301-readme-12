
    <main class="page__main page__main--adding-post">
      <div class="page__main-section">
        <div class="container">
          <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
          <div class="adding-post__tabs-wrapper tabs">
            <div class="adding-post__tabs filters">
              <ul class="adding-post__tabs-list filters__list tabs__list">

                <?php foreach ($contentType as $type) : ?>
                <li class="adding-post__tabs-item filters__item">
                  <?php if ($type['class_icon'] === 'post-photo') :?>
                  <a class="adding-post__tabs-link filters__button filters__button--photo <?= ($type['id'] == $id) ? 'filters__button--active tabs__item--active' : '' ?> tabs__item button" href="<?= '?id=' . $type['id'] ?>">
                    <svg class="filters__icon" width="22" height="18">
                      <use xlink:href="#icon-filter-photo"></use>
                    </svg>
                    <span>Фото</span>
                  </a>
                  <?php elseif ($type['class_icon'] === 'post-video') : ?>
                      <a class="adding-post__tabs-link filters__button filters__button--video <?= ($type['id'] == $id) ? 'filters__button--active tabs__item--active' : '' ?>tabs__item button" href="<?= '?id=' . $type['id'] ?>">
                          <svg class="filters__icon" width="24" height="16">
                              <use xlink:href="#icon-filter-video"></use>
                          </svg>
                          <span>Видео</span>
                      </a>
                  <?php elseif ($type['class_icon'] === 'post-text') : ?>
                      <a class="adding-post__tabs-link filters__button filters__button--text <?= ($type['id'] == $id) ? 'filters__button--active tabs__item--active' : '' ?>tabs__item button" href="<?= '?id=' . $type['id'] ?>">
                          <svg class="filters__icon" width="20" height="21">
                              <use xlink:href="#icon-filter-text"></use>
                          </svg>
                          <span>Текст</span>
                      </a>
                  <?php elseif ($type['class_icon'] === 'post-quote') : ?>
                      <a class="adding-post__tabs-link filters__button filters__button--quote <?= ($type['id'] == $id) ? 'filters__button--active tabs__item--active' : '' ?>tabs__item button" href="<?= '?id=' . $type['id'] ?>">
                          <svg class="filters__icon" width="21" height="20">
                              <use xlink:href="#icon-filter-quote"></use>
                          </svg>
                          <span>Цитата</span>
                      </a>
                  <?php elseif ($type['class_icon'] === 'post-link') : ?>
                      <a class="adding-post__tabs-link filters__button filters__button--link <?= ($type['id'] == $id) ? 'filters__button--active tabs__item--active' : '' ?>tabs__item button" href="<?= '?id=' . $type['id'] ?>">
                          <svg class="filters__icon" width="21" height="18">
                              <use xlink:href="#icon-filter-link"></use>
                          </svg>
                          <span>Ссылка</span>
                      </a>
                  <?php endif; ?>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <div class="adding-post__tab-content">
              <!-- Формы  -->
                <?= $formContent ?>
            </div>
          </div>
        </div>
      </div>
    </main>
