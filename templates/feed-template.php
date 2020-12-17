<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">
                    <?php foreach($posts as $post): ?>
                    <article class="feed__post post post-<?=$post['type_class'];?>">
                        <header class="post__header post__author">
                            <a class="post__author-link" href="profile.php?id=<?= $post['author_id'] ?? ''; ?>" title="Автор">
                                <div class="post__avatar-wrapper">
                                <?php if (isset($post['avatar'])) : ?>
                                    <img class="post__author-avatar" src="img/<?= $post['avatar']; ?>" alt="Аватар пользователя" width="60" height="60">
                                <?php endif; ?>
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?= $post['username']; ?></b>
                                    <span class="post__time">
                                        <?= absolute_time_to_relative($post['dt_add']); ?>
                                    </span>
                                </div>
                            </a>
                        </header>
                        <div class="post__main">
                            <h2><a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ? htmlspecialchars($post['title']) : ''; ?></a></h2>
                            <?php switch($post['type_class']): case 'quote': ?>
                                <blockquote>
                                    <p><?= $post['content'] ? htmlspecialchars($post['content']) : ''; ?></p>
                                    <cite><?= $post['quote_author'] ? htmlspecialchars($post['quote_author']) : ''; ?></cite>
                                </blockquote>
                            <?php break; case 'text': ?>
                                <p><?= $post['content'] ? htmlspecialchars(truncate_text($post['content'])) : ''; ?></p>
                                <?php if (mb_strlen($post['content']) > 300):?>
                                    <a class="post-text__more-link" href="#">Читать далее</a>
                                <?php endif; ?>
                            <?php break; case 'photo': ?>
                                <div class="post-photo__image-wrapper">
                                    <?php if (isset($post['content'])) : ?>
                                        <img src="img/<?= htmlspecialchars($post['content']); ?>" alt="Фото от пользователя" width="360" height="240">
                                    <?php endif; ?>
                                </div>
                            <?php break; case 'link': ?>
                            <div class="post__main">
                                <div class="post-link__wrapper">
                                    <a class="post-link__external" href="<?= $post['content'] ? htmlspecialchars($post['content']) : ''; ?>" title="Перейти по ссылке">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= $post['title'] ? htmlspecialchars($post['title']) : ''; ?></h3>
                                            <span><?= $post['content'] ? htmlspecialchars($post['content']) : ''; ?></span>
                                        </div>
                                        <svg class="post-link__arrow" width="11" height="16">
                                            <use xlink:href="#icon-arrow-right-ad"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <?php break; case 'video': ?>
                                <div class="post-video__block">
                                    <div class="post-video__preview">
                                        <?
                                        // =embed_youtube_cover(/* вставьте ссылку на видео */);
                                        ?>
                                        <img src="img/coast-medium.jpg" alt="Превью к видео" width="360" height="188">
                                        </div>
                                        <a href="post-details.php" class="post-video__play-big button">
                                            <svg class="post-video__play-big-icon" width="14" height="14">
                                                <use xlink:href="#icon-video-play-big"></use>
                                            </svg>
                                            <span class="visually-hidden">Запустить проигрыватель</span>
                                        </a>
                                    </div>
                                    <div class="post-video__control">
                                        <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
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
                        <footer class="post__footer">
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="like.php?id=<?= $post['id'] ?? ''; ?>" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?= $post['likes'] ?? '' ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button" href="post.php?id=<?= $post['id'] ?? ''; ?>" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?= $post['comments'] ?? ''?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                    <a class="post__indicator post__indicator--repost button" href="repost.php?id=<?= $post['id'] ?? ''; ?>" title="Репост">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-repost"></use>
                                        </svg>
                                        <span><?= $post['reposts'] ?? ''?></span>
                                        <span class="visually-hidden">количество репостов</span>
                                    </a>
                                </div>
                            </div>
                            <?php if (isset($post['tags'])): ?>
                                <ul class="post__tags">
                                    <?php foreach ($post['tags']  as $tag_name): ?>
                                        <li>
                                            <a href="search.php?keywords=<?= '%23'.htmlspecialchars($tag_name) ?>"><?= '#'.htmlspecialchars($tag_name) ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </footer>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <ul class="feed__filters filters">
                <li class="feed__filters-item filters__item">
                    <a class="filters__button filters__button--active" href="feed.php">
                    <span>Все</span>
                    </a>
                </li>
                <?php foreach($content_types as $content_type): ?>
                    <li class="feed__filters-item filters__item">
                        <a class="button filters__button filters__button--<?=$content_type['type_class'];?> <?= ($filter == $content_type['type_class']) ? 'filters__button--active' : ''?>" href="feed.php?filter=<?=$content_type['type_class'];?>">
                            <span class="visually-hidden"><?=$content_type['type_name'];?></span>
                            <svg class="filters__icon" width="22" height="21">
                                <use xlink:href="#icon-filter-<?=$content_type['type_class'];?>"></use>
                            </svg>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
                Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
            </p>
            <a class="promo__link" href="#">
                Подробнее
            </a>
            </article>
            <article class="promo__block promo__block--technomart">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
                Товары будущего уже сегодня в онлайн-сторе Техномарт!
            </p>
            <a class="promo__link" href="#">
                Перейти в магазин
            </a>
            </article>
            <article class="promo__block">
            <h2 class="visually-hidden">Рекламный блок</h2>
            <p class="promo__text">
                Здесь<br> могла быть<br> ваша реклама
            </p>
            <a class="promo__link" href="#">
                Разместить
            </a>
            </article>
        </aside>
    </div>
</main>
