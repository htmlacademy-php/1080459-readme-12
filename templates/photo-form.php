<section class="adding-post__photo tabs__content <?= ($form_type === 'photo') ? 'tabs__content--active' : '' ?>">
  <h2 class="visually-hidden">Форма добавления фото</h2>
  <form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
    <input type="hidden" id="form-type" name="form-type" value="photo">
    <div class="form__text-inputs-wrapper">
      <div class="form__text-inputs">
        <div class="adding-post__input-wrapper form__input-wrapper">
          <label class="adding-post__label form__label" for="photo-heading">Заголовок <span
              class="form__input-required">*</span></label>
          <div class="form__input-section  <?= !empty($form_errors['heading']) ? 'form__input-section--error' : '' ?>">
            <input class="adding-post__input form__input" id="photo-heading" type="text" name="heading"
              placeholder="Введите заголовок" value=<?= $form_values['heading'] ?? '' ?>>
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об
                ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Ошибка</h3>
              <p class="form__error-desc"><?= $form_errors['heading'] ?? '' ?></p>
            </div>
          </div>
        </div>
        <div class="adding-post__input-wrapper form__input-wrapper">
          <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
          <div
            class="form__input-section  <?= !empty($form_errors['photo-url']) ? 'form__input-section--error' : '' ?>">
            <input class="adding-post__input form__input" id="photo-url" type="text" name="photo-url"
              placeholder="Введите ссылку" value=<?= $form_values['photo-url'] ?? '' ?>>
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об
                ошибке</span></button>
            <div class="form__error-text">
              <h3 class="form__error-title">Ошибка</h3>
              <p class="form__error-desc"><?= $form_errors['photo-url'] ?? '' ?></p>
            </div>
          </div>
        </div>
        <?php $form_errors['tags'] ?? 'form__input-section--error' ?>
        <div class="adding-post__input-wrapper form__input-wrapper">
          <label class="adding-post__label form__label" for="photo-tags">Теги</label>
          <div class="form__input-section  <?= !empty($form_errors['tags']) ? 'form__input-section--error' : '' ?>">
            <input class="adding-post__input form__input" id="photo-tags" type="text" name="tags"
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
    <div class="adding-post__input-file-container form__input-container form__input-container--file">
      <div class="adding-post__input-file-wrapper form__input-file-wrapper">
        <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
          <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="photo-file">
          <div class="form__file-zone-text">
            <span>Перетащите фото сюда</span>
          </div>
        </div>
        <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button"
          type="button">
          <span>Выбрать фото</span>
          <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
            <use xlink:href="#icon-attach"></use>
          </svg>
        </button>
      </div>
      <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">
      </div>
    </div>
    <div class="adding-post__buttons">
      <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
      <a class="adding-post__close" href="#">Закрыть</a>
    </div>
  </form>
</section>
