<section class="adding-post__text tabs__content <?= ($form_type == 'text') ? 'tabs__content--active' : '' ?>">
    <h2 class="visually-hidden">Форма добавления текста</h2>
    <form class="adding-post__form form" action="add.php" method="post">
        <input type="hidden" id="form-type" name="form-type" value="text">
        <div class="form__text-inputs-wrapper">
            <div class="form__text-inputs">
            <div class="adding-post__input-wrapper form__input-wrapper">
                <label class="adding-post__label form__label" for="text-heading">Заголовок <span class="form__input-required">*</span></label>
                <div class="form__input-section <?= !empty($form_errors['heading']) ? 'form__input-section--error' : '' ?>">
                <input class="adding-post__input form__input" id="text-heading" type="text" name="heading" placeholder="Введите заголовок" value=<?= $form_values['heading'] ?? '' ?>>
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка</h3>
                    <p class="form__error-desc"><?= $form_errors['heading'] ?? '' ?></p>
                </div>
            </div>
        </div>
        <div class="adding-post__textarea-wrapper form__textarea-wrapper">
            <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
            <div class="form__input-section <?= !empty($form_errors['content']) ? 'form__input-section--error' : '' ?>">
            <textarea class="adding-post__textarea form__textarea form__input" id="post-text" placeholder="Введите текст публикации" name="content"><?= $form_values['content'] ?? '' ?></textarea>
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
                <h3 class="form__error-title">Ошибка</h3>
                <p class="form__error-desc"> <?= $form_errors['content'] ?? '' ?> </p>
            </div>
            </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
            <label class="adding-post__label form__label" for="post-tags">Теги</label>
            <div class="form__input-section  <?= !empty($form_errors['tags']) ? 'form__input-section--error' : '' ?>">
            <input class="adding-post__input form__input" id="post-tags" type="text" name="tags" placeholder="Введите теги" value=<?= $form_values['tags'] ?? '' ?>>
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
                <h3 class="form__error-title">Ошибка</h3>
                <p class="form__error-desc"><?= $form_errors['tags'] ?? '' ?></p>
            </div>
            </div>
        </div>
        </div>
        <?php if (!empty($form_errors)):?>
            <div class="form__invalid-block">
                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                <ul class="form__invalid-list">
                    <?php foreach ($form_errors as $field => $error) : ?>
                        <li class="form__invalid-item"><?= $field_error_codes[$field] ?>. <?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <div class="adding-post__buttons">
        <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
        <a class="adding-post__close" href="#">Закрыть</a>
    </div>
    </form>
</section>
