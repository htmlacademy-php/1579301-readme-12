<section class="adding-post__link tabs__content tabs__content--active">
    <h2 class="visually-hidden">Форма добавления ссылки</h2>
    <form class="adding-post__form form" action="add.php<?= ($id) ? '?id='.$id : '' ?>" method="post">
        <div class="form__text-inputs-wrapper">
            <div class="form__text-inputs">
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="link-heading">Заголовок <span class="form__input-required">*</span></label>
                    <div class="form__input-section <?= isset($errors['header']) ? 'form__input-section--error' : '' ?>">
                        <input class="adding-post__input form__input" id="link-heading" type="text" name="header" value="<?= $data['header'] ?? '' ?>"placeholder="Введите заголовок">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc"><?= $errors['header'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="adding-post__textarea-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
                    <div class="form__input-section <?= isset($errors['link']) ? 'form__input-section--error' : '' ?>">
                        <input class="adding-post__input form__input" id="post-link" type="text" name="link">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc"><?= $errors['link'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="link-tags">Теги</label>
                    <div class="form__input-section">
                        <input class="adding-post__input form__input" id="link-tags" type="text" name="hashtag" placeholder="Введите тег">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!empty($errors)) : ?>
                <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">
                        <?php foreach ($errors as $key => $error) : ?>
                            <li class="form__invalid-item"><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>
        </div>
        <div class="adding-post__buttons">
            <button class="adding-post__submit button button--main" type="submit" name="submit" value="5">Опубликовать</button>
            <a class="adding-post__close" href="#">Закрыть</a>
        </div>
    </form>
</section>
