<div class="post__main">
  <div class="post-link__wrapper">
    <a class="post-link__external" href="<?= $post['url'] ? htmlspecialchars($post['url']) : ''; ?>"
      title="Перейти по ссылке">
      <div class="post-link__info-wrapper">
        <div class="post-link__info">
          <h3><?= $post['title'] ? htmlspecialchars($post['title']) : ''; ?></h3>
          <span><?= $post['url'] ? htmlspecialchars(remove_url_protocol($post['url'])) : ''; ?></span>
        </div>
      </div>
    </a>
  </div>
</div>
