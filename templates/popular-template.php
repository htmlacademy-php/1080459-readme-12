<main class="page__main page__main--popular">
  <div class="container">
    <h1 class="page__title page__title--popular">Популярное</h1>
  </div>
  <div class="popular container">
    <div class="popular__filters-wrapper">
      <div class="popular__sorting sorting">
        <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
        <ul class="popular__sorting-list sorting__list">
          <li class="sorting__item sorting__item--popular">
            <a class="sorting__link sorting__link--active"
              href="popular.php?sort=view_count<?= $filter ? '&filter=' . $filter : '' ?>">
              <span>Популярность</span>
              <svg class="sorting__icon" width="10" height="12">
                <use xlink:href="#icon-sort"></use>
              </svg>
            </a>
          </li>
          <li class="sorting__item">
            <a class="sorting__link" href="popular.php?sort=likes<?= $filter ? '&filter=' . $filter : '' ?>">
              <span>Лайки</span>
              <svg class="sorting__icon" width="10" height="12">
                <use xlink:href="#icon-sort"></use>
              </svg>
            </a>
          </li>
          <li class="sorting__item">
            <a class="sorting__link" href="popular.php?sort=dt_add<?= $filter ? '&filter=' . $filter : '' ?>">
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
            <a class="filters__button filters__button--ellipse filters__button--all
            <?= ($filter == '') ? 'filters__button--active' : '' ?>" href="#">
              <span>Все</span>
            </a>
          </li>
          <?php foreach ($content_types as $content_type) : ?>
          <li class="popular__filters-item filters__item">
            <a class="button filters__button
              <?= $content_type['type_class'] ? 'filters__button--' . $content_type['type_class'] : '' ?>
              <?= ($filter == $content_type['type_class']) ? 'filters__button--active' : ''?>"
              href="popular.php?filter=<?=$content_type['type_class'] ?>">
              <span class="visually-hidden"><?= $content_type['type_name'] ?></span>
              <svg class="filters__icon" width="22" height="21">
                <use xlink:href="#icon-filter-<?= $content_type['type_class'] ?? '' ?>"></use>
              </svg>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php if (isset($posts)) : ?>
        <div class="popular__posts">
        <?php foreach ($posts as $post) : ?>
          <article class="popular__post post <?= $post['type_class'] ? 'post-' . $post['type_class'] : '' ?>">
          <header class="post__header">
            <h2><a href="post.php?id=<?= $post['id'] ?? '' ?>">
            <?= $post['title'] ? htmlspecialchars($post['title']) : '' ?></a>
            </h2>
          </header>
        <div class="post__main">
            <?php
            switch ($post['type_class']) :
                case 'quote':
                    ?>
                    <blockquote>
                      <p><?= $post['content'] ? htmlspecialchars($post['content']) : '' ?></p>
                      <cite><?= $post['quote_author'] ? htmlspecialchars($post['quote_author']) : '' ?></cite>
                    </blockquote>
                    <?php
                    break;
                case 'text':
                    ?>
                    <p><?= $post['content'] ? htmlspecialchars(truncate_text($post['content'])) : '' ?></p>
                    <?php if (mb_strlen($post['content']) > 300) :?>
                    <a class="post-text__more-link" href="#">Читать далее</a>
                    <?php endif; ?>
                    <?php
                    break;
                case 'photo':
                    ?>
                    <div class="post-photo__image-wrapper">
                    <?php if (isset($post['content'])) : ?>
                        <img src="img/<?= htmlspecialchars($post['content']) ?>" alt="Фото от пользователя" width="360"
                        height="240">
                    <?php endif; ?>
                    </div>
                    <?php
                    break;
                case 'link':
                    ?>
                    <div class="post-link__wrapper">
                      <a class="post-link__external" href="
                      <?= $post['content'] ? htmlspecialchars($post['content']) : '' ?>"
                        title="Перейти по ссылке">
                        <div class="post-link__info-wrapper">
                          <div class="post-link__icon-wrapper">
                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                          </div>
                          <div class="post-link__info">
                            <h3><?= $post['title'] ? htmlspecialchars($post['title']) : '' ?></h3>
                          </div>
                        </div>
                        <span><?= $post['content'] ? htmlspecialchars($post['content']) : '' ?></span>
                      </a>
                    </div>
                    <?php
                    break;
                case 'video':
                    ?>
                    <div class="post-video__block">
                      <div class="post-video__preview">
                      <?= $post['youtube_url'] ? embed_youtube_cover($post['youtube_url']) : ''; ?>
                      </div>
                      <a href="post-details.php" class="post-video__play-big button">
                      <svg class="post-video__play-big-icon" width="14" height="14">
                        <use xlink:href="#icon-video-play-big"></use>
                      </svg>
                      <span class="visually-hidden">Запустить проигрыватель</span>
                      </a>
                    </div>
            <?php endswitch ?>
        </div>
        <footer class="post__footer">
          <div class="post__author">
            <a class="post__author-link" href="profile.php?id=<?= $post['author_id'] ?? '' ?>" title="Автор">
              <div class="post__avatar-wrapper">
                <?php if (isset($post['avatar'])) : ?>
                <img class="post__author-avatar" src="img/<?=$post['avatar']?>" alt="Аватар пользователя">
                <?php endif; ?>
              </div>
              <div class="post__info">
                <b class="post__author-name"><?= $post['username'] ? htmlspecialchars($post['username']) : '' ?></b>
                <time class="post__time" datetime="<?= $post['dt_add'] ?? '' ?>"
                  title="<?= date_create_from_format('Y-m-d H:i:s', $post['dt_add'])->format('d.m.Y H:i'); ?>">
                  <?= absolute_time_to_relative($post['dt_add']); ?></time>
              </div>
            </a>
          </div>
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
              <a class="post__indicator post__indicator--comments button" href="post.php?id=<?= $post['id'] ?>"
                title="Комментарии">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-comment"></use>
                </svg>
                <span><?= $post['comments'] ?? '' ?></span>
                <span class="visually-hidden">количество комментариев</span>
              </a>
            </div>
          </div>
        </footer>
      </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if ($total_posts > $page_limit) :?>
        <div class="popular__page-links">
        <?php if ($page_number > 1) :?>
            <a class="popular__page-link popular__page-link--prev button button--gray"
            href="popular.php?page=<?= $page_number - 1?><?= $filter ? '&filter=' . $filter : ''?><?= $sort ? '&sort=' . $sort : ''?>">
            Предыдущая страница
            </a>
        <?php endif; ?>
        <?php if ($page_number < $total_posts / $page_limit) :?>
            <a class="popular__page-link popular__page-link--next button button--gray"
            href="popular.php?page=<?= $page_number + 1?><?= $filter ? '&filter=' . $filter : ''?><?= $sort ? '&sort=' . $sort : ''?>">
            Следующая страница
            </a>
        <?php endif; ?>
        </div>
    <?php endif; ?>
  </div>
</main>
