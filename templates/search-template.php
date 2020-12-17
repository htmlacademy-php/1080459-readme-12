<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска</h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= $keywords ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <div class="container">
                <div class="search__content">
                    <?php foreach($posts as $post): ?>
                        <article class="search__post post <?= $post['type_class'] ? 'post-'.$post['type_class'] : '' ?>">
                            <header class="post__header post__author">
                                <a class="post__author-link" href="profile.php?id=<?= $post['author_id'] ?? '' ?>" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <?php if (isset($post['avatar'])) : ?>
                                            <img class="post__author-avatar" src="img/<?= $post['avatar'] ?>" alt="Аватар пользователя" width="60" height="60">
                                        <?php endif; ?>
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= ($post['username']) ? htmlspecialchars($post['username']) : '' ?></b>
                                        <?php if (isset($post['dt_add'])) : ?>
                                            <time class="post__time" datetime="<?= $post['dt_add'] ?>"><?= absolute_time_to_relative($post['dt_add'], 'назад'); ?></time>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </header>
                            <h2><a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ? htmlspecialchars($post['title']) : '' ?></a></h2>
                            <div class="post__main">
                                <?php switch($post['type_class']): case 'quote': ?>
                                    <blockquote>
                                        <p><?= ($post['content']) ? htmlspecialchars($post['content']) : '' ?></p>
                                        <cite><?= $post['quote_author'] ? htmlspecialchars($post['quote_author']) : '' ?></cite>
                                    </blockquote>
                                <?php break; case 'text': ?>
                                    <p><?= $post['content'] ? htmlspecialchars(truncate_text($post['content'])) : '' ?></p>
                                    <?php if (mb_strlen($post['content']) > 300):?>
                                        <a class="post-text__more-link" href="#">Читать далее</a>
                                    <?php endif; ?>
                                <?php break; case 'photo': ?>
                                    <div class="post-photo__image-wrapper">
                                        <?php if (isset($post['content'])) : ?>
                                            <img src="img/<?= htmlspecialchars($post['content']) ?>" alt="Фото от пользователя" width="360" height="240">
                                        <?php endif; ?>
                                    </div>
                                <?php break; case 'link': ?>
                                    <div class="post-link__wrapper">
                                    <a class="post-link__external" href="<?= $post['content'] ? htmlspecialchars($post['content']) : '' ?>" title="Перейти по ссылке">
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
                                <?php break; case 'video': ?>
                                    <div class="post-video__block">
                                        <div class="post-video__preview">
                                            <img src="<?= $post['youtube_url'] ?? '' ?>" alt="Превью к видео" width="760" height="396">
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
                                            <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                                        </div>
                                        <button class="post-video__play-big button" type="button">
                                            <svg class="post-video__play-big-icon" width="27" height="28">
                                                <use xlink:href="#icon-video-play-big"></use>
                                            </svg>
                                            <span class="visually-hidden">Запустить проигрыватель</span>
                                        </button>
                                    </div>
                                <?php endswitch ?>
                            </div>
                            <footer class="post__footer post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="like.php?id=<?= $post['id'] ?? '' ?>" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?= $post['likes'] ?? '' ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button" href="post.php?id=<?= $post['id'] ?? '' ?>" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?= $post['comments'] ?? '' ?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </footer>
                        </article>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </section>
</main>
