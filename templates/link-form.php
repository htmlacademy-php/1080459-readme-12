<section class="adding-post__link tabs__content <?= ($form_type == 'link') ? 'tabs__content--active' : '' ?>">
  <h2 class="visually-hidden">Форма добавления ссылки</h2>
  <form class="adding-post__form form" action="#" method="post">
    <input type="hidden" id="form-type" name="form-type" value="link">
    <div class="form__text-inputs-wrapper">
      <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper">
          <label class="adding-post__label form__label" for="link-heading">Заголовок <span
              class="form__input-required">*</span></label>
          <div class="form__input-section  <?= !empty($form_errors['heading']) ? 'form__input-section--error' : '' ?>">
            <input class="adding-post__input form__input" id="link-heading" type="text" name="heading"
              placeholder="Введите заголовок" value=<?= $form_values['heading'] ?? '' ?>>
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об
                ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Ошибка</h3>
              <p class="form__error-desc"><?= $form_errors['heading'] ?? '' ?></p>
            </div>
          </div>
        </div>
        <div class="adding-post__textarea-wrapper form__input-wrapper">
          <label class="adding-post__label form__label" for="post-link">Ссылка <span
              class="form__input-required">*</span></label>
          <div class="form__input-section  <?= !empty($form_errors['link-url']) ? 'form__input-section--error' : '' ?>">
            <input class="adding-post__input form__input" id="post-link" type="text" name="link-url"
              placeholder="Введите ссылку" value=<?= $form_values['link-url'] ?? ''?>>
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об
                ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Ошибка</h3>
              <p class="form__error-desc"><?= $form_errors['link-url'] ?? ''?></p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
          <label class="adding-post__label form__label" for="link-tags">Теги</label>
          <div class="form__input-section  <?= !empty($form_errors['tags']) ? 'form__input-section--error' : '' ?>">
            <input class="adding-post__input form__input" id="link-tags" type="text" name="tags"
              placeholder="Введите теги" value=<?= $form_values['tags'] ?? '' ?>>
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об
                ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Ошибка</h3>
              <p class="form__error-desc"><?= $form_errors['tags'] ?? '' ?></p>
            </div>
          </div>
        </div>
      </div>
      <?php if (!empty($form_errors)) :?>
      <div class="form__invalid-block">
        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
        <ul class="form__invalid-list">
          <?php foreach ($form_errors as $field => $error) : ?>
          <li class="form__invalid-item"><?= $field_error_codes[$field] ?> . <?= $error ?></li>
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
