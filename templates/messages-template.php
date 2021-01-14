<main class="page__main page__main--messages">
  <h1 class="visually-hidden">Личные сообщения</h1>
  <section class="messages tabs">
  <h2 class="visually-hidden">Сообщения</h2>
  <?php if (isset($dialogs)) :?>
    <div class="messages__contacts">
      <ul class="messages__contacts-list tabs__list">
        <?php foreach ($dialogs as $dialog_id => $dialog) : ?>
          <li class="messages__contacts-item">
            <a class="messages__contacts-tab
            <?= ($active_dialog_id === $dialog_id) ? 'messages__contacts-tab--active tabs__item--active' : '' ?>
            tabs__item " href="messages.php?id=<?= $dialog_id ?>">
              <div class="messages__avatar-wrapper">
              <?php if (isset($dialog['avatar'])) : ?>
                <img class="messages__avatar" src="img/<?= $dialog['avatar']?>" alt="Аватар пользователя">
              <?php endif; ?>
              </div>
              <div class="messages__info">
                <span class="messages__contact-name">
                  <?= $dialog['username']?>
                </span>
                <div class="messages__preview">
                  <p class="messages__preview-text">
                  <?= $dialog['content'] ?? '' ?>
                  </p>
                  <?php if (isset($dialog['last_message'])) : ?>
                    <time class="messages__preview-time" datetime="<?= $dialog['last_message'] ?>">
                        <?= absolute_time_to_relative($dialog['last_message'], 'назад') ?>
                    </time>
                  <?php endif; ?>
                </div>
              </div>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="messages__chat">
      <div class="messages__chat-wrapper">
      <?php foreach ($dialogs as $dialog_id => $dialog) : ?>
        <ul class="messages__list tabs__content
            <?= ($active_dialog_id === $dialog_id) ? 'tabs__content--active' : '' ?>">
            <?php foreach ($dialog['messages'] as $message) : ?>
            <li class="messages__item <?= ($user['id'] === $message['sender_id']) ? 'messages__item--my' : '' ?>">
                  <div class="messages__info-wrapper">
                  <div class="messages__item-avatar">
                      <a class="messages__author-link" href="#">
                      <?php $first_sender = ($user['id'] === $message['sender_id']) ? $user : $dialog; ?>
                      <?php if (isset($first_sender['avatar'])) : ?>
                      <img class="messages__avatar"
                      src="img/<?= ($user['id'] === $message['sender_id']) ? $user['avatar'] : $dialog['avatar']?>"
                      alt="Аватар пользователя">
                      <?php endif; ?>
                      </a>
                  </div>
                  <div class="messages__item-info">
                      <a class="messages__author" href="#">
                      <?= ($user['id'] === $message['sender_id']) ? $user['name'] : $dialog['username']?>
                      </a>
                      <?php if (isset($dialog['last_message'])) : ?>
                        <time class="messages__time" datetime="<?= $message['dt_add'] ?>">
                                <?= absolute_time_to_relative($message['dt_add'], 'назад'); ?>
                        </time>
                      <?php endif; ?>
                  </div>
                  </div>
                  <p class="messages__text">
                    <?= $message['content'] ?? '' ?>
                  </p>
              </li>
            <?php endforeach; ?>
        </ul>
      <?php endforeach; ?>
        <div class="comments">
          <form class="comments__form form" action="#" method="post">
            <input type="hidden" name="receiver-id" value="<?= $active_dialog_id ?>">
              <div class="comments__my-avatar">
              <?php if (isset($user['avatar'])) : ?>
                <img class="comments__picture" src="img/<?= $user['avatar'] ?>" alt="Аватар пользователя">
              <?php endif; ?>
              </div>
              <div class="form__input-section <?= !empty($message_errors) ? 'form__input-section--error' : '' ?>">
                <textarea class="comments__textarea form__textarea form__input" name="message"
                placeholder="Ваше сообщение"></textarea>
              <label class="visually-hidden">Ваше сообщение</label>
              <?php if (!empty($message_errors)) : ?>
                <button class="form__error-button button" type="button">!</button>
                <div class="form__error-text">
                  <h3 class="form__error-title">Ошибка валидации</h3>
                  <p class="form__error-desc"><?= $message_errors['receiver-id'] ?? $message_errors['message'] ?></p>
                </div>
              <?php endif; ?>
                </div>
                <button class="comments__submit button button--green" type="submit">Отправить</button>
                </form>
            </div>
        </div>
  <?php endif; ?>
    </section>
</main>
