<div class="post-details__image-wrapper post-quote">
    <div class="post__main">
        <blockquote>
            <p>
                <?= $post['content'] ? htmlspecialchars($post['content']) : ''; ?>
            </p>
            <cite><?= $post['quote_author'] ? htmlspecialchars($post['quote_author']) : ''; ?></cite>
        </blockquote>
    </div>
</div>
