<div class="post__main">
    <div class="post-link__wrapper">
        <a class="post-link__external" href="<?= $post['content'] ? htmlspecialchars($post['content']) : ''; ?>" title="Перейти по ссылке">
            <div class="post-link__info-wrapper">
                <div class="post-link__info">
                    <h3><?= $post['title'] ? htmlspecialchars($post['title']) : ''; ?></h3>
                    <span><?= $post['content'] ? htmlspecialchars($post['content']) : ''; ?></span>
                </div>
            </div>
        </a>
    </div>
</div>
