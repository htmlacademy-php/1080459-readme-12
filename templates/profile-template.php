<main class="page__main page__main--profile">
  <h1 class="visually-hidden">Профиль</h1>
  <div class="profile profile--default">
    <div class="profile__user-wrapper">
      <div class="profile__user user container">
        <div class="profile__user-info user__info">
          <div class="profile__avatar user__avatar">
            <?php if (isset($owner['avatar'])) : ?>
            <img class="profile__picture user__picture" src="img/<?= $owner['avatar'] ?>" alt="Аватар пользователя">
            <?php endif; ?>
          </div>
          <div class="profile__name-wrapper user__name-wrapper">
            <span class="profile__name user__name"><?= $owner['username'] ?? '' ?></span>
            <time class="profile__user-time user__time"
              datetime="<?= $owner['dt_add'] ?? ''?>">
              <?= absolute_time_to_relative($owner['dt_add'], 'на сайте'); ?></time>
          </div>
        </div>
        <div class="profile__rating user__rating">
          <p class="profile__rating-item user__rating-item user__rating-item--publications">
            <span class="user__rating-amount"><?= count($posts) ?? '' ?></span>
            <span
              class="profile__rating-text user__rating-text">
              <?= get_noun_plural_form(count($posts), 'публикация', 'публикации', 'публикаций') ?></span>
          </p>
          <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
            <span class="user__rating-amount"><?= $owner['followers'] ?? '' ?></span>
            <span
              class="profile__rating-text user__rating-text">
              <?= get_noun_plural_form($owner['followers'], 'подписчик', 'подписчика', 'подписчиков') ?></span>
          </p>
        </div>
        <?php if ($user['id'] != $owner['id']) : ?>
        <div class="profile__user-buttons user__buttons">
          <a class="profile__user-button user__button user__button--subscription button button--main"
            href="subscribe.php?id=<?= $owner['id'] ?>"><?= $user['subscribed'] ? 'Отписаться' : 'Подписаться' ?></a>
          <a class="profile__user-button user__button user__button--writing button button--green"
            href="messages.php">Сообщение</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="profile__tabs-wrapper tabs">
      <div class="container">
        <div class="profile__tabs filters">
          <b class="profile__tabs-caption filters__caption">Показать:</b>
          <ul class="profile__tabs-list filters__list tabs__list">
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button
              <?= ($tab == 'posts') ? 'filters__button--active' : '' ?> tabs__item button"
                href="profile.php?id=<?= $owner['id'] ?>&tab=posts">Посты</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button
              <?= ($tab == 'likes') ? 'filters__button--active' : '' ?> tabs__item button"
                href="profile.php?id=<?= $owner['id'] ?>&tab=likes">Лайки</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button
              <?= ($tab == 'subscribes') ? 'filters__button--active' : '' ?> tabs__item button"
                href="profile.php?id=<?= $owner['id'] ?>&tab=subscribes">Подписки</a>
            </li>
          </ul>
        </div>
        <div class="profile__tab-content">
          <section class="profile__posts tabs__content <?= ($tab == 'posts') ? 'tabs__content--active' : '' ?>">
            <h2 class="visually-hidden">Публикации</h2>
            <?php foreach ($posts as $post) : ?>
            <article class="profile__post post post-<?=$post['type_class'];?>">
              <header class="post__header">
                <?php if ($post['repost']) :?>
                <div class="post__author">
                  <a class="post__author-link" href="profile.php?id=<?= $post['original_author_id'] ?? '' ?>"
                    title="Автор">
                    <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                      <img class="post__author-avatar" src="img/<?= $post['original_author_avatar'] ?? '' ?>"
                        alt="Аватар пользователя">
                    </div>
                    <div class="post__info">
                      <b class="post__author-name">Репост: <?= $post['original_author_username'] ?? '' ?></b>
                    </div>
                  </a>
                </div>
                <?php endif; ?>
                <h2><a href="post.php?id=<?=$post['id']?>"><?= htmlspecialchars($post['title']) ?? '' ?></a></h2>
              </header>
              <div class="post__main">
                <?php
                switch ($post['type_class']) :
                    case 'quote':
                        ?>
                        <blockquote>
                          <p><?=htmlspecialchars($post['content']); ?></p>
                          <cite><?=htmlspecialchars($post['quote_author']); ?></cite>
                        </blockquote>
                        <?php
                        break;
                    case 'text':
                        ?>
                        <p><?=htmlspecialchars(truncate_text($post['content']));?></p>
                        <?php if (mb_strlen($post['content']) > 300) :?>
                        <a class="post-text__more-link" href="#">Читать далее</a>
                        <?php endif; ?>
                        <?php
                        break;
                    case 'photo':
                        ?>
                        <div class="post-photo__image-wrapper">
                          <img src="img/<?=$post['img_url'];?>" alt="Фото от пользователя" width="360" height="240">
                        </div>
                        <?php
                        break;
                    case 'link':
                        ?>
                        <div class="post-link__wrapper">
                        <a class="post-link__external" href="<?=$post['content'];?>" title="Перейти по ссылке">
                          <div class="post-link__info-wrapper">
                            <div class="post-link__icon-wrapper">
                              <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                            </div>
                            <div class="post-link__info">
                              <h3><?=htmlspecialchars($post['title']) ?? '';?></h3>
                            </div>
                          </div>
                          <span><?=htmlspecialchars($post['content']);?></span>
                        </a>
                        </div>
                        <?php
                        break;
                    case 'video':
                        ?>
                        <div class="post-video__block">
                        <div class="post-video__preview">
                            <?= embed_youtube_cover($post['youtube_url'] ?? ''); ?>
                        </div>
                        <div class="post-video__control">
                          <button class="post-video__play post-video__play--paused button button--video" type="button">
                            <span class="visually-hidden">Запустить видео</span>
                          </button>
                          <div class="post-video__scale-wrapper">
                            <div class="post-video__scale">
                              <div class="post-video__bar">
                              <div class="post-video__toggle"></div>
                              </div>
                          </div>
                        </div>
                        <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video"
                        type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                        </div>
                        <button class="post-video__play-big button" type="button">
                            <svg class="post-video__play-big-icon" width="27" height="28">
                            <use xlink:href="#icon-video-play-big"></use>
                            </svg>
                            <span class="visually-hidden">Запустить проигрыватель</span>
                        </button>
                        </div>
                <?php endswitch; ?>
              </div>
              <footer class="post__footer">
                <div class="post__indicators">
                  <div class="post__buttons">
                    <a class="post__indicator post__indicator--likes button" href="like.php?id=<?= $post['id'] ?>"
                      title="Лайк">
                      <svg class="post__indicator-icon" width="20" height="17">
                        <use xlink:href="#icon-heart"></use>
                      </svg>
                      <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                        <use xlink:href="#icon-heart-active"></use>
                      </svg>
                      <span><?= $post['likes'] ?? '' ?></span>
                      <span class="visually-hidden">количество лайков</span>
                    </a>
                    <a class="post__indicator post__indicator--repost button" href="repost.php?id=<?= $post['id'] ?>"
                      title="Репост">
                      <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-repost"></use>
                      </svg>
                      <span><?= $post['reposts'] ?? '' ?></span>
                      <span class="visually-hidden">количество репостов</span>
                    </a>
                  </div>
                  <time class="post__time"
                    datetime="<?= $post['dt_add'] ?? '' ?>"><?= absolute_time_to_relative($post['dt_add']); ?></time>
                </div>
                <?php if (isset($post['tags'])) : ?>
                <ul class="post__tags">
                    <?php foreach ($post['tags'] as $tag_name) : ?>
                    <li>
                      <a href="search.php?keywords=<?= '%23' . $tag_name ?>"><?= '#' . $tag_name ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
              </footer>
              <div class="comments">
                <a class="comments__button button" href="#">Показать комментарии</a>
              </div>
            </article>
            <?php endforeach; ?>
          </section>
          <section class="profile__likes tabs__content <?= ($tab == 'likes') ? 'tabs__content--active' : '' ?>">
            <h2 class="visually-hidden">Лайки</h2>
            <ul class="profile__likes-list">
              <?php foreach ($likes as $like) : ?>
              <li class="post-mini <?= $like['type_class'] ? 'post-mini--' . $like['type_class'] : '' ?> post user">
                <div class="post-mini__user-info user__info">
                  <div class="post-mini__avatar user__avatar">
                    <a class="user__avatar-link" href="#">
                      <?php if (isset($like['avatar'])) : ?>
                      <img class="post-mini__picture user__picture" src="img/<?=$like['avatar'];?>"
                        alt="Аватар пользователя">
                      <?php endif; ?>
                    </a>
                  </div>
                  <div class="post-mini__name-wrapper user__name-wrapper">
                    <a class="post-mini__name user__name" href="profile.php?id=<?= $like['user_id'] ?>">
                      <span><?= $like['username']?></span>
                    </a>
                    <div class="post-mini__action">
                      <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                      <span><?= $like['title'] ? htmlspecialchars($like['title']) : '' ?></span>
                    </div>
                  </div>
                </div>
                <div class="post-mini__preview">
                  <a class="post-mini__link" href="post.php?id=<?= $like['post_id']?>" title="Перейти на публикацию">
                    <?php
                    switch ($like['type_class']) :
                        case 'photo':
                            ?>
                            <span class="visually-hidden">Фото</span>
                            <div class="post-mini__image-wrapper">
                            <?php
                            $img_src = ($like['content']) ? ($like['content']) : ($like['img_url']);
                            if (isset($img_src)) : ?>
                              <img class="post-mini__image"src="img/<?= htmlspecialchars($img_src); ?>" alt="Превью публикации" width="109" height="109">
                            <?php endif; ?>
                            </div>
                            <?php
                            break;
                        case 'video':
                            ?>
                            <span class="visually-hidden">Видео</span>
                            <div class="post-mini__image-wrapper">
                            <?= $like['youtube_url'] ? embed_youtube_cover($like['youtube_url']) : '' ?>
                              <span class="post-mini__play-big">
                                <svg class="post-mini__play-big-icon" width="12" height="13">
                                  <use xlink:href="#icon-video-play-big"></use>
                                </svg>
                              </span>
                            </div>
                            <?php
                            break;
                        case 'text':
                            ?>
                            <span class="visually-hidden">Текст</span>
                            <svg class="post-mini__preview-icon" width="20" height="21">
                            <use xlink:href="#icon-filter-text"></use>
                            </svg>
                            <?php
                            break;
                        case 'link':
                            ?>
                            <span class="visually-hidden">Ссылка</span>
                            <svg class="post-mini__preview-icon" width="21" height="18">
                            <use xlink:href="#icon-filter-link"></use>
                            </svg>
                            <?php
                            break;
                        case 'quote':
                            ?>
                            <span class="visually-hidden">Цитата</span>
                            <svg class="post-mini__preview-icon" width="21" height="20">
                            <use xlink:href="#icon-filter-quote"></use>
                            </svg>
                    <?php endswitch; ?>
                  </a>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </section>
          <section
            class="profile__subscriptions tabs__content <?= ($tab == 'subscribes') ? 'tabs__content--active' : '' ?>">
            <h2 class="visually-hidden">Подписки</h2>
            <ul class="profile__subscriptions-list">
              <?php foreach ($subscribes as $subscribe) : ?>
              <li class="post-mini post-mini--photo post user">
                <div class="post-mini__user-info user__info">
                  <div class="post-mini__avatar user__avatar">
                    <a class="user__avatar-link" href="#">
                      <?php if (isset($subscribe['avatar'])) : ?>
                      <img class="post-mini__picture user__picture" src="img/<?= $subscribe['avatar'] ?? '' ?>"
                        alt="Аватар пользователя">
                      <?php endif; ?>
                    </a>
                  </div>
                  <div class="post-mini__name-wrapper user__name-wrapper">
                    <a class="post-mini__name user__name" href="profile.php?id=<?= $subscribe['user_id'] ?? '' ?>">
                      <span><?= $subscribe['username'] ? htmlspecialchars($subscribe['username']) : '' ?></span>
                    </a>
                    <time class="post-mini__time user__additional"
                      datetime="<?= $subscribe['dt_add'] ?? '' ?>">
                      <?= absolute_time_to_relative($subscribe['dt_add'], 'на сайте'); ?></time>
                  </div>
                </div>
                <div class="post-mini__rating user__rating">
                  <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                    <span
                      class="post-mini__rating-amount user__rating-amount"><?= $subscribe['post_count'] ?? '' ?></span>
                    <span class="post-mini__rating-text user__rating-text">
                      <?= get_noun_plural_form($subscribe['post_count'], 'публикация', 'публикации', 'публикаций') ?>
                    </span>
                  </p>
                </div>
                <div class="post-mini__user-buttons user__buttons">
                  <a class="post-mini__user-button user__button user__button--subscription button
                    <?= $subscribe['user_subscribe'] ? 'button--quartz' : 'button--main' ?>"
                    href="subscribe.php?id=<?= $subscribe['user_id'] ?>">
                    <?= $subscribe['user_subscribe'] ? 'Отписаться' : 'Подписаться' ?></a>
                </div>
              </li>
              <?php endforeach; ?>
            </ul>
          </section>
        </div>
      </div>
    </div>
  </div>
</main>
